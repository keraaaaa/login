<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../connect/connect.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['register'])) {

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $bhouse_name = $_POST['bhouse_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];

            echo '<pre>';
            print_r($_FILES['image']);
            echo '</pre>';

            $uploadDir = '../uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $uniqueImageName = uniqid() . '_' . basename($imageName);

            $targetFilePath = $uploadDir . $uniqueImageName;

            if (!move_uploaded_file($imageTmpPath, $targetFilePath)) {
                echo "Failed to upload image.";
                exit(); 
            }

            $imagePath = $targetFilePath;
        }

        $stmt = $conn->prepare("INSERT INTO owners (first_name, last_name, bhouse_name, email, password, contact, address, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssss", $first_name, $last_name, $bhouse_name, $email, $password, $contact, $address, $imagePath);

        if ($stmt->execute()) {
            $_SESSION['status'] = "Registration successful for $bhouse_name!";
            header("Location: ../owners.php");
            exit();
        } else {

            $message = "Error: " . $stmt->error;
            echo $message; 
            exit();
        }
        $stmt->close();
    }

    elseif (isset($_POST['login'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM owners WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $owner = $result->fetch_assoc();

        if ($owner && password_verify($password, $owner['password'])) {
            $_SESSION['owner_id'] = $owner['id']; 
            $_SESSION['email'] = $owner['email']; 
            header("Location: ../ownerslog.php");
            exit();
        } else {

            $_SESSION['status'] = "Incorrect email/password.";
            header("Location: ../owners.php");
            exit();
        }
    }
}
?>
