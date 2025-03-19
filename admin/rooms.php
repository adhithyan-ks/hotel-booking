<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Hotel Admin Panel</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    <div class="content">
        <h1>Room types</h1>
        <?php
        $query = "SELECT * FROM room_types ORDER BY price_per_night ASC";
        $result = $conn->query($query);
        echo "<table><tr><th>Room type</th><th>Description</th><th>Price per night</th><th>Image URL</th><th>Image</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['room_type'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['price_per_night'] . "</td>";
            echo "<td>" . $row['image_url'] . "</td>";
            echo "<td><img src='../" . $row['image_url'] . "'></td></tr>";
        }
        echo "</table>";
        
        ?>
    </div>
</body>
</html>