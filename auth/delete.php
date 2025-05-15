<?php
        $Id=$_GET['id'];
        include('../connect/connect.php');
        mysqli_query($conn,"delete from `users` where id='$Id'");
        header('location:../settings.php');
?>