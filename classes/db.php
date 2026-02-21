<?php

class DBConnection {
    private $host = 'localhost';
    private $dbName = 'safedose';
    private $username = 'root';
    private $password = 'root';

    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);

        if (!$this->conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>