<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images\logo\hotellogo.png" />
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
    <h1>Users</h1>
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
    // $query = "SELECT * FROM users WHERE email = 1";
    session_start();
    if (isset($_SESSION['userEmail'])) {
        // If the user is already logged in
        $email = $_SESSION['userEmail'];
        echo "Welcome back, $email! <br>";
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['user_id'] . "<br>";
            echo "Name: " . $row['name'] . "<br>";
            echo "Email: " . $row['email'] . "<br>";
            echo "Phone: " . $row['phone'] . "<br>";
            echo "Created at: " . $row['created_at'] . "<br>";
            echo "<a href='logout.php'>Logout</a>";
        }
    } else {
         // If the user is not logged in
        echo "Welcome, Guest! <br>";
        echo "<a href='login.php'>Login</a>";
    }
?>
</body>
</html>
