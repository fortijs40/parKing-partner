<?php
session_name('session_1');
session_start();

require_once 'connection.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$partnerId = $_SESSION['partner_id'];

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("DELETE FROM reservations WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM reviews WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM reports WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM parkingspots WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM persons WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM companies WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM partners_id WHERE partner_id = :partner_id");
    $stmt->bindParam(':partner_id', $partnerId);
    $stmt->execute();

    $apiEndpoint = "http://rhomeserver.ddns.net:8086/api/partners/delete/{$partnerId}";
    $ch = curl_init($apiEndpoint);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $apiResponse = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Error executing cURL request: ' . curl_error($ch));
    }

    curl_close($ch);

    $conn->commit();

    session_destroy();
    header("Location: ../login.php");
    exit();
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>