<?php
// --- START DEBUGGING ---
// Show all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Force MySQLi to throw exceptions on errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// --- END DEBUGGING ---


// Database credentials
$servername = "localhost";
$username = "root";
$password = ""; // <-- Double-check this! Use "root" if MAMP.
$dbname = "finserve_db";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    // If connection fails, stop everything and show the error
    die("Connection failed: " . $e->getMessage());
}

// If we get here, the connection was successful!
?>

