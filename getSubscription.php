<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (

    isset($_POST['token'])

) {
    $token = $_POST['token'];

    $isAdmin = isAdmin($token);


    if (!$isAdmin) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"

        ));
        die();
    }


    global $CON;


    $sql = 'select subscription.*,membership.*, payments.*,full_name,email from subscription join
    payments on payments.payment_id = subscription.payment_id
    join membership on membership.membership_id = subscription.membership_id
    join users on users.user_id = payments.user_id order by subscription_id desc';


    $result = mysqli_query($CON, $sql);

    if ($result) {
        $subscriptions = mysqli_fetch_all($result, MYSQLI_ASSOC);

        echo json_encode(array(
            "success" => true,
            "message" => "Subscriptions fetched successfully!",
            "subscriptions" => $subscriptions

        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Failed to fetch subscriptions!",
            "error" => mysqli_error($CON)

        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid token"

    ));
}
