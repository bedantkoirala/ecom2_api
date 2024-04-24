<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (

    isset($_POST['token'])

) {
    $token = $_POST['token'];




    if (!isset($_POST['product_id'])) {
        echo json_encode(array(
            "success" => false,
            "message" => "Product id is required!"

        ));
        die();
    }

    global $CON;


    $product_id = $_POST['product_id'];



    $sql = "select ratings.*,full_name,email from ratings join users on users.user_id = ratings.user_id where product_id='$product_id' and review is not null order by rating_id desc";

    $result = mysqli_query($CON, $sql);


    if ($result) {

        $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);


        $sql = "select rating from products where product_id = $product_id";
        $result = mysqli_query($CON, $sql);

        $product = mysqli_fetch_assoc($result);


        echo json_encode(array(
            "success" => true,
            "message" => "Reviews fetched successfully!",
            "reviews" => $reviews,
            "rating" => $product['rating']

        ));
    } else {

        echo json_encode(array(
            "success" => false,
            "message" => "Fetching reviews failed!"

        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"

    ));
}
