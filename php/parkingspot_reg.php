<?php
session_name('session_1');
session_start();
global $conn;

  if (isset($_SESSION['logged_user'])) {
    $_partner_id=$_SESSION['logged_user'];
  } 

  if (isset($_POST['spot_type'])) {
    $_spot_type=test_input($_POST['spot_type']);
  } 

  if (isset($_POST['spot_name'])) {
    $_spot_name=test_input($_POST['spot_name']);
  } 

  if (isset($_POST['spot_address'])) {
    $_spot_address=test_input($_POST['spot_address']);
  } 

  if (isset($_POST['start_time'])) {
    $_start_time=test_input($_POST['start_time']);
  } 

  if (isset($_POST['end_time'])) {
    $_end_time=test_input($_POST['end_time']);
  } 

  if (isset($_POST['price'])) {
    $_price=test_input($_POST['price']);
  } 

  if (isset($_POST['is_premium'])) {
    $_is_premium=test_input($_POST['is_premium']);
  } 

  if (isset($_POST['is_disabled'])) {
    $_is_disabled=test_input($_POST['is_disabled']);
  } 

  if (isset($_POST['add_info'])) {
    $_add_info=test_input($_POST['add_info']);
  } 

  function test_input($_data) {
    $_data = trim((string)$_data);
    $_data = stripslashes((string)$_data);
    $_data = htmlspecialchars($_data);

    return $_data;
}

require_once 'connection.php';

try {

$stmt = $conn->prepare("INSERT INTO parkingspots(partner_id, spot_type, spot_name, spot_address, start_time, end_time,
                             price, max_spots_count, is_premium, is_disabled, add_info)
                            VALUES (:partner_id, :spot_type, :spot_name, :spot_address, :start_time, :end_time,
                             :price, :max_spots_count, :is_premium, :is_disabled, :add_info");

$stmt->bindParam(':partner_id', $_partner_id);
$stmt->bindParam(':spot_type', $_spot_type);
$stmt->bindParam(':spot_name', $_spot_name);
$stmt->bindParam(':spot_address', $_spot_address);
$stmt->bindParam(':start_time', $_start_time);
$stmt->bindParam(':end_time', $_end_time);
$stmt->bindParam(':price', $_price);
$stmt->bindParam(':max_spots_count', $_max_spots_count);
$stmt->bindParam(':is_premium', $_is_premium);
$stmt->bindParam(':is_disabled', $_is_disabled);
$stmt->bindParam(':add_info', $_add_info);

$stmt->execute();

echo 'New record created successfully.';

} catch(PDOException $e) {

echo 'Error: ' . $e->getMessage();
echo 'New record was not created.';
}

$conn = null;

echo '<br>';             
          
?>       