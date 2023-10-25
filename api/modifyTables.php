<?php

require_once '../php/connection.php';

function processPayload($payload) {

    // Check if a review is included in the payload and update the reviews table
    if (isset($payload['review'])) {
        $reviewData = $payload['review'];
        echo 'Review Payload: ' . json_encode($reviewData);
        //Need to create so it sends a notification to the partner

        updateReviews($reviewData);
    }
    
    // Check if a report is included in the payload and update the reports table
    if (isset($payload['report'])) {
        $reportData = $payload['report'];
        echo 'Report Payload: ' . json_encode($reportData);
        //Need to create so it sends a notification to the partner
        updateReports($reportData);
    }
    
    // Check if a reservation is included in the payload and update the reservations table
    if (isset($payload['reservation'])) {
        $reservationData = $payload['reservation'];
        echo 'Reservation Payload: ' . json_encode($reservationData);
        //Need to create so it sends a notification to the partner
        updateReservations($reservationData);
    }
}

function updateReservations($data) {
    global $conn;
    try {
        $spot_id = $data['spot_id'];
        $partner_id = getPartnerIdFromSpotId($spot_id);
        $stmt = $conn->prepare("INSERT INTO reservations (partner_id, spot_id, end_time, parkingspot, payment_sum, is_read) 
                               VALUES (:partner_id, :spot_id, :end_time, :parkingspot, :payment_sum, 0)");

        $stmt->bindParam(':partner_id', $partner_id);
        $stmt->bindParam(':spot_id', $spot_id);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':parkingspot', $data['parkingspot']);
        $stmt->bindParam(':payment_sum', $data['payment_sum']);

        $stmt->execute();
        echo "Reservation data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting reservation data: " . $e->getMessage();
    }
}

function updateReviews($data) {
    global $conn;
    try {
        $spot_id = $data['spot_id'];
        $partner_id = getPartnerIdFromSpotId($spot_id);
        $stmt = $conn->prepare("INSERT INTO reviews (partner_id, spot_id, rev_description, posted_time, rating, title, is_read) 
                               VALUES (:partner_id, :spot_id, :rev_description, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), :rating, :title, 0)");

        $stmt->bindParam(':partner_id', $partner_id);
        $stmt->bindParam(':spot_id', $spot_id);
        $stmt->bindParam(':rev_description', $data['rev_description']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':title', $data['title']);

        $stmt->execute();
        echo "Review data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting review data: " . $e->getMessage();
    }
}

function updateReports($data) {
    global $conn;
    try {
        $spot_id = $data['spot_id'];
        $partner_id = getPartnerIdFromSpotId($spot_id);
        $stmt = $conn->prepare("INSERT INTO reports (partner_id, spot_id, rep_description, is_read) 
                               VALUES (:partner_id, :spot_id, :rep_description, 0)");

        $stmt->bindParam(':partner_id', $partner_id);
        $stmt->bindParam(':spot_id', $spot_id);
        $stmt->bindParam(':rep_description', $data['rep_description']);

        $stmt->execute();
        echo "Report data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting report data: " . $e->getMessage();
    }
}
function getPartnerIdFromSpotId($spot_id) {
    global $conn;
    $partner_id = null;
    try {
        $stmt = $conn->prepare("SELECT partner_id FROM parkingspots WHERE spot_id = :spot_id");
        $stmt->bindParam(':spot_id', $spot_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $partner_id = $result['partner_id'];
        }
    } catch(PDOException $e) {
        echo "Error retrieving partner_id: " . $e->getMessage();
    }
    return $partner_id;
}
?>
