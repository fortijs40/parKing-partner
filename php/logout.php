<?php
session_name('session_1');
session_start();

$_SESSION = array();

session_destroy();
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to the login page
header("Location: ../index.php");
exit();
?>