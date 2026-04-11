<?php

class Medicine {
  private $db;
  private $user;

  public function __construct() {
    $this->db = new DBConnection();
    $this->user = new User();
  }

  public function allMedicines($token) {
    $conn = $this->db->getConnection();
    $userId = $this->user->validateToken($token);

    if (!$userId) {
      return ['success' => false, 'message' => 'Invalid or expired token'];
    }

    if (!$this->user->isUserActive($userId)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

    $userStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $user = $userResult->fetch_assoc();

    $role = $user['role'];

    $query = "SELECT m.id, m.name, m.generic_name, m.category, m.dosage, m.dosage_strength, m.quantity, m.price, m.expiry_date, m.manufacturer, m.description, u.name AS posted_by FROM medicine m JOIN users u ON m.user_id = u.id";
    if ($role === 'staff') {
      $query .= " WHERE m.expiry_date >= CURDATE()";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
      return ['success' => false, 'message' => 'Database error: ' . $conn->error];
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $medicines = [];
    while ($row = $result->fetch_assoc()) {
      $medicines[] = $row;
    }
    return ['success' => true, 'message' => 'All medicines', 'data' => $medicines];
  }

  public function createMedicine($token, $name, $generic_name, $category, $dosage, $dosage_strength, $quantity, $price, $expiry_date, $manufacturer, $description) {
    $conn = $this->db->getConnection();

    $userId = $this->user->validateToken($token);

    if (!$userId) {
      return ['success' => false, 'message' => 'Invalid or expired token'];
    }

    if (!$this->user->isUserActive($userId)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

    if (!$this->user->isAdmin($userId)) {
      return ['success' => false, 'message' => 'Unauthorized Access'];
    }

    if (!in_array($category, ['antibiotic', 'analgesic', 'antihistamine', 'antiviral', 'antifungal', 'cardiovascular', 'diabetes', 'other'])) {
      return ['success' => false, 'message' => 'Invalid Category'];
    }

    if (!in_array($dosage, ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'inhaler', 'other'])) {
      return ['success' => false, 'message' => 'Invalid Dosage Form'];
    }

    $stmt = $conn->prepare("INSERT INTO medicine (name, generic_name, category, dosage, dosage_strength, quantity, price, expiry_date, manufacturer, description, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssidsiss", $name, $generic_name, $category, $dosage, $dosage_strength, $quantity, $price, $expiry_date, $manufacturer, $description, $userId);
    if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Medicine added successfully'];
    } else {
      return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }
  }

  public function checkLowStock() {
    $conn = $this->db->getConnection();

    $query = "SELECT id, name, quantity, expiry_date FROM medicine WHERE quantity <= 10";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
      return ['success' => false, 'message' => 'Database error: ' . $conn->error];
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $lowStock = [];
    while ($row = $result->fetch_assoc()) {
      $lowStock[] = $row;
    }
    return ['success' => true, 'message' => 'Low stock medicines', 'data' => $lowStock];
  }

  public function deleteMedicine($token, $medicineId) {
    $conn = $this->db->getConnection();

    $userId = $this->user->validateToken($token);
    if (!$userId) {
      return ['success' => false, 'message' => 'Invalid or expired token'];
    }

    if (!$this->user->isUserActive($userId)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

    $userStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $user = $userResult->fetch_assoc();

    if ($user['role'] !== 'admin') {
      return ['success' => false, 'message' => 'Unauthorized: Only admin can delete medicine'];
    }

    $checkStmt = $conn->prepare("SELECT id FROM medicine WHERE id = ?");
    $checkStmt->bind_param("i", $medicineId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
      return ['success' => false, 'message' => 'Medicine not found'];
    }

    $deleteStmt = $conn->prepare("DELETE FROM medicine WHERE id = ?");
    if (!$deleteStmt) {
      return ['success' => false, 'message' => 'Database error: ' . $conn->error];
    }

    $deleteStmt->bind_param("i", $medicineId);

    if ($deleteStmt->execute()) {
      return ['success' => true, 'message' => 'Medicine deleted successfully'];
    } else {
      return ['success' => false, 'message' => 'Failed to delete medicine'];
    }
  }
}