<?php
require_once __DIR__ . '/../lib/bootstrap.php';

$staff = current_staff();
json_response(['staff' => $staff]);
