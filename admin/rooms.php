<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Rooms Management - Admin Panel</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    <div class="content">
        <h1>Room Types</h1>
        <?php
        // Fetch room types
        $query = "SELECT * FROM room_types ORDER BY price_per_night ASC";
        $result = $conn->query($query);
        echo "<table>
                <tr>
                    <th>Room Type</th>
                    <th>Description</th>
                    <th>Price per Night</th>
                    <th>Image</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['room_type'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>₹" . $row['price_per_night'] . "</td>
                    <td><img src='../" . $row['image_url'] . "'></td>
                  </tr>";
        }
        echo "</table>";
        ?>

        <h1>Rooms</h1>
        <?php
        // Fetch individual rooms
        $query = "SELECT r.room_id, r.room_type, rt.price_per_night, rt.image_url 
                  FROM rooms r
                  JOIN room_types rt ON r.room_type = rt.room_type
                  ORDER BY room_id ASC";
        $result = $conn->query($query);
        echo "<table>
                <tr>
                    <th>Room ID</th>
                    <th>Room Type</th>
                    <th>Price per Night</th>
                    <th>Image</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['room_id'] . "</td>
                    <td>" . $row['room_type'] . "</td>
                    <td>₹" . $row['price_per_night'] . "</td>
                    <td><img src='../" . $row['image_url'] . "'></td>
                  </tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
