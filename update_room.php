<?php
session_start();
include("connect/connect.php");

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerslog.php");
    exit();
}
$ownerId = $_SESSION['owner_id'];

$query = "SELECT id, image_path, description, room_location, price_range, room_type FROM room_images WHERE owner_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("i", $ownerId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Update Room Images</title>
    <link rel="stylesheet" href="css/style15.css">
    <style>
        .image-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            display: inline-block;
            margin: 10px;
            text-align: center;
            width: 200px;
        }

        .image-item img {
            width: 100%;
            height:200px;
            transition: transform 0.3s ease;
        }

        .image-item:hover img {
            transform: scale(1.1);
        }

        .edit-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: blue;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .image-details {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }

        .image-details p {
            margin: 5px 0;
        }

        .image-details strong {
            color: #007bff;
        }

        #editModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        #editModal form {
            background: #fff;
            padding: 40px 20px 20px 20px; 
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        #editModal h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        #editModal label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        #editModal input,
        #editModal textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        #editModal button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        #editModal button:hover {
            background-color: #0056b3;
        }

        #editModal button[type="button"] {
            background-color: #ccc;
        }

        #editModal button[type="button"]:hover {
            background-color: #aaa;
        }
.form-group select {
    width: 90%; 
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 14px;
    margin-bottom: 15px;
    font-family: Arial, sans-serif; 
    box-sizing: border-box; 
    background-color: #fff; 
    transition: border-color 0.3s ease; 
}


.form-group select:focus {
    border-color: #007bff; 
    outline: none; 
}

.form-group select:hover {
    border-color: #0056b3;
}
 .back-button {
            background-color: #ccc;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            float:right;
        }

        .back-button:hover {
            background-color: #aaa;
        }

    </style>
</head>
<body>
    <div class="main-content">
        <main>
        <button class="back-button" onclick="window.history.back()">Back</button>
            <div class="page-header">
                <h1>Update Room Images</h1>
            </div>
            <div class="image-gallery">
              
                <br><br>
                <div class="image-container">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $imageId = $row['id'];
                            $imagePath = $row['image_path'];
                            $description = htmlspecialchars($row['description']);
                            $roomLocation = htmlspecialchars($row['room_location']);
                            $priceRange = htmlspecialchars($row['price_range']);
                            $roomType = htmlspecialchars($row['room_type']);

                            echo "<div class='image-item'>
                                    <img src='" . htmlspecialchars($imagePath) . "' alt='Room Image'>
                                    <div class='image-details'>
                                        <p><strong>Description:</strong> $description</p>
                                        <p><strong>Room Location:</strong> $roomLocation</p>
                                        <p><strong>Price Range:</strong> $priceRange</p>
                                        <p><strong>Room Type:</strong> $roomType</p>
                                        <br><br><br>
                                    <button class='edit-button' onclick='editImage($imageId, \"$description\", \"$roomLocation\", \"$priceRange\", \"$roomType\")'>Edit</button>
                                    </div>
                                  </div>";
                        }

                    } else {
                        echo "<p>No images uploaded yet.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <div id="editModal">
    <form method="POST" action="update_image_details.php" id="editForm">
        <h3>Edit Image Details</h3>

        <input type="hidden" name="image_id" id="image_id">
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea><br>
        </div>

        <div class="form-group">
            <label for="room_location">Room Location:</label>
            <select name="room_location" id="room_location" required class="form-control">
                <option value="Agujo">Agujo</option>
                <option value="Poblacion">Poblacion</option>
            </select>
        </div>

        <div class="form-group">
            <label for="price_range">Price Range:</label>
            <select name="price_range" id="price_range" required class="form-control">
                <option value="500-700">₱500 - ₱700</option>
                <option value="800-1000">₱800 - ₱1,000</option>
            </select>
        </div>
        <div class="form-group">
            <label for="room_type">Room Type:</label>
            <select name="room_type" id="room_type" required class="form-control">
                <option value="Bedspacer">BedSpacer</option>
                <option value="Private Room">Private Room</option>
            </select>
        </div>

        <button type="submit">Update</button>
        <button type="button" onclick="closeEditModal()">Cancel</button>
    </form>
</div>

    <script>

function editImage(imageId, description, roomLocation, priceRange, roomType) {
    document.getElementById("image_id").value = imageId;
    document.getElementById("description").value = description;
    document.getElementById("room_location").value = roomLocation;
    document.getElementById("price_range").value = priceRange;
    document.getElementById("room_type").value = roomType;
    document.getElementById("editModal").style.display = "flex";
}


        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }

    </script>
</body>
</html>
