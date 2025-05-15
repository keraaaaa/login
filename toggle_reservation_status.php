<?php
session_start();
include("connect/connect.php");

if (isset($_GET['id']) && isset($_GET['status'])) {
    $reservationId = $_GET['id'];
    $newStatus = $_GET['status'];

    if (!in_array($newStatus, ['pending', 'confirmed'])) {
        $_SESSION['error'] = "Invalid status.";
        header("Location: roomusers.php");
        exit();
    }

    $query = "UPDATE reservations SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("si", $newStatus, $reservationId);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Reservation status updated successfully.";

            if ($newStatus == 'confirmed') {

                $stmt = $conn->prepare("SELECT user_email FROM reservations WHERE id = ?");
                $stmt->bind_param("i", $reservationId);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    $userEmail = $row['user_email'];

                    $message = "Hello, your reservation request has been accepted. Your reservation is now confirmed. Thank you for choosing our service.";

                    $notificationQuery = "INSERT INTO notif (user_email, message) VALUES (?, ?)";
                    if ($stmt2 = $conn->prepare($notificationQuery)) {
                        $stmt2->bind_param("ss", $userEmail, $message);
                        $stmt2->execute();
                    } else {
                        echo "Error inserting notification: " . $conn->error;
                    }
                }
            }
        } else {
            $_SESSION['error'] = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }

    header("Location: roomusers.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: roomusers.php");
    exit();
}

$conn->close();
?>
