<?php
require_once __DIR__ . '/../lib/bootstrap.php';

require_admin();

$rows = db()->query('SELECT id, name, email, role, created_at FROM staff ORDER BY created_at')->fetchAll();
json_response(['staff' => $rows]);
