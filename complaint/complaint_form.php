<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
      rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style13.css">
    <link rel="stylesheet" href="../css/style6.css" />
    <style>
        .tooltip {
  position: absolute;
  bottom: 100%; 
  left: 50%;
  transform: translateX(-50%);
  background-color: transparent;
  color: #63b1f1;
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
      </style>
</head>
<body>
  <nav>
      <div class="nav__logo">CTU-DB | BHMS</div>
      <ul class="nav__links">
            <li><a href="../index.php" class="active">Home</a></li>
            <li><a href="../about_us.php">About Us</a></li>
            <li><a>Register ▾</a></li>
        </ul>
    </nav> 

    <div class="floating-nav">
  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="../student.php">
        <i class="fas fa-user-graduate"></i>
      </a>
      <span class="tooltip">Student</span>
    </div>
  </div>
  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="../owners.php">
        <i class="fas fa-home"></i>
      </a>
      <span class="tooltip">Owners</span>
    </div>
  </div>
  <div class="floating-nav__item">
    <div class="nav-box">
      <a href="complaint_form.php">
        <i class="fas fa-envelope"></i>
      </a>
      <span class="tooltip">Email Us</span>
    </div>
  </div>
</div>

    <section id="section-wrapper">
        <div class="box-wrapper">
            <div class="info-wrap">
                <h2 class="info-title">Contact Information</h2>
                <h3 class="info-sub-title">Fill up the form and our Team will get back to you within 24 hours</h3>
                <ul class="info-details">
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>Phone:</span> <a> +9208153770</a>
                    </li>
                    <li>
                        <i class="fas fa-paper-plane"></i>
                        <span>Email:</span> <a>phpproject000@gmail.com</a>
                    </li>
                    <li>
                        <i class="fas fa-globe"></i>
                        <span>Website:</span> <a href="#">yoursite.com</a>
                    </li>
                </ul>
                <ul class="social-icons">
                    <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fab fa-tiktok"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram "></i></a></li>
                </ul>
            </div>
            <div class="form-wrap"> 

            <form action="https://formspree.io/f/xpwwodee" method="POST">
>                <h2 class="form-title">Send us a message</h2>
    <div class="form-fields">
        <div class="form-group">
            <input type="text" name="fname" class="fname" placeholder="First Name" required>
        </div>
        <div class="form-group">
            <input type="text" name="lname" class="lname" placeholder="Last Name" required>
        </div>
        <div class="form-group">
            <input type="email" name="email" class="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="text" name="phone" class="phone" placeholder="Phone" required>
        </div>
        <div class="form-group">
            <textarea name="message" placeholder="Write your message" required></textarea>
        </div>
    </div>
    <input type="submit" value="Send Message" class="submit-button">
</form>

            </div>
        </div>
    </section>

</body>
</html>