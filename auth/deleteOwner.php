<?php

$Id = intval($_GET['id']);
include('../connect/connect.php');

mysqli_begin_transaction($conn);

try {

    $deleteRoomImages = "DELETE FROM `room_images` WHERE `owner_id` = ?";
    if ($stmt = mysqli_prepare($conn, $deleteRoomImages)) {
        mysqli_stmt_bind_param($stmt, "i", $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $deleteOwner = "DELETE FROM `owners` WHERE `id` = ?";
    if ($stmt = mysqli_prepare($conn, $deleteOwner)) {
        mysqli_stmt_bind_param($stmt, "i", $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_commit($conn);

    header('Location: ../home_owner.php');
    exit();

} catch (Exception $e) {

    mysqli_roll_back($conn);
    echo "Error deleting owner: " . $e->getMessage();
}

mysqli_close($conn);
?>
