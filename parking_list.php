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

$reservations = [];
$reports = [];
$reviews = [];

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


        <!-- Backup -->
        <!-- <div id="notification-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-notification-modal" onclick="closeNotifications()">&times;</span>
                <h1>Notifications</h1>
                <div id="notification-content">
                   
                </div>
            </div>
        </div> -->




        <div class="parking-list">
            <?php
                foreach ($parkingData as $parkingSpot) {
                    echo "<div class='parking-space'>";
                    echo "<div class='first-line-info'><h2>{$parkingSpot['spot_name']} </h2> 
                    <p> Time: " . date('H:i', strtotime($parkingSpot['start_time'])) . " - " . date('H:i', strtotime($parkingSpot['end_time'])) . "</p>";
                    if ($parkingSpot['is_premium'] == 1) {
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='28' height='26' viewBox='0 0 28 26' fill='none'>
                                <path d='M6.0312 0.75C5.77627 0.749909 5.5261 0.819124 5.30746 0.95024C5.08883 1.08136 4.90995 1.26944 4.78995 1.49438L1.03995 8.52562C0.911817 8.76588 0.856187 9.03817 0.879819 9.30944C0.90345 9.5807 1.00533 9.83927 1.17308 10.0538L12.8918 25.0537C13.0234 25.2221 13.1915 25.3582 13.3835 25.4519C13.5755 25.5455 13.7863 25.5942 14 25.5942C14.2136 25.5942 14.4244 25.5455 14.6164 25.4519C14.8084 25.3582 14.9766 25.2221 15.1081 25.0537L26.8268 10.0538C26.9943 9.83908 27.0958 9.58042 27.1191 9.30916C27.1424 9.0379 27.0865 8.76571 26.9581 8.52562L23.2081 1.49438C23.0882 1.26972 22.9097 1.08182 22.6914 0.950722C22.4731 0.819624 22.2233 0.750249 21.9687 0.75H6.0312ZM4.37558 8.25L6.87495 3.5625H9.26933L8.09746 8.25H4.37558ZM5.53058 11.0625H8.42558L10.355 17.2369L5.53058 11.0625ZM11.3712 11.0625H16.6287L14 19.4738L11.3712 11.0625ZM19.5743 11.0625H22.4693L17.645 17.2369L19.5762 11.0625H19.5743ZM23.6243 8.25H19.9025L18.7306 3.5625H21.125L23.6243 8.25ZM17.0037 8.25H10.9962L12.1681 3.5625H15.8318L17.0037 8.25Z' fill='#FED956'/>
                              </svg>";
                    }
                
                    // Check if the parking spot is disabled and display the disabled icon
                    if ($parkingSpot['is_disabled'] == 1) {
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30' fill='none'>
                                <path d='M13.0027 5.86969C14.1058 5.86969 15.0001 4.97545 15.0001 3.87234C15.0001 2.76924 14.1058 1.875 13.0027 1.875C11.8996 1.875 11.0054 2.76924 11.0054 3.87234C11.0054 4.97545 11.8996 5.86969 13.0027 5.86969Z' fill='black'/>
                                <path d='M26.9602 21.1218L24.1511 21.824L23.0274 16.5803C22.9588 16.2663 22.7851 15.9851 22.5352 15.783C22.2852 15.581 21.9738 15.4701 21.6524 15.4688H16.4549L16.0762 12.1875H22.5001V10.3125H15.8599L15.6177 8.21333C15.5965 8.02988 15.5394 7.85239 15.4496 7.69099C15.3599 7.5296 15.2392 7.38747 15.0945 7.27272C14.9498 7.15796 14.784 7.07283 14.6064 7.02218C14.4288 6.97153 14.2429 6.95636 14.0595 6.97753L11.7312 7.24618L12.8966 17.3438H21.2737L22.7244 24.1135L27.4152 22.9408L26.9602 21.1218Z' fill='black'/>
                                <path d='M13.125 26.25C11.4948 26.2497 9.91535 25.683 8.65684 24.6469C7.39833 23.6107 6.53897 22.1694 6.22578 20.5696C5.9126 18.9697 6.16504 17.3108 6.93992 15.8765C7.71481 14.4423 8.96399 13.3218 10.4738 12.7069L10.2523 10.788C6.74666 11.9858 4.21875 15.3125 4.21875 19.2187C4.21875 24.1296 8.21408 28.125 13.125 28.125C14.6576 28.1242 16.1642 27.7281 17.499 26.9749C18.8338 26.2217 19.9517 25.137 20.7448 23.8255L20.1562 21.0937C19.2188 23.9062 16.5586 26.25 13.125 26.25Z' fill='black'/>
                              </svg>";
                    }
                    echo "</div>";
                    echo "<div class='status'>Free Spots: {$parkingSpot['max_spot_count']}</div>";
                    echo "<div class='second-line-info'>";
                    echo "<p><b>Address:</b> {$parkingSpot['spot_address']}</p>";
                        echo "<div class='button-container'>";
                    ?>
                    <div id="edit-parking" class="modal">
                    <div class="modal-content">
                        <div class="parking-form">
                            <span class="close" id="close-edit-modal" onclick="closeEditForm()">&times;</span>
                            <form action="./php/parkinglist_upd.php" method="post" onsubmit="return validateEditForm()">
                                <h1>Edit Parking Spot</h1>

                                <input type="hidden" name="spot_id" id="edit-spot-id">

                                <label for="edit-spot_name">Spot Name:</label>
                                <input type="text" name="spot_name" id="edit-spot_name" value="<?php echo $parkingSpot['spot_name']?>"required><br>

                                <label for="edit-spot_address">Spot Address:</label>
                                <input type="text" name="spot_address" id="edit-spot_address" value="<?php echo $parkingSpot['spot_address']?>" required><br>

                                <label for="edit-start_time">Start Time:</label>
                                <input type="time" name="start_time" id="edit-start_time" value="<?php echo $parkingSpot['start_time']?>"><br>

                                <label for="edit-end_time">End Time:</label>
                                <input type="time" name="end_time" id="edit-end_time"value="<?php echo $parkingSpot['end_time']?>"><br>

                                <label for="edit-price">Price:</label>
                                <input type="text" name="price" id="edit-price" pattern="^\d+(\.\d{1,2})?$" value="<?php echo $parkingSpot['price']?>"required><br>

                                <label for="edit-max_spot_count">Spot amount:</label>
                                <input type="number" min="1" step="1" name="max_spot_count" id="edit-max_spot_count" value="<?php echo $parkingSpot['max_spot_count']?>"required><br>

                                <label for="edit-add_info">Additional Information:</label>
                                <textarea name="add_info" id="edit-add_info"><?php echo $parkingSpot['add_info']; ?></textarea><br>

                                <input id="edit-submitButton" type="submit" value="Update">
                            </form>
                        </div>
                    </div>
                </div>
                    <button class='edit-button' onclick='openEditForm(<?php echo json_encode($parkingSpot); ?>)'>Edit</button>
                    <?php
                        echo "<div class='button-container'>";
                        echo "<form action='parkingspot_details.php' method='post'>";
                        echo "<input type='hidden' name='spot_id' value='{$parkingSpot['spot_id']}'>";
                        echo "<input type='hidden' name='spot_name' value='{$parkingSpot['spot_name']}'>";
                        echo "<input type='hidden' name='start_time' value='{$parkingSpot['start_time']}'>";
                        echo "<input type='hidden' name='end_time' value='{$parkingSpot['end_time']}'>";
                        echo "<input type='hidden' name='spot_address' value='{$parkingSpot['spot_address']}'>";
                        echo "<input type='hidden' name='max_spot_count' value='{$parkingSpot['max_spot_count']}'>";
                        echo "<input type='hidden' name='price' value='{$parkingSpot['price']}'>";
                        echo "<input type='hidden' name='add_info' value='{$parkingSpot['add_info']}'>";
                        echo "<button class='edit-button' type='submit'>View Details</button>";
                        echo "</form>";
                    
                    // Pass parking spot information to parkingspot_details.php
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>

    <div class="container2">
    <div id="notification-modal" class="modal2">
            <div class="modal-content2">
                <span class="close2" id="close-notification-modal" onclick="closeNotifications()">&times;</span>
                <h1>Notifications</h1>
                <div id="notification-content">
                    <div class="display-notification">
                        <div class="display-contextBox">
                                <h4>Reservations</h4>
                                  <?php if (count($reservations) > 0) : ?>
                                     <?php foreach ($reservations as $reservation) : ?>
                                         <h3>You have a new reservation for spot <?php echo $reservation['spot_id']; ?></h3>
                                            <?php endforeach; ?>
                                                <?php else : ?>
                                                    <p>You have no reservations.</p>
                                                      <?php endif; ?>                           
                                <h4>Reports</h4>
                                 <?php if (count($reports) > 0) : ?>
                                   <?php foreach ($reports as $report) : ?>
                                      <h3>A new report was submitted for spot <?php echo $report['spot_id']; ?></h3>
                                        <?php endforeach; ?>
                                           <?php else : ?>
                                             <p>You have no reports.</p>
                                                <?php endif; ?>                              
                                  <h4>Reviews</h4>
                                   <?php if (count($reviews) > 0) : ?>
                                    <?php foreach ($reviews as $review) : ?>
                                        <h3>A new review was posted for spot <?php echo $review['spot_id']; ?></h3>
                                             <?php endforeach; ?>
                                                 <?php else : ?>
                                                   <p>You have no reviews.</p>
                                                      <?php endif; ?>                      
                          </div>
                    <!-- Notifications will be displayed here -->
                   </div>
                </div>
            </div>
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