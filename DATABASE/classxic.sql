-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 06:50 PM
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
  `user_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `material_id`, `rating`, `comment`, `created_at`) VALUES
(1, 37, 15, 5, 'hh', '2025-10-24 16:04:26'),
(2, 35, 15, 5, 'A good Module', '2025-10-24 16:06:30'),
(3, 35, 27, 5, 'das', '2025-10-25 23:46:06'),
(4, 35, 30, 5, 'Good', '2025-10-29 01:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `material_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `uploaded_by_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`material_id`, `title`, `description`, `uploaded_by_id`, `file_url`, `upload_date`) VALUES
(2, 'aaseq', 'testing 123', 19, 'uploads/6824bf741be20_Chapter 4.pdf', '2025-05-14 18:06:12'),
(3, 'testing', 'testing', 19, 'uploads/682f702742e9b_Title.pdf', '2025-05-22 20:42:47'),
(8, 'TESTING P2', 'ASD', 19, 'uploads/6838d2d009644_testing.pdf', '2025-05-29 23:34:08'),
(12, 'test', 'testing 123 123 123', 19, 'uploads/6852b4297c136_TITLES.pdf', '2025-06-18 14:42:17'),
(13, 'testingg', 'testing', 19, 'uploads/6860095fc7233_TITLES.pdf', '2025-06-28 17:25:19'),
(14, 'test3', 'test', 19, 'uploads/6861425f00776_TITLES.pdf', '2025-06-29 15:40:47'),
(15, 'test', 'sssssq', 19, 'uploads/6861427fd5387_TITLES.pdf', '2025-06-29 15:41:19'),
(16, 'upload testing', 'this is also a testing', 19, 'uploads/686147fe089f8_TITLES.pdf', '2025-06-29 16:04:46'),
(27, 'The Legend of The Bamboo and The Moon', 'An Old Folk Tale', 19, 'uploads/68fb837d292db_The Legend of the Bamboo and the Moon.pdf', '2025-10-24 15:47:41'),
(30, 'What are Foxes', 'Learning Material', 39, 'uploads/68fd624ceace4_What are Foxes.pdf', '2025-10-26 01:50:36');

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
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `role`, `first_name`, `last_name`, `email`, `password_hash`, `contact_number`, `address`, `date_of_birth`, `created_at`) VALUES
(2, 'junne123', 'student', 'Junnel', 'Decena', 'junneldecena@gmail.com', '$2y$10$aOqxEkTpii842hE5EH2b0O55p/Fq92IbEVYprnBL8x8kOiPiGG8aq', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-19 15:31:05'),
(3, 'jennydecena', 'student', 'Jenny', 'Decena', 'jennydecena@gmail.com', '$2y$10$tJz70m/6X/TokcbrxR7wP.rX07x2UB4QBfo.5gH81O6d7oNC9FC/q', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:06:28'),
(4, 'jennydecena10', '', 'Jenny', 'Decena', 'junneldecena10@gmail.com', '$2y$10$Z0uEpQLBnJQDWhXKGrTJxOlhKjAxjZx6NsCPOkNwwTOuwIgSbEruW', '09270190303', 'Matalatala, Mabitac', '1985-05-15', '2025-05-22 07:09:12'),
(14, 'jenjen', '', 'jen', 'jen', 'jenjen@gmail.com', '$2y$10$/L6XC36ircE30RSI/UULs.pxYm9ysXYO2sCBWekeMLapaCnp7oZYy', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 07:56:22'),
(19, 'jonel', 'admin', 'SPLD Admin', 'jonel', 'jonel@gmail.com', '$2y$10$oNkyD8KWcOyQPh898AoSOuDgtGi.KrPz6I60CY8VGZxnQcsUCdbNu', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:12:06'),
(20, 'jens', 'student', 'jens', 'jens', 'jens@gmail.com', '$2y$10$yGDV5nCyfKugxeBnoBXUw.d2Ri4EdgE9E.yjGfM8.oXOgk2.aL.qC', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-22 08:13:24'),
(26, 'tutor', 'admin', 'SPLD Admin', 'tutor', 'tutor@gmail.com', '$2y$10$DFofKW9akFwdE9o0rlCLJ.icQmnKeIxQHphLzkUMkZuRByY2iX8jC', '09270190303', 'Matalatala, Mabitac', '2001-03-03', '2025-05-22 08:33:04'),
(27, 'junneltutor', 'admin', 'Junnel', 'Decena', 'junneldecena123@gmail.com', '$2y$10$BLneaXEscoCtCgzoeelPnu64quhBhEbuEzIWeMyYDG/x1xVW/JJ8m', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-05-30 13:35:48'),
(28, 'junnieboy', 'student', 'junnel', 'decena', 'junnieboy123@gmail.com', '$2y$10$iGmaqpRGrsLrf38MNsBgNOC.JC5hfyoD9aGqyg6alxV3tz7UdyQDO', '09270190303', 'matalatala', '2001-03-30', '2025-06-22 15:57:32'),
(29, 'beta', 'student', 'tester', 'tester', 'beta@gmail.com', '$2y$10$W9U96r85IFOceTvSBSi.nukygPfm2hxU/Xd6oxNPTChr9h3v6f6FO', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 15:55:29'),
(30, 'betatutor', 'admin', 'betatutor', 'beta', 'betatutor@gmail.com', '$2y$10$mYEAKDiScNfZGZBlTIV/YO/BbzVr3xpxSGzRViMoEtBXZuKMgIa7O', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 15:56:23'),
(31, 'rowel123', 'student', 'rowel', 'lagubana', 'rowel123@gmail.com', '$2y$10$u9UaP5xRhXgDlto9NiQ69edUVCN7Iy6HIsI5ypRhJA43apYIwWbFW', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-14 16:13:13'),
(32, 'junnie', 'student', 'junnie', 'junnie', 'junnie@gmail.com', '$2y$10$QjlSz83Hxya5EAtebPmPOOtGw73OHMcVwZnRsN/GfdROCr3EfO38i', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-15 05:39:43'),
(33, 'testing', 'admin', 'testing', 'testing', 'testing@gmail.com', '$2y$10$ZZ6NSZioucPflV2CWZwU9OO816L6WZoE6R815nxKpeHM0RQm2wyYC', '09270190303', 'Matalatala, Mabitac', '2001-03-30', '2025-07-16 08:53:42'),
(34, 'johndoe', 'student', 'john', 'doe', 'johndoe@gmail.com', '$2y$10$fn74jj97Wq4a8LU5v3ICDu.oGgT1wdSgujL7PjxGqib3NcloeF0F2', '09270190303', 'Matalatala, Laguna', '2001-03-30', '2025-07-17 03:10:41'),
(35, 'lucky', 'student', 'Mark Gabriels', 'Magdaong', 'markgabrielmagdaong@gmail.com', '$2y$10$lKAK3GgjmMWY0W5chgJomupy/sSsbKQv2UpZPZQQ3NUgFQv8Zy2tK', '09625505643', '#540 Montealgere Street. Sitio Bugarin Brgy. Halayhayin Pililla, Rizal', '2003-09-14', '2025-08-08 11:57:50'),
(36, 'luna', 'student', 'luna', 'dog', 'luna@gmail.com', '$2y$10$FhUFRCMws7Ar81fWsZjkg.EG/5Ky1JPZzaENf4paw52KhEnYTPw6O', '09213781287367', 'Montealegre Street', '2010-06-12', '2025-08-11 17:33:28'),
(37, 'admin', 'admin', 'admin', 'admin', 'admin@gmail.com', '$2y$10$R4VkBO0vxxUB1kDOCND6k.pHnswog3HswAEnj9yCQPBBlYl52Zx5i', '09213781287367', 'Montealegre Street', '2025-10-01', '2025-10-01 13:01:35'),
(38, 'juan', 'student', 'Juan', 'Dela Cruz', 'juan@gmail.com', '$2y$10$NqlSbio8Gks4CqQ/4MMnt.s.59/ugjwCCljyWWZH9jAOlBRtv.0Xq', '0935623678', 'Siniloan, Laguna', '2004-01-16', '2025-10-15 18:36:04'),
(39, 'joseph', 'admin', 'SPLD Admin', 'Barangas', 'joseph@gmail.com', '$2y$10$SXPWd4H56wKd5FhbqUfTCuBAWoM7Jo9ur5u.HnSCtiUGrhIfeHT7O', '09270190303', 'Bugarin Pililla, Rizal', '2004-07-19', '2025-10-24 15:06:34'),
(40, 'willowmontealegre', 'student', 'Willow', 'Montealegre', 'willowthedog870@gmail.com', '$2y$10$hAi0yzg5nT36uOZGNRGcFu9QkZbU.MvCek7NaIfoKcGYqCeTU7vsO', '09235272537', 'Bugarin Pililla, Rizal', '2004-09-16', '2025-10-29 16:59:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD UNIQUE KEY `unique_user_material_feedback` (`user_id`,`material_id`),
  ADD KEY `idx_material_id` (`material_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `idx_uploaded_by_id` (`uploaded_by_id`);

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
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`material_id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD CONSTRAINT `fk_learning_materials_uploaded_by` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
