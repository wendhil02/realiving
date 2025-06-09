-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 03:36 AM
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
-- Database: `realivingupdated`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `phone`, `email`, `subject`, `message`) VALUES
(6, 'artlorenz', '09913242651', 'achielle14@gmail.com\n', 'BPL INTERNATIONAL CORPORATION has responded to your application for Project Coordinator (Constructi…', 's'),
(11, 'Art Lorenz Jimenez', '09913242651', 'artlorenzpogi123@gmail.com', 'BPL INTERNATIONAL CORPORATION has responded to your application for Project Coordinator (Constructi…', 'asdasd'),
(12, 'Art Lorenz Jimenez', '09913242651', 'doreysmoleyna@gmail.com', 'BPL INTERNATIONAL CORPORATION has responded to your application for Project Coordinator (Constructi…', 'asd'),
(13, 'Achielle T. Villanueva', '09913245354', 'diwatapares@gmail.com', 'diwata!', 'diwata kung akoy tawagin ');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(55) NOT NULL,
  `date_uploaded` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `category`, `description`, `image`, `date_uploaded`) VALUES
(5, 'Transform Your Space with Realiving Design Center\r\n', 'Business', 'Realiving Design Center offers innovative interior design solutions for homes, offices, and commercial spaces, blending style and functionality to create the perfect environment tailored to your needs.\r\n\r\n', './images/alphaland-1.jpg', '2025-05-01 03:19:48'),
(6, 'Where Design Meets Comfort\r\n', 'Lifestyle', 'Redefine your living or working space with Realiving Design Center. We create interiors that prioritize comfort, beauty, and practicality, ensuring your space feels as good as it looks.', './images/alphaland-2.jpg', '2025-05-01 03:19:48'),
(9, 'Crafting Unique Spaces with Realiving\r\n', 'Design & Architecture\r\n', 'From custom furniture to full-scale renovations, Realiving Design Center specializes in creating one-of-a-kind spaces that match your personal style while incorporating the latest design trends and sustainable practices.\r\n\r\n', './images/alphaland-3.jpg', '2025-05-01 03:19:48'),
(12, 'Interior Design as a Form of Self-Expression', 'Arts & Culture', 'At Realiving Design Center, we believe every space tells a story. Our design process is rooted in creativity, culture, and identity—bringing depth and meaning to your interiors.\r\n\r\n', './images/background-image2.jpg', '2025-05-14 02:47:38'),
(13, 'Smart Design Meets Modern Living', 'Technology & Innovation', 'We incorporate modern technology and sustainable materials into our designs, making Realiving Design Center a hub for smart, future-ready interior solutions.\r\n\r\n', './images/alphaland-3.jpg', '2025-05-14 02:48:21'),
(14, 'Collaboration is at the Heart of Our Design', 'Creative Industry', 'With every project, Realiving Design Center partners with skilled artisans, fabricators, and designers to bring original and collaborative concepts to life.\r\n\r\n', './images/alphaland-2.jpg', '2025-05-14 02:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `title`, `address`, `description`, `main_image`, `image1`, `image2`, `image3`) VALUES
(1, 'Tourism Boost', 'Manila, Philippines', 'The tourism sector in the Philippines is booming.', 'images/alphaland-2.jpg', 'images/alphaland-project-1.png', 'images/alphaland-project-2.png', 'images/alphaland-project-2.png'),
(2, 'New Bridge Project', 'Cebu City, Philippines', 'A new bridge connecting islands is now under construction.', 'images/alphaland-2.jpg', 'images/alphaland-project-1.png', 'images/alphaland-project-1.png', 'images/alphaland-project-1.png'),
(3, 'Festival of Colors', 'Davao City, Philippines', 'Davao celebrates with vibrant festivals and parades.', 'images/alphaland-2.jpg', 'images/alphaland-project-1.png', 'images/alphaland-project-1.png', 'images/alphaland-project-1.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
