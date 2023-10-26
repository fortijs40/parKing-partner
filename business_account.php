<!DOCTYPE html>
<html lang="en">
    <?php
        session_name('session_1');
        session_start();

        require_once '.\php\connection.php';

        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header("Location: login.php");
            exit();
        }
        $companyId = $_SESSION['company_id'];

        $stmt = $conn->prepare("SELECT * FROM companies WHERE company_id = :company_id");
        $stmt->bindParam(':company_id', $companyId);
        $stmt->execute();
        $partnerData = $stmt->fetch(PDO::FETCH_ASSOC);

        $conn = null;
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
</head>

<body>
    <header class="header-container" id="header">
        <div class="header">
            <img src="src/img/logo.png" alt="ParKing" class="logo">
            <div class="header-links">
                <div class="header-links-clickable">
                    <a href="parking_list.php" class="link">Parking List</a>
                    <a href="business_account.php" class="link">My Account</a>
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
    <div class="account-container">
        <div class="user-info-container">
            <div class="user-info-left">
                <div class="user-settings-title">
                    <h2>Your Profile</h2>
                </div>
                <div class="business-name">
                    <img src="src/img/function3.svg" alt="function 3" class="function-img" style="margin-bottom: 35px;">
                    <h1 class="gradient-text" style="margin-bottom: 25px;" name="company_name"><?php echo $partnerData['company_name'];?></h1>
                </div>
                <div class="user-balance-container">
                    <h2>Total Earnings:</h2>
                    <h2 class="gradient-text" name="earnings"> 13.37€</h2>
                </div>
            </div>

            <div class="user-info-mid">
                <div class="user-settings-title">
                    <h2>Account Details</h2>
                </div>
                <div class="user-account-details">
                    <div class="user-account-details">
                        <div class="user-account-details-input-phones">
                            <div class="user-account-details-input-container">
                                <label for="phone_number" style="font-size: 20px;">Phone number</label>
                                <input type="text" name="phone_number" id="phone_number" value="<?php echo $partnerData['phone_number'];?>" class="input" style="color: black" disabled>
                            </div>
                            <div class="user-account-details-input-container">
                                <label for="second_phone_no" style="font-size: 20px;">2nd phone No.(optional)</label>
                                <input type="text" name="second_phone_no" id="second_phone_no" value="<?php echo $partnerData['second_phone_no'];?>" class="input" style="color: black" disabled>
                            </div>
                        </div>                    
                        <div class="user-account-details-input-email-reg">
                            <div class="user-account-details-input-container">
                                <label for="email" style="font-size: 20px;">Email</label>
                                <input type="text" name="email" id="email" value="<?php echo $partnerData['email'];?>" class="input" style="color: black" disabled> 
                            </div>
                            <div class="user-account-details-input-container">
                                <label for="reg_no" style="font-size: 20px;">Reg. number</label>
                                <input type="text" name="reg_no" id="reg_no" value="<?php echo $partnerData['reg_no'];?>" class="input" style="color: black" disabled>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="user-bank-details">
                    <div class="user-bank-details">
                        <div class="user-bank-details-input">
                            <label for="bank_account">Bank account</label>
                            <input type="text" name="bank_account" id="bank_account" placeholder="LV82*************8305" class="input" style="font-size: 20px; color: black">
                        </div>
                        <div class="user-bank-details-input">
                            <label for="billing_address">Billing adress</label>
                            <textarea name="billing_address" id="billing_address" placeholder="Plieņciema iela 35, Mārupe, Mārupes novads, Latvija,  LV-2167" class="input" style="font-size: 20px; color: black"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-info-right">
                <div class="user-settings-title">
                    <h2>Account Settings</h2>
                </div>
                <div class="user-account-settings">
                    <div class="user-account-settings-inputs">
                        <form id="updateForm"> <!-- To update the database with new info, gotta implement this-->
                            <div class="user-account-settings-input">
                                <label for="email" style="height: 15px">Email</label>
                                <input type="text" name="email" id="email" placeholder="your@email.com" class="input" style="color: black; height: 12px">
                            </div>
                            <div class="user-account-settings-input">
                                <label for="phone_number" style="height: 15px">Phone number</label>
                                <input type="text" name="phone_number" id="phone_number" placeholder="+371 21 337 420" class="input" style="color: black; height: 12px">
                            </div>
                            <div class="user-account-settings-input">
                                <label for="second_phone_no" style="height: 15px">Second phone number</label>
                                <input type="text" name="second_phone_no" id="second_phone_no" placeholder="+371 21 337 420" class="input" style="color: black; height: 12px">
                            </div>
                            <div class="user-account-settings-input">
                                <label for="password" style="height: 15px">Password</label>
                                <input type="password" name="password" id="password" placeholder="Current password" class="input" style="color: black; height: 12px">
                            </div>
                            <div class="user-account-settings-password-confirm">
                                <div class="user-account-settings-input">
                                    <label for="new-password" style="height: 15px">New Password</label>
                                    <input type="password" name="new-password" id="new-password" placeholder="New password" class="input" style="color: black; height: 12px">
                                </div>
                                <div class="user-account-settings-input">
                                    <label for="confirm-password" style="height: 15px">Confirm Password</label>
                                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm password" class="input" style="color: black; height: 12px">
                                </div>
                            </div>
                            <div class="user-account-update">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="user-account-settings">
                    <div class="user-delete-account" style="padding: 10px;">
                        <button class="btn btn-primary" style="padding: 10px;">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="src/js/main.js"></script>