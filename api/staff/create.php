<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_admin();

$body = json_body();
require_fields($body, ['name', 'email', 'password']);

if (strlen($body['password']) < 8) {
    json_error('Password must be at least 8 characters', 422);
}

$role = in_array($body['role'] ?? 'editor', ['admin', 'editor'], true) ? $body['role'] : 'editor';

try {
    $stmt = db()->prepare('INSERT INTO staff (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        trim($body['name']),
        trim($body['email']),
        password_hash($body['password'], PASSWORD_DEFAULT),
        $role,
    ]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        json_error('A staff account with that email already exists', 409);
    }
    throw $e;
}

json_response(['id' => (int)db()->lastInsertId()], 201);
