<?php
/**
 * Public: appointment request from the website booking form. Emails the
 * clinic the full request and sends the client an acknowledgement.
 */
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

$body = json_body();
guard_public_form($body, 'appt');
require_fields($body, ['service', 'date', 'time', 'name', 'phone', 'email']);
if (!valid_email($body['email'])) {
    json_error('Please enter a valid email address', 422);
}

$field = fn(string $k, int $max = 200) => mb_substr(trim((string)($body[$k] ?? '')), 0, $max);
$name  = $field('name', 100);
$email = $field('email', 160);

$details = [
    'Service'     => $field('service'),
    'Doctor'      => $field('vet', 100) ?: 'No preference',
    'Date'        => $field('date', 20),
    'Time'        => $field('time', 20),
    'Branch'      => $field('branch', 60),
    'Coming from' => $field('clientArea', 100),
];
$client = [
    'Name'    => $name,
    'Phone'   => $field('phone', 30),
    'Email'   => $email,
    'Pet'     => $field('pet', 100),
    'Pet age' => $field('petAge', 40),
    'Notes'   => $field('notes', 1000),
];

$clinicTo = mail_config()['notify_to'] ?? 'info@poseidonvet.com';

send_mail(
    $clinicTo,
    "New appointment request: {$details['Service']} on {$details['Date']} {$details['Time']}",
    email_layout('New appointment request', email_rows($details) . '<hr style="border:0;border-top:1px solid #e2e8f0;margin:16px 0">' . email_rows($client)),
    $email
);

// The client copy is best-effort: the clinic already has the request.
try {
    send_mail(
        $email,
        'We received your appointment request — Poseidon Vet',
        email_layout('Thank you, ' . $name . '!',
            '<p>We have received your appointment request. Our team will call or email you shortly to confirm the time.</p>'
            . email_rows($details)
            . '<p>Need to change something? Reply to this email or call us on +254 20 2470436.</p>'),
        $clinicTo
    );
} catch (Throwable $e) {
    error_log('Appointment client email failed: ' . $e->getMessage());
}

json_response(['ok' => true]);
