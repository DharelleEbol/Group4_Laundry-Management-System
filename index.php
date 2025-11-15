<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <div class="container">
        <div class="form-section">

            <!-- No need to set action for AJAX -->
            <form class="login-form" method="POST">
                
                <!-- Error message container -->
                <div class="error" style="color:red; margin-bottom:10px;"></div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input required type="email" id="email" name="email" placeholder="Email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input required type="password" id="password" name="password" placeholder="Password">
                </div>

                <button type="submit" class="login-btn">Log In</button>

                <p class="signup-text">Don’t have an account? <a href="registration.php">Sign Up</a></p>
            </form>
        </div>

        <div class="image-section">
            <img src="assets/bubles.jpg.png" alt="Background">
        </div>
    </div>

    <!-- Include your JS file -->
    <script src="js/login.js"></script>
</body>
</html>
