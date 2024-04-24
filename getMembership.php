<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (
    isset(
        $_POST['token'],
    )

) {
    global $CON;


    $token = $_POST['token'];

    $checkAdmin = isAdmin($token);

    $sql = "select * from membership";
    $result = mysqli_query($CON, $sql);



    if ($result) {


        $memberships = mysqli_fetch_all($result, MYSQLI_ASSOC);



        echo json_encode(
            array(
                "success" => true,
                "message" => "Membership fetched successfully!",
                "memberships" => $memberships
            )
        );
    } else {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Something went wrong!"
            )
        );
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "token is required"
        )
    );
}
