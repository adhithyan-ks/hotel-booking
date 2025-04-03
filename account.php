<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/account.css"> <!-- New CSS file -->
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <title>My Account</title>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="account-container">
    <div class="account-card">
        <h2>My Account</h2>

        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM users WHERE user_id = ?";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                echo "<div class='user-info'>";
                echo "<p><strong>ID:</strong> {$user['user_id']}</p>";
                echo "<p><strong>Name:</strong> {$user['name']}</p>";
                echo "<p><strong>Email:</strong> {$user['email']}</p>";
                echo "<p><strong>Phone:</strong> {$user['phone']}</p>";
                echo "<p><strong>Joined:</strong> {$user['created_at']}</p>";
                echo "<a href='includes/logout.php' class='logout-btn'>Logout</a>";
                echo "</div>";
            } else {
                echo "<p>User not found!</p>";
            }

            echo "<h2>My Bookings</h2>";

            // Fetch user bookings
            $query = "SELECT b.*, r.room_type, rt.price_per_night, rt.image_url 
            FROM bookings b 
            JOIN rooms r ON b.room_id = r.room_id
            JOIN room_types rt ON r.room_type = rt.room_type
            WHERE b.user_id = ? AND (b.status = 'confirmed' OR b.status = 'canceled')
            ORDER BY b.booked_at DESC";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table class='bookings-table'>
                        <tr>
                            <th>Booking ID</th>
                            <th>Room Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>";

                while ($row = $result->fetch_assoc()) {
                    $booking_id = $row['booking_id'];
                    $room_type = $row['room_type'];
                    $check_in = $row['check_in_date'];
                    $check_out = $row['check_out_date'];
                    $status = ucfirst($row['status']); // Capitalize status

                    echo "<tr>
                            <td>$booking_id</td>
                            <td>$room_type</td>
                            <td>$check_in</td>
                            <td>$check_out</td>
                            <td>$status</td>
                            <td>";

                    // Show cancel button only if booking is confirmed and in the future
                    if ($status == 'Confirmed' && strtotime($check_in) > time()) {
                        echo "<form action='cancel_booking.php' method='POST' onsubmit='return confirm(\"Are you sure you want to cancel this booking?\")'>
                                <input type='hidden' name='booking_id' value='$booking_id'>
                                <button type='submit' class='cancel-btn'>Cancel</button>
                              </form>";
                    } else {
                        echo "-";
                    }

                    echo "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-bookings'>You have no bookings.</p>";
            }
        } else {
            echo "<p class='guest-message'>Welcome, Guest!</p>";
            echo "<a href='login.php' class='login-btn'>Login</a>";
        }
        ?>
    </div>
</main>


<?php include 'includes/footer.php'; ?>

<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
