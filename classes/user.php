<?php

require_once __DIR__ . '/traits/validationTrait.php';

class User extends DBConnection
{
    use ValidationTrait;

    private function emailExists($email)
    {
        $stmt = $this->getConnection()->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) return false;
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function isUserActive($userId)
    {
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

    private function isAdmin($userId)
    {
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

    private function isSuperAdmin($userId)
    {
        $stmt = $this->getConnection()->prepare("SELECT is_super FROM users WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return (int) $user['is_super'] === 1;
        }
        return false;
    }

    public function validateToken($token)
    {
        $stmt = $this->getConnection()->prepare("SELECT user_id, expires_at FROM tokens WHERE token = ?");
        if (!$stmt) return false;
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $tokenData = $result->fetch_assoc();
            $expiryTime = strtotime($tokenData['expires_at']);
            if (time() < $expiryTime) {
                return $tokenData['user_id'];
            } else {
                $stmt = $this->getConnection()->prepare("DELETE FROM tokens WHERE token = ?");
                if ($stmt) {
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                }
                return false;
            }
        } else {
            return false;
        }
    }

  public function createUser($name, $email, $phoneNumber, $password, $token) {
    // create a user
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
        $userId = $this->validateToken($token);

        if (!$userId) {
            return ['success' => false, 'message' => 'Invalid or expired token'];
        }
        if (!$this->isUserActive($userId)) {
            return ['success' => false, 'message' => 'User account is inactive'];
        }
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        $user = $result->fetch_assoc();
        if ($user['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized: Only admins can add users'];
        }

        $message = "New user registration:\nName: $name\nEmail: $email\nPhone: $phoneNumber, Password: $password";
        mail($email, "New User Registration", $message);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'staff')");
        $stmt->bind_param("ssss", $name, $email, $phoneNumber, $hashedPassword);
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully'];
        }
        return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }

    public function getUsers($token)
    {
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

    public function getStaff($token)
    {
        $userId = $this->validateToken($token);
        if (!$userId || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        $stmt = $this->getConnection()->prepare("SELECT id, name, email, phone, role, status FROM users WHERE id != ?");
        if (!$stmt) return ['success' => false, 'message' => 'Database error: ' . $this->getConnection()->error];
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = [];
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
        return ['success' => true, 'data' => $staff, 'isSuperAdmin' => $this->isSuperAdmin($userId)];
    }

    public function updateStatus($targetUserId, $status, $token)
    {
        $userId = $this->validateToken($token);
        if (!$userId || !$this->isAdmin($userId)) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        if (!in_array($status, ['active', 'inactive'])) {
            return ['success' => false, 'message' => 'Invalid status'];
        }
        $stmt = $this->getConnection()->prepare("UPDATE users SET status = ? WHERE id = ? AND id != ? AND role = 'staff'");
        if (!$stmt) return ['success' => false, 'message' => 'Database error'];
        $stmt->bind_param("sii", $status, $targetUserId, $userId);
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Status updated'];
        }
        return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }

    public function updateRole($targetUserId, $role, $token)
    {
        $userId = $this->validateToken($token);
        if (!$userId || !$this->isSuperAdmin($userId)) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        if (!in_array($role, ['admin', 'staff'])) {
            return ['success' => false, 'message' => 'Invalid role'];
        }
        if ($targetUserId === $userId) {
            return ['success' => false, 'message' => 'Cannot change your own role'];
        }
        $stmt = $this->getConnection()->prepare("UPDATE users SET role = ? WHERE id = ?");
        if (!$stmt) return ['success' => false, 'message' => 'Database error'];
        $stmt->bind_param("si", $role, $targetUserId);
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Role updated'];
        }
        return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }
}
