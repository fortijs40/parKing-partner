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
        $stmt = $conn->prepare("INSERT INTO reservations (partner_id, client_id, spot_id, start_date, end_date, start_time, end_time, parkingspot, payment_sum, is_read) 
                               VALUES (:partner_id, :client_id, :spot_id, :start_date, :end_date, :start_time, :end_time, :parkingspot, :payment_sum, :is_read)");

        $stmt->bindParam(':partner_id', $data['partner_id']);
        $stmt->bindParam(':client_id', $data['client_id']);
        $stmt->bindParam(':spot_id', $data['spot_id']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':parkingspot', $data['parkingspot']);
        $stmt->bindParam(':payment_sum', $data['payment_sum']);
        $stmt->bindParam(':is_read', $data['is_read']);

        $stmt->execute();
        echo "Reservation data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting reservation data: " . $e->getMessage();
    }
}

function updateReviews($data) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (partner_id, client_id, spot_id, rev_description, posted_time, rating, title, is_read) 
                               VALUES (:partner_id, :client_id, :spot_id, :rev_description, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), :rating, :title, :is_read)");

        $stmt->bindParam(':partner_id', $data['partner_id']);
        $stmt->bindParam(':client_id', $data['client_id']);
        $stmt->bindParam(':spot_id', $data['spot_id']);
        $stmt->bindParam(':rev_description', $data['rev_description']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':is_read', $data['is_read']);

        $stmt->execute();
        echo "Review data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting review data: " . $e->getMessage();
    }
}

function updateReports($data) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO reports (partner_id, client_id, spot_id, rep_description, is_read) 
                               VALUES (:partner_id, :client_id, :spot_id, :rep_description, :is_read)");

        $stmt->bindParam(':partner_id', $data['partner_id']);
        $stmt->bindParam(':client_id', $data['client_id']);
        $stmt->bindParam(':spot_id', $data['spot_id']);
        $stmt->bindParam(':rep_description', $data['rep_description']);
        $stmt->bindParam(':is_read', $data['is_read']);

        $stmt->execute();
        echo "Report data inserted successfully.<br>";
    } catch(PDOException $e) {
        echo "Error inserting report data: " . $e->getMessage();
    }
}

?>
