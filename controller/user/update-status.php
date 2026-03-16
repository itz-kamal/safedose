<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetUserId = (int) ($_POST['userId'] ?? 0);
    $status = $_POST['status'] ?? '';
    $token = $_POST['token'] ?? '';
    echo json_encode($user->updateStatus($targetUserId, $status, $token));
}
