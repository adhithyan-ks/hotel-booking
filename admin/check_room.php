<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Check Room Availability</title>
    <style>/* Availability Check Page Styles */
.content {
    padding: 20px;
}


form {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

form label {
    font-weight: bold;
}

form input[type="date"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}



form button:hover {
    background:rgb(97, 97, 97);
}

.no-data {
    color: #d9534f;
    font-size: 16px;
    margin-top: 20px;
}
</style>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    
    <div class="content">
        <h1>Check Room Availability</h1>

        <!-- Availability Check Form -->
        <form method="GET">
            <label for="check_in">Check-in Date:</label>
            <input type="date" name="check_in" required value="<?= isset($_GET['check_in']) ? $_GET['check_in'] : '' ?>">
            
            <label for="check_out">Check-out Date:</label>
            <input type="date" name="check_out" required value="<?= isset($_GET['check_out']) ? $_GET['check_out'] : '' ?>">

            <button type="submit">Check Availability</button>
        </form>

        <?php
        if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
            $check_in = $_GET['check_in'];
            $check_out = $_GET['check_out'];

            // Fetch available room types
            $query = "SELECT r.room_type, COUNT(r.room_id) AS total_rooms,
                      (COUNT(r.room_id) - 
                      (SELECT COUNT(*) FROM bookings 
                      WHERE room_id = r.room_id 
                      AND status = 'confirmed' 
                      AND ('$check_in' < check_out_date AND '$check_out' > check_in_date))) 
                      AS available_rooms
                      FROM rooms r
                      GROUP BY r.room_type";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>Room Type</th>
                            <th>Total Rooms</th>
                            <th>Available Rooms</th>
                        </tr>";
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['room_type']}</td>
                            <td>{$row['total_rooms']}</td>
                            <td>{$row['available_rooms']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-data'>No rooms available.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
