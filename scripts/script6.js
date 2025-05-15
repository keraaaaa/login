fetch('auth/booking_id.php')
.then(response => response.text())
.then(data => {
    document.getElementById('idCount').innerText = data;
})
.catch(error => {
    document.getElementById('idCount').innerText = "Error loading data";
});