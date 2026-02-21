<?php
require_once '../classes/auth.php';

header('Content-Type: application/json');

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phoneNumber = trim($_POST['phoneNumber'] ?? '');
  $password = $_POST['password'] ?? '';

  echo $auth->registerAdmin($name, $email, $phoneNumber, $password, $role);
}