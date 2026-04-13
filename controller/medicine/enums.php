<?php
header('Content-Type: application/json');

require_once '../../classes/db.php';

$db = new DBConnection();
$conn = $db->getConnection();

function getEnumValues($conn, $column) {
    $stmt = $conn->prepare("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'medicine' AND COLUMN_NAME = ?");
    $stmt->bind_param("s", $column);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    preg_match_all("/'([^']+)'/", $row['COLUMN_TYPE'], $matches);
    return $matches[1];
}

echo json_encode([
    'categories' => getEnumValues($conn, 'category'),
    'dosageForms' => getEnumValues($conn, 'dosage'),
]);
