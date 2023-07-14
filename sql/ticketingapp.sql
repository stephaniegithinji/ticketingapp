-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 14, 2023 at 03:33 AM
-- Server version: 10.11.2-MariaDB-1
-- PHP Version: 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketingapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `venue` varchar(50) NOT NULL,
  `time` time DEFAULT NULL,
  `ticket_price` mediumint(7) NOT NULL,
  `tickets_capacity` mediumint(7) NOT NULL,
  `banner` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `date`, `venue`, `time`, `ticket_price`, `tickets_capacity`, `banner`, `created_at`, `updated_at`) VALUES
(6, 'Baha &amp; steph events', '2023-07-26', 'Ruaka', '20:10:00', 3500, 1000, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:08:33', '2023-07-14 00:44:10'),
(7, '420 sesh', '2023-07-19', 'Kwa Mathee', '17:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:26', '2023-07-14 00:44:38'),
(8, 'Soulfest', '2023-07-15', 'Ngong Racecourse', '21:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:34', '0000-00-00 00:00:00'),
(9, 'Soulfest', '2023-07-15', 'Ngong Racecourse', '21:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:39', '0000-00-00 00:00:00'),
(10, 'Soulfest', '2023-07-20', 'Ngong Racecourse', '21:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:47', '0000-00-00 00:00:00'),
(11, 'Soulfest', '2023-07-15', 'Ngong Racecourse', '21:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:52', '0000-00-00 00:00:00'),
(12, 'Soulfest', '2023-07-15', 'Ngong Racecourse', '21:10:00', 1500, 800, '../assets/images/banners/64b059c15e663.jpg', '2023-07-13 20:13:57', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `number_of_tickets` int(5) NOT NULL,
  `total_amount` int(20) NOT NULL,
  `users_id` int(11) NOT NULL,
  `events_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `number_of_tickets`, `total_amount`, `users_id`, `events_id`) VALUES
(2, 2, 7000, 7, 6);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_code` varchar(20) NOT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `contact`, `password`, `is_admin`) VALUES
(1, 'admin', 'adm1n.tickectok@gmail.com', '707294699', '$2y$10$2vKxlNfeK7bE8XpDIY7dGe4VhRB4.iVDS7/JTznizStW54iNzb6Lu', 1),
(5, 'afefefe', 'andrew.karanja@gmail.com', '0712354788', '$2y$10$60qU6md.n8TCm72TreGIS.AzmXUXm6q7JIzfk/6YB5evJo0Mo7TZ6', 0),
(7, 'stephh', 'me@stephiiee.dev', '071234578', '$2y$10$X7fM93PKqoSq.eEBKyHfXutPTQnSfdeHLibIP8Rd8R67uEOlgyzZe', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`,`events_id`),
  ADD KEY `events_id` (`events_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`) USING BTREE,
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
