<?php
require_once __DIR__ . '/../lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
$me = require_admin();

$body = json_body();
require_fields($body, ['id']);

if ((int)$body['id'] === (int)$me['id']) {
    json_error("You can't delete your own account while logged in", 422);
}

$stmt = db()->prepare('DELETE FROM staff WHERE id = ?');
$stmt->execute([(int)$body['id']]);

json_response(['ok' => true]);
