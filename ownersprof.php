<?php
session_start();
include("connect/connect.php");

$ownerId = $_SESSION['owner_id'];

error_reporting(E_ALL & ~E_NOTICE);  
ini_set('display_errors', 0);

$query = "SELECT bhouse_name FROM owners WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ownerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $bhouseName = $row['bhouse_name'];
} else {
    $bhouseName = "Default Name";
}

$notificationQuery = "
    SELECT * FROM notifications WHERE owner_id = ? ORDER BY created_at DESC LIMIT 5
";
$stmt = $conn->prepare($notificationQuery);
$stmt->bind_param("i", $ownerId);
$stmt->execute();
$notificationResult = $stmt->get_result();
$notifications = [];
while ($row = $notificationResult->fetch_assoc()) {
    $notifications[] = $row;
}

$paymentStatus = "pending"; 
$sql = "SELECT status FROM payments WHERE owner_id = " . intval($ownerId) . " ORDER BY payment_date DESC LIMIT 1";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $paymentStatus = $row['status']; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Owner Profile</title>
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
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
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

        #notification-dropdown {
            z-index: 1000;
        }
        a.disabled {
    pointer-events: none;
    color: #6c757d; 
    text-decoration: none; 
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
                         $query = mysqli_query($conn, "SELECT owners.profile_at FROM owners WHERE owners.email='$email'");
                         if ($row = mysqli_fetch_array($query)) {
                             $profile_picture = !empty($row['profile_at']) ? htmlspecialchars($row['profile_at']) : 'uploads/default.jpg';
                             echo $profile_picture;
                         } else {
                             echo 'uploads/default.jpg';
                         }
                     } else {
                         echo 'uploads/default.jpg';
                     }
                 ?>');">
            </div>
            <h4><?php echo htmlspecialchars($bhouseName); ?></h4>
        </div>
        <div class="side-menu">
            <ul>
                <li>
                    <a href="ownerslog.php">
                        <span class="las la-home"></span>
                        <small>Dashboard</small>
                    </a>
                </li>
                <li>
                    <a href="ownersprof.php" class="active">
                        <span class="las la-user"></span>
                        <small>Profile</small>
                    </a>
                </li>
                <li>
                <a href="roomowner.php" class="btn <?php echo ($paymentStatus == "Confirmed" ? "" : "disabled"); ?>">
                    <span class= "las la-bed"></span>
                    <small>Rooms</small>
                </a>
            </li>
            <li>
                <a href="roomusers.php" class="btn <?php echo ($paymentStatus == "Confirmed" ? "" : "disabled"); ?>">
                    <span class="las la-user-graduate"></span>
                    <small>Room Users</small>
                </a>
            </li>
                <li>
                    <a href="payment.php">
                        <span class="las la-file-invoice-dollar"></span>
                        <small>Payment</small>
                     </a>
                </li>
                <li>
                    <a href="auth/Ologout.php">
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
            <h1>Owner Profile</h1>
            <small><?php
                if (isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    $query = mysqli_query($conn, "SELECT owners.bhouse_name FROM owners WHERE owners.email='$email'");
                    while ($row = mysqli_fetch_array($query)) {
                        echo htmlspecialchars($row['bhouse_name']);
                    }
                }
                ?></small>
        </div>
    </main>

    <div class="info">
        <div class="container">
            <form action="upload_owner.php" method="post" enctype="multipart/form-data">
                <div class="card">
                    <?php
                    if (isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];

                        $query = mysqli_query($conn, "SELECT owners.email, owners.first_name, owners.last_name, owners.bhouse_name, owners.contact, owners.address, owners.profile_at FROM owners WHERE owners.email='$email'");

                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_array($query)) {

                                $profile_picture = !empty($row['profile_at']) ? htmlspecialchars($row['profile_at']) : 'uploads/default.jpg';

                                echo "<img src='" . $profile_picture . "' alt='Profile Picture' class='profile-image'>";
                                echo "<h2>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</h2>";
                                echo "<p><strong>House Name:</strong> " . htmlspecialchars($row['bhouse_name']) . "</p>";
                                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                                echo "<p><strong>Contact:</strong> " . htmlspecialchars($row['contact']) . "</p>";
                                echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                            }
                        } else {
                            echo "No owner information found.";
                        }
                    } else {
                        echo "Owner is not logged in.";
                    }
                    ?>
                    <label for="image">Upload Profile Picture:</label>
                    <input type="file" name="image" id="image" required>
                    <input type="submit" value="Upload" class="button-upload">
                </div>
            </form>
        </div>
    </div>
    
    <div class="notifications">
                    <div class="notification-bell">
                        <i class="las la-bell" id="bell-icon"></i>
                        <span id="notification-count" class="badge">
                            <?php echo count(array_filter($notifications, function($notif) { return $notif['status'] == 'unread'; })); ?>
                        </span>
                        <div id="notification-dropdown" class="dropdown-content">
                            <?php if (count($notifications) > 0): ?>
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="notification <?php echo $notification['status'] == 'unread' ? 'unread' : ''; ?>" id="notification-<?php echo $notification['id']; ?>">
                                        <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
                                        <p><small>Received at: <?php echo htmlspecialchars($notification['created_at']); ?></small></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No new notifications.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
</div>
<script>
     document.addEventListener('DOMContentLoaded', function() {
        var bellIcon = document.getElementById('bell-icon');
        var notificationDropdown = document.getElementById('notification-dropdown');
        var notificationCount = document.getElementById('notification-count');

        bellIcon.addEventListener('click', function(event) {
            event.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';

            if (notificationCount.textContent !== '0') {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'mark_notifications_read.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText); 
                        notificationCount.textContent = '0'; 
                        var unreadNotifications = document.querySelectorAll('.notification.unread');
                        unreadNotifications.forEach(function(notification) {
                            notification.classList.remove('unread');
                        });
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

        document.querySelectorAll('.notification').forEach(function(notification) {
            notification.addEventListener('click', function() {
                if (notification.classList.contains('unread')) {
                    var notifId = notification.id.replace('notification-', '');

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'mark_single_notification_read.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                notification.classList.remove('unread');
                                notificationCount.textContent = countUnreadNotifications();  
                            }
                        }
                    };
                    xhr.send('id=' + notifId);
                }
            });
        });
    });

    function countUnreadNotifications() {
        var unreadNotifications = document.querySelectorAll('.notification.unread');
        return unreadNotifications.length;
    }
    </script>
</body>
</html>
