<?php

require_once "database.php";

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $fullName = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($fullName === '' || $email === '' || $password === '' || $passwordConfirm === '') {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $passwordConfirm) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $db = new Database();
        $conn = $db->getConnection();

        if (!$conn) {
            // Provide stored DB error if available
            $dbError = method_exists($db, 'getLastError') ? $db->getLastError() : null;
            $errors[] = "Database connection failed" . ($dbError ? ": " . $dbError : "");
        } else {
            try {
                // check if email already exists
                $stmt = $conn->prepare("SELECT id FROM user WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $errors[] = "Email is already registered";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    // use column name 'name' to match your table columns (id, name, email, password)
                    $stmt = $conn->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
                    $ok = $stmt->execute([$fullName, $email, $hashedPassword]);
                    if ($ok && $stmt->rowCount() > 0) {
                        $success = "Registration successful!";
                    } else {
                        $errors[] = "Insert failed: no rows affected.";
                    }
                }
            } catch (PDOException $e) {
                // Provide friendly guidance for common schema errors
                $msg = $e->getMessage();
                $code = $e->getCode();
                if (stripos($msg, 'Base table or view not found') !== false || stripos($msg, 'doesn\'t exist') !== false) {
                    $errors[] = "Database table not found. Make sure the 'user' table exists.";
                } elseif (stripos($msg, 'Unknown column') !== false) {
                    $errors[] = "Database column not found. Check your user table columns (name, email, password).";
                } else {
                    $errors[] = "Database error (" . $code . "): " . $msg;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="../css/registration.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <?php
            // display errors or success
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
                }
            } elseif ($success) {
                echo "<div class='alert alert-success'>" . htmlspecialchars($success) . "</div>";
            }
            ?>
            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input required type="text" id="name" name="name" placeholder="Name" value="<?php echo isset($fullName) ? htmlspecialchars($fullName) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input required type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input required type="password" id="password" name="password" placeholder="Password">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <input required type="password" id="password_confirm" name="password_confirm" placeholder="Confirm Password">
                </div>
                
                <button type="submit" name="submit" class="login-btn">Register</button>
                
                <p class="signup-text">Have an Account? <a href="logIn.php">Log In</a></p>
            </form>
        </div>
        
        <div class="image-section">
            <img src="bubles.jpg.png" alt="Background">
        </div>
    </div>
</body>
</html>