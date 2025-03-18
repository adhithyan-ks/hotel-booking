<?php
include 'includes/config.php';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $query = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $success_message = "Your message has been sent!";
        } else {
            $error_message = "Error sending message. Please try again.";
        }
        $stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main class="container">
    <h2>Contact Us</h2>

    <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

    <form action="contact.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit" class="button">Send Message</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>
</body>
</html>
