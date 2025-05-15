<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style6.css" />
    <link rel="stylesheet" href="css/style8.css" />
    <link rel="stylesheet" href="css/style10.css"/>
    <style> 
     .form__group select {
            padding: 10px 12px; 
            font-size: 14px; 
            width: 100%; 
            background-color: transparent; 
            border: 2px solid #000;
            border-radius: 20px; 
            appearance: none; 
            outline: none; 
            cursor: pointer;
        }
.header__content h1 {
  font-size: 3.5rem;            
  line-height: 4rem;            
  font-weight: 600;            
  color: var(--white);                  
  white-space:nowrap;      
          
}
h4{
  color:white;
  font-size:50px;
}
.tooltip {
  position: absolute;
  bottom: 100%; 
  left: 50%;
  transform: translateX(-50%);
  background-color: transparent;
  color: black;
  border-radius: 10px;
  padding: 5px;
  font-size: 12px;
  opacity: 0;
  pointer-events: none;
  visibility: hidden;
  transition: opacity 0.3s, visibility 0.3s;
}

.floating-nav__item:hover .tooltip {
  opacity: 1;
  visibility: visible;
}


.button {
  width: 120px;
  height: 45px;
  display: flex;
  left:37em;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background-color:;
  border-radius: 30px;
  color: rgb(19, 19, 19);
  font-weight: 600;
  border: none;
  position: relative;
  cursor: pointer;
  transition-duration: .2s;
  box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.116);
  padding:10px;
  transition-duration: .5s;
}

.svgIcon {
  height: 25px;
  transition-duration: 1.5s;
}

.bell path {
  fill: rgb(19, 19, 19);
}

.button:hover {
  background-color:rgb(242, 255, 0);
  transition-duration: .5s;
}

.button:active {
  transform: scale(0.97);
  transition-duration: .2s;
}

.button:hover .svgIcon {
  transform: rotate(1040deg);
  transition-duration: 3s;
}
.owner-details {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
    text-align:center;
}

.owner-address, .owner-contact {
    margin: 5px 0;
}
.card, .card-body, .owner-container, img {
    border: none;  
    outline: none;            
}
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.card {
    width: 300px; 
    height: 250px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}


.card-body {
    position: relative;
    width: 100%;
    height: 250px; 
    display: flex;
    justify-content: center;
    align-items: center;
}
.image-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.card-body img {
    width: 100%;
    height: 100%;
    object-fit: cover;  
    border-radius: 10px;  
}

.rating {
    position: absolute;
    top: 10px; 
    left: 10px; 
    font-size: 18px;
    color: gold; 
    z-index: 10; 
}

.star {
    font-size: 20px;
    cursor:default;
}

.star.filled {
    color: gold; 
}

.star {
    color: gold; 
}
.nav__links{
  color:black;
}
ul li a:hover {
  background-color:rgb(242, 255, 0);

}
ul li a.active {
    background:rgb(242, 255, 0);
}
.nav-box {
  background-color:rgb(242, 255, 0);
}
.floating-nav__item a:hover {
    color:white;
  }
      </style>

    <title>CTU-DB | BHMS</title>
  </head>
  <body>
    <nav>
      <div class="nav__logo">CTU-DB | BHMS</div>
      <ul class="nav__links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a>Register ▾</a></li>
        </ul>
    </nav>
    
    <div class="floating-nav">
  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="student.php">
        <i class="fas fa-user-graduate"></i>
      </a>
      <span class="tooltip">Student</span>
    </div>
  </div>
  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="owners.php">
        <i class="fas fa-home"></i>
      </a>
      <span class="tooltip">Owners</span>
    </div>
  </div>

  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="complaint/complaint_form.php">
        <i class="fas fa-envelope"></i>
      </a>
      <span class="tooltip">Email Us</span>
    </div>
  </div>
</div>

<header class="section__container header__container">
  <div class="header__image__container">
    <div class="header__content">
      <h4>CTU STUDENTS</h4>
      <p>BOARDING HOUSE LOCATOR & MANAGEMENT SYSTEM</p><br>
      <h1>WELCOME VISITORS!</h1>
    </div>
    <div class="booking__container">  
      <form id="searchForm">  
        <div class="form__group">
          <div class="input__group">
            <select id="location" name="location">
              <option value="" disabled selected>Select Location ▾</option>
              <option value="Daanbantayan">Poblacion</option>
              <option value="Agujo">Agujo</option>
            </select>
          </div>
          <p>Where are you going?</p>
        </div>

        <div class="form__group">
          <div class="input__group">
            <select id="price_range" name="price_range">
              <option value="" disabled selected>Select Price Range ▾</option>
              <option value="500-700">₱500 - ₱700</option>
              <option value="800-1000">₱800 - ₱1,000</option>
            </select>
          </div>
          <p>What is your budget?</p>
        </div>
        <div class="form__group">
          <div class="input__group">
            <select id="looking_for" name="looking_for">
              <option value="" disabled selected>What are you looking for? ▾</option>
              <option value="Bedspacer">Bed Spacer</option>
              <option value="Private Room">Private Room</option>
            </select>
          </div>
          <p>What amenities are you interested in?</p>
        </div>
        
      </form>
      <button type="button" class="btn" onclick="redirectToLocation()"><i class="ri-search-line"></i></a></button>
    </div>
</header>
<section class="section__container popular__container">
    <?php
        include("connect/connect.php");

       $query = "SELECT ri.image_path, o.bhouse_name, ri.price_range, o.address, o.contact
          FROM room_images ri 
          JOIN owners o ON ri.owner_id = o.id 
          ORDER BY o.bhouse_name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentOwner = '';
        while ($row = $result->fetch_assoc()) {
            if ($currentOwner != $row['bhouse_name']) {
                if ($currentOwner != '') {
                    echo '</div>';
                }
                $currentOwner = $row['bhouse_name'];
                echo "<div class='owner-container'>
                        <div class='owner-name'>" . htmlspecialchars($currentOwner) . "</div>
                        <div class='owner-details'>
                            <div class='owner-address'>Address: " . htmlspecialchars($row['address']) . "</div>
                            <div class='owner-contact'>Contact: " . htmlspecialchars($row['contact']) . "</div>
                        </div>
                        <div class='card-container'>";
            }
            $rating = isset($row['rating']) ? (int) $row['rating'] : 'No Rating';

           $stars = "";
              for ($i = 0; $i < 5; $i++) {
                  if ($i < $rating) {
                      $stars .= "<span class='star filled'>★</span>";
                  } else {
                      $stars .= "<span class='star'>☆</span>";
                  }
              }

            echo "<div class='card' data-price='" . htmlspecialchars($row['price_range']) . "'>
                    <div class='card-body'>
                        <div class='image-container'>
                            <img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['bhouse_name']) . "'>
                            <div class='rating'>$stars</div> 
                        </div>
                        <div class='price-range'>" . htmlspecialchars($row['price_range']) . "</div>
                    </div>
                  </div>";
        }
        if ($currentOwner != '') {
            echo '</div>';
        }

        $stmt->close();
    ?>
</div>

<div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-details">
                <div id="caption"></div>
                <div class="price-range" id="priceRange"></div>
            </div>
        </div>
    </div>
    </section>

    <section class="section__container">
  <div class="more__container">
    <p>Looking for more?</p>
    <h4>Click button below and look for places you prefer.</h4>
    <a href="https://www.google.com/maps/place/Cebu+Technological+University+Daanbantayan+Campus/@11.2631681,124.0144792,3459m/data=!3m1!1e3!4m6!3m5!1s0x33a877b0a980f5e7:0xfe7d8c4f7a99b59d!8m2!3d11.2676734!4d124.0086328!16s%2Fg%2F11bysjv_dc?entry=ttu&g_ep=EgoyMDI0MTExMy4xIKXMDSoASAFQAw%3D%3D" target="_blank">
    <button class="button">
   <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path></svg>
  Explore
</button>
    </a>
  </div>
</section>

    <footer class="footer">
  <div class="footer__content">
    <p>&copy; 2024 CTU-Students | Boarding House Locator Management System. All rights reserved.</p>
    <p><a href="https://www.facebook.com/?_rdc=2&_rdr#">Privacy Policy</a> | <a href="https://www.youtube.com/">Terms of Service</a></p>
  </div>
</footer>

<script src="scripts/script1.js"></script>

  </body>
</html>