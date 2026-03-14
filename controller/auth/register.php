<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/auth.php';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminCheck = $auth->isAdminExists();
    if ($adminCheck['adminExists']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'An admin account already exists']);
        exit;
    }
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $auth->registerAdmin($name, $email, $phoneNumber, $password);

    echo json_encode($result);
}
