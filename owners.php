<?php
session_start();  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owners</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style11.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
 body {
      background-image:none;
    }
    .tooltip {
      position: absolute;
      bottom: 100%; 
      left: 50%;
      transform: translateX(-50%);
      background-color: transparent;
      color:rgb(0, 0, 0);
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
    .nav__links{
  color:black;
}
ul li a:hover {
  background-color:rgb(242, 255, 0);
  border-radius:15px;
}
ul li a.active {
    background:rgb(242, 255, 0);
    border-radius:15px;
}
.nav-box {
  background-color:rgb(242, 255, 0);
}
.floating-nav__item a:hover {
    color:white;
  }
  form .button input:hover {
  background: rgb(245, 250, 153);
}
form .button input {
  background:rgb(242, 255, 0) ;
  color:black;
}
    
  </style>
  </head>
<body>
<nav>
      <div class="nav__logo">CTU-DB | BHMS</div>
      <?php
if(isset($_SESSION['status']))
{
  echo $_SESSION['status'];
  unset($_SESSION['status']);

}
?>
      <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a class="active">Register ▾</a></li>
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


<div class="container" id="signup" style="display:none;">
    <div class="title"> Landlords Registration</div>
    <div class="content">
    <form action="auth/register-login.php" method="POST" enctype="multipart/form-data">
        <div class="user-details">
          <div class="input-box">
            <span class="details">First Name</span>
            <input type="text" name="first_name"  placeholder="First Name" required title="Enter your first name.">
          </div>
        
          <div class="input-box">
            <span class="details">Last Name</span>
            <input type="text" name="last_name"  placeholder="Last Name" required title="Enter your last name.">
          </div>
          
          <div class="input-box">
            <span class="details">Boarding House Name</span>
            <input type="text" name="bhouse_name"  placeholder="Boarding House Name" required title="Enter your last name.">
          </div>
          
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Email" required title="Please enter valid Email Address.">
          </div>

          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password"  placeholder="Password" required title="Your password must be at least 8 characters long.">
          </div>

          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="tele" name="contact"  placeholder="Contact number" required title="Must be an active phone number.">
          </div>
              
          <div class="input-box">
            <span class="details">Boarding House Address</span>
            <input type="text" name="address"  placeholder="Boarding House Address" required title="Enter your current address">
          </div>

          <div class="input-box">
            <span class="details">Upload Gcash QR Code</span>
            <input type="file" name="image" accept="image/*" title="Upload Payment QR Code">
        </div>

    </div>
        
    <div class="button">
          <input type="submit" class="btn" value="Register" name="register">
        </div>
      
      <div class="button">
        <p>Already Have Account ?</p>
        <input type="submit" id="signInButton" class="btn" value="Sign In">
      </div>
      </form>
    </div>
</div>

    <div class="container" id="signIn" >
    <div class="title"> Sign In</div>
    <div class="content">
    <form action="auth/register-login.php" method="POST">  
        <div class="user-details">
        <div class="input-box">

        <span class="details">Boaring House Name</span>
        <input type="text" name="bhouse_name" placeholder="Boarding House Name" required>
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Email" required>
            <span class="details">Password</span>
            <input type="password" name="password"  placeholder="Password" required>
            <br><br><br>
            <a href="owner-forgot.php">Forgot password?</a>
          </div>
           <div>     
           <div class="button">
          <input type="submit" class="btn" value="Sign In" name="login">
          <br><br>
           <p>Don't have account yet? </p>
          <input type="submit" id="signUpButton" class="btn" value="Register">
          
        </div>
        </form>
        <script src="scripts/script.js"></script>
    
</body>
</html>
