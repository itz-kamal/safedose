<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';
require_once '../../classes/medicine.php';

$medicine = new Medicine();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $medicineId = intval($_POST['id'] ?? 0);

    $result = $medicine->deleteMedicine($token, $medicineId);

    echo json_encode($result);
}
