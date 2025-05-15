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
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Uploaded Rooms</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/style7.css">
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

        .image-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
    }

    .image-item img {
        width: 100%; 
        transition: transform 0.3s ease;
    }

    .image-item:hover img {
        transform: scale(1.1);
        cursor: pointer;
    }

    .availability-button, .delete-button {
        position: absolute;
        width: 100px;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .availability-button {
        bottom: 10px; 
        left: 10px;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .delete-button {
        bottom: 10px; 
        right: 10px;
        background-color:red;
    }

    .available {
        background-color: green;
    }

    .unavailable {
        background-color: blue;
    }
.upload-form {
    max-width: 700px;
    margin: 0 auto;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.form-row {
    display: flex;
    gap: 10px; 
    flex-wrap: wrap;
}

.form-group {
    flex: 1;
    min-width: 260px; 
    margin-bottom: 20px;
}

.form-group label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}
textarea.form-control {
    resize: vertical;
}
.upload-button {
    background-color:rgb(242, 255, 0);
    color: black;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.3s ease;
}

.upload-button:hover {
    background: rgb(245, 250, 153);
}

.form-control:focus {
    border-color: #4CAF50;
    outline: none;
}

.upload-form .form-group {
    margin-bottom: 15px;
}

.upload-form {
    background-color: #ffffff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
}
.rating-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            display: flex;
        }

        .star {
            font-size: 20px;
            color: #FFD700;
            margin-right: 2px;
            cursor: pointer;
        }

        .star.filled {
            color: #FFD700; 
        }

        .star:not(.filled) {
            color: #ccc;
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
                        <a href="ownersprof.php">
                        <span class="las la-user"></span>
                            <small>Profile</small>
                        </a>
                    </li>
                    <li>
                       <a href="roomowner.php" class="active">
                       <span class="las la-bed"></span>
                            <small> Rooms</small>
                        </a>
                    </li>
                    <li>
                       <a href="roomusers.php">
                       <span class="las la-user-graduate"></span>
                            <small>Room users</small>
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
                <h1>My Rooms</h1>
                <br>
                <br>
                <a href="update_room.php" class="upload-button">Update Room</a>
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

            <form action="upload_room.php" method="POST" enctype="multipart/form-data" class="upload-form">
    <div class="form-row">
        <div class="form-group">
            <label for="room_image">Room Image</label>
            <input type="file" name="room_image" required class="form-control">
        </div>

        <div class="form-group">
            <label for="location">Room Location</label>
            <select name="location" required class="form-control">
                <option value="" disabled selected>Select a Location</option>
                <option value="Agujo">Agujo</option>
                <option value="Daanbantayan">Poblacion</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="price">Price Range</label>
            <select name="price" required class="form-control">
                <option value="" disabled selected>Select Price Range</option>
                <option value="500-700">₱500 - ₱700</option>
                <option value="800-1000">₱800 - ₱1,000</option>
            </select>
        </div>

        <div class="form-group">
            <label for="room_type">Room Type</label>
            <select name="room_type" required class="form-control">
                <option value="" disabled selected>Select Room Type</option>
                <option value="Bedspacer">BedSpacer</option>
                <option value="Private Room">Private Room</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="description">Room Description</label>
        <textarea name="description" id="description" rows="3" required class="form-control" placeholder="Enter a description of the room..."></textarea>
    </div>

    <button type="submit" class="upload-button">Upload Room Image</button>
</form>

<div class="image-gallery">
                <h2>Uploaded Room</h2>
                <br><br>
                <div class="image-container">
                    <?php
                    $ownerId = $_SESSION['owner_id'];
                    $query = "SELECT id, image_path, is_available, rating FROM room_images WHERE owner_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $ownerId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $imageId = $row['id'];
                        $imagePath = $row['image_path'];
                        $isAvailable = $row['is_available'] ? 'available' : 'unavailable';
                        $rating = $row['rating'] ?: 0; 

                        $availabilityClass = $isAvailable == 'available' ? 'available' : 'unavailable';

                        echo "<div class='image-item'>
                                <img src='" . htmlspecialchars($imagePath) . "' alt='Room Image'>
                                <div class='rating-overlay'>";
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $rating) ? '<span class="star filled">&#9733;</span>' : '<span class="star">&#9734;</span>';
                                    }
                        echo    "</div>
                                <button class='availability-button $availabilityClass' onclick='toggleAvailability($imageId, \"$isAvailable\")'>
                                    " . ucfirst($isAvailable) . "
                                </button>
                                <button class='delete-button' onclick='deleteImage(\"" . htmlspecialchars($imagePath) . "\")'>Delete</button>
                              </div>";
                    }
                    $stmt->close();
                    ?>
                </div>
            </div>
        </main>
    </div>
    <script src="scripts/script5.js"></script>
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