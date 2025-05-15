<?php
session_start();
include("connect/connect.php");

$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

$bookedImages = [];
$notif = [];

if ($email) {

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

$imageQuery = "
SELECT 
    room_images.image_path, 
    owners.bhouse_name AS owner_name, 
    bookings.date AS booking_date, 
    bookings.duration, 
    bookings.status AS booking_status,
    room_images.rating,
    'booking' AS type
FROM bookings 
JOIN room_images ON bookings.image_path = room_images.image_path 
JOIN owners ON room_images.owner_id = owners.id 
WHERE bookings.user_email = ? 
UNION ALL
SELECT 
    room_images.image_path, 
    owners.bhouse_name AS owner_name, 
    reservations.date AS reservation_date, 
    reservations.duration, 
    reservations.status AS booking_status,
    room_images.rating,
    'reservation' AS type
FROM reservations
JOIN room_images ON reservations.image_path = room_images.image_path 
JOIN owners ON room_images.owner_id = owners.id 
WHERE reservations.user_email = ?
";


    if ($stmt = $conn->prepare($imageQuery)) {
        $stmt->bind_param("ss", $email, $email); 
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $bookedImages[] = [
                'path' => htmlspecialchars($row['image_path']),
                'owner' => htmlspecialchars($row['owner_name']),
                'date' => htmlspecialchars($row['booking_date']), 
                'duration' => htmlspecialchars($row['duration']),
                'status' => htmlspecialchars($row['booking_status']),
                'type' => htmlspecialchars($row['type']),
                'rating' => htmlspecialchars($row['rating'])
            ];
        }
        $stmt->close();  
    } else {
        die("Error preparing booked images query: " . $conn->error);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Students Dashboard</title>
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
        .image-item {
            position: relative;
            overflow: hidden;
            margin: 10px;
            width: 200px; 
            display: inline-block;
            text-align: center; 
            background-color:transparent;
            border-radius:15px;
        }

        .image-item img {
            border-radius:15px;
            width: 100%;
            height: 180px;
            object-fit: cover; 
            transition: transform 0.2s; 
        }
        .image-item:hover img {
            transform: scale(1.02);
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7); 
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 70%;
            max-width: 700px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }

        .modal-image {
            width: 100%;
            border-radius: 8px;
        }

        .modal-details {
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }

        .modal-details h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #1e1e1e;
        }

        .modal-details p {
            font-size: 18px;
            margin: 5px 0;
        }

        #rescheduleBtn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        #rescheduleBtn:hover {
            background-color: #45a049;
        }
        .rating {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .rating .star {
            margin-right: 5px; 
        }

        .rating .rate-here {
        margin-left: 7px; 
        font-size: 17px; 
        }
        .star {
            font-size: 25px;
            color: #ddd;
            cursor: pointer;
            margin: 0 10px;
        }

        .star.filled {
            color: #ffcc00; 
        }

        .star.empty {
            color: #ddd; 
        }

        .star:hover,
        .star:hover ~ .star {
            color: #ffcc00;
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
                         if ($email) {
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
                    if ($email) {
                        echo htmlspecialchars($email);
                    }
                ?></small>
            </div>

            <div class="side-menu">
                <ul>
                    <li>
                       <a href="studentdb.php" class="active">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="stprof.php">
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
                    if ($email) {
                        echo htmlspecialchars($email);
                    }
                ?></small>
            </div>
            
               
        <div class="booked-images">
            <h2>You're booked/reserved at:</h2>
            <?php if (!empty($bookedImages)): ?>

                <div class="image-gallery">
    <?php foreach ($bookedImages as $image): ?>
        <div class="image-item" onclick="openModal('<?php echo $image['path']; ?>', '<?php echo $image['owner']; ?>', '<?php echo $image['date']; ?>', '<?php echo $image['duration']; ?>', '<?php echo $image['status']; ?>', '<?php echo $image['type']; ?>', '<?php echo $image['rating']; ?>')">
            <img src="<?php echo $image['path']; ?>" alt="Booked Room Image">
            <p><strong><?php echo $image['owner']; ?></strong></p>
            <div class="rating" data-image-path="<?php echo $image['path']; ?>">
                <br>
                <strong><p class="rate-here">Rate me</p></strong>
                <div class="stars-container">
                    <?php 
                        $rating = $image['rating'] ? (int)$image['rating'] : 0;
                        for ($i = 1; $i <= 5; $i++) {
                            $filled = $i <= $rating ? 'filled' : 'empty';
                            echo "<span class='star $filled' data-rating='$i'>&#9733;</span>";
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

            <?php else: ?>
                <p>No booked images found.</p>
                <?php endif; ?>
        </div>
    </main>
            </div>

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


    <div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img class="modal-image" id="modalImage">
        <div class="modal-details" id="modalDetails"></div>
        <button type="button" id="rescheduleBtn" onclick="openUpdateModal()">Reschedule</button>
    </div>
</div>

<div id="updateModal" class="modal">
    <span class="close" onclick="closeUpdateModal()">&times;</span>
    <div class="modal-content">
        <h2>Update Booking/Reservation</h2>
        <br>
        <form id="updateForm">
            <div>
                <label for="newDate">New Booking Date:</label>
                <input type="date" id="newDate" required>
            </div>
        <br>
            <div>
                <label for="newDuration">New Duration:</label>
                <select id="newDuration" required>
                    <option value="24">24 hours</option>
                    <option value="48">2 days</option>
                </select>
            </div>

            <button type="button" id="rescheduleBtn" onclick="rescheduleBooking()">Update</button>
        </form>
    </div>
</div>

    <script>
let currentImagePath = null;
let currentBookingDate = null;
let currentType = null;
let isRatingClick = false;

function openModal(imagePath, owner, bookingDate, duration, status, type) {
    if (isRatingClick) {    
        isRatingClick = false; 
        return; 
    }

    document.getElementById("modalImage").src = imagePath;
    document.getElementById("modalDetails").innerHTML = `
        <h2>${owner}</h2>
        <p>Booking Date: ${bookingDate}</p>
        <p>Duration: ${duration}</p>
        <p>Status: ${status}</p>
        <p>Type: ${type === 'booking' ? 'Booked' : 'Reserved'}</p>
    `;
    
    currentImagePath = imagePath;
    currentBookingDate = bookingDate;
    currentType = type;

    document.getElementById("imageModal").style.display = "block";
}

document.querySelectorAll('.star').forEach(function(star) {
    star.addEventListener('click', function(event) {
        isRatingClick = true; 
    });
});
function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}

function openUpdateModal() {

    document.getElementById("updateModal").style.display = "block";
    document.getElementById("newDate").value = currentBookingDate;
    document.getElementById("newDuration").value = currentType === 'booking' && currentBookingDate === '24' ? '24' : '48';
}

function closeUpdateModal() {
    document.getElementById("updateModal").style.display = "none";
}

function rescheduleBooking() {
    const newDate = document.getElementById("newDate").value;
    const newDuration = document.getElementById("newDuration").value;

    if (!newDate || !newDuration || newDuration <= 0 || !currentType) {
        alert('Please provide valid new date and duration!');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_booking.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                closeUpdateModal();
                closeModal();
                location.reload();  
            } else {
                alert(response.message);
            }
        } else {
            alert("Error in updating booking or reservation.");
        }
    };

    xhr.send(`image_path=${encodeURIComponent(currentImagePath)}&booking_date=${encodeURIComponent(currentBookingDate)}&new_date=${encodeURIComponent(newDate)}&new_duration=${encodeURIComponent(newDuration)}&type=${encodeURIComponent(currentType)}`);
}

window.onclick = function(event) {
    const modal = document.getElementById("imageModal");
    const updateModal = document.getElementById("updateModal");
    if (event.target === modal) {
        closeModal();
    }
    if (event.target === updateModal) {
        closeUpdateModal();
    }
}
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

    document.querySelectorAll('.star').forEach(function(star) {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            const imagePath = this.closest('.rating').getAttribute('data-image-path');

            const stars = this.closest('.rating').querySelectorAll('.star');
            stars.forEach(function(starElem) {
                starElem.classList.remove('filled');
                starElem.classList.add('empty');
            });

            for (let i = 0; i < rating; i++) {
                stars[i].classList.add('filled');
                stars[i].classList.remove('empty');
            }
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "rate_image.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Rating updated successfully!");
                    } else {
                        alert("Failed to update rating.");
                    }
                }
            };

            xhr.send(`image_path=${encodeURIComponent(imagePath)}&rating=${encodeURIComponent(rating)}`);
        });
    });

    </script>
</body>
</html>
