<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['id', 'name']);

$stmt = db()->prepare('UPDATE categories SET name = ?, sort_order = ? WHERE id = ?');
$stmt->execute([trim($body['name']), (int)($body['sort_order'] ?? 0), (int)$body['id']]);

json_response(['ok' => true]);
