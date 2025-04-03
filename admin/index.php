<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>Hotel Admin Panel</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>

    <div class="admin-container">
        <h1>Admin Dashboard</h1>

        <div class="dashboard-stats">
            <?php
                // Fetch Total Users
                $queryUsers = "SELECT COUNT(*) AS total_users FROM users";
                $totalUsers = $conn->query($queryUsers)->fetch_assoc()['total_users'];

                // Fetch Total Bookings
                $queryBookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
                $totalBookings = $conn->query($queryBookings)->fetch_assoc()['total_bookings'];

                // Fetch Total Available Rooms
                $queryRooms = "SELECT COUNT(*) AS total_rooms FROM rooms";
                $totalRooms = $conn->query($queryRooms)->fetch_assoc()['total_rooms'];

                // Fetch Total Revenue Earned
                $queryRevenue = "SELECT SUM(total_price) AS total_revenue FROM bookings WHERE status = 'confirmed'";
                $totalRevenue = $conn->query($queryRevenue)->fetch_assoc()['total_revenue'] ?? 0;

                // Fetch Pending Bookings
                $queryPending = "SELECT COUNT(*) AS pending_bookings FROM bookings WHERE status = 'pending'";
                $pendingBookings = $conn->query($queryPending)->fetch_assoc()['pending_bookings'];

                // Fetch Confirmed Bookings
                $queryConfirmed = "SELECT COUNT(*) AS confirmed_bookings FROM bookings WHERE status = 'confirmed'";
                $confirmedBookings = $conn->query($queryConfirmed)->fetch_assoc()['confirmed_bookings'];
            ?>

            <div class="stat-box">
                <h2>Total Users</h2>
                <p><?= $totalUsers; ?></p>
            </div>

            <div class="stat-box">
                <h2>Total Bookings</h2>
                <p><?= $totalBookings; ?></p>
            </div>

            <div class="stat-box">
                <h2>Pending Bookings</h2>
                <p><?= $pendingBookings; ?></p>
            </div>

            <div class="stat-box">
                <h2>Confirmed Bookings</h2>
                <p><?= $confirmedBookings; ?></p>
            </div>

            <div class="stat-box">
                <h2>Total Rooms</h2>
                <p><?= $totalRooms; ?></p>
            </div>

            <div class="stat-box">
                <h2>Total Revenue</h2>
                <p>₹<?= number_format($totalRevenue, 2); ?></p>
            </div>
        </div>
    </div>

</body>
</html>
