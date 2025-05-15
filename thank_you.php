<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Booking</title>
    <script>
       
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
            window.location.href = 'checkroom.php'; 
        }, 3000); 
    </script>
</head>
<body>
    <h1>Booking/Reservation Status</h1>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p id='message' style='color: green;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']); 
    } elseif (isset($_SESSION['error'])) {
        echo "<p id='message' style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); 
    }
    ?>
</body>
</html>
