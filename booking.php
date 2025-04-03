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
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['room_type'])) {
    echo "Room type not specified!";
    exit;
}

$roomType = $_GET['room_type'];
$checkInDate = $_GET['check_in_date'] ?? '';
$checkOutDate = $_GET['check_out_date'] ?? '';

$availabilityMessage = "";
$dateError = "";

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

// Date validation function
function validateDates($checkInDate, $checkOutDate) {
    $today = new DateTime();
    $today->setTime(0, 0, 0); // Set to midnight for comparison
    $checkIn = new DateTime($checkInDate);
    $checkOut = new DateTime($checkOutDate);
    
    if ($checkIn < $today) {
        return "Check-in date cannot be in the past.";
    }
    
    if ($checkOut <= $checkIn) {
        return "Check-out date must be after check-in date.";
    }
    
    return ""; // No error
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['check_availability'])) {
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];
    
    // Validate dates
    $dateError = validateDates($checkInDate, $checkOutDate);
    
    if (empty($dateError)) {
        $queryTotalRooms = "SELECT COUNT(*) as total_rooms FROM rooms WHERE room_type = ?";
        $stmt = $conn->prepare($queryTotalRooms);
        $stmt->bind_param("s", $roomType);
        $stmt->execute();
        $totalRoomsResult = $stmt->get_result();
        $totalRooms = $totalRoomsResult->fetch_assoc()['total_rooms'] ?? 0;

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

        $availableRooms = $totalRooms - $bookedRooms;

        if ($availableRooms > 0) {
            $availabilityMessage = "<p class='success-message'>$availableRooms rooms available. You can proceed with booking.</p>";
        } else {
            $availabilityMessage = "<p class='error-message'>Sorry, no available rooms for the selected dates.</p>";
        }
    } else {
        $availabilityMessage = "<p class='error-message'>$dateError</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirm_booking'])) {
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];
    
    // Validate dates before proceeding with booking
    $dateError = validateDates($checkInDate, $checkOutDate);
    
    if (!empty($dateError)) {
        $availabilityMessage = "<p class='error-message'>$dateError</p>";
    } 
    else if (!empty($availabilityMessage) && strpos($availabilityMessage, 'Sorry') !== false) {
        echo "<p class='error-message'>This room type is not available for the selected dates.</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $breakfast = $_POST['breakfast'];
        $breakfast_time = $_POST['breakfast_time'] ?? NULL;
        $dinner = $_POST['dinner'];
        $dinner_time = $_POST['dinner_time'] ?? NULL;
        $additional_services = $_POST['additional_services'] ?? NULL;

        // Check availability again to be sure
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

            $date1 = new DateTime($checkInDate);
            $date2 = new DateTime($checkOutDate);
            $nights = $date1->diff($date2)->days;
            $totalPrice = $nights * $roomTypeData['price_per_night'];

            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, breakfast, breakfast_time, dinner, dinner_time, additional_services, status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("iissdsssss", $user_id, $room_id, $checkInDate, $checkOutDate, $totalPrice, $breakfast, $breakfast_time, $dinner, $dinner_time, $additional_services);

            if ($stmt->execute()) {
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
                <input type="date" id="checkInDate" name="check_in_date" value="<?= $checkInDate ?>" min="<?= date('Y-m-d'); ?>" required>

                <label for="checkOutDate">Check-out Date:</label>
                <input type="date" id="checkOutDate" name="check_out_date" value="<?= $checkOutDate ?>" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required>

                <button type="submit" name="check_availability" class="button">Check Availability</button>
                <?= $availabilityMessage; ?>
                
                <label>Breakfast:</label>
                <select name="breakfast" id="breakfast">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
                <label>Time:</label>
                <input type="time" name="breakfast_time" id="breakfast_time">

                <label>Dinner:</label>
                <select name="dinner" id="dinner">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
                <label>Time:</label>
                <input type="time" name="dinner_time" id="dinner_time">

                <label>Additional Services:</label>
                <textarea name="additional_services" placeholder="Specify additional requests"></textarea>

                <button type="submit" name="confirm_booking" class="button">Proceed to Payment</button>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
<script>
    // JavaScript for client-side validation and better UX
    document.addEventListener('DOMContentLoaded', function() {
        const checkInDate = document.getElementById('checkInDate');
        const checkOutDate = document.getElementById('checkOutDate');
        const breakfast = document.getElementById('breakfast');
        const breakfastTime = document.getElementById('breakfast_time');
        const dinner = document.getElementById('dinner');
        const dinnerTime = document.getElementById('dinner_time');
        
        // Set minimum dates for inputs
        const today = new Date();
        const tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);
        
        checkInDate.min = today.toISOString().split('T')[0];
        checkOutDate.min = tomorrow.toISOString().split('T')[0];
        
        // Update check-out minimum date when check-in changes
        checkInDate.addEventListener('change', function() {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutDate.min = nextDay.toISOString().split('T')[0];
            
            // Reset check-out if it's now invalid
            if (new Date(checkOutDate.value) <= new Date(this.value)) {
                checkOutDate.value = nextDay.toISOString().split('T')[0];
            }
        });
        
        // Optional: Make breakfast/dinner time required when yes is selected
        breakfast.addEventListener('change', function() {
            breakfastTime.required = (this.value === 'Yes');
        });
        
        dinner.addEventListener('change', function() {
            dinnerTime.required = (this.value === 'Yes');
        });
        
        // Optional: Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (new Date(checkInDate.value) < new Date().setHours(0,0,0,0)) {
                e.preventDefault();
                alert('Check-in date cannot be in the past.');
                return false;
            }
            
            if (new Date(checkOutDate.value) <= new Date(checkInDate.value)) {
                e.preventDefault();
                alert('Check-out date must be after check-in date.');
                return false;
            }
            
            if (breakfast.value === 'Yes' && !breakfastTime.value) {
                e.preventDefault();
                alert('Please select a breakfast time.');
                return false;
            }
            
            if (dinner.value === 'Yes' && !dinnerTime.value) {
                e.preventDefault();
                alert('Please select a dinner time.');
                return false;
            }
        });
    });
</script>
</body>
</html>