<?php
// Include your database connection code here
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $spot_id = $_POST['spot_id'];
        $spot_name = $_POST['spot_name'];
        $spot_address = $_POST['spot_address'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $price = $_POST['price'];
        $max_spot_count = $_POST['max_spot_count'];
        $add_info = $_POST['add_info'];

        // Update the parking spot data in the database
        $stmt = $conn->prepare("UPDATE parkingspots SET 
            spot_name = :spot_name, 
            spot_address = :spot_address, 
            start_time = :start_time, 
            end_time = :end_time, 
            price = :price, 
            max_spot_count = :max_spot_count, 
            add_info = :add_info 
            WHERE spot_id = :spot_id");

        $stmt->bindParam(':spot_id', $spot_id);
        $stmt->bindParam(':spot_name', $spot_name);
        $stmt->bindParam(':spot_address', $spot_address);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':max_spot_count', $max_spot_count);
        $stmt->bindParam(':add_info', $add_info);

        $stmt->execute();

        // Redirect to the parking list page after updating
        header('Location: ../parking_list.php');
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
    }
}
?>
