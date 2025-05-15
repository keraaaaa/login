<?php
session_start();
include('connect/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $transaction_id = isset($_POST['transaction-id']) ? $_POST['transaction-id'] : '';
    $phone_number = isset($_POST['phone-number']) ? $_POST['phone-number'] : '';
    $term = isset($_POST['term']) ? $_POST['term'] : '';
    $mode_of_payment = isset($_POST['mode_of_payment']) ? $_POST['mode_of_payment'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $insertQuery = "
    INSERT INTO transactions (transaction_id, phone_number, term, mode_of_payment, email)
    VALUES (?, ?, ?, ?, ?)
    ";

    if ($stmt = $conn->prepare($insertQuery)) {

        $stmt->bind_param("sssss", $transaction_id, $phone_number, $term, $mode_of_payment, $email);

        if ($stmt->execute()) {

            header("Location: payment_success.php?message=Payment%20transaction%20submitted%20successfully.");
            exit(); 
        } else {
            
            $errorMessage = "There was an error submitting your payment.";
        }
        $stmt->close();
    } else {
        die("Error preparing insert query: " . $conn->error);
    }
}
?>
