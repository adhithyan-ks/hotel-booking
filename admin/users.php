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
        <h1>Users List</h1>
        <?php
        //Select all records from the users table
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Prepare search query
        $query = "SELECT * FROM users";
        if (!empty($search)) {
            $query .= " WHERE user_id LIKE '%$search%' OR name LIKE '%$search%' OR phone LIKE '%$search%'";
        }

        $result = $conn->query($query);
        ?>
        <form method="GET">
            <input type="text" name="search" placeholder="Search by ID, Name, or Phone" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
        <?php
        echo "<table><tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created at</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td> " . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td></tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
