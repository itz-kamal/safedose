<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';
require_once '../../classes/medicine.php';

$medicine = new Medicine();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'] ?? '';

    $result = $medicine->allMedicines($token);

    echo json_encode($result);
}
