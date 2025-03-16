<?php
/*
session_start();
include '../includes/config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check admin credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_name'] = $admin['admin_name'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Incorrect email or password";
    }
}*/
?>
<?php
          include '../includes/config.php';
          session_start();
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Simulate a login process (in a real application, this would involve checking credentials)
            $email = $_POST['email'];
            $password = $_POST['password'];
            $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              // Set the username in the session
            //  $_SESSION['userEmail'] = $email;
              //$_SESSION['userPass'] = $password;
              $admin = $result->fetch_assoc(); // Fetch user data

              $_SESSION['admin_id'] = $admin['admin_id']; // Store user ID from database
              $_SESSION['admin_email'] = $admin['email']; // Store user ID from database
              $_SESSION['admin_name'] = $admin['admin_name']; // Store user ID from database


             // $_SESSION['user_role'] = $user['role']; // Store role (user/admin)
              // Redirect to the home page
              header('Location: index.php');
              exit();
            } else {
              $error = "Invalid email or password";
            }
          }
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/adminlogin.css">
    <title>Admin Login</title>
    <style>

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
