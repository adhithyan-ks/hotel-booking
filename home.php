<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="icon" type="image/x-icon" href="images\logo\hotellogo.png" />
    <title>Home</title>
  </head>

  <body>
    <header>
      <h1 class="logo">PalmHotel</h1>
      <button class="hamburger">
        <i class="fa-solid fa-bars"></i>
      </button>
      
      <nav>
        <ul class="nav-links">
          <li><a href="home.php">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="about.php">About</a></li>
        </ul>
        
        <?php
        // Duplicate login/account button for mobile menu
          session_start();
          if (isset($_SESSION['user_id'])) {
            echo "<div class='mobile-cta'><a href='account.php'><button><i class='fa-solid fa-user input-icon'></i> Account</button></a></div>";
          } else {
            echo "<div class='mobile-cta'><a href='login.php'><button><i class='fa-solid fa-user input-icon'></i> Login</button></a></div>";
          }
        ?>
      </nav>
      <?php
        if (isset($_SESSION['user_id'])) {
          echo "<a class='cta' href='account.php'><button><i class='fa-solid fa-user input-icon'></i> Account</button></a>";
        } else {
          echo "<a class='cta' href='login.php'><button><i class='fa-solid fa-user input-icon'></i> Login</button></a>";
        }
      ?>
    </header>

    <main class="container">
    <?php
      if (isset($_SESSION['user_id'])) {
        // If the user is already logged in
        $user_id = $_SESSION['user_id'];
        echo "Welcome back, $user_id! <br>";
        echo "<a href='logout.php'>Logout</a>";
      } else {
        // If the user is not logged in
        echo "Welcome, Guest! <br>";
        echo "<a href='login.php'>Login</a>";
      }
    ?>
    </main>

    <!-- Footer section -->
    <footer>
      <p>&copy; 2024 Hotel Name. All rights reserved.</p>
    </footer>
  </body>
  <script
    src="https://kit.fontawesome.com/2e5e758ab7.js"
    crossorigin="anonymous"
  ></script>
  <script src="js/navbar.js"></script>
</html>