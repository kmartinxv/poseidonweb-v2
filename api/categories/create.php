<?php
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/slug.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['name']);

$slug = unique_slug('categories', $body['name']);
$stmt = db()->prepare('INSERT INTO categories (name, slug, sort_order) VALUES (?, ?, ?)');
$stmt->execute([trim($body['name']), $slug, (int)($body['sort_order'] ?? 0)]);

json_response(['id' => (int)db()->lastInsertId(), 'slug' => $slug], 201);
