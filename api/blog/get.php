<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if (empty($_GET['id']) && empty($_GET['slug'])) {
    json_error('Missing id or slug', 422);
}

if (!empty($_GET['id'])) {
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
} else {
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE slug = ?');
    $stmt->execute([$_GET['slug']]);
}

$post = $stmt->fetch();

if (!$post || (!$post['published'] && !current_staff())) {
    json_error('Post not found', 404);
}

json_response(['post' => $post]);
