<?php
require_once "../includes/config.php"; // Database connection

// Ensure an admin is canceling the booking
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    // Debugging: Check if the booking ID is received
    error_log("Admin attempting to cancel booking ID: " . $booking_id);

    // Fetch the booking details
    $sql = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging output
    }
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: bookings.php?error=Booking not found");
        exit();
    }

    $booking = $result->fetch_assoc();

    // Check if booking is already canceled or if check-in date is in the past
    if ($booking['status'] != 'confirmed' || strtotime($booking['check_in_date']) <= time()) {
        header("Location: bookings.php?error=Booking cannot be canceled");
        exit();
    }

    // Cancel the booking by updating its status
    $update_sql = "UPDATE bookings SET status = 'canceled' WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("SQL Error: " . $conn->error);
    }
    $update_stmt->bind_param("i", $booking_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        // Success, redirect back to bookings page
        header("Location: bookings.php?success=Booking canceled successfully");
        exit();
    } else {
        // No rows updated (possible error)
        header("Location: bookings.php?error=Failed to cancel booking");
        exit();
    }
} else {
    header("Location: bookings.php?error=Invalid request");
    exit();
}
?>
