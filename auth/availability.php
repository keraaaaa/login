<?php
session_start();
include("../connect/connect.php");

if (isset($_GET['id']) && isset($_GET['status'])) {
    $imageId = $_GET['id'];
    $status = $_GET['status'] == 'available' ? 1 : 0;

    $query = "UPDATE room_images SET is_available = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $status, $imageId);
    $stmt->execute();


    header("Location: ../roomowner.php");
    exit();
} else {

    header("Location: ../roomowner.php");
    exit();
}
