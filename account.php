<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <title>Home</title>
</head>
<body>
<?php include 'includes/header.php'; ?>
<main class="container">
<h1>Account Details</h1>
<?php
    include 'includes/config.php';
    if (isset($_SESSION['user_id'])) {
        // If the user is already logged in
        $user_id = $_SESSION['user_id'];
        //echo "Welcome back, $email! <br>";
        $query = "SELECT * FROM users WHERE user_id = '$user_id'";

        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo "ID: {$row['user_id']}<br>";
            echo "Name: {$row['name']}<br>";
            echo "Email: {$row['email']}<br>";
            echo "Phone: {$row['phone']}<br>";
            echo "Created at: {$row['created_at']}<br>";
            echo "<a href='includes/logout.php'>Logout</a>";
        } else {
            echo "User not found!";
        }
    } else {
         // If the user is not logged in
        echo "Welcome, Guest! <br>";
        echo "<a href='login.php'>Login</a>";
    }
?>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
