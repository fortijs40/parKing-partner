<?php
session_name('session_1');
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once 'connection.php';

$partner_id = $_SESSION['partner_id'];
$person_id = $_SESSION['person_id'];

$stmt = $conn->prepare("SELECT * FROM persons WHERE person_id = :person_id");
$stmt->bindParam(':person_id', $person_id);
$stmt->execute();
$partnerData = $stmt->fetch(PDO::FETCH_ASSOC);

$_error = array();

$_email = (!empty($_POST['email'])) ? $_POST['email'] : $partnerData['email'];

$_phone_number = (!empty($_POST['phone_number'])) ? $_POST['phone_number'] : $partnerData['phone_number'];

// Check if the user wants to change the password
if (!empty($_POST['password']) && !empty($_POST['new-password']) && !empty($_POST['confirm-password'])) {

    // User wants to change the password
    $_current_password = $_POST['password'];
    $_new_password = $_POST['new-password'];
    $_confirm_password = $_POST['confirm-password'];

    // Retrieve the current hashed password from the database
    $stmt = $conn->prepare("SELECT hashed_password FROM partners_id WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partner_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the current password
    if (password_verify($_current_password, $result['hashed_password'])) {
        // Current password is correct, proceed with password update
        if ($_new_password === $_confirm_password) {
            // Passwords match, update the password
            $_hashed_password = password_hash($_new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE partners_id SET hashed_password = :hashed_password WHERE partner_id = :partner_id");
            $stmt->bindParam(':partner_id', $partner_id);
            $stmt->bindParam(':hashed_password', $_hashed_password);
            $stmt->execute();

            $passwordUpdateSuccess = true;
        } else {
            $_error[] = 'New passwords do not match.';
            $passwordUpdateSuccess = false;
        }
    } else {
        $_error[] = 'Current password is incorrect.';
        $passwordUpdateSuccess = false;
    }
}

if (!empty($_error)) {
    // Display errors and stop execution
    foreach ($_error as $error) {
        echo $error . '<br>';
    }
    exit; // Stop execution
}

// Update user information (excluding password) in the database
try {
    $stmt = $conn->prepare("UPDATE persons SET email = :email, phone_number = :phone_number WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partner_id);
    $stmt->bindParam(':email', $_email);
    $stmt->bindParam(':phone_number', $_phone_number);
    $stmt->execute();

    $updateStatus = $passwordUpdateSuccess ? "success" : "no-password-change";

    header("Location: ../user_account.php?update={$updateStatus}");
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $updateStatus = "failed";
    header("Location: ../user_account.php?update={$updateStatus}");
}

// Close the database connection
$conn = null;