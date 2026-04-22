-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 08:26 PM
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
-- Database: `golf_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `charities`
--

CREATE TABLE `charities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `charities`
--

INSERT INTO `charities` (`id`, `name`, `description`, `image`) VALUES
(1, 'Helping Hands', 'Providing support to underprivileged communities', NULL),
(2, 'Child Education Fund', 'Helping children get access to education', NULL),
(3, 'Healthcare Support', 'Medical aid and healthcare support for needy', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `draws`
--

CREATE TABLE `draws` (
  `id` int(11) NOT NULL,
  `draw_date` date DEFAULT NULL,
  `numbers` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `draws`
--

INSERT INTO `draws` (`id`, `draw_date`, `numbers`, `created_at`) VALUES
(16, '2026-04-21', '3,8,9,12,18', '2026-04-21 09:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL CHECK (`score` between 1 and 45),
  `score_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `score`, `score_date`, `created_at`) VALUES
(4, 1, 12, '2026-04-12', '2026-04-21 07:17:34'),
(5, 1, 12, '2026-04-21', '2026-04-21 07:20:28'),
(6, 1, 12, '2026-04-22', '2026-04-21 07:25:37'),
(8, 1, 25, '2026-04-20', '2026-04-21 10:37:37'),
(9, 1, 42, '2026-04-14', '2026-04-21 13:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan` enum('basic','pro','premium') DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `renewal_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan`, `status`, `renewal_date`) VALUES
(10, 3, 'basic', 'active', '2026-05-21'),
(12, 1, 'pro', 'inactive', '2026-05-21'),
(13, 1, '', 'inactive', '2027-04-21'),
(14, 1, '', 'inactive', '2027-04-21'),
(15, 1, 'pro', 'active', '2027-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'kavya', 'kavya@gmail.com', '$2y$10$bcM1.pFfnBM98TaSM3y0Zum0YCVZQ.QpC5sMHsTXj1eoru45YfwTm', 'user', '2026-04-20 12:20:23'),
(2, 'Admin', 'admin_golf_platform@gmail.com', '$2y$10$HPXz5JEP8OWW6UDtRu6FA.lQO/A89LvML9/sCUz4P/mGnyXdtidMy', 'admin', '2026-04-21 07:54:24'),
(3, 'Mahesh Kumar', 'mahesh@gmail.com', '$2y$10$ao8PHM4QhhPSmOdN3b5.q.1jWHSm/RyQMyuCPLT5cUku2256iVasC', 'user', '2026-04-21 12:26:23'),
(4, 'Umakanth', 'umakanth@yahoo.com', '$2y$10$bzJA5vioOq4Gx9PQHrNM5e9nELQzFQ92..SKJFBw/RtNv0T15pN1W', 'user', '2026-04-21 18:24:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_charity`
--

CREATE TABLE `user_charity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `charity_id` int(11) DEFAULT NULL,
  `percentage` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_charity`
--

INSERT INTO `user_charity` (`id`, `user_id`, `charity_id`, `percentage`) VALUES
(6, 1, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE `winners` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `draw_id` int(11) DEFAULT NULL,
  `match_count` int(11) DEFAULT NULL,
  `prize` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `proof` varchar(255) DEFAULT NULL,
  `verification_status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `winners`
--

INSERT INTO `winners` (`id`, `user_id`, `draw_id`, `match_count`, `prize`, `status`, `proof`, `verification_status`) VALUES
(2, 1, 16, 3, 1000.00, 'paid', '1776766561_Screenshot 2026-04-20 231500.png', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `charities`
--
ALTER TABLE `charities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `draws`
--
ALTER TABLE `draws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`score_date`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_charity`
--
ALTER TABLE `user_charity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `charity_id` (`charity_id`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `charities`
--
ALTER TABLE `charities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `draws`
--
ALTER TABLE `draws`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_charity`
--
ALTER TABLE `user_charity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_charity`
--
ALTER TABLE `user_charity`
  ADD CONSTRAINT `user_charity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_charity_ibfk_2` FOREIGN KEY (`charity_id`) REFERENCES `charities` (`id`);

--
-- Constraints for table `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
