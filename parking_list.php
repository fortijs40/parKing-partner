<?php
session_name('session_1');
session_start();


if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
require_once './php/connection.php';
$redirectURL = ($_SESSION['type_id'] == 1) ? 'user_account.php' : 'business_account.php';
try {
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from the "parkingspots" table
    $stmt = $conn->prepare("SELECT * FROM parkingspots where partner_id = $_SESSION[partner_id]");
    $stmt->execute();
    $parkingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ParKing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!---
    ---> <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/colors-light.css" id="theme-style">
    <link rel="apple-touch-icon" sizes="180x180" href="src/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="src/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="src/img/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./src/css/praking_list.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
    <header class="header-container" id="header">
        <div class="header">
            <img src="src/img/logo.png" alt="ParKing" class="logo">
            <div class="header-links">

                <div class="header-links-clickable">
                    <div class="notification-icon" id="notification-icon" onclick="openNotifications()">
                        <i class="fas fa-bell"></i> <!-- You can use an actual bell icon here -->
                    </div>
                    <a href="parking_list.php" class="link">Parking List</a>
                    <button class="btn btn-primary" onclick="window.location.href='<?php echo $redirectURL; ?>'">My Account</button>
                </div>
                <div>

                    <svg id="light-theme-toggle" class="theme-change"xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="rgb(var(--primary-color))" d="M12 5q-.425 0-.712-.288Q11 4.425 11 4V2q0-.425.288-.713Q11.575 1 12 1t.713.287Q13 1.575 13 2v2q0 .425-.287.712Q12.425 5 12 5Zm4.95 2.05q-.275-.275-.275-.688q0-.412.275-.712l1.4-1.425q.3-.3.712-.3q.413 0 .713.3q.275.275.275.7q0 .425-.275.7L18.35 7.05q-.275.275-.7.275q-.425 0-.7-.275ZM20 13q-.425 0-.712-.288Q19 12.425 19 12t.288-.713Q19.575 11 20 11h2q.425 0 .712.287q.288.288.288.713t-.288.712Q22.425 13 22 13Zm-8 10q-.425 0-.712-.288Q11 22.425 11 22v-2q0-.425.288-.712Q11.575 19 12 19t.713.288Q13 19.575 13 20v2q0 .425-.287.712Q12.425 23 12 23ZM5.65 7.05l-1.425-1.4q-.3-.3-.3-.725t.3-.7q.275-.275.7-.275q.425 0 .7.275L7.05 5.65q.275.275.275.7q0 .425-.275.7q-.3.275-.7.275q-.4 0-.7-.275Zm12.7 12.725l-1.4-1.425q-.275-.3-.275-.712q0-.413.275-.688q.275-.275.688-.275q.412 0 .712.275l1.425 1.4q.3.275.287.7q-.012.425-.287.725q-.3.3-.725.3t-.7-.3ZM2 13q-.425 0-.712-.288Q1 12.425 1 12t.288-.713Q1.575 11 2 11h2q.425 0 .713.287Q5 11.575 5 12t-.287.712Q4.425 13 4 13Zm2.225 6.775q-.275-.275-.275-.7q0-.425.275-.7L5.65 16.95q.275-.275.688-.275q.412 0 .712.275q.3.3.3.713q0 .412-.3.712l-1.4 1.4q-.3.3-.725.3t-.7-.3ZM12 18q-2.5 0-4.25-1.75T6 12q0-2.5 1.75-4.25T12 6q2.5 0 4.25 1.75T18 12q0 2.5-1.75 4.25T12 18Zm0-2q1.65 0 2.825-1.175Q16 13.65 16 12q0-1.65-1.175-2.825Q13.65 8 12 8q-1.65 0-2.825 1.175Q8 10.35 8 12q0 1.65 1.175 2.825Q10.35 16 12 16Zm0 0q-1.65 0-2.825-1.175Q8 13.65 8 12q0-1.65 1.175-2.825Q10.35 8 12 8q1.65 0 2.825 1.175Q16 10.35 16 12q0 1.65-1.175 2.825Q13.65 16 12 16Z" />
                    </svg>
                    <svg id="dark-theme-toggle" class="hide theme-change" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 21q-3.75 0-6.375-2.625T3 12q0-3.75 2.625-6.375T12 3q.35 0 .688.025t.662.075q-1.025.725-1.638 1.888T11.1 7.5q0 2.25 1.575 3.825T16.5 12.9q1.375 0 2.525-.613T20.9 10.65q.05.325.075.662T21 12q0 3.75-2.625 6.375T12 21Z" />
                    </svg>
                </div>
                <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="hide-hamburger" width="42" height="42" viewBox="0 0 24 24"><path fill="none" stroke="rgb(var(--primary-color))" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14M5 12h14M5 7h14"/></svg>
                </div>
                <div>
                    <svg id="logout-button" xmlns="http://www.w3.org/2000/svg" class="logout-button" onclick="logout()" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M5 21q-.825 0-1.413-.588T3 19V5q0-.825.588-1.413T5 3h6q.425 0 .713.288T12 4q0 .425-.288.713T11 5H5v14h6q.425 0 .713.288T12 20q0 .425-.288.713T11 21H5Zm12.175-8H10q-.425 0-.713-.288T9 12q0-.425.288-.713T10 11h7.175L15.3 9.125q-.275-.275-.275-.675t.275-.7q.275-.3.7-.313t.725.288L20.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.713.288t-.712-.313q-.275-.3-.263-.713t.288-.687l1.85-1.85Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>
    <!-- <div class="container2">
        <div class="filter-container">
            <label for="filterIsFree">Filter by Availability:</label>
            <select id="filterIsFree">
                <option value="all">All</option>
                <option value="true">Available</option>
                <option value="false">Not Available</option>
            </select>
        </div>
        <div class="parking-list">
         
        </div>
    </div> -->
    <div class="parking-list-bar">
        <button id="add-parking-spot-btn" class="btn btn-primary" onclick=openForm()>Add a Parking Spot</button>
    </div>
    <div class="container2">
        <div id="register-parking" class="modal">
            <div class="modal-content">
                <div class="parking-form">
                    <span class="close" id="close-modal" onclick="closeForm()">&times;</span>
                    <form action="./php/parkingspot_reg.php" method="post" onsubmit="return validateForm()">
                        <h1>Register a Parking Spot</h1>

                        <label for="spot_name">Spot Name:</label>
                        <input type="text" name="spot_name" required><br>

                        <label for="spot_address">Spot Address:</label>
                        <input type="text" name="spot_address" required><br>

                        <label for="start_time">Start Time:</label>
                        <input type="time" name="start_time" value="00:00"><br>

                        <label for="end_time">End Time </label>
                        <input type="time" name="end_time"value="00:00"><br>

                        <label for="price">Price:</label>
                        <input type="text" name="price" value="0.00" pattern="^\d+(\.\d{1,2})?$" required><br>

                        <label for="max_spot_count">Spot amount:</label>
                        <input type="number" min="1" step="1"name="max_spot_count" value="1"required><br>

                        <label for="is_premium">Is Premium:</label>
                        <input type="checkbox" name="is_premium"><br>

                        <label for="is_disabled">Is Disabled:</label>
                        <input type="checkbox" name="is_disabled"><br>

                        <label for="add_info">Additional Information:</label>
                        <textarea name="add_info"></textarea><br>

                        <input id="sumbitButton"type="submit" value="Register">
                    </form>
                </div>
            </div>
        </div>
        <div id="notification-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-notification-modal" onclick="closeNotifications()">&times;</span>
                <h1>Notifications</h1>
                <div id="notification-content">
                    <!-- Notifications will be displayed here -->
                </div>
            </div>
        </div>
        <div class="parking-list">
            <?php
                foreach ($parkingData as $parkingSpot) {
                    echo "<div class='parking-space'>";
                    echo "<h2>{$parkingSpot['spot_name']}</h2>";
                    echo "<p>Time: {$parkingSpot['start_time']} - {$parkingSpot['end_time']}</p>";
                    echo "<p>Address: {$parkingSpot['spot_address']}</p>";
                    echo "<div class='status'>Free Spots: {$parkingSpot['max_spot_count']}</div>";
                    echo "<div class='button-container'>";
                    
                    // Check if the user is logged in and is the creator of this spot
                    if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] == $parkingSpot['partner_id']) {
                        echo "<button class='edit-button' onclick='editParkingSpot({$parkingSpot['id']})'>Edit</button>";
                    }
                    
                    // Pass parking spot information to parkingspot_details.php
                    echo "<a class='edit-button' href='parkingspot_details.php?id={$parkingSpot['spot_id']}&name={$parkingSpot['spot_name']}&start_time={$parkingSpot['start_time']}&end_time={$parkingSpot['end_time']}&address={$parkingSpot['spot_address']}&max_spot_count={$parkingSpot['max_spot_count']}&price={$parkingSpot['price']}&add_info={$parkingSpot['add_info']}'>View More</a>";
                    
                    echo "</div>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>


    <div class="footer">
        <img src="src/img/logo.png" alt="ParKing" class="footer-logo">
        <p>Copyright © 2023 ParKing. All rights reserved.</p>
    </div>
</body>
<script src="src/js/main.js"></script>
<script src="src/js/notification.js"></script>
<!-- --><script src="src/js/parking_list.js"></script>
<script> 
</script>
</html>