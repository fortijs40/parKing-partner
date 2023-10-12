<?php
require_once 'config.php';
require_once 'modifyTables.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the API key from the header
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

    if ($apiKey !== $sharedApiKey) {
        http_response_code(403);
        echo 'Unauthorized';
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);


    // funcij pectam lai updatotu datubazi n stuff, idk yet
    processPayload($payload); //funkcija no modifyTables.php :)

    // Respond with a 200 OK status
    http_response_code(200);
    echo 'Webhook received successfully';
} else {
    // Respond with a 405 Method Not Allowed status for non-POST requests
    http_response_code(405);
    echo 'Method Not Allowed';
}

?>