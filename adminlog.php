<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Form</title>
    <link rel="stylesheet" href="css/style9.css" />
    <style>
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-image: url('bckgnds/index.jpg');
    background-size: cover;
    background-repeat: no-repeat;
}
</style>
</head>
<body>
    <div class="admin-login-container">
        <h2>Admin Login</h2>
        <form id="adminLoginForm">
            <input type="text" id="userName" placeholder="Enter Admin Name" required>
            <input type="text" id="adminUsername" placeholder="Admin Username" value="Admin" disabled>
            <input type="password" id="adminPassword" placeholder="Admin Password" required>
            <div class="error" id="adminErrorMessage"></div>
            <button type="submit">Login</button>
        </form>
        <button id="backButton">Back</button>
        <div class="welcome-message" id="welcomeMessage"></div>
    </div>
    <script src="scripts/script8.js"></script>
</body>
</html>
