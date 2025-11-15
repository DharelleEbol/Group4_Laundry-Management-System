<?php
// controllers/AuthController.php
session_start();

class AuthController {
    private $conn;

    public function __construct($pdoConnection) {
        $this->conn = $pdoConnection;
    }

    // ---------------- LOGIN ----------------
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, full_name, password FROM superadmin WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return ['status' => false, 'message' => "Email not found."];
        if (!password_verify($password, $user['password'])) return ['status' => false, 'message' => "Incorrect password."];

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];

        return ['status' => true, 'message' => "Login successful."];
    }

    // ---------------- SIGNUP ----------------
    public function signup($full_name, $email, $password) {
        // Check if email exists
        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            return ['status' => false, 'message' => "Email already taken."];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)
        ");

        if ($stmt->execute([$full_name, $email, $hashedPassword])) {
            return ['status' => true, 'message' => "Account created successfully."];
        } else {
            return ['status' => false, 'message' => "Something went wrong."];
        }
    }
}
