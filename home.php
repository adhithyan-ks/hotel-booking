<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HB Website - Home</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section class="hero">
        <div class="overlay">
            <h1>Welcome to HB Website</h1>
            <p>Experience luxury and comfort at our premium hotel.</p>

            <form class="booking-form">
                <label for="check-in">Check-in</label>
                <input type="date" id="check-in" required>

                <label for="check-out">Check-out</label>
                <input type="date" id="check-out" required>


                <button type="submit">Submit</button>
            </form>
        </div>
    </section>

    <!-- Featured Rooms -->
<section class="featured-rooms">
    <div class="container">
        <h2>Featured Rooms</h2>
        <div class="room-list">
            <?php
            $query = "SELECT * FROM room_types ORDER BY price_per_night ASC LIMIT 3";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
            <div class="room-card">
                <img src="<?= $row['image_url']; ?>" alt="Room Image">
                <div class="room-info">
                    <h3><?= $row['room_type']; ?></h3>
                    <p><?= $row['description']; ?></p>
                    <p class="room-price">Price per night: ₹<?= $row['price_per_night']; ?></p>
                    <a href="rooms.php" class="button">View More</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No rooms available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
    <script src="js/navbar.js"></script>
</body>
</html>
