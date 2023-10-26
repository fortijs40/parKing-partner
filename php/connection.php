<?php
$servername = "localhost";
$username = "user";
$password = "password";
$dbname = "partners";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully.<br><br>";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
