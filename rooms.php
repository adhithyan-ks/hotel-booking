<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/rooms.css" />
    <title>Rooms</title>
</head>
<body>
    <header>
        <h1 class="logo">PalmHotel</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="rooms.php">Rooms</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
        <?php
        session_start();
        if (isset($_SESSION['userEmail'])) {
            echo "<a class='cta' href='account.php'><button><i class='fa-solid fa-user input-icon'></i> Account</button></a>";
        } else {
            echo "<a class='cta' href='login.php'><button><i class='fa-solid fa-user input-icon'></i> Login</button></a>";
        }
        ?>
    </header>

    <main class="container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hotel_db";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $query = "SELECT * FROM rooms";
        $result = $conn->query($query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='room-card'>";
            echo "<img src='" . $row['image_url'] . "' alt='Room Image'>";
            echo "<div class='room-info'>";
            echo "<h2>" . $row['room_type'] . "</h2>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p class='room-price'>Price per night: ₹" . $row['price_per_night'] . "</p>";
            echo "<p class='availability'>Status: " . $row['avail_status'] . "</p>";
            echo "<a href='#' class='button'>Book Now</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </main>
</body>
</html>
