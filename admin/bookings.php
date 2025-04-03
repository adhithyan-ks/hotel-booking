<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <title>Admin - Manage Bookings</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>
    
    <div class="content">
        <h1>Manage Hotel Bookings</h1>

        <!-- Filter Bookings -->
        <form method="GET" class="filter-form">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="confirmed" <?= isset($_GET['status']) && $_GET['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="canceled" <?= isset($_GET['status']) && $_GET['status'] == 'canceled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </form>

        <?php
        // Fetch filtered bookings
        $status_filter = isset($_GET['status']) && $_GET['status'] != '' ? "WHERE b.status = '{$_GET['status']}'" : '';
        $query = "SELECT b.*, r.room_type, 
                 p.payment_status, p.payment_method, p.transaction_id,
                 b.booking_source, b.admin_id
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.room_id
          LEFT JOIN payments p ON b.booking_id = p.booking_id
          $status_filter
          ORDER BY b.booked_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Room Type</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Services</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Transaction ID</th>
                        <th>Booking Source</th>
                        <th>Admin ID</th>
                        <th>Booked At</th>
                        <th>Action</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                // Handle services display
                $services = [];

                if ($row['breakfast'] == 'Yes') {
                    $services[] = "Breakfast (" . ($row['breakfast_time'] ?? 'N/A') . ")";
                }
                if ($row['dinner'] == 'Yes') {
                    $services[] = "Dinner (" . ($row['dinner_time'] ?? 'N/A') . ")";
                }
                if (!empty($row['additional_services'])) {
                    $services[] = "Extra: " . $row['additional_services'];
                }

                $services_display = !empty($services) ? implode(', ', $services) : 'No services';

                echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td><a href='users.php?search={$row['user_id']}' class='user-link'>{$row['user_id']}</a></td>
                        <td>{$row['room_type']}</td>
                        <td>{$row['check_in_date']}</td>
                        <td>{$row['check_out_date']}</td>
                        <td>{$services_display}</td>
                        <td>₹{$row['total_price']}</td>
                        <td class='status {$row['status']}'>" . ucfirst($row['status']) . "</td>
                        <td>";
                        
                if (!empty($row['payment_status'])) {
                    echo ucfirst($row['payment_status']) . " (" . (!empty($row['payment_method']) ? $row['payment_method'] : "N/A") . ")";
                } elseif ($row['booking_source'] == 'offline') {
                    echo "Cash (Paid at Counter)";
                } else {
                    echo "N/A";
                }

                echo "</td>
                        <td>" . ($row['transaction_id'] ? $row['transaction_id'] : 'N/A') . "</td>
                        <td>" . ucfirst($row['booking_source']) . "</td>
                        <td>" . ($row['admin_id'] ? $row['admin_id'] : 'N/A') . "</td>
                        <td>{$row['booked_at']}</td>
                        <td>";
                        
                if ($row['status'] == 'confirmed' && strtotime($row['check_in_date']) > time()) {
                    echo "<form action='cancel_booking.php' method='POST' onsubmit='return confirm(\"Cancel this booking?\")'>
                            <input type='hidden' name='booking_id' value='{$row['booking_id']}'>
                            <button type='submit' class='cancel-btn'>Cancel</button>
                        </form>";
                } else {
                    echo "-";
                }

                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-bookings'>No bookings found.</p>";
        }
        ?>
    </div>
</body>
</html>
