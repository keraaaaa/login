<?php
include('../connect/connect.php');

$Id = $_GET['id'];

$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$email = $_POST['email'];
$tele = $_POST['tele'];  
$gender = $_POST['gender']; 
$school = $_POST['school']; 
$address = $_POST['address']; 
$emergencyContact = $_POST['emergencyContact']; 

$sql = "UPDATE `users` SET 
            firstname='$firstName', 
            lastname='$lastName', 
            email='$email', 
            tele='$tele', 
            gender='$gender', 
            school='$school', 
            address='$address', 
            emergency_contact='$emergencyContact' 
        WHERE id='$Id'";

if (mysqli_query($conn, $sql)) {
    header("Location: ../settings.php");
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
