<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/auth.php';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $auth->login($email, $password);

    echo json_encode($result);
}
