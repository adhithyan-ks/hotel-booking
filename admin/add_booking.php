<?php
include '../includes/config.php';

// Fetch available rooms (rooms not booked)
$roomQuery = "SELECT r.room_id, r.room_type, rt.price_per_night 
              FROM rooms r
              JOIN room_types rt ON r.room_type = rt.room_type
              WHERE r.room_id NOT IN (
                  SELECT room_id FROM bookings WHERE status = 'confirmed'
              )";

$roomResult = $conn->query($roomQuery);
if (!$roomResult) {
    die("Error fetching rooms: " . $conn->error);
}

// Fetch existing users
$userQuery = "SELECT user_id, name FROM users";
$userResult = $conn->query($userQuery);
if (!$userResult) {
    die("Error fetching users: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $total_price = $_POST['total_price'];
    $admin_id = 1; // Replace with actual admin ID from session
    $booking_source = 'offline';

    if (empty($user_id)) {
        die("<script>alert('Please select a user or add a new user before booking.');</script>");
    }

    if ($user_id == "new") {
        $new_username = $_POST['new_username'] ?? '';
        $new_phone = $_POST['new_phone'] ?? '';

        if (empty($new_username) || empty($new_phone)) {
            die("<script>alert('Please enter new user details.');</script>");
        }

        $insertUser = "INSERT INTO users (name, phone) VALUES ('$new_username', '$new_phone')";
        if ($conn->query($insertUser)) {
            $user_id = $conn->insert_id; // Get the new user_id
        } else {
            die("Error creating user: " . $conn->error);
        }
    }


    // Get the room type of the selected room
    $roomTypeQuery = "SELECT room_type FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($roomTypeQuery);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $roomTypeResult = $stmt->get_result();
    
    if ($roomTypeResult->num_rows == 0) {
        die("Invalid room selection!");
    }
    
    $roomTypeData = $roomTypeResult->fetch_assoc();
    $roomType = $roomTypeData['room_type'];

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
              AND b.status = 'confirmed'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $roomType, $check_out_date, $check_in_date);
    $stmt->execute();
    $bookedRoomsResult = $stmt->get_result();
    $bookedRooms = $bookedRoomsResult->fetch_assoc()['booked_rooms'] ?? 0;

    // Calculate available rooms
    $availableRooms = $totalRooms - $bookedRooms;

    if ($availableRooms <= 0) {
        die("<script>alert('No available rooms of this type for the selected dates. Please choose different dates or a different room type.');</script>");
    }

    // Insert booking
    $insertBooking = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status, booked_at, booking_source, admin_id) 
                      VALUES (?, ?, ?, ?, ?, 'confirmed', NOW(), ?, ?)";
    $stmt = $conn->prepare($insertBooking);
    $stmt->bind_param("iissdsi", $user_id, $room_id, $check_in_date, $check_out_date, $total_price, $booking_source, $admin_id);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!');</script>";
    } else {
        die("Error creating booking: " . $stmt->error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/add_booking.css">
    <title>Offline Booking</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>

    <div class="content">
        <h1>Add Booking</h1><br>

        <?php if (isset($errorMessage)) { ?>
            <div class="alert"><?= $errorMessage; ?></div>
        <?php } ?>

        <form method="POST">
            <!-- Select User -->
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id">
                <option value="" disabled selected>-- Select User --</option>
                <option value="new">New User</option>
                <?php while ($user = $userResult->fetch_assoc()) { ?>
                    <option value="<?= $user['user_id'] ?>"><?= $user['name'] ?></option>
                <?php } ?>
            </select>

            <!-- New User Fields -->
            <div id="newUserField">
                <label for="new_username">New Username:</label>
                <input type="text" name="new_username">

                <label for="new_phone">Phone Number:</label>
                <input type="text" name="new_phone">
            </div>

            <!-- Select Room -->
            <label for="room_id">Select Room:</label>
            <select name="room_id" id="room_id">
                <option value="" disabled selected>-- Select Room --</option>
                <?php while ($room = $roomResult->fetch_assoc()) { ?>
                    <option value="<?= $room['room_id'] ?>" data-price="<?= $room['price_per_night'] ?>">
                        <?= $room['room_type'] ?> - ₹<?= $room['price_per_night'] ?>/night
                    </option>
                <?php } ?>
            </select>

            <!-- Booking Dates -->
            <label for="check_in_date">Check-in Date:</label>
            <input type="date" name="check_in_date" id="check_in_date" required>

            <label for="check_out_date">Check-out Date:</label>
            <input type="date" name="check_out_date" id="check_out_date" required>

            <!-- Total Price -->
            <label for="total_price">Total Price:</label>
            <input type="number" name="total_price" id="total_price" readonly>
            <button type="button" id="calculate_price">Calculate Price</button>
            <button type="button" id="check_availability">Check Availability</button>
<p id="availability_message"></p>
            <button type="submit">Book Room</button>
        </form>
    </div>

    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            let newUserField = document.getElementById('newUserField');
            newUserField.style.display = this.value === 'new' ? 'block' : 'none';
        });

        document.getElementById('calculate_price').addEventListener('click', function() {
            let roomDropdown = document.getElementById('room_id');
            let checkInDate = document.getElementById('check_in_date').value;
            let checkOutDate = document.getElementById('check_out_date').value;
            let totalPriceField = document.getElementById('total_price');

            if (!roomDropdown.value || !checkInDate || !checkOutDate) {
                alert('Please select a room and enter check-in/check-out dates.');
                return;
            }

            let pricePerNight = parseFloat(roomDropdown.options[roomDropdown.selectedIndex].getAttribute('data-price'));
            let checkIn = new Date(checkInDate);
            let checkOut = new Date(checkOutDate);
            let timeDiff = checkOut.getTime() - checkIn.getTime();
            let nightCount = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (nightCount <= 0) {
                alert('Check-out date must be after check-in date.');
                return;
            }

            let totalPrice = nightCount * pricePerNight;
            totalPriceField.value = totalPrice;
        });
        // Check availability
        document.getElementById('check_availability').addEventListener('click', function () {
            let roomDropdown = document.getElementById('room_id');
            let checkInDate = document.getElementById('check_in_date').value;
            let checkOutDate = document.getElementById('check_out_date').value;
            let messageField = document.getElementById('availability_message');

            if (!roomDropdown.value || !checkInDate || !checkOutDate) {
                alert('Please select a room and enter check-in/check-out dates.');
                return;
            }

            let roomType = roomDropdown.options[roomDropdown.selectedIndex].text.split(' - ')[0];

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_availability.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    messageField.innerHTML = xhr.responseText;
                }
            };
            xhr.send(`room_type=${roomType}&check_in_date=${checkInDate}&check_out_date=${checkOutDate}`);
        });

    </script>
    <script>
    document.querySelector("form").addEventListener("submit", function (event) {
        let userDropdown = document.getElementById('user_id');
        let selectedUser = userDropdown.value;
        let newUserField = document.getElementById('newUserField');
        let newUsername = document.querySelector("input[name='new_username']").value;
        let newPhone = document.querySelector("input[name='new_phone']").value;

        if (!selectedUser) {
            alert("Please select an existing user or add a new user.");
            event.preventDefault();
        }

        if (selectedUser === "new") {
            if (!newUsername || !newPhone) {
                alert("Please enter new user details.");
                event.preventDefault();
            }
        }
    });
</script>

</body>
</html>

