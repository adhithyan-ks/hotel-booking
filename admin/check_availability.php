<?php
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomType = $_POST['room_type'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    // Get total rooms of the selected type
    $queryTotalRooms = "SELECT COUNT(*) AS total_rooms FROM rooms WHERE room_type = ?";
    $stmt = $conn->prepare($queryTotalRooms);
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $totalRoomsResult = $stmt->get_result();
    $totalRooms = $totalRoomsResult->fetch_assoc()['total_rooms'] ?? 0;

    // Check how many rooms are already booked in the selected dates
    $queryBookedRooms = "SELECT COUNT(*) AS booked_rooms FROM bookings b 
                         JOIN rooms r ON b.room_id = r.room_id
                         WHERE r.room_type = ? 
                         AND (b.check_in_date < ? AND b.check_out_date > ?)
                         AND b.status = 'confirmed'";
    
    $stmt = $conn->prepare($queryBookedRooms);
    $stmt->bind_param("sss", $roomType, $checkOutDate, $checkInDate);
    $stmt->execute();
    $bookedRoomsResult = $stmt->get_result();
    $bookedRooms = $bookedRoomsResult->fetch_assoc()['booked_rooms'] ?? 0;

    // Calculate available rooms
    $availableRooms = $totalRooms - $bookedRooms;

    if ($availableRooms > 0) {
        echo "<span style='color: green;'>Rooms available: $availableRooms</span>";
    } else {
        echo "<span style='color: red;'>No available rooms for the selected dates.</span>";
    }
}
?>
