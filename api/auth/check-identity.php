<?php
require_once __DIR__ . '/../lib/bootstrap.php';

/**
 * Used by the unified /login page to route people to the right flow before
 * they authenticate: staff sign in with a password, clients with an OTP.
 * Only ever called with an email (phone numbers can't be staff accounts —
 * see schema), and only reveals whether that email belongs to a staff
 * account, nothing else.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

$body = json_body();
require_fields($body, ['identifier']);

$stmt = db()->prepare('SELECT id FROM staff WHERE email = ?');
$stmt->execute([trim($body['identifier'])]);

json_response(['is_staff' => (bool) $stmt->fetch()]);
