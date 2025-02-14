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
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate a login process (in a real application, this would involve checking credentials)
    $email = $_POST['adminEmail'];
    $password = $_POST['adminPassword'];
    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      // Set the username in the session
      $_SESSION['adminEmail'] = $email;
      $_SESSION['adminPass'] = $password;
      // Redirect to the home page
      header('Location: home.php');
      exit();
    } else {
      echo "<div class='invalid'>Invalid email or password</div>";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="icon" type="image/x-icon" href="/images/hotel-logo.png" />
    <title>Login</title>
  </head>

  <body>
    <main class="container" border>
      <!-- From Uiverse.io by Yaya12085 -->
      <!-- From Uiverse.io by kyle1dev -->
      <form class="modern-form">
        <fieldset>
          <legend>Admin Login</legend>
        <div>
          <div>
            <div>
              <i class="fa-solid fa-envelope input-icon"></i>
              <input
                required=""
                placeholder="Email"
                class="form-input"
                id="adminEmail"
                name="adminEmail"
                type="email"
              />
            </div>
          </div>

          <div>
            <div>
              <i class="fa-solid fa-key input-icon"></i>
              <input
                required=""
                placeholder="Password"
                class="form-input"
                id="adminPassword"
                name="adminPassword"
                type="password"
              />
            </div>
          </div>
        </div>

        <button id="loginButton" class="submit-button" type="submit">
          <span class="button-text">Login</span>
          <div class="button-glow"></div>
        </button>
<!--
        <div class="form-footer">
          <a href="forgot-password.html" class="footer-link">Forgot Password?</a>
        </div>
-->
        </fieldset>
      </form>
    </main>
    
  </body>
  <script
    src="https://kit.fontawesome.com/2e5e758ab7.js"
    crossorigin="anonymous"
  ></script>
</html>
