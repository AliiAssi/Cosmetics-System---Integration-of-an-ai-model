-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 01:53 PM
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
-- Database: `cosmetics`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `picture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`_id`, `title`, `picture`) VALUES
(1, 'hello beautiful', 'banner-img.png'),
(3, 'hi queen', 'image_66798f1342551_newbanner.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `_id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `_id` int(11) NOT NULL,
  `feedBack` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `howMuch` float NOT NULL,
  `acceptance` int(11) NOT NULL DEFAULT 100,
  `homeDisplayed` int(11) NOT NULL DEFAULT 0,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`_id`, `feedBack`, `createdAt`, `howMuch`, `acceptance`, `homeDisplayed`, `userId`) VALUES
(22, 'nice products', '2024-06-23 18:25:25', 50, 100, 1, 27),
(23, 'that is wonderful guys', '2024-06-24 19:15:20', 100, 100, 1, 25);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `_id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` enum('seen','unseen','deleted') NOT NULL DEFAULT 'unseen',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`_id`, `userId`, `productId`, `content`, `status`, `createdAt`) VALUES
(5, 25, 12, 'Product details updated. Please review the changes.', 'seen', '2024-06-24 18:46:59'),
(6, 25, 12, 'Product details updated. Please review the changes.', 'seen', '2024-06-24 19:10:51'),
(7, 26, 12, 'Product details updated. Please review the changes.', 'unseen', '2024-06-24 19:10:51'),
(8, 27, 12, 'Product details updated. Please review the changes.', 'unseen', '2024-06-24 19:10:51'),
(9, 25, 13, 'Product details updated. Please review the changes.', 'seen', '2024-06-24 19:12:53'),
(10, 26, 13, 'Product details updated. Please review the changes.', 'unseen', '2024-06-24 19:12:53'),
(11, 27, 13, 'Product details updated. Please review the changes.', 'unseen', '2024-06-24 19:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `_id` int(11) NOT NULL,
  `totale` float NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('placed','accepted','canceled','completed') NOT NULL DEFAULT 'placed',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`_id`, `totale`, `userId`, `status`, `createdAt`, `address`) VALUES
(33, 964.5, 25, 'completed', '2024-06-23 18:25:20', 'lebanon/beirut/hamra'),
(34, 290, 25, 'accepted', '2024-06-24 18:26:31', 'lebanon/beirut/hamra'),
(35, 14.25, 25, 'accepted', '2024-06-24 18:29:08', 'lebanon/beirut/hamra'),
(36, 13.35, 25, 'placed', '2024-06-24 19:13:22', 'lebanon/beirut/hamra'),
(37, 1350, 28, 'placed', '2024-06-24 23:51:23', 'Lebanon/baabda/myAreaaaaa'),
(38, 37.35, 25, 'placed', '2024-06-25 14:45:30', 'lebanon/beirut/hamra'),
(39, 56, 25, 'placed', '2024-06-25 14:45:44', 'lebanon/beirut/hamra'),
(40, 13.35, 25, 'placed', '2024-06-25 14:46:04', 'lebanon/beirut/hamra'),
(41, 13.35, 25, 'placed', '2024-06-25 14:46:18', 'lebanon/beirut/hamra'),
(42, 119.35, 27, 'placed', '2024-06-25 14:47:42', 'Lebanon/baabda/myAreaaaaa'),
(43, 24, 27, 'placed', '2024-06-25 14:47:55', 'Lebanon/baabda/myAreaaaaa'),
(44, 60, 27, 'placed', '2024-06-25 14:48:12', 'Lebanon/baabda/myAreaaaaa'),
(45, 65, 27, 'placed', '2024-06-25 14:48:43', 'Lebanon/baabda/myAreaaaaa'),
(46, 24, 27, 'placed', '2024-06-25 14:49:53', 'Lebanon/baabda/myAreaaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `_id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` float NOT NULL,
  `orderId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`_id`, `productId`, `qty`, `price`, `orderId`) VALUES
(41, 12, 2, 14.25, 33),
(42, 22, 2, 450, 33),
(43, 13, 2, 18, 33),
(44, 20, 1, 250, 34),
(45, 21, 1, 40, 34),
(46, 12, 1, 14.25, 35),
(47, 12, 1, 13.35, 36),
(48, 22, 3, 450, 37),
(49, 12, 1, 13.35, 38),
(50, 14, 1, 24, 38),
(51, 14, 1, 24, 39),
(52, 18, 1, 32, 39),
(53, 12, 1, 13.35, 40),
(54, 12, 1, 13.35, 41),
(55, 12, 1, 13.35, 42),
(56, 14, 1, 24, 42),
(57, 16, 1, 40, 42),
(58, 13, 1, 10, 42),
(59, 18, 1, 32, 42),
(60, 14, 1, 24, 43),
(61, 13, 2, 10, 44),
(62, 16, 1, 40, 44),
(63, 19, 1, 25, 45),
(64, 21, 1, 40, 45),
(65, 14, 1, 24, 46);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `discount` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `picture` varchar(100) NOT NULL,
  `description` varchar(50) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`_id`, `name`, `price`, `discount`, `categoryId`, `createdAt`, `picture`, `description`, `count`) VALUES
(12, 'Black Makeup Brush', 15, 11, 2, '2024-06-24 17:56:12', 'image_6679890c18a4d_Black Makeup Brush.jpg', 'wonderful !!!', 12),
(13, 'Bottle With Pink Liq', 20, 50, 3, '2024-06-24 17:57:10', 'image_6679894610112_Bottle With Pink Liquid.jpg', 'wonderful !!', 45),
(14, 'Pink Lipstick and Bl', 80, 70, 2, '2024-06-24 17:58:25', 'image_66798991ce910_Pink LipstickandBlushOn.jpg', 'it\'s a perfect product for your lips', 55),
(15, 'Sunglasses ', 80, 50, 6, '2024-06-24 17:59:05', 'image_667989b906ae1_Sunglasses .jpg', 'perfect Sunglasses  ^_^', 50),
(16, 'Bottle of Essential ', 80, 50, 3, '2024-06-24 18:02:18', 'image_66798a7a74470_Bottle ofEssentialOilinCloseupShot.jpg', 'perfect!', 48),
(17, 'golden oil', 80, 50, 4, '2024-06-24 18:05:40', 'image_66798b44dc76e_pexels-annpoan-5797999.jpg', 'the perfect for your sun care ^_^', 50),
(18, 'Clarins oil', 80, 60, 4, '2024-06-24 18:12:52', 'image_66798cf41378f_clarins oil.webp', 'for your sun care', 8),
(19, ' Hat and a Shawl on ', 50, 50, 6, '2024-06-24 18:14:38', 'image_66798d5e53b95_pexels-dorota-semla-1929451-8969278.jpg', 'bello', 49),
(20, 'chanel', 500, 50, 7, '2024-06-24 18:16:46', 'image_66798dde0b84a_pexels-kdjproductions-1557980.jpg', 'the best perfume ever', 4),
(21, 'queeny', 50, 20, 7, '2024-06-24 18:17:42', 'image_66798e167bcc4_pexels-valeriya-965993.jpg', 'the besty perfume ever', 18),
(22, 'make-up set', 500, 10, 2, '2024-06-24 18:20:39', 'image_66798ec74d8b6_pexels-suzyhazelwood-2536965.jpg', 'all in one', 0);

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE `productcategory` (
  `_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `available` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`_id`, `name`, `createdAt`, `available`) VALUES
(2, 'Makeup', '2024-04-12 00:11:00', 1),
(3, 'Skincare', '2024-04-12 00:11:00', 1),
(4, 'Sun Care', '2024-04-12 02:11:57', 1),
(5, 'Haircare', '2024-04-12 02:11:57', 1),
(6, 'Accessories', '2024-06-24 17:45:40', 1),
(7, 'Fragrances', '2024-06-24 17:47:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `_id` int(11) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `gmail` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `isAdmin` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `profilePicture` varchar(50) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`_id`, `firstName`, `lastName`, `gmail`, `password`, `isAdmin`, `status`, `createdAt`, `profilePicture`) VALUES
(25, 'gigi', 'assi', 'gigi@gmail.com', '$2y$10$Ruyj5JOMGm4RR3WrluXjaOsISf3.I.Y7h8Q2xgaA.CvQ7W5paFfqq', 0, 0, '2024-04-12 00:24:04', 'default.jpg'),
(26, 'adminfn', 'adminln', 'admin@gmail.com', '$2y$10$nQA5Z/7elgqChliKCQnP8uXNs/vOM2Ynru4UzK9iEd.zLBii72fIm', 1, 0, '2024-04-12 02:35:54', 'default.jpg'),
(27, 'my first name', 'my last name', 'email@gmail.com', '$2y$10$FgrqHi3NLA/jc76rr33tw.i3jC/03q0.CjZUlwb.CnGS0TmSaMR/W', 0, 0, '2024-06-23 18:23:53', 'default.jpg'),
(28, 'mira', 'shubaarfne', 'mira@gmail.com', '$2y$10$QFExWF1sd/KUrQ5rOYb/NuMDddekPjNde0KEuyUvZYbMo6So2.Xsm', 0, 0, '2024-06-24 23:35:31', 'image_6679d8f2a5a23_banner-img.png');

-- --------------------------------------------------------

--
-- Table structure for table `useradressdetails`
--

CREATE TABLE `useradressdetails` (
  `userId` int(11) NOT NULL,
  `country` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `area` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `useradressdetails`
--

INSERT INTO `useradressdetails` (`userId`, `country`, `city`, `area`) VALUES
(25, 'lebanon', 'beirut', 'hamra'),
(26, 'Lebanon', 'beirut', 'areai'),
(27, 'Lebanon', 'baabda', 'myAreaaaaa'),
(28, 'Lebanon', 'baabda', 'myAreaaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `userfavorite`
--

CREATE TABLE `userfavorite` (
  `_id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_order_notify`
--

CREATE TABLE `user_order_notify` (
  `_id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `content` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('seen','unseen') NOT NULL DEFAULT 'unseen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_order_notify`
--

INSERT INTO `user_order_notify` (`_id`, `userId`, `orderId`, `content`, `createdAt`, `status`) VALUES
(7, 25, 33, 'Your order (ID: 33) has been accepted.', '2024-06-24 18:25:44', 'seen'),
(8, 25, 33, 'Your order (ID: 33) has been completed.', '2024-06-24 18:26:03', 'seen'),
(9, 25, 34, 'Your order (ID: 34) has been accepted.', '2024-06-24 18:28:41', 'seen'),
(10, 25, 35, 'Your order (ID: 35) has been accepted.', '2024-06-24 19:13:34', 'seen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`_id`),
  ADD UNIQUE KEY `unique_product_user` (`productId`,`userId`),
  ADD KEY `fk_cart_user` (`userId`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_feedback_user` (`userId`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_notification_user` (`userId`),
  ADD KEY `fk_notification_product` (`productId`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_order_user` (`userId`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_orderItems_order` (`orderId`),
  ADD KEY `fk_orderItem_product` (`productId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_category` (`categoryId`);

--
-- Indexes for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `useradressdetails`
--
ALTER TABLE `useradressdetails`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `userfavorite`
--
ALTER TABLE `userfavorite`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_userfavorite_product` (`productId`),
  ADD KEY `fk_userfavorite_user` (`userId`);

--
-- Indexes for table `user_order_notify`
--
ALTER TABLE `user_order_notify`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `userRlt` (`userId`),
  ADD KEY `orderRlt` (`orderId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `productcategory`
--
ALTER TABLE `productcategory`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `userfavorite`
--
ALTER TABLE `userfavorite`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `user_order_notify`
--
ALTER TABLE `user_order_notify`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`productId`) REFERENCES `product` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_product` FOREIGN KEY (`productId`) REFERENCES `product` (`_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `fk_orderItem_product` FOREIGN KEY (`productId`) REFERENCES `product` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderItems_order` FOREIGN KEY (`orderId`) REFERENCES `order` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`categoryId`) REFERENCES `productcategory` (`_id`);

--
-- Constraints for table `useradressdetails`
--
ALTER TABLE `useradressdetails`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`);

--
-- Constraints for table `userfavorite`
--
ALTER TABLE `userfavorite`
  ADD CONSTRAINT `fk_userfavorite_product` FOREIGN KEY (`productId`) REFERENCES `product` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_userfavorite_user` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_order_notify`
--
ALTER TABLE `user_order_notify`
  ADD CONSTRAINT `orderRlt` FOREIGN KEY (`orderId`) REFERENCES `order` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userRlt` FOREIGN KEY (`userId`) REFERENCES `user` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
