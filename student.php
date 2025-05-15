<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration Form</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/stylee.css">
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
    <li><a href="index.php" >Home</a></li>
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
  <div class="title">Student Registration</div>
  <div class="content">
    <form method="post" action="auth/register.php">
      <div class="user-details">
        <div class="input-box">
          <span class="details">First Name</span>
          <input type="text" name="fName" id="fName" placeholder="First Name" required title="Enter your first name.">
        </div>
        <div class="input-box">
          <span class="details">Last Name</span>
          <input type="text" name="lName" id="lName" placeholder="Last Name" required title="Enter your last name.">
        </div>
        <div class="input-box">
          <span class="details">Date of Birth</span>
          <input type="date" name="dateofbirth" id="dateofbirth" placeholder="Date Of Birth" required>
        </div>
        <div class="input-box">
          <span class="details">Email</span>
          <input type="email" name="email" id="email" placeholder="Email" required title="Please enter valid Email Address.">
        </div>
        <div class="input-box">
          <span class="details">Phone Number</span>
          <input type="tel" name="tel" id="tel" placeholder="Phone number" required title="Must be an active phone number.">
        </div>
        <div class="input-box">
          <span class="details">Password</span>
          <input type="password" name="password" id="password" placeholder="Password" required title="Your password must be at least 8 characters long.">
        </div>
        <div class="input-box">
          <span class="details">School Id</span>
          <input type="text" name="school" id="school" placeholder="School Id" required title="Enter your school id, if none type 000000.">
        </div>
        <div class="input-box">
          <span class="details">Confirm Password</span>
          <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required title="Enter your password to confirm">
        </div>
        <div class="input-box">
          <span class="details">Address</span>
          <input type="text" name="address" id="address" placeholder="Current Address" required title="Enter your current address">
        </div>
        <div class="input-box">
          <span class="details">Emergency Contact #</span>
          <input type="tel" name="emergencyContact" id="emergencyContact" placeholder="Contact In Case of Emergency" required title="Enter emergency contact number">
        </div>
      </div>
      <div class="gender-details">
        <input type="radio" name="gender" id="dot-1" value="Male" required>
        <input type="radio" name="gender" id="dot-2" value="Female" required>
        <span class="gender-title">Gender</span>
        <div class="category">
          <label for="dot-1">
            <span class="dot one"></span> 
            <span class="gender">Male</span>
          </label>
          <label for="dot-2">
            <span class="dot two"></span>
            <span class="gender">Female</span>
          </label>
        </div>
      </div>
      
      <div class="button">
        <input type="submit" class="btn" value="Register" name="signUp">
      </div>
      
      <div class="button">
        <p>Already Have an Account?</p>
        <input type="submit" id="signInButton" class="btn" value="Sign In">
      </div>
    </form>
  </div>
</div>

<div class="container" id="signIn">
  <div class="title">Student Sign In</div>
  <div class="content">
    <form method="post" action="auth/register.php">
      <div class="user-details">
        <div class="input-box">
          <span class="details">Email</span>
          <input type="email" name="email" id="email" placeholder="Email" required>
          <span class="details">Password</span>
          <input type="password" name="password" id="password" placeholder="Password" required>
          <br><br><br>
          <a href="forgot-password.php">Forgot password?</a>
        </div>
        <div>     
          <div class="button">
            <input type="submit" class="btn" value="Sign In" name="signIn">
            <br><br>
            <p>Don't have an account yet?</p>
            <input type="submit" id="signUpButton" class="btn" value="Register">
          </div>
        </div>
    </form>
  </div>
</div>
<script src="scripts/script.js"></script>
</body>
</html>
