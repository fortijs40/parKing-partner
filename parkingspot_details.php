<!DOCTYPE html>
<html lang="en">
    <?php
        session_name('session_1');
        session_start();
        
        require_once './php/connection.php';
        
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header("Location: login.php");
            exit();
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve information from the form submission
            $spotId = $_POST['spot_id'];
            $spotName = $_POST['spot_name'];
            $startTime = date('H:i', strtotime($_POST['start_time']));
            $endTime = date('H:i', strtotime($_POST['end_time']));
            $address = $_POST['spot_address'];
            $maxSpotCount = $_POST['max_spot_count'];
            $price = $_POST['price'];
            $price = number_format((float)$price, 2, '.', '');
            $addInfo = $_POST['add_info'];
        } else {
            // Handle the case when there's no form submission data
            header("Location: parking_list.php"); // Redirect back to the parking list page or display an error message
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM reviews WHERE spot_id = :spot_id");
        $stmt->bindParam(':spot_id', $spotId);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT * FROM reports WHERE spot_id = :spot_id");
        $stmt->bindParam(':spot_id', $spotId);
        $stmt->execute();
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $conn = null;
    ?>
    
<head>
    <title>ParKing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/colors-light.css" id="theme-style">
    <link rel="stylesheet" href="src/css/parkingdetails.css">
    <link rel="apple-touch-icon" sizes="180x180" href="src/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="src/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="src/img/favicon/site.webmanifest">
</head>

<body>
    <header class="header-container" id="header">
        <div class="header">
            <img src="src/img/logo.png" alt="ParKing" class="logo">
            <div class="header-links">
                <div class="header-links-clickable">
                    <a href="parking_list.php" class="link">Parking List</a>
                    <a href="user_account.php" class="link">My Account</a>
                </div>
                <div>
                    <svg id="light-theme-toggle" class="theme-change" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="rgb(var(--primary-color))" d="M12 5q-.425 0-.712-.288Q11 4.425 11 4V2q0-.425.288-.713Q11.575 1 12 1t.713.287Q13 1.575 13 2v2q0 .425-.287.712Q12.425 5 12 5Zm4.95 2.05q-.275-.275-.275-.688q0-.412.275-.712l1.4-1.425q.3-.3.712-.3q.413 0 .713.3q.275.275.275.7q0 .425-.275.7L18.35 7.05q-.275.275-.7.275q-.425 0-.7-.275ZM20 13q-.425 0-.712-.288Q19 12.425 19 12t.288-.713Q19.575 11 20 11h2q.425 0 .712.287q.288.288.288.713t-.288.712Q22.425 13 22 13Zm-8 10q-.425 0-.712-.288Q11 22.425 11 22v-2q0-.425.288-.712Q11.575 19 12 19t.713.288Q13 19.575 13 20v2q0 .425-.287.712Q12.425 23 12 23ZM5.65 7.05l-1.425-1.4q-.3-.3-.3-.725t.3-.7q.275-.275.7-.275q.425 0 .7.275L7.05 5.65q.275.275.275.7q0 .425-.275.7q-.3.275-.7.275q-.4 0-.7-.275Zm12.7 12.725l-1.4-1.425q-.275-.3-.275-.712q0-.413.275-.688q.275-.275.688-.275q.412 0 .712.275l1.425 1.4q.3.275.287.7q-.012.425-.287.725q-.3.3-.725.3t-.7-.3ZM2 13q-.425 0-.712-.288Q1 12.425 1 12t.288-.713Q1.575 11 2 11h2q.425 0 .713.287Q5 11.575 5 12t-.287.712Q4.425 13 4 13Zm2.225 6.775q-.275-.275-.275-.7q0-.425.275-.7L5.65 16.95q.275-.275.688-.275q.412 0 .712.275q.3.3.3.713q0 .412-.3.712l-1.4 1.4q-.3.3-.725.3t-.7-.3ZM12 18q-2.5 0-4.25-1.75T6 12q0-2.5 1.75-4.25T12 6q2.5 0 4.25 1.75T18 12q0 2.5-1.75 4.25T12 18Zm0-2q1.65 0 2.825-1.175Q16 13.65 16 12q0-1.65-1.175-2.825Q13.65 8 12 8q-1.65 0-2.825 1.175Q8 10.35 8 12q0 1.65 1.175 2.825Q10.35 16 12 16Zm0 0q-1.65 0-2.825-1.175Q8 13.65 8 12q0-1.65 1.175-2.825Q10.35 8 12 8q1.65 0 2.825 1.175Q16 10.35 16 12q0 1.65-1.175 2.825Q13.65 16 12 16Z" />
                    </svg>
                    <svg id="dark-theme-toggle" class="hide theme-change" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 21q-3.75 0-6.375-2.625T3 12q0-3.75 2.625-6.375T12 3q.35 0 .688.025t.662.075q-1.025.725-1.638 1.888T11.1 7.5q0 2.25 1.575 3.825T16.5 12.9q1.375 0 2.525-.613T20.9 10.65q.05.325.075.662T21 12q0 3.75-2.625 6.375T12 21Z" />
                    </svg>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="hide-hamburger" width="42" height="42" viewBox="0 0 24 24">
                        <path fill="none" stroke="rgb(var(--primary-color))" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14M5 12h14M5 7h14" />
                    </svg>
                </div>
                <div>
                    <svg id="logout-button" xmlns="http://www.w3.org/2000/svg" class="logout-button" onclick="logout()" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M5 21q-.825 0-1.413-.588T3 19V5q0-.825.588-1.413T5 3h6q.425 0 .713.288T12 4q0 .425-.288.713T11 5H5v14h6q.425 0 .713.288T12 20q0 .425-.288.713T11 21H5Zm12.175-8H10q-.425 0-.713-.288T9 12q0-.425.288-.713T10 11h7.175L15.3 9.125q-.275-.275-.275-.675t.275-.7q.275-.3.7-.313t.725.288L20.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.713.288t-.712-.313q-.275-.3-.263-.713t.288-.687l1.85-1.85Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>
    
    <div class="details-container">
        <div class="parking-info-container">
        <?php
            $avgScore = 0.00;
            $revCount = 0;
            if (!empty($reviews)) {
                foreach ($reviews as $review) {
                    $revCount = $revCount + 1;
                    $avgScore = ($avgScore + $review['rating']) / $revCount;
                }
            }
            $avgScore = number_format($avgScore, 1);

            // Output parking spot information in your HTML
            echo "<div class='parking-info-title'>";
                echo "<h1>{$spotName}</h1><h3> ({$startTime} - {$endTime})</h3>";
            echo "</div>";
            echo "<div class='parking-details-container'>";
                echo "<div class='parking-details-title'>";
                    echo "<h3>Information</h3>";
                echo "</div>";
                echo "<div class='parking-details-content'>";
                    echo "<div class='parking-spotCount-row'>";
                        echo "<div class='parking-spotCount-left'>";
                            echo "<p style='font-size: 25px;'><b>Total spots: </b>{$maxSpotCount}</p>";
                        echo "</div>";
                        echo "<div class='parking-spotCount-middle'>";
                            echo "<p style='font-size: 25px;'><b>Spots left</b>: 999</p>";
                        echo "</div>";
                        echo "<div class='parking-price-right'>";
                            echo "<p style='font-size: 25px;'><b>Price: </b>{$price}€</p>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='parking-adress-row'>";
                        echo "<p style='font-size: 25px;'><b>Address: </b>{$address}</p>";
                    echo "</div>";
                    echo "<div class='parking-userInfo-row'>";
                        echo "<p style='font-size: 25px;'><b>Additional information:</b> {$addInfo}</p>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
            ?>
        </div>

        <div class="parking-reviews-container">
            <?php
                echo "<div class='parking-review-container'>";
                    echo "<div class='parking-details-title'>";
                        echo "<h3>Reviews ($avgScore)</h3>";
                    echo "</div>";
                    echo "<div class='parking-review-content'>";
                        if (!empty($reviews)) {
                            foreach ($reviews as $review) {
                                $postedTime = $review['posted_time'];
                                $postedTime = date('d.m.Y H:i', strtotime($postedTime));
                                
                                echo "<div class='parking-review-list'>";
                                    echo "<div class='review'>";
                                        echo "<h3>{$review['title']}</h3>";
                                        echo "<p>{$review['rev_description']}</p>";
                                        echo "<p>Rating: {$review['rating']}</p>";
                                        echo "<p>Posted Time: {$postedTime}</p>";
                                    echo "</div>";
                                echo "</div>"; 
                            }
                        } else {
                            echo "<p style = 'display: flex;align-items: center;justify-content: center;text-align: center; margin:30px'>No reviews available for this parking spot.</p>";
                        }
                    echo "</div>";
                echo "</div>";
                
            ?>
        </div>
    </div>

    <div class="reaf-rep-cont">
        <button id="view-reports-btn" class="btn btn-primary" onclick=openModal()>Read reports</button>
    </div>
    
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h1 style="text-align: center;">Reports</h2>
            <div class="parking-report-container">
                <?php
                    echo "<div class='parking-review-container'>";
                        if (!empty($reports)) {
                            foreach ($reports as $report) {
                                echo "<div class='parking-review-list'>";
                                    echo "<div class='review'>";
                                        echo "<h2>{$report['title']}</h2>";
                                        if (($report['rep_description'])) {
                                            echo "<h3>{$report['rep_description']}</h3>";
                                        } else {
                                            echo "<h3>No details were provided</h3>";
                                        }
                                            
                                    echo "</div>";
                                echo "</div>"; 
                            }
                        } else {
                            echo "<p style = 'display: flex;align-items: center;justify-content: center;text-align: center; margin:30px'>No reports available for this parking spot.</p>";
                        }
                    echo "</div>";
                    
                ?>
            </div>
            <div id="reportsList"></div>
        </div>
    </div>

    <div class="footer">
        <img src="src/img/logo.png" alt="ParKing" class="footer-logo">
        <p>Copyright © 2023 ParKing. All rights reserved.</p>
    </div>


    
</body>
<script src="src/js/main.js"></script>
<script>
    var modal = document.getElementById("myModal");

    var btn = document.getElementById("view-reports-btn");

    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";

        var reportsList = document.getElementById("reportsList");

        reportsData.forEach(function(report) {
            var reportItem = document.createElement("div");
            reportItem.innerHTML = "<h3>" + report.title + "</h3><p>" + report.desc + "</p>";
            reportsList.appendChild(reportItem);
        });
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</html>