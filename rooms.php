<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check Rooms</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/style10.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
<div class="sidebar">    
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url()"></div>
                <h4>User</h4>
                <small>User</small>
            </div>
            <div class="side-menu">
                <ul>
                    <li>
                       <a href="#">
                            <span class="las la-envelope"></span>
                            <small>Message Us</small>
                        </a>
                    </li>
                    <li>
                       <a href="auth/logout.php">
                            <span class="las la-backward"></span>
                            <small>Back to Homepage</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        <main>             
            <div class="dashboard-content">
                <?php
                include("connect/connect.php");

                $query = "SELECT ri.image_path, o.bhouse_name, ri.price_range
                          FROM room_images ri 
                          JOIN owners o ON ri.owner_id = o.id 
                          ORDER BY o.bhouse_name";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $currentOwner = '';
                while ($row = $result->fetch_assoc()) {
                    if ($currentOwner != $row['bhouse_name']) {
                        if ($currentOwner != '') {
                            echo '</div>';
                        }
                        $currentOwner = $row['bhouse_name'];
                        echo "<div class='owner-container'>
                                <div class='owner-name'>" . htmlspecialchars($currentOwner) . "</div>
                                <div class='card-container'>";
                    }
                    echo "<div class='card' data-price='" . htmlspecialchars($row['price_range']) . "'>
                            <div class='card-body'>
                                <img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['bhouse_name']) . "'>
                                <div class='price-range'>" . htmlspecialchars($row['price_range']) . "</div>
                            </div>
                          </div>";
                }
                if ($currentOwner != '') {
                    echo '</div>'; 
                }

                $stmt->close();
                ?>
            </div>
        </main>       
    </div>

    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-details">
                <div id="caption"></div>
                <div class="price-range" id="priceRange"></div>
                <button class="book-now-btn" id="bookNowBtn">Book Now</button>
            </div>
        </div>
    </div>
    <script src="scripts/script4.js"></script>
</body>
</html>
