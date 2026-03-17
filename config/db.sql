CREATE DATABASE IF NOT EXISTS safedose;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone BIGINT NOT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    is_super TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, phone, role, is_super) VALUES
('John Doe', 'superadmin@gmail.com', '$2y$10$L0CWHKDdiupy7.yegrP73el0Tdpna22seh8qlkjl1qen/k3D17UoO', 07849026488, 'admin', 1);

CREATE TABLE medicine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    generic_name VARCHAR(150),
    category ENUM('antibiotic', 'analgesic', 'antihistamine', 'antiviral', 'antifungal', 'cardiovascular', 'diabetes', 'other') NOT NULL,
    dosage ENUM('tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'inhaler', 'other') NOT NULL,
    dosage_strength VARCHAR(20) NOT NULL,
    quantity INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0.00,
    expiry_date DATE NOT NULL,
    manufacturer VARCHAR(150),
    description TEXT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) AUTO_INCREMENT = 6000;