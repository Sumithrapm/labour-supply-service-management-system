-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 04:32 AM
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
-- Database: `lssemsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(100) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 9876543210, 'admin@lssems.com', '$2y$10$Y.FlEpAOh3s5wD6qxjpV5OWpjkFPaQevSvYwMEnP/5ttkipyoN4Ru', '2025-12-10 14:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `ID` int(10) NOT NULL,
  `BookingNumber` varchar(100) DEFAULT NULL,
  `UserID` int(10) DEFAULT NULL,
  `WorkerID` int(10) DEFAULT NULL,
  `ServiceDate` date DEFAULT NULL,
  `ServiceTime` varchar(50) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `TotalHours` decimal(5,2) DEFAULT 0.00,
  `WorkDescription` text DEFAULT NULL,
  `BookingDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Waiting for Approval','Approved','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `WorkerStatus` enum('Pending','Accepted','Rejected','In Progress','Completed') DEFAULT 'Pending',
  `Address` text DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `AdvanceAmount` decimal(10,2) DEFAULT 0.00,
  `PaymentDate` datetime DEFAULT NULL,
  `RemainingAmount` decimal(10,2) DEFAULT 0.00,
  `AdvancePaidDate` datetime DEFAULT NULL,
  `FinalPaidDate` datetime DEFAULT NULL,
  `PaymentStatus` enum('Pending','Paid','Refunded') DEFAULT 'Pending',
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `TransactionID` varchar(255) DEFAULT NULL,
  `PaymentReceived` enum('No','Partial','Full') DEFAULT 'No',
  `Remark` text DEFAULT NULL,
  `RejectionReason` text DEFAULT NULL,
  `UpdatedDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`ID`, `BookingNumber`, `UserID`, `WorkerID`, `ServiceDate`, `ServiceTime`, `StartTime`, `EndTime`, `TotalHours`, `WorkDescription`, `BookingDate`, `Status`, `WorkerStatus`, `Address`, `City`, `Amount`, `AdvanceAmount`, `PaymentDate`, `RemainingAmount`, `AdvancePaidDate`, `FinalPaidDate`, `PaymentStatus`, `PaymentMethod`, `TransactionID`, `PaymentReceived`, `Remark`, `RejectionReason`, `UpdatedDate`) VALUES
(10, 'BK202512198932', 6, 7, '2025-12-20', '19:04', NULL, NULL, 0.00, 'clean house', '2025-12-19 11:33:21', 'Waiting for Approval', 'Pending', 'palkakal House\r\nThippalur', 'Thrissur', 1000.00, 500.00, NULL, 500.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, NULL, NULL),
(11, 'BK202512197913', 6, 9, '2025-12-24', '03:04', NULL, NULL, 0.00, 'kerala dish', '2025-12-19 11:34:48', 'Approved', 'Accepted', 'Tippalur,erumapetty', 'Thrissur', 1000.00, 800.00, NULL, 200.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, NULL, '2025-12-19 11:36:27'),
(12, 'BK202512214496', 7, 6, '2025-12-22', '15:30', '2026-01-09 06:05:33', '2026-01-09 06:05:37', 0.00, 'carpenting', '2025-12-21 05:54:26', 'Completed', 'Completed', 'thrissur\r\n579522', 'Thrissur', 0.00, 1000.00, NULL, -1000.00, NULL, '2026-01-09 10:35:41', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2026-01-09 05:05:41'),
(13, 'BK202512217025', 7, 9, '2025-12-23', '14:30', '2025-12-21 07:04:50', NULL, 0.00, 'traditional food ', '2025-12-21 06:00:26', 'In Progress', 'In Progress', 'kunnamkulam', 'Thrissur', 2500.00, 800.00, NULL, 1700.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, NULL, '2025-12-21 06:04:50'),
(14, 'BK202512299196', 8, 6, '2025-12-29', '10:40', '2025-12-29 05:12:07', '2025-12-29 05:12:09', 0.00, 'cupboards ', '2025-12-29 04:10:53', 'Completed', 'Completed', 'thrissur', 'Thrissur', 0.00, 1000.00, NULL, -1000.00, NULL, '2025-12-29 09:42:12', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2025-12-29 04:12:12'),
(15, 'BK202512292280', 8, 6, '2025-12-29', '10:46', '2025-12-29 06:25:19', '2025-12-29 06:25:22', 0.00, 'cupboard\r\n', '2025-12-29 05:17:05', 'Completed', 'Completed', 'thrissur', 'Thrissur', 0.00, 1000.00, NULL, -1000.00, NULL, '2025-12-30 08:53:49', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2025-12-30 03:23:49'),
(16, 'BK202512301994', 8, 6, '2025-12-30', '12:08', NULL, NULL, 0.00, 'furniture', '2025-12-30 06:39:43', 'Cancelled', 'Rejected', 'thrissur', 'thrissur', 3000.00, 1000.00, NULL, 2000.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy\r\n', '2026-01-08 09:54:50'),
(17, 'BK202512303434', 8, 6, '2025-12-30', '12:08', '2026-01-09 06:05:16', '2026-01-09 06:05:20', 0.00, 'furniture', '2025-12-30 06:55:15', 'Completed', 'Completed', 'thrissur', 'thrissur', 0.00, 1000.00, '2026-01-09 10:49:48', -1000.00, NULL, '2026-01-09 13:34:05', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 08:04:05'),
(18, 'BK202512301050', 8, 6, '2025-12-30', '14:27', NULL, NULL, 0.00, 'drgdgd', '2025-12-30 06:56:37', 'Cancelled', 'Rejected', 'kunnamkulam', 'Kunnamkulam', 2000.00, 1000.00, NULL, 1000.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy\r\n', '2026-01-08 09:54:37'),
(19, 'BK202512309003', 8, 6, '2025-12-30', '12:34', '2026-01-08 11:01:58', '2026-01-08 11:02:00', 0.00, 'ASDASD', '2025-12-30 07:04:49', 'Completed', 'Completed', 'THRISSUR', 'THRISSUR', 0.00, 1000.00, NULL, -1000.00, NULL, '2026-01-09 10:03:17', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2026-01-09 04:33:17'),
(20, 'BK202601087149', 8, 6, '2026-01-12', '15:39', '2026-01-09 06:04:47', '2026-01-09 06:04:53', 0.00, '21313', '2026-01-08 10:10:34', 'Completed', 'Completed', 'erumapetty', 'Erumapetty ', 0.00, 1000.00, NULL, -1000.00, NULL, '2026-01-09 10:34:57', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2026-01-09 05:04:57'),
(21, 'BK202601082795', 8, 6, '2026-01-08', '15:48', '2026-01-08 11:23:18', '2026-01-08 11:24:14', 0.00, 'wardrobe', '2026-01-08 10:18:34', 'Completed', 'Completed', 'Erumapetty', 'Erumapetty ', 0.00, 1000.00, NULL, -1000.00, NULL, '2026-01-08 15:54:40', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2026-01-08 10:24:40'),
(22, 'BK202601094147', 8, 6, '2026-01-09', '09:33', '2026-01-09 06:04:17', '2026-01-09 06:04:19', 0.00, 'cupboard work', '2026-01-09 04:04:13', 'Completed', 'Completed', 'Erumapetty ', 'Erumapetty ', 0.00, 0.00, NULL, 0.00, NULL, '2026-01-09 10:35:24', 'Paid', NULL, NULL, 'Full', NULL, NULL, '2026-01-09 05:05:24'),
(23, 'BK202601093153', 8, 6, '2026-01-09', '10:51', '2026-01-09 06:23:23', '2026-01-09 06:23:31', 0.00, 'shelves', '2026-01-09 05:22:21', 'Completed', 'Completed', 'Erumapetty', 'Erumapetty', 0.00, 0.00, '2026-01-09 10:54:56', 0.00, NULL, '2026-01-09 10:55:25', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 05:25:25'),
(24, 'BK202601091665', 8, 6, '2026-01-09', '10:59', '2026-01-09 06:30:34', '2026-01-09 06:30:35', 0.00, 'kitchen cupboard', '2026-01-09 05:30:23', 'Completed', 'Completed', 'Erumapetty ', 'Erumapetty', 0.00, 0.00, '2026-01-09 11:00:59', 0.00, NULL, '2026-01-09 12:05:05', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 06:35:05'),
(25, 'BK202601093688', 8, 6, '2026-01-09', '11:11', '2026-01-09 07:03:50', '2026-01-09 07:03:58', 0.00, 'kitchen shelves', '2026-01-09 05:44:57', 'Completed', 'Completed', 'Erumapetty ', 'Erumapetty', 0.00, 0.00, '2026-01-09 11:39:37', 0.00, NULL, '2026-01-09 12:05:09', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 06:35:09'),
(26, 'BK202601097317', 8, 6, '2026-01-09', '11:11', '2026-01-09 06:50:09', '2026-01-09 06:50:11', 0.00, 'kitchen shelves', '2026-01-09 05:49:57', 'Completed', 'Completed', 'Erumapetty ', 'Erumapetty', 0.00, 0.00, '2026-01-09 11:21:23', 0.00, NULL, '2026-01-09 12:05:13', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 06:35:13'),
(27, 'BK202601098360', 8, 6, '2026-01-09', '11:41', '2026-01-09 07:15:52', '2026-01-09 07:16:48', 0.00, 'kitchen shelves', '2026-01-09 06:11:51', 'Completed', 'Completed', 'Erumapetty ', 'Erumapetty', 0.00, 0.00, '2026-01-09 11:47:59', 0.00, NULL, '2026-01-09 12:05:11', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 06:35:11'),
(28, 'BK202601096079', 8, 6, '2026-01-09', '16:38', NULL, NULL, 0.00, 'na', '2026-01-09 08:05:32', 'Cancelled', 'Rejected', 'na', 'na', 4000.00, 0.00, NULL, 0.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy\r\n', '2026-01-10 03:44:46'),
(29, 'BK202601092267', 8, 6, '2026-01-09', '16:38', NULL, NULL, 0.00, 'na', '2026-01-09 08:05:59', 'Cancelled', 'Rejected', 'na', 'na', 4000.00, 0.00, NULL, 0.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy\r\n', '2026-01-10 03:44:57'),
(30, 'BK202601091293', 8, 6, '2026-01-09', '14:14', NULL, NULL, 0.00, 'cupboard', '2026-01-09 08:44:22', 'Cancelled', 'Rejected', 'erumapetty', 'erumapetty', 2000.00, 1000.00, NULL, 1000.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy', '2026-01-10 03:45:06'),
(31, 'BK202601094323', 8, 6, '2026-01-09', '13:33', NULL, NULL, 0.00, 'cupboard', '2026-01-09 08:45:20', 'Cancelled', 'Rejected', 'erumapetty', 'Erumapetty', 3000.00, 1000.00, NULL, 2000.00, NULL, NULL, 'Pending', NULL, NULL, 'No', NULL, 'busy', '2026-01-10 03:45:13'),
(32, 'BK202601097142', 8, 13, '2026-01-09', '14:22', '2026-01-09 09:54:05', '2026-01-09 09:54:08', 0.00, 'western food', '2026-01-09 08:53:00', 'Completed', 'Completed', 'erumapetty', 'Erumapetty', 0.00, 500.00, '2026-01-09 14:32:02', -500.00, NULL, '2026-01-09 14:36:32', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-09 09:06:32'),
(33, 'BK202601094043', 8, 13, '2026-01-09', '14:35', '2026-01-09 10:06:39', '2026-01-09 10:06:41', 0.00, 'western food', '2026-01-09 09:06:25', 'Completed', 'Completed', 'erumapetty', 'erumapetty', 1000.00, 0.00, '2026-01-10 09:10:19', 0.00, NULL, NULL, 'Paid', 'Demo Payment', NULL, 'No', NULL, NULL, '2026-01-10 03:40:19'),
(34, 'BK202601101351', 8, 6, '2026-01-10', '09:13', '2026-01-10 04:45:44', '2026-01-10 04:45:56', 0.00, 'wardrobe', '2026-01-10 03:43:51', 'Completed', 'Completed', 'Erumapetty', 'Erumapetty ', 1000.00, 0.00, '2026-01-10 09:16:11', 0.00, NULL, '2026-01-10 09:16:28', 'Paid', 'Demo Payment', NULL, 'Full', NULL, NULL, '2026-01-10 03:46:28'),
(35, 'BK202602244445', 10, 7, '2026-02-26', '08:00', '2026-02-24 04:28:34', '2026-02-24 04:29:42', 0.02, 'cleaning', '2026-02-24 03:21:07', 'Completed', 'Completed', 'tghjkkd', 'kootanad', 500.00, 0.00, '2026-02-24 09:01:38', 10.00, NULL, NULL, 'Paid', 'Demo Payment', NULL, 'No', NULL, NULL, '2026-02-24 03:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(10) NOT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `Category`, `Description`, `CreationDate`) VALUES
(1, 'Plumber', 'Professional plumbing services', '2025-12-10 14:32:13'),
(2, 'Electrician', 'Electrical repairs and installations', '2025-12-10 14:32:13'),
(3, 'Carpenter', 'Furniture and woodwork services', '2025-12-10 14:32:13'),
(4, 'Painter', 'Interior and exterior painting', '2025-12-10 14:32:13'),
(5, 'Cleaner', 'House and office cleaning services', '2025-12-10 14:32:13'),
(6, 'Gardener', 'Garden maintenance and landscaping', '2025-12-10 14:32:13'),
(7, 'Driver', 'Personal and commercial driving services', '2025-12-10 14:32:13'),
(8, 'Cook', 'Home cooking and catering services', '2025-12-10 14:32:13'),
(9, 'Mason', 'Construction and masonry work', '2025-12-10 14:32:13'),
(10, 'Welder', 'Metal fabrication and welding', '2025-12-10 14:32:13'),
(13, 'Teacher', 'Teach  students from grade 1-10', '2025-12-21 06:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontact`
--

CREATE TABLE `tblcontact` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `EnquiryDate` timestamp NULL DEFAULT current_timestamp(),
  `IsRead` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontact`
--

INSERT INTO `tblcontact` (`ID`, `Name`, `Email`, `Message`, `EnquiryDate`, `IsRead`) VALUES
(1, 'Thridev CR', 'thecastir0npiston46@gmail.com', '....', '2025-12-16 04:58:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblnotification`
--

CREATE TABLE `tblnotification` (
  `ID` int(10) NOT NULL,
  `UserType` enum('User','Worker','Admin') NOT NULL,
  `UserID` int(10) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Message` text NOT NULL,
  `BookingID` int(10) DEFAULT NULL,
  `IsRead` tinyint(1) DEFAULT 0,
  `CreatedDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblnotification`
--

INSERT INTO `tblnotification` (`ID`, `UserType`, `UserID`, `Title`, `Message`, `BookingID`, `IsRead`, `CreatedDate`) VALUES
(1, 'Worker', 2, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-16 06:50:30'),
(2, 'Worker', 5, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-16 08:03:20'),
(3, 'Worker', 4, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-16 08:03:25'),
(4, 'Worker', 3, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-16 08:03:29'),
(5, 'Worker', 6, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-16 09:36:29'),
(6, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512161395. Please review and respond.', 1, 0, '2025-12-16 10:19:05'),
(7, 'User', 3, 'Booking Accepted', 'Your booking #BK202512161395 has been accepted. Please proceed with advance payment.', 1, 0, '2025-12-16 10:19:53'),
(8, 'User', 3, 'Work Completed', 'Work for booking #BK202512161395 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 1, 0, '2025-12-16 10:21:38'),
(9, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512163056. Please review and respond.', 2, 0, '2025-12-16 10:27:03'),
(10, 'User', 4, 'Booking Accepted', 'Your booking #BK202512163056 has been accepted. Please proceed with advance payment.', 2, 0, '2025-12-16 10:44:57'),
(11, 'User', 4, 'Work Completed', 'Work for booking #BK202512163056 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 2, 0, '2025-12-16 10:45:02'),
(12, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512169985. Please review and respond.', 3, 0, '2025-12-16 10:47:48'),
(13, 'User', 3, 'Booking Rejected', 'Your booking #BK202512169985 has been rejected. Reason: busy at the moment', 3, 0, '2025-12-16 10:51:16'),
(14, 'Worker', 9, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-17 05:12:35'),
(15, 'Worker', 8, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-17 05:12:39'),
(16, 'Worker', 7, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-17 05:12:43'),
(17, 'Worker', 10, 'Account Rejected', 'Your worker account application has been rejected. Please contact admin for more details.', NULL, 0, '2025-12-17 05:24:10'),
(18, 'Worker', 9, 'New Booking Request', 'You have received a new booking request #BK202512171205. Please review and respond.', 4, 0, '2025-12-17 06:04:35'),
(19, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512174530. Please review and respond.', 5, 0, '2025-12-17 07:12:15'),
(20, 'User', 3, 'Booking Accepted', 'Your booking #BK202512174530 has been accepted. Please proceed with advance payment.', 5, 0, '2025-12-17 07:13:49'),
(21, 'User', 3, 'Work Completed', 'Work for booking #BK202512174530 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 5, 0, '2025-12-17 07:15:10'),
(22, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512179652. Please review and respond.', 6, 0, '2025-12-17 07:36:58'),
(23, 'User', 3, 'Booking Accepted', 'Your booking #BK202512179652 has been accepted. Please proceed with advance payment.', 6, 0, '2025-12-17 07:38:47'),
(24, 'User', 3, 'Work Completed', 'Work for booking #BK202512179652 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 6, 0, '2025-12-17 07:39:58'),
(25, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512175245. Please review and respond.', 7, 0, '2025-12-17 08:57:11'),
(26, 'User', 3, 'Booking Accepted', 'Your booking #BK202512175245 has been accepted. Please proceed with advance payment.', 7, 0, '2025-12-17 08:58:48'),
(27, 'User', 3, 'Work Completed', 'Work for booking #BK202512175245 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 7, 0, '2025-12-17 08:59:22'),
(28, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512178183. Please review and respond.', 8, 0, '2025-12-17 09:10:32'),
(29, 'User', 3, 'Booking Accepted', 'Your booking #BK202512178183 has been accepted. Please proceed with advance payment.', 8, 0, '2025-12-17 09:12:00'),
(30, 'User', 3, 'Work Completed', 'Work for booking #BK202512178183 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 8, 0, '2025-12-17 09:12:16'),
(31, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512175049. Please review and respond.', 9, 0, '2025-12-17 09:17:02'),
(32, 'User', 3, 'Booking Accepted', 'Your booking #BK202512175049 has been accepted. Please proceed with advance payment.', 9, 0, '2025-12-17 09:18:41'),
(33, 'User', 3, 'Work Completed', 'Work for booking #BK202512175049 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 9, 0, '2025-12-17 09:19:00'),
(34, 'Worker', 7, 'New Booking Request', 'You have received a new booking request #BK202512198932. Please review and respond.', 10, 0, '2025-12-19 11:33:21'),
(35, 'Worker', 9, 'New Booking Request', 'You have received a new booking request #BK202512197913. Please review and respond.', 11, 0, '2025-12-19 11:34:48'),
(36, 'User', 6, 'Booking Accepted', 'Your booking #BK202512197913 has been accepted. Please proceed with advance payment.', 11, 0, '2025-12-19 11:36:27'),
(37, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512214496. Please review and respond.', 12, 0, '2025-12-21 05:54:26'),
(38, 'Worker', 9, 'New Booking Request', 'You have received a new booking request #BK202512217025. Please review and respond.', 13, 0, '2025-12-21 06:00:26'),
(39, 'User', 7, 'Booking Accepted', 'Your booking #BK202512217025 has been accepted. Please proceed with advance payment.', 13, 0, '2025-12-21 06:02:24'),
(40, 'Worker', 11, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2025-12-21 06:14:24'),
(41, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512299196. Please review and respond.', 14, 0, '2025-12-29 04:10:53'),
(42, 'User', 8, 'Booking Accepted', 'Your booking #BK202512299196 has been accepted. Please proceed with advance payment.', 14, 0, '2025-12-29 04:12:05'),
(43, 'User', 8, 'Work Completed', 'Work for booking #BK202512299196 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 14, 0, '2025-12-29 04:12:09'),
(44, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512292280. Please review and respond.', 15, 0, '2025-12-29 05:17:05'),
(45, 'User', 8, 'Booking Accepted', 'Your booking #BK202512292280 has been accepted. Please proceed with advance payment.', 15, 0, '2025-12-29 05:25:17'),
(46, 'User', 8, 'Work Completed', 'Work for booking #BK202512292280 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 15, 0, '2025-12-29 05:25:22'),
(47, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512301994. Please review and respond.', 16, 0, '2025-12-30 06:39:43'),
(48, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512303434. Please review and respond.', 17, 0, '2025-12-30 06:55:15'),
(49, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512301050. Please review and respond.', 18, 0, '2025-12-30 06:56:37'),
(50, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202512309003. Please review and respond.', 19, 0, '2025-12-30 07:04:49'),
(51, 'User', 8, 'Booking Accepted', 'Your booking #BK202512309003 has been accepted. Please proceed with advance payment.', 19, 0, '2026-01-08 09:54:24'),
(52, 'User', 8, 'Booking Rejected', 'Your booking #BK202512301050 has been rejected. Reason: busy\r\n', 18, 0, '2026-01-08 09:54:37'),
(53, 'User', 8, 'Booking Rejected', 'Your booking #BK202512301994 has been rejected. Reason: busy\r\n', 16, 0, '2026-01-08 09:54:50'),
(54, 'User', 8, 'Booking Accepted', 'Your booking #BK202512303434 has been accepted. Please proceed with advance payment.', 17, 0, '2026-01-08 09:54:56'),
(55, 'User', 8, 'Work Completed', 'Work for booking #BK202512309003 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 19, 0, '2026-01-08 10:02:00'),
(56, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601087149. Please review and respond.', 20, 0, '2026-01-08 10:10:34'),
(57, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601082795. Please review and respond.', 21, 0, '2026-01-08 10:18:34'),
(58, 'User', 8, 'Booking Accepted', 'Your booking #BK202601082795 has been accepted. Please proceed with advance payment.', 21, 0, '2026-01-08 10:21:28'),
(59, 'User', 8, 'Work Completed', 'Work for booking #BK202601082795 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 21, 0, '2026-01-08 10:24:14'),
(60, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601094147. Please review and respond.', 22, 0, '2026-01-09 04:04:13'),
(61, 'User', 8, 'Booking Accepted', 'Your booking #BK202601087149 has been accepted. Please proceed with advance payment.', 20, 0, '2026-01-09 04:31:10'),
(62, 'User', 8, 'Booking Accepted', 'Your booking #BK202601094147 has been accepted. Please proceed with advance payment.', 22, 0, '2026-01-09 05:04:07'),
(63, 'User', 8, 'Work Completed', 'Work for booking #BK202601094147 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 22, 0, '2026-01-09 05:04:19'),
(64, 'User', 8, 'Work Completed', 'Work for booking #BK202601087149 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 20, 0, '2026-01-09 05:04:53'),
(65, 'User', 8, 'Work Completed', 'Work for booking #BK202512303434 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 17, 0, '2026-01-09 05:05:20'),
(66, 'User', 7, 'Booking Accepted', 'Your booking #BK202512214496 has been accepted. Please proceed with advance payment.', 12, 0, '2026-01-09 05:05:29'),
(67, 'User', 7, 'Work Completed', 'Work for booking #BK202512214496 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-1000', 12, 0, '2026-01-09 05:05:37'),
(68, 'Worker', 6, 'Payment Received', 'Payment of ₹-1000 received for booking #BK202512303434. Transaction completed successfully.', 17, 0, '2026-01-09 05:19:48'),
(69, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601093153. Please review and respond.', 23, 0, '2026-01-09 05:22:21'),
(70, 'User', 8, 'Booking Accepted', 'Your booking #BK202601093153 has been accepted. Please proceed with advance payment.', 23, 0, '2026-01-09 05:23:13'),
(71, 'User', 8, 'Work Completed', 'Work for booking #BK202601093153 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 23, 0, '2026-01-09 05:23:31'),
(72, 'Worker', 6, 'Payment Received', 'Payment of ₹0 received for booking #BK202601093153. Transaction completed successfully.', 23, 0, '2026-01-09 05:24:56'),
(73, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601091665. Please review and respond.', 24, 0, '2026-01-09 05:30:23'),
(74, 'User', 8, 'Booking Accepted', 'Your booking #BK202601091665 has been accepted. Please proceed with advance payment.', 24, 0, '2026-01-09 05:30:31'),
(75, 'User', 8, 'Work Completed', 'Work for booking #BK202601091665 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 24, 0, '2026-01-09 05:30:35'),
(76, 'Worker', 6, 'Payment Received', 'Payment of ₹0 received for booking #BK202601091665. Transaction completed successfully.', 24, 0, '2026-01-09 05:30:59'),
(77, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601093688. Please review and respond.', 25, 0, '2026-01-09 05:44:57'),
(78, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601097317. Please review and respond.', 26, 0, '2026-01-09 05:49:57'),
(79, 'User', 8, 'Booking Accepted', 'Your booking #BK202601097317 has been accepted. Please proceed with advance payment.', 26, 0, '2026-01-09 05:50:06'),
(80, 'User', 8, 'Work Completed', 'Work for booking #BK202601097317 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 26, 0, '2026-01-09 05:50:11'),
(81, 'Worker', 6, 'Payment Received', 'Payment of ₹0 received for booking #BK202601097317. Transaction completed successfully.', 26, 0, '2026-01-09 05:51:23'),
(82, 'User', 8, 'Booking Accepted', 'Your booking #BK202601093688 has been accepted. Please proceed with advance payment.', 25, 0, '2026-01-09 06:03:08'),
(83, 'User', 8, 'Work Completed', 'Work for booking #BK202601093688 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 25, 0, '2026-01-09 06:03:58'),
(84, 'Worker', 6, 'Payment Received', 'Payment of ₹0 received for booking #BK202601093688. Transaction completed successfully.', 25, 0, '2026-01-09 06:09:37'),
(85, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601098360. Please review and respond.', 27, 0, '2026-01-09 06:11:51'),
(86, 'User', 8, 'Booking Accepted', 'Your booking #BK202601098360 has been accepted. Please proceed with advance payment.', 27, 0, '2026-01-09 06:15:50'),
(87, 'User', 8, 'Work Completed', 'Work for booking #BK202601098360 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 27, 0, '2026-01-09 06:16:48'),
(88, 'Worker', 6, 'Payment Received', 'Payment of ₹0 received for booking #BK202601098360. Transaction completed successfully.', 27, 0, '2026-01-09 06:17:59'),
(89, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601096079. Please review and respond.', 28, 0, '2026-01-09 08:05:32'),
(90, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601092267. Please review and respond.', 29, 0, '2026-01-09 08:05:59'),
(91, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601091293. Please review and respond.', 30, 0, '2026-01-09 08:44:22'),
(92, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601094323. Please review and respond.', 31, 0, '2026-01-09 08:45:20'),
(93, 'Worker', 12, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2026-01-09 08:47:39'),
(94, 'Worker', 13, 'Account Approved', 'Congratulations! Your worker account has been approved. You can now receive booking requests.', NULL, 0, '2026-01-09 08:51:03'),
(95, 'Worker', 13, 'New Booking Request', 'You have received a new booking request #BK202601097142. Please review and respond.', 32, 0, '2026-01-09 08:53:00'),
(96, 'User', 8, 'Booking Accepted', 'Your booking #BK202601097142 has been accepted. Please proceed with advance payment.', 32, 0, '2026-01-09 08:54:03'),
(97, 'User', 8, 'Work Completed', 'Work for booking #BK202601097142 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹-500', 32, 0, '2026-01-09 08:54:08'),
(98, 'Worker', 13, 'Payment Received', 'Payment of ₹-500 received for booking #BK202601097142. Transaction completed successfully.', 32, 0, '2026-01-09 09:02:02'),
(99, 'Worker', 13, 'New Booking Request', 'You have received a new booking request #BK202601094043. Please review and respond.', 33, 0, '2026-01-09 09:06:25'),
(100, 'User', 8, 'Booking Accepted', 'Your booking #BK202601094043 has been accepted. Please proceed with advance payment.', 33, 0, '2026-01-09 09:06:37'),
(101, 'User', 8, 'Work Completed', 'Work for booking #BK202601094043 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 33, 0, '2026-01-09 09:06:41'),
(102, 'Worker', 13, 'Payment Received', 'Payment of ₹1000 received for booking #BK202601094043. Transaction completed successfully.', 33, 0, '2026-01-10 03:40:19'),
(103, 'Worker', 6, 'New Booking Request', 'You have received a new booking request #BK202601101351. Please review and respond.', 34, 0, '2026-01-10 03:43:51'),
(104, 'User', 8, 'Booking Rejected', 'Your booking #BK202601096079 has been rejected. Reason: busy\r\n', 28, 0, '2026-01-10 03:44:46'),
(105, 'User', 8, 'Booking Rejected', 'Your booking #BK202601092267 has been rejected. Reason: busy\r\n', 29, 0, '2026-01-10 03:44:57'),
(106, 'User', 8, 'Booking Rejected', 'Your booking #BK202601091293 has been rejected. Reason: busy', 30, 0, '2026-01-10 03:45:06'),
(107, 'User', 8, 'Booking Rejected', 'Your booking #BK202601094323 has been rejected. Reason: busy', 31, 0, '2026-01-10 03:45:13'),
(108, 'User', 8, 'Booking Accepted', 'Your booking #BK202601101351 has been accepted. Please proceed with advance payment.', 34, 0, '2026-01-10 03:45:17'),
(109, 'User', 8, 'Work Completed', 'Work for booking #BK202601101351 is completed. Total: ₹0 (0 hours). Please pay remaining amount: ₹0', 34, 0, '2026-01-10 03:45:56'),
(110, 'Worker', 6, 'Payment Received', 'Payment of ₹1000 received for booking #BK202601101351. Transaction completed successfully.', 34, 0, '2026-01-10 03:46:11'),
(111, 'Worker', 7, 'New Booking Request', 'You have received a new booking request #BK202602244445. Please review and respond.', 35, 0, '2026-02-24 03:21:07'),
(112, 'User', 10, 'Booking Accepted', 'Your booking #BK202602244445 has been accepted. Please proceed with advance payment.', 35, 0, '2026-02-24 03:22:48'),
(113, 'User', 10, 'Work Completed', 'Work for booking #BK202602244445 is completed. Total: ₹10 (0.02 hours). Please pay remaining amount: ₹10', 35, 0, '2026-02-24 03:29:42'),
(114, 'Worker', 7, 'Payment Received', 'Payment of ₹500 received for booking #BK202602244445. Transaction completed successfully.', 35, 0, '2026-02-24 03:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(50) DEFAULT NULL,
  `PageTitle` varchar(200) DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`) VALUES
(1, 'aboutus', 'About LSSEMS', '<p>LSSEMS (Labor Supply & Service Management System) is a comprehensive platform connecting skilled workers with customers who need their services. We provide a reliable, efficient, and transparent system for booking labor services across various categories.</p><p>Our mission is to empower workers by providing them with consistent work opportunities while making it easy for customers to find qualified professionals for their needs.</p>', NULL, NULL, '2025-12-10 14:38:38'),
(2, 'contactus', 'Contact Us', 'LSSEMS Office, Tech Park, Sector 21, Your City - 110096, India', 'contact@lssems.com', 9876543210, '2025-12-10 14:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment_transactions`
--

CREATE TABLE `tblpayment_transactions` (
  `ID` int(11) NOT NULL,
  `BookingID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PaymentType` enum('Advance','Full','Remaining') NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `TransactionID` varchar(255) NOT NULL,
  `Status` enum('Pending','Success','Failed') DEFAULT 'Pending',
  `PaymentDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblrazorpay_settings`
--

CREATE TABLE `tblrazorpay_settings` (
  `ID` int(11) NOT NULL,
  `KeyID` varchar(255) NOT NULL,
  `KeySecret` varchar(255) NOT NULL,
  `TestMode` tinyint(1) DEFAULT 1,
  `AdvancePercentage` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblreview`
--

CREATE TABLE `tblreview` (
  `ID` int(10) NOT NULL,
  `BookingID` int(10) DEFAULT NULL,
  `UserID` int(10) DEFAULT NULL,
  `WorkerID` int(10) DEFAULT NULL,
  `Rating` int(1) DEFAULT NULL CHECK (`Rating` >= 1 and `Rating` <= 5),
  `Review` text DEFAULT NULL,
  `ReviewDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblreview`
--

INSERT INTO `tblreview` (`ID`, `BookingID`, `UserID`, `WorkerID`, `Rating`, `Review`, `ReviewDate`) VALUES
(1, 14, 8, 6, 5, 'Excellent, Very help', '2025-12-29 09:50:27'),
(2, 15, 8, 6, 4, 'good, excellent work and very polite...', '2025-12-30 03:23:15'),
(4, 21, 8, 6, 4, 'excellent work and very polite', '2026-01-09 03:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Address` mediumtext DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `FullName`, `Email`, `MobileNumber`, `Password`, `Address`, `City`, `State`, `RegDate`) VALUES
(1, 'Thridev CR', 'thecastir0npiston46@gmail.com', 9074899438, '$2y$10$wfPwiGYWk5CSEqdLym1is.6kQv5uWcNDC5wuw4o0YUJNGtRg7ncuq', 'kunnankulam', 'erumapetty', 'kerala', '2025-12-16 04:45:27'),
(6, 'Divya', 'divya@gmail.com', 9080099009, '$2y$10$04ATna9fszRXj1YXlKQMfeYh7I8sVJjTTYzDDZXWRxmCV2sY8E/CW', 'Roomno:203,Thippalur.Thrissur ', 'Thrissur', 'kerala', '2025-12-19 10:54:26'),
(7, 'hari', 'hari123@gmail.com', 9946006271, '$2y$10$S2Li.PtdcFZEeiti6RGDluHXZayoajAInFLkoQEDCzCM6oUb7XCaO', 'pv\r\npo\r\n09876', 'kootanad', 'kerala', '2025-12-21 05:50:57'),
(8, 'Jyothi ', 'jyothi@gmail.com', 8099789678, '$2y$10$Y1.HZxIA1R2x/8euzyFKwuvrLaL4kZhM9Qg3hdLaj9okb9DfbLBb.', 'Thrissur ', 'Thrissur', 'kerala', '2025-12-29 04:08:42'),
(9, 'Santhappan', 'santhappan@gmail.com', 9008999999, '$2y$10$QbisA2eA.EmqfRBZeVyBbeCLJt3F9VsWIreuIFS8..bPo4hVmvvmm', 'Palakkal house\r\nErumapetty', 'Thrissur', 'Kerala', '2026-01-09 08:46:57'),
(10, 'user', 'user@gmail.com', 9586926358, '$2y$10$ebmgykmJPqRd2KQGQOe7G.y.mmqhkM66uCvJ3NdlPyZJz9FimyoXW', 'tgfhunb', 'Thrissur', 'kerala', '2026-02-24 03:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `tblworker`
--

CREATE TABLE `tblworker` (
  `ID` int(10) NOT NULL,
  `Category` varchar(200) DEFAULT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Address` mediumtext DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Picture` varchar(200) DEFAULT NULL,
  `Experience` varchar(50) DEFAULT NULL,
  `Charges` varchar(50) DEFAULT NULL,
  `AdvancePayment` decimal(10,2) DEFAULT 0.00,
  `HourlyRate` decimal(10,2) DEFAULT 0.00,
  `Description` text DEFAULT NULL,
  `Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `IsAvailable` enum('Yes','No') DEFAULT 'Yes',
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdatedDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblworker`
--

INSERT INTO `tblworker` (`ID`, `Category`, `FullName`, `Email`, `MobileNumber`, `Password`, `Address`, `City`, `State`, `Picture`, `Experience`, `Charges`, `AdvancePayment`, `HourlyRate`, `Description`, `Status`, `IsAvailable`, `RegDate`, `UpdatedDate`) VALUES
(6, 'Carpenter', 'Bob ', 'bob@gmail.com', 9090909090, '$2y$10$UHlc97UFyDRxfNGVhrYN3OyWJoReBCBktMVJAclY7Vkdwbrok9xR6', 'Erumapetty', 'Erumapetty', 'Kerala', '72acded3acd45e4c8b6ed680854b8ab11765877753.jpg', '12', '8000', 1000.00, 1000.00, 'Builder', 'Approved', 'Yes', '2025-12-16 09:35:53', '2025-12-16 09:36:29'),
(7, 'Cleaner', 'Sneha', 'sneha@gmail.com', 9080099009, '$2y$10$uqj5YWkm9mx6IBgNgI21Ku1qz7zIqTMwbETOZD9ZhIJPh8CYYBaB.', 'Thrissur ', 'Thrissur', 'Kerala', '51011625c841e8a9a42c00e818789c941765947259.png', '10', '2000', 500.00, 500.00, 'Punctual and Highly Passionate in Job', 'Approved', 'Yes', '2025-12-17 04:54:19', '2025-12-17 05:12:43'),
(8, 'Driver', 'Stephen', 'stephen@gmail.com', 9080099009, '$2y$10$vlrGOc4E99lPTyV93mw/2O3OrZjdrfDQtTN.6iojh.nu1tq1BRSDG', 'Flat no:503,AlGadheer Building, Thrissur', 'Thrissur', 'Kerala', 'bd2dc25972ee758db0aa780b650560251765947576.png', '6', '1500', 500.00, 800.00, 'Well Experienced ', 'Approved', 'Yes', '2025-12-17 04:59:36', '2025-12-17 05:12:39'),
(9, 'Cook', 'Radhika', 'radhika@gmail.com', 9080099009, '$2y$10$iWtrhVs680EZu2RxAvQGFuX005DvpqJiaqSpybkTaCV/tyS5xBDpe', 'street no:245,Gandhi nagar, Chennai', 'Chennai', 'TamilNadu', 'ed1dbc237677b82c6509d73bb67dd9411765947857.png', '6', '1000', 800.00, 500.00, 'western food', 'Approved', 'Yes', '2025-12-17 05:04:17', '2025-12-21 06:07:38'),
(10, 'Welder', 'Marco', 'marco@gmail.com', 9080099009, '$2y$10$j85DdG7Z9wI8MEDaloFkz.LfSn1TY.0rChpunf7Z5kLCdAZmG5KAy', 'Puliparambil house,Nattika', 'Thrissur', 'kerala', 'a5416af198ea1410bd01fadba36091f41765948932.png', '4', '800', 500.00, 1500.00, 'Skilled worker', 'Rejected', 'Yes', '2025-12-17 05:22:12', '2025-12-17 05:24:10'),
(11, 'Teacher', 'priya', 'priya@gmail.com', 7766554433, '$2y$10$I3u4r.z9zUQy/fc5xoPh9eH43Vsbv9IOEzhtdYIexY1nRNKqGD6ca', 'abc building thrissur', 'Chennai', 'TamilNadu', '51011625c841e8a9a42c00e818789c941766297603.png', '4', '1500', 500.00, 700.00, 'Teach maths english ', 'Approved', 'Yes', '2025-12-21 06:13:23', '2025-12-21 06:14:24'),
(12, 'Gardener', 'Morty ', 'morty@gmail.com', 7086786708, '$2y$10$jfkfoYelhAffWlyabQT3XeDdbN9zizLHcebYvoBkD4BedIMimJY/6', 'Nattika', 'Nattika', 'Kerala', '55ccf27d26d7b23839986b6ae2e447ab1767070594.jpg', '7', '3000', 1000.00, 400.00, 'good gardener', 'Approved', 'Yes', '2025-12-30 04:56:34', '2026-01-09 08:47:39'),
(13, 'Cook', 'Santhappan', 'santhappan@gmail.com', 906788088, '$2y$10$/mCFZHknZRMkKHjXyt1cf.eyl15.rTanyPIJHjrdJxYWKQxYq6qQW', 'Palakkal house\r\nErumapetty', 'Thrissur', 'Kerala', 'bd2dc25972ee758db0aa780b650560251767948643.png', '10', '8000', 500.00, 1000.00, 'western style foods', 'Approved', 'Yes', '2026-01-09 08:50:43', '2026-01-09 08:51:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Email` (`Email`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `BookingNumber` (`BookingNumber`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `WorkerID` (`WorkerID`),
  ADD KEY `Status` (`Status`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Category` (`Category`);

--
-- Indexes for table `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblnotification`
--
ALTER TABLE `tblnotification`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BookingID` (`BookingID`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PageType` (`PageType`);

--
-- Indexes for table `tblpayment_transactions`
--
ALTER TABLE `tblpayment_transactions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblrazorpay_settings`
--
ALTER TABLE `tblrazorpay_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblreview`
--
ALTER TABLE `tblreview`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `BookingID` (`BookingID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `WorkerID` (`WorkerID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `tblworker`
--
ALTER TABLE `tblworker`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Category` (`Category`),
  ADD KEY `Email` (`Email`),
  ADD KEY `Status` (`Status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblnotification`
--
ALTER TABLE `tblnotification`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpayment_transactions`
--
ALTER TABLE `tblpayment_transactions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrazorpay_settings`
--
ALTER TABLE `tblrazorpay_settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblreview`
--
ALTER TABLE `tblreview`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblworker`
--
ALTER TABLE `tblworker`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_worker` FOREIGN KEY (`WorkerID`) REFERENCES `tblworker` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tblreview`
--
ALTER TABLE `tblreview`
  ADD CONSTRAINT `fk_review_booking` FOREIGN KEY (`BookingID`) REFERENCES `tblbooking` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_worker` FOREIGN KEY (`WorkerID`) REFERENCES `tblworker` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
