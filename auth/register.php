<?php
session_start();
include("../connect/connect.php"); 

session_unset();
session_destroy();
session_start();

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $dob = date('Y-m-d', strtotime($_POST['dateofbirth']));
    $email = $_POST['email'];
    $tele = $_POST['tel'];
    $password = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $gender = $_POST['gender'];
    $school = $_POST['school'];
    $address = $_POST['address']; 
    $emergencyContact = $_POST['emergencyContact'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: ../student.php");
        exit();
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,10}$/';
    
    if (!preg_match($passwordPattern, $password)) {
      
        $_SESSION['status'] = "Password must contain uppercase/lowercase, number, and symbol.";
        header("Location: ../student.php");
        exit();
    }

    if (!preg_match('/^\d{7}$/', $school)) {
        $_SESSION['status'] = "School ID must be exactly 7 digits.";
        header("Location: ../student.php");
        exit();
    }

    if ($password !== $cpass) {
        $_SESSION['status'] = "Password and confirm password do not match.";
        header("Location: ../student.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['status'] = "Email already exists!";
        header("Location: ../student.php");
        exit();

    } else {
        $insertQuery = "INSERT INTO users (firstName, lastName, dob, email, tele, password, gender, school, address, emergency_contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssssss", $firstName, $lastName, $dob, $email, $tele, $hashed_password, $gender, $school, $address, $emergencyContact);

        if ($stmt->execute()) {
           $_SESSION['status'] = "Registration successful for $firstName!";
            header("Location: ../student.php");
            exit();
        } 
        else {
            echo "Error:" . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: ../student.php");
        exit();
    }
            
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            header("Location: ../studentdb.php");
            exit();
        }
         else {
            $_SESSION['status'] = "Incorrect password.";
            header("Location: ../student.php");
            exit();
        }
    }  else {
        $_SESSION['status'] = "Email not registered.";
        header("Location: ../student.php");
        exit();
    }
}
?>
