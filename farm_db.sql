-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 14 نوفمبر 2026 الساعة 11:33
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farm_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `farmers`
--

INSERT INTO `farmers` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'bushra', 'majalibushra23@gmail.com', '$2y$10$2TkmNy3Mldz5O9S346QxGubq1XfqKgXuz4x3/uGlFbyYK6iXeSPSy', '2026-11-13 10:19:27'),
(2, 'bushra@bhh', 'bushra@bhh', '$2y$10$qvwegrMSA/.JtEe3dXBvWuxqmmx7qPErrI.D0QQt1djgXmWMJPn2C', '2026-11-14 01:43:33'),
(3, 'Bushra @123', 'bushramajali831@gmail.com', '$2y$10$Lk2ajRBlQCjMM1hH6FbpOOj/5Isp86.mqyafEb/wXE78Wc6BNIYAW', '2026-11-14 01:55:58'),
(4, 'rewa', 'rewamajali098@gmail.com', '$2y$10$ozjMidnVNqK1O6CuHUmjkulKaau5N2SR4u1qYr/1c2/1FfAquqv92', '2026-11-14 05:53:19');

-- --------------------------------------------------------

--
-- بنية الجدول `lands`
--

CREATE TABLE `lands` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `land_name` varchar(150) DEFAULT NULL,
  `land_number` varchar(100) DEFAULT NULL,
  `land_area` decimal(12,3) DEFAULT NULL,
  `area_unit` varchar(30) DEFAULT NULL,
  `location_text` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `soil_type` varchar(100) DEFAULT NULL,
  `current_crop` varchar(150) DEFAULT NULL,
  `previous_crop` varchar(150) DEFAULT NULL,
  `future_crop` varchar(150) DEFAULT NULL,
  `farming_goal` varchar(100) DEFAULT NULL,
  `avoid_items` text DEFAULT NULL,
  `water_availability` varchar(50) DEFAULT NULL,
  `water_quantity` decimal(12,3) DEFAULT NULL,
  `water_unit` varchar(30) DEFAULT NULL,
  `nearby_river` varchar(30) DEFAULT NULL,
  `nearby_wastewater` varchar(30) DEFAULT NULL,
  `flood_risk` varchar(30) DEFAULT NULL,
  `environmental_notes` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `lands`
--
ALTER TABLE `lands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_farmer_id` (`farmer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lands`
--
ALTER TABLE `lands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `lands`
--
ALTER TABLE `lands`
  ADD CONSTRAINT `fk_lands_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
