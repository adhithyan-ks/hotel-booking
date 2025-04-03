<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo/hotellogo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Book Room</title>
</head>
<body>
<?php include 'includes/header.php'; ?>

<?php 
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure a room_type is provided
if (!isset($_GET['room_type'])) {
    echo "Room type not specified!";
    exit;
}

$roomType = $_GET['room_type'];
$checkInDate = $_GET['check_in_date'] ?? '';
$checkOutDate = $_GET['check_out_date'] ?? '';

$availabilityMessage = "";

// Fetch room type details
$query = "SELECT * FROM room_types WHERE room_type = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $roomType);
$stmt->execute();
$roomTypeResult = $stmt->get_result();

if ($roomTypeResult->num_rows == 0) {
    echo "Invalid room type!";
    exit;
}

$roomTypeData = $roomTypeResult->fetch_assoc();

// Check availability when user selects dates
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['check_availability'])) {
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    // Get total rooms of the selected type
    $queryTotalRooms = "SELECT COUNT(*) as total_rooms FROM rooms WHERE room_type = ?";
    $stmt = $conn->prepare($queryTotalRooms);
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $totalRoomsResult = $stmt->get_result();
    $totalRooms = $totalRoomsResult->fetch_assoc()['total_rooms'] ?? 0;

    // Check how many rooms are already booked in the selected dates
    $query = "SELECT COUNT(*) AS booked_rooms FROM bookings b 
          JOIN rooms r ON b.room_id = r.room_id
          WHERE r.room_type = ? 
          AND (b.check_in_date < ? AND b.check_out_date > ?)
          AND b.status = 'completed'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $roomType, $checkOutDate, $checkInDate);
    $stmt->execute();
    $bookedRoomsResult = $stmt->get_result();
    $bookedRooms = $bookedRoomsResult->fetch_assoc()['booked_rooms'] ?? 0;

    // Calculate available rooms
    $availableRooms = $totalRooms - $bookedRooms;

    if ($availableRooms > 0) {
        $availabilityMessage = "<p class='success-message'>$availableRooms rooms available. You can proceed with booking.</p>";
    } else {
        $availabilityMessage = "<p class='error-message'>Sorry, no available rooms for the selected dates.</p>";
    }
}

// Process Booking
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirm_booking'])) {
    if (!empty($availabilityMessage) && strpos($availabilityMessage, 'Sorry') !== false) {
        echo "<p class='error-message'>This room type is not available for the selected dates.</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $checkInDate = $_POST['check_in_date'];
        $checkOutDate = $_POST['check_out_date'];

        // Find an available room of this type
        $query = "SELECT room_id FROM rooms r WHERE room_type = ? 
                  AND NOT EXISTS (
                      SELECT 1 FROM bookings b 
                      WHERE b.room_id = r.room_id
                      AND (b.check_in_date < ? AND b.check_out_date > ?)
                  ) LIMIT 1";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $roomType, $checkOutDate, $checkInDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $availableRoom = $result->fetch_assoc();

        if ($availableRoom) {
            $room_id = $availableRoom['room_id'];

            // Calculate total price
            $date1 = new DateTime($checkInDate);
            $date2 = new DateTime($checkOutDate);
            $nights = $date1->diff($date2)->days;
            $totalPrice = $nights * $roomTypeData['price_per_night'];

            // Insert booking into database with payment status = pending
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status) 
                                    VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("iissd", $user_id, $room_id, $checkInDate, $checkOutDate, $totalPrice);
            
            if ($stmt->execute()) {
                // Redirect to payment page
                header("Location: payment.php?booking_id=" . $conn->insert_id);
                exit();
            } else {
                echo "<p class='error-message'>Booking failed: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            $availabilityMessage="<p class='error-message'>No rooms of this type available for the selected dates.</p>";
        }
    }
}
?>

<main class="container">
    <h2>Book Your Room</h2>
    <div class="room-container">
        <div class="room-image">
            <img src="<?= $roomTypeData['image_url']; ?>" alt="Room Image">
        </div>
        <div class="room-info">
            <h3><?= $roomType; ?></h3>
            <p><?= $roomTypeData['description']; ?></p>
            <p>Price per night: ₹<?= $roomTypeData['price_per_night']; ?></p>
            <form action="" method="POST">
                <label for="checkInDate">Check-in Date:</label>
                <input type="date" id="checkInDate" name="check_in_date" value="<?= $checkInDate ?>" required>

                <label for="checkOutDate">Check-out Date:</label>
                <input type="date" id="checkOutDate" name="check_out_date" value="<?= $checkOutDate ?>" required>

                <button type="submit" name="check_availability" class="button">Check Availability</button>

                <?= $availabilityMessage; ?>

                <button type="submit" name="confirm_booking" class="button">Proceed to Payment</button>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>