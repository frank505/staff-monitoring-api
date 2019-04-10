-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2019 at 09:18 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staff_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profilephoto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `profilephoto`, `phone_number`, `push_token`, `created_at`, `updated_at`) VALUES
(1, 'franklin', 'akpufranklin2@gmail.com', NULL, 'password', NULL, NULL, '', NULL, '2019-03-14 06:02:37'),
(2, 'francis', 'francis@gmail.ccom', NULL, '$2y$10$hcg0YgVvyHVPDg6nUTyJ..49icoviUZIlz21LrqK1hXIh7Ftm0rk6', NULL, NULL, '', '2019-03-13 21:20:13', '2019-03-13 21:20:13'),
(3, 'frank', 'akpufranklin3@gmail.com', NULL, '$2y$10$g3aL282DoGkwlbdFIZHIiur9v5Nh7.0JYBlv9/NaqFNep2Y5pKff6', NULL, NULL, '', '2019-03-13 22:01:48', '2019-03-13 22:01:48'),
(4, 'pascal', 'pascal@gmail.com', NULL, '$2y$10$9GanONiTSkqAz7z.4zMoH.3i/yiRcs9/fLY3S9k2nY.B5wt1xsE.a', NULL, '334333333', '', '2019-03-14 05:25:38', '2019-03-14 05:25:38'),
(5, 'balotelli', 'ball@gmail.com', NULL, '$2y$10$W1mynGoTw829WoB6kgZZROp69JUPqSm0UI8.kbeeCUaByaQvuc5bi', NULL, '2345555', '', '2019-03-14 14:36:09', '2019-03-14 14:36:09'),
(6, 'cancelo', 'cancello@gmail.com', NULL, '$2y$10$Sb6zYlHhxorp2X2k6F2EuOw0LG3/x2prK9SAJfqIGy91d3VfYrZRi', NULL, '3423454', '', '2019-03-14 14:36:37', '2019-03-14 14:36:37'),
(7, 'cancelo', 'barack@gmail.com', NULL, '$2y$10$UrySrNa0EXkjkpiTDH5l9OlaVyw.LWyqbcYuZbx/ZGEYuNCGo4wrm', NULL, '345432455', '', '2019-03-14 14:36:55', '2019-03-14 14:36:55'),
(8, 'francesco totti', 'francesco@gmail.com', NULL, '$2y$10$G5zA22ui6W25TwKR3dLHP.7NgRNRlCmx4UobVazhwb/A7sNLpIVre', NULL, '2345433', '', '2019-03-14 14:37:25', '2019-03-14 14:37:25'),
(9, 'lionel messi', 'lionel@gmail.com', NULL, '$2y$10$OJp17KoFd00i/M370r2T9OJrg46oiUFNEP6.lYhZUXa18P/93iBqy', NULL, '334555943', '', '2019-03-14 14:37:50', '2019-03-14 14:37:50'),
(10, 'hazard', 'hazard@gmail.com', NULL, '$2y$10$LVlHnOfKaHVAfFdiABA0l.6E548eStgUQlHgw.X4vpNAYq4Nh4xW.', NULL, '334555943', '', '2019-03-14 14:38:11', '2019-03-14 14:38:11'),
(11, 'xx', 'a@gmail.com', NULL, '$2y$10$WZljre0WD9Iph19xTx/wW.sZyp5a4udVMJ3JvoJbe/YxpIW54T8iC', NULL, '22222223', '', '2019-03-15 15:00:01', '2019-03-15 15:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `financial_discipline`
--

CREATE TABLE `financial_discipline` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` int(191) NOT NULL,
  `user_id` int(191) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fine` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remaining_balance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_of_the_month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_punishement_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_complaints` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_discipline`
--

INSERT INTO `financial_discipline` (`id`, `task_id`, `user_id`, `name`, `salary`, `fine`, `remaining_balance`, `day_of_the_month`, `day`, `month`, `year`, `staff_punishement_type`, `admin_complaints`, `created_at`, `updated_at`) VALUES
(9, 11, 16, 'camiaono', '40000', '1290', '38710', '25th', 'Monday', 'March', '2019', 'incompetence', 'your sick abiii', '2019-03-25 02:21:49', '2019-03-25 02:22:07'),
(10, 4, 13, 'candreva', '79998', '2580', '77418', '23rd', 'Saturday', 'March', '2019', 'incompetence', NULL, '2019-03-23 22:13:10', '2019-03-23 22:13:10'),
(11, 4, 19, 'camaronesi', '30000', '967', '29033', '23rd', 'Saturday', 'March', '2019', 'incompetence', NULL, '2019-03-23 22:13:10', '2019-03-23 22:13:10'),
(12, 4, 17, 'fever', '40000', '1290', '38710', '23rd', 'Saturday', 'March', '2019', 'incompetence', NULL, '2019-03-23 22:13:10', '2019-03-23 22:13:10'),
(112, 11, 14, 'barecho', '40000', '1290', '38710', '25th', 'Monday', 'March', '2019', 'incompetence', 'your sick abiii', '2019-03-25 02:21:49', '2019-03-25 02:22:08'),
(114, 11, 17, 'fever', '40000', '1290', '38710', '25th', 'Monday', 'March', '2019', 'incompetence', 'your sick abiii', '2019-03-25 02:21:49', '2019-03-25 02:22:08'),
(115, 59, 19, 'camaronesi', '30000', '967', '29033', '26th', 'Tuesday', 'March', '2019', 'incompetence', 'simply lazy towards coming to work', '2019-03-26 12:33:42', '2019-03-26 12:34:09'),
(116, 59, 16, 'camiaono', '40000', '1290', '38710', '26th', 'Tuesday', 'March', '2019', 'incompetence', 'simply lazy towards coming to work', '2019-03-26 12:33:42', '2019-03-26 12:34:09'),
(119, 74, 20, 'pantero', '30000', '1000', '29000', '2nd', 'Tuesday', 'April', '2019', 'incompetence', 'black and yellow', '2019-04-02 19:07:20', '2019-04-02 19:07:42'),
(120, 74, 19, 'camaronesi', '30000', '1000', '29000', '2nd', 'Tuesday', 'April', '2019', 'incompetence', 'black and yellow', '2019-04-02 19:07:20', '2019-04-02 19:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_03_11_041403_create_admins_table', 1),
(4, '2019_03_11_042613_create_tasks_table', 1),
(5, '2019_03_23_223603_create_financial_discipline_table', 2),
(6, '2019_04_02_145903_create_staff_login_detail_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_login_detail`
--

CREATE TABLE `staff_login_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_recieve_push` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_login_detail`
--

INSERT INTO `staff_login_detail` (`id`, `staff_id`, `staff_name`, `time`, `day`, `date`, `month`, `year`, `admin_recieve_push`, `created_at`, `updated_at`) VALUES
(1, 17, 'fever', '01:13:41', 'Thursday', '4', 'April', '2019', 1, '2019-04-03 23:13:41', '2019-04-03 23:13:41'),
(2, 4, 'kamzy', '03:41:02', 'Thursday', '4', 'April', '2019', 1, '2019-04-04 01:41:02', '2019-04-04 01:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_header` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_assigned` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_content` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attached_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` int(191) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_header`, `users_assigned`, `task_content`, `attached_file`, `approval_status`, `created_at`, `updated_at`) VALUES
(59, 'test this book and see', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'ball in your court', '5c98e59d2b61c_155352412520190325_TASK.jpg', 1, '2019-03-15 15:12:47', '2019-03-26 12:33:42'),
(60, 'test this book and see', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'ball in your court', '5c98e62409569_155352426020190325_TASK.jpg', 0, '2019-03-25 13:31:00', '2019-03-25 13:31:00'),
(61, 'test this book and see', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'ball in your court', '5c98e6472b6c7_155352429520190325_TASK.jpg', 0, '2019-03-25 13:31:35', '2019-03-25 13:31:35'),
(62, 'def', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'defiant fellows', '5c98e8d0da346_155352494420190325_TASK.jpg', 0, '2019-03-25 13:42:25', '2019-03-25 13:42:25'),
(63, 'def', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'defiant fellows', '5c98e8e60af3b_155352496620190325_TASK.jpg', 0, '2019-03-25 13:42:46', '2019-03-25 13:42:46'),
(65, 'fall in ur smile', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"camiaono\",\"id\":16}]', 'fall in love with you', '5c991c879c05c_155353818320190325_TASK.jpg', 0, '2019-03-25 17:23:03', '2019-03-25 17:23:03'),
(67, 'caster', '[{\"name\":\"camaronesi\",\"id\":19},{\"name\":\"barecho\",\"id\":14},{\"name\":\"zooby\",\"id\":5},{\"name\":\"barecho\",\"id\":14},{\"name\":\"zooby\",\"id\":5}]', 'jesus tonight we decree', '5c993ba6b2719_155354615020190325_TASK.jpg', 0, '2019-03-25 19:35:50', '2019-03-25 19:35:50'),
(73, 'boss come and see ooooh how good the lord is oooooooh', '[{\"name\":\"camara\",\"id\":15},{\"name\":\"camiaono\",\"id\":16}]', 'hgfcvgfdcvc  yufhvfyhfdtt yudchcgnyf', '5c9a29688db1b_155360701620190326_TASK.JPG', 0, '2019-03-15 15:12:47', '2019-03-26 12:30:16'),
(74, 'cows and goats', '[{\"name\":\"pantero\",\"id\":20},{\"name\":\"camaronesi\",\"id\":19}]', 'booos come and see ooooh', '5c99bbf682b85_155357899820190326_TASK.JPG', 1, '2019-03-15 15:12:47', '2019-04-02 19:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fingerprint` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilephoto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `earning` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_Login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `fingerprint`, `profilephoto`, `earning`, `phone_number`, `first_login`, `last_Login`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'franklin', 'franklin2@gmail.com', NULL, '$2y$10$8OatY153OdOAruT.889oE.BE0P6yg8ceOe/hegNR3MqQe0QZsUWJy', NULL, 'default-avatar.png', '200000', '701034', NULL, NULL, NULL, '2019-03-15 15:12:47', '2019-03-15 15:12:47'),
(2, 'kelechi', 'kelechi@gmail.com', NULL, '$2y$10$ITZJlGrk.uwzAlQYVt3K/OKKRHoWpSCcMZQFvRDIvzyk9m7Jqn3e6', NULL, 'default-avatar.png', '20000', '7010342545', NULL, '2019-04-01 03:41:02', NULL, '2019-03-15 22:37:06', '2019-03-15 22:37:06'),
(4, 'kamzy', 'kamzy@gmail.com', NULL, '$2y$10$siWhifpm2jBs4WHVpWQm8.AGhY7ErguG6HG7EWaxNqh5CqPkfrLv6', NULL, 'default-avatar.png', '20000', '8033646254', '1', '2019-04-04 03:41:02', NULL, '2019-03-15 22:38:03', '2019-04-04 01:41:02'),
(5, 'zooby', 'zooby@gmail.com', NULL, '$2y$10$cOkyE73GAXQ72B1pw9pK0unjK.MFGDbejN1P5c1JfsRvHDMH.yOtG', NULL, 'default-avatar.png', '20000', '80333444554', NULL, NULL, NULL, '2019-03-15 22:38:25', '2019-03-15 22:38:25'),
(7, 'calistus', 'calistus@gmail.com', NULL, '$2y$10$YrPC9/kH1nfuG7u9eHG9nuVfWEuuZsp0iNSXJbREtXJG25RMqVx/G', NULL, 'default-avatar.png', '32234', '23344322222', NULL, '2019-04-01 03:41:02', NULL, '2019-03-15 22:44:27', '2019-03-15 22:44:27'),
(13, 'candreva', 'candreva@gmail.com', NULL, '$2y$10$SQtqe88bnAEoxj46RSPyoOEZSRxtcaynsPKnRCZ25yTHke1bWowfm', NULL, 'default-avatar.png', '79998', '2223322234', NULL, NULL, NULL, '2019-03-15 22:47:36', '2019-03-15 22:47:36'),
(14, 'barecho', 'barecho@gmail.com', NULL, '$2y$10$tifFBGRr2s5it3cGaUR3mewKa6CVa0x2CBB/ZgzaS9lBRPASjhELW', NULL, 'default-avatar.png', '40000', '23345444', NULL, NULL, NULL, '2019-03-17 14:45:04', '2019-03-17 14:45:04'),
(15, 'camara', 'camara@gmail.com', NULL, '$2y$10$N89411kSpQ8E8b1Nhxrn4.XY4f8468KmHOnAfKwd5tMnVmmVYXwwu', NULL, 'default-avatar.png', '40000', '2344322', NULL, NULL, NULL, '2019-03-17 14:45:36', '2019-03-17 14:45:36'),
(16, 'camiaono', 'camiaono@gmail.com', NULL, '$2y$10$hTWgKOicJwPYnn6y8KNPQudvxnLen1hDseXnX7sUHp6DVXbivR6BS', NULL, 'default-avatar.png', '40000', '2344322', NULL, NULL, NULL, '2019-03-17 14:46:01', '2019-03-17 14:46:01'),
(17, 'fever', 'fever@gmail.com', NULL, '$2y$10$bFiGbfhb6E0L8T/CDnKikethG5fIrrKGUB6.IiCmuN/JxypyqSm4W', NULL, 'default-avatar.png', '40000', '2344322', '1', '2019-04-04 01:13:41', NULL, '2019-03-17 14:46:18', '2019-04-03 23:13:41'),
(18, 'close', 'close@gmail.com', NULL, '$2y$10$RZz8f3eeCBeWrjhKb0OB6.IFt642WF4aWcTk8UXqCx06bEkYRjAn6', NULL, 'default-avatar.png', '40000', '2344322', NULL, NULL, NULL, '2019-03-17 14:46:32', '2019-03-17 14:46:32'),
(19, 'camaronesi', 'camaronesi@gmail.com', NULL, '$2y$10$SJSw.odnDcVwVkjUa8Ai8OrDfqAB5PL3Gub9DJkpyQ3shdF/QHKV2', NULL, 'default-avatar.png', '30000', '233444454', NULL, NULL, NULL, '2019-03-19 10:08:08', '2019-03-19 10:08:08'),
(20, 'pantero', 'pantero@gmail.com', NULL, '$2y$10$m4c1XFpgfa56SEIezI3.xOxVLPVjSPxxnEhtFHqn8SzKQeoQP0WL.', NULL, 'default-avatar.png', '30000', '3344556666', NULL, NULL, NULL, '2019-03-19 10:57:10', '2019-03-19 10:57:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `financial_discipline`
--
ALTER TABLE `financial_discipline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `staff_login_detail`
--
ALTER TABLE `staff_login_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `financial_discipline`
--
ALTER TABLE `financial_discipline`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff_login_detail`
--
ALTER TABLE `staff_login_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
