<?php 
include 'includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/rooms.css" />
    <link rel="stylesheet" href="css/filter.css" />
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png" />
    <title>Rooms</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h2>Find Your Perfect Room</h2>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <label for="roomType">Room Type:</label>
            <select name="roomType" id="roomType">
                <option value="">All</option>
                <option value="Standard">Standard</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Suite">Suite</option>
                <option value="Family Room">Family Room</option>
            </select>

            <label for="priceRange">Max Price:</label>
            <input type="number" name="priceRange" id="priceRange" placeholder="Enter max price">

            <label for="availability">Availability:</label>
            <select name="availability" id="availability">
                <option value="">All</option>
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>

            <button type="submit" class="button">Search</button>
        </form>

        <!-- Fetch and Display Filtered Rooms -->
        <?php
        // Default SQL query
        $query = "SELECT * FROM rooms WHERE 1=1"; 

        // Apply filters if selected
        if (!empty($_GET['roomType'])) {
            $roomType = $_GET['roomType'];
            $query .= " AND room_type = '$roomType'";
        }

        if (!empty($_GET['priceRange'])) {
            $maxPrice = $_GET['priceRange'];
            $query .= " AND price_per_night <= $maxPrice";
        }

        if (isset($_GET['availability']) && $_GET['availability'] !== "") {
            $availability = $_GET['availability'];
            $query .= " AND avail_status = $availability";
        }

        // Execute Query
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="room-card">
                    <img src="<?= $row['image_url']; ?>" alt="Room Image">
                    <div class="room-info">
                        <h2><?= $row['room_type']; ?></h2>
                        <p><?= $row['description']; ?></p>
                        <p class="room-price">Price per night: ₹<?= $row['price_per_night']; ?></p>
                        <p class="availability">Availability Status: 
                            <span><?= ($row['avail_status'] == 1) ? "Yes" : "No"; ?></span>
                        </p>
                        <div><a href="booking.php?room_id=<?= $row['room_id']; ?>" class="button">Book Now</a></div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No rooms match your criteria.</p>";
        }
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
    <script src="js/navbar.js"></script>
</body>
</html>
