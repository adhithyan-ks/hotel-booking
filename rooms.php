<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/nav.css" />
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        img {
          border-radius: 5px;
          height: 200px;
          width: auto;
        }
    </style>
    <title>Rooms</title>
  </head>

  <body>
    <header>
      <h1 class="logo">PalmHotel</h1>
      <nav>
        <ul class="nav-links">
          <li><a href="home.html">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="about.html">About</a></li>
        </ul>
      </nav>
      <a class="cta" href="login.html"><button>Login</button></a>
    </header>

    <main class="container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel-booking";
    //Ceate connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Select all records from the users table
    $query = "SELECT * FROM rooms";
    $result = $conn->query($query);
    
    echo "<hr>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<div class='room'>";
        echo "<img src='" . $row['image_url'] . "'>";
        echo "<p>Room ID:" . $row['room_id'] . "</p>";
        echo "<p>Room Type:" . $row['room_type'] . "</p>";
        echo "<p>Description:" . $row['description'] . "</p>";
        echo "<p>Price per night:₹" . $row['price_per_night'] . "</p>";
        echo "<p>Availability Status:" . $row['avail_status'] . "</p>";
        echo "<p>Image:" . $row['image_url'] . "</p><hr>";
      echo "</div>";
    }
    echo "</table>";
    ?>
    <!-- Footer section 
    <footer>
      <p>&copy; 2024 Hotel Name. All rights reserved.</p>
    </footer>
  --></body></main>
</html>
