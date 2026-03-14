<?php

require_once __DIR__ . '/traits/validationTrait.php';

class Auth extends DBConnection {
use ValidationTrait;

  private function adminExists() {
    $stmt = $this->getConnection()->prepare("SELECT id FROM users WHERE role = 'admin'");
    if (!$stmt) return false;
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }

  private function userIsActive($email) {
      $stmt = $this->getConnection()->prepare("SELECT status FROM users WHERE email = ?");
      if (!$stmt) return false;
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();
          return $user['status'] === 'active';
      }
      return false;
  }

  public function login($email, $password) {
      $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
      if (!$stmt) return ['success' => false, 'message' => 'Database error'];

      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if (!$this->userIsActive($email)) {
          return ['success' => false, 'message' => 'User account is inactive'];
      }

      if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();
          if (password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(32));

            $expiresAt = date('Y-m-d H:i:s', strtotime('+8 hours'));
            $stmt = $this->getConnection()->prepare("INSERT INTO tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("iss", $user['id'], $token, $expiresAt);
                $stmt->execute();
            }

            return ['success' => true, 'message' => 'Login successful', 'user' => [
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'token' => $token,
                'expiresAt' => $expiresAt
            ]];
          }
      }

      return ['success' => false, 'message' => 'Invalid email or password'];
  }

  public function isAdminExists() {
    return ['success' => true, 'adminExists' => $this->adminExists()];
  }

  public function logout($token) {
    $stmt = $this->getConnection()->prepare("DELETE FROM tokens WHERE token = ?");
    if (!$stmt) return ['success' => false, 'message' => 'Logout error'];
    $stmt->bind_param("s", $token);
    $stmt->execute();
    return ['success' => true, 'message' => 'Logout successful'];
  }
}

?>