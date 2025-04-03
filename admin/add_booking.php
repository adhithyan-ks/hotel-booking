<?php
include '../includes/config.php';

// Start session if not already started (needed for potential admin ID later)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch available rooms (rooms not booked for *any* confirmed dates - simplified for dropdown)
// Note: Availability check is more complex and handled via JS/AJAX + PHP check_availability.php
$roomQuery = "SELECT r.room_id, r.room_type, rt.price_per_night
              FROM rooms r
              JOIN room_types rt ON r.room_type = rt.room_type";
              // Simplified initial query - actual availability check done later
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

    // --- Service fields added from booking.php ---
    $breakfast = $_POST['breakfast'] ?? 'No'; // Default to 'No'
    $breakfast_time = !empty($_POST['breakfast_time']) ? $_POST['breakfast_time'] : NULL; // NULL if empty
    $dinner = $_POST['dinner'] ?? 'No'; // Default to 'No'
    $dinner_time = !empty($_POST['dinner_time']) ? $_POST['dinner_time'] : NULL; // NULL if empty
    $additional_services = $_POST['additional_services'] ?? NULL; // NULL if empty
    // --- End of added service fields ---

    // Assume admin ID 1 for now, replace with actual logged-in admin ID from session if available
    $admin_id = $_SESSION['admin_id'] ?? 1;
    $booking_source = 'offline';

    if (empty($user_id)) {
        // Using JS alert for immediate feedback might be better UX
        die("<script>alert('Please select a user or choose New User.'); window.history.back();</script>");
    }

    if ($user_id == "new") {
        $new_username = $_POST['new_username'] ?? '';
        $new_phone = $_POST['new_phone'] ?? '';

        if (empty($new_username) || empty($new_phone)) {
             die("<script>alert('Please enter new user details (name and phone).'); window.history.back();</script>");
        }

        // Optional: Check if phone number already exists for another user
        $checkPhoneSql = "SELECT user_id FROM users WHERE phone = ?";
        $stmtCheck = $conn->prepare($checkPhoneSql);
        $stmtCheck->bind_param("s", $new_phone);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck->num_rows > 0) {
            die("<script>alert('Phone number already exists for another user.'); window.history.back();</script>");
        }
        $stmtCheck->close();


        $insertUser = "INSERT INTO users (name, phone) VALUES (?, ?)";
        $stmtUser = $conn->prepare($insertUser);
        $stmtUser->bind_param("ss", $new_username, $new_phone);
        if ($stmtUser->execute()) {
            $user_id = $conn->insert_id; // Get the new user_id
        } else {
            die("Error creating user: " . $stmtUser->error);
        }
         $stmtUser->close();
    }


    // Get the room type of the selected room to perform availability check
    $roomTypeQuery = "SELECT room_type FROM rooms WHERE room_id = ?";
    $stmtRT = $conn->prepare($roomTypeQuery);
    $stmtRT->bind_param("i", $room_id);
    $stmtRT->execute();
    $roomTypeResult = $stmtRT->get_result();

    if ($roomTypeResult->num_rows == 0) {
        die("<script>alert('Invalid room selection!'); window.history.back();</script>");
    }

    $roomTypeData = $roomTypeResult->fetch_assoc();
    $roomType = $roomTypeData['room_type'];
    $stmtRT->close();

    // Server-side check for availability (redundant if JS check is trusted, but good practice)
    $queryTotalRooms = "SELECT COUNT(*) as total_rooms FROM rooms WHERE room_type = ?";
    $stmtTotal = $conn->prepare($queryTotalRooms);
    $stmtTotal->bind_param("s", $roomType);
    $stmtTotal->execute();
    $totalRoomsResult = $stmtTotal->get_result();
    $totalRooms = $totalRoomsResult->fetch_assoc()['total_rooms'] ?? 0;
    $stmtTotal->close();

    $queryBooked = "SELECT COUNT(DISTINCT b.room_id) AS booked_rooms FROM bookings b
                    JOIN rooms r ON b.room_id = r.room_id
                    WHERE r.room_type = ?
                    AND b.status = 'confirmed'
                    AND (b.check_in_date < ? AND b.check_out_date > ?)";

    $stmtBooked = $conn->prepare($queryBooked);
    $stmtBooked->bind_param("sss", $roomType, $check_out_date, $check_in_date);
    $stmtBooked->execute();
    $bookedRoomsResult = $stmtBooked->get_result();
    $bookedRooms = $bookedRoomsResult->fetch_assoc()['booked_rooms'] ?? 0;
    $stmtBooked->close();


    $availableRooms = $totalRooms - $bookedRooms;

    if ($availableRooms <= 0) {
        die("<script>alert('No available rooms of type [$roomType] for the selected dates. Please check availability again or choose different dates/room.'); window.history.back();</script>");
    }

    // Assign a specific available room_id of the requested type for this booking
    // This finds *one* specific room of the correct type that is NOT booked for the requested dates.
    $findSpecificRoomSQL = "SELECT r.room_id FROM rooms r
                           WHERE r.room_type = ?
                           AND r.room_id NOT IN (
                               SELECT b.room_id FROM bookings b
                               WHERE b.status = 'confirmed'
                               AND (b.check_in_date < ? AND b.check_out_date > ?)
                           )
                           LIMIT 1";
    $stmtFindRoom = $conn->prepare($findSpecificRoomSQL);
    $stmtFindRoom->bind_param("sss", $roomType, $check_out_date, $check_in_date);
    $stmtFindRoom->execute();
    $specificRoomResult = $stmtFindRoom->get_result();

    if ($specificRoomResult->num_rows > 0) {
        $specificRoomData = $specificRoomResult->fetch_assoc();
        $final_room_id = $specificRoomData['room_id']; // Use this specific room_id
    } else {
        // This should technically not happen if the previous check passed, but as a safeguard:
        die("<script>alert('Could not assign a specific available room. Please try again.'); window.history.back();</script>");
    }
    $stmtFindRoom->close();


    // Insert booking using the specifically assigned room_id ($final_room_id)
    // Updated INSERT statement with service fields
    $insertBooking = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status, booked_at, booking_source, admin_id, breakfast, breakfast_time, dinner, dinner_time, additional_services)
                      VALUES (?, ?, ?, ?, ?, 'confirmed', NOW(), ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertBooking);
    // Updated bind_param with types and variables for service fields
    $stmtInsert->bind_param("iissdsssssss", $user_id, $final_room_id, $check_in_date, $check_out_date, $total_price, $booking_source, $admin_id, $breakfast, $breakfast_time, $dinner, $dinner_time, $additional_services);

    if ($stmtInsert->execute()) {
        // Success - redirect or show message
         echo "<script>alert('Booking successful!'); window.location.href = 'bookings.php';</script>"; // Redirect to manage page
         exit; // Stop script execution after redirect
    } else {
        // Provide specific error for debugging if possible
        error_log("Error creating booking: " . $stmtInsert->error); // Log error server-side
        die("<script>alert('Error creating booking. Please check the details and try again. Error: " . $stmtInsert->error . "'); window.history.back();</script>");
    }
    $stmtInsert->close();
}

$conn->close(); // Close connection at the end of the script if not needed further
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/add_booking.css">
    <title>Add Offline Booking</title>
    <style>
        /* Basic styling for better layout */
        label { display: block; margin-top: 10px; }
        input, select, textarea { width: 95%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 15px; cursor: pointer; }
        #newUserField { display: none; border: 1px solid #ccc; padding: 10px; margin-top: 10px; }
        .service-time { display: inline-block; width: auto; margin-left: 10px; } /* Style for time inputs */
        #availability_message { margin-top: 10px; font-weight: bold; }
        .success-message { color: green; }
        .error-message { color: red; }
    </style>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>

    <div class="content">
        <h1>Add Offline Booking</h1><br>

        <form id="addBookingForm" method="POST" action="add_booking.php">
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" required>
                <option value="" disabled selected>-- Select User --</option>
                <option value="new">-- Add New User --</option>
                <?php while ($user = $userResult->fetch_assoc()) { ?>
                    <option value="<?= htmlspecialchars($user['user_id']) ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php } ?>
                 <?php $userResult->data_seek(0); // Reset pointer if needed elsewhere ?>
            </select>

            <div id="newUserField">
                <label for="new_username">New User Full Name:</label>
                <input type="text" name="new_username" id="new_username">

                <label for="new_phone">Phone Number:</label>
                <input type="tel" name="new_phone" id="new_phone" pattern="[0-9]{10,15}" title="Enter a valid phone number (10-15 digits)">
            </div>

            <label for="room_id">Select Room Type:</label>
            <select name="room_id" id="room_id" required>
                <option value="" disabled selected>-- Select Room Type --</option>
                <?php
                // Group rooms by type for dropdown clarity
                $roomTypes = [];
                while ($room = $roomResult->fetch_assoc()) {
                    if (!isset($roomTypes[$room['room_type']])) {
                        $roomTypes[$room['room_type']] = [
                            'price' => $room['price_per_night'],
                            // Store one room_id as representative for data- attributes,
                            // actual available room ID determined server-side on submit
                             'id' => $room['room_id']
                         ];
                    }
                }
                foreach ($roomTypes as $type => $details) {
                    echo "<option value=\"" . htmlspecialchars($details['id']) . "\" data-price=\"" . htmlspecialchars($details['price']) . "\" data-type=\"" . htmlspecialchars($type) . "\">"
                       . htmlspecialchars($type) . " - ₹" . htmlspecialchars($details['price']) . "/night"
                       . "</option>";
                }
                 $roomResult->data_seek(0); // Reset pointer
                ?>
            </select>
             <input type="hidden" name="selected_room_type" id="selected_room_type">


            <label for="check_in_date">Check-in Date:</label>
            <input type="date" name="check_in_date" id="check_in_date" required>

            <label for="check_out_date">Check-out Date:</label>
            <input type="date" name="check_out_date" id="check_out_date" required>

            <button type="button" id="check_availability">Check Availability</button>
             <p id="availability_message"></p> <hr> <h4>Optional Services:</h4>

            <label for="breakfast">Breakfast:</label>
            <select name="breakfast" id="breakfast">
                <option value="No" selected>No</option>
                <option value="Yes">Yes</option>
            </select>
            <label for="breakfast_time" class="service-time">Time:</label>
            <input type="time" name="breakfast_time" id="breakfast_time" class="service-time">

            <label for="dinner">Dinner:</label>
            <select name="dinner" id="dinner">
                <option value="No" selected>No</option>
                <option value="Yes">Yes</option>
            </select>
             <label for="dinner_time" class="service-time">Time:</label>
            <input type="time" name="dinner_time" id="dinner_time" class="service-time">

            <label for="additional_services">Additional Services/Requests:</label>
            <textarea name="additional_services" id="additional_services" rows="3" placeholder="e.g., Extra pillows, specific view request (not guaranteed)"></textarea>
             <hr> <label for="total_price">Total Room Price (₹):</label>
            <input type="number" name="total_price" id="total_price" readonly required step="0.01">
            <button type="button" id="calculate_price">Calculate Room Price</button>

            <button type="submit">Add Booking</button>
        </form>
    </div>

    <script>
        // Toggle New User Fields
        document.getElementById('user_id').addEventListener('change', function() {
            const newUserField = document.getElementById('newUserField');
            const newUsernameInput = document.getElementById('new_username');
            const newPhoneInput = document.getElementById('new_phone');
            if (this.value === 'new') {
                newUserField.style.display = 'block';
                newUsernameInput.required = true; // Make required when visible
                newPhoneInput.required = true;
            } else {
                newUserField.style.display = 'none';
                 newUsernameInput.required = false; // Make not required when hidden
                 newPhoneInput.required = false;
                 newUsernameInput.value = ''; // Clear fields when hiding
                 newPhoneInput.value = '';
            }
        });

        // Calculate Price Logic
        function calculatePrice() {
            const roomDropdown = document.getElementById('room_id');
            const checkInDateStr = document.getElementById('check_in_date').value;
            const checkOutDateStr = document.getElementById('check_out_date').value;
            const totalPriceField = document.getElementById('total_price');

            // Clear price if inputs are missing
            totalPriceField.value = '';

            if (!roomDropdown.value || !checkInDateStr || !checkOutDateStr) {
                // Don't alert here, just clear price. Alert on explicit button click.
                return;
            }

            const selectedOption = roomDropdown.options[roomDropdown.selectedIndex];
            const pricePerNight = parseFloat(selectedOption.getAttribute('data-price'));
            const checkIn = new Date(checkInDateStr);
            const checkOut = new Date(checkOutDateStr);

             // Basic date validation
             const today = new Date();
             today.setHours(0, 0, 0, 0); // Set to midnight for comparison

            if (isNaN(checkIn.getTime()) || isNaN(checkOut.getTime())) {
                 // Invalid date format
                 return;
             }

            if (checkIn < today) {
                 alert('Check-in date cannot be in the past.');
                 document.getElementById('check_in_date').value = ''; // Clear invalid date
                 return;
             }

            if (checkOut <= checkIn) {
                // Don't calculate if check-out is not after check-in
                return;
            }


            const timeDiff = checkOut.getTime() - checkIn.getTime();
            const nightCount = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (nightCount > 0 && !isNaN(pricePerNight)) {
                const totalPrice = nightCount * pricePerNight;
                totalPriceField.value = totalPrice.toFixed(2); // Format to 2 decimal places
            }
        }

        // Event listeners for price calculation
        document.getElementById('room_id').addEventListener('change', calculatePrice);
        document.getElementById('check_in_date').addEventListener('change', calculatePrice);
        document.getElementById('check_out_date').addEventListener('change', calculatePrice);
        document.getElementById('calculate_price').addEventListener('click', function() {
             // Explicitly calculate and potentially alert if inputs missing
             const roomDropdown = document.getElementById('room_id');
             const checkInDateStr = document.getElementById('check_in_date').value;
             const checkOutDateStr = document.getElementById('check_out_date').value;
             if (!roomDropdown.value || !checkInDateStr || !checkOutDateStr) {
                 alert('Please select a room type and enter valid check-in/check-out dates to calculate the price.');
             } else if (new Date(checkOutDateStr) <= new Date(checkInDateStr)) {
                 alert('Check-out date must be after check-in date.');
             } else {
                 calculatePrice(); // Recalculate if dates are valid now
             }
         });


        // Set hidden room type field when room selection changes
         document.getElementById('room_id').addEventListener('change', function() {
             const selectedOption = this.options[this.selectedIndex];
             const roomType = selectedOption.getAttribute('data-type');
             document.getElementById('selected_room_type').value = roomType || '';
             // Also clear availability message when room type changes
             document.getElementById('availability_message').innerHTML = '';
             document.getElementById('availability_message').className = ''; // Clear classes
         });

        // Check Availability via AJAX
        document.getElementById('check_availability').addEventListener('click', function () {
            const roomDropdown = document.getElementById('room_id');
            const checkInDate = document.getElementById('check_in_date').value;
            const checkOutDate = document.getElementById('check_out_date').value;
            const messageField = document.getElementById('availability_message');

             // Clear previous message
             messageField.innerHTML = '';
             messageField.className = '';

            if (!roomDropdown.value || !checkInDate || !checkOutDate) {
                alert('Please select a room type and enter check-in/check-out dates.');
                return;
            }

            const checkIn = new Date(checkInDate);
             const checkOut = new Date(checkOutDate);
             const today = new Date();
             today.setHours(0, 0, 0, 0);

             if (checkIn < today) {
                 messageField.textContent = 'Check-in date cannot be in the past.';
                 messageField.className = 'error-message';
                 return;
             }
             if (checkOut <= checkIn) {
                 messageField.textContent = 'Check-out date must be after check-in date.';
                 messageField.className = 'error-message';
                 return;
             }


            // Get room type from the data-type attribute
            const selectedOption = roomDropdown.options[roomDropdown.selectedIndex];
            const roomType = selectedOption.getAttribute('data-type');

            if (!roomType) {
                 alert('Could not determine room type. Please re-select.');
                 return;
            }


            // AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_availability.php', true); // Ensure this path is correct
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) { // Request finished
                     if (xhr.status === 200) { // Request successful
                        // Assume check_availability.php returns plain text like "X rooms available" or "Not available"
                        const responseText = xhr.responseText.trim();
                         messageField.innerHTML = responseText;
                         // Add styling based on response (simple check)
                         if (responseText.toLowerCase().includes("available")) {
                             messageField.className = 'success-message';
                         } else {
                             messageField.className = 'error-message';
                         }
                    } else {
                        // Handle server errors (e.g., 404, 500)
                        messageField.textContent = 'Error checking availability. Status: ' + xhr.status;
                        messageField.className = 'error-message';
                        console.error("AJAX Error:", xhr.status, xhr.statusText);
                    }
                }
            };
             xhr.onerror = function() {
                 // Handle network errors
                 messageField.textContent = 'Network error checking availability.';
                 messageField.className = 'error-message';
                 console.error("AJAX Network Error");
             };

            // Send data
             xhr.send(`room_type=${encodeURIComponent(roomType)}&check_in_date=${checkInDate}&check_out_date=${checkOutDate}`);
        });

        // Form Submission Validation (Client-side)
        document.getElementById('addBookingForm').addEventListener("submit", function (event) {
            const userDropdown = document.getElementById('user_id');
            const selectedUser = userDropdown.value;
            const newUsername = document.getElementById('new_username').value.trim();
            const newPhone = document.getElementById('new_phone').value.trim();
            const roomDropdown = document.getElementById('room_id');
            const checkInDateStr = document.getElementById('check_in_date').value;
            const checkOutDateStr = document.getElementById('check_out_date').value;
            const totalPrice = document.getElementById('total_price').value;
            const availabilityMessage = document.getElementById('availability_message').textContent;

            // 1. User Selection/Creation
            if (!selectedUser) {
                alert("Please select an existing user or choose 'Add New User'.");
                event.preventDefault(); // Stop submission
                return;
            }
            if (selectedUser === "new") {
                if (!newUsername || !newPhone) {
                    alert("Please enter the new user's name and phone number.");
                    event.preventDefault();
                    return;
                }
                // Optional: Basic phone validation regex (adjust as needed)
                if (!/^[0-9]{10,15}$/.test(newPhone)) {
                    alert("Please enter a valid phone number (10-15 digits).");
                     event.preventDefault();
                     return;
                 }
            }

             // 2. Room and Dates
             if (!roomDropdown.value) {
                 alert("Please select a room type.");
                 event.preventDefault();
                 return;
             }
             if (!checkInDateStr || !checkOutDateStr) {
                 alert("Please select check-in and check-out dates.");
                 event.preventDefault();
                 return;
             }
             const checkIn = new Date(checkInDateStr);
             const checkOut = new Date(checkOutDateStr);
             const today = new Date();
             today.setHours(0, 0, 0, 0);

             if (checkIn < today) {
                 alert('Check-in date cannot be in the past.');
                 event.preventDefault();
                 return;
             }
             if (checkOut <= checkIn) {
                 alert("Check-out date must be after check-in date.");
                 event.preventDefault();
                 return;
             }

            // 3. Price Calculation
            if (!totalPrice || parseFloat(totalPrice) <= 0) {
                 alert("Please calculate the room price before submitting.");
                 event.preventDefault();
                 return;
             }

            // 4. Availability Check (Optional but recommended)
             // Check if availability was checked and if it was negative
             if (availabilityMessage && availabilityMessage.toLowerCase().includes("not available")) {
                 if (!confirm("The availability check indicated no rooms. Do you want to proceed anyway? (This might fail)")) {
                     event.preventDefault();
                     return;
                 }
             }
              if (!availabilityMessage) {
                 if (!confirm("You haven't checked availability for these dates/room type. Proceed anyway?")) {
                     event.preventDefault();
                     return;
                 }
             }

            // 5. Service Time Validation (Optional: ensure time is set if Yes is selected)
             const breakfastSelected = document.getElementById('breakfast').value === 'Yes';
             const breakfastTime = document.getElementById('breakfast_time').value;
             const dinnerSelected = document.getElementById('dinner').value === 'Yes';
             const dinnerTime = document.getElementById('dinner_time').value;

             if (breakfastSelected && !breakfastTime) {
                 alert("Please select a breakfast time.");
                 event.preventDefault();
                 return;
             }
              if (dinnerSelected && !dinnerTime) {
                 alert("Please select a dinner time.");
                 event.preventDefault();
                 return;
             }

            // If all checks pass, the form will submit normally.
        });

    </script>

</body>
</html>