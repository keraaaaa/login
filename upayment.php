<?php
session_start();
include("connect/connect.php");

$notif = [];

$image_path = '';
$owner_image_path = '';
$phone_number = ''; 
$email = ''; 

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $user_query = mysqli_query($conn, "SELECT tele FROM `users` WHERE email='$email'");
    if ($user_row = mysqli_fetch_array($user_query)) {
        $phone_number = !empty($user_row['tele']) ? htmlspecialchars($user_row['tele']) : ''; 
    }

    $query = mysqli_query($conn, "
    SELECT room_images.image_path, room_images.owner_id 
    FROM room_images
    LEFT JOIN bookings ON bookings.image_path = room_images.image_path AND bookings.user_email = '$email'
    LEFT JOIN reservations ON reservations.image_path = room_images.image_path AND reservations.user_email = '$email'
    JOIN users ON users.email = '$email'
    WHERE users.email = '$email'
    LIMIT 1
");

    $booking_or_reservation = mysqli_fetch_array($query);

    if ($booking_or_reservation && isset($booking_or_reservation['image_path'])) {

        $image_path = $booking_or_reservation['image_path'];
        
        $owner_id = $booking_or_reservation['owner_id'];

        $owner_query = mysqli_query($conn, "SELECT owner_image FROM owners WHERE id='$owner_id'");
        $owner = mysqli_fetch_array($owner_query);
        
        if ($owner && isset($owner['owner_image'])) {
            $owner_image_path = $owner['owner_image'];
        }
    }
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

$sql_transactions = "SELECT transaction_id, phone_number, term, mode_of_payment, payment_date, status, email FROM transactions";
$result_transactions = $conn->query($sql_transactions);

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
        

        .owner-image {
            width: 100px;
            height: 100px;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        
        #payment-form-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        #form-section {
            width: 50%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        input, select, button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="text"], select {
            width: 100%;
        }

        button {
            background-color:rgb(242, 255, 0);
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background: rgb(245, 250, 153);
        }
        #image-section {
            width: 50%;
            text-align: center;
        }

        .owner-image {
            width: 100%;
            height: 500px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .owner-image p {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            #payment-form-container {
                flex-direction: column;
                padding: 15px;
            }

            #form-section, #image-section {
                width: 100%;
            }

            .owner-image {
                height: 200px;
            }
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

        table {
    width: 100%;  
    margin-top: 30px; 
    border-collapse: collapse;
}
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .page-header{
            border-bottom:none;
        }
        table th {
            background-color: #4CAF50;
            color: black;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .btn {
           
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn:focus {
            outline: none;
        }

        .btn-print {
            background-color:rgb(242, 255, 0);
            margin-left: 10px;
        }

        .btn-print:hover {
            background: rgb(245, 250, 153);
        }
        @media print {
            .sidebar, .side-menu, .btn, .btn-print, h1, .notification-bell{
        display: none;
    }

    body {
        background-color: white;
        margin: 0;
        padding: 0;
        font-size: 12px; 
    }
    .main-content{
        width: 100%;  
        max-width: 900px; 
        margin: 0 auto;  
        padding: 10px;
    }
 
    #payment-form-container {
        max-width: 100%;
        margin: 0;
        padding: 0;
        display: none;
        page-break-inside: avoid; 
    }
  
    table {
        width: 100%;  
        border-collapse: collapse;
        margin-top: 20px;
        margin: 0;
    }
    table th, table td {
        padding: 8px;  
        font-size: 12px; 
        border: 1px solid #000;
    }

    table th {
        background-color: #4CAF50;
       
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    table tr:hover {
        background-color: transparent; 
    }

    @page {
        size: auto;
        margin: 20mm; 
    }

    .owner-image, .payment-form {
        page-break-inside: avoid; 
    }

    p {
        font-size: 14px;
    }
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
                        <a href="payment.php" class="active">
                            <span class="las la-file-invoice-dollar">
                            </span><small>Payment</small>
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
                <h1>Payment/Bills</h1>
                <br>
                <button class="btn btn-print" onclick="printPage()">Print</button>

            <div id="payment-form-container">
    <div id="form-section">
        <h2>Payment Form</h2>
        <form action="upayment_process.php" method="POST">

            <label for="transaction-id">Transaction ID:</label>
            <input type="text" id="transaction-id" name="transaction-id" required>

            <label for="phone-number">Phone Number:</label>
            <input type="text" id="phone-number" name="phone-number" value="<?php echo $phone_number; ?>" disabled>
            <input type="hidden" name="phone-number" value="<?php echo htmlspecialchars($phone_number); ?>"> 
            
            <label for="term">Term:</label>
            <input type="text" id="term" name="term" value="Monthly" disabled>
            <input type="hidden" id="term" name="term" value="Monthly">

            <label for="mode_of_payment">Mode of payment:</label>
        <input type="text" id="mode_of_payment" name="mode_of_payment" value="Gcash" disabled><br>
        <input type="hidden" id="mode_of_payment" name="mode_of_payment" value="Gcash"><br>


        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <button type="submit">Submit Payment</button>
        </form>
    </div>

    <div id="image-section">
        <?php if ($owner_image_path): ?>
            <div class="owner-image" style="background-image: url('uploads/<?php echo htmlspecialchars($owner_image_path); ?>');"></div>
            <p><strong>Owner's QR Code</strong></p>
        <?php else: ?>
            <p>QR Code/Booking/Reservations Missing!</p>
        <?php endif; ?>
    </div>
</div>

<h2>My Transaction Records</h2>
        <?php if ($result_transactions->num_rows > 0): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Phone Number</th>
                        <th>Term</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_transactions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['mode_of_payment']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['term']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transaction records found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
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
  function printPage() {
            window.print();
        }
        </script>
</body>
</html>
