<?php
// Start or resume the session
session_start();

// Check if the user is logged in (adjust this check based on your authentication method)
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to a logout success page or your login page
    header('Location: login.html');
    exit();
} else {
    // User is not logged in, redirect to the login page
    header('Location: login.html');
    exit();
}
?>