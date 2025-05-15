<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Search</title>
    <link rel="stylesheet" href="css/style15.css" />
    <link rel="stylesheet" href="css/style12.css" />
</head>
<body>
    <div class="container">
        <h1>Search Results</h1>
        <br>

        <?php
   session_start();
        include("connect/connect.php");

        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : '';
        $lookingFor = isset($_GET['looking_for']) ? $_GET['looking_for'] : '';

        $query = "SELECT * FROM room_images WHERE 1";

        if ($location) {
            $query .= " AND room_location = ?";
        }

        if ($priceRange) {
            list($minPrice, $maxPrice) = explode('-', $priceRange);
            $query .= " AND price_range BETWEEN ? AND ?";
        }

        if ($lookingFor) {
            $query .= " AND room_type = ?";
        }

        $stmt = $conn->prepare($query);

        $params = [];
        $types = '';

        if ($location) {
            $params[] = $location;
            $types .= 's';
        }

        if ($priceRange) {
            $params[] = $minPrice;
            $params[] = $maxPrice;
            $types .= 'ii';
        }

        if ($lookingFor) {
            $params[] = $lookingFor;
            $types .= 's';
        }

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='room-card'>";
                echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Room Image'>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row['room_location']) . "</p>";
                echo "<p><strong>Price: $</strong>" . htmlspecialchars($row['price_range']) . "</p>";
                echo "<p><strong>Room Type:</strong> " . htmlspecialchars($row['room_type']) . "</p>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";

                echo "<a href='#' class='btn'>Book Now</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-results'>No rooms found matching your criteria.</div>";
        }

        $stmt->close();
        ?>
    </div>
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <div class="image-container">
                <img id="modalImage" src="" alt="Room Image">
            </div>
            <div id="bookingForm">
                <h2>Booking/Reservation Form</h2>
                <form id="form" method="POST" action="auth/bookingmodal.php">
                    <input type="hidden" id="image_path" name="image_path" value="">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="user_email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" disabled>

                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="user_name" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="booking_date" required>

                    <label for="action">Action:</label>
                    <select name="action" id="action" required>
                        <option value="reserve" selected>Reserve Room</option>
                        <option value="book">Book Room</option>
                    </select>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="script/script7.css"></script>
    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var bookButtons = document.querySelectorAll('.btn');

        bookButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                
                var imagePath = this.closest('.room-card').querySelector('img').src;

                document.getElementById('modalImage').src = imagePath;
                document.getElementById('image_path').value = imagePath;

                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
