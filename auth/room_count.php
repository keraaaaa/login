<?php
session_start();
include("../connect/connect.php");

$query = "SELECT COUNT(id) as room_count FROM room_images";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    echo $row['room_count'];
} else {
    echo 0; 
}
?>
