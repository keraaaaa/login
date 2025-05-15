<?php
session_start();
include("connect/connect.php");

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerslog.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image_id'])) {
    $imageId = $_POST['image_id'];
    $description = $_POST['description'];
    $roomLocation = $_POST['room_location'];
    $priceRange = $_POST['price_range'];
    $roomType = $_POST['room_type'];

    $query = "UPDATE room_images SET description = ?, room_location = ?, price_range = ?, room_type = ? WHERE id = ? AND owner_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("sssssi", $description, $roomLocation, $priceRange, $roomType, $imageId, $_SESSION['owner_id']);
    $stmt->execute();
    header("Location: update_room.php?update=success");
    exit();
}
?>
