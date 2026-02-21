<?php

class Auth extends DBConnection {
  use ValidationTrait;

  private function emailExists($email) {
    $stmt = $this->getConnection()->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }

  public function registerAdmin($name, $email, $phoneNumber, $password, $role) {
      $conn = $this->getConnection();
      if (!$this->validateEmail($email)) {
          return ['success' => false, 'message' => 'Invalid email format'];
      }

      if (!$this->validatePhoneNumber($phoneNumber)) {
          return ['success' => false, 'message' => 'Invalid phone number format'];
      }

      if (!$this->validatePassword($password)) {
          return ['success' => false, 'message' => 'Password must be at least 8 characters long'];
      }

      if ($this->emailExists($email)) {
          return ['success' => false, 'message' => 'Already have an account'];
      }

      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, 'admin', 'active')");
      $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phoneNumber);
      $stmt->execute();
      if ($stmt->affected_rows > 0) {
        return ['success' => true, 'message' => 'Admin account created successfully'];
      } else {
        return ['success' => false, 'message' => 'Failed to create admin account'];
      }
  }

    public function loginAdmin($email, $password) {
      $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
          return ['success' => true, 'message' => 'Login successful', 'user' => [
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'role' => $user['role']
          ]];
        }
      }
      return ['success' => false, 'message' => 'Invalid email or password'];
    }

}