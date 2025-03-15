-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 09:11 AM
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
(1, 'Adhithyan', 'asd', 'adhithyanktd@gmail.com', '2025-02-14 10:02:41'),
(2, 'Adhars', '123', 'ADHARS@gmail.com', '2025-02-14 10:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,0) NOT NULL,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `booked_at`) VALUES
(1, 33, 1, '2025-03-10', '2025-03-13', 4500, '2025-03-10 16:45:27'),
(2, 33, 1, '2025-03-10', '2025-03-13', 4500, '2025-03-10 16:46:01'),
(3, 33, 1, '2025-03-10', '2025-03-13', 4500, '2025-03-10 16:48:40'),
(4, 33, 1, '2025-03-10', '2025-03-13', 4500, '2025-03-10 16:48:56'),
(5, 33, 1, '2025-03-10', '2025-03-13', 4500, '2025-03-10 17:25:15'),
(6, 33, 1, '2025-03-13', '2025-03-28', 22500, '2025-03-12 07:24:09'),
(7, 33, 15, '2025-03-21', '2025-03-25', 4800, '2025-03-14 08:00:53');

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
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(13, 'Adhithyan', 'adhithyan@gmail.com', 'kuiuuhh788', '1231231312', '2025-01-26 13:43:54'),
(15, 'vELLI', 'ADHARS@gmail.com', 'SDFW323', '9747601123', '2025-01-31 08:50:01'),
(16, 'admin', 'admin@gmail.com', 'passwordfff', '97648585', '2025-01-31 10:16:48'),
(17, 'Velayudhan', 'velayudhan@gmail.com', 'sadf', '1234456464', '2025-02-04 15:03:05'),
(22, 'qwert', 'qwer@gm', '123asd!@#', '1234567890', '2025-02-05 10:00:06'),
(28, 'fana', 'fana@ghm.com', 'dfsgdfger', '1234567768', '2025-02-05 10:29:17'),
(29, 'Unni', 'unni@gmail.com', 'unni123', '9747601128', '2025-02-05 12:29:21'),
(30, 'Adhithyan', 'adhi@gamil.com', 'asd123', '9747601128', '2025-02-05 13:29:07'),
(31, 'Maven', 'florayt863@gmail.com', 'MavenGT200', '9656994624', '2025-02-07 07:25:58'),
(32, 'Maven', 'asd@ad.com', 'asd', '9656994624', '2025-02-14 09:15:22'),
(33, 'asd', 'asd@asd', 'asd', '9656994624', '2025-03-10 14:23:43');

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type`) REFERENCES `room_types` (`room_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
