<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <title>Account</title>
    <style>
        img {
            width: 100px;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="container">
    <h1>Account Details</h1>

    <?php
    if (isset($_SESSION['user_id'])) {
        // Fetch user details
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE user_id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            echo "<p><strong>ID:</strong> {$user['user_id']}</p>";
            echo "<p><strong>Name:</strong> {$user['name']}</p>";
            echo "<p><strong>Email:</strong> {$user['email']}</p>";
            echo "<p><strong>Phone:</strong> {$user['phone']}</p>";
            echo "<p><strong>Created at:</strong> {$user['created_at']}</p>";
            echo "<a href='includes/logout.php' class='button'>Logout</a>";
        } else {
            echo "<p>User not found!</p>";
        }

        echo "<h2>My Bookings</h2>";

        // Fetch user bookings
        $query = "SELECT b.*, r.room_type, rt.price_per_night, rt.image_url 
                  FROM bookings b 
                  JOIN rooms r ON b.room_id = r.room_id
                  JOIN room_types rt ON r.room_type = rt.room_type
                  WHERE b.user_id = ? 
                  ORDER BY b.booked_at DESC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($booking = $result->fetch_assoc()) {
                echo "<div class='booking-card'>";
                echo "<img src='{$booking['image_url']}' alt='Room Image'>";
                echo "<p><strong>Room Type:</strong> {$booking['room_type']}</p>";
                echo "<p><strong>Check-in:</strong> {$booking['check_in_date']}</p>";
                echo "<p><strong>Check-out:</strong> {$booking['check_out_date']}</p>";
                echo "<p><strong>Total Price:</strong> ₹{$booking['total_price']}</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No bookings found.</p>";
        }
    } else {
        // If user is not logged in
        echo "<p>Welcome, Guest!</p>";
        echo "<a href='login.php' class='button'>Login</a>";
    }
    ?>

</main>

<?php include 'includes/footer.php'; ?>

<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
