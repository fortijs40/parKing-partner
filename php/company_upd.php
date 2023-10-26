<?php
session_name('session_1');
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once 'connection.php';

$partner_id = $_SESSION['partner_id'];
$company_id = $_SESSION['company_id'];

$stmt = $conn->prepare("SELECT * FROM companies WHERE company_id = :company_id");
$stmt->bindParam(':company_id', $company_id);
$stmt->execute();
$partnerData = $stmt->fetch(PDO::FETCH_ASSOC);

$_error = array();

$_email = (!empty($_POST['email'])) ? $_POST['email'] : $partnerData['email'];

$_phone_number = (!empty($_POST['phone_number'])) ? $_POST['phone_number'] : $partnerData['phone_number'];

$_second_phone_no = (!empty($_POST['second_phone_no'])) ? $_POST['second_phone_no'] : $partnerData['second_phone_no'];

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
    $stmt = $conn->prepare("UPDATE companies SET email = :email, phone_number = :phone_number, second_phone_no = :second_phone_no WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partner_id);
    $stmt->bindParam(':email', $_email);
    $stmt->bindParam(':phone_number', $_phone_number);
    $stmt->bindParam(':second_phone_no', $_second_phone_no);
    $stmt->execute();

    $updateStatus = $passwordUpdateSuccess ? "success" : "no-password-change";

    header("Location: ../business_account.php?update={$updateStatus}");
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $updateStatus = "failed";
    header("Location: ../user_account.php?update={$updateStatus}");
}

// Close the database connection
$conn = null;