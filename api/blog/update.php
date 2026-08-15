<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['id', 'title', 'body']);

$stmt = db()->prepare('UPDATE blog_posts SET
    title = ?, excerpt = ?, category = ?, author = ?, author_avatar = ?,
    image = ?, body = ?, tags = ?, read_time = ?, published = ?
    WHERE id = ?');
$stmt->execute([
    trim($body['title']),
    $body['excerpt'] ?? null,
    $body['category'] ?? null,
    $body['author'] ?? null,
    $body['author_avatar'] ?? null,
    $body['image'] ?? null,
    $body['body'],
    $body['tags'] ?? null,
    $body['read_time'] ?? null,
    isset($body['published']) ? (int)(bool)$body['published'] : 1,
    (int)$body['id'],
]);

json_response(['ok' => true]);
