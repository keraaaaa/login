<?php
session_start();
include("connect/connect.php");

$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if ($email) {

    $updateQuery = "UPDATE notif SET status = 'read' WHERE user_email = ? AND status = 'unread'";

    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error marking notifications as read.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
}
?>
