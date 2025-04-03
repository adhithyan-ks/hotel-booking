<?php 
include 'includes/config.php'; 

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure form data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['amount'], $_POST['payment_method'])) {
    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    // Generate a fake transaction ID
    $transaction_id = uniqid("txn_");

    // Insert payment record
    $query = "INSERT INTO payments (user_id, booking_id, amount, payment_status, payment_method, transaction_id) 
              VALUES (?, ?, ?, 'completed', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iidss", $user_id, $booking_id, $amount, $payment_method, $transaction_id);

    if ($stmt->execute()) {
        // Update booking table to mark as paid
        $updateBooking = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?");
        $updateBooking->bind_param("i", $booking_id);
        $updateBooking->execute();

        // Redirect to confirmation page
        header("Location: confirmation.php?booking_id=$booking_id");
        exit();
    } else {
        echo "Payment failed!";
    }
} else {
    echo "Invalid request!";
}
?>
