<?php
session_start();
include("connect/connect.php");
    $notif = [];
    
        $notificationQuery = "
        SELECT * FROM notif WHERE user_email = ? ORDER BY created_at DESC LIMIT 5
    ";
    
    if ($stmt = $conn->prepare($notificationQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $notificationResult = $stmt->get_result();
    
        while ($row = $notificationResult->fetch_assoc()) {
            $notif[] = $row;  
        }
        $stmt->close();  
    } else {
        die("Error preparing notification query: " . $conn->error);
    }
    
    $unreadCountQuery = "
        SELECT COUNT(*) AS unread_count FROM notif WHERE user_email = ? AND status = 'unread'
    ";
    
    if ($stmt = $conn->prepare($unreadCountQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $unreadCount = $row['unread_count'];
    } else {
        die("Error preparing notification query: " . $conn->error);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
         .notification-bell {
            position: fixed;
            top: 20px;
            right: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1000;
        }

        .notification-bell .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color:red;
            color: white;
            border-radius: 100%;
            padding: 5px 8px;
            font-size: 10px;
        }

        #notification-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            border-radius: 8px;
            z-index: 10;
            font-size: 14px;  
        }

        .notification-bell:hover + #notification-dropdown,
        #notification-dropdown:hover {
            display: block;
        }

        .notification {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }

        .notification.unread {
            border-left: 4px solid green;
        }
        .button-upload {
    background-color:rgb(242, 255, 0);
    border: none;
    color:black;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-top: auto; 
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.button-upload:hover {
    background: rgb(245, 250, 153);
}
  

    </style>
    
</head>
<body>
<input type="checkbox" id="menu-toggle">
    <div class="sidebar">    
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" 
                     style="background-image: url('<?php 
                         if (isset($_SESSION['email'])) {
                             $email = $_SESSION['email'];
                             $query = mysqli_query($conn, "SELECT users.profile_picture FROM `users` WHERE users.email='$email'");
                             if ($row = mysqli_fetch_array($query)) {
                                
                                 $profile_picture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'uploads/default.jpg';
                                 echo $profile_picture;
                             } else {
                                 echo 'uploads/default.jpg'; 
                             }
                         } else {
                             echo 'uploads/default.jpg'; 
                         }
                     ?>');">
                </div>
                <small><?php 
                    if (isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT users.email FROM `users` WHERE users.email='$email'");
                        while ($row = mysqli_fetch_array($query)) {
                            echo htmlspecialchars($row['email']);        
                        }
                    }
                ?></small>
            </div>
            <div class="side-menu">
                <ul>
                    <li>
                        <a href="studentdb.php">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                        <a href="stprof.php" class="active">
                        <span class="las la-user-graduate"></span>
                            <small>Profile</small>
                        </a>
                    </li>
                    <li>
                        <a href="checkroom.php">
                        <span class="las la-bed"></span>
                            <small>Rooms</small>
                        </a>
                    </li>
                    <li>
                    <a href="upayment.php">
                        <span class="las la-file-invoice-dollar"></span><small>Payment</small>
                     </a>
                </li>
                    <li>
                        <a href="auth/Ulogout.php">
                            <span class="las la-power-off"></span>
                            <small>Logout</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <main>       
            <div class="page-header">
                <h1>Dashboard</h1>
                <small><?php 
                    if (isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT users.email FROM `users` WHERE users.email='$email'");
                        while ($row = mysqli_fetch_array($query)) {
                            echo htmlspecialchars($row['email']);        
                        }
                    }
                ?></small>
            </div>
        </main>
        
        <div class="notification-bell">
        <i class="las la-bell" id="bell-icon"></i>
        <span id="notification-count" class="badge">
    <?php echo $unreadCount > 0 ? $unreadCount : '0'; ?>
</span>
        <div id="notification-dropdown" class="dropdown-content">
            <?php if (count($notif) > 0): ?>
                <?php foreach ($notif as $notification): ?>
                    <div class="notification <?php echo $notification['status'] == 'unread' ? 'unread' : ''; ?>" id="notification-<?php echo $notification['id']; ?>" onclick="toggleNotificationStatus(<?php echo $notification['id']; ?>)">
    <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
    <p><small>Received at: <?php echo htmlspecialchars($notification['created_at']); ?></small></p>
</div>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No new notifications.</p>
            <?php endif; ?>
        </div>
    </div>

        <div class="info">
            <div class="container">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <?php
                        if (isset($_SESSION['email'])) {
                            $email = $_SESSION['email'];

                            $query = mysqli_query($conn, "SELECT users.email, users.firstName, users.lastName, users.tele, users.gender, users.school, users.address, users.emergency_contact, users.profile_picture FROM `users` WHERE users.email='$email'");

                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_array($query)) {
                                    $profile_picture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'uploads/default.jpg';

                                    echo "<img src='" . $profile_picture . "' alt='Profile Picture' class='profile-image'>";
                                    echo "<h2>" . htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']) . "</h2>";
                                    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                                    echo "<p><strong>Telephone:</strong> " . htmlspecialchars($row['tele']) . "</p>";
                                    echo "<p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>";
                                    echo "<p><strong>School:</strong> " . htmlspecialchars($row['school']) . "</p>";
                                    echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                                    echo "<p><strong>Emergency Contact#:</strong> " . htmlspecialchars($row['emergency_contact']) . "</p>";
                                }
                            } else {
                                echo "No user information found.";
                            }
                        } else {
                            echo "User is not logged in.";
                        }
                        ?>
                        <label for="image">Upload Profile Picture:</label>
                        <input type="file" name="image" id="image" required>
                        <input type="submit" value="Upload" class="button-upload">
                    </div>
                </form>
            </div>
        </div>
    </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
    var bellIcon = document.getElementById('bell-icon');
    var notificationDropdown = document.getElementById('notification-dropdown');
    var notificationCount = document.getElementById('notification-count');
    var unreadNotifications = document.querySelectorAll('.notification.unread');

    bellIcon.addEventListener('click', function(event) {
        event.stopPropagation();

        notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';

        if (notificationCount.textContent !== '0') {

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'mark_notif_read.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {

                        notificationCount.textContent = '0';
                        unreadNotifications.forEach(function(notification) {
                            notification.classList.remove('unread');
                        });
                    }
                }
            };
            xhr.send();
        }
    });

    document.addEventListener('click', function(event) {
        if (!bellIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.style.display = 'none';
        }
    });
});
document.querySelectorAll('.notification').forEach(function(notification) {
    notification.addEventListener('click', function() {
        if (!notification.classList.contains('read')) {
            var notifId = notification.id.replace('notification-', '');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'mark_single_notif_read.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        notification.classList.add('read');
                        notification.classList.remove('unread');
                    }
                }
            };
            xhr.send('id=' + notifId);
        }
    });
});


        </script>
</body>
</html>
