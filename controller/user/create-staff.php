<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $password = $_POST['password'] ?? '';
    $token = $_POST['token'] ?? '';

    $result = $user->createUser($name, $email, $phoneNumber, $password, $token);

    echo json_encode($result);
}
