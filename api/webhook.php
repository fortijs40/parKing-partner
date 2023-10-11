<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the API key from the header
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

    if ($apiKey !== $sharedApiKey) {
        http_response_code(403);
        echo 'Unauthorized';
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);

    echo 'Payload: ' . json_encode($payload);

    // funcij pectam lai updatotu datubazi n stuff, idk yet
    processPayload($payload);

    // Respond with a 200 OK status
    http_response_code(200);
    echo 'Webhook received successfully';
} else {
    // Respond with a 405 Method Not Allowed status for non-POST requests
    http_response_code(405);
    echo 'Method Not Allowed';
}
function processPayload($payload) {
    // Extract relevant information from the payload
    $partnerId = $payload['partner_id'];
    $reservation = $payload['reservation'];
    $review = $payload['review'];
    $report = $payload['report'];

    // Update the reservations table
    updateReservations($reservation);

    // Check if a review is included in the payload and update the reviews table
    if (isset($review)) {
        //echo 'Review: ' . json_encode($review);
        updateReviews($review);
    }
    if (isset($report)) {
        //echo 'Report: ' . json_encode($report);
        updateReports($report);
    }
    if (isset($reservation)) {
        //echo 'Reservation: ' . json_encode($reservation);
        updateReservations($reservation);
    }

}
function updateReports($report) {
    // update the reports table

}

function updateReservations($reservation) {
    // update the reservations table

}

function updateReviews($review) {
    //  update the reviews table

}
?>