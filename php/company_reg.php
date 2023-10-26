<?php
global $conn;
$_error = array();

$_company_name = NULL;
if (empty($_POST['company_name'])){
    $_error[] = 'Company name is missing.';
} elseif (isset($_POST['company_name'] )) {
    $_company_name=$_POST['company_name'];
}

$_reg_no = NULL;
if (empty($_POST['reg_no'])) {
    $_error[] = 'Company registration number is missing.';
} elseif (isset($_POST['reg_no'] )) {
    $_reg_no=$_POST['reg_no'];
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

$_second_phone_no = NULL;
if (isset($_POST['second_phone_no'] )) {
    $_second_phone_no=$_POST['second_phone_no'];
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

//$_date_created = new DateTime();
//$_last_update = new DateTime();

$_type_id = 2;

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

    $stmt = $conn->prepare("INSERT INTO companies(partner_id, company_name, reg_no, email, phone_number, second_phone_no)
                                    VALUES (:partner_id, :company_name, :reg_no, :email, :phone_number, :second_phone_no)");

    $stmt->bindParam(':partner_id', $_last_id);
    $stmt->bindParam(':company_name', $_company_name);
    $stmt->bindParam(':reg_no', $_reg_no);
    $stmt->bindParam(':email', $_email);
    $stmt->bindParam(':phone_number', $_phone_number);
    $stmt->bindParam(':second_phone_no', $_second_phone_no);

    $stmt->execute();

    echo 'New record created successfully.';

} catch(PDOException $e) {

    echo 'Error: ' . $e->getMessage();
    echo 'New record was not created.';
}

$_last_id = NULL;

try {

    require_once 'connection.php';

    $_last_id = $conn->lastInsertId();

    $stmt = $conn->prepare("SELECT * FROM companies WHERE company_id = :last_insert");

    $stmt->bindParam(':last_insert', $_last_id);

    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {

        $apiUrl = 'http://your_spring_boot_api_endpoint';

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        curl_close($ch);

        echo $response;

    } else {
        echo "No data found";
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

echo '<br>';

header('Location: ../login.php');
exit;