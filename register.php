<?php
session_start();
// Include the database connection file
require 'db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- Validation (Simple) ---
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: index.php?register_error=All fields are required.");
        exit();
    }

    // --- Check if email already exists ---
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Email already exists
        header("Location: index.php?register_error=Email already in use.");
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // --- Create new user ---
    
    // CRITICAL: Hash the password for security
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        // Registration successful
        header("Location: index.php?register_success=Registration successful! Please log in.");
    } else {
        // Registration failed
        header("Location: index.php?register_error=Something went wrong. Please try again.");
    }

    $stmt->close();
    $conn->close();
} else {
    // Not a POST request
    header("Location: index.php");
    exit();
}
?>
