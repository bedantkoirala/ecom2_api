<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (isset($_POST['token'])) {
    $token = $_POST['token'];

    if (!isAdmin($token)) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"
        ));
        die();
    }

    global $CON;

    // Fetch total income
    $sql = 'SELECT SUM(total) AS total_income FROM orders WHERE status = "paid"';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_income = $row['total_income'];

    // Fetch total users
    $sql = 'SELECT COUNT(*) AS total_users FROM users';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_users = $row['total_users'];

    // Fetch total orders
    $sql = 'SELECT COUNT(*) AS total_orders FROM orders';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_orders = $row['total_orders'];

    // Fetch total products
    $sql = 'SELECT COUNT(*) AS total_products FROM products';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_products = $row['total_products'];

    // Fetch total feedbacks
    $sql = 'SELECT COUNT(*) AS total_feedbacks FROM ratings ';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_feedbacks = $row['total_feedbacks'];

    // Fetch total subscriptions
    $sql = 'SELECT COUNT(*) AS total_subscriptions FROM subscription';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_subscriptions = $row['total_subscriptions'];

    if ($result) {
        echo json_encode(array(
            "success" => true,
            "message" => "Stats fetched successfully!",
            "data" => array(
                "total_income" => $total_income,
                "total_users" => $total_users,
                "total_orders" => $total_orders,
                "total_products" => $total_products,
                "total_feedbacks" => $total_feedbacks,
                "total_subscriptions" => $total_subscriptions
            )
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Fetching statistics failed!"
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"
    ));
}
?>
