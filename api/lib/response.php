<?php

function json_response($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $status = 400): void
{
    json_response(['error' => $message], $status);
}

function json_body(): array
{
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function require_fields(array $body, array $fields): void
{
    foreach ($fields as $f) {
        if (!array_key_exists($f, $body) || $body[$f] === '' || $body[$f] === null) {
            json_error("Missing required field: {$f}", 422);
        }
    }
}
