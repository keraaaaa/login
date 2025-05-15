<?php
session_start();
include("connect/connect.php");

if (isset($_POST['id'])) {
    $notifId = $_POST['id'];

    $query = "UPDATE notif SET status = 'read' WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $notifId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error marking notification as read."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error preparing query."]);
    }
}
?>
