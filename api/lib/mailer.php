<?php
/**
 * Minimal SMTP sender (implicit TLS / port 465 with AUTH LOGIN) so the API
 * can send notification emails on shared hosting without Composer packages.
 * Credentials come from the 'mail' block in config/config.php.
 */

function mail_config(): array
{
    static $mail = null;
    if ($mail === null) {
        $config = require __DIR__ . '/../config/config.php';
        $mail = $config['mail'] ?? [];
    }
    return $mail;
}

/** Sends one HTML email (with a plain-text fallback). Throws on failure. */
function send_mail(string $to, string $subject, string $html, ?string $replyTo = null): void
{
    $c = mail_config();
    if (empty($c['host']) || empty($c['username'])) {
        throw new RuntimeException('Mail is not configured');
    }

    $fp = stream_socket_client(
        'ssl://' . $c['host'] . ':' . ($c['port'] ?? 465),
        $errno, $errstr, 20, STREAM_CLIENT_CONNECT,
        stream_context_create(['ssl' => ['peer_name' => $c['host']]])
    );
    if (!$fp) {
        throw new RuntimeException("SMTP connect failed: {$errstr}");
    }
    stream_set_timeout($fp, 20);

    $read = function () use ($fp): string {
        $out = '';
        while (($line = fgets($fp, 515)) !== false) {
            $out .= $line;
            if (isset($line[3]) && $line[3] === ' ') break; // last line of reply
        }
        return $out;
    };
    $cmd = function (string $line, array $expect) use ($fp, $read): void {
        fwrite($fp, $line . "\r\n");
        $reply = $read();
        if (!in_array((int)substr($reply, 0, 3), $expect, true)) {
            throw new RuntimeException('SMTP error: ' . trim($reply));
        }
    };

    $from     = $c['from_address'] ?? $c['username'];
    $fromName = $c['from_name'] ?? 'Poseidon Vet';
    $boundary = 'b' . bin2hex(random_bytes(8));
    $text     = trim(html_entity_decode(strip_tags(preg_replace('/<(br|\/p|\/tr|\/h\d)>/i', "\n", $html)), ENT_QUOTES, 'UTF-8'));

    $headers = [
        'Date: ' . date('r'),
        'From: ' . mb_encode_mimeheader($fromName, 'UTF-8') . " <{$from}>",
        "To: <{$to}>",
        'Subject: ' . mb_encode_mimeheader($subject, 'UTF-8'),
        'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . substr(strrchr($from, '@'), 1) . '>',
        'MIME-Version: 1.0',
        "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
    ];
    if ($replyTo) {
        $headers[] = "Reply-To: <{$replyTo}>";
    }
    $body = "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
          . chunk_split(base64_encode($text))
          . "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
          . chunk_split(base64_encode($html))
          . "--{$boundary}--";

    try {
        $greeting = $read();
        if ((int)substr($greeting, 0, 3) !== 220) {
            throw new RuntimeException('SMTP greeting: ' . trim($greeting));
        }
        $cmd('EHLO ' . (gethostname() ?: 'localhost'), [250]);
        $cmd('AUTH LOGIN', [334]);
        $cmd(base64_encode($c['username']), [334]);
        $cmd(base64_encode($c['password']), [235]);
        $cmd("MAIL FROM:<{$from}>", [250]);
        $cmd("RCPT TO:<{$to}>", [250, 251]);
        $cmd('DATA', [354]);
        // Dot-stuff lines starting with '.' per RFC 5321.
        $cmd(preg_replace('/^\./m', '..', implode("\r\n", $headers) . "\r\n\r\n" . $body) . "\r\n.", [250]);
        $cmd('QUIT', [221]);
    } finally {
        fclose($fp);
    }
}

/** Escapes user input for inclusion in an email body. */
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/** Wraps content in the clinic's simple branded email layout. */
function email_layout(string $title, string $inner): string
{
    return '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;color:#1e293b">'
        . '<div style="background:#0d9488;color:#fff;padding:20px 24px;border-radius:8px 8px 0 0">'
        . '<strong style="font-size:18px">Poseidon Vet</strong></div>'
        . '<div style="border:1px solid #e2e8f0;border-top:0;padding:24px;border-radius:0 0 8px 8px">'
        . '<h2 style="margin-top:0;font-size:20px">' . e($title) . '</h2>' . $inner
        . '<p style="color:#64748b;font-size:13px;margin-top:24px">Poseidon Veterinary Clinic · Langata, Nairobi · +254 20 2470436</p>'
        . '</div></div>';
}

/** Renders label/value pairs as a table. Values are escaped. */
function email_rows(array $rows): string
{
    $html = '<table style="width:100%;border-collapse:collapse;font-size:14px">';
    foreach ($rows as $label => $value) {
        if ($value === null || $value === '') continue;
        $html .= '<tr><td style="padding:6px 0;color:#64748b;width:140px;vertical-align:top">' . e($label)
               . '</td><td style="padding:6px 0">' . nl2br(e((string)$value)) . '</td></tr>';
    }
    return $html . '</table>';
}

/**
 * Basic abuse protection for public form endpoints: rejects bots that fill
 * the hidden 'website' honeypot field, and caps each IP at $max submissions
 * per hour.
 */
function guard_public_form(array $body, string $bucket, int $max = 5): void
{
    if (!empty($body['website'])) {
        json_response(['ok' => true]); // pretend success to bots
    }
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $file = sys_get_temp_dir() . '/pv_rate_' . $bucket . '_' . md5($ip);
    $now  = time();
    $hits = array_filter(
        is_file($file) ? (json_decode((string)file_get_contents($file), true) ?: []) : [],
        fn($t) => $t > $now - 3600
    );
    if (count($hits) >= $max) {
        json_error('Too many requests — please call us instead.', 429);
    }
    $hits[] = $now;
    file_put_contents($file, json_encode(array_values($hits)));
}

function valid_email(?string $email): bool
{
    return (bool)filter_var((string)$email, FILTER_VALIDATE_EMAIL);
}
