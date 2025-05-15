function redirectToLocation() {

    var location = document.getElementById("location").value;
    var priceRange = document.getElementById("price_range").value;
    var lookingFor = document.getElementById("looking_for").value;

    if (!location || !priceRange || !lookingFor) {

      alert("Please fill in all the fields before searching.");
      return; 
    }

    var url = "search.php?";

    if (location) {
      url += "location=" + location + "&";
    }
    if (priceRange) {
      url += "price_range=" + priceRange + "&";
    }
    if (lookingFor) {
      url += "looking_for=" + lookingFor + "&";
    }

    url = url.slice(0, -1); 

    window.location.href = url; 
  }