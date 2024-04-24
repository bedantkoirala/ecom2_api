<?php

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';
if (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['price']) &&
    isset($_POST['category']) &&
    isset($_POST['token']) &&
    isset($_FILES['image'])

) {
    global $CON;
    $title = mysqli_real_escape_string($CON, $_POST['title']);
    $token = $_POST['token'];
    $description = mysqli_real_escape_string($CON, $_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];


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

    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);


    $allowed_extensions = array('jpg', 'jpeg', 'png', 'webp');

    if (!in_array($image_extension, $allowed_extensions)) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Please upload a valid image!"
            )
        );
        die();
    }

    if ($image_size > 5000000) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Image size must be less than 5MB!"
            )
        );
        die();
    }

    $image_new_name = time() . '_' . $image_name;

    $upload_path = 'images/' . $image_new_name;

    if (!move_uploaded_file($image_tmp_name, $upload_path)) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Image upload failed!"
            )
        );
        die();
    }



    $sql = "INSERT INTO products (title, description, price, category_id, image_url) VALUES ('$title', '$description', '$price', '$category', '$upload_path')";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Product added successfully!"
            )
        );
    } else {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Adding product failed!",
                "error" => mysqli_error($CON)
            )
        );
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Please fill all the fields!",
            "required fields" => "token, title, description, price, category, image"
        )
    );
}
