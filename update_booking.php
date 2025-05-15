<?php
session_start();
include("connect/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['email'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $imagePath = isset($_POST['image_path']) ? $_POST['image_path'] : null;
    $bookingDate = isset($_POST['booking_date']) ? $_POST['booking_date'] : null;
    $newDate = isset($_POST['new_date']) ? $_POST['new_date'] : null;
    $newDuration = isset($_POST['new_duration']) ? $_POST['new_duration'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;

    if (!$imagePath || !$bookingDate || !$newDate || !$newDuration || !$type) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $TimeDuration = '';
    if ($newDuration == '24') {
        $TimeDuration = '24 hours';
    } elseif ($newDuration == '48') {
        $TimeDuration = '2 days';
    } else {

        echo json_encode(['success' => false, 'message' => 'Invalid duration']);
        exit;
    }

    if ($type === 'booking') {
        $query = "
            UPDATE bookings 
            SET date = ?, duration = ? 
            WHERE image_path = ? AND user_email = ? AND date = ?
        ";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $newDate, $TimeDuration, $imagePath, $_SESSION['email'], $bookingDate);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Booking updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating booking']);
        }

        mysqli_stmt_close($stmt);
    } 
    else if ($type === 'reservation') {
        $query = "
            UPDATE reservations 
            SET date = ?, duration = ? 
            WHERE image_path = ? AND user_email = ? AND date = ?
        ";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $newDate, $TimeDuration, $imagePath, $_SESSION['email'], $bookingDate);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Reservation updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating reservation']);
        }

        mysqli_stmt_close($stmt);
    } 
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid type']);
    }

    mysqli_close($conn);
}
?>
