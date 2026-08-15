<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

$body = json_body();
require_fields($body, ['email', 'password']);

$stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM staff WHERE email = ?');
$stmt->execute([trim($body['email'])]);
$staff = $stmt->fetch();

if (!$staff || !password_verify($body['password'], $staff['password_hash'])) {
    json_error('Incorrect email or password', 401);
}

start_session();
session_regenerate_id(true);
$_SESSION['staff_id'] = $staff['id'];

unset($staff['password_hash']);
json_response(['staff' => $staff]);
