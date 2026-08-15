<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0'); // never leak PHP errors/warnings into a JSON response

require_once __DIR__ . '/response.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage());
    json_error('Server error', 500);
});

// Only POST/GET are used anywhere in this API (see README) — kept deliberately
// simple so it runs unmodified on plain shared hosting.
header('Access-Control-Allow-Credentials: true');
