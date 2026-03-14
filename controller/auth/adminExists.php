<?php

header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/auth.php';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $auth->isAdminExists();
    echo json_encode($result);
}
