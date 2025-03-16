<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <title>Hotel Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="sidebar">
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
    <div class="content">
        <h1>Welcome to the Admin Panel</h1>
        <p>Select an option from the sidebar to manage the hotel.</p>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hotel_db";
        //Ceate connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        //Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Select all records from the users table
        $query = "SELECT * FROM bookings";
        $result = $conn->query($query);
        echo "<table><tr><th>Booking ID</th><th>User ID</th><th>Room ID</th><th>Check in date</th><th>Check out date</th><th>Total Price</th><th>Booked at</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['booking_id'] . "</td>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['room_id'] . "</td>";
            echo "<td>" . $row['check_in_date'] . "</td>";
            echo "<td> " . $row['check_out_date'] . "</td>";
            echo "<td>" . $row['total_price'] . "</td>";
            echo "<td>" . $row['booked_at'] . "</td>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
