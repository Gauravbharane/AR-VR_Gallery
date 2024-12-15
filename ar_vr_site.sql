-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 05:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ar_vr_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `model_link` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) NOT NULL DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `model_link`, `created_at`, `category`) VALUES
(1, 'astronaut', 'a astronaut', 'https://cdn.glitch.com/36cb8393-65c6-408d-a538-055ada20431b/Astronaut.glb', '2024-12-15 08:10:11', 'Uncategorized'),
(2, 'mazada car', 'car', 'https://cdn.glitch.me/e18bd9fe-5ad8-4c58-8c8d-b23012ed844d/mazda_rx-7_tuned.glb?v=1734255192750', '2024-12-15 09:34:25', 'Uncategorized'),
(3, 'Earth Model', 'Earth 3D model', 'https://cdn.glitch.global/e18bd9fe-5ad8-4c58-8c8d-b23012ed844d/earths_vegetation_index_low_poly.glb?v=1734255488122', '2024-12-15 09:38:40', 'Uncategorized'),
(4, 'Statue of Liberty', 'Statue of liberty 3d Model Low Poly', 'https://cdn.glitch.global/e18bd9fe-5ad8-4c58-8c8d-b23012ed844d/liberty_island.glb?v=1734255992603', '2024-12-15 09:47:29', 'Uncategorized'),
(5, 'honda_twister_300 bike', 'honda_twister_300 bike', 'https://cdn.glitch.me/e18bd9fe-5ad8-4c58-8c8d-b23012ed844d/honda_twister_300.glb?v=1734255989354', '2024-12-15 09:48:38', 'Uncategorized'),
(6, 'Small Road City', 'Small Road City Model', 'https://cdn.glitch.global/e18bd9fe-5ad8-4c58-8c8d-b23012ed844d/city.glb?v=1734256290757', '2024-12-15 09:52:13', 'Uncategorized');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(2, 'gaurav', 'gaurav@gmail.com', '$2y$10$XBRjmqSkcDZetMHc.oc3mu2Vyl8aFik8gbn.p7ui/Csi8JpA9s7S6'),
(3, 'admin', 'admin@gmail.com', '$2y$10$7sBixmDDbyPlJdXvG7B/DOuuJaNJOybV5j19Bto8HfszEityBsY1u');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
