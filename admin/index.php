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
        <h1>Dashboard</h1>
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
        ?>
        <?php
      if (isset($_SESSION['userEmail'])) {
        // If the user is already logged in
        $email = $_SESSION['userEmail'];
        echo "Welcome back, $email! <br>";
        echo "<a href='logout.php'>Logout</a>";
        } else {
        // If the user is not logged in
        echo "Welcome, Guest! <br>";
        echo "<a href='login.php'>Login</a>";
        }
      ?>
    </div>
</body>
</html> 