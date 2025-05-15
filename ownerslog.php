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

$bhouseName = "Default Name";
if ($result && $row = $result->fetch_assoc()) {
    $bhouseName = $row['bhouse_name'];
}

$bookingQuery = "
    SELECT COUNT(*) AS total_bookings
    FROM bookings b
    JOIN room_images ri ON b.image_path = ri.image_path
    WHERE ri.owner_id = ?
";
$stmt = $conn->prepare($bookingQuery);
$stmt->bind_param("i", $ownerId);
$stmt->execute();
$bookingResult = $stmt->get_result();
$totalBookings = 0;
if ($bookingResult && $row = $bookingResult->fetch_assoc()) {
    $totalBookings = $row['total_bookings'];
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
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="css/style2.css">
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

        .dashboard-content {
            margin: 20px;
        }

        .stats {
            text-align: center;
        }

        .card {
            border: none; 
            border-radius: 15px; 
            transition: transform 0.3s, box-shadow 0.3s; 
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        }

        .card-body {
            border-radius: 15px;
            padding: 20px; 
            background-color: #E9edf2;
        }

        .card-body h3 {
            font-size: 2.5rem;
            color: #007bff; 
            margin-bottom: 15px; 
        }

        .card-body img {
            width: 70px; 
            height: auto;
            margin-top: 10px;
        }

        .card-text {
            font-size: 1.5rem;
            color: #6c757d; 
            margin-top: 10px; 
        }
        a.disabled {
    pointer-events: none;
    color: #6c757d; 
    text-decoration: none; 
}
    </style>
</head>
<body>

    <div class="sidebar">    
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url('<?php 
                    if (isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT owners.profile_at FROM `owners` WHERE owners.email='$email'");
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
                        <a href="ownerslog.php" class="active">
                        <span class="las la-home"></span>
                        <small>Dashboard</small>
                    </a>
                </li>
                    <li>
                        <a href="ownersprof.php">
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
                <h1>My Dashboard</h1>
                <small><?php echo htmlspecialchars($bhouseName); ?></small> 
            </div>

            <div class="dashboard-content">
                <div class="stats">
                    <h2>Number of current booked students</h2><br>
                    <div class="card text-center">
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($totalBookings); ?></h3> 
                            <p class="card-text">Total Booked Students</p>
                            <img src="students.png" alt="">
                        </div>
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
        </main>
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
