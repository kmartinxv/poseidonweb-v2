<?php
require_once __DIR__ . '/../lib/bootstrap.php';

$sql    = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
           FROM products p
           LEFT JOIN categories c ON c.id = p.category_id
           WHERE 1=1';
$params = [];

if (!empty($_GET['category']) && $_GET['category'] !== 'All') {
    $sql      .= ' AND c.slug = ?';
    $params[] = $_GET['category'];
}
if (!empty($_GET['q'])) {
    $sql      .= ' AND p.name LIKE ?';
    $params[] = '%' . $_GET['q'] . '%';
}

$sql .= ' ORDER BY p.created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);

json_response(['products' => $stmt->fetchAll()]);
