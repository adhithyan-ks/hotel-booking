<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
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
    <h1>Admins</h1>
    <form action="admins.php" method="POST">
        <input type="text" name="adminName" placeholder="Admin Name" required>
        <input type="email" name="adminEmail" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Insert Admin</button>
    </form>
  
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
    //Admin insert to database
    $errorMessage = ""; // Initialize error message

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['adminName'];
        $email = $_POST['adminEmail'];
        $password = $_POST['password'];
        // Check if email already exists
        $result = $conn->query("SELECT * FROM admins WHERE email = '$email'");
        if ($result->num_rows > 0) {
            $errorMessage = "Email already exists";
        } else {
            $conn->query("INSERT INTO admins (admin_name, email, password) VALUES ('$name', '$email', '$password')");
            header("Location: admins.php");
            exit();
        }
    }
    //Select all records from the users table
    $query = "SELECT * FROM admins";
    $result = $conn->query($query);
    echo "<table><tr><th>Admin ID</th><th>Admin Name</th><th>Email</th><th>Password</th><th>Created at</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['admin_id'] . "</td>";
        echo "<td>" . $row['admin_name'] . "</td>";
        echo "<td> " . $row['email'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td></tr>";
    }
    echo "</table>";
?>
</body>
</html>
