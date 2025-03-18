<?php
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    echo "Invalid Booking!";
    exit;
}

$bookingId = intval($_GET['booking_id']);

// Fetch booking details
$query = "SELECT b.*, r.room_type, rt.price_per_night, rt.image_url 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.room_id
          JOIN room_types rt ON r.room_type = rt.room_type
          WHERE b.booking_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Booking not found!";
    exit;
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <link rel="stylesheet" href="css/confirmation.css">
    <style>
        img {
            width: 100px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h2>Booking Confirmed!</h2>
        <div class="booking-details">
            <img src="<?= $booking['image_url']; ?>" alt="Room Image">
            <h3><?= $booking['room_type']; ?></h3>
            <p><strong>Check-in:</strong> <?= $booking['check_in_date']; ?></p>
            <p><strong>Check-out:</strong> <?= $booking['check_out_date']; ?></p>
            <p><strong>Total Price:</strong> ₹<?= $booking['total_price']; ?></p>
        </div>
        <a href="account.php" class="button">View My Bookings</a>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
    <script src="js/navbar.js"></script>
</body>
</html>
