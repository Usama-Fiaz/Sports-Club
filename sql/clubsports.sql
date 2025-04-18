-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 07, 2025 at 08:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clubsports`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `billingAddress` varchar(255) NOT NULL,
  `billingCity` varchar(100) NOT NULL,
  `billingState` varchar(100) NOT NULL,
  `billingPincode` varchar(20) NOT NULL,
  `billingCountry` varchar(100) NOT NULL,
  `shippingAddress` varchar(255) NOT NULL,
  `shippingCity` varchar(100) NOT NULL,
  `shippingState` varchar(100) NOT NULL,
  `shippingPincode` varchar(20) NOT NULL,
  `shippingCountry` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `userId`, `billingAddress`, `billingCity`, `billingState`, `billingPincode`, `billingCountry`, `shippingAddress`, `shippingCity`, `shippingState`, `shippingPincode`, `shippingCountry`) VALUES
(1, 4, '123maint', 'lahore', 'uk', '', 'uk', '123maint', 'lahore', 'uk', '', 'uk');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin', 'f925916e2754e5e03f75dd58a5733251', '2023-05-21 13:49:37');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productQty` int(11) NOT NULL DEFAULT 1,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `addressId` int(11) NOT NULL,
  `totalAmount` decimal(11,2) NOT NULL,
  `txntype` varchar(50) NOT NULL,
  `txnnumber` varchar(50) NOT NULL,
  `orderNumber` varchar(50) NOT NULL,
  `orderDate` datetime NOT NULL,
  `orderStatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `userId`, `addressId`, `totalAmount`, `txntype`, `txnnumber`, `orderNumber`, `orderDate`, `orderStatus`) VALUES
(3, 4, 1, 30.00, 'COD', '', 'ORD67C80E87729D3', '2025-03-05 08:42:47', 'In Transit'),
(5, 4, 1, 25.00, 'COD', '', 'ORD67C812EE95C52', '2025-03-05 09:01:34', 'Delivered'),
(6, 4, 1, 70.00, 'COD', '', 'ORD67C94A9743EA6', '2025-03-06 07:11:19', 'Cancelled'),
(7, 4, 1, 30.00, 'COD', '', 'ORD67C94AFCE2FB7', '2025-03-06 07:13:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `orderNumber` varchar(50) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `orderStatus` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `orderNumber`, `userId`, `productId`, `quantity`, `orderDate`, `orderStatus`) VALUES
(2, 'ORD67C4220707E2C', 4, 10, 1, '2025-03-02 09:16:55', 'Pending'),
(4, 'ORD67C812EE95C52', 4, 10, 1, '2025-03-05 09:01:34', 'Pending'),
(5, 'ORD67C94A9743EA6', 4, 13, 1, '2025-03-06 07:11:19', 'Pending'),
(6, 'ORD67C94AFCE2FB7', 4, 12, 1, '2025-03-06 07:13:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_track_history`
--

CREATE TABLE `order_track_history` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `orderNumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Packed','Dispatched','In Transit','Out For Delivery','Delivered','Cancelled') NOT NULL,
  `remark` text DEFAULT NULL,
  `actionBy` int(11) DEFAULT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `canceledBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_track_history`
--

INSERT INTO `order_track_history` (`id`, `orderId`, `orderNumber`, `status`, `remark`, `actionBy`, `postingDate`, `canceledBy`) VALUES
(5, 3, 'ORD67C80E87729D3', 'Packed', '', 1, '2025-03-05 09:40:42', NULL),
(6, 5, 'ORD67C812EE95C52', 'Dispatched', '', 1, '2025-03-05 09:59:26', NULL),
(7, 5, 'ORD67C812EE95C52', 'In Transit', '', 1, '2025-03-05 10:02:46', NULL),
(8, 5, 'ORD67C812EE95C52', 'Delivered', '', 1, '2025-03-05 10:03:05', NULL),
(9, 3, 'ORD67C80E87729D3', 'Delivered', '', 1, '2025-03-05 10:14:54', NULL),
(10, 6, 'ORD67C94A9743EA6', 'Cancelled', 'Order cancelled by user.', 4, '2025-03-06 07:12:28', 4),
(11, 3, 'ORD67C80E87729D3', 'In Transit', '', 1, '2025-03-06 07:15:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `subCategoryId` int(11) DEFAULT NULL,
  `productName` varchar(255) NOT NULL,
  `productCompany` varchar(255) NOT NULL,
  `productPrice` decimal(10,2) NOT NULL,
  `productPriceBeforeDiscount` decimal(10,2) DEFAULT NULL,
  `productDescription` text DEFAULT NULL,
  `productImage1` varchar(255) DEFAULT NULL,
  `productImage2` varchar(255) DEFAULT NULL,
  `productImage3` varchar(255) DEFAULT NULL,
  `shippingCharge` decimal(10,2) DEFAULT 0.00,
  `productAvailability` enum('In Stock','Out of Stock') DEFAULT 'In Stock',
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `addedBy` int(11) NOT NULL,
  `lastUpdatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `categoryId`, `subCategoryId`, `productName`, `productCompany`, `productPrice`, `productPriceBeforeDiscount`, `productDescription`, `productImage1`, `productImage2`, `productImage3`, `shippingCharge`, `productAvailability`, `postingDate`, `updationDate`, `addedBy`, `lastUpdatedBy`) VALUES
(1, 1, 1, 'Nike Air Zoom Pegasus', 'Nike', 120.00, 150.00, 'Lightweight running shoes for athletes.', 'nike_zoom_1.jpg', 'nike_zoom_2.jpg', 'nike_zoom_3.jpg', 5.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:48:49', 1, NULL),
(2, 1, 1, 'Adidas Ultraboost 22', 'Adidas', 140.00, 170.00, 'High-performance running shoes with superior cushioning.', 'adidas_ultraboost_1.jpg', 'adidas_ultraboost_2.jpg', 'adidas_ultraboost_3.jpg', 5.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:50:22', 1, NULL),
(3, 2, 2, 'Adjustable Dumbbells (20KG)', 'Bowflex', 90.00, 120.00, 'Compact and adjustable dumbbells for home workouts.', 'dumbbells_1.jpg', 'dumbbells_2.jpg', NULL, 10.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:50:47', 2, NULL),
(4, 2, 2, 'Resistance Bands Set', 'FitCord', 35.00, 50.00, 'High-quality resistance bands for strength training.', 'resistance_band_1.jpg', 'resistance_band_2.jpg', NULL, 3.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:51:42', 2, NULL),
(5, 3, 1, 'Manchester United Home Jersey', 'Adidas', 80.00, 100.00, 'Official Adidas Manchester United home kit.', 'manu_jersey_1.jpg', 'manu_jersey_2.jpg', NULL, 5.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:49:36', 1, NULL),
(6, 3, 3, 'Los Angeles Lakers Basketball Jersey', 'Nike', 85.00, 110.00, 'Official NBA Lakers jersey with player customization.', 'lakers_jersey_1.jpg', 'lakers_jersey_2.jpg', NULL, 5.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:51:52', 1, NULL),
(7, 4, 4, 'Everlast Boxing Gloves', 'Everlast', 60.00, 80.00, 'Durable boxing gloves for training and competition.', 'boxing_gloves_1.jpg', 'boxing_gloves_2.jpg', NULL, 7.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:52:08', 3, NULL),
(8, 4, 4, 'Pro Knee Pads for Skating', 'Triple Eight', 40.00, 55.00, 'Heavy-duty knee protection for skating and extreme sports.', 'knee_pads_1.jpg', 'knee_pads_2.jpg', NULL, 4.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:52:23', 3, NULL),
(9, 5, 5, 'Smart Fitness Watch', 'Garmin', 200.00, 250.00, 'Advanced GPS fitness watch with heart rate monitoring.', 'garmin_watch_1.jpg', 'garmin_watch_2.jpg', NULL, 8.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:52:37', 1, NULL),
(10, 5, 5, 'Insulated Water Bottle (1L)', 'Hydro Flask', 25.00, 35.00, 'Keeps beverages cold for 24 hours.', 'hydro_flask_1.jpg', 'hydro_flask_2.jpg', NULL, 2.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:52:49', 1, NULL),
(11, 6, 6, 'Agility Ladder & Cones Set', 'SKLZ', 50.00, 65.00, 'Essential speed and agility training set.', 'agility_ladder_1.jpg', 'agility_ladder_2.jpg', NULL, 5.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:53:00', 2, NULL),
(12, 6, 6, 'Skipping Rope (Speed Rope)', 'Rogue Fitness', 30.00, 45.00, 'High-speed skipping rope for cardio training.', 'skipping_rope_1.jpg', 'skipping_rope_2.jpg', NULL, 3.00, 'In Stock', '2025-02-27 19:12:07', '2025-03-06 02:53:09', 2, NULL),
(13, 1, 1, 'bkbkbkb', 'bibnkn', 70.00, 90.00, 'bkbbkbkbbk', '14e92923ed5ba845e8821bc991a4bc1a.jpg', '982358d675015d0161930372c0b0eb29.jpg', '2b672720dc7d38886107b5a8f7e837f5.jpg', NULL, NULL, '2025-03-06 03:11:30', '2025-03-06 03:11:30', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_category`
--

CREATE TABLE `shopping_category` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` text DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `createdBy` int(11) NOT NULL,
  `categoryImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_category`
--

INSERT INTO `shopping_category` (`id`, `categoryName`, `categoryDescription`, `creationDate`, `updationDate`, `createdBy`, `categoryImage`) VALUES
(1, 'Sports Shoes', 'Footwear designed for sports and outdoor activities.', '2025-02-27 19:00:43', '2025-03-02 00:41:09', 1, 'alexandra-tran-fS3tGOkp0xY-unsplash.jpg'),
(2, 'Gym Equipment', 'Dumbbells, resistance bands, treadmills, and other workout gear.', '2025-02-27 19:00:43', '2025-03-02 00:54:48', 1, 'evan-wise-wTcD3MwL_VY-unsplash.jpg'),
(3, 'Jerseys & Apparel', 'Sports jerseys, shorts, tracksuits, and sweatshirts.', '2025-02-27 19:00:43', '2025-03-02 00:55:14', 1, 'swati-kedia-CTLuPLp-LDg-unsplash.jpg'),
(4, 'Protective Gear', 'Helmets, knee pads, gloves, and mouthguards.', '2025-02-27 19:00:43', '2025-03-02 00:53:59', 1, 'muhammad-masood-l5v9hXW387s-unsplash.jpg'),
(5, 'Accessories', 'Water bottles, gym bags, fitness watches, and more.', '2025-02-27 19:00:43', '2025-03-02 00:59:09', 1, 'elena-kloppenburg--fkLJ9Ws5XQ-unsplash.jpg'),
(6, 'Training Aids', 'Agility ladders, cones, skipping ropes, and performance boosters.', '2025-02-27 19:00:43', '2025-03-02 00:57:04', 1, 'karsten-winegeart-0Wra5YYVQJE-unsplash.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `subCategoryName` varchar(255) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `categoryId`, `subCategoryName`, `creationDate`, `updationDate`, `createdBy`) VALUES
(1, 1, 'Running Shoes', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(2, 1, 'Basketball Shoes', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(3, 1, 'Football Boots', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(4, 2, 'Weightlifting Equipment', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2),
(5, 2, 'Resistance Training', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2),
(6, 2, 'Cardio Machines', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2),
(7, 3, 'Football Jerseys', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(8, 3, 'Basketball Jerseys', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(9, 3, 'Tracksuits', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(10, 4, 'Boxing Gloves', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 3),
(11, 4, 'Skating Protective Gear', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 3),
(12, 4, 'Martial Arts Gear', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 3),
(13, 5, 'Sports Watches', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(14, 5, 'Gym Bags', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(15, 5, 'Hydration Gear', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 1),
(16, 6, 'Agility Training', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2),
(17, 6, 'Jump Ropes', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2),
(18, 6, 'Speed Training', '2025-02-27 19:15:08', '2025-02-27 19:15:08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblbookings`
--

CREATE TABLE `tblbookings` (
  `id` int(11) NOT NULL,
  `BookingId` bigint(12) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `EventId` int(11) DEFAULT NULL,
  `NumberOfMembers` int(11) DEFAULT NULL,
  `UserRemark` mediumtext DEFAULT NULL,
  `AdminRemark` mediumtext DEFAULT NULL,
  `UserCancelRemark` mediumtext DEFAULT NULL,
  `BookingDate` timestamp NULL DEFAULT current_timestamp(),
  `BookingStatus` varchar(100) DEFAULT NULL,
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbookings`
--

INSERT INTO `tblbookings` (`id`, `BookingId`, `UserId`, `EventId`, `NumberOfMembers`, `UserRemark`, `AdminRemark`, `UserCancelRemark`, `BookingDate`, `BookingStatus`, `LastUpdationDate`) VALUES
(1, 713920123, 1, 2, 5, 'NA', 'Booking Confirmed', NULL, '2023-05-21 11:34:49', 'Confirmed', '2023-05-21 11:35:42'),
(2, 429161742, 2, 2, 50, 'ggjhghj', 'Your booking has been confirmed', NULL, '2023-05-21 13:43:59', 'Confirmed', '2023-05-21 14:04:06'),
(3, 357382557, 3, 2, 2, 'looking forward ', NULL, NULL, '2025-02-21 04:35:59', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(200) DEFAULT NULL,
  `CategoryDescription` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsActive` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `CategoryDescription`, `CreationDate`, `UpdationDate`, `IsActive`) VALUES
(1, 'Cricket', 'a match ggg', '2025-02-16 03:40:49', NULL, '1'),
(2, 'football', 'match', '2025-02-16 03:41:57', NULL, '1'),
(3, 'marathon', '1km run', '2025-02-16 03:42:19', NULL, '1'),
(4, 'Swimming contest', 'all ages', '2025-02-16 03:44:09', NULL, '1'),
(5, 'Basketball', 'hb vs rf', '2025-02-16 03:44:24', NULL, '1'),
(6, 'Tennis', '......', '2025-02-16 03:44:52', NULL, '1'),
(7, 'Golf', 'hole in one ', '2025-02-16 12:36:19', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tblevents`
--

CREATE TABLE `tblevents` (
  `id` int(11) NOT NULL,
  `CategoryId` char(10) DEFAULT NULL,
  `SponserId` char(10) DEFAULT NULL,
  `EventName` varchar(255) DEFAULT NULL,
  `EventDescription` mediumtext DEFAULT NULL,
  `EventStartDate` date DEFAULT NULL,
  `EventEndDate` date DEFAULT NULL,
  `EventLocation` varchar(255) DEFAULT NULL,
  `EventImage` varchar(255) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsActive` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblevents`
--

INSERT INTO `tblevents` (`id`, `CategoryId`, `SponserId`, `EventName`, `EventDescription`, `EventStartDate`, `EventEndDate`, `EventLocation`, `EventImage`, `PostingDate`, `LastUpdationDate`, `IsActive`) VALUES
(1, '1', '1', 'DPS vs St. Xavier\'s High School(SXHS) Cricket Match', 'DPS vs St. Xavier\'s High School(SXHS) league cricket match', '2025-02-20', '2025-02-21', 'uk ', 'marcus-wallis-mUtQXjjLPbw-unsplash.jpg', '2025-02-19 03:50:51', NULL, 1),
(2, '2', '4', 'BasketBall ', 'Sportssync vs sportylzer\r\n', '2025-02-21', '2025-02-22', 'liverpool uk ', 'logan-weaver-lgnwvr-zPHJ_7seOfA-unsplash.jpg', '2025-02-19 12:25:37', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblgenralsettings`
--

CREATE TABLE `tblgenralsettings` (
  `id` int(11) NOT NULL,
  `SiteName` varchar(200) DEFAULT NULL,
  `PhoneNumber` bigint(12) DEFAULT NULL,
  `EmailId` varchar(255) DEFAULT NULL,
  `address` mediumtext DEFAULT NULL,
  `footercontent` mediumtext DEFAULT NULL,
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblgenralsettings`
--

INSERT INTO `tblgenralsettings` (`id`, `SiteName`, `PhoneNumber`, `EmailId`, `address`, `footercontent`, `LastUpdationDate`) VALUES
(1, 'SS ', 1234567890, 'demotest@test.com', 'Test address\r\nTest City\r\nTest State\r\nIN-110091', 'SPORTS SYNC LIMITED ', '2025-02-16 19:22:40');

-- --------------------------------------------------------

--
-- Table structure for table `tblnews`
--

CREATE TABLE `tblnews` (
  `id` int(11) NOT NULL,
  `NewsTitle` varchar(255) DEFAULT NULL,
  `NewsDetails` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblnews`
--

INSERT INTO `tblnews` (`id`, `NewsTitle`, `NewsDetails`, `PostingDate`, `LastUpdationDate`) VALUES
(4, 'Test news', ' This is for testing purpose. This is for testing purpose. This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.This is for testing purpose.', '2025-02-18 03:24:03', NULL),
(5, 'New title', 'New detail come here. New detail come hereNew detail come hereNew detail come hereNew detail come hereNew detail come hereNew detail come hereNew detail come here', '2025-02-18 12:39:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `PageType` varchar(255) DEFAULT NULL,
  `PageDetails` mediumtext DEFAULT NULL,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `PageType`, `PageDetails`, `LastupdationDate`) VALUES
(1, 'aboutus', 'Sample text for testing. Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.Sample text for testing.', '2025-02-18 15:57:01');

-- --------------------------------------------------------

--
-- Table structure for table `tblsponsers`
--

CREATE TABLE `tblsponsers` (
  `id` int(11) NOT NULL,
  `sponserName` varchar(255) DEFAULT NULL,
  `sponserLogo` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `lastUpdationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsponsers`
--

INSERT INTO `tblsponsers` (`id`, `sponserName`, `sponserLogo`, `postingDate`, `lastUpdationDate`) VALUES
(1, 'VIVO', 'f6aac84a83343a247532b533b0ef059f.png', '2023-05-21 02:45:57', '0000-00-00 00:00:00'),
(2, 'OLA', 'dd7e6dd23586907e1b0cb0b0319f6445.jpg', '2023-05-21 02:46:33', '0000-00-00 00:00:00'),
(3, 'TATA', 'dc78d13a95344a4147b0b2657c851cda.png', '2023-05-21 02:47:13', '0000-00-00 00:00:00'),
(4, 'Airtel', '80ba95f4a6e05bd753fdf3c43ce89f46.jpg', '2023-05-21 02:47:48', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubscriber`
--

CREATE TABLE `tblsubscriber` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(255) DEFAULT NULL,
  `Regdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsubscriber`
--

INSERT INTO `tblsubscriber` (`id`, `UserEmail`, `Regdate`) VALUES
(1, 'johndoe@test.com', '2023-05-21 04:20:09'),
(2, 'ak@gmail.com', '2023-05-21 11:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `Userid` int(11) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `UserName` varchar(200) DEFAULT NULL,
  `Emailid` varchar(255) DEFAULT NULL,
  `PhoneNumber` bigint(12) DEFAULT NULL,
  `UserGender` varchar(100) DEFAULT NULL,
  `UserPassword` varchar(255) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsActive` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`Userid`, `FullName`, `UserName`, `Emailid`, `PhoneNumber`, `UserGender`, `UserPassword`, `RegDate`, `LastUpdationDate`, `IsActive`) VALUES
(1, 'John Doe', 'john12', 'john@test.com', 1425632120, 'Male', 'f925916e2754e5e03f75dd58a5733251', '2023-05-21 11:34:02', '2023-05-21 11:34:21', 1),
(2, 'Test123', 'test12345', 'test@gmail.com', 7979797979, 'Male', 'f925916e2754e5e03f75dd58a5733251', '2023-05-21 13:37:28', '2023-05-21 15:12:39', 1),
(3, 'momal', 'momal12', 'momalnasir01@gmail.com', 306000392, 'Female', 'd90083fee06fb4b5d358463a1159f698', '2025-02-21 04:35:23', '2025-02-28 04:13:51', 1),
(4, 'momal21', 'momall21', 'momal21@gmail.com', 7777937366, 'Female', 'd90083fee06fb4b5d358463a1159f698', '2025-03-02 02:22:04', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `userId`, `productId`, `postingDate`) VALUES
(2, 4, 12, '2025-03-02 03:25:54'),
(4, 4, 13, '2025-03-06 07:10:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `order_details_ibfk_2` (`orderNumber`);

--
-- Indexes for table `order_track_history`
--
ALTER TABLE `order_track_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderNumber` (`orderNumber`),
  ADD KEY `actionBy` (`actionBy`),
  ADD KEY `canceledBy` (`canceledBy`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `addedBy` (`addedBy`),
  ADD KEY `lastUpdatedBy` (`lastUpdatedBy`);

--
-- Indexes for table `shopping_category`
--
ALTER TABLE `shopping_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `tblbookings`
--
ALTER TABLE `tblbookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblevents`
--
ALTER TABLE `tblevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblgenralsettings`
--
ALTER TABLE `tblgenralsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblnews`
--
ALTER TABLE `tblnews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsponsers`
--
ALTER TABLE `tblsponsers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsubscriber`
--
ALTER TABLE `tblsubscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`Userid`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `productId` (`productId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_track_history`
--
ALTER TABLE `order_track_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `shopping_category`
--
ALTER TABLE `shopping_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblbookings`
--
ALTER TABLE `tblbookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblevents`
--
ALTER TABLE `tblevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblgenralsettings`
--
ALTER TABLE `tblgenralsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblnews`
--
ALTER TABLE `tblnews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblsponsers`
--
ALTER TABLE `tblsponsers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblsubscriber`
--
ALTER TABLE `tblsubscriber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `Userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `shopping_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`addedBy`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`lastUpdatedBy`) REFERENCES `tblusers` (`Userid`) ON DELETE SET NULL;

--
-- Constraints for table `shopping_category`
--
ALTER TABLE `shopping_category`
  ADD CONSTRAINT `shopping_category_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `sub_category_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `shopping_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_category_ibfk_2` FOREIGN KEY (`createdBy`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblusers` (`Userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
