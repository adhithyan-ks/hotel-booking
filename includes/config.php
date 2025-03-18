<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db_name = "hotel_db";

// Create database connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
