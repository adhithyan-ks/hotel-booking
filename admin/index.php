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
                // Total Users
                $queryUsers = "SELECT COUNT(*) AS total_users FROM users";
                $resultUsers = $conn->query($queryUsers);
                $totalUsers = $resultUsers->fetch_assoc()['total_users'];

                // Total Bookings
                $queryBookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
                $resultBookings = $conn->query($queryBookings);
                $totalBookings = $resultBookings->fetch_assoc()['total_bookings'];

                // Total Rooms Available
                $queryRooms = "SELECT COUNT(*) AS total_rooms FROM rooms";
                $resultRooms = $conn->query($queryRooms);
                $totalRooms = $resultRooms->fetch_assoc()['total_rooms'];

                // Total Revenue Earned
                $queryRevenue = "SELECT SUM(total_price) AS total_revenue FROM bookings";
                $resultRevenue = $conn->query($queryRevenue);
                $totalRevenue = $resultRevenue->fetch_assoc()['total_revenue'] ?? 0;
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
