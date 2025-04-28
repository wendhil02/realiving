-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 06:53 AM
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
-- Database: `realivingdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$1o0XmgtW/aTWg1rQvOyKVOO/H1zpkiw7bQS5ev.n7gFHudMLvmbDO', '2025-04-22 04:56:12');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `time` varchar(10) DEFAULT NULL,
  `status` enum('pending','done') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `title`, `appointment_date`, `time`, `status`) VALUES
(13, 'test', '2025-04-23', '14:51', 'done'),
(14, 'test', '2025-04-23', '14:56', 'done'),
(15, 'test', '2025-04-24', '15:01', 'done'),
(16, 'test', '2025-04-23', '16:01', 'done'),
(17, 'test1', '2025-04-23', '17:00', 'done');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `date_time` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `name`, `email`, `phone`, `date_time`, `created_at`, `status`) VALUES
(11, 'Art Lorenz Jimenez', 'noblehomeconst.ph@gmail.com', '09382041746', '2025-05-02 17:30', '2025-04-24 05:06:16', 'approved'),
(12, 'Lebron James', 'noblehomeconst.ph@gmail.com', '09685916544', '2025-05-07 15:00', '2025-04-24 05:23:38', 'approved'),
(13, 'Lolo Bronny ', 'jinggoyestrada@gmail.com', '099132421651', '2025-06-21 12:52', '2025-04-25 22:09:21', 'approved'),
(15, 'test12333', 'noblehomeconst.ph@gmail.com', '09685916544', '2025-04-30 12:00', '2025-04-26 02:14:45', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`id`, `name`, `email`, `phone`, `message`, `created_at`, `product_name`, `is_read`) VALUES
(17, 'lucky day', 'artpogi@gmail.com', '09913242651', '123123213213asdasdasd', '2025-04-22 23:53:09', 'SPC flooring', 1),
(18, 'Lebron James', 'noblehomeconst.ph@gmail.com\n', '09123546789', 'hello i would like to order', '2025-04-23 00:55:12', 'Ceiling Panel', 1),
(20, 'Kyrie Irving', 'lorenzjimenez22@gmail.com', '9913242651', 'testing lang', '2025-04-25 04:11:48', 'contact', 1),
(21, 'Steph Curry', 'james.harden@hotmail.com', '99132421651', 'basketball', '2025-04-25 04:13:32', 'contact', 1),
(22, 'James Hardin tagalog', 'kidkulafoo@gmail.com', '09913242651', 'Magandang araw po!\r\n\r\nAko po si James Hardin at interesado akong malaman ang higit pang detalye tungkol sa isa sa inyong mga produkto. Maaari po ba akong humingi ng karagdagang impormasyon ukol sa availability, presyo, at shipping options?\r\n\r\nMaraming sal', '2025-04-25 22:07:53', 'WPC Indoor Fluted Wall Panel ', 1),
(23, 'Art Lorenz Brrrr', 'bpldomestics@gmail.com', '09685916544', '123', '2025-04-26 02:14:14', 'PVC Ceiling Panel', 1),
(24, 'test123', 'test123@gmail.com', '9913242651', 'test123', '2025-04-26 02:15:14', 'contact', 1),
(25, 'noblehome', 'bpldomestics@gmail.com', '09685916544', 'hello!', '2025-04-26 03:59:54', 'Laminated Board', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_replies`
--

CREATE TABLE `inquiry_replies` (
  `id` int(11) NOT NULL,
  `inquiry_id` int(11) DEFAULT NULL,
  `reply_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry_replies`
--

INSERT INTO `inquiry_replies` (`id`, `inquiry_id`, `reply_message`, `created_at`) VALUES
(1, 18, 'hello', '2025-04-24 02:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `summary`, `image`, `link`, `created_at`) VALUES
(1, 'OTSP', 'counter top kitchen and closet', '../uploads/hometwo.jpg', 'http://localhost/realiving/code/index.php', '2025-04-25 00:00:54'),
(2, 'OTSPS', 'counter top kitchen and closets', '../uploads/118070812_195140055295308_6239049302229042686_n.jpg', 'http://localhost/realiving/code/index.php', '2025-04-25 00:03:43'),
(3, 'OTSPS', 'counter top kitchen and closets', '../uploads/118070812_195140055295308_6239049302229042686_n.jpg', 'http://localhost/realiving/code/index.php', '2025-04-25 00:04:10'),
(4, 'OTSPS', 'counter top kitchen and closets', '../uploads/118070812_195140055295308_6239049302229042686_n.jpg', 'http://localhost/realiving/code/index.php', '2025-04-25 00:04:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `weight` varchar(55) NOT NULL,
  `material_type` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `brand_name`, `description`, `unit_of_measure`, `weight`, `material_type`, `image`, `image2`, `image3`) VALUES
(18, 'Laminated Board', 'Board', 'Noblehome', 'Durable and waterproof board, ideal for marine applications. Resistant to saltwater and extreme weather conditions, perfect for use in boat construction, docks, and other outdoor structures.', 'Piece', '15 kg', 'Marine-grade plywood or composite', '680ad979a3d87.jpg', '680ad979a4565.png', '2.png'),
(19, 'PVC Ceiling Panels', 'Ceiling Materials', 'Noblehome', 'Durable and lightweight ceiling panel, 250mm wide and 7mm thick, ideal for residential and commercial interiors. Waterproof, termite-proof, and easy to install.', 'Piece', '1.2 kg', 'Polyvinyl Chloride (PVC)', '680ae15f6421b.jpg', '680ae15f64991.jpg', '680ae15f64f71.jpg'),
(22, 'WPC Indoor Fluted Wall Panel ', 'Wall Cladding / Decorative Panels', 'Noblehome', 'Elegant and durable WPC fluted panel designed for interior walls. Size: 219mm wide x 26mm thick. Offers a natural wood-like appearance with modern acoustic benefits. Ideal for living rooms, offices, and commercial interiors. Water-resistant, termite-resistant, and eco-friendly.\r\n', 'Piece', '3.8 kg', 'Wood-Plastic Composite (WPC)', '680ae4454f60a.jpg', 'asasd.jpg', ''),
(23, 'WPC Outdoor Wall Cladding', 'Exterior Wall Cladding', 'Noblehome', 'Weather-resistant WPC wall cladding designed for exterior walls and facades. Size: 219mm wide x 26mm thick. Combines the appearance of natural wood with the durability of plastic. UV-protected, termite-proof, low maintenance, and environmentally friendly—perfect for villas, resorts, and commercial exteriors.', 'Piece', '4.2 kg', 'Wood-Plastic Composite (WPC)', '680ae58476298.jpg', '680ae5847686f.jpg', '680ae58476e6d.jpg'),
(24, 'WPC Timber', 'Decking / Structural Timber', 'Noblehome', 'Heavy-duty WPC timber plank ideal for both indoor and outdoor applications such as decking structures, pergolas, fences, and architectural details. Size: 100mm x 50mm. Resistant to rot, insects, and moisture. Maintains a natural wood look with less maintenance. Eco-friendly and easy to work with using conventional tools.', 'Piece', '5.5 kg', 'Wood-Plastic Composite (WPC)', '680ae7dc27735.jpg', '680ae7dc2846e.jpg', ''),
(25, 'WPC Decorative Tube', 'Decorative Tube / Structural Profiles', 'Noblehome', 'Hollow-core WPC tube used for indoor and outdoor architectural accents, fencing, screens, and partitions. Size: 50mm x 100mm. Lightweight, durable, water- and UV-resistant. Gives a modern wooden aesthetic with easy maintenance. Ideal for exterior facades or indoor accent designs.\r\n', 'Piece', '3.2 kg', 'Wood-Plastic Composite (WPC)', '680ae8b2f33bc.jpg', '680ae8b2f41cd.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `step_updates`
--

CREATE TABLE `step_updates` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `step` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `step_updates`
--

INSERT INTO `step_updates` (`id`, `client_id`, `step`, `update_time`, `end_date`, `description`) VALUES
(1, 5, 5, '2025-04-24 10:49:00', NULL, NULL),
(3, 5, 7, '2025-04-24 00:09:00', NULL, NULL),
(4, 5, 1, '2025-04-24 11:51:00', NULL, NULL),
(5, 5, 7, '2025-04-24 11:58:00', '2025-04-26', NULL),
(6, 5, 2, '2025-04-24 13:03:00', NULL, NULL),
(7, 5, 10, '2025-04-24 13:17:00', NULL, NULL),
(8, 5, 6, '2025-04-24 02:41:00', '2025-04-24', '122mm marine board'),
(12, 5, 9, '2025-04-25 15:49:00', '2025-04-25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `clientname` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatestatus` int(11) DEFAULT 1,
  `update_time` datetime DEFAULT current_timestamp(),
  `nameproject` varchar(255) NOT NULL,
  `reference_number` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `clientname`, `status`, `created_at`, `updatestatus`, `update_time`, `nameproject`, `reference_number`) VALUES
(4, 'noble', 'New Client', '2025-04-24 01:21:44', 0, '2025-04-24 03:21:44', 'spiral building', 'REF20250424032144921A'),
(5, 'mr june', 'New Client', '2025-04-24 02:47:27', 2, '2025-04-24 04:47:27', 'table', 'REF202504240447273716'),
(7, 'we', 'New', '2025-04-27 08:35:06', 0, '2025-04-27 10:35:06', 'test', 'REF2025042710350663F9'),
(8, 'mio', 'Old Client', '2025-04-27 08:35:24', 0, '2025-04-27 10:35:24', 'test', 'REF20250427103524EBEB');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inquiry_id` (`inquiry_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `step_updates`
--
ALTER TABLE `step_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `step_updates`
--
ALTER TABLE `step_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inquiry_replies`
--
ALTER TABLE `inquiry_replies`
  ADD CONSTRAINT `inquiry_replies_ibfk_1` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiry` (`id`);

--
-- Constraints for table `step_updates`
--
ALTER TABLE `step_updates`
  ADD CONSTRAINT `step_updates_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `user_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
