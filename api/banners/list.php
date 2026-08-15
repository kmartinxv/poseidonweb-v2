<?php
require_once __DIR__ . '/../lib/bootstrap.php';

$staff = current_staff();

$sql    = 'SELECT * FROM banners WHERE 1=1';
$params = [];

if (!empty($_GET['placement'])) {
    $sql      .= ' AND placement = ?';
    $params[] = $_GET['placement'];
}
if (!$staff) {
    $sql .= ' AND active = 1';
}
$sql .= ' ORDER BY sort_order, created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);

json_response(['banners' => $stmt->fetchAll()]);
