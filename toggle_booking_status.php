<?php
session_start();
include("connect/connect.php");

if (isset($_GET['id']) && isset($_GET['status'])) {
    $bookingId = $_GET['id'];
    $status = $_GET['status'];

    $query = "UPDATE bookings SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("si", $status, $bookingId);
        $stmt->execute();

        if ($status == 'confirmed') {
            $stmt = $conn->prepare("SELECT user_email FROM bookings WHERE id = ?");
            $stmt->bind_param("i", $bookingId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $userEmail = $row['user_email'];

                $message = "Hello, your booking request has been accepted. The room is now confirmed for your stay. Thank you for choosing our service.";
                $notificationQuery = "INSERT INTO notif (user_email, message) VALUES (?, ?)";
                if ($stmt2 = $conn->prepare($notificationQuery)) {
                    $stmt2->bind_param("ss", $userEmail, $message);
                    $stmt2->execute();
                } else {
                    echo "Error inserting notification: " . $conn->error;
                }
            }
        }

        header("Location: roomusers.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
