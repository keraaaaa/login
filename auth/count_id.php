<?php

include ("../connect/connect.php"); 


$sql = "SELECT COUNT(Id) AS total_ids FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['total_ids'];
} else {
    echo "0";
}

$conn->close();
?>
