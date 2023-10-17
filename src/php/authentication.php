<?php
global $conn;
session_name('session_1');
session_start();
$_SESSION['sid']=session_id();
$_SESSION['count'] = 1;

require_once 'connection.php';

if(isset($_POST['submit'])){

    $_username = NULL;
    $_email = NULL;
    $_password = NULL;
    $_hashed_password = NULL;
    $_type_id = NULL;

    if (isset($_POST['email'])) {
        $_email=test_input($_POST['email']);
    }

    if (isset($_POST['password'])) {
        $_password=test_input($_POST['password']);
    }

}

function test_input($_data) {
    $_data = trim((string)$_data);
    $_data = stripslashes((string)$_data);
    $_data = htmlspecialchars($_data);

    return $_data;
}

try {

    $stmt = $conn->prepare("SELECT partner_id, type_id, email, hashed_password FROM partners_id WHERE email = :_email");
    $stmt->bindValue(':_email', $_email, PDO::PARAM_STR);

    $stmt->execute();

    $result = $stmt->fetch();

    $_rows=$stmt->rowCount();

    if ($_rows == 1) {

        $_hashed_password = $result['hashed_password'];
        $_type_id = $result['type_id'];

    } else {
        echo '<div class="container">';
        echo 'User not found.';
        echo '</div>';
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (password_verify($_password, $_hashed_password)) {
    isAuthorized();
    $_SESSION['logged_user']=$result['email'];

    if ($_type_id == 1) {
        header("location: index_pers.php");
    } else {
        header("location: index_comp.php");
    }

} else {
    echo 'Incorrect username or password.';
}


$conn = null;

function isAuthorized() {
    return  $_SESSION['is_loged_in'] = true;

}
exit;