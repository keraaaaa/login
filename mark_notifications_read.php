<?php
session_start();
include("connect/connect.php");

if (isset($_SESSION['owner_id'])) {
    $ownerId = $_SESSION['owner_id'];

    $query = "UPDATE notifications SET status = 'read' WHERE owner_id = ? AND status = 'unread'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Notifications marked as read.";
    } else {
        echo "No unread notifications found.";
    }
}
?>
