<?php 
include 'includes/config.php'; 

if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: home.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Fetch booking and payment details
$query = "SELECT b.*, p.payment_status, p.transaction_id, p.payment_method, rt.room_type, rt.image_url 
          FROM bookings b 
          LEFT JOIN payments p ON b.booking_id = p.booking_id
          JOIN rooms r ON b.room_id = r.room_id
          JOIN room_types rt ON r.room_type = rt.room_type
          WHERE b.booking_id = ? AND b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Invalid booking!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="icon" href="images/logo/hotellogo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/confirmation.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h2>Booking Confirmed!</h2>
    <div class="booking-details">
        <img src="<?= htmlspecialchars($booking['image_url']); ?>" alt="Room Image">
        <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['booking_id']); ?></p>
        <p><strong>Room Type:</strong> <?= htmlspecialchars($booking['room_type']); ?></p>
        <p><strong>Check-in:</strong> <?= htmlspecialchars($booking['check_in_date']); ?></p>
        <p><strong>Check-out:</strong> <?= htmlspecialchars($booking['check_out_date']); ?></p>
        <p><strong>Total Price:</strong> ₹<?= htmlspecialchars($booking['total_price']); ?></p>
        
        <h3>Payment Details</h3>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($booking['payment_method'] ?? 'N/A'); ?></p>
        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($booking['transaction_id'] ?? 'N/A'); ?></p>
        <p><strong>Payment Status:</strong> <?= ($booking['payment_status'] == 'completed') ? '<span class="success">Paid</span>' : '<span class="pending">Pending</span>'; ?></p>
        
        <a href="account.php" class="button">View My Bookings</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
