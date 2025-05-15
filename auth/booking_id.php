<?php
session_start();
include("../connect/connect.php");

$query = "SELECT COUNT(id) as booking_count FROM bookings";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    echo $row['booking_count'];
} else {
    echo 0; 
}
?>
