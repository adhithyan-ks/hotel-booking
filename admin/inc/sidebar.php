<?php
session_start();
if (!isset($_SESSION['adminEmail'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="sidebar">
    <div>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="admins.php">Admins</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="admin-info">
        Logged in as: <strong><?php echo isset($_SESSION['adminEmail']) ? $_SESSION['admin_name'] : 'Admin'; ?></strong>
    </div>
</div>
