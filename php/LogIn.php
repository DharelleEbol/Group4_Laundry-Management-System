<?php
require_once "database.php";

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        $db = new Database();
        $conn = $db->getConnection();

        if (!$conn) {
            $dbError = method_exists($db, 'getLastError') ? $db->getLastError() : null;
            $errors[] = "Database connection failed" . ($dbError ? ": " . $dbError : "");
        } else {
            try {
                $stmt = $conn->prepare("SELECT id, name, email, password FROM user WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $success = "Login successful! Redirecting...";

                    // redirect after 2 seconds
                    header("refresh:2; url=dashboard.php");
                } else {
                    $errors[] = "Invalid email or password";
                }
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
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
    <title>Login Form</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <?php
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
                    <label for="email">Email</label>
                    <input required type="email" id="email" name="email" placeholder="Email" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input required type="password" id="password" name="password" placeholder="Password">
                </div>

                <button type="submit" name="submit" class="login-btn">Log In</button>

                <p class="signup-text">Don’t have an account? <a href="registration.php">Sign Up</a>
                </p>
            </form>
        </div>

        <div class="image-section">
            <img src="bubles.jpg.png" alt="Background">
        </div>
    </div>
</body>
</html>