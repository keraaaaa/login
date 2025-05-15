<?php
        include('../connect/connect.php');
        $Id=$_GET['id'];
        $query=mysqli_query($conn,"select * from `owners` where id='$Id'");
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
      <form method="POST" action="../auth/updateOwner.php?id=<?php echo $Id; ?>">
    <div class="main-user-info">
        <div class="user-input-box">
            <label>Firstname:</label><input type="text" value="<?php echo $row['first_name']; ?>" name="firstname"><br>
        </div>

        <div class="user-input-box">
            <label>Lastname:</label><input type="text" value="<?php echo $row['last_name']; ?>" name="lastname"><br>
        </div>

        <div class="user-input-box">
            <label>House Name:</label><input type="text" value="<?php echo $row['bhouse_name']; ?>" name="bhouse_name"><br> 
        </div>

        <div class="user-input-box">
            <label>Email:</label><input type="text" value="<?php echo $row['email']; ?>" name="email"><br>  
        </div>

        <div class="user-input-box">
            <label>Phone Number:</label><input type="text" value="<?php echo $row['contact']; ?>" name="contact"><br>
        </div>

        <div class="user-input-box">
            <label>Address:</label><input type="text" value="<?php echo $row['address']; ?>" name="address"><br> 
        </div>
    </div>
    <div class="form-submit-btn">
        <input type="submit" name="submit" value="Update">
    </div>
</form>

    </div>
  </body>
</html> 