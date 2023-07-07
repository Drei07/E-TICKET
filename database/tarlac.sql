-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 07, 2023 at 09:30 AM
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
  `token` varchar(145) DEFAULT NULL,
  `type_of_event` varchar(145) DEFAULT NULL,
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

--
-- Dumping data for table `course_event`
--

INSERT INTO `course_event` (`id`, `course_id`, `year_level_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 14, 'active', '2023-06-29 11:40:23', '2023-06-29 12:07:39'),
(2, 3, 15, 'active', '2023-06-29 11:40:23', '2023-06-29 12:07:51'),
(3, 7, 16, 'active', '2023-06-29 11:40:23', '2023-07-07 02:09:35'),
(4, 2, 14, 'active', '2023-07-02 01:21:42', '2023-07-07 02:06:05'),
(5, 9, 15, 'active', '2023-07-02 01:23:22', '2023-07-02 03:11:48'),
(7, 10, 14, 'active', '2023-07-02 11:55:53', '2023-07-07 02:07:03');

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
(8, 'COLLEGE OF BUSINESS AND ACCOUNTANCY', 'CBA_Logo.png', 'active', '2023-03-25 00:11:57', '2023-07-06 08:23:27'),
(6, 'COLLEGE OF COMPUTER STUDIES', 'CCS_Logo.png', 'active', '2023-03-25 00:11:57', '2023-06-29 03:29:46'),
(9, 'COLLEGE OF CRIMINAL JUSTICE EDUCATION', 'CCJE_Logo.png', 'active', '2023-03-25 00:11:57', '2023-06-29 03:31:08'),
(2, 'COLLEGE OF EDUCATION', 'CED_Logo.png', 'active', '2023-03-25 00:11:32', '2023-06-29 03:28:58'),
(5, 'COLLEGE OF HOSPITALITY MANAGEMENT', 'CHM_Logo.png', 'active', '2023-03-25 00:11:32', '2023-06-29 03:29:28'),
(1, 'COLLEGE OF LIBERAL ARTS', 'CLA_Logo.png', 'active', '2023-03-24 21:02:10', '2023-06-29 03:28:33'),
(11, 'SHS HIGH SCHOOL DEPARTMENT', 'SHS_Logo.png', 'active', '2023-07-01 11:42:55', '2023-07-01 11:47:30');

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
(1, 'Dhvsu.cna05@gmail.com', 'awhrzohlbevkkkgb', '2023-02-20 11:25:24', NULL);

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
  `event_rules` varchar(145) DEFAULT NULL,
  `event_type` varchar(145) DEFAULT NULL,
  `event_poster` varchar(145) DEFAULT NULL,
  `event_price` varchar(145) DEFAULT NULL,
  `course_id` int(15) DEFAULT NULL,
  `year_level_id` int(15) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_time`, `event_venue`, `event_max_guest`, `event_rules`, `event_type`, `event_poster`, `event_price`, `course_id`, `year_level_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BATTLE OF THE BANDS BY BELLE & BASTIAN BATTLE OF THE BANDS BY BELLE & BASTIAN', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', '77', 'MANDATORY', 'poster.jpg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 07:41:47'),
(2, 'BATTLE OF THE BANDS', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', 'NOTHING', 'MANDATORY', 'poster2.jpeg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 07:41:55'),
(3, 'BATTLE OF THE BANDS BY BELLE & BASTIAN', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', '77', 'MANDATORY', 'poster.jpg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 07:41:49'),
(4, 'BATTLE OF THE BANDS BY TITI VIC AND JOEY', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', 'NOTHING', 'MANDATORY', 'poster2.jpeg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 08:51:45'),
(5, 'BATTLE OF THE BANDS BY BELLE & BASTIAN', '2023-08-24', '13:00:00', 'DCT GYMNASIUM', '400', '77', 'MANDATORY', 'poster.jpg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 12:49:50'),
(6, 'BATTLE OF THE BANDS', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', 'NOTHING', 'MANDATORY', 'poster2.jpeg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 08:52:05'),
(7, 'BATTLE OF THE BANDS BY BELLE & BASTIAN', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '400', 'NOTHING', 'MANDATORY', 'poster.jpg', NULL, 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-06 08:52:01'),
(8, 'TAWAG NG TANGHALAN', '2023-08-16', '13:00:00', 'DCT GYMNASIUM', '', 'NOTHINGS', 'MANDATORY', 'poster2.jpeg', '250', 10, 14, 'active', '2023-07-05 07:12:47', '2023-07-07 03:48:09'),
(9, 'TAWAG NG TANGHALAN', '2023-10-17', '14:00:00', 'DCT GYMNASIUM', '500', 'NO RULES', 'MANDATORY', 'poster.jpg', NULL, 9, 15, 'active', '2023-07-06 02:34:33', '2023-07-06 12:24:54'),
(10, 'DANCE CONTEST', '2023-07-26', '22:12:00', 'DCT HALLWAY', '300', 'JUST DO IT', 'OPTIONAL', 'poster6.jpeg', NULL, 10, 14, 'active', '2023-07-06 14:10:48', NULL),
(11, 'BINGO', '2023-07-20', '08:46:00', 'KAHIT SAAN', '900', 'SAMPLE', 'OPTIONAL', 'bingo.jpeg', NULL, 10, 14, 'active', '2023-07-07 00:43:32', NULL);

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
(1, '6Lf-JsEeAAAAAPwHHG56Bb4kwfboD_eDUmDxtG0k', '6Lf-JsEeAAAAANEiSp7kRn6nmakC9A6vSGdaXxcO', '2023-02-20 00:57:18', '2023-07-01 02:38:18');

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
(1, 6, 'Has successfully signed in', '2023-06-25 07:11:10', NULL),
(2, 8, 'Has successfully signed in', '2023-06-25 07:14:28', NULL),
(3, 7, 'Has successfully signed in', '2023-06-25 07:14:58', NULL),
(4, 6, 'Has successfully signed in', '2023-06-25 11:54:24', NULL),
(5, 6, 'Has successfully signed in', '2023-06-25 12:13:44', NULL),
(6, 8, 'Has successfully signed in', '2023-06-25 12:28:37', NULL),
(7, 8, 'Has successfully signed in', '2023-06-25 12:29:00', NULL),
(8, 7, 'Has successfully signed in', '2023-06-26 13:08:31', NULL),
(9, 7, 'Has successfully signed in', '2023-06-27 03:05:12', NULL),
(10, 7, 'Has successfully signed in', '2023-06-27 12:29:48', NULL),
(11, 7, 'Has successfully signed in', '2023-06-29 10:31:34', NULL),
(12, 7, 'Has successfully signed in', '2023-06-30 02:57:21', NULL),
(13, 7, 'Has successfully signed in', '2023-07-01 02:57:54', NULL),
(14, 7, 'Has successfully signed in', '2023-07-01 11:37:16', NULL),
(15, 7, 'Has successfully signed in', '2023-07-02 00:56:41', NULL),
(16, 7, 'Has successfully signed in', '2023-07-02 01:05:04', NULL),
(17, 7, 'Has successfully signed in', '2023-07-04 06:10:28', NULL),
(18, 7, 'Has successfully signed in', '2023-07-04 11:48:59', NULL),
(19, 7, 'Has successfully signed in', '2023-07-05 02:27:51', NULL),
(20, 7, 'Has successfully signed in', '2023-07-06 02:00:07', NULL),
(21, 7, 'Has successfully signed in', '2023-07-06 02:06:55', NULL),
(22, 7, 'Has successfully signed in', '2023-07-06 02:20:12', NULL),
(23, 7, 'Has successfully signed in', '2023-07-06 02:59:44', NULL);

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
(1, 'DCT  E-CKET', '0977662192', 'dct.ecket2023@gmail.com', 'DCT-LOGO.png', NULL, 'COPYRIGHT © 2023 - DOMINICAN COLLEGE OF TARLAC. ALL RIGHTS RESERVED.', '2023-02-20 00:16:44', '2023-06-28 05:38:23');

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
(6, 'USER', 'SANTOS', 'DATU', NULL, NULL, NULL, NULL, '9776621929', 'andrewi.m.viscayno@gmail.com', '24b35e91f6650c460b66bceaa1590664', 'profile.png', 'Y', '253b2ddeff678a7865863f6b6e9e2ac5', 'active', '2', 5, '2023-06-25 00:54:32', '2023-07-05 11:57:24'),
(7, 'JOSE', 'SANTOS', 'DATU', 'MALE', NULL, NULL, 'MARRIED', NULL, 'andrei.m.viscayno@gmail.com', '8280cf6cf941dbabb5ebabf6a8016c0f', 'profile.png', 'Y', '253b2ddeff678a7865863f6b6e9e2ac5', 'active', '1', NULL, '2023-06-25 00:57:18', '2023-07-02 00:56:18'),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'andryi.m.viscayn@gmail.com', '24b35e91f6650c460b66bceaa1590664', 'profile.png', 'Y', '253b2ddeff678a7865863f6b6e9e2ac5', 'active', '1', NULL, '2023-06-25 00:57:18', '2023-06-25 07:14:22'),
(9, 'ANDREI', 'MANALANSAN', 'VISCAYNO', NULL, NULL, NULL, NULL, '9776621929', 'sample@gmail.com', '99e5c46e68d80ddc868c1818014d7a70', 'profile.png', 'Y', '0a962950d4565bac2182e89726e22e12', 'active', '2', 6, '2023-06-28 10:31:37', '2023-07-05 07:37:16'),
(10, 'SADASD', 'ASD', 'ADASD', NULL, NULL, NULL, NULL, '9777662127', 'ss.m.viscayno@gmail.com', 'eb27413295d072c61de94baf28813df7', 'profile.png', 'Y', 'cfad77736582662874978aa5367ce322', 'active', '2', 2, '2023-06-28 10:34:11', '2023-07-05 07:37:06');

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
(1, 'G1 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:08'),
(2, 'G2 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:20'),
(3, 'G3 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:26'),
(4, 'G4 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:32'),
(5, 'G5 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:38'),
(6, 'G6 ELEMENTARY', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:01:49'),
(7, 'G7 HIGH SCHOOL', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:02:10'),
(8, 'G8 HIGH SCHOOL', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:02:40'),
(9, 'G9 HIGH SCHOOL', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:02:45'),
(10, 'G10 HIGH SCHOOL', '0', 'active', '2023-02-18 16:36:44', '2023-06-29 04:02:54'),
(11, 'G11 SENIOR HIGH SCHOOL', '1', 'active', '2023-02-18 16:36:44', '2023-06-29 03:58:06'),
(12, 'G12 SENIOR HIGH SCHOOL', '1', 'active', '2023-02-18 16:36:44', '2023-06-29 03:58:10'),
(13, '1st YEAR COLLEGE', '2', 'active', '2023-02-18 16:36:44', '2023-06-29 03:59:06'),
(14, '2nd YEAR COLLEGE', '2', 'active', '2023-02-18 16:36:44', '2023-06-29 03:59:12'),
(15, '3rd YEAR COLLEGE', '2', 'active', '2023-02-18 16:36:44', '2023-06-29 03:59:22'),
(16, '4th YEAR COLLEGE', '2', 'active', '2023-02-18 16:36:44', '2023-06-29 03:59:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_level_id` (`year_level_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `email_config`
--
ALTER TABLE `email_config`
  MODIFY `Id` int(145) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `year_level`
--
ALTER TABLE `year_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `access_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`year_level_id`) REFERENCES `year_level` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
