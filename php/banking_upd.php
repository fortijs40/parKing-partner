<?php
session_name('session_1');
session_start();

require_once 'connection.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$_error = array();

// Check if the user is a person or a company
if (isset($_SESSION['person_id'])) {
    // User is a person
    $person_id = $_SESSION['person_id'];

    // Retrieve form data for a person
    $_bank_account = (!empty($_POST['bank_account'])) ? $_POST['bank_account'] : null;
    $_billing_address = (!empty($_POST['billing_address'])) ? $_POST['billing_address'] : null;

    try {
        // Update the persons table with banking details
        $stmt = $conn->prepare("UPDATE persons SET bank_account = :bank_account, billing_address = :billing_address WHERE person_id = :person_id");
        $stmt->bindParam(':person_id', $person_id);
        $stmt->bindParam(':bank_account', $_bank_account);
        $stmt->bindParam(':billing_address', $_billing_address);
        $stmt->execute();

        echo 'Banking details updated successfully for the person.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        echo 'Banking details were not updated for the person.';
    }
} elseif (isset($_SESSION['company_id'])) {
    // User is a company
    $company_id = $_SESSION['company_id'];

    // Retrieve form data for a company
    $_bank_account = (!empty($_POST['bank_account'])) ? $_POST['bank_account'] : null;
    $_billing_address = (!empty($_POST['billing_address'])) ? $_POST['billing_address'] : null;

    try {
        // Update the companies table with banking details
        $stmt = $conn->prepare("UPDATE companies SET bank_account = :bank_account, billing_address = :billing_address WHERE company_id = :company_id");
        $stmt->bindParam(':company_id', $company_id);
        $stmt->bindParam(':bank_account', $_bank_account);
        $stmt->bindParam(':billing_address', $_billing_address);
        $stmt->execute();

        $updateStatus = "successBank";

        header("Location: ../business_account.php?update={$updateStatus}");
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        $updateStatus = "failedBank";
        header("Location: ../business_account.php?update={$updateStatus}");
    }
}

// Close the database connection
$conn = null;
?>