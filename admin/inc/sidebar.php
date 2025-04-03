<?php
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="sidebar">
    <div>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="check_room.php">Check Room Availability</a></li>
            <li><a href="add_booking.php">Add Bookings</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="payments.php">Payments</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="messages.php">Messages</a></li>
            <li><a href="admins.php">Admins</a></li>
            
            <li><a href="inc/logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="admin-info">
        Logged in as: <strong><?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_name'] : 'Admin'; ?></strong>
    </div>
</div>
