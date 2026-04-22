-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2026 at 06:40 AM
-- Server version: 8.0.36-28
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `silver`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `opening_amount` decimal(15,2) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`id`, `name`, `opening_amount`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Cutomer', 120.00, 1, '2024-03-20 16:24:37', NULL),
(2, 'karigar', 130.00, 1, '2024-03-20 16:25:06', '2024-03-21 14:45:12'),
(3, 'Kasar', 150.00, 1, '2024-03-20 16:25:06', NULL),
(4, 'Expense', 160.00, 1, '2024-03-20 16:26:07', NULL),
(5, 'Fin Expense', 110.00, 1, '2024-03-20 16:26:07', NULL),
(6, 'Mfg Exp', 150.00, 1, '2024-03-20 16:26:41', NULL),
(7, 'Staff', 120.00, 1, '2024-03-20 16:26:41', NULL),
(9, 'Dealer', 130.00, 1, '2024-03-20 16:27:35', '2024-06-22 23:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `baki`
--

CREATE TABLE `baki` (
  `id` int NOT NULL,
  `baki_code` text,
  `customer_id` int NOT NULL,
  `type` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `mode` text,
  `gross` text,
  `purity` text,
  `wb` text,
  `fine` text,
  `metal_type_id` int NOT NULL DEFAULT '0',
  `rate` text,
  `amount` text,
  `remark` text,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'AXIS', 0, '2024-02-07 11:52:31', '2024-11-11 13:54:54'),
(5, 'SBI', 0, '2024-02-09 16:12:15', '2024-02-09 16:12:43'),
(7, 'cash in hand', 0, '2024-03-27 17:01:44', '2024-03-27 17:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'SILVER JEWELLERY', 0, '2024-01-23 15:15:55', '2024-06-20 16:44:11'),
(2, 'category1', 0, '2024-01-23 15:17:48', NULL),
(3, 'category 2', 0, '2024-02-22 10:13:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Ahmedabad', 0, '2024-01-19 19:39:25', '2024-01-20 09:55:35'),
(2, 'surat', 0, '2024-01-20 10:16:45', '2024-01-20 10:39:56'),
(3, 'Amreli', 0, '2024-02-22 10:12:47', '2024-02-22 10:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `common_bhuko`
--

CREATE TABLE `common_bhuko` (
  `id` int NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `weight` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `common_bhuko`
--

INSERT INTO `common_bhuko` (`id`, `touch`, `weight`, `created_at`, `updated_at`) VALUES
(1, 80.74, 105.61, '2024-11-11 16:22:07', '2026-04-20 12:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `city_id` int NOT NULL,
  `account_type_id` int NOT NULL,
  `opening_amount` decimal(15,2) NOT NULL,
  `opening_amount_type` enum('JAMA','BAKI') NOT NULL,
  `opening_fine` decimal(15,2) NOT NULL,
  `opening_fine_type` enum('JAMA','BAKI') NOT NULL,
  `process_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `mobile`, `city_id`, `account_type_id`, `opening_amount`, `opening_amount_type`, `opening_fine`, `opening_fine_type`, `process_id`, `created_at`, `updated_at`) VALUES
(1, 'Arjun', '', 0, 2, 0.00, 'BAKI', 0.00, 'BAKI', NULL, '2026-03-16 16:44:15', NULL),
(2, 'AMBESHWARI', '', 0, 2, 0.00, 'BAKI', 0.00, 'BAKI', '1,2', '2026-04-01 18:38:30', NULL),
(3, 'IN HOUSE VANUJJAR', '', 0, 2, 0.00, 'BAKI', 0.00, 'BAKI', '2,5', '2026-04-01 18:51:26', NULL),
(4, 'VIJAYBHAI R', '', 0, 2, 0.00, 'BAKI', 0.00, 'BAKI', '4', '2026-04-01 18:53:30', NULL),
(5, 'SHAILESHBHAI CHAIN', '', 0, 9, 0.00, 'BAKI', 0.00, 'BAKI', NULL, '2026-04-01 19:04:51', NULL),
(6, 'Raju firm vibrating', '', 0, 2, 10.00, 'BAKI', 0.05, 'JAMA', '12', '2026-04-06 18:27:07', '2026-04-07 18:25:48'),
(7, 'JAY SILVER CASTING', '', 0, 9, 0.00, 'BAKI', 0.00, 'BAKI', NULL, '2026-04-07 19:33:54', NULL),
(8, 'DUMMY WORKER', '', 0, 2, 0.00, 'BAKI', 0.00, 'BAKI', NULL, '2026-04-09 17:11:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_item`
--

CREATE TABLE `customer_item` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `item_id` int NOT NULL,
  `extra_touch` decimal(15,2) NOT NULL,
  `wastage` decimal(15,2) NOT NULL,
  `label` varchar(100) NOT NULL,
  `rate` double(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_item`
--

INSERT INTO `customer_item` (`id`, `customer_id`, `item_id`, `extra_touch`, `wastage`, `label`, `rate`, `sub_total`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 100.00, 0.00, '', 0.00, 100.00, '2026-03-16 16:48:10', NULL),
(2, 1, 3, 2.00, 0.00, '', 0.00, 2.00, '2026-03-16 16:57:42', NULL),
(3, 1, 4, 81.00, 0.00, '', 0.00, 81.00, '2026-03-16 17:08:47', NULL),
(4, 5, 6, 80.50, 5.00, '', 0.00, 80.50, '2026-04-01 19:08:43', NULL),
(5, 7, 7, 38.50, 0.00, 'net', 650.00, 38.50, '2026-04-07 19:36:28', NULL),
(6, 7, 4, 38.43, 0.00, 'net', 650.00, 38.43, '2026-04-20 11:47:53', NULL),
(7, 7, 4, 38.43, 0.00, 'net', 650.00, 38.43, '2026-04-20 11:47:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `garnu`
--

CREATE TABLE `garnu` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `worker_id` int DEFAULT NULL,
  `garnu_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `total_used_weight` double NOT NULL,
  `total_unused_weight` double NOT NULL,
  `total_used_silver` decimal(15,2) NOT NULL,
  `remaining_silver` decimal(15,2) NOT NULL,
  `total_used_copper` decimal(15,2) NOT NULL,
  `remaining_copper` decimal(15,2) NOT NULL,
  `fine` decimal(15,2) NOT NULL,
  `is_kasar` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `transfer_account` int DEFAULT '0',
  `creation_date` date DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `recieved` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `vadharo_garnu` double DEFAULT NULL,
  `verification` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `garnu`
--

INSERT INTO `garnu` (`id`, `name`, `worker_id`, `garnu_weight`, `touch`, `silver`, `copper`, `total_used_weight`, `total_unused_weight`, `total_used_silver`, `remaining_silver`, `total_used_copper`, `remaining_copper`, `fine`, `is_kasar`, `transfer_account`, `creation_date`, `user_id`, `created_at`, `updated_at`, `recieved`, `vadharo_garnu`, `verification`) VALUES
(1, '81', 1, 8.7, 80.80, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 7.03, 'NO', NULL, '2026-04-01', 1, '2026-04-01 18:34:20', '2026-04-01 18:36:45', 'YES', NULL, 0),
(2, '45', 8, 1, 40.00, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.81, 'NO', 0, '2026-04-09', 1, '2026-04-09 17:05:43', '2026-04-09 17:11:49', 'YES', NULL, 0),
(3, 'testing', 1, 1, 81.00, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.81, 'NO', 0, '2026-04-21', 1, '2026-04-21 17:31:22', '2026-04-21 17:31:27', 'YES', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `garnu_item`
--

CREATE TABLE `garnu_item` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `closing_touch` varchar(50) NOT NULL,
  `weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `net_weight` double NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `fine` double NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `garnu_item`
--

INSERT INTO `garnu_item` (`id`, `user_id`, `garnu_id`, `metal_type_id`, `closing_touch`, `weight`, `touch`, `net_weight`, `silver`, `copper`, `fine`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, '100 - 10 KG', 7, 100.00, 0, 0, 0, 7, '2026-04-01', 1, '2026-04-01 18:34:20', NULL),
(2, 1, 1, 13, '2 - 10 KG', 1.7, 2.00, 0, 0, 0, 0.03, '2026-04-01', 1, '2026-04-01 18:34:20', NULL),
(3, 1, 2, 18, '80.8 - 8.69 KG', 1, 80.80, 0, 0, 0, 0.81, '2026-04-09', 1, '2026-04-09 17:05:43', '2026-04-09 17:11:44'),
(4, 1, 3, 9, '80.8 - 8.69 KG', 1, 80.80, 0, 0, 0, 0.81, '2026-04-21', 1, '2026-04-21 17:31:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `given`
--

CREATE TABLE `given` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `rc_qty` int NOT NULL,
  `process_id` int NOT NULL,
  `material_type_id` int DEFAULT NULL,
  `closing_touch` varchar(255) DEFAULT NULL,
  `item_id` int NOT NULL,
  `worker_id` int NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `given_qty` int NOT NULL,
  `given_weight` double NOT NULL,
  `given_touch` decimal(15,2) NOT NULL,
  `row_material_weight` double DEFAULT NULL,
  `total_weight` double NOT NULL,
  `labour` int NOT NULL,
  `vadharo_dhatado` double DEFAULT NULL,
  `is_completed` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `is_kasar` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `transfer_account` int DEFAULT '0',
  `receive_code` varchar(50) DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `given`
--

INSERT INTO `given` (`id`, `user_id`, `garnu_id`, `rc_qty`, `process_id`, `material_type_id`, `closing_touch`, `item_id`, `worker_id`, `remarks`, `given_qty`, `given_weight`, `given_touch`, `row_material_weight`, `total_weight`, `labour`, `vadharo_dhatado`, `is_completed`, `verification`, `is_kasar`, `transfer_account`, `receive_code`, `creation_date`, `created_at`) VALUES
(1, 1, 1, 0, 1, 9, '80.8 - 8.69 KG', 5, 2, '', 9, 8.69, 80.80, 0, 8.69, 0, -0.5, 'YES', 'NO', 'YES', 6, '', '2026-04-01', '2026-04-01 18:39:51'),
(2, 1, 1, 0, 2, 0, '', 4, 3, '', 10, 10, 81.00, 0, 10, 0, -0.45, 'YES', 'NO', 'YES', 3, '', '2026-04-01', '2026-04-01 18:51:52'),
(3, 1, 1, 0, 4, 0, '', 6, 4, '', 0, 0, 80.75, 11, 11, 0, -105, 'NO', 'NO', 'NO', NULL, '', '2026-04-01', '2026-04-01 19:13:22'),
(4, 1, 3, 0, 1, 10, '100 - 110 KG', 7, 2, '', 1, 55, 100.00, 8.69, 63.69, 0, NULL, 'NO', 'NO', 'NO', 0, '', '2026-04-21', '2026-04-21 17:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `given_row_material`
--

CREATE TABLE `given_row_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `given_id` int NOT NULL,
  `row_material_id` int DEFAULT NULL,
  `lot_wise_rm_id` int NOT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `creation_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `given_row_material`
--

INSERT INTO `given_row_material` (`id`, `user_id`, `garnu_id`, `given_id`, `row_material_id`, `lot_wise_rm_id`, `touch`, `weight`, `quantity`, `creation_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 11, 2, 80.50, 6, 0, '2026-04-01', '2026-04-01 19:13:22', NULL),
(2, 1, 1, 3, 10, 0, 81.00, 5, 0, '2026-04-01', '2026-04-01 19:13:22', NULL),
(3, 1, 3, 4, 1, 1, 80.80, 8.69, 0, '2026-04-21', '2026-04-21 17:41:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `given_testing`
--

CREATE TABLE `given_testing` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `worker_id` int DEFAULT NULL,
  `garnu_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `total_used_weight` double NOT NULL,
  `total_unused_weight` double NOT NULL,
  `total_used_silver` decimal(15,2) NOT NULL,
  `remaining_silver` decimal(15,2) NOT NULL,
  `total_used_copper` decimal(15,2) NOT NULL,
  `remaining_copper` decimal(15,2) NOT NULL,
  `fine` decimal(15,2) NOT NULL,
  `is_kasar` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `transfer_account` int DEFAULT '0',
  `creation_date` date DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `recieved` enum('YES','NO') NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `given_testing_item`
--

CREATE TABLE `given_testing_item` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('Metal','Row Material') NOT NULL,
  `given_testing_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `closing_touch` varchar(50) NOT NULL,
  `weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `net_weight` double NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `fine` double NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `category_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'FANCY PAYAL', 1, '2024-06-20 16:44:28', '2024-11-11 13:53:22'),
(2, 'Fine', 1, '2026-03-16 16:46:02', NULL),
(3, 'Copper', 1, '2026-03-16 16:49:49', NULL),
(4, 'xyzzz', 1, '2026-03-16 17:00:02', '2026-04-21 17:28:50'),
(5, 'VARO', 1, '2026-04-01 18:39:24', NULL),
(6, 'CHAIN', 1, '2026-04-01 19:07:54', NULL),
(7, 'MIX CASTING ', 1, '2026-04-07 19:34:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jama`
--

CREATE TABLE `jama` (
  `id` int NOT NULL,
  `bank_id` int NOT NULL,
  `sale_id` varchar(255) DEFAULT NULL,
  `jama_code` text,
  `customer_id` int NOT NULL,
  `type` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `mode` text,
  `gross` text,
  `purity` text,
  `wb` text,
  `fine` text,
  `metal_type_id` int DEFAULT '0',
  `rate` text,
  `amount` text,
  `payment_type` enum('CREDIT','DEBIT') NOT NULL,
  `remark` text,
  `sequence_code` varchar(50) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `is_not_show` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jama`
--

INSERT INTO `jama` (`id`, `bank_id`, `sale_id`, `jama_code`, `customer_id`, `type`, `date`, `mode`, `gross`, `purity`, `wb`, `fine`, `metal_type_id`, `rate`, `amount`, `payment_type`, `remark`, `sequence_code`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`, `verification`, `is_not_show`) VALUES
(1, 0, NULL, '', 1, 'bank', '2026-03-16', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-03-16', 0, '2026-03-16 16:44:15', NULL, 'NO', 1),
(2, 0, NULL, 'JAMA_201', 1, 'fine', '2026-04-01', 'Silli', '10', '100', '', '10', 10, '', '', 'CREDIT', '', 'PY_186', '2026-04-01', 0, '2026-04-01 18:25:32', NULL, 'NO', 0),
(3, 0, NULL, 'JAMA_201', 1, 'fine', '2026-04-01', 'Silli', '10', '2', '', '0', 13, '', '', 'CREDIT', '', 'PY_187', '2026-04-01', 0, '2026-04-01 18:26:03', NULL, 'NO', 0),
(4, 0, NULL, '', 2, 'bank', '2026-04-01', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-01', 0, '2026-04-01 18:38:30', NULL, 'NO', 1),
(5, 0, NULL, '', 3, 'bank', '2026-04-01', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-01', 0, '2026-04-01 18:51:26', NULL, 'NO', 1),
(6, 0, NULL, '', 4, 'bank', '2026-04-01', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-01', 0, '2026-04-01 18:53:30', NULL, 'NO', 1),
(7, 0, NULL, '', 5, 'bank', '2026-04-01', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-01', 0, '2026-04-01 19:04:51', NULL, 'NO', 1),
(8, 0, NULL, 'JAMA_204', 1, 'fine', '2026-04-01', 'Silli', '100.000', '100', '', '100', 10, '', '', 'CREDIT', '', 'PY_188', '2026-04-01', 0, '2026-04-01 19:25:42', NULL, 'NO', 0),
(9, 0, NULL, 'JAMA_204', 1, 'fine', '2026-04-01', 'Silli', '100.000', '2.00', '', '2', 13, '', '', 'CREDIT', '', 'PY_189', '2026-04-01', 0, '2026-04-01 19:26:05', NULL, 'NO', 0),
(10, 0, NULL, '', 6, 'bank', '2026-04-06', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-06', 0, '2026-04-06 18:27:07', NULL, 'NO', 1),
(11, 0, NULL, '', 7, 'bank', '2026-04-07', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-07', 0, '2026-04-07 19:33:54', NULL, 'NO', 1),
(12, 0, NULL, '', 8, 'bank', '2026-04-09', '', '', '', '', '', 0, '', '0', 'CREDIT', '', '', '2026-04-09', 0, '2026-04-09 17:11:10', NULL, 'NO', 1),
(13, 5, NULL, 'JAMA_211', 2, 'bank', '2026-04-21', 'bank', '0', '100', '', '0', 0, '', '100', 'CREDIT', '', 'PY_190', '2026-04-21', 0, '2026-04-21 18:00:27', NULL, 'NO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lot_creation`
--

CREATE TABLE `lot_creation` (
  `id` int UNSIGNED NOT NULL,
  `receive_id` int NOT NULL,
  `barcode` varchar(555) DEFAULT NULL,
  `tag` varchar(10) NOT NULL,
  `item_id` int DEFAULT NULL,
  `sub_item_id` int NOT NULL,
  `stamp_id` int NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `gross_weight` decimal(16,3) DEFAULT '0.000',
  `net_weight` decimal(16,3) DEFAULT '0.000',
  `l_weight` decimal(15,3) NOT NULL DEFAULT '0.000',
  `amt` decimal(16,3) DEFAULT '0.000',
  `status` int DEFAULT '0' COMMENT '0 - pending | 1 - ready for sold | 2 - sale',
  `piece` int DEFAULT '1',
  `admin_id` int DEFAULT NULL,
  `session_id` int DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lot_creation_barcode`
--

CREATE TABLE `lot_creation_barcode` (
  `id` int NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `gross_weight` decimal(15,2) NOT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) NOT NULL,
  `creation_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lot_wise_rm`
--

CREATE TABLE `lot_wise_rm` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `code` varchar(250) NOT NULL,
  `type` enum('RECEIVE','PURCHASE','GIVEN') DEFAULT NULL,
  `is_merger` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `merger_id` int DEFAULT NULL,
  `purchase_detail_id` int NOT NULL,
  `row_material_id` int NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `weight` decimal(15,2) NOT NULL,
  `quantity` int NOT NULL,
  `given_weight` decimal(15,2) NOT NULL,
  `given_quantity` int NOT NULL,
  `receive_weight` decimal(15,2) NOT NULL,
  `receive_quantity` int NOT NULL,
  `rem_weight` decimal(15,2) NOT NULL,
  `rem_quantity` int NOT NULL,
  `average_touch` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_complated` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `creation_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lot_wise_rm`
--

INSERT INTO `lot_wise_rm` (`id`, `user_id`, `code`, `type`, `is_merger`, `merger_id`, `purchase_detail_id`, `row_material_id`, `touch`, `weight`, `quantity`, `given_weight`, `given_quantity`, `receive_weight`, `receive_quantity`, `rem_weight`, `rem_quantity`, `average_touch`, `is_complated`, `creation_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'REN_80.80', 'RECEIVE', 'NO', NULL, 0, 1, 80.80, 8.69, 0, 8.69, 0, 8.69, 0, 0.00, 0, 0.00, 'NO', '2026-04-01', '2026-04-01 18:41:43', '2026-04-21 17:41:49'),
(2, 1, 'CASTING_81.00', 'RECEIVE', 'NO', NULL, 0, 10, 81.00, 9.95, 10, 6.00, 0, 10.95, 10, 4.95, 10, 0.00, 'NO', '2026-04-01', '2026-04-01 18:52:51', '2026-04-01 19:16:38'),
(3, 1, 'CHAIN_80.5', 'RECEIVE', 'NO', NULL, 0, 11, 80.50, 2.00, 0, 0.00, 0, 2.00, 0, 2.00, 0, 0.00, 'NO', '2026-04-01', '2026-04-01 19:16:38', NULL),
(4, 1, 'PR_1_6', 'PURCHASE', 'NO', NULL, 6, 10, 38.50, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 'NO', '2026-04-09', '2026-04-09 16:16:38', '2026-04-20 11:43:55'),
(5, 1, 'PR_1_7', 'PURCHASE', 'NO', NULL, 7, 1, 0.00, 100.00, 0, 0.00, 0, 0.00, 0, 100.00, 0, 0.00, 'NO', '2026-04-21', '2026-04-21 18:24:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `main_garnu`
--

CREATE TABLE `main_garnu` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `garnu_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `total_used_weight` double NOT NULL,
  `total_unused_weight` double NOT NULL,
  `total_used_silver` decimal(15,2) NOT NULL,
  `remaining_silver` decimal(15,2) NOT NULL,
  `total_used_copper` decimal(15,2) NOT NULL,
  `remaining_copper` decimal(15,2) NOT NULL,
  `fine` decimal(15,2) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `recieved` enum('YES','NO') NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `main_garnu_item`
--

CREATE TABLE `main_garnu_item` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `closing_touch` varchar(50) NOT NULL,
  `weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `net_weight` double NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `fine` double NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metal_type`
--

CREATE TABLE `metal_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `metal_type`
--

INSERT INTO `metal_type` (`id`, `name`, `created_at`, `updated_at`, `status`) VALUES
(8, 'BHUKO', '2024-06-20 12:26:49', NULL, 'ACTIVE'),
(9, 'DHAL', '2024-06-20 12:27:04', NULL, 'ACTIVE'),
(10, 'SILLI SILVER', '2024-06-20 12:28:54', NULL, 'ACTIVE'),
(11, '99 SILVER CHORSA', '2024-06-20 12:29:04', '2024-06-20 12:29:20', 'ACTIVE'),
(12, '98 SILVER CHORSA', '2024-06-20 12:29:34', NULL, 'ACTIVE'),
(13, 'COPPER', '2024-06-20 12:29:42', NULL, 'ACTIVE'),
(14, 'PITAL', '2024-06-20 12:29:47', NULL, 'ACTIVE'),
(15, 'RUPU', '2024-06-20 12:30:24', NULL, 'ACTIVE'),
(16, 'JASAT', '2024-06-20 12:30:36', NULL, 'ACTIVE'),
(17, 'JARU', '2024-09-14 17:54:01', NULL, 'ACTIVE'),
(18, 'OUT HOUSE RAW MATERIAL', '2026-04-09 17:10:06', NULL, 'ACTIVE'),
(19, 'abcd', '2026-04-20 11:41:34', '2026-04-21 17:29:04', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `pre_garnu`
--

CREATE TABLE `pre_garnu` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `worker_id` int DEFAULT NULL,
  `garnu_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `total_used_weight` double NOT NULL,
  `total_unused_weight` double NOT NULL,
  `total_used_silver` decimal(15,2) NOT NULL,
  `remaining_silver` decimal(15,2) NOT NULL,
  `total_used_copper` decimal(15,2) NOT NULL,
  `remaining_copper` decimal(15,2) NOT NULL,
  `fine` decimal(15,2) NOT NULL,
  `is_kasar` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `transfer_account` int DEFAULT '0',
  `creation_date` date DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `recieved` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `vadharo_garnu` double DEFAULT NULL,
  `verification` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_garnu`
--

INSERT INTO `pre_garnu` (`id`, `name`, `worker_id`, `garnu_weight`, `touch`, `silver`, `copper`, `total_used_weight`, `total_unused_weight`, `total_used_silver`, `remaining_silver`, `total_used_copper`, `remaining_copper`, `fine`, `is_kasar`, `transfer_account`, `creation_date`, `user_id`, `created_at`, `updated_at`, `recieved`, `vadharo_garnu`, `verification`) VALUES
(1, '81', 1, 8.7, 80.80, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 7.03, 'NO', NULL, '2026-04-01', 1, '2026-04-01 18:34:20', '2026-04-01 18:36:45', 'YES', NULL, 0),
(2, '45', 1, 1, 40.00, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.81, 'NO', NULL, '2026-04-09', 1, '2026-04-09 17:05:43', '2026-04-17 18:47:56', 'YES', NULL, 0),
(3, 'Heet', 8, 8.69, 80.78, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 7.02, 'NO', 0, '2026-04-17', 1, '2026-04-17 18:48:37', '2026-04-21 18:20:47', 'YES', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pre_garnu_item`
--

CREATE TABLE `pre_garnu_item` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `closing_touch` varchar(50) NOT NULL,
  `weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `net_weight` double NOT NULL,
  `silver` double NOT NULL,
  `copper` double NOT NULL,
  `fine` double NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_garnu_item`
--

INSERT INTO `pre_garnu_item` (`id`, `user_id`, `garnu_id`, `metal_type_id`, `closing_touch`, `weight`, `touch`, `net_weight`, `silver`, `copper`, `fine`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, '100 - 10 KG', 7, 100.00, 0, 0, 0, 7, '2026-04-01', 1, '2026-04-01 18:34:20', NULL),
(2, 1, 1, 13, '2 - 10 KG', 1.7, 2.00, 0, 0, 0, 0.03, '2026-04-01', 1, '2026-04-01 18:34:20', NULL),
(3, 1, 2, 18, '80.8 - 8.69 KG', 1, 80.80, 0, 0, 0, 0.81, '2026-04-09', 1, '2026-04-09 17:05:43', '2026-04-09 17:11:44'),
(4, 1, 3, 9, '80.8 - 8.69 KG', 8.69, 80.80, 0, 0, 0, 7.02, '2026-04-17', 1, '2026-04-17 18:48:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_receive_garnu_dhal`
--

CREATE TABLE `pre_receive_garnu_dhal` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `weight` double NOT NULL,
  `net_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_receive_garnu_dhal`
--

INSERT INTO `pre_receive_garnu_dhal` (`id`, `user_id`, `garnu_id`, `metal_type_id`, `weight`, `net_weight`, `touch`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 9, 8.69, 7.02, 80.80, '2026-04-01', 0, '2026-04-01 18:36:45', NULL),
(2, 1, 1, 8, 0.01, 0.01, 80.80, '2026-04-01', 0, '2026-04-01 18:36:45', NULL),
(3, 1, 2, 8, 0, 0, 40.00, '2026-04-17', 0, '2026-04-17 18:47:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `finished_good` varchar(255) DEFAULT 'Yes',
  `user_id` int NOT NULL,
  `labour_type` enum('PCS','WEIGHT','BOTH') NOT NULL DEFAULT 'BOTH',
  `show_or_not` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `autofill_given_touch` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `line_no` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `process`
--

INSERT INTO `process` (`id`, `name`, `finished_good`, `user_id`, `labour_type`, `show_or_not`, `autofill_given_touch`, `created_at`, `updated_at`, `status`, `line_no`) VALUES
(1, 'ROLLPRESS', 'No', 0, 'WEIGHT', 'NO', 'NO', '2024-06-20 16:15:59', '2024-10-14 18:31:36', 'ACTIVE', 11),
(2, 'VAAN UJJAR/DRUM - 1', 'Yes', 0, 'BOTH', 'NO', 'NO', '2024-06-22 17:25:16', '2024-10-14 18:31:42', 'ACTIVE', 21),
(3, 'NACHAK CUTTING', 'Yes', 0, 'BOTH', 'NO', 'NO', '2024-06-22 17:25:26', '2024-10-14 18:31:49', 'ACTIVE', 31),
(4, 'REV VANU', 'Yes', 0, 'BOTH', 'YES', 'YES', '2024-06-22 17:25:39', NULL, 'ACTIVE', 41),
(5, 'VAAN UJJAR/DRUM - 2', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:25:56', '2024-10-14 18:51:15', 'ACTIVE', 51),
(6, 'OXODISE', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:27:00', '2024-10-14 18:51:12', 'ACTIVE', 61),
(7, 'VAAN UJJAR/DRUM - 3', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:32:12', '2024-10-14 18:51:08', 'ACTIVE', 71),
(8, 'MINA', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:32:19', '2024-07-22 16:06:37', 'ACTIVE', 81),
(9, 'FITTING', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:39:31', '2024-07-22 16:06:32', 'ACTIVE', 91),
(10, 'CHAIN CUTTING', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:39:41', '2024-07-22 16:06:27', 'ACTIVE', 101),
(11, 'LASER MARKING', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:39:53', '2024-07-22 16:06:19', 'ACTIVE', 111),
(12, 'VIBRATE/PLANT', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:40:04', '2024-07-22 16:06:12', 'ACTIVE', 112),
(13, 'CHECKING PACKING', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:40:26', '2024-07-22 16:06:05', 'ACTIVE', 113),
(14, 'MOTI GUTHVANA', 'Yes', 0, 'BOTH', 'NO', 'YES', '2024-06-22 17:40:40', '2024-07-22 16:05:59', 'ACTIVE', 85);

-- --------------------------------------------------------

--
-- Table structure for table `process_metal_type`
--

CREATE TABLE `process_metal_type` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `given_id` int NOT NULL,
  `metal_type_id` int DEFAULT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `creation_date` date NOT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `process_metal_type`
--

INSERT INTO `process_metal_type` (`id`, `user_id`, `given_id`, `metal_type_id`, `touch`, `weight`, `quantity`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 8, 70.60, 0.3, 0, '2024-11-20', 1, '2024-11-20 16:27:24', '2024-11-20 16:29:17'),
(3, 1, 1, 8, 50.00, 0.2, 0, '2024-11-20', 1, '2024-11-20 16:28:26', '2024-11-20 16:29:17'),
(4, 1, 2, 17, 66.00, 0.2, 0, '2024-11-20', 0, '2024-11-20 16:33:07', NULL),
(5, 1, 2, 8, 75.00, 0.3, 0, '2024-11-20', 1, '2024-11-20 16:33:07', '2024-11-20 16:33:53'),
(6, 1, 4, 8, 99.00, 0.3, 0, '2024-11-20', 1, '2024-11-20 16:49:00', '2024-11-23 13:28:54'),
(7, 1, 4, 8, 78.43, 0.6, 0, '2024-11-27', 1, '2024-11-27 12:02:57', '2026-04-20 12:56:08'),
(8, 1, 3, 8, 80.75, 105, 0, '2026-04-06', 1, '2026-04-06 18:33:19', '2026-04-20 12:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `party_id` int DEFAULT NULL,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `code` varchar(20) NOT NULL,
  `sequence_code` varchar(50) NOT NULL,
  `closing_fine` varchar(11) DEFAULT NULL,
  `closing_amount` varchar(11) DEFAULT NULL,
  `product_type` enum('item','rowMaterial') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `user_id`, `date`, `party_id`, `verification`, `code`, `sequence_code`, `closing_fine`, `closing_amount`, `product_type`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-16', 1, 'NO', 'P94458', 'P_25', '0', '0', 'item', '2026-03-16 16:48:10', NULL),
(2, 1, '2026-04-01', 5, 'NO', 'P09820', 'P_26', '0', '0', 'rowMaterial', '2026-04-01 19:08:43', '2026-04-01 19:12:26'),
(3, 1, '2026-04-07', 7, 'NO', 'P50966', 'P_27', NULL, NULL, 'item', '2026-04-07 19:36:28', '2026-04-20 11:45:37'),
(5, 1, '2026-04-21', 1, 'NO', 'P05839', 'P_29', NULL, NULL, 'rowMaterial', '2026-04-21 18:24:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_detail`
--

CREATE TABLE `purchase_detail` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `purchase_id` int DEFAULT NULL,
  `product_type` enum('item','rowMaterial') NOT NULL,
  `item_id` int DEFAULT NULL,
  `stamp_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `remark` text,
  `gross_weight` decimal(11,4) DEFAULT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) DEFAULT NULL,
  `pre_touch` decimal(15,2) DEFAULT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `wastage` decimal(15,2) DEFAULT NULL,
  `fine` decimal(15,2) DEFAULT NULL,
  `piece` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `labour_type` varchar(60) DEFAULT NULL,
  `labour` decimal(15,2) DEFAULT NULL,
  `other_amount` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `raw_material_data` text,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_detail`
--

INSERT INTO `purchase_detail` (`id`, `user_id`, `purchase_id`, `product_type`, `item_id`, `stamp_id`, `unit_id`, `remark`, `gross_weight`, `less_weight`, `net_weight`, `pre_touch`, `touch`, `wastage`, `fine`, `piece`, `rate`, `labour_type`, `labour`, `other_amount`, `sub_total`, `raw_material_data`, `verification`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'item', 2, 0, 1, '', 10.0000, 0.00, 10.00, 0.00, 100.00, 0.00, 10.00, 0.00, 0.00, '', 0.00, 0.00, 0.00, '', 'NO', '2026-03-16 16:48:10', NULL),
(2, 0, 1, 'item', 3, 0, 1, '', 10.0000, 0.00, 10.00, 0.00, 2.00, 0.00, 0.20, 0.00, 0.00, '', 0.00, 0.00, 0.00, '', 'NO', '2026-03-16 16:57:42', NULL),
(3, 0, 1, 'item', 4, 0, NULL, '', 10.0000, 0.00, 10.00, 0.00, 81.00, 0.00, 8.10, 0.00, 0.00, '', 0.00, 0.00, 0.00, '', 'NO', '2026-03-16 17:08:47', NULL),
(4, 1, 2, 'rowMaterial', 11, 1, 1, '', 10.0000, 0.00, 10.00, 0.00, 80.50, 5.00, 8.55, 0.00, 0.00, '', 0.00, 0.00, 0.00, '', 'NO', '2026-04-01 19:08:43', '2026-04-01 19:12:26'),
(5, 1, 3, 'item', 4, 1, 1, '', 12.8860, 0.00, 12.89, 0.00, 38.43, 0.00, 4.95, 0.00, 0.00, 'net', 650.00, 0.00, 8375.90, NULL, 'NO', '2026-04-07 19:36:28', '2026-04-20 11:47:53'),
(7, 1, 5, 'rowMaterial', 1, 1, 4, '', 100.0000, 0.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, 0.00, 0.00, NULL, 'NO', '2026-04-21 18:24:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_material`
--

CREATE TABLE `purchase_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `purchase_detail_id` int DEFAULT NULL,
  `row_material_id` int DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `party_id` int DEFAULT NULL,
  `product_type` enum('item','rowMaterial') NOT NULL,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `code` varchar(20) NOT NULL,
  `sequence_code` varchar(50) NOT NULL,
  `closing_amount` varchar(11) DEFAULT NULL,
  `closing_fine` varchar(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_detail`
--

CREATE TABLE `purchase_return_detail` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `purchase_id` int DEFAULT NULL,
  `product_type` enum('item','rowMaterial') NOT NULL,
  `item_id` int DEFAULT NULL,
  `stamp_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `remark` text,
  `gross_weight` int DEFAULT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) DEFAULT NULL,
  `pre_touch` decimal(15,2) DEFAULT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `wastage` decimal(15,2) DEFAULT NULL,
  `fine` decimal(15,2) DEFAULT NULL,
  `piece` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `labour_type` varchar(60) DEFAULT NULL,
  `labour` decimal(15,2) DEFAULT NULL,
  `other_amount` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `raw_material_data` text,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_material`
--

CREATE TABLE `purchase_return_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `purchase_detail_id` int DEFAULT NULL,
  `row_material_id` int DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ready_for_sale`
--

CREATE TABLE `ready_for_sale` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `party_id` int DEFAULT '0',
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `code` varchar(20) NOT NULL,
  `sequence_code` varchar(50) NOT NULL,
  `isSale` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `closing_fine` varchar(11) DEFAULT NULL,
  `closing_amount` varchar(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ready_for_sale_detail`
--

CREATE TABLE `ready_for_sale_detail` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `stamp_id` int DEFAULT NULL,
  `sub_item_id` int DEFAULT NULL,
  `remark` text,
  `gross_weight` int DEFAULT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) DEFAULT NULL,
  `piece` decimal(15,2) NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `lot_creation_tag` varchar(10) NOT NULL,
  `raw_material_data` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ready_for_sale_material`
--

CREATE TABLE `ready_for_sale_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `row_material_id` int DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receive`
--

CREATE TABLE `receive` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `given_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `item_id` int NOT NULL,
  `pcs` int NOT NULL,
  `weight` decimal(15,2) NOT NULL,
  `labour_type` enum('PCS','WEIGHT') DEFAULT NULL,
  `labour` int NOT NULL,
  `total_labour` int NOT NULL,
  `final_labour` int NOT NULL,
  `row_material_weight` decimal(15,2) DEFAULT NULL,
  `total_weight` decimal(15,2) NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `fine` decimal(15,2) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `lot_creation` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `is_full` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `isGiven` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `remark` varchar(500) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receive`
--

INSERT INTO `receive` (`id`, `user_id`, `given_id`, `garnu_id`, `item_id`, `pcs`, `weight`, `labour_type`, `labour`, `total_labour`, `final_labour`, `row_material_weight`, `total_weight`, `touch`, `fine`, `code`, `lot_creation`, `is_full`, `isGiven`, `verification`, `remark`, `creation_date`, `created_at`) VALUES
(1, 1, 1, 1, 5, 0, 0.00, '', 0, 0, 869, 8.69, 8.69, 80.80, 7.02, 'Apr_R1_G1', 'YES', 'NO', 'NO', 'NO', '', '2026-04-01', '2026-04-01 18:41:43'),
(2, 1, 2, 1, 4, 0, 0.00, '', 0, 0, 0, 9.95, 9.95, 81.00, 8.06, 'Apr_R2_G2', 'NO', 'NO', 'NO', 'NO', '', '2026-04-01', '2026-04-01 18:52:51'),
(3, 1, 3, 1, 1, 0, 8.00, 'WEIGHT', 500, 4000, 4000, 3.00, 11.00, 80.75, 8.88, 'Apr_R3_G3', 'NO', 'NO', 'NO', 'NO', '', '2026-04-01', '2026-04-01 19:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `receive_garnu`
--

CREATE TABLE `receive_garnu` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `weight` double NOT NULL,
  `net_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receive_garnu_dhal`
--

CREATE TABLE `receive_garnu_dhal` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `weight` double NOT NULL,
  `net_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receive_garnu_dhal`
--

INSERT INTO `receive_garnu_dhal` (`id`, `user_id`, `garnu_id`, `metal_type_id`, `weight`, `net_weight`, `touch`, `creation_date`, `is_bhuko_used`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 9, 8.69, 7.02, 80.80, '2026-04-01', 0, '2026-04-01 18:36:45', NULL),
(2, 1, 1, 8, 0.01, 0.01, 80.80, '2026-04-01', 1, '2026-04-01 18:36:45', '2026-04-20 12:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `receive_given_testing`
--

CREATE TABLE `receive_given_testing` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `given_testing_id` int NOT NULL,
  `metal_type_id` int NOT NULL,
  `weight` double NOT NULL,
  `net_weight` double NOT NULL,
  `touch` decimal(15,2) NOT NULL,
  `creation_date` date DEFAULT NULL,
  `is_bhuko_used` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receive_row_material`
--

CREATE TABLE `receive_row_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `garnu_id` int NOT NULL,
  `received_id` int NOT NULL,
  `row_material_id` int DEFAULT NULL,
  `lot_wise_rm_id` int NOT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `labour_type` enum('PCS','WEIGHT') DEFAULT NULL,
  `labour` int NOT NULL,
  `total_labour` int NOT NULL,
  `creation_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receive_row_material`
--

INSERT INTO `receive_row_material` (`id`, `user_id`, `garnu_id`, `received_id`, `row_material_id`, `lot_wise_rm_id`, `touch`, `weight`, `quantity`, `labour_type`, `labour`, `total_labour`, `creation_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 80.80, 8.69, 0, 'WEIGHT', 100, 869, '2026-04-01', '2026-04-01 18:41:43', NULL),
(2, 1, 1, 2, 10, 2, 81.00, 9.95, 10, '', 0, 0, '2026-04-01', '2026-04-01 18:52:51', NULL),
(3, 1, 1, 3, 11, 3, 80.50, 2, 0, '', 0, 0, '2026-04-01', '2026-04-01 19:16:38', NULL),
(4, 1, 1, 3, 10, 2, 81.00, 1, 0, '', 0, 0, '2026-04-01', '2026-04-01 19:16:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `row_material`
--

CREATE TABLE `row_material` (
  `id` int NOT NULL,
  `row_material_type_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `opening_stock` decimal(15,2) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `row_material`
--

INSERT INTO `row_material` (`id`, `row_material_type_id`, `name`, `opening_stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'REN PATI 10*29', 100.00, 'ACTIVE', '2024-06-20 16:36:36', '2024-06-20 16:40:12'),
(2, 1, 'VARO 16 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:38:38', NULL),
(3, 1, 'VARO 17 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:39:03', NULL),
(4, 1, 'VARO 18 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:39:16', NULL),
(5, 1, 'VARO 19 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:39:27', NULL),
(6, 1, 'VARO 20 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:39:38', NULL),
(7, 1, 'REN PATI 8*29', 100.00, 'ACTIVE', '2024-06-20 16:40:29', NULL),
(8, 1, 'REN VARO 22 GAUGE', 100.00, 'ACTIVE', '2024-06-20 16:40:50', NULL),
(9, 1, 'REN VARO', 100.00, 'ACTIVE', '2024-06-20 16:40:58', NULL),
(10, 1, 'CASTING', 5.00, 'ACTIVE', '2024-10-16 20:05:12', NULL),
(11, 3, 'CHAIN', 100.00, 'ACTIVE', '2024-10-16 20:05:35', '2026-04-01 19:11:43'),
(12, 1, 'Mix Casting', 0.00, 'ACTIVE', '2026-04-21 17:28:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `row_material_type`
--

CREATE TABLE `row_material_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `row_material_type`
--

INSERT INTO `row_material_type` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'UNFINISHED ', '2024-06-19 18:10:29', '2024-06-20 16:18:54'),
(2, 'one time useable', '2024-06-19 18:10:39', NULL),
(3, 'FINISHED', '2024-06-20 16:19:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `party_id` int DEFAULT NULL,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `code` varchar(20) NOT NULL,
  `ready_for_sale_code` varchar(20) NOT NULL,
  `sequence_code` varchar(50) NOT NULL,
  `closing_fine` varchar(11) DEFAULT NULL,
  `closing_amount` varchar(11) DEFAULT NULL,
  `total_fine` varchar(11) DEFAULT NULL,
  `total_amount` varchar(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_detail`
--

CREATE TABLE `sale_detail` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `sub_item_id` int NOT NULL,
  `stamp_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `remark` text,
  `gross_weight` int DEFAULT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) DEFAULT NULL,
  `pre_touch` decimal(15,2) DEFAULT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `wastage` decimal(15,2) DEFAULT NULL,
  `fine` decimal(15,2) DEFAULT NULL,
  `piece` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `labour_type` varchar(60) DEFAULT NULL,
  `labour` decimal(15,2) DEFAULT NULL,
  `other_amount` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `lot_creation_tag` decimal(15,2) NOT NULL,
  `raw_material_data` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_material`
--

CREATE TABLE `sale_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `row_material_id` int DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return`
--

CREATE TABLE `sale_return` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `party_id` int DEFAULT NULL,
  `closing_fine` varchar(11) DEFAULT NULL,
  `closing_amount` varchar(11) DEFAULT NULL,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `code` varchar(20) NOT NULL,
  `sequence_code` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_detail`
--

CREATE TABLE `sale_return_detail` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `stamp_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `remark` text,
  `gross_weight` int DEFAULT NULL,
  `less_weight` decimal(15,2) NOT NULL,
  `net_weight` decimal(15,2) DEFAULT NULL,
  `pre_touch` decimal(15,2) DEFAULT NULL,
  `touch` decimal(15,2) DEFAULT NULL,
  `wastage` decimal(15,2) DEFAULT NULL,
  `fine` decimal(15,2) DEFAULT NULL,
  `piece` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `labour_type` varchar(60) DEFAULT NULL,
  `labour` decimal(15,2) DEFAULT NULL,
  `other_amount` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `raw_material_data` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_material`
--

CREATE TABLE `sale_return_material` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `row_material_id` int DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sequence`
--

CREATE TABLE `sequence` (
  `id` int NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `padding` int DEFAULT NULL,
  `number` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `increment` int DEFAULT NULL,
  `sequence` varchar(255) DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sequence`
--

INSERT INTO `sequence` (`id`, `prefix`, `suffix`, `padding`, `number`, `model`, `increment`, `sequence`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'PR_', '', 0, 0, 'purchase_return', 8, 'PR_8', 1, '2024-04-15 14:38:47', '2024-12-01 22:23:10'),
(2, 'P_', NULL, 0, 0, 'purchase', 30, 'P_30', 1, '2024-04-15 14:40:07', '2026-04-21 18:24:11'),
(3, 'SR_', NULL, 0, 0, 'sale_return', 9, 'SR_9', 1, '2024-04-15 14:40:43', '2024-11-13 16:48:00'),
(4, 'LS_', '', 0, 0, 'sale', 66, 'LS_66', 1, '2024-04-15 14:41:30', '2024-12-01 22:11:18'),
(5, 'PY_', '', 0, 0, 'jama', 191, 'PY_191', 1, '2024-04-15 16:03:06', '2026-04-21 18:00:27'),
(6, 'RFS_', '', 0, 0, 'ready_for_sale', 26, 'RFS_26', 1, '2024-04-25 15:48:18', '2024-11-23 13:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int NOT NULL,
  `last_order_no` double NOT NULL DEFAULT '1',
  `jama_code` double NOT NULL,
  `baki_code` double NOT NULL,
  `voucher_no` int NOT NULL DEFAULT '1',
  `purchase_code` double NOT NULL DEFAULT '1',
  `sale_code` double NOT NULL DEFAULT '1',
  `purchase_return_code` double NOT NULL DEFAULT '1',
  `sale_return_code` double NOT NULL DEFAULT '1',
  `last_manual_voucher` int DEFAULT NULL,
  `worker_jama_code` int NOT NULL DEFAULT '1',
  `worker_baki_code` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `last_order_no`, `jama_code`, `baki_code`, `voucher_no`, `purchase_code`, `sale_code`, `purchase_return_code`, `sale_return_code`, `last_manual_voucher`, `worker_jama_code`, `worker_baki_code`) VALUES
(1, 318, 213, 38, 12, 2, 101, 15, 1, 18, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stamp`
--

CREATE TABLE `stamp` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stamp`
--

INSERT INTO `stamp` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'ABC', 0, '2024-02-09 17:22:13', '2024-11-11 13:55:15'),
(2, 'XYZ', 0, '2024-02-09 17:28:53', '2024-11-11 13:55:27'),
(3, 'abc', 0, '2024-02-22 10:16:53', '2024-05-20 13:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `sub_item`
--

CREATE TABLE `sub_item` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `item_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_item`
--

INSERT INTO `sub_item` (`id`, `name`, `item_id`, `created_at`, `updated_at`) VALUES
(1, 'FANCY PAYAL 8 INCH', 1, '2024-04-25 14:09:33', '2024-06-20 16:45:28'),
(2, 'FANCY PAYAL 8.5 INCH', 1, '2024-04-25 15:08:54', '2024-06-20 16:45:37'),
(3, 'FANCY PAYAL 9 INCH', 1, '2024-04-25 15:09:30', '2024-06-20 16:47:02'),
(4, 'FANCY PAYAL 10 INCH', 1, '2024-04-25 15:09:39', '2024-06-20 16:48:19'),
(5, 'FANCY PAYAL 10.5 INCH', 1, '2024-04-25 15:10:04', '2024-06-20 16:48:32'),
(6, 'FANCY PAYAL 11 INCH', 1, '2024-04-25 15:10:17', '2024-06-20 16:48:43'),
(7, 'FANCY PAYAL 11.5 INCH', 1, '2024-04-25 15:10:31', '2024-06-20 16:49:18'),
(8, 'FANCY PAYAL 8 INCH CLR', 1, '2024-04-25 15:10:48', '2024-06-20 16:49:14'),
(9, 'FANCY PAYAL 9.5 INCH', 1, '2024-06-20 16:47:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transfer_entry`
--

CREATE TABLE `transfer_entry` (
  `id` int NOT NULL,
  `date` date DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `transfer_customer_id` int DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `narration` text,
  `gold` decimal(10,2) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `verification` enum('YES','NO') NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'KG', 0, '2024-02-09 17:38:34', '2024-03-14 11:18:41'),
(4, 'GRAM', 0, '2024-06-02 19:06:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `opening_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `otp` int DEFAULT NULL,
  `type` enum('ADMIN','OTHER') NOT NULL DEFAULT 'ADMIN',
  `permission` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `opening_fine` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `password`, `opening_amount`, `otp`, `type`, `permission`, `created_at`, `status`, `opening_fine`) VALUES
(1, 'admin', '1234567890', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 123.00, NULL, 'ADMIN', '75,76,77', '2024-01-19 10:11:08', 'ACTIVE', 12.00),
(2, 'Maulik', '9016105096', '7c4a8d09ca3762af61e59520943dc26494f8941b', 100.00, NULL, 'ADMIN', '1,2,3,5,6,7,9,10,11,33,34,35,37,38,39,109,110,111,21,22,23,25,26,27,29,30,31,45,46,47,49,50,51,53,54,55,41,42,43,57,58,59,60,61,62,63,113,80,79,78,81,114,115,116,117,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,64,65,66,68,69,70,72,73,74,82,83,84,85,102,103,108,105,104,106,107', '2024-10-28 11:23:09', 'ACTIVE', 100.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `baki`
--
ALTER TABLE `baki`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `common_bhuko`
--
ALTER TABLE `common_bhuko`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_item`
--
ALTER TABLE `customer_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garnu`
--
ALTER TABLE `garnu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garnu_item`
--
ALTER TABLE `garnu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garnu_id` (`garnu_id`);

--
-- Indexes for table `given`
--
ALTER TABLE `given`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `given_row_material`
--
ALTER TABLE `given_row_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `given_testing`
--
ALTER TABLE `given_testing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `given_testing_item`
--
ALTER TABLE `given_testing_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garnu_id` (`given_testing_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jama`
--
ALTER TABLE `jama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lot_creation`
--
ALTER TABLE `lot_creation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `tag` (`barcode`);

--
-- Indexes for table `lot_creation_barcode`
--
ALTER TABLE `lot_creation_barcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lot_wise_rm`
--
ALTER TABLE `lot_wise_rm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_garnu`
--
ALTER TABLE `main_garnu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_garnu_item`
--
ALTER TABLE `main_garnu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garnu_id` (`garnu_id`);

--
-- Indexes for table `metal_type`
--
ALTER TABLE `metal_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_garnu`
--
ALTER TABLE `pre_garnu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_garnu_item`
--
ALTER TABLE `pre_garnu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garnu_id` (`garnu_id`);

--
-- Indexes for table `pre_receive_garnu_dhal`
--
ALTER TABLE `pre_receive_garnu_dhal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_metal_type`
--
ALTER TABLE `process_metal_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_detail`
--
ALTER TABLE `purchase_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_material`
--
ALTER TABLE `purchase_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_detail`
--
ALTER TABLE `purchase_return_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_material`
--
ALTER TABLE `purchase_return_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ready_for_sale`
--
ALTER TABLE `ready_for_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ready_for_sale_detail`
--
ALTER TABLE `ready_for_sale_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ready_for_sale_material`
--
ALTER TABLE `ready_for_sale_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive`
--
ALTER TABLE `receive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_garnu`
--
ALTER TABLE `receive_garnu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_garnu_dhal`
--
ALTER TABLE `receive_garnu_dhal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_given_testing`
--
ALTER TABLE `receive_given_testing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_row_material`
--
ALTER TABLE `receive_row_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `row_material`
--
ALTER TABLE `row_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `row_material_type`
--
ALTER TABLE `row_material_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_detail`
--
ALTER TABLE `sale_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_material`
--
ALTER TABLE `sale_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return`
--
ALTER TABLE `sale_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_detail`
--
ALTER TABLE `sale_return_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_material`
--
ALTER TABLE `sale_return_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sequence`
--
ALTER TABLE `sequence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stamp`
--
ALTER TABLE `stamp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_item`
--
ALTER TABLE `sub_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_entry`
--
ALTER TABLE `transfer_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
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
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `baki`
--
ALTER TABLE `baki`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `common_bhuko`
--
ALTER TABLE `common_bhuko`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer_item`
--
ALTER TABLE `customer_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `garnu`
--
ALTER TABLE `garnu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `garnu_item`
--
ALTER TABLE `garnu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `given`
--
ALTER TABLE `given`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `given_row_material`
--
ALTER TABLE `given_row_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `given_testing`
--
ALTER TABLE `given_testing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `given_testing_item`
--
ALTER TABLE `given_testing_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jama`
--
ALTER TABLE `jama`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `lot_creation`
--
ALTER TABLE `lot_creation`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lot_creation_barcode`
--
ALTER TABLE `lot_creation_barcode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lot_wise_rm`
--
ALTER TABLE `lot_wise_rm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `main_garnu`
--
ALTER TABLE `main_garnu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_garnu_item`
--
ALTER TABLE `main_garnu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metal_type`
--
ALTER TABLE `metal_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pre_garnu`
--
ALTER TABLE `pre_garnu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pre_garnu_item`
--
ALTER TABLE `pre_garnu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pre_receive_garnu_dhal`
--
ALTER TABLE `pre_receive_garnu_dhal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `process_metal_type`
--
ALTER TABLE `process_metal_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_detail`
--
ALTER TABLE `purchase_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchase_material`
--
ALTER TABLE `purchase_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_detail`
--
ALTER TABLE `purchase_return_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_material`
--
ALTER TABLE `purchase_return_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ready_for_sale`
--
ALTER TABLE `ready_for_sale`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ready_for_sale_detail`
--
ALTER TABLE `ready_for_sale_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ready_for_sale_material`
--
ALTER TABLE `ready_for_sale_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive`
--
ALTER TABLE `receive`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `receive_garnu`
--
ALTER TABLE `receive_garnu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_garnu_dhal`
--
ALTER TABLE `receive_garnu_dhal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receive_given_testing`
--
ALTER TABLE `receive_given_testing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_row_material`
--
ALTER TABLE `receive_row_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `row_material`
--
ALTER TABLE `row_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `row_material_type`
--
ALTER TABLE `row_material_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_detail`
--
ALTER TABLE `sale_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_material`
--
ALTER TABLE `sale_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale_return`
--
ALTER TABLE `sale_return`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_detail`
--
ALTER TABLE `sale_return_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_material`
--
ALTER TABLE `sale_return_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sequence`
--
ALTER TABLE `sequence`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stamp`
--
ALTER TABLE `stamp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_item`
--
ALTER TABLE `sub_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transfer_entry`
--
ALTER TABLE `transfer_entry`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
