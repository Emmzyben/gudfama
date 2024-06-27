-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: sdb-72.hosting.stackcp.net
-- Generation Time: May 22, 2024 at 02:38 PM
-- Server version: 10.6.17-MariaDB-log
-- PHP Version: 7.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudfama-35303631fafd`
--

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
  `referralLink` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `subscription`, `dob`, `state_of_residence`, `password`, `how_did_you_hear_about_us`, `profile_picture`, `registration_date`, `accountName`, `bankName`, `accountNumber`, `package`, `paymentPlan`, `totalCost`, `amountPaid`, `amountToPay`, `referralLink`) VALUES
(8, 'Emmanuel Amadi', 'emmanuel.amadi@gudfama.com', '09056897432', 'no', '1996-04-04', 'Rivers state', '$2y$10$MI2ZheITjHz33l//4R5fwe2rasO5fd4QCbAFDFzkw3mKvNjWHf0um', 'Facebook', 'uploads/20221217_170608.jpg', '2024-05-22 11:46:11', '', '', '', '', '', '0.00', '0.00', '0.00', 'http://gudfama.com/register.php?ref=k4XHA1lgte');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
