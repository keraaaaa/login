<?php
session_start();
include("connect/connect.php");

$images_by_owner = [];
$notif = [];

$query = "
    SELECT room_images.image_path, room_images.is_available, owners.bhouse_name AS owner_name, room_images.id, room_images.price_range, room_images.description, room_images.rating
    FROM room_images 
    JOIN owners ON room_images.owner_id = owners.id
";

if ($result = mysqli_query($conn, $query)) {
    while ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['image_path'])) {
            $owner_name = htmlspecialchars($row['owner_name']);
            $images_by_owner[$owner_name][] = [
                'image_path' => htmlspecialchars($row['image_path']),
                'is_available' => $row['is_available'],
                'id' => $row['id'],
                'price_range' => htmlspecialchars($row['price_range']),
                'description' => htmlspecialchars($row['description']),
                'rating' => $row['rating']
            ];
        }
    }
    mysqli_free_result($result);
} else {
    die("Error executing query: " . mysqli_error($conn));
}

    
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Rooms</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/style7.css">
    <link rel="stylesheet" href="css/style12.css">
    <link rel="stylesheet" href="css/style14.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        .form__group select {
            padding: 10px 12px; 
            font-size: 14px; 
            width: auto; 
            background-color: transparent; 
            border: 2px solid #000;
            border-radius: 20px; 
            appearance: none; 
            outline: none; 
            cursor: pointer;
        }
        .room-price {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    color: #000;
    margin-top: 10px;
}
        .question-text {
    font-size: 13px; 
    font-family: Arial, sans-serif; 
    margin: 10px 0; 
}
    .availability-button{
        background-color: green;
     cursor:not-allowed;
    }
    
    .room-description {
    font-size: 14px;
    font-family: Arial, sans-serif;
    margin-top: 10px;
    padding: 5px;
    color: #333;
}
   .rating {
    position: absolute;
    top: 5px;  
    left: 70%;
    transform: translateX(-50%);
    font-size: 24px; 
    color: gold;
    z-index: 1;
}
        .rating span {
            cursor: pointer;
        }
        .filled {
            color: gold;
        }
        .unavailable {
    background-color: red;
    cursor: not-allowed;
}

.disabled {
    background-color: gray;
    cursor: not-allowed;
}


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

    </style>
</head>
<body>
<input type="checkbox" id="menu-toggle">
<div class="sidebar">
    <div class="side-content">
        <div class="profile">
            <div class="profile-img bg-img" style="background-image: url('<?php 
                if (isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    $query = "SELECT profile_picture FROM users WHERE email = ?";
                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $profile_picture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'uploads/default.jpg';
                            echo $profile_picture;
                        } else {
                            echo 'uploads/default.jpg';
                        }
                        $stmt->close();
                    }
                } else {
                    echo 'uploads/default.jpg';
                }
            ?>');">
            </div>
            <small><?php 
                if (isset($_SESSION['email'])) {
                    echo htmlspecialchars($email);
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
                    <a href="stprof.php">
                        <span class="las la-user-graduate"></span><small>Profile</small>
                    </a>
                </li>
                <li>
                    <a href="checkroom.php" class="active">
                        <span class="las la-bed"></span><small>Rooms</small>
                     </a>
                </li>
                    <li>
                        <a href="upayment.php">
                            <span class="las la-file-invoice-dollar"></span><small>Payment</small>
                        </a>
                    </li>
                <li>
                    <a href="auth/Ulogout.php">
                        <span class="las la-power-off"></span><small>Logout</small>
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
            <small><?php echo htmlspecialchars($email); ?></small>
        </div>

        <div class="booking__container">
            <h2>Search for Rooms</h2>
            <br><br>
            <form id="searchForm" class="search-form">
                <div class="input__group">
                    <div class="form__group">
                        <select id="location" name="location">
                            <option value="" disabled selected>Select Location ▾</option>
                            <option value="Daanbantayan">Poblacion</option>
                            <option value="Agujo">Agujo</option>
                        </select>
                    </div>
                    <p class="question-text">Where are you going?</p> 
                </div>
                <div class="form__group">
                    <div class="input__group">
                        <select id="price_range" name="price_range">
                            <option value="" disabled selected>Select Price Range ▾</option>
                            <option value="500-700">₱500 - ₱700</option>
                            <option value="800-1000">₱800 - ₱1,000</option>
                        </select>
                    </div>
                    <p>What is your budget?</p>
                </div>

                <div class="form__group">
                    <div class="input__group">
                        <select id="looking_for" name="looking_for">
                            <option value="" disabled selected>What are you looking for? ▾</option>
                            <option value="Bedspacer">Bed Spacer</option>
                            <option value="Private Room">Private Room</option>
                        </select>
                    </div>
                    <p>What amenities or room types are you interested in?</p>
                </div>
                <div class="search-btn-container">
    <button type="button" class="btn search-btn" onclick="redirectToLocation()">
        <i class="ri-search-line"></i>
    </button>
            </div>
            </form>
        </div>
        <br>
        <div class="image-gallery">
    <?php if (!empty($images_by_owner)): ?>
        <?php foreach ($images_by_owner as $owner => $images): ?>
            <div class="owner-container">
                <h2>Uploaded Rooms</h2>
                <div class="owner-title">Uploaded by: <?php echo $owner; ?></div>
                <div class="portfolio-container">
                    <?php foreach ($images as $image): ?>
                        <div class="image-item <?php echo $image['is_available'] ? '' : 'unavailable'; ?>" style="position: relative;">
                            <a href="#" class="image-link" data-image="<?php echo $image['image_path']; ?>" 
                               <?php echo $image['is_available'] ? '' : 'style="pointer-events: none;"'; ?>>
                                <img src="<?php echo $image['image_path']; ?>" alt="Room Image">
                            </a>
                            
                            <div class="room-price">
                                <p>Price: ₱<?php echo $image['price_range']; ?></p>
                            </div>

                            <div class="rating">
                                <?php 
                                    $rating = $image['rating']; 
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '<span class="filled">★</span>' : '<span>☆</span>';
                                    }
                                ?>
                            </div>

                            <div class="availability-button-container">
                                <?php if (isset($_SESSION['owner_id'])): ?>
                                    <button class="availability-button <?php echo $image['is_available'] ? 'available' : 'unavailable'; ?>" onclick="toggleAvailability(<?php echo $image['id']; ?>, <?php echo $image['is_available']; ?>)">
                                        <?php echo $image['is_available'] ? 'Available' : 'Unavailable'; ?>
                                    </button>
                                <?php else: ?>
                                    <button class="availability-button disabled" disabled>
                                        <?php echo $image['is_available'] ? 'Available' : 'Unavailable'; ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="room-description">
                                <p><?php echo nl2br(htmlspecialchars($image['description'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No images uploaded yet.</p>
    <?php endif; ?>
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


        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <div class="modal-content">
                <div class="image-container">
                    <img id="modalImage">
                </div>
                <div id="bookingForm">
    <h2>Booking/Reservation Form</h2>
    <form id="form" method="POST" action="auth/booking_process.php">

    <input type="hidden" id="image_path" name="image_path" value="path/to/selected/image.jpg">

    <label for="email">Email:</label>
    <input type="email" id="email" name="user_email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($email) : ''; ?>" disabled>

    <label for="name">Full Name:</label>
    <input type="text" id="name" name="user_name" required>
<br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="booking_date" required>
<br>
    <label for="duration">Duration:</label>
    <select id="duration" name="duration" required>
        <option value="24hrs">24hrs</option>
        <option value="2days">2 days</option>
    </select>
    
<br><br>
    <label for="action">Action:</label>
    <select name="action" id="action" required>
        <option value="reserve" selected>Reserve Room</option>
        <option value="book">Book Room</option>
    </select>
<br>
<br>
    <button type="submit">Submit</button>
</form>

</div>

            </div>
        </div>
    </main>

</div>

<script src="scripts/script7.js"></script>
<script src="scripts/script1.js"></script>

<script>
function openModal(imagePath, priceRange) {
    document.getElementById("modalImage").src = imagePath;

    document.getElementById("room_image_path").value = imagePath;
    var roomId = imagePath.split('/').pop().split('.')[0]; 
    document.getElementById("room_id").value = roomId;
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
}

var modal = document.getElementById("myModal");
var closeBtn = document.getElementsByClassName("close")[0];
closeBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function redirectToLocation() {
    var location = document.getElementById("location").value;
    var price_range = document.getElementById("price_range").value;
    var looking_for = document.getElementById("looking_for").value;

    window.location.href = 'checkroom.php?location=' + location + '&price_range=' + price_range + '&looking_for=' + looking_for;
}
function redirectToLocation() {

var location = document.getElementById("location").value;
var priceRange = document.getElementById("price_range").value;
var lookingFor = document.getElementById("looking_for").value;

if (!location || !priceRange || !lookingFor) {

  alert("Please fill in all the fields before searching.");
  return; 
}

var url = "student_search.php?";

if (location) {
  url += "location=" + location + "&";
}
if (priceRange) {
  url += "price_range=" + priceRange + "&";
}
if (lookingFor) {
  url += "looking_for=" + lookingFor + "&";
}

url = url.slice(0, -1); 

window.location.href = url; 
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


</script>

</body>
</html>
