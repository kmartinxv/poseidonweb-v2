<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['id']);

$stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
$stmt->execute([(int)$body['id']]);

json_response(['ok' => true]);
