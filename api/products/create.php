<?php
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/slug.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['name', 'price']);

$slug = unique_slug('products', $body['name']);

$stmt = db()->prepare('INSERT INTO products
    (name, slug, category_id, price, old_price, description, image, rating, reviews, badge, in_stock)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([
    trim($body['name']),
    $slug,
    !empty($body['category_id']) ? (int)$body['category_id'] : null,
    (float)$body['price'],
    isset($body['old_price']) && $body['old_price'] !== '' ? (float)$body['old_price'] : null,
    $body['description'] ?? null,
    $body['image'] ?? null,
    isset($body['rating']) ? (float)$body['rating'] : 5.0,
    isset($body['reviews']) ? (int)$body['reviews'] : 0,
    $body['badge'] ?? null,
    isset($body['in_stock']) ? (int)(bool)$body['in_stock'] : 1,
]);

json_response(['id' => (int)db()->lastInsertId(), 'slug' => $slug], 201);
