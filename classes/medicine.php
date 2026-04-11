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

    $stmt = $this->db->getConnection()->prepare("SELECT m.id, m.name, m.generic_name, m.category, m.dosage, m.dosage_strength, m.quantity, m.price, m.expiry_date, m.manufacturer, m.description, u.name AS posted_by FROM medicine m JOIN users u ON m.user_id = u.id");
    if (!$stmt) return ['success' => false, 'message' => 'Database error: ' . $conn->error];
    $stmt->execute();
    $result = $stmt->get_result();
    $medicines = [];
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
    return ['success' => true, 'message' => 'All medicines', 'data' => $medicines];
  }

  public function updateMedicine($token, $medId, $name, $generic_name, $category, $dosage, $dosage_strength, $quantity, $price, $expiry_date, $manufacturer, $description) {
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

    $checkStmt = $conn->prepare("SELECT id FROM medicine WHERE id = ?");
    $checkStmt->bind_param("i", $medId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Medicine not found'];
    }
    $stmt = $conn->prepare("UPDATE medicine SET name = ?, generic_name = ?, category = ?, dosage = ?, dosage_strength = ?, quantity = ?, price = ?, expiry_date = ?, manufacturer = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssssidsissi", $name, $generic_name, $category, $dosage, $dosage_strength, $quantity, $price, $expiry_date, $manufacturer, $description, $medId);
    if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Medicine updated successfully'];
    } else {
      return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }
  }

  public function getExpiredMedicines($token) {
    $conn = $this->db->getConnection();
    $userId = $this->user->validateToken($token);

    if (!$userId) {
      return ['success' => false, 'message' => 'Invalid or expired token'];
    }

    if (!$this->user->isUserActive($userId)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT * FROM medicine WHERE expiry_date < ?");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $medicines = $result->fetch_all(MYSQLI_ASSOC);

    return ['success' => true, 'message' => 'Expired medicines', 'data' => $medicines ];
  }

  public function getMedicinesExpiringSoon($token) {
    $conn = $this->db->getConnection();
    $userId = $this->user->validateToken($token);
    if (!$userId) {
      return ['success' => false, 'message' => 'Invalid or expired token'];
    }
    if (!$this->user->isUserActive($userId)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

    $today = date('Y-m-d');
    $nextWeek = date('Y-m-d', strtotime('+7 days'));
    $stmt = $conn->prepare("SELECT * FROM medicine WHERE expiry_date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $today, $nextWeek);
    $stmt->execute();
    $result = $stmt->get_result();
    $medicines = $result->fetch_all(MYSQLI_ASSOC);
    return ['success' => true, 'data' => $medicines];
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
}