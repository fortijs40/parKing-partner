<?php
global $conn;
$_error = array();

$_username = NULL;
if (empty($_POST['username'])){
    $_error[] = 'Username is missing.';
} elseif (isset($_POST['username'] )) {
    $_username=$_POST['username'];
}

$_first_name = NULL;
if (empty($_POST['first_name'])) {
    $_error[] = 'First name is missing.';
} elseif (isset($_POST['first_name'] )) {
    $_first_name=$_POST['first_name'];
}

$_last_name = NULL;
if (empty($_POST['last_name'])) {
    $_error[] = 'Last name is missing.';
} elseif (isset($_POST['last_name'] )) {
    $_last_name=$_POST['last_name'];
}

$_email = NULL;
if (empty($_POST['email'])) {
    $_error[] = 'Email is missing.';
} elseif (isset($_POST['email'] )) {
    $_email=$_POST['email'];
}

$_phone_number = NULL;
if (empty($_POST['phone_number'])) {
    $_error[] = 'Phone number is missing.';
} elseif (isset($_POST['phone_number'] )) {
    $_phone_number=$_POST['phone_number'];
}

$_password = NULL;
if (empty($_POST['password'])) {
    $_error[] = 'Password is missing.';
} elseif (isset($_POST['password'] )) {
    $_password=$_POST['password'];
}

$_hashed_password = password_hash($_password, PASSWORD_DEFAULT);

$_password_confirmed = NULL;
if (empty($_POST['password_confirmed'])) {
    $_error[] = 'Password confirmation is missing.';
} elseif (isset($_POST['password_confirmed'] )) {
    $_password_confirmed=$_POST['password_confirmed'];
}

$_type_id = 1;

$_last_id = NULL;

if(!empty($_error)) {
    die(implode('<br>', $_error));
}

try {

    require_once 'connection.php';

    $stmt = $conn->prepare("INSERT INTO partners_id(type_id) VALUES (:type_id)");

    $stmt->bindParam(':type_id', $_type_id);

    $stmt->execute();

    $_last_id = $conn->lastInsertId();

    $stmt = $conn->prepare("UPDATE partners_id SET email = :email, hashed_password = :hashed_password WHERE partner_id = :last_insert");

    $stmt->bindParam(':last_insert', $_last_id);
    $stmt->bindParam(':email', $_email);
    $stmt->bindParam(':hashed_password', $_hashed_password);

    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO persons(partner_id, first_name, last_name, email, phone_number)
                                    VALUES (:partner_id, :first_name, :last_name, :email, :phone_number)");

    $stmt->bindParam(':partner_id', $_last_id);
    $stmt->bindParam(':first_name', $_first_name);
    $stmt->bindParam(':last_name', $_last_name);
    $stmt->bindParam(':email', $_email);
    $stmt->bindParam(':phone_number', $_phone_number);

    $stmt->execute();

    echo 'New records created successfully.';

} catch(PDOException $e) {

    echo 'Error: ' . $e->getMessage();
    echo 'New records were not created.';
}

$conn = null;

echo '<br>';

header('account.html');
exit;
?>