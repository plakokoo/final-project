-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 04:00 AM
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
-- Database: `project_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `failed_login_attempts` int(10) UNSIGNED DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_failed_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`, `updated_at`, `email_verified_at`, `failed_login_attempts`, `last_login`, `last_failed_login`) VALUES
(1, 'Jonathan Andrew', 'jonathanandrew@gmail.com', '$2y$10$ADEK2XbaN7RivMHibQhmE.kBVfzZg3G6AB4bgY6mLszzQ.W/6O6JK', '2026-02-23 03:29:14', '2026-03-01 14:18:43', NULL, 5, '2026-02-28 10:25:03', '2026-03-01 14:18:43'),
(11, 'leblanc', 'leblanc@gmail.com', '$2y$10$7j.l7ndSW64RNTagB/cQ8uWCHfWyLJ7Nyr771gC7i5sHx8SdeLn6i', '2026-03-08 13:11:38', '2026-03-09 16:42:40', NULL, 0, '2026-03-09 16:42:40', NULL),
(16, 'Brent Faiyaz', 'brentfaiyaz23@gmail.com', '$2y$10$hyHfAT1PEX35pY0Evu0KD.D3otkBgyNVMDCBc/NTqYLwzXSN4z/We', '2026-03-09 16:15:37', '2026-03-09 16:15:37', NULL, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
