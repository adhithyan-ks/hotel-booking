<?php include 'includes/config.php' ?>
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
    <?php
    if (isset($_SESSION['user_id'])) {
        echo "Welcome back, " . $_SESSION['user_id'] . "!<br>";
        echo "<a href='includes/logout.php'>Logout</a>";
    } else {
        echo "Welcome, Guest!<br>";
        echo "<a href='login.php'>Login</a>";
    }
    ?>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
