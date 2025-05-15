<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Search</title>
    <link rel="stylesheet" href="css/style15.css" />
</head>
<body>
    <div class="container">
        <h1>Search Results</h1>
        <br>

        <?php
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
                echo "<p>Location: " . htmlspecialchars($row['room_location']) . "</p>";
                echo "<p>Price: $" . htmlspecialchars($row['price_range']) . "</p>";
                echo "<p>Room Type: " . htmlspecialchars($row['room_type']) . "</p>";
                echo "<a href='index.php' class='btn'>Sign In/Register to Book</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-results'>No rooms found matching your criteria.</div>";
        }

        $stmt->close();
        ?>
    </div>
</body>
</html>
