<?php
$servername = "localhost";
$username = // write your username
$password = // write your password
$dbname = // write your db name;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully.<br><br>";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
