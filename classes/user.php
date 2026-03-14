<?php

class User extends DBConnection {
  use ValidationTrait;

  private function emailExists($email) {
    $stmt = $this->getConnection()->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) return false;
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }

  private function isUserActive($userId) {
    $stmt = $this->getConnection()->prepare("SELECT status FROM users WHERE id = ?");
    if (!$stmt) return false;
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user['status'] === 'active';
    }
    return false;
  }

  private function isAdmin($userId) {
    $stmt = $this->getConnection()->prepare("SELECT role FROM users WHERE id = ?");
    if (!$stmt) return false;
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user['role'] === 'admin';
    }
    return false;
  }

  public function validateToken($token) {
    $stmt = $this->getConnection()->prepare("SELECT user_id FROM tokens WHERE token = ?");
    if (!$stmt) return false;
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $tokenData = $result->fetch_assoc();
        $expiryTime = strtotime($tokenData['expiry']);
        if (time() < $expiryTime) {
            return $tokenData['user_id'];
        } else {
            $stmt = $this->getConnection()->prepare("DELETE FROM tokens WHERE token = ?");
            if ($stmt) { 
                $stmt->bind_param("s", $token);
                $stmt->execute();
            }
            return ['success' => false, 'message' => 'Token expired'];
        }
    } else {
      return ['success' => false, 'message' => 'Invalid token'];
    }
  }

  public function createUser($name, $email, $phoneNumber, $password, $token) {
    $conn = $this->getConnection();

    if (!$this->validateEmail($email)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }

    if (!$this->validatePhoneNumber($phoneNumber)) {
        return ['success' => false, 'message' => 'Invalid phone number format'];
    }

    if (!$this->validatePassword($password)) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters long and include an uppercase letter, a number, and a special character'];
    }

    if ($this->emailExists($email)) {
        return ['success' => false, 'message' => 'Email already registered'];
    }

    $userID = $this->validateToken($token);

    if (!$this->isUserActive($userID)) {
      return ['success' => false, 'message' => 'User account is inactive'];
    }

     $userId = $this->validateToken($token);

     $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
     if (!$stmt) {
         return ['success' => false, 'message' => 'Database error: ' . $conn->error];
     }
     $stmt->bind_param("i", $userId);
     $stmt->execute();
     $result = $stmt->get_result();
     if ($result->num_rows > 0) {
         $user = $result->fetch_assoc();
         if ($user['role'] !== 'admin') {
             return ['success' => false, 'message' => 'Unauthorized: Only admins can add users'];
         }
     } else {
         return ['success' => false, 'message' => 'User not found'];
     }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, 'user')");
    if (!$stmt) {
        return ['success' => false, 'message' => 'Database error: ' . $conn->error];
    }
    $stmt->bind_param("sssss", $name, $email, $phoneNumber, $hashedPassword, $role);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'User registered successfully'];
    } else {
        return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }

  }

  public function getUsers($token) {
    $userId = $this->validateToken($token);
    if (!$this->isAdmin($userId)) {
      return ['success' => false, 'message' => 'Unauthorized: Only admins can view users'];
    }
    $stmt = $this->getConnection()->prepare("SELECT id, name, email, phone_number, status FROM users");
    if (!$stmt) return ['success' => false, 'message' => 'Database error: ' . $this->getConnection()->error];
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return ['success' => true, 'message' => 'All Users', 'data' => $users];
  }

}