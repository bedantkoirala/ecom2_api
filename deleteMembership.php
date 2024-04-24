<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

// Check if required parameters are set
if (isset($_POST['membership_id'], $_POST['token'])) {
    // Extract parameters
    $membershipId = $_POST['membership_id'];
    $token = $_POST['token'];

    // Check if user is authorized as admin
    if (!isAdmin($token)) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"
        ));
        die();
    }

    global $CON;

    // Construct SQL DELETE query
    $sql = "DELETE FROM membership WHERE membership_id = '$membershipId'";

    // Execute SQL query
    $result = mysqli_query($CON, $sql);

    // Check if query was successful
    if ($result) {
        echo json_encode(array(
            "success" => true,
            "message" => "Membership deleted successfully!"
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Failed to delete membership!"
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Please provide membership ID and token!"
    ));
}
?>
