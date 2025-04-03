<?php
require_once "includes/config.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID and booking ID
$user_id = $_SESSION['user_id'];
if (!isset($_POST['booking_id'])) {
    header("Location: account.php?error=Invalid request");
    exit();
}

$booking_id = intval($_POST['booking_id']);

// Fetch the booking details
$sql = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error); // Debugging output
}
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: account.php?error=Booking not found");
    exit();
}

$booking = $result->fetch_assoc();

// Check if booking is already canceled or in the past
if ($booking['status'] != 'confirmed' || strtotime($booking['check_in_date']) <= time()) {
    header("Location: account.php?error=Booking cannot be canceled");
    exit();
}

// Cancel the booking by updating its status
$update_sql = "UPDATE bookings SET status = 'canceled' WHERE booking_id = ?";
$update_stmt = $conn->prepare($update_sql);
if (!$update_stmt) {
    die("SQL Error: " . $conn->error); // Debugging output
}
$update_stmt->bind_param("i", $booking_id);
$update_stmt->execute();

// ✅ No need to update `available_rooms` manually

// Redirect back with success message
header("Location: account.php?success=Booking  successfully");
exit();
?>
