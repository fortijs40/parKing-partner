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
            $isDisabled = $_POST['isDisabled'];
            $isPremium = $_POST['isPremium'];
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
                echo "<h1>{$spotName} ";
                if ($isPremium == 1) {
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='28' height='26' viewBox='0 0 28 26' fill='none'>
                            <path d='M6.0312 0.75C5.77627 0.749909 5.5261 0.819124 5.30746 0.95024C5.08883 1.08136 4.90995 1.26944 4.78995 1.49438L1.03995 8.52562C0.911817 8.76588 0.856187 9.03817 0.879819 9.30944C0.90345 9.5807 1.00533 9.83927 1.17308 10.0538L12.8918 25.0537C13.0234 25.2221 13.1915 25.3582 13.3835 25.4519C13.5755 25.5455 13.7863 25.5942 14 25.5942C14.2136 25.5942 14.4244 25.5455 14.6164 25.4519C14.8084 25.3582 14.9766 25.2221 15.1081 25.0537L26.8268 10.0538C26.9943 9.83908 27.0958 9.58042 27.1191 9.30916C27.1424 9.0379 27.0865 8.76571 26.9581 8.52562L23.2081 1.49438C23.0882 1.26972 22.9097 1.08182 22.6914 0.950722C22.4731 0.819624 22.2233 0.750249 21.9687 0.75H6.0312ZM4.37558 8.25L6.87495 3.5625H9.26933L8.09746 8.25H4.37558ZM5.53058 11.0625H8.42558L10.355 17.2369L5.53058 11.0625ZM11.3712 11.0625H16.6287L14 19.4738L11.3712 11.0625ZM19.5743 11.0625H22.4693L17.645 17.2369L19.5762 11.0625H19.5743ZM23.6243 8.25H19.9025L18.7306 3.5625H21.125L23.6243 8.25ZM17.0037 8.25H10.9962L12.1681 3.5625H15.8318L17.0037 8.25Z' fill='#FED956'/>
                        </svg>";
                }
            
                // Check if the parking spot is disabled and display the disabled icon
                if ($isDisabled == 1) {
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30' fill='none'>
                            <path d='M13.0027 5.86969C14.1058 5.86969 15.0001 4.97545 15.0001 3.87234C15.0001 2.76924 14.1058 1.875 13.0027 1.875C11.8996 1.875 11.0054 2.76924 11.0054 3.87234C11.0054 4.97545 11.8996 5.86969 13.0027 5.86969Z' fill='black'/>
                            <path d='M26.9602 21.1218L24.1511 21.824L23.0274 16.5803C22.9588 16.2663 22.7851 15.9851 22.5352 15.783C22.2852 15.581 21.9738 15.4701 21.6524 15.4688H16.4549L16.0762 12.1875H22.5001V10.3125H15.8599L15.6177 8.21333C15.5965 8.02988 15.5394 7.85239 15.4496 7.69099C15.3599 7.5296 15.2392 7.38747 15.0945 7.27272C14.9498 7.15796 14.784 7.07283 14.6064 7.02218C14.4288 6.97153 14.2429 6.95636 14.0595 6.97753L11.7312 7.24618L12.8966 17.3438H21.2737L22.7244 24.1135L27.4152 22.9408L26.9602 21.1218Z' fill='black'/>
                            <path d='M13.125 26.25C11.4948 26.2497 9.91535 25.683 8.65684 24.6469C7.39833 23.6107 6.53897 22.1694 6.22578 20.5696C5.9126 18.9697 6.16504 17.3108 6.93992 15.8765C7.71481 14.4423 8.96399 13.3218 10.4738 12.7069L10.2523 10.788C6.74666 11.9858 4.21875 15.3125 4.21875 19.2187C4.21875 24.1296 8.21408 28.125 13.125 28.125C14.6576 28.1242 16.1642 27.7281 17.499 26.9749C18.8338 26.2217 19.9517 25.137 20.7448 23.8255L20.1562 21.0937C19.2188 23.9062 16.5586 26.25 13.125 26.25Z' fill='black'/>
                        </svg>";
                }
                echo "</h1>";
                echo "<h3> ({$startTime} - {$endTime}) </h3>";
            echo "</div>";
            echo "<div class='parking-details-container' style='background-color: rgb(var(--secondary-color));'>";
                echo "<div class='parking-details-title' style='background-color: rgb(var(--secondary-color));'>";
                    echo "<h3>Information</h3>";
                echo "</div>";
                echo "<div class='parking-details-content' style='background-color: rgb(var(--secondary-color));'>";
                    echo "<div class='parking-spotCount-row'>";
                        echo "<div class='parking-spotCount-left' style='background-color: rgb(var(--background-color));'>";
                            echo "<p style='font-size: 25px;'><b>Total spots: </b>{$maxSpotCount}</p>";
                        echo "</div>";
                        echo "<div class='parking-spotCount-middle' style='background-color: rgb(var(--background-color));'>";
                            echo "<p style='font-size: 25px;'><b>Spots left</b>: 999</p>";
                        echo "</div>";
                        echo "<div class='parking-price-right' style='background-color: rgb(var(--background-color));'>";
                            echo "<p style='font-size: 25px;'><b>Price: </b>{$price}€</p>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='parking-adress-row' style='background-color: rgb(var(--background-color));'>";
                        echo "<p style='font-size: 25px;'><b>Address: </b>{$address}</p>";
                    echo "</div>";
                    echo "<div class='parking-userInfo-row' style='background-color: rgb(var(--background-color));'>";
                        echo "<p style='font-size: 25px;'><b>Additional information:</b> {$addInfo}</p>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
            ?>
        </div>

        <div class="parking-reviews-container" style='background-color: rgb(var(--secondary-color));'>
            <?php
                echo "<div class='parking-details-title' style='background-color: rgb(var(--secondary-color));'>";
                        echo "<h3>Reviews ($avgScore", "<svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' viewBox='0 0 28 26' fill='none'>
                        <path d='M14 16.9774L7.42818 20.7171C7.10255 20.9157 6.74732 21.0097 6.36248 20.9991C5.97765 20.9885 5.63721 20.9055 5.34119 20.7502C5.04516 20.5958 4.81603 20.3862 4.65381 20.1214C4.49159 19.8566 4.48389 19.5698 4.63072 19.2609L7.16176 13.1385L0.723153 9.72976C0.36792 9.55326 0.145899 9.3216 0.057091 9.03478C-0.0317173 8.74796 -0.0169158 8.4832 0.101495 8.24051C0.219906 7.99782 0.427126 7.78249 0.723153 7.59451C1.01918 7.40653 1.37441 7.31299 1.78885 7.31387H9.7372L12.3126 0.959744C12.4607 0.650863 12.6904 0.413467 13.0018 0.247553C13.3132 0.0816402 13.646 -0.000875513 14 7.00408e-06C14.3552 7.00408e-06 14.6886 0.0829639 15 0.248877C15.3114 0.41479 15.5405 0.651746 15.6874 0.959744L18.2628 7.31387H26.2111C26.6256 7.31387 26.9808 7.40786 27.2768 7.59583C27.5729 7.78381 27.7801 7.9987 27.8985 8.24051C28.0169 8.4832 28.0317 8.74796 27.9429 9.03478C27.8541 9.3216 27.6321 9.55326 27.2768 9.72976L20.8382 13.1385L23.3693 19.2609C23.5173 19.5698 23.5102 19.8566 23.348 20.1214C23.1857 20.3862 22.956 20.5958 22.6588 20.7502C22.3628 20.9046 22.0224 20.9876 21.6375 20.9991C21.2527 21.0105 20.8974 20.9165 20.5718 20.7171L14 16.9774Z' fill='#F2C94C'/>
                        </svg>", " )</h3>";
                    echo "</div>";
                    echo "<div class='parking-review-content'>";
                        if (!empty($reviews)) {
                            foreach ($reviews as $review) {
                                $postedTime = $review['posted_time'];
                                $postedTime = date('d.m.Y H:i', strtotime($postedTime));
                                
                                echo "<div class='parking-review-list' style='background-color: rgb(var(--background-color));'> ";
                                    echo "<div class='review'>";
                                        echo "<h3>{$review['title']}</h3>";
                                        echo "<p>{$review['rev_description']}</p>";
                                        echo "<p>Rating: {$review['rating']} ";
                                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 28 19' fill='none'>
                                        <path d='M14 16.9774L7.42818 20.7171C7.10255 20.9157 6.74732 21.0097 6.36248 20.9991C5.97765 20.9885 5.63721 20.9055 5.34119 20.7502C5.04516 20.5958 4.81603 20.3862 4.65381 20.1214C4.49159 19.8566 4.48389 19.5698 4.63072 19.2609L7.16176 13.1385L0.723153 9.72976C0.36792 9.55326 0.145899 9.3216 0.057091 9.03478C-0.0317173 8.74796 -0.0169158 8.4832 0.101495 8.24051C0.219906 7.99782 0.427126 7.78249 0.723153 7.59451C1.01918 7.40653 1.37441 7.31299 1.78885 7.31387H9.7372L12.3126 0.959744C12.4607 0.650863 12.6904 0.413467 13.0018 0.247553C13.3132 0.0816402 13.646 -0.000875513 14 7.00408e-06C14.3552 7.00408e-06 14.6886 0.0829639 15 0.248877C15.3114 0.41479 15.5405 0.651746 15.6874 0.959744L18.2628 7.31387H26.2111C26.6256 7.31387 26.9808 7.40786 27.2768 7.59583C27.5729 7.78381 27.7801 7.9987 27.8985 8.24051C28.0169 8.4832 28.0317 8.74796 27.9429 9.03478C27.8541 9.3216 27.6321 9.55326 27.2768 9.72976L20.8382 13.1385L23.3693 19.2609C23.5173 19.5698 23.5102 19.8566 23.348 20.1214C23.1857 20.3862 22.956 20.5958 22.6588 20.7502C22.3628 20.9046 22.0224 20.9876 21.6375 20.9991C21.2527 21.0105 20.8974 20.9165 20.5718 20.7171L14 16.9774Z' fill='#F2C94C'/>
                                        </svg>";
                                        echo " </p>";
                                        echo "<p>Posted Time: {$postedTime}</p>";
                                    echo "</div>";
                                echo "</div>"; 
                            }
                        } else {
                            echo "<p style = 'display: flex;align-items: center;justify-content: center;text-align: center; margin:30px'>No reviews available for this parking spot.</p>";
                        }
                    echo "</div>";
                ?>
        </div>
    </div>

    <div class="reaf-rep-cont">
        <button id="view-reports-btn" class="btn btn-primary" style="background-color: rgb(var(--danger-color)); color: rgb(var(--text-color)) " onclick=openModal()>Read reports</button>
    </div>
    
    <div id="myModal" class="modal">
        <div class="modal-content " style='background-color: rgb(var(--background-color));'>
            <span class="close">&times;</span>
            <h1 style="text-align: center;">Reports</h2>
            <div class="parking-report-container" style='background-color: rgb(var(--secondary-color));'>
                <?php
                    echo "<div class='parking-review-container'>";
                        if (!empty($reports)) {
                            foreach ($reports as $report) {
                                echo "<div class='parking-review-list' style='background-color: rgb(var(--background-color));'>";
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