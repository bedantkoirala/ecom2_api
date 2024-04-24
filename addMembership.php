<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (
    isset(
        $_POST['title'],
        $_POST['token'],
        $_POST['price'],
        $_POST['duration_months'],
        $_POST['description'],
        $_POST['discount_percentage'],
    )

) {
    global $CON;


    $token = $_POST['token'];

    $checkAdmin = isAdmin($token);

    if (!$checkAdmin) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "You are not authorized!"
            )
        );
        die();
    }

    $title = mysqli_real_escape_string($CON, $_POST['title']);
    $price = $_POST['price'];
    $duration_months = $_POST['duration_months'];
    $description = mysqli_real_escape_string($CON, $_POST['description']);
    $discount_percentage = $_POST['discount_percentage'];



    $sql = "INSERT INTO membership (name, price, duration_months, description, discount_percentage) VALUES ('$title', '$price', '$duration_months', '$description', '$discount_percentage')";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Membership added successfully!"
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
            "message" => "Please fill all the fields!",
            "required fields" => "token, title, price, duration_months, description, discount_percentage"
        )
    );
}
