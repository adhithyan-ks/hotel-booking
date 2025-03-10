<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images\logo\hotellogo.png" />
    <title>Users</title>
</head>
<body>
    <h1>Users</h1>
<?php
    include 'includes/config.php';
    session_start();
    if (isset($_SESSION['user_id'])) {
        // If the user is already logged in
        $user_id = $_SESSION['user_id'];
        //echo "Welcome back, $email! <br>";
        $query = "SELECT * FROM users WHERE user_id = '$user_id'";
        $result = $conn->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['user_id'] . "<br>";
            echo "Name: " . $row['name'] . "<br>";
            echo "Email: " . $row['email'] . "<br>";
            echo "Phone: " . $row['phone'] . "<br>";
            echo "Created at: " . $row['created_at'] . "<br>";
            echo "<a href='includes/logout.php'>Logout</a>";
        }
    } else {
         // If the user is not logged in
        echo "Welcome, Guest! <br>";
        echo "<a href='login.php'>Login</a>";
    }
?>
</body>
</html>
