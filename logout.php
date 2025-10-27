<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page with a success message
header("Location: index.php?logout_success=You have been logged out.");
exit();
?>
