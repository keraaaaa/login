<?php
session_start();

include("connect/connect.php");

if (!isset($_SESSION['owner_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['bhouse_name'])) {
    $ownerId = $_SESSION['owner_id'];

    $query = "SELECT bhouse_name FROM owners WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $_SESSION['bhouse_name'] = $row['bhouse_name'];  
    } else {
        $_SESSION['bhouse_name'] = "Default Name";  
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transactionId = $_POST['transaction_id'];
    $ownerId = $_SESSION['owner_id'];
    
    $bhouseName = $_SESSION['bhouse_name'];

    if (!preg_match('/^\d{13}$/', $transactionId)) {
        echo "Error: Transaction ID must be a 13-digit code!";
        exit;
    }

    if (isset($_POST['phone_number'])) {
        $phoneNumber = $_POST['phone_number']; 
    } else {
        echo "Error: Phone number is missing!";
        exit;
    }

    $query = "INSERT INTO payments (owner_id, transaction_id, phone_number, bhouse_name, payment_date) 
              VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $ownerId, $transactionId, $phoneNumber, $bhouseName);

    if ($stmt->execute()) {
        header("Location: success.php?message=Payment%20transaction%20submitted%20successfully.");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
