<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/rooms.css" />
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png" />
    <title>Rooms</title>
</head>
<body>
<?php include 'includes/header.php'; ?>
<main class="container">
    <?php
    // Fetch all rooms from database
    $query = "SELECT * FROM rooms";
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
                    <div><a href="#" class="button">Book Now</a></div>
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
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
