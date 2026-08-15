<?php
/**
 * Copy this file to config.php and fill in your real database credentials.
 * config.php is gitignored — never commit real credentials.
 *
 * On HostGator: cPanel → MySQL Databases gives you the DB name, username,
 * and password. Host is almost always 'localhost'.
 */

return [
    'db' => [
        'host'    => 'localhost',
        'name'    => 'poseidonweb_shop',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    // Random long string used to sign session cookies. Change this to your
    // own random value in production (e.g. `php -r "echo bin2hex(random_bytes(32));"`).
    'session_secret' => 'change-me-to-a-random-string',

    // Where uploaded product/blog/banner images are stored, relative to
    // this api/ directory, and the public URL prefix used to serve them.
    'upload_dir' => __DIR__ . '/../uploads',
    'upload_url' => '/api/uploads',
];
