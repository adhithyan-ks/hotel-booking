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
        <h1>Messages from visitors</h1>
        <?php
        $query = "SELECT * FROM messages";
        $result = $conn->query($query);
        echo "<table><tr><th>Message ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Submitted At</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['message_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['subject'] . "</td>";
            echo "<td>" . $row['message'] . "</td>";
            echo "<td>" . $row['submitted_at'] . "</td></tr>";
        }
        echo "</table>";
        
        ?>
    </div>
</body>
</html>