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


if (!isset($_POST['order_id'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "OrderId is required!"
        )
    );
    die();
}

global $CON;

$token = $_POST['token'];
$userId = getUserId($token);
$order_id = $_POST['order_id'];


$sql = "SELECT * FROM order_details
join products on products.product_id = order_details.product_id
join categories on categories.category_id = products.category_id
 WHERE order_id = $order_id";



$result = mysqli_query($CON, $sql);


if ($result) {
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    echo json_encode(
        array(
            "success" => true,
            "message" => "Orders fetched successfully!",
            "data" => $orders,

        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Fetching orders failed!",
            "error" => mysqli_error($CON)

        )
    );
}
