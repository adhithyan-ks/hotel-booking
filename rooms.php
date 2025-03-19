<?php include 'includes/config.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/rooms.css" />
    <link rel="stylesheet" href="css/filter.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png" />
    <title>Rooms</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h2>Find Your Perfect Room</h2>

        <form class="booking-form" method="GET">
            <label for="check-in">Check-in</label>
            <input type="date" name="check_in_date" id="check-in" required>

            <label for="check-out">Check-out</label>
            <input type="date" name="check_out_date" id="check-out" required>

            <button type="submit">Submit</button>

            <!-- Clear Filters Button -->
            <button type="button" class="clear-button" onclick="clearFilters()">Clear Filters</button>
        </form>


        <!-- Fetch and Display Available Room Types -->
        <?php
        // Check if user entered dates
        $dateFilter = !empty($_GET['check_in_date']) && !empty($_GET['check_out_date']);

        if ($dateFilter) {
            $check_in_date = $_GET['check_in_date'];
            $check_out_date = $_GET['check_out_date'];

            // Get total rooms per type and booked rooms count, sorted by price (ascending)
            $query = "SELECT rt.room_type, rt.description, rt.price_per_night, rt.image_url, 
                    COUNT(r.room_id) AS total_rooms,
                    (SELECT COUNT(*) FROM bookings b 
                     JOIN rooms r2 ON b.room_id = r2.room_id
                     WHERE r2.room_type = rt.room_type
                     AND (b.check_in_date < '$check_out_date' AND b.check_out_date > '$check_in_date')
                    ) AS booked_rooms
              FROM rooms r
              JOIN room_types rt ON r.room_type = rt.room_type
              GROUP BY rt.room_type
              ORDER BY rt.price_per_night ASC";

        } else {
            // Show only room types sorted by price if no date is selected
            $query = "SELECT * FROM room_types ORDER BY price_per_night ASC";
        }

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Calculate available rooms
                $available_rooms = $dateFilter ? ($row['total_rooms'] - $row['booked_rooms']) : "N/A";

                ?>
                <div class="room-card">
                    <img src="<?= $row['image_url']; ?>" alt="Room Image">
                    <div class="room-info">
                        <h3><?= $row['room_type']; ?></h3>
                        <p><?= $row['description']; ?></p>
                        <p class="room-price">Price per night: ₹<?= $row['price_per_night']; ?></p>
                        <p class="availability">
                            <?= $dateFilter ? "Available Rooms: <strong>$available_rooms</strong>" : "Check dates to see availability"; ?>
                        </p>
                        <div>
                            <a href="booking.php?room_type=<?= $row['room_type']; ?>&check_in_date=<?= $_GET['check_in_date'] ?? '' ?>&check_out_date=<?= $_GET['check_out_date'] ?? '' ?>" class="button">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No rooms available.</p>";
        }
        ?>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
        function clearFilters() {
            window.location.href = "rooms.php"; // Reload page to reset filters
        }
    </script>
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
    <script src="js/navbar.js"></script>
</body>
</html>
