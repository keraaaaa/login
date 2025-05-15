<?php
session_start();
include("connect/connect.php");

if (isset($_POST['image_path']) && isset($_POST['rating'])) {
    $imagePath = $_POST['image_path'];
    $rating = $_POST['rating'];

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating.']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE room_images SET rating = ? WHERE image_path = ?");
    $stmt->bind_param("is", $rating, $imagePath);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rating updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating rating.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
?>

?>
