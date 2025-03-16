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
        <h1>Users List</h1>

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
        $query = "SELECT * FROM users";
        $result = $conn->query($query);
        echo "<table><tr><th>User ID</th><th>Name</th><th>Email</th><th>Password</th><th>Phone</th><th>Created at</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td> " . $row['email'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td></tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
