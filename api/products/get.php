<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if (empty($_GET['id'])) {
    json_error('Missing id', 422);
}

$stmt = db()->prepare('SELECT p.*, c.slug AS category_slug, c.name AS category_name
                        FROM products p
                        LEFT JOIN categories c ON c.id = p.category_id
                        WHERE p.id = ?');
$stmt->execute([(int)$_GET['id']]);
$product = $stmt->fetch();

if (!$product) {
    json_error('Product not found', 404);
}

json_response(['product' => $product]);
