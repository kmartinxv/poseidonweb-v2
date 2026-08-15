<?php
require_once __DIR__ . '/lib/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}
require_staff();

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    json_error('No image uploaded', 422);
}

$file = $_FILES['image'];

$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];

$mime = mime_content_type($file['tmp_name']);
if (!isset($allowed[$mime])) {
    json_error('Only JPG, PNG, WEBP, or GIF images are allowed', 422);
}

if ($file['size'] > 5 * 1024 * 1024) {
    json_error('Image must be under 5MB', 422);
}

$config     = require __DIR__ . '/config/config.php';
$uploadDir  = rtrim($config['upload_dir'], '/');
$uploadUrl  = rtrim($config['upload_url'], '/');

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
$dest     = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    json_error('Failed to save upload', 500);
}

json_response(['url' => $uploadUrl . '/' . $filename], 201);
