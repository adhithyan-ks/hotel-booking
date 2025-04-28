-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 09:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `password`, `email`, `created_at`) VALUES
(2, 'Adhars', '123', 'ADHARS@gmail.com', '2025-02-14 10:03:31'),
(4, 'Adhithyan M', 'password', 'adhi360m@gmail.com', '2025-03-19 06:37:20'),
(5, 'Adhithyan K S', 'password', 'adhithyanktd@gmail.com', '2025-03-19 06:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,0) NOT NULL,
  `status` enum('pending','confirmed','canceled') DEFAULT 'pending',
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booking_source` enum('online','offline') DEFAULT 'online',
  `admin_id` int(11) DEFAULT NULL,
  `breakfast` enum('Yes','No') DEFAULT 'No',
  `breakfast_time` time DEFAULT NULL,
  `dinner` enum('Yes','No') DEFAULT 'No',
  `dinner_time` time DEFAULT NULL,
  `additional_services` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `status`, `booked_at`, `booking_source`, `admin_id`, `breakfast`, `breakfast_time`, `dinner`, `dinner_time`, `additional_services`) VALUES
(58, 34, 13, '2025-04-05', '2025-04-06', 5000, 'confirmed', '2025-04-03 17:40:39', 'online', NULL, 'No', '00:00:00', 'No', '00:00:00', 'No food needed'),
(59, 34, 19, '2025-04-28', '2025-04-29', 15000, 'confirmed', '2025-04-27 16:26:04', 'online', NULL, 'No', '00:00:00', 'No', '00:00:00', ''),
(61, 35, 19, '2025-05-01', '2025-05-02', 15000, 'confirmed', '2025-04-27 16:30:40', 'online', NULL, 'No', '00:00:00', 'No', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'adhithyan-ks', 'adhithyanktd@gmail.com', 'Location Details', 'Where is this hotel?', '2025-03-18 16:42:01'),
(2, 'Abhijith', 'abi@gmail.com', 'Parking', 'Is parking available there?', '2025-03-18 16:48:17'),
(3, 'Abhijith', 'abi@gmail.com', 'Parking', 'Is parking available there?', '2025-03-18 17:16:49'),
(4, 'adhithyan-ks', 'adhithyanktd@gmail.com', 'Location Details', '??', '2025-03-19 07:58:38');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed') DEFAULT 'pending',
  `payment_method` enum('credit_card','paypal','upi','cash') NOT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `booking_id`, `amount`, `payment_status`, `payment_method`, `transaction_id`, `created_at`) VALUES
(26, 34, 58, 5000.00, 'completed', 'upi', 'txn_67eec817d5eca', '2025-04-03 17:40:39'),
(27, 34, 59, 15000.00, 'completed', 'upi', 'txn_680e5a9c23c7b', '2025-04-27 16:26:04'),
(28, 35, 61, 15000.00, 'completed', 'upi', 'txn_680e5bb02f0d4', '2025-04-27 16:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_type`) VALUES
(5, 'Deluxe'),
(6, 'Deluxe'),
(7, 'Deluxe'),
(17, 'Executive'),
(18, 'Executive'),
(8, 'Family'),
(9, 'Family'),
(10, 'Family'),
(19, 'Presidential'),
(15, 'Single'),
(16, 'Single'),
(1, 'Standard'),
(2, 'Standard'),
(3, 'Standard'),
(4, 'Standard'),
(13, 'Suite'),
(14, 'Suite'),
(11, 'Twin'),
(12, 'Twin');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `room_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type`, `description`, `price_per_night`, `image_url`) VALUES
('Deluxe', 'A spacious deluxe room with premium amenities.', 2500.00, 'images/rooms/deluxe_room.jpg'),
('Executive', 'A high-end business room with a work desk and fast Wi-Fi.', 3200.00, 'images/rooms/executive_room.jpg'),
('Family', 'A large room with extra beds for families.', 3500.00, 'images/rooms/family_room.jpg'),
('Presidential', 'The most luxurious room with VIP services.', 15000.00, 'images/rooms/presidential_suite.jpg'),
('Single', 'A compact room with a single bed, ideal for solo travelers.', 1200.00, 'images/rooms/single_room.jpg'),
('Standard', 'A simple and affordable room with basic facilities.', 1500.00, 'images/rooms/standard_room.jpg'),
('Suite', 'A luxury suite with a private balcony and lounge area.', 5000.00, 'images/rooms/suite.jpg'),
('Twin', 'A room with two single beds for friends or colleagues.', 2000.00, 'images/rooms/twin_room.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(25) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(34, 'Adhithyan K S', 'adhithyan@gmail.com', 'password', '9747601129', '2025-04-03 17:37:47'),
(35, 'Unni', 'unni@gmail.com', 'password', '9966332211', '2025-04-03 07:08:10'),
(45, 'Sumesh K N', NULL, '', '4444444444', '2025-04-03 14:07:49'),
(46, 'Sabu', NULL, '', '7777777777', '2025-04-03 14:17:08'),
(47, 'Abhijith Kannan', NULL, '', '1234567890', '2025-04-03 17:18:28'),
(49, 'Adhithyan M', 'adhi360m@gmail.com', 'password', '9099999999', '2025-04-28 05:59:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `uq_admin_email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `room_type` (`room_type`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`room_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type`) REFERENCES `room_types` (`room_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
