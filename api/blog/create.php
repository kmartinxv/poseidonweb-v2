<?php
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/slug.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
$staff = require_staff();

$body = json_body();
require_fields($body, ['title', 'body']);

$slug = unique_slug('blog_posts', $body['title']);

$stmt = db()->prepare('INSERT INTO blog_posts
    (slug, title, excerpt, category, author, author_avatar, image, body, tags, read_time, published)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([
    $slug,
    trim($body['title']),
    $body['excerpt'] ?? null,
    $body['category'] ?? null,
    $body['author'] ?? $staff['name'],
    $body['author_avatar'] ?? null,
    $body['image'] ?? null,
    $body['body'],
    $body['tags'] ?? null,
    $body['read_time'] ?? null,
    isset($body['published']) ? (int)(bool)$body['published'] : 1,
]);

json_response(['id' => (int)db()->lastInsertId(), 'slug' => $slug], 201);
