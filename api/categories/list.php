<?php
require_once __DIR__ . '/../lib/bootstrap.php';

$rows = db()->query('SELECT id, name, slug, sort_order FROM categories ORDER BY sort_order, name')->fetchAll();
json_response(['categories' => $rows]);
