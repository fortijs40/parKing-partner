<?php

session_start();


if(isset($_SESSION['is_loged_in']) && $_SESSION['is_loged_in'] === true) {
    session_destroy();
    header("location: logout_confirmation.php");
    exit();
} else {
    // If the user is not logged in
    header("location: login.php");
    exit();
}
?>
