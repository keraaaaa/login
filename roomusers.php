<?php
session_start();
include("connect/connect.php");

$ownerId = $_SESSION['owner_id'];

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

$bookings = [];
$query = "
    SELECT bookings.id, bookings.user_email, bookings.duration, bookings.date, bookings.status, bookings.duration, room_images.image_path
    FROM bookings 
    JOIN room_images ON bookings.image_path = room_images.image_path 
    WHERE room_images.owner_id = ?
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $bookings[] = [
            'id' => $row['id'],
            'user_email' => htmlspecialchars($row['user_email']),
            'booking_date' => htmlspecialchars($row['date']),
            'stats' => htmlspecialchars($row['status']),
            'duration' => htmlspecialchars($row['duration']),
            'image_path' => htmlspecialchars($row['image_path'])
        ];
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

$reservations = [];
$query = "
    SELECT reservations.id, reservations.user_email, reservations.date, reservations.status, reservations.duration, room_images.image_path
    FROM reservations 
    JOIN room_images ON reservations.image_path = room_images.image_path 
    WHERE room_images.owner_id = ?
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reservations[] = [
            'id' => $row['id'],
            'user_email' => htmlspecialchars($row['user_email']),
            'reservation_date' => htmlspecialchars($row['date']),
            'stats' => htmlspecialchars($row['status']),
            'duration' => htmlspecialchars($row['duration']),
            'image_path' => htmlspecialchars($row['image_path'])
        ];
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Room Users</title>
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
        .btn-confirm, .btn-pending, .btn-decline{
            color: black;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-confirm {
            background-color: #4CAF50;

        }
        .btn-confirm:hover{
            background-color: #45a049;

        }

        .btn-pending {
            background-color: orange;
        }
        .btn-decline{
            background-color: #ffa500;
        }
        .btn-decline:hover{
            background-color: #ff8c00;
        }
        .btn-confirm:hover, .btn-pending:hover {
            opacity: 0.8;
        }
        .tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
        }
        table thead th {
    padding: 1rem 0rem;
    text-align: center;
    color: #444;
    font-size: 1rem;
}
table tbody td {
    padding: 1rem 0rem;
    color: #444;
    text-align:center;
}

table tbody td:first-child {
    padding-left: 1rem;
    color: blue;
    font-weight: 600;
    font-size: .9rem;
}

table tbody tr{
    border-bottom: 1px solid #dee2e8;
    background-color:lightgrey ;
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
                        <a href="ownerslog.php">
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
                        <a href="roomowner.php">
                            <span class="las la-bed"></span>
                            <small>Rooms</small>
                        </a>
                    </li>
                    <li>
                        <a href="roomusers.php" class="active">
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
                <h1>Your Dashboard</h1>
            </div>
            <h2>Your Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student Email</th>
                        <th>Booking Date</th>
                        <th>Duration</th>
                        <th>Room</th>
                        <th>Booking Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

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

                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['user_email']; ?></td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['duration']; ?></td>
                                <td><img src="<?php echo htmlspecialchars($booking['image_path']); ?>" alt="Room Image" width="50" height="50"></td>
                                <td><?php echo $booking['stats']; ?></td>
                                <td>
                                    <?php if ($booking['stats'] == 'pending'): ?>
                                        <a href="toggle_booking_status.php?id=<?php echo $booking['id']; ?>&status=confirmed" class="btn-confirm">Accept</a>
                                        <a href="delete_booking.php?id=<?php echo $booking['id']; ?>&status=canceled" class="btn-decline">Decline</a>

                                    <?php else: ?>
                                        <a href="toggle_booking_status.php?id=<?php echo $booking['id']; ?>&status=pending" class="btn-pending">Revert to Pending</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No bookings available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
<br><br><br><br><br><br>
            <h2>Your Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student Email</th>
                        <th>Reservation Date</th>
                        <th>Duration</th>
                        <th>Room</th>
                        <th>Reservation Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo $reservation['user_email']; ?></td>
                                <td><?php echo $reservation['reservation_date']; ?></td>
                                <td><?php echo $reservation['duration']; ?></td>
                                <td>
                        <img src="<?php echo htmlspecialchars($reservation['image_path']); ?>" alt="Room Image" width="50" height="50"> 
                                <td><?php echo $reservation['stats']; ?></td>
                                <td>
                                    <?php if ($reservation['stats'] == 'pending'): ?>
                                        <a href="toggle_reservation_status.php?id=<?php echo $reservation['id']; ?>&status=confirmed" class="btn-confirm">Accept</a>
                                        <a href="delete_reservation.php?id=<?php echo $reservation['id']; ?>&status=canceled" class="btn-decline">Decline</a>
                                        <?php else: ?>
                                        <a href="toggle_reservation_status.php?id=<?php echo $reservation['id']; ?>&status=pending" class="btn-pending">Revert to Pending</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No reservations available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
