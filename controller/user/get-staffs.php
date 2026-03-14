<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'] ?? '';

    $result = $user->getUsers($token);

    echo json_encode($result);
}
