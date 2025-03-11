<?php 
include 'includes/config.php';
include 'includes/header.php';

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure a room_id is provided
if (!isset($_GET['room_id'])) {
    echo "Room not specified!";
    exit;
}

$room_id = intval($_GET['room_id']);

// Get the room details from the database
$query = "SELECT * FROM rooms WHERE room_id = $room_id";
$room_result = $conn->query($query);
if ($room_result->num_rows == 0) {
    echo "Invalid room!";
    exit;
}
$room = $room_result->fetch_assoc();

$availability_message = ""; // To store availability message

// Check availability
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['check_availability'])) {
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];

    // Query to check if this room is already booked in the selected dates
    $query = "SELECT * FROM bookings 
              WHERE room_id = ? 
              AND (check_in_date < ? AND check_out_date > ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $room_id, $check_out_date, $check_in_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $availability_message = "<p class='error-message'>Sorry, this room is already booked for the selected dates.</p>";
    } else {
        $availability_message = "<p class='success-message'>This room is available! You can proceed with booking.</p>";
    }
}

// Process booking submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirm_booking'])) {
    if (!empty($availability_message) && strpos($availability_message, 'Sorry') !== false) {
        echo "<p class='error-message'>This room is not available for the selected dates.</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];

        // Calculate total nights and total price
        $date1 = new DateTime($check_in_date);
        $date2 = new DateTime($check_out_date);
        $interval = $date1->diff($date2);
        $nights = $interval->days;

        if ($nights <= 0) {
            echo "<p class='error-message'>Invalid dates! Check-out date must be after check-in date.</p>";
        } else {
            $total_price = $room['price_per_night'] * $nights;

            // Insert booking into the bookings table
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iissd", $user_id, $room_id, $check_in_date, $check_out_date, $total_price);

            if ($stmt->execute()) {
                echo "<p class='success-message'>Booking successful! Total price: ₹" . $total_price . "</p>";
                echo "<a href='account.php'>Go to your account</a>";
            } else {
                echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png" />
    <link rel="stylesheet" href="css/nav.css">
    <title>Book Room</title>
    <style>
        /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Heading */
h2 {
    color: #333;
}

/* Room Details */
.room-details {
    text-align: center;
    margin-bottom: 20px;
}

.room-details h3 {
    color: #555;
    font-size: 24px;
}

.room-details img {
    width: 100%;
    max-width: 500px;
    border-radius: 10px;
    margin-top: 10px;
}

.room-details p {
    font-size: 16px;
    color: #666;
    margin: 10px 0;
}

/* Booking Form */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

label {
    font-size: 16px;
    color: #444;
    margin: 10px 0 5px;
}

input[type="date"] {
    padding: 10px;
    width: 80%;
    max-width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Button Styling */
.button {
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.button:hover {
    background-color: #0056b3;
}
/* Success & Error Messages */
.success-message {
    color: green;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
}

.error-message {
    color: red;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
}


/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    .room-details img {
        max-width: 100%;
    }
}

    </style>
</head>
<body>
    <main class="container">
        <h2>Book Your Room</h2>
        <div class="room-details">
            <h3><?= $room['room_type']; ?></h3>
            <img src="<?= $room['image_url']; ?>" alt="Room Image">
            <p><?= $room['description']; ?></p>
            <p>Price per night: ₹<?= $room['price_per_night']; ?></p>
        </div>
        <form action="" method="POST">
            <label for="checkInDate">Check-in Date:</label>
            <input type="date" id="checkInDate" name="check_in_date" required>
            
            <label for="checkOutDate">Check-out Date:</label>
            <input type="date" id="checkOutDate" name="check_out_date" required>
            
            <button type="submit" name="check_availability" class="button">Check Availability</button>
            
            <!-- Show availability message -->
            <?= $availability_message; ?>
            
            <button type="submit" name="confirm_booking" class="button">Confirm Booking</button>
        </form>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
