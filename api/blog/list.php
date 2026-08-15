<?php
require_once __DIR__ . '/../lib/bootstrap.php';

// Logged-in staff see drafts too (for the admin blog manager); the public
// blog page only ever sees published posts.
$staff = current_staff();

$sql = 'SELECT id, slug, title, excerpt, category, author, author_avatar, image,
               tags, read_time, published, created_at
        FROM blog_posts';
if (!$staff) {
    $sql .= ' WHERE published = 1';
}
$sql .= ' ORDER BY created_at DESC';

json_response(['posts' => db()->query($sql)->fetchAll()]);
