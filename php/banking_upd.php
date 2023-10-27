<?php
session_name('session_1');
session_start();

require_once 'connection.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$partner_id = $_SESSION['partner_id'];

$_error = array();

// Check if the user is a person or a company
if (isset($_SESSION['person_id'])) {
    // User is a person
    $person_id = $_SESSION['person_id'];
    $name = fetchPersonNameFromLocalDatabase($conn, $person_id);

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

        updateBackendDatabase($partner_id, $name, $_bank_account);

        $updateStatus = "successBank";
        $_SESSION['updateStatus'] = $updateStatus;

        header("Location: ../user_account.php");
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        $updateStatus = "failedBank";
        $_SESSION['updateStatus'] = $updateStatus;

        header("Location: ../user_account.php");
    }
} elseif (isset($_SESSION['company_id'])) {
    // User is a company
    $company_id = $_SESSION['company_id'];
    $name = fetchCompanyNameFromLocalDatabase($conn, $company_id);

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

        updateBackendDatabase($partner_id, $name, $_bank_account);

        $updateStatus = "successBank";
        $_SESSION['updateStatus'] = $updateStatus;

        header("Location: ../business_account.php");
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        $updateStatus = "failedBank";
        $_SESSION['updateStatus'] = $updateStatus;

        header("Location: ../business_account.php");
    }
}

// Close the database connection
$conn = null;

function fetchPersonNameFromLocalDatabase($conn, $person_id) {
    $stmt = $conn->prepare("SELECT first_name, last_name FROM persons WHERE person_id = :person_id");
    $stmt->bindParam(':person_id', $person_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['first_name'] . ' ' . $result['last_name'];
}

function fetchCompanyNameFromLocalDatabase($conn, $company_id) {
    $stmt = $conn->prepare("SELECT company_name FROM companies WHERE company_id = :company_id");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['company_name'];
}

function updateBackendDatabase($partnerId, $name, $bankAccount) {
    $apiUrl = 'http://rhomeserver.ddns.net:8086/api/partners/update/' . $partnerId;
    $postData = array(
        'name' => $name,
        'bankAccount' => $bankAccount
    );

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }

    curl_close($ch);

    echo $response;
}


?>