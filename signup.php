<?php
include 'includes/config.php'; // Include the database configuration file

$errorMessage = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['userName'];
    $email = $_POST['userEmail'];
    $password = $_POST['userPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['userPhone'];

    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match!";
    } else {
        // Check if email already exists
        $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($result->num_rows > 0) {
            $errorMessage = "Email already exists";
        } else {
            $conn->query("INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')");
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registerstyle.css">
    <link rel="icon" type="image/x-icon" href="images\logo\hotellogo.png" />
    <title>Sign Up</title>
</head>

<body>
    <main class="container">
        <form class="modern-form" action="" method="POST">
            <div class="form-title">Sign Up</div>

            <div class="form-body">
                <div class="input-group">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input required placeholder="Username" class="form-input" name="userName" type="text">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input required placeholder="Email" class="form-input" name="userEmail" type="email">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone input-icon"></i>
                        <input required placeholder="Phone Number" class="form-input" name="userPhone" type="tel" pattern="[0-9]{10,}">
                        </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-key input-icon"></i>
                        <input required placeholder="Password" class="form-input" name="userPassword" type="password">
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="input-group">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-key input-icon"></i>
                        <input required placeholder="Confirm Password" class="form-input" name="confirmPassword" type="password">
                    </div>
                </div>
            </div>

            <button id="signupButton" class="submit-button" type="submit">
                <span class="button-text">Create Account</span>
            </button>

            <!-- Error message -->
            <?php if ($errorMessage): ?>
                <div class="invalid"><?= $errorMessage; ?></div>
            <?php endif; ?>

            <div class="form-footer">
                <a class="login-link" href="login.php">
                    Already have an account? <span>Login</span>
                </a>
            </div>
            <div id="error-message" class="invalid" style="display:none; color: red;"></div>

        </form>
    </main>
</body>
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/signup.js"></script>

</html>