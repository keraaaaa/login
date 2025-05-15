<?php
session_start();
include("connect/connect.php");

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $deleteQuery = "DELETE FROM bookings WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $bookingId);
        if ($deleteStmt->execute()) {
            header("Location: roomusers.php?status=deleted");
            exit();
        } else {
            header("Location: roomusers.php?status=error");
            exit();
        }
    } else {
        header("Location: roomusers.php?status=notfound");
        exit();
    }
} else {
    header("Location: roomusers.php?status=error");
    exit();
}
?>
