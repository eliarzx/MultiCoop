-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.infinityfree.com
-- Generation Time: May 29, 2025 at 10:03 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38828717_db_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `FIXED_ACCOUNTS`
--

CREATE TABLE `FIXED_ACCOUNTS` (
  `account_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_balance` decimal(12,2) NOT NULL,
  `deposit_date` datetime DEFAULT current_timestamp(),
  `maturity_date` datetime NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `status` enum('active','matured','closed') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `FIXED_ACCOUNTS`
--

INSERT INTO `FIXED_ACCOUNTS` (`account_id`, `member_id`, `account_balance`, `deposit_date`, `maturity_date`, `interest_rate`, `status`, `created_at`) VALUES
(6, 4, '36700.00', '2025-05-11 00:00:00', '2025-05-08 00:00:00', '3.00', 'active', '2025-05-07 19:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `FIXED_ACCOUNT_INTERESTS`
--

CREATE TABLE `FIXED_ACCOUNT_INTERESTS` (
  `interest_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `interest_amount` decimal(12,2) NOT NULL,
  `interest_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `FIXED_ACCOUNT_INTERESTS`
--

INSERT INTO `FIXED_ACCOUNT_INTERESTS` (`interest_id`, `account_id`, `interest_amount`, `interest_date`) VALUES
(2, 6, '6000.00', '2025-05-15 00:00:00'),
(3, 6, '4.00', '2025-05-24 00:00:00'),
(4, 6, '4000.00', '2025-05-11 00:00:00'),
(5, 6, '3.00', '2025-05-21 00:00:00'),
(6, 6, '980.00', '2025-06-17 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `INVOICES`
--

CREATE TABLE `INVOICES` (
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('unpaid','paid','overdue') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `INVOICES`
--

INSERT INTO `INVOICES` (`invoice_id`, `user_id`, `description`, `amount`, `due_date`, `status`, `created_at`) VALUES
(1, 13, 'Payment for Bills', '900.00', '2025-05-23', 'paid', '2025-05-22 11:48:48'),
(2, 13, 'Service Fee', '30.00', '2025-05-23', 'paid', '2025-05-22 11:49:42'),
(3, 13, 'Service Fee', '300.00', '2025-05-01', 'overdue', '2025-05-22 12:11:06'),
(4, 18, 'Transaction Fee', '900.00', '2025-05-30', 'paid', '2025-05-29 13:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `LOAN_ACCOUNTS`
--

CREATE TABLE `LOAN_ACCOUNTS` (
  `account_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `LOAN_ACCOUNTS`
--

INSERT INTO `LOAN_ACCOUNTS` (`account_id`, `member_id`, `loan_type_id`, `balance`, `status`, `created_at`) VALUES
(1, 1, 1, '100.00', 'active', '2025-05-07 13:43:05'),
(2, 1, 2, '5000.00', 'active', '2025-05-08 01:30:46'),
(3, 10, 1, '0.00', 'active', '2025-05-22 07:00:00'),
(4, 11, 1, '0.00', 'active', '2025-05-28 07:00:00'),
(5, 12, 1, '0.00', 'active', '2025-05-28 07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `LOAN_APPLICATION`
--

CREATE TABLE `LOAN_APPLICATION` (
  `loan_application_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `loan_type_id` int(11) DEFAULT NULL,
  `amount_requested` decimal(10,2) DEFAULT NULL,
  `term_months` int(11) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `LOAN_APPLICATION`
--

INSERT INTO `LOAN_APPLICATION` (`loan_application_id`, `member_id`, `loan_type_id`, `amount_requested`, `term_months`, `application_date`, `status`, `notes`) VALUES
(2, 11, 1, '10000.00', 6, '2025-05-28', 'rejected', ''),
(3, 11, 1, '10000.00', 6, '2025-05-28', 'rejected', ''),
(4, 11, 1, '10000.00', 6, '2025-05-28', '', ''),
(5, 11, 1, '10000.00', 6, '2025-05-28', 'approved', ''),
(6, 11, 1, '148854.00', 24, '2025-05-28', 'approved', ''),
(7, 11, 1, '10000.00', 6, '2025-05-28', 'approved', ''),
(8, 11, 1, '10000.00', 6, '2025-05-28', 'approved', ''),
(9, 11, 1, '10000.00', 6, '2025-05-28', 'approved', ''),
(10, 11, 1, '10500.00', 6, '2025-05-28', 'approved', ''),
(11, 11, 1, '1050.00', 6, '2025-05-28', 'rejected', ''),
(12, 11, 1, '40940.55', 6, '2025-05-28', 'approved', ''),
(13, 11, 1, '10500.00', 6, '2025-05-28', 'approved', ''),
(14, 11, 1, '10500.00', 6, '2025-05-28', 'approved', ''),
(15, 12, 3, '130520.00', 24, '2025-05-28', 'approved', ''),
(16, 12, 5, '76006.32', 12, '2025-05-29', 'approved', '');

-- --------------------------------------------------------

--
-- Table structure for table `LOAN_INTEREST`
--

CREATE TABLE `LOAN_INTEREST` (
  `loan_interest_id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `interest_type` enum('monthly','yearly','flat','declining') DEFAULT NULL,
  `rate` decimal(5,2) DEFAULT NULL,
  `calculated_on` date DEFAULT NULL,
  `interest_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `LOAN_INTEREST`
--

INSERT INTO `LOAN_INTEREST` (`loan_interest_id`, `loan_id`, `interest_type`, `rate`, `calculated_on`, `interest_amount`, `notes`) VALUES
(1, 1, 'monthly', '5.00', '2025-05-07', '2500.00', 'Salary loan interest for 12 months'),
(2, 2, 'monthly', '8.00', '2025-05-07', '9600.00', 'Business loan interest for 12 months'),
(3, 3, 'monthly', '4.00', '2025-05-07', '1200.00', 'Emergency loan interest for 6 months'),
(4, 4, 'monthly', '3.00', '2025-05-07', '900.00', 'Educational loan interest for 6 months'),
(5, 5, 'monthly', '2.00', '2025-05-07', '520.00', 'Calamity loan interest for 6 months'),
(8, 1, 'monthly', '23.00', '0000-00-00', '23.00', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `LOAN_REPAYMENT`
--

CREATE TABLE `LOAN_REPAYMENT` (
  `repayment_id` int(11) NOT NULL,
  `loan_application_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `LOAN_REPAYMENT`
--

INSERT INTO `LOAN_REPAYMENT` (`repayment_id`, `loan_application_id`, `amount_paid`, `payment_date`, `payment_method`) VALUES
(1, 4, '1666.67', '2025-05-28 09:17:37', 'Cash'),
(2, 4, '2000.00', '2025-05-28 09:17:52', 'Cash'),
(3, 4, '1666.67', '2025-05-28 09:22:24', 'Cash'),
(4, 4, '4666.66', '2025-05-28 09:27:32', 'Cash'),
(5, 5, '1666.67', '2025-05-28 09:35:43', 'Cash'),
(6, 5, '1708.33', '2025-05-28 09:47:58', 'Cash'),
(7, 15, '5873.40', '2025-05-28 10:07:47', 'Cash'),
(8, 15, '90000.00', '2025-05-29 07:51:42', 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `LOAN_TYPE`
--

CREATE TABLE `LOAN_TYPE` (
  `loan_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `interest_type` enum('monthly','yearly') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `LOAN_TYPE`
--

INSERT INTO `LOAN_TYPE` (`loan_type_id`, `name`, `interest_rate`, `interest_type`, `created_at`) VALUES
(1, 'Salary Loan', '5.00', 'monthly', '2025-05-07 05:47:35'),
(2, 'Business Loan', '8.00', 'monthly', '2025-05-07 05:47:35'),
(3, 'Emergency Loan', '4.00', 'monthly', '2025-05-07 05:47:35'),
(4, 'Educational Loan', '3.00', 'monthly', '2025-05-07 05:47:35'),
(5, 'Calamity', '2.00', 'monthly', '2025-05-07 05:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `MEMBER_PROFILE`
--

CREATE TABLE `MEMBER_PROFILE` (
  `member_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_type` enum('loan','savings','time_deposit','fixed_account') NOT NULL,
  `Balance` int(11) NOT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `net_income` decimal(10,2) DEFAULT NULL,
  `wants_to_open_account` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `MEMBER_PROFILE`
--

INSERT INTO `MEMBER_PROFILE` (`member_id`, `user_id`, `account_type`, `Balance`, `monthly_salary`, `net_income`, `wants_to_open_account`, `created_at`) VALUES
(1, 1, 'loan', 10000, '30000.00', '25000.00', 1, '2025-05-08 01:30:46'),
(2, 2, 'savings', 20000, '40000.00', '32000.00', 1, '2025-05-08 01:30:46'),
(3, 3, 'time_deposit', 30000, '50000.00', '40000.00', 1, '2025-05-08 01:30:46'),
(4, 5, 'fixed_account', 40000, '60000.00', '48000.00', 1, '2025-05-08 01:30:46'),
(6, 8, 'savings', 0, '800.00', '90.00', 1, '2025-05-10 23:34:55'),
(7, 7, 'fixed_account', 600210, '1000.00', '10.00', 1, '2025-05-10 23:58:44'),
(8, 7, 'fixed_account', 510253, '19000.00', '9000.00', 1, '2025-05-11 00:21:36'),
(9, 9, 'time_deposit', 0, '90000.00', '90000.00', 1, '2025-05-16 05:50:42'),
(10, 13, 'savings', 8460, '90000.00', '90000.00', 1, '2025-05-22 10:22:47'),
(11, 16, 'loan', 283136, '28000.00', '20000.00', 1, '2025-05-28 09:23:08'),
(12, 17, 'loan', 110653, '120000.00', '100000.00', 1, '2025-05-28 14:06:09'),
(13, 18, 'savings', 45000, '98000.00', '90000.00', 1, '2025-05-29 13:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `MONTHLY_AMORTIZATION`
--

CREATE TABLE `MONTHLY_AMORTIZATION` (
  `amortization_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `MONTHLY_AMORTIZATION`
--

INSERT INTO `MONTHLY_AMORTIZATION` (`amortization_id`, `loan_type_id`, `amount`, `duration_months`, `created_at`) VALUES
(5, 1, '2100.00', 12, '2025-05-07 05:47:35'),
(6, 1, '1100.00', 24, '2025-05-07 05:47:35'),
(7, 2, '5200.00', 12, '2025-05-07 05:47:35'),
(8, 2, '2800.00', 24, '2025-05-07 05:47:35'),
(9, 3, '1050.00', 6, '2025-05-07 05:47:35'),
(10, 3, '550.00', 12, '2025-05-07 05:47:35'),
(11, 4, '850.00', 6, '2025-05-07 05:47:35'),
(12, 4, '460.00', 12, '2025-05-07 05:47:35'),
(13, 5, '430.00', 6, '2025-05-07 05:47:35'),
(14, 5, '220.00', 12, '2025-05-07 05:47:35'),
(19, 1, '8900.00', 3, '2025-05-29 06:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATIONS`
--

CREATE TABLE `NOTIFICATIONS` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `NOTIFICATIONS`
--

INSERT INTO `NOTIFICATIONS` (`notification_id`, `user_id`, `message`, `created_at`) VALUES
(1, 13, 'You withdrew PHP 300.00 on May 22, 2025 06:44 AM', '2025-05-22 10:44:20'),
(2, 13, 'You withdrew PHP 40.00 on May 22, 2025 06:44 AM', '2025-05-22 10:44:33'),
(3, 13, 'You deposited PHP 900.00.', '2025-05-22 10:48:02'),
(4, 13, 'You deposited PHP 900.00.', '2025-05-22 10:48:15'),
(5, 13, 'You deposited PHP 900.00.', '2025-05-22 10:48:18'),
(6, 13, 'You deposited PHP 900.00.', '2025-05-22 10:50:48'),
(7, 13, 'You deposited PHP 300.00.', '2025-05-22 10:50:57'),
(8, 13, 'You withdrew PHP 8,000.00 on May 22, 2025 06:51 AM', '2025-05-22 10:51:10'),
(9, 13, 'You deposited PHP 900.00.', '2025-05-22 10:51:54'),
(10, 7, 'You withdrew PHP 900.00 on May 25, 2025 04:45 AM', '2025-05-25 08:45:18'),
(11, 7, 'You withdrew PHP 9,000.00 on May 25, 2025 04:46 AM', '2025-05-25 08:46:36'),
(12, 7, 'You withdrew PHP 9,000.00 on May 25, 2025 04:46 AM', '2025-05-25 08:46:49'),
(13, 7, 'You deposited PHP 10.00.', '2025-05-28 09:12:07'),
(14, 7, 'You withdrew PHP 4,500.00 on May 28, 2025 05:12 AM', '2025-05-28 09:12:18'),
(15, 7, 'You deposited PHP 10,000.00.', '2025-05-28 09:19:11'),
(16, 7, 'You deposited PHP 900,000.00.', '2025-05-28 09:19:15'),
(17, 7, 'You withdrew PHP 90,000.00 on May 28, 2025 05:19 AM', '2025-05-28 09:19:26'),
(18, 7, 'You withdrew PHP 100,000.00 on May 28, 2025 05:19 AM', '2025-05-28 09:19:43'),
(19, 7, 'You withdrew PHP 10,000.00 on May 28, 2025 05:19 AM', '2025-05-28 09:19:57'),
(20, 7, 'You withdrew PHP 120,000.00 on May 28, 2025 05:20 AM', '2025-05-28 09:20:27'),
(21, 16, 'Your loan application for PHP 90000 has been submitted.', '2025-05-28 10:02:28'),
(22, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 07:46 AM', '2025-05-28 11:46:02'),
(23, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 07:59 AM', '2025-05-28 11:59:11'),
(24, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 08:08 AM', '2025-05-28 12:08:35'),
(25, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 08:16 AM', '2025-05-28 12:16:53'),
(26, 16, 'You made a loan payment of PHP 1,666.67 on May 28, 2025 09:17 AM for Loan ID: 5', '2025-05-28 13:17:37'),
(27, 16, 'You made a loan payment of PHP 2,000.00 on May 28, 2025 09:17 AM for Loan ID: 5', '2025-05-28 13:17:52'),
(28, 16, 'You made a loan payment of PHP 1,666.67 on May 28, 2025 09:22 AM for Loan ID: 5', '2025-05-28 13:22:24'),
(29, 16, 'You made a loan payment of PHP 4,666.66 on May 28, 2025 09:27 AM for Loan ID: 5', '2025-05-28 13:27:32'),
(30, 16, 'You submitted a loan application for PHP 148,854.00 on May 28, 2025 09:28 AM', '2025-05-28 13:28:02'),
(31, 16, 'You made a loan payment of PHP 1,666.67 on May 28, 2025 09:35 AM for Loan ID: 6', '2025-05-28 13:35:43'),
(32, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 09:37 AM', '2025-05-28 13:37:36'),
(33, 16, 'You made a loan payment of PHP 1,708.33 on May 28, 2025 09:47 AM for Loan ID: 6', '2025-05-28 13:47:58'),
(34, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 09:50 AM', '2025-05-28 13:50:48'),
(35, 16, 'You submitted a loan application for PHP 10,000.00 on May 28, 2025 09:51 AM', '2025-05-28 13:51:55'),
(36, 16, 'You submitted a loan application for PHP 10,000.00 (Total Repayment: PHP 10,500.00) on May 28, 2025 09:54 AM. Your total balance has been updated.', '2025-05-28 13:54:10'),
(37, 16, 'You submitted a loan application for PHP 1,000.00 (Total Repayment: PHP 1,050.00) on May 28, 2025 09:54 AM. Your total balance has been updated.', '2025-05-28 13:54:55'),
(38, 16, 'You submitted a loan application for PHP 38,991.00 (Estimated Total Repayment: PHP 40,940.55) on May 28, 2025 09:56 AM. It is now pending approval.', '2025-05-28 13:56:21'),
(39, 16, 'Your loan application (ID: 12) for PHP 40,940.55 has been **Approved**. Your balance has been updated.', '2025-05-28 13:58:50'),
(40, 16, 'You submitted a loan application for PHP 10,000.00 (Estimated Total Repayment: PHP 10,500.00) on May 28, 2025 09:59 AM. It is now pending approval.', '2025-05-28 13:59:10'),
(41, 16, 'Your loan application (ID: 13) for PHP 10,500.00 has been **Approved**. Your balance has been updated.', '2025-05-28 13:59:32'),
(42, 16, 'You submitted a loan application for PHP 10,000.00 (Estimated Total Repayment: PHP 10,500.00) on May 28, 2025 09:59 AM. It is now pending approval.', '2025-05-28 13:59:58'),
(43, 16, 'Your loan application (ID: 14) for PHP 10,500.00 has been **Approved**. Your balance has been updated.', '2025-05-28 14:00:05'),
(44, 17, 'You submitted a loan application for PHP 125,500.00 (Estimated Total Repayment: PHP 130,520.00) on May 28, 2025 10:07 AM. It is now pending approval.', '2025-05-28 14:07:01'),
(45, 17, 'Your loan application (ID: 15) for PHP 130,520.00 has been **Approved**. Your balance has been updated.', '2025-05-28 14:07:18'),
(46, 17, 'You made a loan payment of PHP 5,873.40 on May 28, 2025 10:07 AM for Loan ID: 15', '2025-05-28 14:07:47'),
(47, 7, 'You deposited PHP 500.00.', '2025-05-29 01:16:10'),
(48, 7, 'You withdrew PHP 56,900.00 on May 28, 2025 09:16 PM', '2025-05-29 01:16:24'),
(49, 17, 'You submitted a loan application for PHP 74,516.00 (Estimated Total Repayment: PHP 76,006.32) on May 29, 2025 07:50 AM. It is now pending approval.', '2025-05-29 11:50:56'),
(50, 17, 'Your loan application (ID: 16) for PHP 76,006.32 has been **Approved**. Your balance has been updated.', '2025-05-29 11:51:16'),
(51, 17, 'You made a loan payment of PHP 90,000.00 on May 29, 2025 07:51 AM for Loan ID: 15', '2025-05-29 11:51:42'),
(52, 18, 'You deposited PHP 120,000.00.', '2025-05-29 13:50:49'),
(53, 18, 'You withdrew PHP 75,000.00 on May 29, 2025 09:51 AM', '2025-05-29 13:51:47');

-- --------------------------------------------------------

--
-- Table structure for table `PAYMENT_PROOFS`
--

CREATE TABLE `PAYMENT_PROOFS` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `PAYMENT_PROOFS`
--

INSERT INTO `PAYMENT_PROOFS` (`id`, `user_id`, `file_path`, `uploaded_at`) VALUES
(1, 13, './uploads/payment_proofs/13_1747911350.pdf', '2025-05-22 03:55:50'),
(2, 13, './uploads/payment_proofs/13_1747911599.pdf', '2025-05-22 03:59:59'),
(3, 13, './uploads/payment_proofs/13_1747911669.pdf', '2025-05-22 04:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `SAVINGS_ACCOUNTS`
--

CREATE TABLE `SAVINGS_ACCOUNTS` (
  `account_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_balance` decimal(12,2) NOT NULL,
  `deposit_date` datetime DEFAULT current_timestamp(),
  `interest_rate` decimal(5,2) NOT NULL,
  `status` enum('active','closed') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `SAVINGS_ACCOUNTS`
--

INSERT INTO `SAVINGS_ACCOUNTS` (`account_id`, `member_id`, `account_balance`, `deposit_date`, `interest_rate`, `status`, `created_at`) VALUES
(2, 2, '2000.00', '2025-05-12 00:00:00', '1.25', 'active', '2025-05-07 18:30:46'),
(5, 13, '0.00', '2025-05-29 00:00:00', '0.01', 'active', '2025-05-29 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `TIME_DEPOSIT_ACCOUNTS`
--

CREATE TABLE `TIME_DEPOSIT_ACCOUNTS` (
  `account_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_balance` decimal(12,2) NOT NULL,
  `deposit_date` datetime DEFAULT current_timestamp(),
  `maturity_date` datetime NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `status` enum('active','matured','closed') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `TIME_DEPOSIT_ACCOUNTS`
--

INSERT INTO `TIME_DEPOSIT_ACCOUNTS` (`account_id`, `member_id`, `account_balance`, `deposit_date`, `maturity_date`, `interest_rate`, `status`, `created_at`) VALUES
(3, 3, '30000.00', '2025-05-07 18:30:46', '2026-05-08 00:00:00', '2.50', 'active', '2025-05-07 18:30:46'),
(7, 7, '9000.00', '2025-05-21 00:00:00', '2025-05-15 00:00:00', '9.00', 'active', '2025-05-10 00:00:00'),
(9, 9, '9000.00', '2025-05-21 00:00:00', '2025-05-31 00:00:00', '29.00', 'active', '2025-05-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `TIME_DEPOSIT_INTEREST`
--

CREATE TABLE `TIME_DEPOSIT_INTEREST` (
  `interest_id` int(11) NOT NULL,
  `term_months` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `TIME_DEPOSIT_INTEREST`
--

INSERT INTO `TIME_DEPOSIT_INTEREST` (`interest_id`, `term_months`, `interest_rate`, `created_at`) VALUES
(2, 25, '2.00', '2025-05-08 00:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

CREATE TABLE `USERS` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `municipality_code` varchar(50) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `role` enum('admin','member','accountant') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `USERS`
--

INSERT INTO `USERS` (`user_id`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `barangay`, `municipality_code`, `province`, `role`, `created_at`) VALUES
(1, 'Juan', 'Cruz', 'Dela', '1990-02-10', 'Male', 'Barangay Uno', 'M001', 'Cebu', 'member', '2025-05-08 01:30:46'),
(2, 'Maria', 'Lopez', 'Reyes', '1988-05-25', 'Female', 'Barangay Dos', 'M002', 'Davao', 'member', '2025-05-08 01:30:46'),
(3, 'Carlos', 'Gomez', 'Santos', '1985-11-14', 'Male', 'Barangay Tres', 'M003', 'Manila', 'member', '2025-05-08 01:30:46'),
(4, 'Admin', 'Admin', 'Admin', '2002-01-01', 'Female', NULL, NULL, NULL, 'admin', '2025-05-08 01:09:58'),
(5, 'Ana', 'Rosario', 'Torres', '1995-09-03', 'Female', 'Barangay Cuatro', 'M004', 'Laguna', 'member', '2025-05-08 01:30:46'),
(7, 'Mae', 'Cruz', 'Santos', '2000-09-03', 'Female', 'Dakila', '3000', 'Bulacan', 'member', '2025-05-10 23:21:16'),
(8, 'Marie', 'Gomez', 'Lopez', '2003-02-03', 'Female', NULL, NULL, NULL, 'admin', '2025-05-10 23:26:31'),
(9, 'Karell', 'Mendoza', 'de Guzman', '2005-01-02', 'Female', NULL, NULL, NULL, 'member', '2025-05-11 14:46:34'),
(10, 'John', 'Mendoza', 'Dela Cruz', '2000-02-20', 'Male', NULL, NULL, NULL, 'member', '2025-05-11 14:59:24'),
(11, 'Karell', 'Mendoza', 'De Guzman', '2000-02-19', 'Female', NULL, NULL, NULL, 'member', '2025-05-16 05:37:26'),
(12, 'Karell', 'Mendoza', 'De Guzman', '2003-02-02', 'Female', NULL, NULL, NULL, 'member', '2025-05-16 05:49:34'),
(13, 'Sarah', 'Cruz', 'Santos', '2003-02-22', 'Female', 'Bagong Bayan', '3000', 'Bulacan', 'member', '2025-05-22 10:19:51'),
(14, 'Rose', 'Philip', 'Hernandez', '2000-02-03', 'Female', NULL, NULL, NULL, 'accountant', '2025-05-22 11:17:41'),
(15, 'James', '', 'Williams', '1999-02-02', 'Male', NULL, NULL, NULL, 'member', '2025-05-22 13:13:42'),
(16, 'Guia', 'Cruz', 'Dela Cruz', '2000-01-02', 'Female', NULL, NULL, NULL, 'member', '2025-05-28 09:22:06'),
(17, 'Danny', 'Cruz', 'Domingo', '1999-02-02', 'Male', NULL, NULL, NULL, 'member', '2025-05-28 14:05:26'),
(18, 'Princess', 'Cruz', 'Mendoza', '2000-05-02', 'Female', 'Dakila', '3000', 'Bulacan', 'member', '2025-05-29 13:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `USER_ACCOUNT`
--

CREATE TABLE `USER_ACCOUNT` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `USER_ACCOUNT`
--

INSERT INTO `USER_ACCOUNT` (`account_id`, `user_id`, `email`, `password`, `created_at`) VALUES
(1, 1, 'juan@example.com', 'hashed_pass1', '2025-05-08 01:30:46'),
(2, 2, 'maria@example.com', 'hashed_pass2', '2025-05-08 01:30:46'),
(3, 4, 'superadmin@gmail.com', '$2y$10$3ZUJRjn7MYYgVnEU6KJ/5.bDaBIjjVocnp5IczV7SPd2oDQJ3T7de', '2025-05-08 01:10:12'),
(4, 3, 'carlos@example.com', 'hashed_pass3', '2025-05-08 01:30:46'),
(5, 5, 'ana@example.com', 'hashed_pass4', '2025-05-08 01:30:46'),
(6, 6, 'krl@gmail.com', '$2y$10$5kcw61vxEMG80rbTOQ16t.ZuSO/apr1xZXGPlV9K.1LImPTMSUrry', '2025-05-10 23:16:24'),
(7, 7, 'marie@gmail.com', '$2y$10$Thjef7lHBtmzv8f7hPhWp.W9hiNj0ZNOJ/Pa1BQr0wd4yjp4GJZ2.', '2025-05-10 23:24:57'),
(8, 8, 'ad@gmail.com', '$2y$10$Jih4Je7YwzJfRm6kB8GIL.GxxOoPEwaNm7kU8mLALwcLPssAFDHwm', '2025-05-10 23:26:41'),
(12, 12, 'krll@gmail.com', '$2y$10$ICd8GyvS1Tr5g6t6F8Wl1eCCN6LwKhF7Kxr/nISIWisaxy2b5lalK', '2025-05-16 05:50:00'),
(13, 13, 'sarah@gmail.com', '$2y$10$hOBBNEEb7CEvSRpYuyYQUuXtyTMeqcb5yQzOIpYx./Ce7C.q67RiG', '2025-05-22 10:20:05'),
(14, 14, 'rose@gmail.com', '$2y$10$jV4tJTacEkO.hpytyLZYHesEJ5fMlpyg34pa6Y6HXlH0xbmk3qaym', '2025-05-22 11:17:53'),
(15, 15, 'jw@gmail.com', '$2y$10$vbKGYWRqz36L9Y38twp8KOOvTlwXon0QTY9wVP7tqBHPPydgwatB6', '2025-05-22 13:13:59'),
(16, 16, 'guia@gmail.com', '$2y$10$IH5vwxDL4RV00s9KI6c0z.BV3P3RRxA3uyfiiwR5dXxKBS1.SE3kO', '2025-05-28 09:22:16'),
(17, 17, 'danny@gmail.com', '$2y$10$DVonjWyyk6Lo185Re/zUtem21JL8OhsjMwrEyfY/TvZALI6YQL1Pa', '2025-05-28 14:05:40'),
(18, 18, 'princess@gmail.com', '$2y$10$wmnN98oLYb5/vQP.6WspeeiJNoC6bJQmDqYNixpH1VJ3QbrO0GgOK', '2025-05-29 13:47:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `FIXED_ACCOUNTS`
--
ALTER TABLE `FIXED_ACCOUNTS`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `FIXED_ACCOUNT_INTERESTS`
--
ALTER TABLE `FIXED_ACCOUNT_INTERESTS`
  ADD PRIMARY KEY (`interest_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `INVOICES`
--
ALTER TABLE `INVOICES`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `LOAN_ACCOUNTS`
--
ALTER TABLE `LOAN_ACCOUNTS`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `LOAN_APPLICATION`
--
ALTER TABLE `LOAN_APPLICATION`
  ADD PRIMARY KEY (`loan_application_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `LOAN_INTEREST`
--
ALTER TABLE `LOAN_INTEREST`
  ADD PRIMARY KEY (`loan_interest_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `LOAN_REPAYMENT`
--
ALTER TABLE `LOAN_REPAYMENT`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_application_id` (`loan_application_id`);

--
-- Indexes for table `LOAN_TYPE`
--
ALTER TABLE `LOAN_TYPE`
  ADD PRIMARY KEY (`loan_type_id`);

--
-- Indexes for table `MEMBER_PROFILE`
--
ALTER TABLE `MEMBER_PROFILE`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `MONTHLY_AMORTIZATION`
--
ALTER TABLE `MONTHLY_AMORTIZATION`
  ADD PRIMARY KEY (`amortization_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `PAYMENT_PROOFS`
--
ALTER TABLE `PAYMENT_PROOFS`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `SAVINGS_ACCOUNTS`
--
ALTER TABLE `SAVINGS_ACCOUNTS`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `TIME_DEPOSIT_ACCOUNTS`
--
ALTER TABLE `TIME_DEPOSIT_ACCOUNTS`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `TIME_DEPOSIT_INTEREST`
--
ALTER TABLE `TIME_DEPOSIT_INTEREST`
  ADD PRIMARY KEY (`interest_id`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `USER_ACCOUNT`
--
ALTER TABLE `USER_ACCOUNT`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `FIXED_ACCOUNTS`
--
ALTER TABLE `FIXED_ACCOUNTS`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `FIXED_ACCOUNT_INTERESTS`
--
ALTER TABLE `FIXED_ACCOUNT_INTERESTS`
  MODIFY `interest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `INVOICES`
--
ALTER TABLE `INVOICES`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `LOAN_ACCOUNTS`
--
ALTER TABLE `LOAN_ACCOUNTS`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `LOAN_APPLICATION`
--
ALTER TABLE `LOAN_APPLICATION`
  MODIFY `loan_application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `LOAN_INTEREST`
--
ALTER TABLE `LOAN_INTEREST`
  MODIFY `loan_interest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `LOAN_REPAYMENT`
--
ALTER TABLE `LOAN_REPAYMENT`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `LOAN_TYPE`
--
ALTER TABLE `LOAN_TYPE`
  MODIFY `loan_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `MEMBER_PROFILE`
--
ALTER TABLE `MEMBER_PROFILE`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `MONTHLY_AMORTIZATION`
--
ALTER TABLE `MONTHLY_AMORTIZATION`
  MODIFY `amortization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `PAYMENT_PROOFS`
--
ALTER TABLE `PAYMENT_PROOFS`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `SAVINGS_ACCOUNTS`
--
ALTER TABLE `SAVINGS_ACCOUNTS`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `TIME_DEPOSIT_ACCOUNTS`
--
ALTER TABLE `TIME_DEPOSIT_ACCOUNTS`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `TIME_DEPOSIT_INTEREST`
--
ALTER TABLE `TIME_DEPOSIT_INTEREST`
  MODIFY `interest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `USERS`
--
ALTER TABLE `USERS`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `USER_ACCOUNT`
--
ALTER TABLE `USER_ACCOUNT`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
