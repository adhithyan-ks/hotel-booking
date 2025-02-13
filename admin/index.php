<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    </style>
</head>
<body>
    <header>
    <h1 class="logo">PalmHotel</h1>
    <nav>
        <ul class="nav-links">
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
    </header>
    <main>
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
    </main>
</body>
</html>