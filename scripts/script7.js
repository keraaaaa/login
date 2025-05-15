var modal = document.getElementById("myModal");
var modalImg = document.getElementById("modalImage");
var bookingForm = document.getElementById("bookingForm");
var span = document.getElementsByClassName("close")[0];
var imageLinks = document.querySelectorAll('.image-link');

imageLinks.forEach(function(link) {
    link.onclick = function(e) {
        e.preventDefault();
        modal.style.display = "block";
        modalImg.src = this.getAttribute('data-image');
        document.getElementById('image_path').value = this.getAttribute('data-image');
    }
});

span.onclick = function() {
    modal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

document.getElementById('form').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch('auth/booking_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        modal.style.display = "none";
    })
    .catch(error => console.error('Error:', error));
};