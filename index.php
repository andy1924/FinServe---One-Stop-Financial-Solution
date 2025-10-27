<?php
session_start();

// If the user is already logged in, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Welcome - FinServe</title>
    <!-- We'll link to our new, simple CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <!-- REGISTRATION FORM -->
        <div class="form-container" id="register-form">
            <h1>Create Account</h1>
            <p>Join FinServe to manage your finances.</p>
            <!-- Form submits data to register.php -->
            <form action="register.php" method="POST">
                
                <!-- Display any error messages from the URL -->
                <?php if (isset($_GET['register_error'])): ?>
                    <div class="message error"><?php echo htmlspecialchars($_GET['register_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['register_success'])): ?>
                    <div class="message success"><?php echo htmlspecialchars($_GET['register_success']); ?></div>
                <?php endif; ?>

                <div class="input-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="toggle-link">Already have an account? <a href="#" id="show-login">Log In</a></p>
        </div>

        <!-- LOGIN FORM -->
        <div class="form-container hidden" id="login-form">
            <h1>Log In</h1>
            <p>Welcome back to FinServe.</p>
            <!-- Form submits data to login.php -->
            <form action="login.php" method="POST">

                <!-- Display any error messages from the URL -->
                <?php if (isset($_GET['login_error'])): ?>
                    <div class="message error"><?php echo htmlspecialchars($_GET['login_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['logout_success'])): ?>
                    <div class="message success"><?php echo htmlspecialchars($_GET['logout_success']); ?></div>
                <?php endif; ?>

                <div class="input-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="btn">Log In</button>
            </form>
            <p class="toggle-link">Don't have an account? <a href="#" id="show-register">Register</a></p>
        </div>  
    </div>

    <!-- Simple JS to toggle forms -->
    <script>
        document.getElementById('show-login').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        });
        document.getElementById('show-register').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        });
    </script>
</body>
</html>
