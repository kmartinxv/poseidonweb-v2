<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['id', 'name', 'price']);

$stmt = db()->prepare('UPDATE products SET
    name = ?, category_id = ?, price = ?, old_price = ?, description = ?,
    image = ?, rating = ?, reviews = ?, badge = ?, in_stock = ?
    WHERE id = ?');
$stmt->execute([
    trim($body['name']),
    !empty($body['category_id']) ? (int)$body['category_id'] : null,
    (float)$body['price'],
    isset($body['old_price']) && $body['old_price'] !== '' ? (float)$body['old_price'] : null,
    $body['description'] ?? null,
    $body['image'] ?? null,
    isset($body['rating']) ? (float)$body['rating'] : 5.0,
    isset($body['reviews']) ? (int)$body['reviews'] : 0,
    $body['badge'] ?? null,
    isset($body['in_stock']) ? (int)(bool)$body['in_stock'] : 1,
    (int)$body['id'],
]);

json_response(['ok' => true]);
