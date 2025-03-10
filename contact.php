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
    <?php include 'includes/header.php'; ?>

    <main class="container">
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

    <!-- Footer section -->
    <footer>
      <p>&copy; 2024 Hotel Name. All rights reserved.</p>
    </footer>
  </body>
  <script
    src="https://kit.fontawesome.com/2e5e758ab7.js"
    crossorigin="anonymous"
  ></script>
</html>
