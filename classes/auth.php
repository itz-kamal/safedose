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
          return json_encode(['success' => false, 'message' => 'Invalid email format']);
      }

      if (!$this->validatePhoneNumber($phoneNumber)) {
          return json_encode(['success' => false, 'message' => 'Invalid phone number format']);
      }

      if (!$this->validatePassword($password)) {
          return json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
      }

      if ($this->emailExists($email)) {
          return json_encode(['success' => false, 'message' => 'Already have an account']);
      }

      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
      $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phoneNumber, $role);
      $stmt->execute();
      if ($stmt->affected_rows > 0) {
        return json_encode(['success' => true, 'message' => 'Admin account created successfully']);
      } else {
        return json_encode(['success' => false, 'message' => 'Failed to create admin account']);
      }
  }

    public function loginAdmin($email, $password) {
      $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
          return json_encode(['success' => true, 'message' => 'Login successful', 'user' => [
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'role' => $user['role']
          ]]);
        }
      }
      return json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }

}