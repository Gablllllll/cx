-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 04:23 PM
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
  `approved_at` datetime DEFAULT NULL,
  `uploaded_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`material_id`, `title`, `description`, `file_url`, `uploaded_by`, `upload_date`, `is_approved`, `approved_by`, `approved_at`, `uploaded_by_id`) VALUES
(2, 'aaseq', 'testing 123', 'uploads/6824bf741be20_Chapter 4.pdf', 'admin', '2025-05-14 18:06:12', 1, 'admin', '2025-05-14 18:06:12', NULL),
(3, 'testing', 'testing', 'uploads/682f702742e9b_Title.pdf', 'admin', '2025-05-22 20:42:47', 1, 'admin', '2025-05-22 20:42:47', NULL),
(8, 'TESTING P2', 'ASD', 'uploads/6838d2d009644_testing.pdf', 'admin', '2025-05-29 23:34:08', 1, 'admin', '2025-05-29 23:34:08', NULL),
(12, 'test', 'testing 123 123 123', 'uploads/6852b4297c136_TITLES.pdf', 'tutor', '2025-06-18 14:42:17', 1, 'admin', '2025-06-18 14:42:17', 26),
(13, 'testingg', 'testing', 'uploads/6860095fc7233_TITLES.pdf', 'tutor', '2025-06-28 17:25:19', 1, 'admin', '2025-06-28 17:25:19', 26),
(14, 'test3', 'test', 'uploads/6861425f00776_TITLES.pdf', 'tutor', '2025-06-29 15:40:47', 1, 'admin', '2025-06-29 15:40:47', 26),
(15, 'test', 'sssssq', 'uploads/6861427fd5387_TITLES.pdf', 'tutor', '2025-06-29 15:41:19', 1, 'admin', '2025-06-29 15:41:19', 26),
(16, 'upload testing', 'this is also a testing', 'uploads/686147fe089f8_TITLES.pdf', 'tutor', '2025-06-29 16:04:46', 1, 'admin', '2025-06-29 16:04:46', 26),
(27, 'The Legend of The Bamboo and The Moon', 'An Old Folk Tale', 'uploads/68fb837d292db_The Legend of the Bamboo and the Moon.pdf', 'tutor', '2025-10-24 15:47:41', 1, 'admin', '2025-10-24 15:47:41', NULL);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `tutor_id`, `student_id`, `day`, `available_from`, `available_to`, `is_booked`, `session_date`, `location_mode`, `status`, `created_at`, `created_by`) VALUES
(1, 26, NULL, 'testing', '10:00:00', '09:00:00', 0, '2025-06-01', 'messenger', 'active', '2025-06-22 13:31:46', 0),
(2, 26, NULL, 'testing 2 ', '08:00:00', '09:00:00', 0, '2025-06-01', 'zoom', 'active', '2025-06-22 13:35:10', 0),
(5, 26, NULL, 'testing', '08:00:00', '09:00:00', 0, '2025-06-01', 'zoom', 'active', '2025-06-22 14:24:36', 26),
(7, 26, NULL, 'testing 123', '08:00:00', '09:00:00', 0, '2025-06-02', 'messenger', 'active', '2025-06-22 14:32:00', 26),
(8, 20, NULL, 'testing1235', '08:00:00', '09:00:00', 0, '2025-06-01', 'zoom', 'active', '2025-06-22 14:33:27', 20),
(12, 26, 20, 'testing', '08:00:00', '09:00:00', 0, '2025-06-02', 'zoom', 'active', '2025-06-22 15:48:34', 20),
(13, 26, NULL, 'testing', '06:00:00', '00:00:07', 0, '2025-06-03', 'testing', 'active', '2025-06-22 15:59:39', 20),
(16, 26, 2, 'testing', '08:00:00', '09:00:00', 0, '2025-06-03', 'testing', 'active', '2025-06-29 14:39:57', 26),
(17, 26, 2, 'asd', '08:00:00', '09:00:00', 0, '2025-06-04', 'asd', 'active', '2025-06-29 14:44:54', 26),
(18, 26, 2, 'testing', '10:00:00', '09:00:00', 0, '2025-06-02', 'messenger', 'active', '2025-06-29 14:53:31', 26),
(19, 26, 20, 'testing', '08:00:00', '09:00:00', 0, '2025-07-01', 'messenger', 'active', '2025-07-14 13:24:14', 26),
(20, 26, 20, 'testing', '08:00:00', '09:00:00', 0, '2025-07-02', 'testing', 'active', '2025-07-15 11:45:18', 26),
(21, 30, 20, 'tutoring', '08:00:00', '09:00:00', 0, '2025-07-03', 'messenger', 'active', '2025-07-17 03:11:52', 20);

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
-- Table structure for table `tutor_applications`
--

CREATE TABLE `tutor_applications` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_applications`
--

INSERT INTO `tutor_applications` (`id`, `tutor_id`, `student_id`, `status`, `applied_at`) VALUES
(1, 19, 20, 'pending', '2025-07-14 14:45:57'),
(2, 26, 20, 'rejected', '2025-07-14 14:51:08'),
(3, 26, 20, 'rejected', '2025-07-14 15:05:04'),
(4, 26, 20, 'approved', '2025-07-14 15:07:03'),
(5, 30, 29, 'approved', '2025-07-14 15:56:59'),
(6, 26, 31, 'approved', '2025-07-14 16:13:48'),
(7, 27, 20, 'pending', '2025-07-14 17:30:08'),
(8, 30, 20, 'approved', '2025-07-14 17:54:09'),
(9, 26, 32, 'pending', '2025-07-15 05:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `role`, `first_name`, `last_name`, `email`, `password_hash`, `secret_key`, `contact_number`, `address`, `date_of_birth`, `created_at`, `profile_pic`, `status`) VALUES
(2, 'junne123', 'student', 'Junnel', 'Decena', 'junneldecena@gmail.com', '$2y$10$aOqxEkTpii842hE5EH2b0O55p/Fq92IbEVYprnBL8x8kOiPiGG8aq', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-19 15:31:05', NULL, 'approved'),
(3, 'jennydecena', 'student', 'Jenny', 'Decena', 'jennydecena@gmail.com', '$2y$10$tJz70m/6X/TokcbrxR7wP.rX07x2UB4QBfo.5gH81O6d7oNC9FC/q', '', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:06:28', NULL, 'approved'),
(4, 'jennydecena10', '', 'Jenny', 'Decena', 'junneldecena10@gmail.com', '$2y$10$Z0uEpQLBnJQDWhXKGrTJxOlhKjAxjZx6NsCPOkNwwTOuwIgSbEruW', '', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:09:12', NULL, 'approved'),
(14, 'jenjen', '', 'jen', 'jen', 'jenjen@gmail.com', '$2y$10$/L6XC36ircE30RSI/UULs.pxYm9ysXYO2sCBWekeMLapaCnp7oZYy', '', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 07:56:22', NULL, 'approved'),
(19, 'jonel', 'admin', 'jonel', 'jonel', 'jonel@gmail.com', '$2y$10$oNkyD8KWcOyQPh898AoSOuDgtGi.KrPz6I60CY8VGZxnQcsUCdbNu', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:12:06', NULL, 'approved'),
(20, 'jens', 'student', 'jens', 'jens', 'jens@gmail.com', '$2y$10$yGDV5nCyfKugxeBnoBXUw.d2Ri4EdgE9E.yjGfM8.oXOgk2.aL.qC', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:13:24', NULL, 'approved'),
(26, 'tutor', 'admin', 'tutor', 'tutor', 'tutor@gmail.com', '$2y$10$DFofKW9akFwdE9o0rlCLJ.icQmnKeIxQHphLzkUMkZuRByY2iX8jC', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 08:33:04', NULL, 'approved'),
(27, 'junneltutor', 'admin', 'Junnel', 'Decena', 'junneldecena123@gmail.com', '$2y$10$BLneaXEscoCtCgzoeelPnu64quhBhEbuEzIWeMyYDG/x1xVW/JJ8m', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-30 13:35:48', NULL, 'approved'),
(28, 'junnieboy', 'student', 'junnel', 'decena', 'junnieboy123@gmail.com', '$2y$10$iGmaqpRGrsLrf38MNsBgNOC.JC5hfyoD9aGqyg6alxV3tz7UdyQDO', '1234', '09270190303', 'matalatala', '2001-03-30', '2025-06-22 15:57:32', NULL, 'approved'),
(29, 'beta', 'student', 'tester', 'tester', 'beta@gmail.com', '$2y$10$W9U96r85IFOceTvSBSi.nukygPfm2hxU/Xd6oxNPTChr9h3v6f6FO', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 15:55:29', NULL, 'approved'),
(30, 'betatutor', 'admin', 'betatutor', 'beta', 'betatutor@gmail.com', '$2y$10$mYEAKDiScNfZGZBlTIV/YO/BbzVr3xpxSGzRViMoEtBXZuKMgIa7O', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 15:56:23', NULL, 'approved'),
(31, 'rowel123', 'student', 'rowel', 'lagubana', 'rowel123@gmail.com', '$2y$10$u9UaP5xRhXgDlto9NiQ69edUVCN7Iy6HIsI5ypRhJA43apYIwWbFW', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 16:13:13', NULL, 'approved'),
(32, 'junnie', 'student', 'junnie', 'junnie', 'junnie@gmail.com', '$2y$10$QjlSz83Hxya5EAtebPmPOOtGw73OHMcVwZnRsN/GfdROCr3EfO38i', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-15 05:39:43', NULL, 'approved'),
(33, 'testing', 'admin', 'testing', 'testing', 'testing@gmail.com', '$2y$10$ZZ6NSZioucPflV2CWZwU9OO816L6WZoE6R815nxKpeHM0RQm2wyYC', '1234', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-16 08:53:42', NULL, 'pending'),
(34, 'johndoe', 'student', 'john', 'doe', 'johndoe@gmail.com', '$2y$10$fn74jj97Wq4a8LU5v3ICDu.oGgT1wdSgujL7PjxGqib3NcloeF0F2', '1234', '09270190303', 'Matalatala, Laguna', '2001-03-30', '2025-07-17 03:10:41', NULL, 'approved'),
(35, 'lucky', 'student', 'Mark Gabriel', 'Magdaong', 'markgabrielmagdaong@gmail.com', '$2y$10$lKAK3GgjmMWY0W5chgJomupy/sSsbKQv2UpZPZQQ3NUgFQv8Zy2tK', '1122', '09625505643', '#540 Montealgere Street. Sitio Bugarin Brgy. Halayhayin Pililla, Rizal', '2003-09-14', '2025-08-08 11:57:50', NULL, 'approved'),
(36, 'luna', 'student', 'luna', 'dog', 'luna@gmail.com', '$2y$10$FhUFRCMws7Ar81fWsZjkg.EG/5Ky1JPZzaENf4paw52KhEnYTPw6O', '123', '09213781287367', 'Montealegre Street', '2010-06-12', '2025-08-11 17:33:28', NULL, 'approved'),
(37, 'admin', 'admin', 'admin', 'admin', 'admin@gmail.com', '$2y$10$R4VkBO0vxxUB1kDOCND6k.pHnswog3HswAEnj9yCQPBBlYl52Zx5i', '1122', '09213781287367', 'Montealegre Street', '2025-10-01', '2025-10-01 13:01:35', NULL, 'approved'),
(38, 'juan', 'student', 'Juan', 'Dela Cruz', 'juan@gmail.com', '$2y$10$NqlSbio8Gks4CqQ/4MMnt.s.59/ugjwCCljyWWZH9jAOlBRtv.0Xq', '', '0935623678', 'Siniloan, Laguna', '2004-01-16', '2025-10-15 18:36:04', NULL, 'approved');

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
-- Indexes for table `tutor_applications`
--
ALTER TABLE `tutor_applications`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutor_applications`
--
ALTER TABLE `tutor_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
