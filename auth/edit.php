<?php
        include('../connect/connect.php');
        $Id=$_GET['id'];
        $query=mysqli_query($conn,"select * from `users` where id='$Id'");
        $row=mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Update User Information</title>
    <meta name="viewport" content="width=device-width,
      initial-scale=1.0"/>
    <link rel="stylesheet" href="../css/style3.css" />
  </head>
  <body>
    <div class="container">
      <h1 class="form-title">Users Information</h1>
      <form method="POST" action="../auth/update.php?id=<?php echo $Id; ?>">
    <div class="main-user-info">

        <div class="user-input-box">
            <label>Firstname:</label><input type="text" value="<?php echo $row['firstName']; ?>" name="firstname"><br>
        </div>

        <div class="user-input-box">
            <label>Lastname:</label><input type="text" value="<?php echo $row['lastName']; ?>" name="lastname"><br>
        </div>

        <div class="user-input-box">
            <label>Email:</label><input type="text" value="<?php echo $row['email']; ?>" name="email" ><br>  
        </div>

        <div class="user-input-box">
            <label>Phone Number:</label><input type="text" value="<?php echo $row['tele']; ?>" name="tele"><br> 
        </div>

        <div class="user-input-box">
            <label>Gender:</label><input type="text" value="<?php echo $row['gender']; ?>" name="gender"  ><br> 
        </div>

        <div class="user-input-box">
            <label>School Id:</label><input type="text" value="<?php echo $row['school']; ?>" name="school" ><br> 
        </div>

        <div class="user-input-box">
            <label>Address:</label><input type="text" value="<?php echo $row['address']; ?>" name="address"><br> 
        </div>

        <div class="user-input-box">
            <label>Emergency Contact Number:</label><input type="text" value="<?php echo $row['emergency_contact']; ?>" name="emergencyContact"><br> 
        </div>

    </div>
    <div class="form-submit-btn">
        <input type="submit" name="submit" value="Update">
    </div>
</form>
    </div>
  </body>
</html> 