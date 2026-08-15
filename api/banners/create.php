<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

$body = json_body();
require_fields($body, ['title', 'placement']);

$stmt = db()->prepare('INSERT INTO banners (title, subtitle, image, link, placement, active, sort_order)
    VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([
    trim($body['title']),
    $body['subtitle'] ?? null,
    $body['image'] ?? null,
    $body['link'] ?? null,
    $body['placement'],
    isset($body['active']) ? (int)(bool)$body['active'] : 1,
    (int)($body['sort_order'] ?? 0),
]);

json_response(['id' => (int)db()->lastInsertId()], 201);
