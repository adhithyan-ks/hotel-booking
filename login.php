<?php include 'includes/config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Simulate a login process (in a real application, this would involve checking credentials)
  $email = $_POST['userEmail'];
  $password = $_POST['userPassword'];
  $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
    $_SESSION['user_id'] = $user['user_id']; // Store user ID from database
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
    <link rel="stylesheet" href="css/registerstyle.css" />
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png" />
    <title>Login</title>
  </head>

  <body>
    <main class="container" border>
      <form action="login.php" method="POST" class="modern-form">
        <div class="form-title">Login</div>

        <div class="form-body">
          <div class="input-group">
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope input-icon"></i>
              <input
                required=""
                placeholder="Email"
                class="form-input"
                id="userEmail"
                name="userEmail"
                type="email"
              />
            </div>
          </div>

          <div class="input-group">
            <div class="input-wrapper">
              <i class="fa-solid fa-key input-icon"></i>
              <input
                required=""
                placeholder="Password"
                class="form-input"
                id="userPassword"
                name="userPassword"
                type="password"
              />
            </div>
          </div>
        </div>

        <button id="loginButton" class="submit-button" type="submit">
          <span class="button-text">Login</span>
          <div class="button-glow"></div>
        </button>
        <div class="form-footer">
          <a class="login-link" href="signup.php">
            Don't have an account? <span>Sign up</span>
          </a>
        </div>
      </form>
    </main>
  </body>
  <script
    src="https://kit.fontawesome.com/2e5e758ab7.js"
    crossorigin="anonymous"
  ></script>
</html>
