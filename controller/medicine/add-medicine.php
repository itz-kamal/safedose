<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';
require_once '../../classes/user.php';
require_once '../../classes/medicine.php';

$medicine = new Medicine();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $genericName = $_POST['genericName'] ?? '';
    $category = $_POST['category'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $dosageStrength = $_POST['dosageStrength'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $price = $_POST['price'] ?? '';
    $expire = $_POST['expiryDate'] ?? '';
    $manufacturer = $_POST['manufacturer'] ?? '';
    $description = $_POST['description'] ?? '';
    $token = $_POST['token'] ?? '';

    $result = $medicine->createMedicine($token, $name, $genericName, $category, $dosage, $dosageStrength, $quantity, $price, $expire, $manufacturer, $description);

    echo json_encode($result);
}
