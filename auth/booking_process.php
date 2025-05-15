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
    $duration = $_POST['duration'];     

    $image_path = mysqli_real_escape_string($conn, $image_path);
    $user_name = mysqli_real_escape_string($conn, $user_name);
    $booking_date = mysqli_real_escape_string($conn, $booking_date);

    if (!empty($image_path) && !empty($user_name) && !empty($booking_date) && !empty($user_email)) {

        $query = "SELECT owner_id FROM room_images WHERE image_path = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $image_path);
            $stmt->execute();
            $stmt->bind_result($owner_id);
            $stmt->fetch();
            $stmt->close();
        }

        if ($action == 'reserve') {
            $query = "INSERT INTO reservations (user_email, user_name, image_path, date, duration, status) 
                      VALUES (?, ?, ?, ?, ?, 'pending')";
        
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("sssss", $user_email, $user_name, $image_path, $booking_date, $duration);
                if ($stmt->execute()) {
            
                    $notification_message = "A reservation has been made for your room by " . $user_name;
                    $notification_query = "INSERT INTO notifications (user_email, owner_id, message) VALUES (?, ?, ?)";
                    if ($notification_stmt = $conn->prepare($notification_query)) {
                        $notification_stmt->bind_param("sis", $user_email, $owner_id, $notification_message);
                        if ($notification_stmt->execute()) {
                            $notification_stmt->close();
                            echo "Reservation successful! Your reservation is pending.";  
                            exit();
                        } else {
                            echo "Error inserting notification: " . $notification_stmt->error;  
                        }
                    }
                } else {
                    echo "Error executing query: " . $stmt->error;  
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } elseif ($action == 'book') {
            $query = "INSERT INTO bookings (user_email, user_name, image_path, date, duration, status) 
                      VALUES (?, ?, ?, ?, ?, 'pending')";
        
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("sssss", $user_email, $user_name, $image_path, $booking_date, $duration);
                if ($stmt->execute()) {
                    
                    $notification_message = "A booking has been made for your room by " . $user_name;
                    $notification_query = "INSERT INTO notifications (user_email, owner_id, message) VALUES (?, ?, ?)";
                    if ($notification_stmt = $conn->prepare($notification_query)) {
                        $notification_stmt->bind_param("sis", $user_email, $owner_id, $notification_message);
                        if ($notification_stmt->execute()) {
                            $notification_stmt->close();
                            echo "Booking successful! Your booking is confirmed.";  
                            exit();
                        } else {
                            echo "Error inserting notification: " . $notification_stmt->error;  
                        }
                    }
                } else {
                    echo "Error executing query: " . $stmt->error;  
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
        
    } else {
        echo "Required data not provided."; 
    }

    $conn->close();
}
?>
