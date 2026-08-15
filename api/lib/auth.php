<?php

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function current_staff(): ?array
{
    start_session();
    if (empty($_SESSION['staff_id'])) {
        return null;
    }
    $stmt = db()->prepare('SELECT id, name, email, role FROM staff WHERE id = ?');
    $stmt->execute([$_SESSION['staff_id']]);
    $staff = $stmt->fetch();
    return $staff ?: null;
}

/** Ends the request with 401 if no staff session is active. */
function require_staff(): array
{
    $staff = current_staff();
    if (!$staff) {
        json_error('Not authenticated', 401);
    }
    return $staff;
}

/** Ends the request with 403 if the logged-in staff member isn't an admin. */
function require_admin(): array
{
    $staff = require_staff();
    if ($staff['role'] !== 'admin') {
        json_error('Admin access required', 403);
    }
    return $staff;
}
