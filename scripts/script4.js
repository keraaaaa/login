var modal = document.getElementById("imageModal");

var images = document.querySelectorAll('.card-body img');
images.forEach(function(img) {
    img.onclick = function() {
        modal.style.display = "flex"; 
        document.getElementById("modalImage").src = this.src; 
        document.getElementById("caption").innerHTML = this.alt; 
        
        var card = this.closest('.card');
        var priceRange = card.getAttribute('data-price');
        document.getElementById("priceRange").innerHTML = 'Price: ' + priceRange;
    }
});

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
    document.getElementById("bookNowBtn").onclick = function() {

    alert("Log in first to book.");
    
    setTimeout(function() {
        window.location.href = "#"; 
    }, 1000); 
}