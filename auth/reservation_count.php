<?php
session_start();
include("../connect/connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT COUNT(id) as total_reservations FROM reservations WHERE status = 'pending'"; 

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['total_reservations'];
} else {
    echo "Error fetching data: " . mysqli_error($conn);  
}
mysqli_close($conn);
?>
