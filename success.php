<?php

if (isset($_GET['message'])) {
    $successMessage = htmlspecialchars($_GET['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Payment Success</title>
   <style>

button[type="submit"] {
    background-color: #4CAF50;  
    color: white;  
    font-size: 16px;  
    padding: 12px 20px;  
    border: none;  
    border-radius: 5px;  
    cursor: pointer;  
    transition: background-color 0.3s;  
}

button[type="submit"]:hover {
    background-color: #45a049;  
}

.success-message {
    padding: 20px;
    background-color: #dff0d8;  
    color: #3c763d;  
    border-radius: 5px;
    border: 1px solid #d6e9c6;
    margin-top: 20px;
    text-align: center; 
    font-size: 18px; 
    max-width: 600px;  
    margin: 50px auto;  
}

.main-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh; 
    text-align: center;
}

h1 {
    margin-bottom: 20px;
    font-size: 32px;
    font-weight: bold;
    color: #333;
}

a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

a:hover {
    background-color: #0056b3;
}



    </style>

</head>
<body>
    <div class="main-content">
        <h1>Payment Transaction</h1>
        
        <?php if (isset($successMessage)): ?>
            <div class="success-message">
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php endif; ?>

        <a href="ownerslog.php">Proceed to dashboard</a>
    </div>
</body>
</html>
