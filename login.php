<?php
session_start();
// Include the database connection file
require 'db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- Validation (Simple) ---
    if (empty($email) || empty($password)) {
        header("Location: index.php?login_error=Email and password are required.");
        exit();
    }

    // --- Find user by email ---
    // Use prepared statements
    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // CRITICAL: Verify the hashed password
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct! Start the session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the protected dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            // Invalid password
            header("Location: index.php?login_error=Invalid email or password.");
            exit();
        }
    } else {
        // No user found
        header("Location: index.php?login_error=Invalid email or password.");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Not a POST request
    header("Location: index.php");
    exit();
}
?>
