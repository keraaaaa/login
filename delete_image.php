<?php
session_start();
include("connect/connect.php");

if (isset($_GET['image'])) {
    $imagePath = $_GET['image'];
    

    $ownerId = $_SESSION['owner_id'];

    if (file_exists($imagePath)) {
        unlink($imagePath); 
    }

    $query = "DELETE FROM room_images WHERE image_path = ? AND owner_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $imagePath, $ownerId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      
        header("Location: roomowner.php");
    } else {
        echo "Error: Image not found or you don't have permission to delete it.";
    }
} else {
    echo "Error: No image path specified.";
}
?>
