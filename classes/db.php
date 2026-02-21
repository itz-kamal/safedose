<?php

class DBConnection {
    private $host = 'localhost';
    private $dbName = 'safedose';
    private $username = 'root';
    private $password = '';

    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}