<?php

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (!isset($_POST['token'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "You are not authorized!"
        )
    );
    die();
}

if (!isset($_POST['membership_id'], $_POST['total'], $_POST['other_data'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "membership_id, total and other_data is is required!"
        )
    );
    die();
}

global $CON;
$token = $_POST['token'];
$membership_id = $_POST['membership_id'];
$total = $_POST['total'];
$other_data = $_POST['other_data'];
$userId = getUserId($token);


$sql = "select * from membership where membership_id = '$membership_id'";

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $membership_duration = $row['duration_months'];
    $discount_percentage = $row['discount_percentage'];
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Membership not found!"
        )
    );
    die();
}

$sql = "select * from users where user_id = '$userId'";

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $expiration_date = $row['expires_at'];


    if ($expiration_date > date("Y-m-d")) {

        echo json_encode(
            array(

                "success" => true,
                "message" => "Already subscribed!"
            )
        );
        die();
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "User not found!"
        )
    );
    die();
}





$sql = "INSERT INTO payments (user_id, amount, other_data) VALUES ('$userId','$total','$other_data')";

$result = mysqli_query($CON, $sql);

if ($result) {
    $payment_id = mysqli_insert_id($CON);

    $expiration_date = date("Y-m-d", strtotime("+ $membership_duration months"));


    $sql = "INSERT INTO subscription (user_id, payment_id, membership_id, expiration_date,status) VALUES ('$userId','$payment_id','$membership_id', '$expiration_date','subscribed')";

    $result = mysqli_query($CON, $sql);


    if ($result) {


        $sql = "UPDATE users SET member_discount = $discount_percentage, expires_at = '$expiration_date' WHERE user_id = $userId";


        $result = mysqli_query($CON, $sql);

        if ($result) {
            echo json_encode(
                array(
                    "success" => true,
                    "message" => "Membership subscribed successfully!",
                    "discount" => $discount_percentage,
                    "expires_at" => $expiration_date
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Updating user failed!",
                    "error" => mysqli_error($CON)
                )
            );
        }
    } else {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Creating subscription failed!",
                "error" => mysqli_error($CON)
            )
        );
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Creating payment failed!"
        )
    );
}
