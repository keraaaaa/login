<?php
session_start();
include("connect/connect.php");

if (isset($_POST['notification_id']) && isset($_POST['status'])) {
    $notificationId = $_POST['notification_id'];
    $status = $_POST['status'] == 'unread' ? 'unread' : 'read';
    
    $query = "UPDATE notif SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("si", $status, $notificationId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update notification status."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error preparing query."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
