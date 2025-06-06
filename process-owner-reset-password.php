<?php

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/connect/connect.php";

$sql = "SELECT * FROM owners WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired.");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters.");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter.");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number.");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match.");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE owners
        SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $password_hash, $user["id"]);
$stmt->execute();

session_start();
session_unset();
session_destroy();

echo "Password updated. You can now log in.";

echo "<script>
        setTimeout(function() {
            window.location.href = 'owners.php'; 
        }, 2000); 
      </script>";
?>
