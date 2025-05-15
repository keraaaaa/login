let adminCredentials = {
    username: "Admin",
    password: "admin123"
};

document.getElementById('adminLoginForm').addEventListener('submit', function (e) {
    e.preventDefault(); 

    const adminUsername = document.getElementById('adminUsername').value;
    const adminPassword = document.getElementById('adminPassword').value;
    const userName = document.getElementById('userName').value;
    const welcomeMessage = document.getElementById('welcomeMessage');
    const adminErrorMessage = document.getElementById('adminErrorMessage');

    if (adminUsername === adminCredentials.username && adminPassword === adminCredentials.password) {
        alert("Admin login successful!");
        adminErrorMessage.style.display = 'none';  
        welcomeMessage.textContent = `Welcome, ${userName}!`;
        welcomeMessage.style.display = 'block'; 
       
        document.getElementById("adminPassword").value = '';

        setTimeout(() => {
            window.location.href = "stadmin.php";
        }, 2000);
        
    } else {
        adminErrorMessage.textContent = "Invalid admin password!";
        adminErrorMessage.style.display = 'block';  
        welcomeMessage.style.display = 'none';

        document.getElementById("adminPassword").value = '';
    }
});
  
  document.getElementById('backButton').addEventListener('click', function() {
    window.history.back();
});