-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2026 at 07:45 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbcategory`
--

CREATE TABLE `tbcategory` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbcategory`
--

INSERT INTO `tbcategory` (`CategoryID`, `CategoryName`) VALUES
(1, 'Electronics'),
(2, 'Clothing'),
(3, 'Food & Beverages'),
(4, 'Furniture');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategories`
--

CREATE TABLE `tblcategories` (
  `CategoryID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `CategoryIcon` varchar(60) NOT NULL DEFAULT 'fa-tag',
  `CategoryColor` varchar(20) NOT NULL DEFAULT '#6366f1',
  `Description` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CompanyID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategories`
--

INSERT INTO `tblcategories` (`CategoryID`, `UserID`, `CategoryName`, `CategoryIcon`, `CategoryColor`, `Description`, `CreatedAt`, `CompanyID`) VALUES
(2, 1, 'Food & Beverage', 'fa-utensils', '#10b981', NULL, '2026-05-05 11:54:41', 1),
(3, 8, 'family plan', 'fa-gift', '#121212', 'whejlndojnvonhionvoci', '2026-05-16 15:07:51', 5),
(4, 9, 'Electronics', 'fa-microchip', '#3b82f6', NULL, '2026-05-16 21:32:28', 6),
(11, 1, 'Electronics', 'fa-microchip', '#3b82f6', NULL, '2026-05-18 21:29:59', 1),
(12, 1, 'Office Supplies', 'fa-paperclip', '#f59e0b', NULL, '2026-05-18 21:29:59', 1),
(13, 1, 'Furniture', 'fa-couch', '#8b5cf6', NULL, '2026-05-18 21:29:59', 1),
(14, 1, 'Electronics', 'fa-microchip', '#3b82f6', NULL, '2026-05-18 21:30:48', 1),
(15, 1, 'Office Supplies', 'fa-paperclip', '#f59e0b', NULL, '2026-05-18 21:30:48', 1),
(16, 1, 'Furniture', 'fa-couch', '#8b5cf6', NULL, '2026-05-18 21:30:48', 1),
(17, 18, 'Food & Beverage', 'fa-utensils', '#10b981', NULL, '2026-05-22 07:50:52', 10),
(18, 1, 'Electronics', 'fa-microchip', '#3b82f6', 'Electronic devices and components', '2026-05-24 19:45:55', 1),
(19, 1, 'Office Supplies', 'fa-briefcase', '#8b5cf6', 'Stationery and office essentials', '2026-05-24 19:45:55', 1),
(20, 1, 'Furniture', 'fa-couch', '#f59e0b', 'Desks, chairs, and shelving', '2026-05-24 19:45:55', 1),
(21, 1, 'Beverages', 'fa-bottle-water', '#06b6d4', 'Drinks and refreshments', '2026-05-24 19:45:55', 1),
(22, 1, 'Clothing', 'fa-shirt', '#ec4899', 'Apparel and accessories', '2026-05-24 19:45:55', 1),
(23, 1, 'Tools & Hardware', 'fa-screwdriver-wrench', '#10b981', 'Hand tools and equipment', '2026-05-24 19:45:55', 1),
(24, 1, 'Food & Grocery', 'fa-basket-shopping', '#f97316', 'Dry goods and perishables', '2026-05-24 19:45:55', 1),
(25, 1, 'Electronics', 'fa-microchip', '#3b82f6', 'Electronic devices and components', '2026-05-24 19:53:35', 1),
(26, 1, 'Office Supplies', 'fa-briefcase', '#8b5cf6', 'Stationery and office essentials', '2026-05-24 19:53:35', 1),
(27, 1, 'Furniture', 'fa-couch', '#f59e0b', 'Desks, chairs, and shelving', '2026-05-24 19:53:35', 1),
(28, 1, 'Beverages', 'fa-bottle-water', '#06b6d4', 'Drinks and refreshments', '2026-05-24 19:53:35', 1),
(29, 1, 'Clothing', 'fa-shirt', '#ec4899', 'Apparel and accessories', '2026-05-24 19:53:35', 1),
(30, 1, 'Tools & Hardware', 'fa-screwdriver-wrench', '#10b981', 'Hand tools and equipment', '2026-05-24 19:53:35', 1),
(31, 1, 'Food & Grocery', 'fa-basket-shopping', '#f97316', 'Dry goods and perishables', '2026-05-24 19:53:35', 1),
(32, 1, 'Electronics', 'fa-microchip', '#3b82f6', 'Electronic devices and components', '2026-05-28 07:32:31', 1),
(33, 1, 'Office Supplies', 'fa-briefcase', '#8b5cf6', 'Stationery and office essentials', '2026-05-28 07:32:31', 1),
(34, 1, 'Furniture', 'fa-couch', '#f59e0b', 'Desks, chairs, and shelving', '2026-05-28 07:32:31', 1),
(35, 1, 'Beverages', 'fa-bottle-water', '#06b6d4', 'Drinks and refreshments', '2026-05-28 07:32:31', 1),
(36, 1, 'Clothing', 'fa-shirt', '#ec4899', 'Apparel and accessories', '2026-05-28 07:32:31', 1),
(37, 1, 'Tools & Hardware', 'fa-screwdriver-wrench', '#10b981', 'Hand tools and equipment', '2026-05-28 07:32:31', 1),
(38, 1, 'Food & Grocery', 'fa-basket-shopping', '#f97316', 'Dry goods and perishables', '2026-05-28 07:32:31', 1),
(39, 21, 'Pharmacy', 'fa-flask', '#b7aebd', NULL, '2026-05-28 10:19:41', 11),
(40, 9, 'Medical', 'fa-kit-medical', '#ef4444', NULL, '2026-05-29 19:44:19', 6),
(41, 9, 'Constraction', 'fa-hammer', '#6366f1', NULL, '2026-05-29 19:45:13', 6),
(42, 23, 'Food & Beverage', 'fa-utensils', '#10b981', NULL, '2026-06-06 05:43:08', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblchat`
--

CREATE TABLE `tblchat` (
  `ChatID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `SenderID` int(11) NOT NULL,
  `Message` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `RecipientID` int(11) DEFAULT NULL,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchat`
--

INSERT INTO `tblchat` (`ChatID`, `CompanyID`, `SenderID`, `Message`, `CreatedAt`, `RecipientID`, `IsDeleted`) VALUES
(1, 6, 16, 'hy amir my order didnot arrived', '2026-05-18 21:23:33', NULL, 0),
(2, 6, 17, 'my order not arrived', '2026-05-21 07:05:04', NULL, 0),
(3, 10, 19, 'Hy my ordrer didnot arrived', '2026-05-22 07:55:15', NULL, 0),
(4, 6, 9, 'it will arrive soon', '2026-05-23 07:09:00', 17, 0),
(5, 6, 22, 'order didnot arrive', '2026-06-05 19:57:46', 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblchat_presence`
--

CREATE TABLE `tblchat_presence` (
  `UserID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `IsTyping` tinyint(1) NOT NULL DEFAULT 0,
  `TypingAt` timestamp NULL DEFAULT NULL,
  `LastSeen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchat_presence`
--

INSERT INTO `tblchat_presence` (`UserID`, `CompanyID`, `IsTyping`, `TypingAt`, `LastSeen`) VALUES
(9, 6, 0, '2026-05-23 07:08:59', '2026-06-09 19:13:31'),
(17, 6, 0, NULL, '2026-05-24 19:14:48'),
(20, 6, 0, NULL, '2026-05-23 07:12:03'),
(21, 11, 0, NULL, '2026-05-29 22:12:20'),
(22, 6, 0, '2026-06-05 19:57:45', '2026-06-05 19:57:50');

-- --------------------------------------------------------

--
-- Table structure for table `tblchat_reactions`
--

CREATE TABLE `tblchat_reactions` (
  `ChatID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Emoji` varchar(8) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchat_reactions`
--

INSERT INTO `tblchat_reactions` (`ChatID`, `UserID`, `Emoji`, `CreatedAt`) VALUES
(5, 9, '👍', '2026-06-05 19:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `tblchat_reads`
--

CREATE TABLE `tblchat_reads` (
  `UserID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `LastReadChatID` int(11) NOT NULL DEFAULT 0,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchat_reads`
--

INSERT INTO `tblchat_reads` (`UserID`, `CompanyID`, `LastReadChatID`, `UpdatedAt`) VALUES
(9, 6, 2, '2026-05-23 07:08:05'),
(17, 6, 2, '2026-05-23 07:12:32'),
(20, 6, 2, '2026-05-23 07:11:41'),
(22, 6, 2, '2026-06-05 19:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompanies`
--

CREATE TABLE `tblcompanies` (
  `CompanyID` int(11) NOT NULL,
  `CompanyName` varchar(120) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Address` varchar(255) DEFAULT NULL,
  `Phone` varchar(60) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcompanies`
--

INSERT INTO `tblcompanies` (`CompanyID`, `CompanyName`, `CreatedAt`, `Address`, `Phone`, `Email`) VALUES
(1, 'Administrator\'s Company', '2026-05-16 20:49:36', NULL, NULL, NULL),
(2, 'Amir\'s Company', '2026-05-16 20:49:36', NULL, NULL, NULL),
(3, 'Amma\'s Company', '2026-05-16 20:49:36', NULL, NULL, NULL),
(4, 'geme\'s Company', '2026-05-16 20:49:36', NULL, NULL, NULL),
(5, 'nebil\'s Company', '2026-05-16 20:49:36', NULL, NULL, NULL),
(6, 'Amir\'s Company', '2026-05-16 21:19:13', NULL, NULL, NULL),
(7, 'Admin\'s Company', '2026-05-18 06:52:10', NULL, NULL, NULL),
(8, 'Adem\'s Company', '2026-05-18 07:40:24', NULL, NULL, NULL),
(9, 'Oracle NetSuite\'s Company', '2026-05-18 18:27:33', NULL, NULL, NULL),
(10, 'Liya Girma\'s Company', '2026-05-22 07:50:03', NULL, NULL, NULL),
(11, 'Nebil\'s Company', '2026-05-28 10:01:29', NULL, NULL, NULL),
(12, 'Meron\'s Company', '2026-06-06 05:40:40', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomers`
--

CREATE TABLE `tblcustomers` (
  `CustomerID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CustomerName` varchar(120) NOT NULL,
  `Phone` varchar(40) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CompanyID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcustomers`
--

INSERT INTO `tblcustomers` (`CustomerID`, `UserID`, `CustomerName`, `Phone`, `Email`, `Address`, `CreatedAt`, `CompanyID`) VALUES
(1, 1, 'Amir Adem', '0951779543', 'ademamir673@gmail.com', 'jimma', '2026-04-09 06:05:51', 1),
(2, 1, 'Acme Corp', '555-0101', 'contact@acmecorp.com', '123 Business Rd, NY', '2026-05-18 21:29:59', 1),
(3, 1, 'TechLogistics', '555-0202', 'hello@techlogistics.com', '456 Tech Park, SF', '2026-05-18 21:29:59', 1),
(4, 1, 'Acme Corp', '555-0101', 'contact@acmecorp.com', '123 Business Rd, NY', '2026-05-18 21:30:48', 1),
(5, 1, 'TechLogistics', '555-0202', 'hello@techlogistics.com', '456 Tech Park, SF', '2026-05-18 21:30:48', 1),
(6, 18, 'Amir', '0951779543', NULL, NULL, '2026-05-22 07:52:44', 10),
(7, 1, 'Horizon Tech Enterprises', '+1-415-555-0201', 'procurement@horizontech.com', '250 Market St, San Francisco, CA 94105', '2026-05-24 19:45:55', 1),
(8, 1, 'BlueSky Solutions Ltd', '+44-161-555-0202', 'orders@bluesky.co.uk', '1 Piccadilly Gardens, Manchester M1 1RG', '2026-05-24 19:45:55', 1),
(9, 1, 'Apex Manufacturing Corp', '+1-313-555-0203', 'supply@apexmfg.com', '1200 Industrial Blvd, Detroit, MI 48201', '2026-05-24 19:45:55', 1),
(10, 1, 'Green Valley Retail', '+61-2-5550-0204', 'ops@greenvalley.com.au', '88 George St, Sydney NSW 2000', '2026-05-24 19:45:55', 1),
(11, 1, 'Nairobi Office Hub', '+254-722-555005', 'admin@nairobihub.co.ke', 'Westlands, Nairobi 00100', '2026-05-24 19:45:55', 1),
(12, 1, 'Cairo Central Supplies', '+20-10-5550-206', 'orders@cairocentral.eg', 'Heliopolis, Cairo 11361', '2026-05-24 19:45:55', 1),
(13, 1, 'Dubai Business Centre', '+971-50-555-0207', 'procurement@dubaibc.ae', 'DIFC, Dubai, UAE', '2026-05-24 19:45:55', 1),
(14, 1, 'Lagos Trade Partners', '+234-1-5550-208', 'trade@lagospartners.ng', 'Victoria Island, Lagos 106104', '2026-05-24 19:45:55', 1),
(15, 1, 'Horizon Tech Enterprises', '+1-415-555-0201', 'procurement@horizontech.com', '250 Market St, San Francisco, CA 94105', '2026-05-24 19:53:36', 1),
(16, 1, 'BlueSky Solutions Ltd', '+44-161-555-0202', 'orders@bluesky.co.uk', '1 Piccadilly Gardens, Manchester M1 1RG', '2026-05-24 19:53:36', 1),
(17, 1, 'Apex Manufacturing Corp', '+1-313-555-0203', 'supply@apexmfg.com', '1200 Industrial Blvd, Detroit, MI 48201', '2026-05-24 19:53:36', 1),
(18, 1, 'Green Valley Retail', '+61-2-5550-0204', 'ops@greenvalley.com.au', '88 George St, Sydney NSW 2000', '2026-05-24 19:53:36', 1),
(19, 1, 'Nairobi Office Hub', '+254-722-555005', 'admin@nairobihub.co.ke', 'Westlands, Nairobi 00100', '2026-05-24 19:53:36', 1),
(20, 1, 'Cairo Central Supplies', '+20-10-5550-206', 'orders@cairocentral.eg', 'Heliopolis, Cairo 11361', '2026-05-24 19:53:36', 1),
(21, 1, 'Dubai Business Centre', '+971-50-555-0207', 'procurement@dubaibc.ae', 'DIFC, Dubai, UAE', '2026-05-24 19:53:36', 1),
(22, 1, 'Lagos Trade Partners', '+234-1-5550-208', 'trade@lagospartners.ng', 'Victoria Island, Lagos 106104', '2026-05-24 19:53:36', 1),
(23, 1, 'Horizon Tech Enterprises', '+1-415-555-0201', 'procurement@horizontech.com', '250 Market St, San Francisco, CA 94105', '2026-05-28 07:32:31', 1),
(24, 1, 'BlueSky Solutions Ltd', '+44-161-555-0202', 'orders@bluesky.co.uk', '1 Piccadilly Gardens, Manchester M1 1RG', '2026-05-28 07:32:31', 1),
(25, 1, 'Apex Manufacturing Corp', '+1-313-555-0203', 'supply@apexmfg.com', '1200 Industrial Blvd, Detroit, MI 48201', '2026-05-28 07:32:31', 1),
(26, 1, 'Green Valley Retail', '+61-2-5550-0204', 'ops@greenvalley.com.au', '88 George St, Sydney NSW 2000', '2026-05-28 07:32:31', 1),
(27, 1, 'Nairobi Office Hub', '+254-722-555005', 'admin@nairobihub.co.ke', 'Westlands, Nairobi 00100', '2026-05-28 07:32:31', 1),
(28, 1, 'Cairo Central Supplies', '+20-10-5550-206', 'orders@cairocentral.eg', 'Heliopolis, Cairo 11361', '2026-05-28 07:32:31', 1),
(29, 1, 'Dubai Business Centre', '+971-50-555-0207', 'procurement@dubaibc.ae', 'DIFC, Dubai, UAE', '2026-05-28 07:32:31', 1),
(30, 1, 'Lagos Trade Partners', '+234-1-5550-208', 'trade@lagospartners.ng', 'Victoria Island, Lagos 106104', '2026-05-28 07:32:31', 1),
(32, 23, 'Aymen Adis', NULL, NULL, NULL, '2026-06-06 05:46:11', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblpassword_resets`
--

CREATE TABLE `tblpassword_resets` (
  `ResetID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Token` varchar(64) NOT NULL,
  `ExpiresAt` datetime NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblproducts`
--

CREATE TABLE `tblproducts` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockQuantity` int(11) NOT NULL DEFAULT 0,
  `Description` text DEFAULT NULL,
  `LastUpdated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `SKU` varchar(50) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Unit` varchar(24) NOT NULL DEFAULT 'pcs',
  `CompanyID` int(11) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproducts`
--

INSERT INTO `tblproducts` (`ProductID`, `ProductName`, `CategoryID`, `Price`, `StockQuantity`, `Description`, `LastUpdated`, `SKU`, `UserID`, `Unit`, `CompanyID`, `ImageURL`) VALUES
(1, 'Banana gum', NULL, 242.10, 40, 'High-end modular frame', '2026-05-16 23:49:36', 'DA-MOD-772-B', 1, 'pcs', 1, NULL),
(3, 'Capchino Biscut', NULL, 0.00, 40, NULL, '2026-05-16 23:49:36', NULL, 1, 'pcs', 1, NULL),
(6, 'sugar', NULL, 0.00, 100, NULL, '2026-04-20 10:17:17', NULL, 31, 'kg', NULL, NULL),
(7, 'sugar', NULL, 0.00, 5, NULL, '2026-05-16 23:49:36', NULL, 7, 'pcs', 4, NULL),
(8, 'milk', NULL, 0.00, 4, NULL, '2026-05-16 23:49:36', NULL, 7, 'pcs', 4, NULL),
(9, 'salt', NULL, 0.00, 6, NULL, '2026-05-16 23:49:36', NULL, 7, 'kg', 4, NULL),
(10, 'conmdo,', 3, 0.00, 123344447, NULL, '2026-05-16 23:49:36', NULL, 8, 'pcs', 5, NULL),
(11, 'chacolate', 2, 45.00, 20, NULL, '2026-05-19 00:37:25', NULL, 1, 'pcs', 1, NULL),
(14, 'Wireless Mouse X100', 11, 25.99, 150, NULL, '2026-05-19 00:29:59', NULL, 1, 'pcs', 1, NULL),
(15, 'Mechanical Keyboard K2', 11, 89.50, 80, NULL, '2026-05-19 00:29:59', NULL, 1, 'pcs', 1, NULL),
(16, '27-inch 4K Monitor', 11, 320.00, 35, NULL, '2026-05-28 13:15:52', NULL, 1, 'pcs', 1, NULL),
(17, 'A4 Printer Paper (Ream)', 12, 5.50, 500, NULL, '2026-05-19 00:29:59', NULL, 1, 'pack', 1, NULL),
(18, 'Gel Pens (Black) Box', 12, 12.00, 200, NULL, '2026-05-19 00:29:59', NULL, 1, 'box', 1, NULL),
(19, 'Ergonomic Office Chair', 13, 185.00, 30, NULL, '2026-05-19 00:29:59', NULL, 1, 'pcs', 1, NULL),
(20, 'Standing Desk Frame', 13, 299.99, 20, NULL, '2026-05-19 00:29:59', NULL, 1, 'pcs', 1, NULL),
(21, 'Wireless Mouse X100', 14, 25.99, 150, NULL, '2026-05-19 00:30:48', NULL, 1, 'pcs', 1, NULL),
(22, 'Mechanical Keyboard K2', 14, 89.50, 80, NULL, '2026-05-19 00:30:48', NULL, 1, 'pcs', 1, NULL),
(23, '27-inch 4K Monitor', 14, 320.00, 45, NULL, '2026-05-19 00:30:48', NULL, 1, 'pcs', 1, NULL),
(24, 'A4 Printer Paper (Ream)', 15, 5.50, 500, NULL, '2026-05-19 00:30:48', NULL, 1, 'pack', 1, NULL),
(25, 'Gel Pens (Black) Box', 15, 12.00, 200, NULL, '2026-05-19 00:30:48', NULL, 1, 'box', 1, NULL),
(26, 'Ergonomic Office Chair', 16, 185.00, 30, NULL, '2026-05-19 00:30:48', NULL, 1, 'pcs', 1, NULL),
(27, 'Standing Desk Frame', 16, 299.99, 20, NULL, '2026-05-19 00:30:48', NULL, 1, 'pcs', 1, NULL),
(28, 'Cappcino', 17, 20.00, 41, NULL, '2026-05-22 10:52:00', NULL, 18, 'pcs', 10, NULL),
(29, 'Dell Latitude 5420 Laptop', 11, 899.99, 12, '14\" FHD laptop, Intel Core i7, 16GB RAM, 512GB SSD', '2026-05-24 22:45:55', 'EL-001', 1, 'pcs', 1, NULL),
(30, 'HP LaserJet Pro M404dn', 11, 299.00, 8, 'Monochrome laser printer, duplex, 38ppm', '2026-05-24 22:45:55', 'EL-002', 1, 'pcs', 1, NULL),
(31, 'Logitech MX Master 3 Mouse', 11, 99.99, 45, 'Wireless ergonomic mouse, multi-device', '2026-05-24 22:45:55', 'EL-003', 1, 'pcs', 1, NULL),
(32, 'Logitech MK850 Keyboard', 11, 109.99, 30, 'Wireless multi-device keyboard', '2026-05-24 22:45:55', 'EL-004', 1, 'pcs', 1, NULL),
(33, 'Samsung 27\" Curved Monitor', 11, 249.99, 18, 'Full HD 1080p, 75Hz, 4ms response', '2026-05-24 22:45:55', 'EL-005', 1, 'pcs', 1, NULL),
(34, 'Anker USB-C Hub 7-in-1', 11, 49.99, 60, 'HDMI 4K, USB 3.0, SD card, PD charging', '2026-05-24 22:45:55', 'EL-006', 1, 'pcs', 1, NULL),
(35, 'Brother Label Printer PT-D600', 11, 89.00, 10, 'PC-connectable label printer, 24mm tape', '2026-05-24 22:45:55', 'EL-007', 1, 'pcs', 1, NULL),
(36, 'A4 Copy Paper 80gsm (Ream)', 12, 6.50, 250, 'Premium white copy paper, 500 sheets/ream', '2026-05-24 22:45:55', 'OF-001', 1, 'pack', 1, NULL),
(37, 'Stapler Rapid Classic', 12, 12.99, 40, 'Heavy-duty stapler, 50-sheet capacity', '2026-05-24 22:45:55', 'OF-002', 1, 'pcs', 1, NULL),
(38, 'Ballpoint Pen Blue (Box)', 12, 9.99, 80, 'BIC Cristal ballpoint, 50 pens per box', '2026-05-24 22:45:55', 'OF-003', 1, 'box', 1, NULL),
(39, 'Sticky Notes 76x76mm (Pack)', 12, 7.49, 55, 'Post-it notes, 12 pads ├ù 100 sheets, assorted', '2026-05-24 22:45:55', 'OF-004', 1, 'pack', 1, NULL),
(40, 'Lever Arch File A4', 12, 18.99, 35, '70mm spine, PP cover, multi-pack of 10', '2026-05-24 22:45:55', 'OF-005', 1, 'pack', 1, NULL),
(41, 'Scissors Fiskars 21cm', 12, 8.99, 25, 'Stainless steel blades, soft-grip handle', '2026-05-24 22:45:55', 'OF-006', 1, 'pcs', 1, NULL),
(42, 'Ergonomic Office Chair', 13, 349.00, 15, 'Mesh back, adjustable lumbar, 5-wheel base', '2026-05-24 22:45:55', 'FN-001', 1, 'pcs', 1, NULL),
(43, 'Standing Desk 140x70cm', 13, 599.00, 6, 'Electric height-adjustable, oak finish', '2026-05-24 22:45:55', 'FN-002', 1, 'pcs', 1, NULL),
(44, '3-Drawer Filing Cabinet', 13, 189.00, 9, 'Metal cabinet, lockable, A4 files', '2026-05-24 22:45:55', 'FN-003', 1, 'pcs', 1, NULL),
(45, 'Bookshelf 5-Tier', 13, 129.00, 12, 'Metal frame, MDF shelves, 180cm tall', '2026-05-24 22:45:55', 'FN-004', 1, 'pcs', 1, NULL),
(46, 'Mineral Water 500ml (Case)', 21, 7.99, 120, 'Still mineral water, 24 bottles per case', '2026-05-24 22:45:55', 'BV-001', 1, 'pack', 1, NULL),
(47, 'Coffee Beans Arabica 1kg', 21, 24.99, 30, 'Single-origin Ethiopian Arabica, medium roast', '2026-05-24 22:45:55', 'BV-002', 1, 'kg', 1, NULL),
(48, 'Green Tea Bags (Box 100)', 21, 11.49, 50, 'Premium Sencha green tea, individually wrapped', '2026-05-24 22:45:55', 'BV-003', 1, 'box', 1, NULL),
(49, 'Orange Juice 1L (Pack 12)', 21, 14.99, 40, '100% pure squeezed, chilled', '2026-05-24 22:45:55', 'BV-004', 1, 'pack', 1, NULL),
(50, 'Corporate Polo Shirt (M)', 22, 29.99, 25, 'Branded polo, moisture-wicking, medium', '2026-05-24 22:45:55', 'CL-001', 1, 'pcs', 1, NULL),
(51, 'Corporate Polo Shirt (L)', 22, 29.99, 30, 'Branded polo, moisture-wicking, large', '2026-05-24 22:45:55', 'CL-002', 1, 'pcs', 1, NULL),
(52, 'Safety Work Vest Hi-Vis', 22, 14.99, 40, 'High-visibility vest, EN ISO 20471 Class 2', '2026-05-24 22:45:55', 'CL-003', 1, 'pcs', 1, NULL),
(53, 'DeWalt 18V Cordless Drill', 23, 189.99, 7, 'Brushless motor, 2├ù2Ah batteries, charger included', '2026-05-24 22:45:55', 'TL-001', 1, 'pcs', 1, NULL),
(54, 'Stanley Screwdriver Set 12pc', 23, 24.99, 20, 'Flathead and Phillips, cushion-grip handles', '2026-05-24 22:45:55', 'TL-002', 1, 'pcs', 1, NULL),
(55, 'Cable Ties Assorted (Pack 200)', 23, 6.99, 90, 'Nylon, mixed sizes 100mm-300mm', '2026-05-24 22:45:55', 'TL-003', 1, 'pack', 1, NULL),
(56, 'Instant Noodles Pack x30', 24, 12.99, 60, 'Chicken flavour, 85g per packet', '2026-05-24 22:45:55', 'FG-001', 1, 'pack', 1, NULL),
(57, 'Basmati Rice 5kg', 24, 8.99, 80, 'Long grain, aged basmati rice', '2026-05-24 22:45:55', 'FG-002', 1, 'pack', 1, NULL),
(58, 'Cooking Oil 1L (Case 12)', 24, 22.99, 35, 'Sunflower oil, 12 ├ù 1L per case', '2026-05-24 22:45:55', 'FG-003', 1, 'pack', 1, NULL),
(90, 'Amoxcilin 500mg', 39, 500.00, 10, NULL, '2026-05-28 13:38:42', NULL, 21, 'box', 11, NULL),
(91, 'Samsung', 4, 5000.00, 6, NULL, '2026-06-05 22:51:39', NULL, 9, 'pcs', 6, NULL),
(92, 'Banana', 42, 500.00, 20, NULL, '2026-06-06 08:47:56', NULL, 23, 'pack', 12, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpurchaseorderitems`
--

CREATE TABLE `tblpurchaseorderitems` (
  `PurchaseOrderItemID` int(11) NOT NULL,
  `PurchaseOrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `LineTotal` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpurchaseorderitems`
--

INSERT INTO `tblpurchaseorderitems` (`PurchaseOrderItemID`, `PurchaseOrderID`, `ProductID`, `Quantity`, `UnitPrice`, `LineTotal`) VALUES
(1, 1, 29, 10, 899.99, 8999.90),
(2, 2, 36, 100, 6.50, 650.00),
(3, 2, 37, 25, 12.99, 324.75),
(4, 3, 46, 120, 7.99, 958.80),
(5, 3, 47, 50, 24.99, 1249.50),
(6, 4, 33, 10, 249.99, 2499.90),
(7, 1, 29, 10, 899.99, 8999.90),
(8, 2, 36, 100, 6.50, 650.00),
(9, 2, 37, 25, 12.99, 324.75),
(10, 3, 46, 120, 7.99, 958.80),
(11, 3, 47, 50, 24.99, 1249.50),
(12, 4, 33, 10, 249.99, 2499.90),
(13, 9, 92, 10, 500.00, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblpurchaseorders`
--

CREATE TABLE `tblpurchaseorders` (
  `PurchaseOrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CompanyID` int(11) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `OrderDate` datetime NOT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Draft',
  `Notes` varchar(255) DEFAULT NULL,
  `TotalAmount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpurchaseorders`
--

INSERT INTO `tblpurchaseorders` (`PurchaseOrderID`, `UserID`, `CompanyID`, `SupplierID`, `OrderDate`, `Status`, `Notes`, `TotalAmount`, `CreatedAt`) VALUES
(1, 1, 1, 7, '2025-03-15 10:00:00', 'Received', 'Laptop batch restock Q1', 8999.90, '2026-05-24 19:53:36'),
(2, 1, 1, 8, '2025-04-01 09:00:00', 'Received', 'Q2 stationery replenishment', 975.00, '2026-05-24 19:53:36'),
(3, 1, 1, 10, '2025-05-20 14:30:00', 'Ordered', 'Beverage bulk order May', 2199.60, '2026-05-24 19:53:36'),
(4, 1, 1, 7, '2026-05-24 22:53:36', 'Draft', 'Planned monitor restock June', 2499.90, '2026-05-24 19:53:36'),
(5, 1, 1, 7, '2025-03-15 10:00:00', 'Received', 'Laptop batch restock Q1', 8999.90, '2026-05-28 07:32:31'),
(6, 1, 1, 8, '2025-04-01 09:00:00', 'Received', 'Q2 stationery replenishment', 975.00, '2026-05-28 07:32:31'),
(7, 1, 1, 10, '2025-05-20 14:30:00', 'Ordered', 'Beverage bulk order May', 2199.60, '2026-05-28 07:32:31'),
(8, 1, 1, 7, '2026-05-28 10:32:31', 'Draft', 'Planned monitor restock June', 2499.90, '2026-05-28 07:32:31'),
(9, 23, 12, NULL, '2026-06-06 07:47:56', 'Completed', NULL, 5000.00, '2026-06-06 05:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblroles`
--

CREATE TABLE `tblroles` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblroles`
--

INSERT INTO `tblroles` (`RoleID`, `RoleName`) VALUES
(1, 'Admin'),
(4, 'Customer'),
(2, 'Manager'),
(3, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `tblsalesorderitems`
--

CREATE TABLE `tblsalesorderitems` (
  `SalesOrderItemID` int(11) NOT NULL,
  `SalesOrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `LineTotal` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsalesorderitems`
--

INSERT INTO `tblsalesorderitems` (`SalesOrderItemID`, `SalesOrderID`, `ProductID`, `Quantity`, `UnitPrice`, `LineTotal`) VALUES
(1, 1, 8, 1, 0.00, 0.00),
(2, 2, 7, 1, 0.00, 0.00),
(3, 3, 1, 1, 0.00, 0.00),
(4, 4, 1, 1, 0.00, 0.00),
(5, 5, 14, 10, 25.99, 259.90),
(6, 5, 15, 2, 89.50, 179.00),
(7, 5, 17, 10, 5.50, 55.00),
(8, 6, 16, 3, 320.00, 960.00),
(9, 7, 21, 10, 25.99, 259.90),
(10, 7, 22, 2, 89.50, 179.00),
(11, 7, 24, 10, 5.50, 55.00),
(12, 8, 23, 3, 320.00, 960.00),
(13, 9, 29, 5, 899.99, 4499.95),
(14, 9, 31, 10, 99.99, 999.90),
(15, 9, 33, 6, 249.99, 1499.94),
(16, 10, 36, 40, 6.50, 260.00),
(17, 10, 37, 10, 12.99, 129.90),
(18, 11, 42, 6, 349.00, 2094.00),
(19, 12, 46, 40, 7.99, 319.60),
(20, 12, 47, 12, 24.99, 299.88),
(21, 13, 32, 10, 109.99, 1099.90),
(22, 13, 31, 10, 99.99, 999.90),
(23, 9, 29, 5, 899.99, 4499.95),
(24, 9, 31, 10, 99.99, 999.90),
(25, 9, 33, 6, 249.99, 1499.94),
(26, 10, 36, 40, 6.50, 260.00),
(27, 10, 37, 10, 12.99, 129.90),
(28, 11, 42, 6, 349.00, 2094.00),
(29, 12, 46, 40, 7.99, 319.60),
(30, 12, 47, 12, 24.99, 299.88),
(31, 13, 32, 10, 109.99, 1099.90),
(32, 13, 31, 10, 99.99, 999.90),
(33, 19, 90, 5, 500.00, 2500.00),
(34, 20, 90, 2, 600.00, 1200.00),
(35, 21, 90, 3, 1000.00, 3000.00),
(36, 22, 91, 4, 5500.06, 22000.24),
(37, 23, 92, 10, 600.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblsalesorders`
--

CREATE TABLE `tblsalesorders` (
  `SalesOrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderDate` datetime NOT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Draft',
  `Notes` varchar(255) DEFAULT NULL,
  `TotalAmount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CompanyID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsalesorders`
--

INSERT INTO `tblsalesorders` (`SalesOrderID`, `UserID`, `CustomerID`, `OrderDate`, `Status`, `Notes`, `TotalAmount`, `CreatedAt`, `CompanyID`) VALUES
(1, 7, NULL, '2026-04-22 09:46:35', 'Draft', NULL, 0.00, '2026-04-22 07:46:35', 4),
(2, 7, NULL, '2026-04-22 09:47:05', 'Draft', NULL, 0.00, '2026-04-22 07:47:05', 4),
(3, 1, NULL, '2026-05-05 14:44:17', 'Draft', NULL, 0.00, '2026-05-05 12:44:17', 1),
(4, 1, NULL, '2026-05-05 14:50:20', 'Draft', NULL, 0.00, '2026-05-05 12:50:20', 1),
(5, 1, 2, '2026-05-13 23:29:59', 'Completed', 'Expedited shipping', 493.90, '2026-05-18 21:29:59', 1),
(6, 1, 3, '2026-05-17 23:29:59', 'Pending', 'Deliver to loading dock B', 960.00, '2026-05-18 21:29:59', 1),
(7, 1, 4, '2026-05-13 23:30:48', 'Completed', 'Expedited shipping', 493.90, '2026-05-18 21:30:48', 1),
(8, 1, 5, '2026-05-17 23:30:48', 'Pending', 'Deliver to loading dock B', 960.00, '2026-05-18 21:30:48', 1),
(9, 1, 7, '2025-04-10 09:30:00', 'Completed', 'Q1 IT refresh order', 7099.92, '2026-05-24 19:53:36', 1),
(10, 1, 8, '2025-04-22 14:00:00', 'Completed', 'Monthly stationery order April', 563.30, '2026-05-24 19:53:36', 1),
(11, 1, 9, '2025-05-05 10:15:00', 'Completed', 'Office furniture fit-out', 2094.00, '2026-05-24 19:53:36', 1),
(12, 1, 10, '2025-05-18 08:45:00', 'Pending', 'Beverage restocking weekly', 619.60, '2026-05-24 19:53:36', 1),
(13, 1, 7, '2026-05-24 22:53:36', 'Draft', 'Q2 keyboard and mouse bulk request', 2099.70, '2026-05-24 19:53:36', 1),
(14, 1, 7, '2025-04-10 09:30:00', 'Completed', 'Q1 IT refresh order', 7099.92, '2026-05-28 07:32:31', 1),
(15, 1, 8, '2025-04-22 14:00:00', 'Completed', 'Monthly stationery order April', 563.30, '2026-05-28 07:32:31', 1),
(16, 1, 9, '2025-05-05 10:15:00', 'Completed', 'Office furniture fit-out', 2094.00, '2026-05-28 07:32:31', 1),
(17, 1, 10, '2025-05-18 08:45:00', 'Pending', 'Beverage restocking weekly', 619.60, '2026-05-28 07:32:31', 1),
(18, 1, 7, '2026-05-28 10:32:31', 'Draft', 'Q2 keyboard and mouse bulk request', 2099.70, '2026-05-28 07:32:31', 1),
(19, 21, NULL, '2026-05-28 12:22:34', 'Completed', NULL, 2500.00, '2026-05-28 10:22:34', 11),
(20, 21, NULL, '2026-05-28 12:24:00', 'Completed', NULL, 1200.00, '2026-05-28 10:24:00', 11),
(21, 21, NULL, '2026-05-28 12:27:34', 'Completed', NULL, 3000.00, '2026-05-28 10:27:34', 11),
(22, 9, NULL, '2026-06-05 21:51:24', 'Completed', NULL, 22000.24, '2026-06-05 19:51:24', 6),
(23, 23, 32, '2026-06-06 07:46:42', 'Completed', NULL, 6000.00, '2026-06-06 05:46:42', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblstockmovements`
--

CREATE TABLE `tblstockmovements` (
  `MovementID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Type` varchar(20) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Reason` varchar(100) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstockmovements`
--

INSERT INTO `tblstockmovements` (`MovementID`, `ProductID`, `CompanyID`, `UserID`, `Type`, `Quantity`, `Reason`, `CreatedAt`) VALUES
(1, 11, 1, 1, 'SET', 2147483647, 'Initial Stock', '2026-05-16 20:51:35'),
(2, 14, 1, 1, 'SET', 150, 'Initial Seeding', '2026-05-18 21:29:59'),
(3, 15, 1, 1, 'SET', 80, 'Initial Seeding', '2026-05-18 21:29:59'),
(4, 16, 1, 1, 'SET', 45, 'Initial Seeding', '2026-05-18 21:29:59'),
(5, 17, 1, 1, 'SET', 500, 'Initial Seeding', '2026-05-18 21:29:59'),
(6, 18, 1, 1, 'SET', 200, 'Initial Seeding', '2026-05-18 21:29:59'),
(7, 19, 1, 1, 'SET', 30, 'Initial Seeding', '2026-05-18 21:29:59'),
(8, 20, 1, 1, 'SET', 20, 'Initial Seeding', '2026-05-18 21:29:59'),
(9, 21, 1, 1, 'SET', 150, 'Initial Seeding', '2026-05-18 21:30:48'),
(10, 22, 1, 1, 'SET', 80, 'Initial Seeding', '2026-05-18 21:30:48'),
(11, 23, 1, 1, 'SET', 45, 'Initial Seeding', '2026-05-18 21:30:48'),
(12, 24, 1, 1, 'SET', 500, 'Initial Seeding', '2026-05-18 21:30:48'),
(13, 25, 1, 1, 'SET', 200, 'Initial Seeding', '2026-05-18 21:30:48'),
(14, 26, 1, 1, 'SET', 30, 'Initial Seeding', '2026-05-18 21:30:48'),
(15, 27, 1, 1, 'SET', 20, 'Initial Seeding', '2026-05-18 21:30:48'),
(16, 11, 1, 1, 'OUT', 2147483627, 'Product Edit', '2026-05-18 21:37:25'),
(17, 28, 10, 18, 'SET', 30, 'Initial Stock', '2026-05-22 07:51:38'),
(18, 28, 10, 18, 'IN', 11, '', '2026-05-22 07:52:00'),
(19, 29, 1, 1, 'SET', 12, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(20, 30, 1, 1, 'SET', 8, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(21, 31, 1, 1, 'SET', 45, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(22, 32, 1, 1, 'SET', 30, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(23, 33, 1, 1, 'SET', 18, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(24, 36, 1, 1, 'SET', 250, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(25, 37, 1, 1, 'SET', 40, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(26, 42, 1, 1, 'SET', 15, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(27, 46, 1, 1, 'SET', 120, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(28, 47, 1, 1, 'SET', 30, 'Initial stock ÔÇô seed', '2026-05-24 19:53:36'),
(29, 29, 1, 1, 'SET', 12, 'Initial stock – seed', '2026-05-28 07:32:31'),
(30, 30, 1, 1, 'SET', 8, 'Initial stock – seed', '2026-05-28 07:32:31'),
(31, 31, 1, 1, 'SET', 45, 'Initial stock – seed', '2026-05-28 07:32:31'),
(32, 32, 1, 1, 'SET', 30, 'Initial stock – seed', '2026-05-28 07:32:31'),
(33, 33, 1, 1, 'SET', 18, 'Initial stock – seed', '2026-05-28 07:32:31'),
(34, 36, 1, 1, 'SET', 250, 'Initial stock – seed', '2026-05-28 07:32:31'),
(35, 37, 1, 1, 'SET', 40, 'Initial stock – seed', '2026-05-28 07:32:31'),
(36, 42, 1, 1, 'SET', 15, 'Initial stock – seed', '2026-05-28 07:32:31'),
(37, 46, 1, 1, 'SET', 120, 'Initial stock – seed', '2026-05-28 07:32:31'),
(38, 47, 1, 1, 'SET', 30, 'Initial stock – seed', '2026-05-28 07:32:31'),
(39, 16, 1, 1, 'OUT', 10, '', '2026-05-28 10:15:52'),
(40, 90, 11, 21, 'SET', 10, 'Initial Stock', '2026-05-28 10:20:35'),
(41, 90, 11, 21, 'OUT', 5, 'Sales Order #19', '2026-05-28 10:22:34'),
(42, 90, 11, 21, 'OUT', 2, 'Sales Order #20', '2026-05-28 10:24:00'),
(43, 90, 11, 21, 'OUT', 3, 'Sales Order #21', '2026-05-28 10:27:34'),
(44, 90, 11, 21, 'IN', 10, '', '2026-05-28 10:38:42'),
(45, 91, 6, 9, 'SET', 10, 'Initial Stock', '2026-06-05 19:49:59'),
(46, 91, 6, 9, 'OUT', 4, 'SO#22 → Completed', '2026-06-05 19:51:39'),
(47, 92, 12, 23, 'SET', 20, 'Initial Stock', '2026-06-06 05:43:52'),
(48, 92, 12, 23, 'OUT', 10, 'Sales Order #23', '2026-06-06 05:46:42'),
(49, 92, 12, 23, 'IN', 10, 'Purchase Order #9', '2026-06-06 05:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblsuppliers`
--

CREATE TABLE `tblsuppliers` (
  `SupplierID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `SupplierName` varchar(120) NOT NULL,
  `ContactPerson` varchar(120) DEFAULT NULL,
  `Phone` varchar(40) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CompanyID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsuppliers`
--

INSERT INTO `tblsuppliers` (`SupplierID`, `UserID`, `SupplierName`, `ContactPerson`, `Phone`, `Email`, `Address`, `CreatedAt`, `CompanyID`) VALUES
(1, 1, 'OMAR', NULL, NULL, NULL, NULL, '2026-04-09 06:06:12', 1),
(2, 31, 'Gemme', '0973650492', '0973650492', 'gemme@gmail.com', 'Jimma', '2026-04-22 07:33:31', NULL),
(3, 1, 'ElectroSupply Inc', 'Alice Smith', '555-9090', 'sales@electrosupply.com', '11 Factory St, NJ', '2026-05-18 21:29:59', 1),
(4, 1, 'Office Depot Wholesale', 'Bob Johnson', '555-8080', 'b2b@officedepot.com', '22 Warehouse Ln, OH', '2026-05-18 21:29:59', 1),
(5, 1, 'ElectroSupply Inc', 'Alice Smith', '555-9090', 'sales@electrosupply.com', '11 Factory St, NJ', '2026-05-18 21:30:48', 1),
(6, 1, 'Office Depot Wholesale', 'Bob Johnson', '555-8080', 'b2b@officedepot.com', '22 Warehouse Ln, OH', '2026-05-18 21:30:48', 1),
(7, 1, 'TechSource Global Ltd', 'James Okonkwo', '+1-800-555-0101', 'james@techsource.com', '12 Silicon Ave, San Jose, CA 95112', '2026-05-24 19:45:55', 1),
(8, 1, 'Office World Distributors', 'Sarah Mitchell', '+44-20-7946-0102', 'sarah@officeworld.co.uk', '45 Fleet Street, London EC4Y 1AA', '2026-05-24 19:45:55', 1),
(9, 1, 'FurniPro Wholesale', 'Carlos Mendez', '+1-312-555-0183', 'carlos@furnipro.com', '800 N Michigan Ave, Chicago, IL 60611', '2026-05-24 19:45:55', 1),
(10, 1, 'BevMax Importers', 'Amara Hassan', '+971-4-555-0204', 'amara@bevmax.ae', 'JAFZA Block C, Dubai, UAE', '2026-05-24 19:45:55', 1),
(11, 1, 'Apex Clothing Co.', 'Lin Wei', '+86-21-5555-0105', 'linwei@apexcloth.cn', '88 Renmin Rd, Guangzhou 510000', '2026-05-24 19:45:55', 1),
(12, 1, 'Industrial Tools Depot', 'Paul Kimani', '+254-20-5550-106', 'paul@toolsdepot.co.ke', 'Industrial Area, Nairobi 00506', '2026-05-24 19:45:55', 1),
(13, 1, 'FreshFood Traders', 'Fatima Al-Amin', '+20-2-2555-0107', 'fatima@freshfood.eg', '15 Port Said St, Cairo, Egypt', '2026-05-24 19:45:55', 1),
(14, 1, 'TechSource Global Ltd', 'James Okonkwo', '+1-800-555-0101', 'james@techsource.com', '12 Silicon Ave, San Jose, CA 95112', '2026-05-24 19:53:36', 1),
(15, 1, 'Office World Distributors', 'Sarah Mitchell', '+44-20-7946-0102', 'sarah@officeworld.co.uk', '45 Fleet Street, London EC4Y 1AA', '2026-05-24 19:53:36', 1),
(16, 1, 'FurniPro Wholesale', 'Carlos Mendez', '+1-312-555-0183', 'carlos@furnipro.com', '800 N Michigan Ave, Chicago, IL 60611', '2026-05-24 19:53:36', 1),
(17, 1, 'BevMax Importers', 'Amara Hassan', '+971-4-555-0204', 'amara@bevmax.ae', 'JAFZA Block C, Dubai, UAE', '2026-05-24 19:53:36', 1),
(18, 1, 'Apex Clothing Co.', 'Lin Wei', '+86-21-5555-0105', 'linwei@apexcloth.cn', '88 Renmin Rd, Guangzhou 510000', '2026-05-24 19:53:36', 1),
(19, 1, 'Industrial Tools Depot', 'Paul Kimani', '+254-20-5550-106', 'paul@toolsdepot.co.ke', 'Industrial Area, Nairobi 00506', '2026-05-24 19:53:36', 1),
(20, 1, 'FreshFood Traders', 'Fatima Al-Amin', '+20-2-2555-0107', 'fatima@freshfood.eg', '15 Port Said St, Cairo, Egypt', '2026-05-24 19:53:36', 1),
(21, 1, 'TechSource Global Ltd', 'James Okonkwo', '+1-800-555-0101', 'james@techsource.com', '12 Silicon Ave, San Jose, CA 95112', '2026-05-28 07:32:31', 1),
(22, 1, 'Office World Distributors', 'Sarah Mitchell', '+44-20-7946-0102', 'sarah@officeworld.co.uk', '45 Fleet Street, London EC4Y 1AA', '2026-05-28 07:32:31', 1),
(23, 1, 'FurniPro Wholesale', 'Carlos Mendez', '+1-312-555-0183', 'carlos@furnipro.com', '800 N Michigan Ave, Chicago, IL 60611', '2026-05-28 07:32:31', 1),
(24, 1, 'BevMax Importers', 'Amara Hassan', '+971-4-555-0204', 'amara@bevmax.ae', 'JAFZA Block C, Dubai, UAE', '2026-05-28 07:32:31', 1),
(25, 1, 'Apex Clothing Co.', 'Lin Wei', '+86-21-5555-0105', 'linwei@apexcloth.cn', '88 Renmin Rd, Guangzhou 510000', '2026-05-28 07:32:31', 1),
(26, 1, 'Industrial Tools Depot', 'Paul Kimani', '+254-20-5550-106', 'paul@toolsdepot.co.ke', 'Industrial Area, Nairobi 00506', '2026-05-28 07:32:31', 1),
(27, 1, 'FreshFood Traders', 'Fatima Al-Amin', '+20-2-2555-0107', 'fatima@freshfood.eg', '15 Port Said St, Cairo, Egypt', '2026-05-28 07:32:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbltax_audit_cases`
--

CREATE TABLE `tbltax_audit_cases` (
  `AuditCaseID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `CaseRef` varchar(30) NOT NULL COMMENT 'e.g. AUD-2025-0001',
  `AuditType` enum('desk','field') NOT NULL DEFAULT 'desk',
  `AuditStatus` enum('open','in_review','objection','appeal','closed','escalated') NOT NULL DEFAULT 'open',
  `TaxYearStart` date NOT NULL,
  `TaxYearEnd` date NOT NULL,
  `FilingDate` date DEFAULT NULL COMMENT 'Original return filing date',
  `SOL5YearDeadline` date DEFAULT NULL COMMENT '5-yr standard SOL per Art.86',
  `SOL10YearDeadline` date DEFAULT NULL COMMENT '10-yr fraud/willful default SOL',
  `IsFraudCase` tinyint(1) NOT NULL DEFAULT 0,
  `AssignedTo` int(11) DEFAULT NULL COMMENT 'Auditor UserID',
  `EvidenceFrozen` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Freeze point: no new evidence after initial assessment',
  `EvidenceFreezeDate` datetime DEFAULT NULL,
  `AssessedTax` decimal(18,2) NOT NULL DEFAULT 0.00,
  `AssessedPenalty` decimal(18,2) NOT NULL DEFAULT 0.00,
  `Notes` text DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltax_declarations`
--

CREATE TABLE `tbltax_declarations` (
  `DeclarationID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `FiscalYear` year(4) NOT NULL,
  `Quarter` tinyint(4) NOT NULL COMMENT '1=Q1,2=Q2,3=Q3,4=Q4',
  `DueDate` date NOT NULL,
  `PaidDate` date DEFAULT NULL,
  `AmountDue` decimal(18,2) NOT NULL DEFAULT 0.00 COMMENT '25% of PriorYearTax per Proclamation 983/2016',
  `AmountPaid` decimal(18,2) NOT NULL DEFAULT 0.00,
  `PaymentMethod` enum('bank','cash','digital','none') NOT NULL DEFAULT 'none',
  `CashAmount` decimal(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Cash portion – flagged if >50000 ETB',
  `Status` enum('pending','paid','overdue','partial') NOT NULL DEFAULT 'pending',
  `Notes` text DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltax_declarations`
--

INSERT INTO `tbltax_declarations` (`DeclarationID`, `CompanyID`, `FiscalYear`, `Quarter`, `DueDate`, `PaidDate`, `AmountDue`, `AmountPaid`, `PaymentMethod`, `CashAmount`, `Status`, `Notes`, `CreatedBy`, `CreatedAt`) VALUES
(11, 6, '2026', 1, '2026-04-30', '2026-04-28', 30000.00, 30000.00, 'bank', 0.00, 'paid', 'Q1 Payment - Wire Transfer #402910', 9, '2026-05-29 18:15:10'),
(12, 6, '2026', 2, '2026-07-30', '2026-07-29', 30000.00, 30000.00, 'digital', 0.00, 'paid', 'Q2 Payment - Integrated Telebirr E-Receipt', 9, '2026-05-29 18:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbltax_profiles`
--

CREATE TABLE `tbltax_profiles` (
  `TaxProfileID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `TIN` varchar(20) DEFAULT NULL COMMENT 'Taxpayer Identification Number',
  `TaxCategory` enum('A','B') NOT NULL DEFAULT 'B' COMMENT 'A=Turnover>2M ETB or body; B=Turnover<2M ETB',
  `AnnualTurnover` decimal(18,2) NOT NULL DEFAULT 0.00,
  `PriorYearTax` decimal(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax paid in prior fiscal year (for 25% advance calc)',
  `PEDays` int(11) NOT NULL DEFAULT 0 COMMENT 'Permanent Establishment presence days (91-day threshold)',
  `IsNonResident` tinyint(1) NOT NULL DEFAULT 0,
  `FiscalYearStart` date DEFAULT NULL,
  `FiscalYearEnd` date DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IsCorporateBody` tinyint(1) NOT NULL DEFAULT 0,
  `IsProfessionalServices` tinyint(1) NOT NULL DEFAULT 0,
  `IsVatRegistered` tinyint(1) NOT NULL DEFAULT 0,
  `OptInNetProfit` tinyint(1) NOT NULL DEFAULT 0,
  `DerashReferenceCode` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltax_profiles`
--

INSERT INTO `tbltax_profiles` (`TaxProfileID`, `CompanyID`, `TIN`, `TaxCategory`, `AnnualTurnover`, `PriorYearTax`, `PEDays`, `IsNonResident`, `FiscalYearStart`, `FiscalYearEnd`, `CreatedAt`, `UpdatedAt`, `IsCorporateBody`, `IsProfessionalServices`, `IsVatRegistered`, `OptInNetProfit`, `DerashReferenceCode`) VALUES
(1, 6, '1002345678', 'A', 4500000.00, 120000.00, 120, 1, '2026-01-01', '2026-12-31', '2026-05-28 09:47:18', '2026-05-28 09:47:18', 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbltax_risk_flags`
--

CREATE TABLE `tbltax_risk_flags` (
  `FlagID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `FlagType` varchar(60) NOT NULL COMMENT 'AMT_BREACH|PE_THRESHOLD|CASH_EXCESS|WHT_GAP|CATEGORY_SHIFT',
  `Severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `RiskScore` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Weighted score 0-100',
  `Description` text NOT NULL,
  `FiscalYear` year(4) DEFAULT NULL,
  `Quarter` tinyint(4) DEFAULT NULL,
  `DetectedValue` decimal(18,2) DEFAULT NULL COMMENT 'The value that triggered the flag',
  `LegalThreshold` decimal(18,2) DEFAULT NULL COMMENT 'The legal limit that was breached',
  `LegalReference` varchar(120) DEFAULT NULL COMMENT 'e.g. Proc 979/2016 Art.14',
  `IsResolved` tinyint(1) NOT NULL DEFAULT 0,
  `ResolvedAt` datetime DEFAULT NULL,
  `ResolvedBy` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltax_risk_flags`
--

INSERT INTO `tbltax_risk_flags` (`FlagID`, `CompanyID`, `FlagType`, `Severity`, `RiskScore`, `Description`, `FiscalYear`, `Quarter`, `DetectedValue`, `LegalThreshold`, `LegalReference`, `IsResolved`, `ResolvedAt`, `ResolvedBy`, `CreatedAt`) VALUES
(4, 6, 'AMT_BREACH', 'high', 35.00, 'Alternative Minimum Tax breach. Current tax paid is only 2.00% of turnover, which is below the statutory 2.5% threshold.', NULL, NULL, 0.02, 0.03, 'Proc. 979/2016 Art. 14', 1, '2026-05-28 13:00:18', 9, '2026-05-28 09:59:13'),
(5, 6, 'PE_THRESHOLD', 'critical', 45.00, 'Permanent Establishment threshold breached. Operational presence is 120 days, exceeding the newly revised 91-day limit for non-residents.', NULL, NULL, 120.00, 91.00, 'Proc. 1395/2025 (Amended)', 1, '2026-05-28 13:00:01', 9, '2026-05-28 09:59:13'),
(6, 6, 'CASH_EXCESS', 'high', 30.00, 'Compliance Violation: Cash transaction of ETB 65,000.00 was logged for Q2 2026, breaching the legal ETB 50,000 transaction ceiling.', NULL, NULL, 65000.00, 50000.00, 'Proc. 983/2016 Cash Limit', 1, '2026-05-28 13:00:07', 9, '2026-05-28 09:59:13'),
(13, 6, 'AMT_BREACH', 'high', 35.00, 'Alternative Minimum Tax breach. Calculated business tax due is below the 2.5% statutory AMT gross baseline of ETB 112,500.00. eTax Audit risk high.', NULL, NULL, 60000.00, 112500.00, 'Proc. 979/2016 Art. 14', 0, NULL, NULL, '2026-05-29 18:15:10'),
(14, 6, 'PE_THRESHOLD', 'critical', 45.00, 'Permanent Establishment Limit Exceeded: Operational presence of non-resident is 120 days, breaching the new Proclamation 1395/2025 91-day PE trigger threshold.', NULL, NULL, 120.00, 91.00, 'Proc. 1395/2025 PE Limit', 0, NULL, NULL, '2026-05-29 18:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `CompanyID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`UserID`, `FullName`, `UserName`, `Password`, `RoleID`, `CompanyID`) VALUES
(1, 'Administrator', 'admin', '$2y$10$P3Gw2h.b88OG9XIC6x/2j.QlSfNyz1nS49gQFDm1ZP5LIqWa1d0pO', 1, 1),
(5, 'Amir', 'Adem', '$2y$10$Tmc8ScrBx2arRDX7NfG7CuUc9JNv5Ji25aniCzI/R6/g/EwdvU8s6', 1, 2),
(6, 'Amma', 'amita', '$2y$10$Ln45yTKIyi7IjheBW86LWOZl2foeP7279/FLsZU.XHld6.f6CMS8C', 1, 3),
(7, 'geme', 'gemegmail.com', '$2y$10$NhbXJ9mxrruRpBPudhyZZeiAvN2MbiqT0snwam0MyZul4m/JK7SL2', 1, 4),
(8, 'nebil', 'neba@22', '$2y$10$RReRY/lsWpr7kmhYkuQkr.rE.ajX5nIiBzJXgxX6z5asZgZ/OfzQK', 1, 5),
(9, 'Amir', 'amir@gmail.com', '$2y$10$0kMtsdqoUQvZwg387jYtG.Z6UHWbtObvhVr7DHCax0Rkk.5G.sTQK', 1, 6),
(11, 'Aziza', 'aziti', '$2y$10$93xP7KJ2LUnKsRFLGPzRbeEaFgxaG4HYuOjuoABil7bM3VfifDc.6', 4, 6),
(12, 'Admin', 'admin1', '$2y$10$E6klpBgOK5JOOcWmgTEpT.Yt2AIAyONQisHp16Un9xN9y/KRNCtSi', 1, 7),
(13, 'Adem', 'adem@gmail.com', '$2y$10$8paDeMnOoAs7daWqD/k3Y.aUBj44n5DmrxSEyibxxsSchiQEr/WX2', 1, 8),
(14, 'Oracle NetSuite', 'netsuite', '$2y$10$W2QmiM7pkFKuH/RrMiJWnOzSGcLfOM38ti8lUEGYV0NvKoIA.qy/W', 1, 9),
(16, 'Nebile Adem', 'neba', '$2y$10$VW9OIbOwCOhX8dmKpgLlneDrKE22jxfwfei2imp2T/EJUkSQNooTe', 4, 6),
(17, 'geme', 'geme', '$2y$10$NrWeql0mLc0OYd2Y1/L9G.y2SJw/RB9AunWtTTWqUawt5H3Kzkxda', 4, 6),
(18, 'Liya Girma', 'Liya', '$2y$10$oLEWMqzRrRA/oEVPvJY.GulMJtJUc3jvJ0nar6TeFtcw9CDEtyzXe', 1, 10),
(19, 'Amir', 'Amir', '$2y$10$eSAY5IhC7p/AFOUrQA67gOaFDsv79qsTnvuSGYB64ILMAQOZsuUCW', 4, 10),
(20, 'yoni', 'yoni', '$2y$10$Oq4Ut./ncF.qPZP.PGIJrOnhms2KDMZQTQTOclStoYd.TGRbbYbeG', 3, 6),
(21, 'Nebil', 'Nebati', '$2y$10$RZG9ACCwEzTs6RRJWYaHk.Lw.huHiEeKWSQTI1NzEp1xzzwNbOZry', 1, 11),
(22, 'Abduljelil', 'Abdu', '$2y$10$qPHaIjcS/PiGdbnWv0BsN.MNXkmIOw4BC1ts2PbRYPKTtY/84Co5.', 4, 6),
(23, 'Meron', 'meri', '$2y$10$6.F5FlYN9kpvwn8/vzOaQ./cimP9AzM7O5e7mYOnLk6hFmXUN0ham', 1, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbcategory`
--
ALTER TABLE `tbcategory`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `tblcategories`
--
ALTER TABLE `tblcategories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `idx_categories_user` (`UserID`),
  ADD KEY `idx_cat_company` (`CompanyID`);

--
-- Indexes for table `tblchat`
--
ALTER TABLE `tblchat`
  ADD PRIMARY KEY (`ChatID`),
  ADD KEY `idx_chat_company` (`CompanyID`),
  ADD KEY `idx_chat_recipient` (`RecipientID`);

--
-- Indexes for table `tblchat_presence`
--
ALTER TABLE `tblchat_presence`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `idx_presence_company` (`CompanyID`);

--
-- Indexes for table `tblchat_reactions`
--
ALTER TABLE `tblchat_reactions`
  ADD PRIMARY KEY (`ChatID`,`UserID`,`Emoji`),
  ADD KEY `idx_reaction_chat` (`ChatID`);

--
-- Indexes for table `tblchat_reads`
--
ALTER TABLE `tblchat_reads`
  ADD PRIMARY KEY (`UserID`,`CompanyID`);

--
-- Indexes for table `tblcompanies`
--
ALTER TABLE `tblcompanies`
  ADD PRIMARY KEY (`CompanyID`);

--
-- Indexes for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `idx_customers_user` (`UserID`);

--
-- Indexes for table `tblpassword_resets`
--
ALTER TABLE `tblpassword_resets`
  ADD PRIMARY KEY (`ResetID`),
  ADD UNIQUE KEY `Token` (`Token`),
  ADD KEY `idx_reset_token` (`Token`),
  ADD KEY `idx_reset_user` (`UserID`);

--
-- Indexes for table `tblproducts`
--
ALTER TABLE `tblproducts`
  ADD PRIMARY KEY (`ProductID`),
  ADD UNIQUE KEY `SKU` (`SKU`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `idx_products_category` (`CategoryID`),
  ADD KEY `idx_prod_company` (`CompanyID`);

--
-- Indexes for table `tblpurchaseorderitems`
--
ALTER TABLE `tblpurchaseorderitems`
  ADD PRIMARY KEY (`PurchaseOrderItemID`),
  ADD KEY `idx_po_items_order` (`PurchaseOrderID`),
  ADD KEY `idx_po_items_product` (`ProductID`);

--
-- Indexes for table `tblpurchaseorders`
--
ALTER TABLE `tblpurchaseorders`
  ADD PRIMARY KEY (`PurchaseOrderID`),
  ADD KEY `idx_po_user` (`UserID`),
  ADD KEY `idx_po_company` (`CompanyID`),
  ADD KEY `idx_po_supplier` (`SupplierID`);

--
-- Indexes for table `tblroles`
--
ALTER TABLE `tblroles`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- Indexes for table `tblsalesorderitems`
--
ALTER TABLE `tblsalesorderitems`
  ADD PRIMARY KEY (`SalesOrderItemID`),
  ADD KEY `idx_so_items_order` (`SalesOrderID`),
  ADD KEY `idx_so_items_product` (`ProductID`);

--
-- Indexes for table `tblsalesorders`
--
ALTER TABLE `tblsalesorders`
  ADD PRIMARY KEY (`SalesOrderID`),
  ADD KEY `idx_sales_user` (`UserID`),
  ADD KEY `idx_sales_customer` (`CustomerID`);

--
-- Indexes for table `tblstockmovements`
--
ALTER TABLE `tblstockmovements`
  ADD PRIMARY KEY (`MovementID`),
  ADD KEY `idx_move_product` (`ProductID`),
  ADD KEY `idx_move_company` (`CompanyID`);

--
-- Indexes for table `tblsuppliers`
--
ALTER TABLE `tblsuppliers`
  ADD PRIMARY KEY (`SupplierID`),
  ADD KEY `idx_suppliers_user` (`UserID`);

--
-- Indexes for table `tbltax_audit_cases`
--
ALTER TABLE `tbltax_audit_cases`
  ADD PRIMARY KEY (`AuditCaseID`),
  ADD UNIQUE KEY `CaseRef` (`CaseRef`),
  ADD KEY `idx_audit_company` (`CompanyID`),
  ADD KEY `idx_audit_status` (`AuditStatus`);

--
-- Indexes for table `tbltax_declarations`
--
ALTER TABLE `tbltax_declarations`
  ADD PRIMARY KEY (`DeclarationID`),
  ADD KEY `idx_decl_company` (`CompanyID`),
  ADD KEY `idx_decl_year_q` (`FiscalYear`,`Quarter`);

--
-- Indexes for table `tbltax_profiles`
--
ALTER TABLE `tbltax_profiles`
  ADD PRIMARY KEY (`TaxProfileID`),
  ADD UNIQUE KEY `CompanyID` (`CompanyID`),
  ADD KEY `idx_tax_company` (`CompanyID`);

--
-- Indexes for table `tbltax_risk_flags`
--
ALTER TABLE `tbltax_risk_flags`
  ADD PRIMARY KEY (`FlagID`),
  ADD KEY `idx_flag_company` (`CompanyID`),
  ADD KEY `idx_flag_type` (`FlagType`),
  ADD KEY `idx_flag_resolved` (`IsResolved`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD KEY `RoleID` (`RoleID`),
  ADD KEY `idx_users_company` (`CompanyID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbcategory`
--
ALTER TABLE `tbcategory`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcategories`
--
ALTER TABLE `tblcategories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tblchat`
--
ALTER TABLE `tblchat`
  MODIFY `ChatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcompanies`
--
ALTER TABLE `tblcompanies`
  MODIFY `CompanyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tblpassword_resets`
--
ALTER TABLE `tblpassword_resets`
  MODIFY `ResetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblproducts`
--
ALTER TABLE `tblproducts`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tblpurchaseorderitems`
--
ALTER TABLE `tblpurchaseorderitems`
  MODIFY `PurchaseOrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblpurchaseorders`
--
ALTER TABLE `tblpurchaseorders`
  MODIFY `PurchaseOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblroles`
--
ALTER TABLE `tblroles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblsalesorderitems`
--
ALTER TABLE `tblsalesorderitems`
  MODIFY `SalesOrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tblsalesorders`
--
ALTER TABLE `tblsalesorders`
  MODIFY `SalesOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblstockmovements`
--
ALTER TABLE `tblstockmovements`
  MODIFY `MovementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tblsuppliers`
--
ALTER TABLE `tblsuppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbltax_audit_cases`
--
ALTER TABLE `tbltax_audit_cases`
  MODIFY `AuditCaseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbltax_declarations`
--
ALTER TABLE `tbltax_declarations`
  MODIFY `DeclarationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbltax_profiles`
--
ALTER TABLE `tbltax_profiles`
  MODIFY `TaxProfileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbltax_risk_flags`
--
ALTER TABLE `tbltax_risk_flags`
  MODIFY `FlagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`RoleID`) REFERENCES `tblroles` (`RoleID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
