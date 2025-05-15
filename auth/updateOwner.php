<?php
include('../connect/connect.php');

$Id = $_GET['id'];

$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$bhouse_name = $_POST['bhouse_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];  
$address = $_POST['address'];  

$sql = "UPDATE `owners` SET 
            first_name='$first_name', 
            last_name='$last_name', 
               bhouse_name='$bhouse_name',
            email='$email', 
            contact='$contact', 
            address='$address' 
        WHERE id='$Id'";


if (mysqli_query($conn, $sql)) {
 
    header('Location: ../home_owner.php'); 
    exit();
} else {
    
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
