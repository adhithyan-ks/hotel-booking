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
    <?php include 'inc/sidebar.php'; ?>
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
        $query = "SELECT * FROM rooms";
        $result = $conn->query($query);
        echo "<table><tr><th>Room ID</th><th>Room Type</th><th>Description</th><th>Price Per Night</th><th>Availability Status</th><th>Image URL</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['room_id'] . "</td>";
            echo "<td>" . $row['room_type'] . "</td>";
            echo "<td> " . $row['description'] . "</td>";
            echo "<td>" . $row['price_per_night'] . "</td>";
            echo "<td>" . $row['avail_status'] . "</td>";
            echo "<td>" . $row['image_url'] . "</td></tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>