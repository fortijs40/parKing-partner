<!DOCTYPE html>
<html lang="en">
    <?php
    session_name('session_1');
    session_start();
    
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        // User is already logged in, redirect to their profile page
        if($_SESSION['type_id'] == 1){
            header("Location: user_account.php");
            exit();
        } else if($_SESSION['type_id'] == 2){
            header("Location: business_account.php");
            exit();
        }
    }
    ?>

<head>
    <title>ParKing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/colors-light.css" id="theme-style">
    <link rel="apple-touch-icon" sizes="180x180" href="src/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="src/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="src/img/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header class="header-container" id="header">
        <div class="header">
            <img src="src/img/logo.png" alt="ParKing" class="logo">
            <div class="header-links">

                <div class="header-links-clickable">
                    <!--<div class="notification-icon" id="notification-icon">
                        <i class="fas fa-bell"></i> You can use an actual bell icon here 
                    </div>
                    Notification dropdown container (initially hidden)
                    <div class="notification-dropdown" id="notification-dropdown">
                        Notification content will be dynamically updated here 
                    </div>-->
                    <button class="btn btn-primary" onclick="window.location.href='login.php'">Join now!</button>
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
            </div>
        </div>
    </header>
    <div class="header-dropshadow"></div>
    <div class="container">
        <div class="hero-container">
            <div class="hero">
                <div class="hero-text">
                    <h1>Welcome to <b class="gradient-text">ParKing Partner</b></h1>
                    <h2>Get money by renting out your property to others!</h2>
                    <h3>
                        Whether you're a landowner with available parking spaces or a company with surplus room in your private parking lot, <b class="gradient-text">ParKing</b> is here to be your trusted ally. Simply list your available parking spaces with flexible schedules, and watch the cash flow effortlessly into your pocket.
                    </h3>
                </div>
                <img src="src/img/car.svg" alt="Car" class="hero-img dark-glow">
            </div>
        </div>
        <div class="app-dl graident-app-dl">
            <h2 class="gradient-text dark-glow-text">Try out our client web too</h2>
            <div class="app-dl-container">
                <button class="btn btn-client" onclick="window.location.href='login.html'">Parking for clients</button>
                <p>You can be our client as well! Check out ParKing today and see how easy it is to find and reserve parking online!</p>
            </div>
        </div>
        <div class="features">
            <h2 class="gradient-text features-title dark-glow-text">Features</h2>
            <div class="features-container">
                <div class="feature">
                    <div class="feature-title dark-glow">
                        <img src="src/img/feature1.svg" alt="feature 1" class="feature-img">
                        <h2>Parking List</h2>
                    </div>
                    <p>Easy to see what parking spots you’ve listed and their schedule.</p>
                </div>
            </div>
            <div class="features-container">
                <div class="feature">
                    <div class="feature-title dark-glow">
                        <img src="src/img/feature2.svg" alt="feature 2" class="feature-img">
                        <h2>Earn Money</h2>
                    </div>
                    <p>List your private parking spaces for a fee.</p>
                </div>
            </div>
            <div class="features-container">
                <div class="feature">
                    <div class="feature-title">
                        <img src="src/img/feature3.svg" alt="feature 4" class="feature-img">
                        <h2>Feedback</h2>
                    </div>
                    <p>Get notified about reservations, reviews and suggestions of your parking spots.</p>
                </div>
            </div>
            <div class="features-container">
                <div class="feature">
                    <div class="feature-title">
                        <img src="src/img/feature4.svg" alt="feature 5" class="feature-img">
                        <h2>Flexibility</h2>
                    </div>
                    <p>Disable and enable parking spaces at your convenience, tailor the hours to match your schedule, or easily adjust the quantity as need.</p>
                </div>
            </div>
        </div>
        <div class="functions">
            <h2 class="gradient-text dark-glow-text">How it works</h2>
            <div class="functions-container">
                <div class="function gradient-functions">
                    <h2 class="function-number">#1</h2>
                    <div class="function-info">
                        <img src="src/img/function1.svg" alt="function 1" class="function-img">
                        <div class="function-text">
                            <h2>Create Profile</h2>
                            <p>Create your private or company profile and start listing parking spaces.</p>
                        </div>
                    </div>
                </div>
                <div class="function gradient-functions">
                    <h2 class="function-number">#2</h2>
                    <div class="function-info">
                        <img src="src/img/function2.svg" alt="function 2" class="function-img">
                        <div class="function-text">
                            <h2>List Unused Spaces</h2>
                            <p>List your available parking spaces and their schedules.</p>
                        </div>
                    </div>
                </div>
                <div class="function gradient-functions">
                    <h2 class="function-number">#3</h2>
                    <div class="function-info">
                        <img src="src/img/function3.svg" alt="function 3" class="function-img">
                        <div class="function-text">
                            <h2>Earn Money</h2>
                            <p>See your earnings effortlessly accumulate in your account</p>
                        </div>
                    </div>
                </div>
            </div>
            <h3><b>That's it!</b></h3>
            <p><b class="gradient-text">ParKing Partner</b> makes listing unused spaces and earning money convenient.</p>
        </div>
        <div class="plans">
            <h2 class="gradient-text dark-glow-text">Contact us</h2>
            
        </div>
        <div class="footer">
            <img src="src/img/logo.png" alt="ParKing" class="footer-logo">
            <p>Copyright © 2023 ParKing. All rights reserved.</p>
        </div>
</body>
<script src="src/js/main.js"></script>

</html>