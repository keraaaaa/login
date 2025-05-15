<?php
session_start();
include("../connect/connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $image_path = $_POST['image_path'];  
    $user_name = $_POST['user_name'];    
    $booking_date = $_POST['booking_date']; 
    $user_email = $_SESSION['email'];    
    $action = $_POST['action'];         

    $image_path = mysqli_real_escape_string($conn, $image_path);
    $user_name = mysqli_real_escape_string($conn, $user_name);
    $booking_date = mysqli_real_escape_string($conn, $booking_date);

    if (!empty($image_path) && !empty($user_name) && !empty($booking_date) && !empty($user_email)) {

        if ($action == 'reserve') {
            $query = "INSERT INTO reservations (user_email, user_name, image_path, date, status) 
                      VALUES (?, ?, ?, ?, 'pending')";

            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("ssss", $user_email, $user_name, $image_path, $booking_date);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Reservation successful! Your reservation is pending.";
                    header("Location: ../thank_you.php"); 
                    exit(); 
                } else {
                    $_SESSION['error'] = "Error executing query: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Error preparing statement: " . $conn->error;
            }
        } elseif ($action == 'book') {
            $query = "INSERT INTO bookings (user_email, user_name, image_path, date, status) 
                      VALUES (?, ?, ?, ?, 'pending')";

            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("ssss", $user_email, $user_name, $image_path, $booking_date);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Booking successful! Your booking is confirmed.";
                    header("Location: ../thank_you.php"); 
                    exit();
                } else {
                    $_SESSION['error'] = "Error executing query: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Error preparing statement: " . $conn->error;
            }
        }

    } else {
        $_SESSION['error'] = "Required data not provided.";
    }

    $conn->close();
}
?>
