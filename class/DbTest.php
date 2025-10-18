<?php
    class DBTest {
        private $conn;
        
        public function __construct($db){
            $this->conn = $db;
        }
        public function checkConnection(){
            if (!$this->conn) {
                return ["status" => "error", "message" => "No database connection provided"];
            }

            // mysqli connection object
            if ($this->conn instanceof mysqli) {
                if ($this->conn->connect_errno) {
                    return ["status" => "error", "message" => "MySQLi connection error: " . $this->conn->connect_error];
                }
                $res = $this->conn->query('SELECT 1');
                if ($res !== false) {
                    return ["status" => "success", "message" => "Database connected successfully"];
                }
                return ["status" => "error", "message" => "MySQLi query failed: " . $this->conn->error];
            }

            // PDO connection object
            if ($this->conn instanceof PDO) {
                try {
                    $this->conn->query('SELECT 1');
                    return ["status" => "success", "message" => "Database connected successfully"];
                } catch (PDOException $e) {
                    return ["status" => "error", "message" => "PDO connection error: " . $e->getMessage()];
                }
            }

            // Fallback for other connection types
            try {
                if ($this->conn) {
                    return ["status" => "success", "message" => "Database connection appears valid"];
                }
            } catch (\Throwable $e) {
                // ignore and fall through
            }

            return ["status" => "error", "message" => "Unknown database connection type or failed to connect"];
        }
    }
    ?>