-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 03:39 PM
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
-- Database: `classxic`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `material_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by` varchar(100) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`material_id`, `title`, `description`, `file_url`, `uploaded_by`, `upload_date`, `is_approved`, `approved_by`, `approved_at`) VALUES
(1, 'Pogi', 'asdasd', 'uploads/6824a908ae657_Hello there I am Junnel B.pdf', 'admin', '2025-05-14 16:30:32', 1, 'admin', '2025-05-14 16:30:32'),
(2, 'aaseq', 'testing 123', 'uploads/6824bf741be20_Chapter 4.pdf', 'admin', '2025-05-14 18:06:12', 1, 'admin', '2025-05-14 18:06:12'),
(3, 'testing', 'testing', 'uploads/682f702742e9b_Title.pdf', 'admin', '2025-05-22 20:42:47', 1, 'admin', '2025-05-22 20:42:47'),
(4, 'next title', 'title', 'uploads/682f72f46cde6_Title.pdf', 'admin', '2025-05-22 20:54:44', 1, 'admin', '2025-05-22 20:54:44'),
(5, 'asdasd', 'asd', 'uploads/682f753b4fd8e_Title.pdf', 'admin', '2025-05-22 21:04:27', 1, 'admin', '2025-05-22 21:04:27'),
(6, 'SDASDAS', 'ASDASDASD', 'uploads/682f82e9b8c1e_TITLES.pdf', 'admin', '2025-05-22 22:02:49', 1, 'admin', '2025-05-22 22:02:49'),
(7, 'Testing', 'This is testing file', 'uploads/6838cd9c81455_Hello there I am Junnel B.pdf', 'admin', '2025-05-29 23:11:56', 1, 'admin', '2025-05-29 23:11:56'),
(8, 'TESTING P2', 'ASD', 'uploads/6838d2d009644_testing.pdf', 'admin', '2025-05-29 23:34:08', 1, 'admin', '2025-05-29 23:34:08'),
(9, 'TESTING P3', 'ASD', 'uploads/6838d311cb9b6_Hello there I am Junnel B.pdf', 'admin', '2025-05-29 23:35:13', 1, 'admin', '2025-05-29 23:35:13'),
(10, 'testing', 'asd', 'uploads/6839b4a026b25_Chapter4.pdf', 'tutor', '2025-05-30 15:37:36', 1, 'admin', '2025-05-30 15:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `day` varchar(20) NOT NULL,
  `available_from` time NOT NULL,
  `available_to` time NOT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `session_date` date DEFAULT NULL,
  `location_mode` varchar(100) DEFAULT NULL,
  `status` enum('active','cancelled','completed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `session_date` date NOT NULL,
  `status` enum('upcoming','completed','cancelled') DEFAULT 'upcoming',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('student','parent','tutor','admin') NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `role`, `first_name`, `last_name`, `email`, `password_hash`, `secret_key`, `contact_number`, `address`, `date_of_birth`, `created_at`) VALUES
(2, 'junne123', 'student', 'Junnel', 'Decena', 'junneldecena@gmail.com', '$2y$10$aOqxEkTpii842hE5EH2b0O55p/Fq92IbEVYprnBL8x8kOiPiGG8aq', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-19 15:31:05'),
(3, 'jennydecena', 'student', 'Jenny', 'Decena', 'jennydecena@gmail.com', '$2y$10$tJz70m/6X/TokcbrxR7wP.rX07x2UB4QBfo.5gH81O6d7oNC9FC/q', '', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:06:28'),
(4, 'jennydecena10', 'parent', 'Jenny', 'Decena', 'junneldecena10@gmail.com', '$2y$10$Z0uEpQLBnJQDWhXKGrTJxOlhKjAxjZx6NsCPOkNwwTOuwIgSbEruW', '', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:09:12'),
(14, 'jenjen', 'parent', 'jen', 'jen', 'jenjen@gmail.com', '$2y$10$/L6XC36ircE30RSI/UULs.pxYm9ysXYO2sCBWekeMLapaCnp7oZYy', '', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 07:56:22'),
(19, 'jonel', 'tutor', 'jonel', 'jonel', 'jonel@gmail.com', '$2y$10$oNkyD8KWcOyQPh898AoSOuDgtGi.KrPz6I60CY8VGZxnQcsUCdbNu', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:12:06'),
(20, 'jens', 'student', 'jens', 'jens', 'jens@gmail.com', '$2y$10$yGDV5nCyfKugxeBnoBXUw.d2Ri4EdgE9E.yjGfM8.oXOgk2.aL.qC', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:13:24'),
(26, 'tutor', 'tutor', 'tutor', 'tutor', 'tutor@gmail.com', '$2y$10$DFofKW9akFwdE9o0rlCLJ.icQmnKeIxQHphLzkUMkZuRByY2iX8jC', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 08:33:04'),
(27, 'junneltutor', 'tutor', 'Junnel', 'Decena', 'junneldecena123@gmail.com', '$2y$10$BLneaXEscoCtCgzoeelPnu64quhBhEbuEzIWeMyYDG/x1xVW/JJ8m', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-30 13:35:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
