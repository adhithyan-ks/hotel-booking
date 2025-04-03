<?php 
include 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Fetch user details
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        if (!empty($name) && !empty($email) && !empty($phone)) {
            $updateQuery = "UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Account details updated successfully!'); window.location.href = 'account.php';</script>";
            } else {
                echo "<script>alert('Error updating account details. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('All fields are required!');</script>";
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/account.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <title>My Account</title>
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="account-container">
    <div class="account-card">
        <h2>My Account</h2>

        <div class="user-info">
            <p><strong>ID:</strong> <?= $user['user_id']; ?></p>
            <p><strong>Name:</strong> <span id="display_name"><?= $user['name']; ?></span></p>
            <p><strong>Email:</strong> <span id="display_email"><?= $user['email']; ?></span></p>
            <p><strong>Phone:</strong> <span id="display_phone"><?= $user['phone']; ?></span></p>
            <p><strong>Joined:</strong> <?= $user['created_at']; ?></p>
            <a href="includes/logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Edit Account Button -->
        <button id="editAccountBtn" class="edit-btn">Edit Account</button>

        <!-- Edit Form (Hidden by Default) -->
        <form id="editAccountForm" method="POST" class="edit-account-form" style="display: none;">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= $user['name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= $user['email']; ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?= $user['phone']; ?>" required>

            <button type="submit" class="save-btn">Save Changes</button>
            <button type="button" id="cancelEditBtn" class="cancel-btn">Cancel</button>
        </form>

        <h2>My Bookings</h2>
        <?php
        // Fetch user bookings
        $query = "SELECT b.*, r.room_type 
                  FROM bookings b 
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.user_id = ? AND (b.status = 'confirmed' OR b.status = 'canceled')
                  ORDER BY b.booked_at DESC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table class='bookings-table'>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room Type</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                $booking_id = $row['booking_id'];
                $room_type = $row['room_type'];
                $check_in = $row['check_in_date'];
                $check_out = $row['check_out_date'];
                $status = ucfirst($row['status']);

                echo "<tr>
                        <td>$booking_id</td>
                        <td>$room_type</td>
                        <td>$check_in</td>
                        <td>$check_out</td>
                        <td>$status</td>
                        <td>";
                
                if ($status == 'Confirmed' && strtotime($check_in) > time()) {
                    echo "<form action='cancel_booking.php' method='POST' onsubmit='return confirm(\"Are you sure you want to cancel this booking?\")'>
                            <input type='hidden' name='booking_id' value='$booking_id'>
                            <button type='submit' class='cancel-btn'>Cancel</button>
                          </form>";
                } else {
                    echo "-";
                }

                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-bookings'>You have no bookings.</p>";
        }
        ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="js/navbar.js"></script>
<script>
    document.getElementById('editAccountBtn').addEventListener('click', function() {
        document.getElementById('editAccountForm').style.display = 'block';
        this.style.display = 'none';
    });

    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        document.getElementById('editAccountForm').style.display = 'none';
        document.getElementById('editAccountBtn').style.display = 'block';
    });
</script>

</body>
</html>
