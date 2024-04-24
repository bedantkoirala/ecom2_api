<?php

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (!isset($_POST['token'])) {
    echo json_encode([
        "status" => 400,
        "message" => "Token not found"
    ]);
    exit;
}
$token = $_POST['token'];
$userId = getUserId($token);
if (!$userId) {
    echo json_encode([
        "status" => 400,
        "message" => "Invalid token"
    ]);
    exit;
}



if (isset(
    $_POST['product_id'],
    $_POST['rating']
)) {

    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];


    $sql = "select * from ratings where product_id = $product_id AND user_id = $userId";

    $result = mysqli_query($CON, $sql);


    $rating_id = null;

    if (mysqli_num_rows($result) > 0) {
        $ratingData = mysqli_fetch_assoc($result);
        $rating_id = $ratingData['rating_id'];
    }

    $sql = '';
    $review = null;
    if (isset($_POST['review'])) {
        $review = $_POST['review'];
        $review = "'" . mysqli_real_escape_string($CON, $_POST['review']) . "'";
    } else {
        $review = "NULL";
    }

    // Then, when constructing your SQL query, handle the review string appropriately:
    if ($rating_id != null) {
        if ($review == "NULL") {
            $sql = "UPDATE ratings SET rating = $rating WHERE rating_id = $rating_id";
        } else {

            $sql = "UPDATE ratings SET rating = $rating, review = $review WHERE rating_id = $rating_id";
        }
    } else {
        $sql = "INSERT INTO ratings (user_id, product_id, rating, review) VALUES ($userId, $product_id, $rating, $review)";
    }


    $result = mysqli_query($CON, $sql);


    if ($result) {
        echo json_encode(array(
            "success" => true,
            "message" => "Rating added successfully",

        ));

        $sql = "UPDATE products SET rating = (SELECT AVG(rating) FROM ratings WHERE product_id = $product_id) WHERE product_id = $product_id";
        $result = mysqli_query($CON, $sql);
        die();
    }

    echo json_encode(array(
        "success" => false,
        "message" => "Failed to add rating",
        "error" => mysqli_error($CON)
    ));
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "product_id and rating are required"
    ));
    die();
}
