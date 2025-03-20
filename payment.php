<?php 
include 'includes/config.php'; 

// Ensure user is logged in and booking ID is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: home.php");
    exit();
}

$booking_id = $_GET['booking_id'];

// Fetch booking details
$query = "SELECT b.*, rt.room_type FROM bookings b 
          JOIN rooms r ON b.room_id = r.room_id
          JOIN room_types rt ON r.room_type = rt.room_type
          WHERE b.booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
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
    <title>Secure Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .payment-container {
            background: white;
            padding: 25px;
            width: 400px;
            border: 1px solid #ccc;
            text-align: left;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0056b3;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .payment-details {
            font-size: 16px;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        select, input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
        }

        .pay-button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            text-transform: uppercase;
        }

        .pay-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Secure Payment</h2>
        
        <p class="payment-details">Booking for: <strong><?= $booking['room_type']; ?></strong></p>
        <p class="payment-details">Total Amount: <strong>₹<?= $booking['total_price']; ?></strong></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="<?= $booking_id; ?>">
            <input type="hidden" name="amount" value="<?= $booking['total_price']; ?>">

            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="upi">UPI</option>
                <option value="cash">Cash on Arrival</option>
            </select>

            <button type="submit" class="pay-button">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>
