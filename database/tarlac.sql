-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 16, 2023 at 02:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tarlac`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_token`
--

CREATE TABLE `access_token` (
  `id` int(11) NOT NULL,
  `user_id` int(14) DEFAULT NULL,
  `event_id` int(14) DEFAULT NULL,
  `course_id` int(14) DEFAULT NULL,
  `year_level_id` int(14) DEFAULT NULL,
  `token` varchar(145) DEFAULT NULL,
  `print_status` enum('printed','pending') NOT NULL DEFAULT 'pending',
  `user_email` varchar(145) DEFAULT NULL,
  `date_of_use` datetime DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `country` varchar(145) DEFAULT NULL,
  `province` varchar(145) DEFAULT NULL,
  `city` varchar(145) DEFAULT NULL,
  `barangay` varchar(145) DEFAULT NULL,
  `street` varchar(145) DEFAULT NULL,
  `zip_code` varchar(145) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `department_id` int(14) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `educational_attainment` varchar(145) DEFAULT NULL COMMENT '0 = ELEMENTARY TO JHS, 1 = SHS, 2 = COLLEGE',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `department_id`, `course`, `educational_attainment`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'BACHELOR OF ARTS IN POLITICAL SCIENCE', '2', 'active', '2023-03-24 21:02:10', '2023-10-16 11:03:04'),
(2, 4, 'BACHELOR OF ELEMENTARY EDUCATION (BEED)', '2', 'active', '2023-03-25 00:11:32', '2023-06-30 00:15:00'),
(3, 4, 'BACHELOR OF SECONDARY EDUCATION (BSED)', '2', 'active', '2023-03-25 00:11:57', '2023-06-29 19:05:26'),
(4, 5, 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT', '2', 'active', '2023-03-25 00:12:06', '2023-06-29 19:05:55'),
(5, 5, 'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT', '2', 'active', '2023-03-25 00:11:32', '2023-06-29 19:06:03'),
(6, 2, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)', '2', 'active', '2023-03-25 00:11:57', '2023-06-29 19:06:08'),
(7, 1, 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION, MAJOR IN MARKETING MANAGEMENT', '2', 'active', '2023-03-25 00:12:06', '2023-06-29 22:55:00'),
(8, 1, 'BACHELOR OF SCIENCE IN ACCOUNTANCY', '2', 'active', '2023-03-25 00:11:57', '2023-06-29 19:06:21'),
(9, 3, 'BACHELOR OF SCIENCE IN CRIMINOLOGY (B.S. CRIM.)', '2', 'active', '2023-03-25 00:11:57', '2023-06-29 19:06:26'),
(10, 2, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE', '2', 'active', '2023-06-29 23:49:49', '2023-07-06 23:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `course_event`
--

CREATE TABLE `course_event` (
  `id` int(11) NOT NULL,
  `course_id` int(14) DEFAULT NULL,
  `year_level_id` int(14) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department` varchar(145) DEFAULT NULL,
  `department_logo` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department`, `department_logo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'COLLEGE OF BUSINESS AND ACCOUNTANCY', 'CBA_Logo.png', 'active', '2023-03-24 16:11:57', '2023-08-15 01:42:12'),
(2, 'COLLEGE OF COMPUTER STUDIES', 'CCS_Logo.png', 'active', '2023-03-24 16:11:57', '2023-06-28 19:29:46'),
(3, 'COLLEGE OF CRIMINAL JUSTICE EDUCATION', 'CCJE_Logo.png', 'active', '2023-03-24 16:11:57', '2023-06-28 19:31:08'),
(4, 'COLLEGE OF EDUCATION', 'CED_Logo.png', 'active', '2023-03-24 16:11:32', '2023-06-28 19:28:58'),
(5, 'COLLEGE OF HOSPITALITY MANAGEMENT', 'CHM_Logo.png', 'active', '2023-03-24 16:11:32', '2023-06-28 19:29:28'),
(6, 'COLLEGE OF LIBERAL ARTS', 'CLA_Logo.png', 'active', '2023-03-24 13:02:10', '2023-06-28 19:28:33'),
(7, 'SHS HIGH SCHOOL DEPARTMENT', 'SHS_Logo.png', 'active', '2023-07-01 03:42:55', '2023-07-01 03:47:30');

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `Id` int(145) NOT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`Id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'ecket2023@gmail.com', 'vnvbophnoxickore\n', '2023-02-20 11:25:24', '2023-07-12 10:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(145) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_venue` longtext DEFAULT NULL,
  `event_max_guest` varchar(145) DEFAULT NULL,
  `event_rules` longtext DEFAULT NULL,
  `event_poster` varchar(145) DEFAULT NULL,
  `event_price` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_access_key`
--

CREATE TABLE `event_access_key` (
  `id` int(11) NOT NULL,
  `event_id` int(14) DEFAULT NULL,
  `access_key` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_per_course`
--

CREATE TABLE `event_per_course` (
  `id` int(11) NOT NULL,
  `event_id` int(14) DEFAULT NULL,
  `event_type` varchar(145) DEFAULT NULL COMMENT 'mandatory = 1, optional = 2',
  `course_id` int(11) DEFAULT NULL,
  `year_level_id` int(11) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `event_status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registered`
--

CREATE TABLE `event_registered` (
  `id` int(11) NOT NULL,
  `event_id` int(14) DEFAULT NULL,
  `ticket_id` int(14) DEFAULT NULL,
  `user_first_name` varchar(145) DEFAULT NULL,
  `user_middle_name` varchar(145) DEFAULT NULL,
  `user_last_name` varchar(145) DEFAULT NULL,
  `user_email` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_recaptcha_api`
--

CREATE TABLE `google_recaptcha_api` (
  `Id` int(11) NOT NULL,
  `site_key` varchar(145) DEFAULT NULL,
  `site_secret_key` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `google_recaptcha_api`
--

INSERT INTO `google_recaptcha_api` (`Id`, `site_key`, `site_secret_key`, `created_at`, `updated_at`) VALUES
(1, '6LdiQZQhAAAAABpaNFtJpgzGpmQv2FwhaqNj2azh', '6LdiQZQhAAAAAByS6pnNjOs9xdYXMrrW2OeTFlrm', '2023-02-20 00:57:18', '2023-08-13 00:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(145) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `activity`, `created_at`, `updated_at`) VALUES
(1, 2, 'Has successfully signed in', '2023-10-16 12:43:13', NULL),
(2, 1, 'Has successfully signed in', '2023-10-16 12:43:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pdf_file`
--

CREATE TABLE `pdf_file` (
  `id` int(11) NOT NULL,
  `user_id` int(14) DEFAULT NULL,
  `event_id` int(14) DEFAULT NULL,
  `course_id` int(14) DEFAULT NULL,
  `year_level_id` int(14) DEFAULT NULL,
  `file_name` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

CREATE TABLE `religion` (
  `id` int(11) NOT NULL,
  `religion` varchar(145) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `religion`
--

INSERT INTO `religion` (`id`, `religion`, `created_at`, `updated_at`) VALUES
(1, 'Roman Catholic', '2023-02-19 08:36:44', NULL),
(2, 'Iglesia ni Cristo', '2023-02-19 08:36:44', '2023-02-19 08:38:16'),
(3, 'Cristian', '2023-02-19 08:36:44', '2023-02-19 08:38:29'),
(4, 'Islam', '2023-02-19 08:36:44', '2023-02-19 08:38:37'),
(5, 'Buddhism', '2023-02-19 08:36:44', '2023-02-19 08:38:43'),
(6, 'Protestant', '2023-02-19 08:36:44', '2023-02-19 08:38:50'),
(7, 'Methodist', '2023-02-19 08:36:44', '2023-02-19 08:38:56'),
(8, 'Adventist', '2023-02-19 08:36:44', '2023-02-19 08:39:03'),
(9, 'Independent', '2023-02-19 08:36:44', '2023-02-19 08:39:12'),
(10, 'Evangelical', '2023-02-19 08:36:44', '2023-02-19 08:39:18'),
(11, 'Jehovah\'s-Witnesses', '2023-02-19 08:36:44', '2023-02-19 08:39:27'),
(12, 'JIL', '2023-02-19 08:36:44', '2023-02-19 08:39:33'),
(13, 'Lutheran', '2023-02-19 08:36:44', '2023-02-19 08:39:39'),
(14, 'Orthodox', '2023-02-19 08:36:44', '2023-02-19 08:39:44'),
(15, 'Pentecostal', '2023-02-19 08:36:44', '2023-02-19 08:39:59'),
(16, 'Presbyterianism', '2023-02-19 08:36:44', '2023-02-19 08:40:02'),
(17, 'Latter-Day', '2023-02-19 08:36:44', '2023-02-19 08:40:13'),
(18, 'UCCP', '2023-02-19 08:36:44', '2023-02-19 08:40:18'),
(19, 'KJC', '2023-02-19 08:36:44', '2023-02-19 08:40:24'),
(20, 'Baptist', '2023-02-19 08:36:44', '2023-02-19 08:40:34'),
(21, 'Angelican-Episcopalian', '2023-02-19 08:36:44', '2023-02-19 08:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE `system_config` (
  `Id` int(14) NOT NULL,
  `system_name` varchar(145) DEFAULT NULL,
  `system_phone_number` varchar(145) DEFAULT NULL,
  `system_email` varchar(145) DEFAULT NULL,
  `system_logo` varchar(145) DEFAULT NULL,
  `system_color` varchar(145) DEFAULT NULL,
  `system_copy_right` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`Id`, `system_name`, `system_phone_number`, `system_email`, `system_logo`, `system_color`, `system_copy_right`, `created_at`, `updated_at`) VALUES
(1, 'DCT  E-CKET', '0977662192', 'dct.ecket2023@gmail.com', 'DCT-LOGO.png', NULL, 'COPYRIGHT © 2023 - DOMINICAN COLLEGE OF TARLAC E-CKET. ALL RIGHTS RESERVED.', '2023-02-20 00:16:44', '2023-07-12 11:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `event_id` int(14) DEFAULT NULL,
  `course_id` int(14) DEFAULT NULL,
  `year_level_id` int(14) DEFAULT NULL,
  `barcode` varchar(145) DEFAULT NULL,
  `user_first_name` varchar(145) DEFAULT NULL,
  `user_middle_name` varchar(145) DEFAULT NULL,
  `user_last_name` varchar(145) DEFAULT NULL,
  `user_phone_number` varchar(145) DEFAULT NULL,
  `user_email` varchar(145) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(145) DEFAULT NULL,
  `middle_name` varchar(145) DEFAULT NULL,
  `last_name` varchar(145) DEFAULT NULL,
  `sex` varchar(145) DEFAULT NULL COMMENT 'male=1, female=2',
  `date_of_birth` varchar(145) DEFAULT NULL,
  `age` varchar(145) DEFAULT NULL,
  `civil_status` varchar(145) DEFAULT NULL,
  `phone_number` varchar(145) DEFAULT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `profile` varchar(1145) NOT NULL DEFAULT 'profile.png',
  `status` enum('Y','N') DEFAULT 'N',
  `tokencode` varchar(145) DEFAULT NULL,
  `account_status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `user_type` varchar(14) DEFAULT NULL COMMENT 'superadmin=0,\r\nadmin=1,\r\nsub-admin=2',
  `department` int(14) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `date_of_birth`, `age`, `civil_status`, `phone_number`, `email`, `password`, `profile`, `status`, `tokencode`, `account_status`, `user_type`, `department`, `created_at`, `updated_at`) VALUES
(1, 'SUPERADMIN', 'SUPERADMIN', 'SUPERADMIN', 'MALE', NULL, NULL, 'MARRIED', NULL, 'sprdmndominican@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', 'Y', '253b2ddeff678a7865863f6b6e9e2ac5', 'active', '0', NULL, '2023-06-24 16:57:18', '2023-10-16 12:42:16'),
(2, 'ADMIN', 'ADMIN', 'ADMIN', 'MALE', NULL, NULL, 'SEPERATED', '9628648236', 'admnecket2023@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', 'Y', 'fc8d7f19470873a62253fa15ced58885', 'active', '1', NULL, '2023-07-07 08:02:26', '2023-10-16 12:42:51'),
(13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'profile.png', 'N', NULL, 'active', NULL, NULL, '2023-10-16 12:41:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `year_level`
--

CREATE TABLE `year_level` (
  `id` int(11) NOT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `educational_attainment` varchar(145) DEFAULT NULL COMMENT '0 = ELEMENTARY TO JHS,\r\n1 = SHS,\r\n2 = COLLEGE',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_level`
--

INSERT INTO `year_level` (`id`, `year_level`, `educational_attainment`, `status`, `created_at`, `updated_at`) VALUES
(1, 'G1 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:08'),
(2, 'G2 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:20'),
(3, 'G3 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:26'),
(4, 'G4 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:32'),
(5, 'G5 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:38'),
(6, 'G6 ELEMENTARY', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:01:49'),
(7, 'G7 HIGH SCHOOL', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:02:10'),
(8, 'G8 HIGH SCHOOL', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:02:40'),
(9, 'G9 HIGH SCHOOL', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:02:45'),
(10, 'G10 HIGH SCHOOL', '0', 'active', '2023-02-18 08:36:44', '2023-06-28 20:02:54'),
(11, 'G11 SENIOR HIGH SCHOOL', '1', 'active', '2023-02-18 08:36:44', '2023-06-28 19:58:06'),
(12, 'G12 SENIOR HIGH SCHOOL', '1', 'active', '2023-02-18 08:36:44', '2023-06-28 19:58:10'),
(13, '1ST YEAR COLLEGE', '2', 'active', '2023-02-18 08:36:44', '2023-07-30 14:15:23'),
(14, '2nd YEAR COLLEGE', '2', 'active', '2023-02-18 08:36:44', '2023-06-28 19:59:12'),
(15, '3rd YEAR COLLEGE', '2', 'active', '2023-02-18 08:36:44', '2023-06-28 19:59:22'),
(16, '4th YEAR COLLEGE', '2', 'active', '2023-02-18 08:36:44', '2023-06-28 19:59:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `course_event`
--
ALTER TABLE `course_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_access_key`
--
ALTER TABLE `event_access_key`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_per_course`
--
ALTER TABLE `event_per_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_registered`
--
ALTER TABLE `event_registered`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pdf_file`
--
ALTER TABLE `pdf_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `religion`
--
ALTER TABLE `religion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `year_level`
--
ALTER TABLE `year_level`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_token`
--
ALTER TABLE `access_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_event`
--
ALTER TABLE `course_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `email_config`
--
ALTER TABLE `email_config`
  MODIFY `Id` int(145) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_access_key`
--
ALTER TABLE `event_access_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_per_course`
--
ALTER TABLE `event_per_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registered`
--
ALTER TABLE `event_registered`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pdf_file`
--
ALTER TABLE `pdf_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `religion`
--
ALTER TABLE `religion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `system_config`
--
ALTER TABLE `system_config`
  MODIFY `Id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `year_level`
--
ALTER TABLE `year_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `access_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `access_token_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `access_token_ibfk_4` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`),
  ADD CONSTRAINT `access_token_ibfk_5` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Constraints for table `course_event`
--
ALTER TABLE `course_event`
  ADD CONSTRAINT `course_event_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `course_event_ibfk_2` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`);

--
-- Constraints for table `event_access_key`
--
ALTER TABLE `event_access_key`
  ADD CONSTRAINT `event_access_key_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `event_per_course`
--
ALTER TABLE `event_per_course`
  ADD CONSTRAINT `event_per_course_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `event_per_course_ibfk_2` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`),
  ADD CONSTRAINT `event_per_course_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `event_registered`
--
ALTER TABLE `event_registered`
  ADD CONSTRAINT `event_registered_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `event_registered_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pdf_file`
--
ALTER TABLE `pdf_file`
  ADD CONSTRAINT `pdf_file_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `pdf_file_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `pdf_file_ibfk_3` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`),
  ADD CONSTRAINT `pdf_file_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
