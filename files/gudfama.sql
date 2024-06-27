-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 07:30 AM
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
-- Database: `gudfama`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminuser`
--

CREATE TABLE `adminuser` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('staff','pr','accounts') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminuser`
--

INSERT INTO `adminuser` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'emmzy', '497911', 'staff'),
(2, 'emmzy', '497911', 'pr'),
(3, 'emmzy', '497911', 'accounts');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `paymentPlan` varchar(255) NOT NULL,
  `totalCost` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `returns` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract`
--

INSERT INTO `contract` (`id`, `user_id`, `full_name`, `address`, `email`, `package`, `paymentPlan`, `totalCost`, `amount_paid`, `payment_date`, `returns`) VALUES
(1, 8, 'Emmanuel Amadi', '09056897434', 'emmco96@gmail.com', '10 Fishes', 'full_payment', 13100.00, 65500.00, '2024-05-27', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publickey`
--

CREATE TABLE `publickey` (
  `public_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publickey`
--

INSERT INTO `publickey` (`public_key`) VALUES
('FLWPUBK_TEST-f92e874839fb45102e9c7e53e3d84695-X');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `confirmation` enum('unconfirmed','confirmed','unsuccessful') NOT NULL DEFAULT 'unconfirmed',
  `payment_date` date DEFAULT NULL,
  `package` varchar(255) NOT NULL,
  `payment_plan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `full_name`, `email`, `phone`, `amount_paid`, `receipt`, `confirmation`, `payment_date`, `package`, `payment_plan`) VALUES
(1, 'John Doe', 'emmco96@gmail.com', '123456789', 13100.00, '../uploads/IMG_20221127_091335_112.jpg', 'confirmed', '1995-09-09', '', ''),
(3, 'Solomon Nwabueze', 'asolohii@gmail.com', '07057199111', 3275.00, '../uploads/blessing.jpg', 'confirmed', '2024-05-31', '10', 'Weekly_Payment');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `referrer_email` varchar(255) NOT NULL,
  `referred_email` varchar(255) NOT NULL,
  `referred_name` varchar(255) NOT NULL,
  `referred_phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `paymentPlan` varchar(255) NOT NULL,
  `totalCost` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `sign_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `full_name`, `phone`, `email`, `package`, `paymentPlan`, `totalCost`, `amount_paid`, `payment_date`, `sign_picture`) VALUES
(4, 8, 'Emmanuel Amadi', '09056897434', 'emmco96@gmail.com', '10 Fishes', 'full_payment', 13100.00, 52400.00, '2024-05-23', ''),
(5, 8, 'Emmanuel Amadi', '09056897434', 'emmco96@gmail.com', '10 Fishes', 'full_payment', 13100.00, 65500.00, '2024-05-27', ''),
(8, 11, 'Solomon Nwabueze', '07057199111', 'asolohii@gmail.com', '10 Fishes', 'Monthly_Payment', 13100.00, 3275.00, '2024-05-31', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subscription` enum('yes','no') NOT NULL,
  `dob` date NOT NULL,
  `state_of_residence` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `how_did_you_hear_about_us` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `accountName` varchar(255) NOT NULL,
  `bankName` varchar(255) NOT NULL,
  `accountNumber` varchar(20) NOT NULL,
  `package` varchar(255) NOT NULL,
  `paymentPlan` varchar(255) NOT NULL,
  `totalCost` decimal(10,2) NOT NULL,
  `amountPaid` decimal(10,2) NOT NULL,
  `amountToPay` decimal(10,2) NOT NULL,
  `referralLink` varchar(255) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `confirmation` enum('unconfirmed','confirmed','unsuccessful') NOT NULL DEFAULT 'unconfirmed',
  `payment_date` date DEFAULT NULL,
  `batch` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `totalreturn` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `subscription`, `dob`, `state_of_residence`, `password`, `how_did_you_hear_about_us`, `profile_picture`, `registration_date`, `accountName`, `bankName`, `accountNumber`, `package`, `paymentPlan`, `totalCost`, `amountPaid`, `amountToPay`, `referralLink`, `receipt`, `terms_accepted`, `confirmation`, `payment_date`, `batch`, `address`, `totalreturn`) VALUES
(8, 'Emmanuel Amadi', 'emmco96@gmail.com', '09056897434', 'yes', '1996-09-09', 'Rivers state', '$2y$10$6aEJsaX6KhKrzAIOrRwGIu12QqicLKNeyfAvuAnKaRr.zv/VrE8fC', 'Facebook', 'uploads/IMG_20230101_003151_953~2 - Copy.jpg', '2024-05-21 16:06:16', '', '', '', '10 Fishes', 'full_payment', 13100.00, 183400.00, 13100.00, 'http://gudfama.com/register.php?ref=gVyP6K0Jkm', '../uploads/advert.png', 1, 'confirmed', '2024-05-31', 'June', '', 19000.00),
(11, 'Solomon Nwabueze', 'asolohii@gmail.com', '07057199111', 'yes', '2024-05-31', 'Rivers state', '$2y$10$RXDyBZCY5qMUzMs1nSIhAuGlVW51Mb3ElnL6U6vaV38f31SYzLDk6', 'Facebook', 'uploads/blessing2-removebg-preview.png', '2024-05-31 14:04:36', 'solomon', 'UBA', '0987654', '10 Fishes', 'Weekly_Payment', 13100.00, 13100.00, 3275.00, 'http://gudfama.com/register.php?ref=kM2tP0gPkR', '../uploads/fish1.png', 1, 'confirmed', '2024-05-31', 'June', '27 Elf Road Trans Amadi Industrial Layout', 19000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminuser`
--
ALTER TABLE `adminuser`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminuser`
--
ALTER TABLE `adminuser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
