<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        
        .dashboard-content {
            margin: 20px;
        }

        .owner-container {
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
        }

        .owner-name {
            font-size: 2rem;
            color: black;
            margin-bottom: 15px;
            text-align: center;
        }

        .card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
}
        .card:hover {
            transform: scale(1.05); 
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        }
        .card:hover .card-body img {
            transform: scale(1.1); 
        }

        .modal {
            display: none;
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.8); 
            justify-content: center; 
            align-items: center; 
        }

        .modal-content {
            max-width: 90%; 
            max-height: 90%;
            border-radius: 15px; 
        }

        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        .card {
    border: none;
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    width: 250px; 
    height: 350px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
    overflow: hidden;
}

.card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 10px;
    height: 100%;
    overflow: hidden; 
}

.card-body img {
    width: 100%;
    height: 200px; 
    object-fit: cover; 
    border-radius: 15px;
    transition: transform 0.3s;
    margin-bottom: 10px;
}

.card-body .room-details {
    text-align: left; 
    width: 100%;
    padding: 10px;
    font-size: 0.9rem;
}

.room-details p {
    margin: 5px 0;
}

.room-details strong {
    color: #333;
}

.star {
    font-size: 1.2rem;
    color: #ffd700; /* Gold color for filled stars */
    margin-right: 5px;
}

.star.filled {
    color: #ffd700; /* Filled star color */
}

.star {
    color: #ccc; /* Empty star color */
}

    </style>
</head>
<body>
    
    <div class="sidebar">    
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(bckgnds/m.jpg)"></div>
                <h4>ADMIN</h4>
                <small>Admin</small>
            </div>
            <div class="side-menu">
                <ul>
                    <li>
                       <a href="stadmin.php">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="settings.php">
                            <span class="las la-user-graduate"></span>
                            <small>Student Log</small>
                        </a>
                    </li>
                    <li>
                       <a href="home_owner.php">
                            <span class="las la-user-tie"></span>
                            <small>Home Owner</small>
                        </a>
                    </li>
                    <li>
                       <a href="roomup.php" class="active">
                            <span class="las la-bed"></span>
                            <small>Rooms</small>
                        </a>
                    </li>
                    <li>
                       <a href="https://accounts.google.com/v3/signin/challenge/pwd?TL=AO-GBTedLDDkN4svV-Dlq9F1nZQDfLwiNooNQknDF6kkC4cnXb6_dL8hb-BMaTBN&checkConnection=youtube%3A1495&checkedDomains=youtube&cid=1&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&ddm=1&emr=1&flowName=GlifWebSignIn&followup=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&hl=fil&ifkv=AVdkyDl5Ldznok96sg7mRZVi9qGN_5Nb3HJ9Cj_rdRWWlr-I4YXbTgwsM9Ft3h8DktUlaIpYe9U2dQ&osid=1&pstMsg=1&service=mail">
                            <span class="las la-envelope"></span>
                            <small>Mailbox</small>
                        </a>
                    </li>
                    <li>
                    <a href="apayment.php">
                        <span class="las la-file-invoice-dollar"></span><small>Payment</small>
                     </a>
                </li>
                    <li>
                       <a href="auth/logout.php">
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
                <small>ADMIN Dashboard</small>
            </div>
            
            <div class="dashboard-content">
                <h2>Uploaded Room Images</h2>
                
                <?php
include("connect/connect.php");

// Define the generateStars function once outside of the loop
function generateStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star filled">&#9733;</span>';
        } else {
            $stars .= '<span class="star">&#9734;</span>';
        }
    }
    return $stars;
}

$query = "SELECT ri.image_path, o.bhouse_name, ri.room_location, ri.room_type, ri.price_range, ri.rating
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
    // Get the rating value from the database
    $rating = (int)$row['rating'];

    echo "<div class='card'>
            <div class='card-body'>
                <img src='" . htmlspecialchars($row['image_path']) . "'>
                <div class='room-details'>
                    <p><strong>Location:</strong> " . htmlspecialchars($row['room_location']) . "</p>
                    <p><strong>Type:</strong> " . htmlspecialchars($row['room_type']) . "</p>
                    <p><strong>Price Range:</strong> " . htmlspecialchars($row['price_range']) . "</p>
                    <p><strong>Rating:</strong> " . generateStars($rating) . "</p> <!-- Display the stars here -->
                </div>
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
        <img class="modal-content" id="modalImage">
        <div id="caption"></div>
    </div>

    <script src="scripts/script3.js"></script>
</body>
</html>
