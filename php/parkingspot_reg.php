<?php
session_name('session_1');
session_start();
global $conn;

  if (isset($_SESSION['partner_id'])) {
    $_partner_id=$_SESSION['partner_id'];
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
  if(isset($_POST['max_spot_count'])){
    $_max_spot_count=test_input($_POST['max_spot_count']);
  }
  if (isset($_POST['is_premium'])) {
    $_is_premium=1; //1 means is set
  }else{
    $_is_premium=0; //0 means is not set
  }

  if (isset($_POST['is_disabled'])) {
    $_is_disabled=1; //1 means is set
  }else{
    $_is_disabled=0; // 0 means is not set
  }

if (isset($_POST['add_info'])) {
  $_add_info=test_input($_POST['add_info']);
}

//$_date_created = new DateTime();
//$_last_update = new DateTime();

function test_input($_data) {
  $_data = trim((string)$_data);
  $_data = stripslashes((string)$_data);
  $_data = htmlspecialchars($_data);

  return $_data;
}

try {

require_once 'connection.php';


$stmt = $conn->prepare("INSERT INTO parkingspots(partner_id, spot_type, spot_name, spot_address, start_time, end_time,
                             price, max_spots_count, is_premium, is_disabled, add_info, date_created, last_update)
                            VALUES (:partner_id, :spot_type, :spot_name, :spot_address, :start_time, :end_time,
                             :price, :max_spots_count, :is_premium, :is_disabled, :add_info");

$stmt->bindParam(':partner_id', $_partner_id);
$stmt->bindParam(':spot_name', $_spot_name);
$stmt->bindParam(':spot_address', $_spot_address);
$stmt->bindParam(':start_time', $_start_time);
$stmt->bindParam(':end_time', $_end_time);
$stmt->bindParam(':price', $_price);
$stmt->bindParam(':max_spot_count', $_max_spot_count);
$stmt->bindParam(':is_premium', $_is_premium);
$stmt->bindParam(':is_disabled', $_is_disabled);
$stmt->bindParam(':add_info', $_add_info);
//  $stmt->bindParam(':date_created', $_date_created, PDO::PARAM_STR);
//  $stmt->bindParam(':last_update', $_last_update, PDO::PARAM_STR);


$stmt->execute();
$_SESSION['return_message'] = 'New record created successfully.';
header('Location: ../parking_list.php');

} catch(PDOException $e) {

echo 'Error: ' . $e->getMessage();
$_SESSION['return_message'] = 'New record was not created.';
//echo $stmt->queryString;
echo 'New record was not created.';
header('Location: ../parking_list.php');
}

$_last_id = NULL;

try {

  require_once 'connection.php';

  $_last_id = $conn->lastInsertId();

  $stmt = $conn->prepare("SELECT * FROM parkingspots WHERE spot_id = :last_insert");

  $stmt->bindParam(':last_insert', $_last_id);

  $stmt->execute();

  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($data) > 0) {

    $apiUrl = 'http://your_spring_boot_api_endpoint';

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'cURL Error: ' . curl_error($ch);
    }

    curl_close($ch);

    echo $response;

  } else {
    echo "No data found";
  }

} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

echo '<br>';

?>