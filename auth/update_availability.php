<?php

include("connect/connect.php");

$data = json_decode(file_get_contents('php://input'), true);

$image_id = (int)$data['image_id'];
$new_status = (int)$data['new_status'];

$query = "UPDATE room_images SET is_available = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $new_status, $image_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'new_status' => $new_status]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update availability']);
}

$stmt->close();
$conn->close();
?>
