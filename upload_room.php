<?php
session_start();
include("connect/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
 
        $ownerId = $_SESSION['owner_id'];
        $roomLocation = $_POST['location'];
        $price = $_POST['price'];
        $roomType = $_POST['room_type'];
        $description = $_POST['description'];

        $description = mysqli_real_escape_string($conn, $description);

        $targetDirectory = "upload/";
        $fileName = basename($_FILES["room_image"]["name"]);
        $targetFilePath = $targetDirectory . $fileName;

        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], $targetFilePath)) {

            $query = "INSERT INTO room_images (owner_id, image_path, room_location, price_range, room_type, description) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isssss", $ownerId, $targetFilePath, $roomLocation, $price, $roomType, $description);
            $stmt->execute();
            $stmt->close();

            header("Location: roomowner.php");
            exit();
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "No file was uploaded.";
    }
}
?>
