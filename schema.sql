-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2022 at 10:38 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schema.sql`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(9) NOT NULL,
  `title` varchar(16) NOT NULL,
  `firstname` varchar(16) NOT NULL,
  `lastname` varchar(16) NOT NULL,
  `email` varchar(16) NOT NULL,
  `telephone` varchar(9) NOT NULL,
  `company` varchar(24) NOT NULL,
  `type` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `assigned_to` int(9) NOT NULL,
  `created_by` int(9) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(9) NOT NULL,
  `contact_id` int(9) NOT NULL,
  `commemt` text NOT NULL,
  `created_by` int(9) NOT NULL,
  `created_at` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(9) NOT NULL,
  `firstname` varchar(16) NOT NULL,
  `lastname` varchar(16) NOT NULL,
  `password` varchar(24) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` varchar(16) NOT NULL,
  `created_at` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'Admin', 'User', 'bed4efa1d4fdbd954bd3705d', 'admin@project2.com', 'admin', '2022-11-29 16:38:17.000000');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
