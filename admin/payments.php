<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Payments - Admin Panel</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    <div class="content">
        <h1>Payments Management</h1>
        <p>View all payments made by users.</p>
        
        <?php
        $query = "SELECT p.*, u.name AS user_name, b.check_in_date, b.check_out_date 
                  FROM payments p
                  JOIN users u ON p.user_id = u.user_id
                  JOIN bookings b ON p.booking_id = b.booking_id";
        $result = $conn->query($query);

        echo "<table>
                <tr>
                    <th>Payment ID</th>
                    <th>User</th>
                    <th>Booking ID</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Method</th>
                    <th>Transaction ID</th>
                    <th>Payment Date</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['payment_id'] . "</td>
                    <td>" . $row['user_name'] . "</td>
                    <td>" . $row['booking_id'] . "</td>
                    <td>₹" . $row['amount'] . "</td>
                    <td>" . ucfirst($row['payment_status']) . "</td>
                    <td>" . ucfirst($row['payment_method']) . "</td>
                    <td>" . ($row['transaction_id'] ? $row['transaction_id'] : 'N/A') . "</td>
                    <td>" . $row['created_at'] . "</td>
                </tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
