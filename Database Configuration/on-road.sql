-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 12:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `on-road`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminactivitylog`
--

CREATE TABLE `adminactivitylog` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL,
  `adminCode` int(11) NOT NULL,
  `Role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fullName`, `email`, `username`, `password`, `adminCode`, `Role`) VALUES
(3, 'admin', 'admin@gmail.com', 'admin', '$2y$10$0s/T0qbAgJkZY4KHOhvzG.ZQJUJZiCXf3Yr.c31oZcp.1/Hhf0dIa', 101, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `fullName`, `email`, `username`, `password`, `role`) VALUES
(4, 'client', 'client@gmail.com', 'client', '$2y$10$cIGdDeA/9KFQBVevwS5OFO1q.vgTftYa52OxENY7/t055n5uuQ2M.', 'client'),
(7, 'client1', 'client1@gmail.com', 'client1', '$2y$10$cqEtk1C/M3ubizGrrEeV3OKw2o.B6m4QuRa8bJ1D43H7kaOGtUXW.', 'client'),
(8, 'client2', 'client2@gmail.com', 'client2', '$2y$10$pCuJkic1L/9ynAlIDJ4BMuHnaDguIO8Coi8bF/mSYs499SdpJw.7q', 'client'),
(9, 'client', 'client@gmail.com', 'client', '$2y$10$GXBXGnj7U7lk2527.eNHrubw7WaAT26iRKm6S.k5/9DW8t6r5B1CO', 'client'),
(10, 'client', 'client@gmail.com', 'client', '$2y$10$ciXzfESUm1MuS8U1G0940eYlAl7PrNARE3bm8STf89Cj3Qo7TDHIK', 'client'),
(11, 'client', 'client@gmail.com', 'client', '$2y$10$5cBx/ODnftKeVX/b5thuX.ZZ1s5VOWAzE1Tu3BKyVTqUXorSBuGsy', 'client'),
(12, 'client', 'client@gmail.com', 'client', '$2y$10$n0zWRXZmE8zuXry..9Mp8ufn/658hQmzAz6XO4LwwDDb04aBDTGIu', 'client');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `costRating` int(5) NOT NULL,
  `serviceRating` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mechanics`
--

CREATE TABLE `mechanics` (
  `id` int(11) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL,
  `location` varchar(50) NOT NULL,
  `specialization` varchar(20) NOT NULL,
  `experience` year(4) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `totalReviews` int(11) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanics`
--

INSERT INTO `mechanics` (`id`, `fullName`, `email`, `username`, `password`, `location`, `specialization`, `experience`, `rating`, `totalReviews`, `role`) VALUES
(7, 'mechanic', 'mechanic@gmail.com', 'mechanic', '$2y$10$P1tT0oWHFZy43lTnEpSo3uL6nR4uRcAwJwpxGX.YUOcZownT0fRZq', 'cairo', 'electric', '2015', 0, 0, 'mechanic');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `description` varchar(300) NOT NULL,
  `createdAt` datetime NOT NULL,
  `completedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `solutions`
--

CREATE TABLE `solutions` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_admin_fk` (`admin_id`),
  ADD KEY `log_client_fk` (`client_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_request_fk` (`request_id`),
  ADD KEY `feedback_mechanic_fk` (`mechanic_id`),
  ADD KEY `feedback_client_fk` (`client_id`);

--
-- Indexes for table `mechanics`
--
ALTER TABLE `mechanics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_request_fk` (`client_id`),
  ADD KEY `mechanic_request_fk` (`mechanic_id`);

--
-- Indexes for table `solutions`
--
ALTER TABLE `solutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solution_request_fk` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mechanics`
--
ALTER TABLE `mechanics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `solutions`
--
ALTER TABLE `solutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  ADD CONSTRAINT `log_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `log_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedback_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `feedback_mechanic_fk` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`),
  ADD CONSTRAINT `feedback_request_fk` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `client_request_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `mechanic_request_fk` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`);

--
-- Constraints for table `solutions`
--
ALTER TABLE `solutions`
  ADD CONSTRAINT `solution_request_fk` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
