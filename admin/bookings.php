<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Hotel Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    <div class="content">
        <h1>Welcome to the Admin Panel</h1>
        <p>Select an option from the sidebar to manage the hotel.</p>
        
        <?php
        $query = "SELECT b.*, r.room_type FROM bookings b 
                  JOIN rooms r ON b.room_id = r.room_id";
        $result = $conn->query($query);

        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Room ID</th>
                    <th>Room Type</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Total Price</th>
                    <th>Booked At</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['booking_id'] . "</td>
                    <td>" . $row['user_id'] . "</td>
                    <td>" . $row['room_id'] . "</td>
                    <td>" . $row['room_type'] . "</td>
                    <td>" . $row['check_in_date'] . "</td>
                    <td>" . $row['check_out_date'] . "</td>
                    <td>₹" . $row['total_price'] . "</td>
                    <td>" . $row['booked_at'] . "</td>
                </tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
