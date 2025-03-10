-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2025 at 04:02 PM
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
-- Database: `gym`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `bmi_recommendations`
--

CREATE TABLE `bmi_recommendations` (
  `id` int(11) NOT NULL,
  `bmi_min` decimal(4,1) DEFAULT NULL,
  `bmi_max` decimal(4,1) DEFAULT NULL,
  `workout_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bmi_recommendations`
--

INSERT INTO `bmi_recommendations` (`id`, `bmi_min`, `bmi_max`, `workout_id`) VALUES
(1, 15.0, 18.4, 1),
(2, 18.5, 24.9, 2),
(3, 18.5, 24.9, 3),
(4, 18.5, 24.9, 4),
(5, 18.5, 29.9, 5),
(6, 25.0, 29.9, 6),
(7, 18.5, 29.9, 7),
(8, 15.0, 24.9, 8),
(9, 30.0, 40.0, 9),
(10, 15.0, 29.9, 10),
(11, 15.0, 24.9, 11),
(12, 15.0, 29.9, 12),
(13, 18.5, 40.0, 13),
(14, 25.0, 40.0, 14);

-- --------------------------------------------------------

--
-- Table structure for table `eqp_payment`
--

CREATE TABLE `eqp_payment` (
  `pay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` text NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `rent_price_per_month` decimal(10,2) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `available_quantity` int(11) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equipment_id`, `name`, `rent_price_per_month`, `purchase_price`, `available_quantity`, `image_path`) VALUES
(1, 'Treadmill', 1200.00, 60000.00, 46, 'images/treadmill.jpg'),
(2, 'Elliptical Machine', 900.00, 48000.00, 47, 'images/elliptical.jpg'),
(3, 'Stationary Bike', 600.00, 25000.00, 32, 'images/stationary_bike.jpg'),
(4, 'Rowing Machine', 950.00, 50000.00, 50, 'images/rowing_machine.jpg'),
(5, 'Dumbbell Set', 0.00, 6000.00, 200, 'images/dumbbell.jpg'),
(6, 'Yoga Mat', 0.00, 1200.00, 200, 'images/yoga_mat.jpg'),
(7, 'Kettlebell', 0.00, 3500.00, 200, 'images/kettlebell.jpg'),
(8, 'Pull-Up Bar', 0.00, 2500.00, 200, 'images/pull_up_bar.jpg'),
(9, 'Resistance Bands', 0.00, 600.00, 200, 'images/resistance_bands.jpg'),
(10, 'Barbell', 250.00, 12000.00, 50, 'images/barbell.jpg'),
(11, 'Bench Press', 800.00, 27000.00, 50, 'images/bench_press.jpg'),
(12, 'Punching Bag', 0.00, 9000.00, 50, 'images/punching_bag.jpg'),
(13, 'Foam Roller', 0.00, 400.00, 200, 'images/foam_roller.jpg'),
(14, 'Medicine Ball', 0.00, 1600.00, 200, 'images/medicine_ball.jpg'),
(15, 'Jump Rope', 0.00, 150.00, 200, 'images/jump_rope.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `experience` int(11) NOT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `specialty`, `experience`, `availability`, `phone_number`, `email`, `image_url`, `status`) VALUES
(1, 'Subodh Kc', 'Cardio', 5, 1, '123-456-7890', 'subodhkc@email.com', 'images/subodh_kc.jpg', 'active'),
(2, 'Kabita Nepali', 'Strength Training', 3, 1, '234-567-8901', 'kabitanepali@email.com', 'images/kabita_nepali.jpg', 'active'),
(3, 'Samir Sharma', 'Yoga', 7, 1, '345-678-9012', 'samirsharma@email.com', 'images/samir_sharma.jpg', 'active'),
(4, 'Mohit Joshi', 'Zumba', 2, 1, '456-789-0123', 'mohitjoshi@email.com', 'images/mohit_joshi.jpg', 'active'),
(5, 'Abhinav Gurung', 'Boxing', 4, 1, '567-890-1234', 'abhinavgurung@email.com', 'images/abhinav_gurung.jpg', 'active'),
(6, 'Rajani Shrestha', 'Body Building', 4, 1, '9869374999', 'stharajani@gmail.com', 'images/rajani_shreastha.jpg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_request`
--

CREATE TABLE `instructor_request` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requested_date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `approved_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_request`
--

INSERT INTO `instructor_request` (`id`, `instructor_id`, `user_id`, `requested_date`, `status`, `approved_date`) VALUES
(1, 5, 2, '2025-01-24', 'complete', '2025-02-28'),
(2, 5, 2, '2025-01-24', 'complete', '2025-02-28'),
(3, 5, 13, '2025-02-21', 'rejected', '2025-02-26'),
(4, 6, 2, '2025-02-27', 'complete', '2025-02-28'),
(5, 4, 13, '2025-02-27', 'rejected', '2025-02-26'),
(6, 2, 2, '2025-02-27', 'complete', '2025-02-28'),
(7, 3, 13, '2025-02-27', 'rejected', '2025-02-26'),
(8, 2, 2, '2025-02-27', 'complete', '2025-02-28'),
(9, 1, 13, '2025-02-27', 'rejected', '2025-02-26'),
(10, 3, 13, '2025-02-28', 'rejected', NULL),
(11, 6, 2, '2025-03-01', 'complete', '2025-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `membership_requests`
--

CREATE TABLE `membership_requests` (
  `_id` int(11) NOT NULL,
  `req_userid` int(11) NOT NULL,
  `request_type` enum('renewal','upgrade') NOT NULL,
  `requested_membership_type` varchar(50) DEFAULT NULL,
  `requested_date` datetime DEFAULT current_timestamp(),
  `status` text DEFAULT 'pending',
  `approved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_requests`
--

INSERT INTO `membership_requests` (`_id`, `req_userid`, `request_type`, `requested_membership_type`, `requested_date`, `status`, `approved_date`) VALUES
(1, 2, 'upgrade', 'Silver', '2025-02-28 16:38:45', 'rejected', NULL),
(2, 13, 'upgrade', 'Gold', '2025-02-28 16:39:13', 'complete', NULL),
(3, 13, 'upgrade', 'Gold', '2025-02-28 16:57:37', 'complete', NULL),
(4, 2, 'upgrade', 'Silver', '2025-02-28 17:07:43', 'complete', NULL),
(5, 2, 'upgrade', 'Gold', '2025-03-01 00:29:34', 'rejected', NULL),
(6, 2, 'renewal', NULL, '2025-03-10 00:44:48', 'rejected', NULL),
(8, 2, 'renewal', NULL, '2025-03-10 01:09:41', 'complete', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `memb_payment`
--

CREATE TABLE `memb_payment` (
  `pay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1000,
  `status` text NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memb_payment`
--

INSERT INTO `memb_payment` (`pay_id`, `user_id`, `amount`, `status`) VALUES
(1, 2, 1000, 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `section` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `section`, `status`, `created_at`) VALUES
(4, 13, 'Your request for buying Whey Protein was denied by the admin.', '', 'read', '2025-02-26 16:25:00'),
(5, 13, 'Your request for buying Mass Gainer was approved. The product will be delivered soon.', '', 'read', '2025-02-26 16:25:05'),
(6, 13, 'Your request for buying Creatine Monohydrate was denied by the admin.', '', 'read', '2025-02-26 16:35:51'),
(7, 2, 'Your request for buying BCAA Powder was approved. The product will be delivered soon.', '', 'read', '2025-02-26 16:35:53'),
(8, 13, 'Your request for instructor Mohit Joshi was denied by the admin.', '', 'read', '2025-02-26 23:57:54'),
(9, 13, 'Your request for instructor Mohit Joshi was approved.', '', 'read', '2025-02-26 23:57:56'),
(10, 2, 'Your request for instructor Samir Sharma was approved.', '', 'read', '2025-02-27 00:04:43'),
(11, 13, 'Your request for instructor Samir Sharma was denied by the admin.', '', 'read', '2025-02-27 00:04:44'),
(12, 13, 'Your request for instructor Subodh Kc was approved.', '', 'read', '2025-02-27 00:15:59'),
(13, 2, 'Your request for instructor Kabita Nepali was denied by the admin.', '', 'read', '2025-02-27 00:16:02'),
(14, 13, 'Your request for instructor Samir Sharma was denied by the admin.', '', 'read', '2025-02-28 16:08:15'),
(15, 13, 'Your membership upgrade request has been approved. You are now a Gold member.', '', 'read', '2025-02-28 16:08:17'),
(16, 2, 'Your membership upgrade request has been approved. You are now a Silver member.', '', 'unread', '2025-02-28 16:08:18'),
(17, 2, 'Your request for buying Mass Gainer was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-02-28 23:34:13'),
(18, 2, 'Your request for instructor Rajani Shrestha was approved.', 'instructor', 'read', '2025-02-28 23:34:15'),
(19, 2, 'Your membership upgrade request has been denied.', 'membership', 'read', '2025-02-28 23:34:18'),
(23, 2, 'Your request for buying Treadmill has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:05:13'),
(24, 2, 'Your request for buying Whey Protein was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-01 00:33:40'),
(25, 2, 'Your request for buying Treadmill was denied by the admin', 'prod_equip', 'read', '2025-03-01 00:33:41'),
(26, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:43'),
(27, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:44'),
(28, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:45'),
(29, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:45'),
(30, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:45'),
(31, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:46'),
(32, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-01 00:33:46'),
(41, 2, 'Your request for renting Elliptical Machine was approved by the admin. The equipment will be delivered soon.', 'rental', 'read', '2025-03-01 01:09:18'),
(42, 2, 'Your request for renting Stationary Bike was approved by the admin. The equipment will be delivered soon.', 'rental', 'read', '2025-03-01 01:10:21'),
(43, 2, 'Your request for buying Stationary Bike was denied by the admin', 'prod_equip', 'read', '2025-03-01 01:10:24'),
(44, 2, 'Your request for renting  was denied by the admin..', 'rental', 'read', '2025-03-01 01:11:43'),
(45, 2, 'Your request for renting Rowing Machine was approved by the admin. The equipment will be delivered soon.', 'rental', 'read', '2025-03-01 18:05:41'),
(46, 2, 'Your request for renting Rowing Machine was approved by the admin. The equipment will be delivered soon.', 'rental', 'read', '2025-03-01 18:05:42'),
(49, 2, 'Your request for returning Rowing Machine was approved by the admin.', 'return_rental', 'read', '2025-03-01 18:18:59'),
(50, 2, 'Your request for returning Rowing Machine was approved by the admin.', 'return_rental', 'read', '2025-03-01 18:22:27'),
(51, 2, 'Your request for returning Rowing Machine was approved by the admin.', 'return_rental', 'read', '2025-03-01 18:23:23'),
(52, 2, 'Your request for renting Rowing Machine was approved by the admin. The equipment will be delivered soon.', 'rental', 'read', '2025-03-01 19:29:11'),
(53, 2, 'Your request for returning Rowing Machine was approved by the admin.', 'return_rental', 'read', '2025-03-01 19:30:19'),
(54, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-06 13:11:00'),
(55, 2, 'Your request for buying Mass Gainer was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-06 13:11:01'),
(56, 2, 'Your request for buying Stationary Bike has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-06 15:28:13'),
(57, 2, 'Your request for buying Stationary Bike was denied by the admin', 'prod_equip', 'read', '2025-03-06 15:28:16'),
(58, 2, 'Your request for buying Elliptical Machine has been approved by admin. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-08 15:48:40'),
(59, 2, 'Your request for buying Mass Gainer was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-09 00:53:48'),
(60, 2, 'Your request for buying Elliptical Machine has been approved. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-09 00:53:49'),
(61, 2, 'Your request for buying Elliptical Machine has been approved. The equipment will be delivered soon', 'prod_equip', 'read', '2025-03-09 00:53:51'),
(62, 2, 'Your request for renting Elliptical Machine was approved. The equipment will be delivered soon.', 'rental', 'read', '2025-03-09 00:53:52'),
(63, 2, 'Your request for renting Elliptical Machine was approved. The equipment will be delivered soon.', 'rental', 'read', '2025-03-09 00:53:53'),
(64, 2, 'Payment approved! Next due date: 2025-04-09', 'payment', 'read', '2025-03-09 00:53:56'),
(65, 2, 'Your request for payment was rejected.', 'payment', 'read', '2025-03-09 00:53:57'),
(66, 2, 'Your request for buying Fat Burner was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-09 22:37:10'),
(67, 2, 'Your request for buying Fat Burner was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-09 22:37:12'),
(68, 2, 'Your request for buying Fat Burner was approved. The product will be delivered soon.', 'prod_eqp', 'unread', '2025-03-09 22:38:53'),
(69, 2, 'Your membership renewal request has been denied.', 'membership', 'read', '2025-03-09 23:46:49'),
(70, 2, 'Your request for renting  was denied.', 'rental', 'read', '2025-03-09 23:46:52'),
(71, 2, 'Your request for buying Fat Burner was denied by the admin.', 'prod_eqp', 'unread', '2025-03-09 23:46:53'),
(72, 2, 'Your membership renewal request has been approved. Your new expiry date is updated.', 'membership', 'read', '2025-03-10 00:14:44'),
(73, 2, 'Payment approved! Next due date: 2025-04-10', 'payment', 'read', '2025-03-10 00:59:42');

-- --------------------------------------------------------

--
-- Table structure for table `pending_equipment`
--

CREATE TABLE `pending_equipment` (
  `id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `req_userid` int(11) NOT NULL,
  `requested_qty` int(11) NOT NULL,
  `requested_date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `approved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_equipment`
--

INSERT INTO `pending_equipment` (`id`, `e_id`, `req_userid`, `requested_qty`, `requested_date`, `status`, `approved_date`) VALUES
(1, 1, 2, 0, '2024-12-18', 'rejected', '2025-02-28 19:05:13'),
(2, 2, 13, 2, '2025-02-17', 'complete', '2025-02-17 11:51:26'),
(3, 1, 2, 1, '2025-03-01', 'complete', '2025-03-06 10:28:13'),
(4, 3, 2, 2, '2025-03-01', 'rejected', NULL),
(5, 3, 2, 2, '2025-03-06', 'rejected', NULL),
(6, 2, 2, 1, '2025-03-08', 'complete', '2025-03-08 10:48:40'),
(7, 2, 2, 1, '2025-03-09', 'complete', '2025-03-08 19:53:49'),
(8, 2, 2, 1, '2025-03-09', 'complete', '2025-03-08 19:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `pending_payment`
--

CREATE TABLE `pending_payment` (
  `id` int(11) NOT NULL,
  `req_userid` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `requested_date` date NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `approved_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_payment`
--

INSERT INTO `pending_payment` (`id`, `req_userid`, `amount_paid`, `requested_date`, `status`, `approved_date`) VALUES
(1, 2, 1000.00, '2025-02-06', 'Approved', '2025-02-22'),
(2, 13, 1000.00, '2025-02-08', 'Approved', '2025-02-22'),
(3, 2, 1000.00, '2025-03-09', 'Approved', '2025-03-08'),
(6, 2, 1000.00, '2025-03-10', 'Approved', '2025-03-09');

-- --------------------------------------------------------

--
-- Table structure for table `pending_product`
--

CREATE TABLE `pending_product` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `req_userid` int(11) NOT NULL,
  `requested_qty` int(11) NOT NULL,
  `requested_date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_product`
--

INSERT INTO `pending_product` (`id`, `p_id`, `req_userid`, `requested_qty`, `requested_date`, `status`, `approved_date`) VALUES
(1, 1, 2, 0, '2024-12-04', 'complete', '2025-03-09'),
(2, 1, 2, 2, '2025-02-15', 'complete', '2025-03-09'),
(3, 2, 2, 5, '2025-02-16', 'complete', '2025-03-08'),
(4, 3, 13, 1, '2025-02-17', 'complete', '2025-02-26'),
(5, 2, 2, 1, '2025-02-17', 'complete', '2025-03-08'),
(6, 2, 2, 1, '2025-02-23', 'complete', '2025-03-08'),
(7, 2, 13, 1, '2025-02-23', 'complete', '2025-03-08'),
(8, 2, 13, 1, '2025-02-23', 'complete', '2025-03-08'),
(9, 3, 13, 1, '2025-02-23', 'complete', '2025-02-26'),
(10, 10, 2, 2, '2025-02-23', 'complete', '2025-02-24'),
(11, 1, 13, 1, '2025-02-24', 'complete', '2025-03-09'),
(12, 1, 2, 1, '2025-02-24', 'complete', '2025-03-09'),
(13, 3, 2, 1, '2025-02-24', 'complete', '2025-02-26'),
(14, 4, 2, 1, '2025-02-24', 'rejected', '2025-02-24'),
(15, 1, 2, 1, '2025-02-26', 'complete', '2025-03-09'),
(16, 2, 13, 1, '2025-02-26', 'complete', '2025-03-08'),
(17, 3, 2, 1, '2025-02-26', 'complete', '2025-02-26'),
(18, 4, 13, 1, '2025-02-26', 'rejected', '0000-00-00'),
(19, 2, 2, 2, '2025-03-01', 'complete', '2025-03-08'),
(20, 1, 2, 1, '2025-03-01', 'complete', '2025-03-09'),
(21, 2, 2, 2, '2025-03-06', 'complete', '2025-03-08'),
(22, 2, 2, 1, '2025-03-09', 'complete', '2025-03-08'),
(23, 8, 2, 1, '2025-03-09', 'rejected', '2025-03-09'),
(24, 1, 2, 1, '2025-03-09', 'complete', '2025-03-09');

-- --------------------------------------------------------

--
-- Table structure for table `pending_rental`
--

CREATE TABLE `pending_rental` (
  `r_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requested_qty` int(11) NOT NULL,
  `requested_date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `approved_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_rental`
--

INSERT INTO `pending_rental` (`r_id`, `e_id`, `user_id`, `requested_qty`, `requested_date`, `status`, `approved_date`) VALUES
(9, 4, 2, 2, '2025-03-01', 'complete', '2025-03-01'),
(10, 4, 2, 2, '2025-03-01', 'complete', '2025-03-01'),
(15, 4, 2, 1, '2025-03-01', 'complete', '2025-03-01'),
(16, 2, 2, 1, '2025-03-09', 'complete', '2025-03-08'),
(17, 2, 2, 1, '2025-03-09', 'complete', '2025-03-08'),
(18, 1, 2, 1, '2025-03-09', 'rejected', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pending_rental_return`
--

CREATE TABLE `pending_rental_return` (
  `id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requested_return_date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `approved_return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_rental_return`
--

INSERT INTO `pending_rental_return` (`id`, `e_id`, `user_id`, `requested_return_date`, `status`, `approved_return_date`) VALUES
(1, 1, 2, '2025-01-26', 'complete', '2025-02-19'),
(2, 4, 2, '2025-03-01', 'complete', '2025-03-01'),
(3, 4, 2, '2025-03-01', 'complete', '2025-03-01'),
(4, 4, 2, '2025-03-01', 'complete', '2025-03-01'),
(5, 4, 2, '2025-03-01', 'complete', '2025-03-01'),
(6, 4, 2, '2025-03-01', 'complete', '2025-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `MRP` float NOT NULL,
  `discounted_amt` int(11) NOT NULL,
  `available_qty` tinyint(1) NOT NULL,
  `prod_images` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `MRP`, `discounted_amt`, `available_qty`, `prod_images`) VALUES
(1, 'Whey Protein', 1800, 1500, 88, 'images/whey_protein.jpg'),
(2, 'Mass Gainer', 2200, 1900, 81, 'images/mass_gainer.jpg'),
(3, 'BCAA Powder', 1200, 1000, 86, 'images/bcaa_powder.jpg'),
(4, 'Creatine Monohydrate', 950, 800, 89, 'images/creatine_monohydrate.jpg'),
(5, 'Fish Oil Omega-3', 600, 500, 90, 'images/fish_oil.jpg'),
(6, 'Multivitamin Tablets', 700, 600, 90, 'images/multivitamin_tablets.jpg'),
(7, 'Pre-Workout Supplement', 1400, 1200, 90, 'images/pre_workout.jpg'),
(8, 'Fat Burner', 1500, 1300, 87, 'images/fat_burner.jpg'),
(9, 'Vitamin D3 Supplement', 500, 400, 90, 'images/vitamin_d3.jpg'),
(10, 'Zinc Supplement', 350, 300, 86, 'images/zinc_supplement.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_payment`
--

CREATE TABLE `product_payment` (
  `pay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` text NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rental_equipments`
--

CREATE TABLE `rental_equipments` (
  `rental_id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `available_rental_qty` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_equipments`
--

INSERT INTO `rental_equipments` (`rental_id`, `equipment_id`, `available_rental_qty`) VALUES
(1, 1, 30),
(2, 2, 28),
(3, 3, 30),
(4, 4, 31),
(5, 11, 30),
(6, 10, 30),
(7, 11, 30);

-- --------------------------------------------------------

--
-- Table structure for table `rental_payment`
--

CREATE TABLE `rental_payment` (
  `pay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` text NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_payment`
--

INSERT INTO `rental_payment` (`pay_id`, `user_id`, `e_id`, `amount`, `status`) VALUES
(1, 2, 0, 720, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `rental_transactions`
--

CREATE TABLE `rental_transactions` (
  `transaction_id` int(11) NOT NULL,
  `rental_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rental_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_returned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_transactions`
--

INSERT INTO `rental_transactions` (`transaction_id`, `rental_id`, `user_id`, `rental_date`, `due_date`, `is_returned`) VALUES
(6, 4, 2, '2025-03-01', '2025-03-26', 1),
(11, 4, 2, '2025-03-15', '2025-04-26', 1),
(12, 2, 2, '2025-03-08', '2025-04-08', 0),
(13, 2, 2, '2025-03-08', '2025-04-08', 0),
(14, 1, 2, '2025-03-10', '2025-03-28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_payment`
--

CREATE TABLE `system_payment` (
  `pay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` text NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_payment`
--

INSERT INTO `system_payment` (`pay_id`, `user_id`, `amount`, `status`) VALUES
(1, 2, 1000, 'complete'),
(2, 2, 1000, 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `id` int(11) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `membership` varchar(30) NOT NULL,
  `membership_expiry_date` date NOT NULL,
  `bmi` float NOT NULL,
  `payment_due` decimal(10,2) DEFAULT 1000.00,
  `payment_due_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Unpaid',
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`id`, `phone_no`, `membership`, `membership_expiry_date`, `bmi`, `payment_due`, `payment_due_date`, `status`, `instructor_id`) VALUES
(2, '9822395676', 'Silver', '2026-03-09', 21.64, 0.00, NULL, 'paid', 6),
(13, '9822395600', 'Gold', '2025-02-17', 0, 0.00, '2025-03-08', 'paid', 1),
(14, '', '', '0000-00-00', 0, 1000.00, NULL, 'Unpaid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_login`
--

CREATE TABLE `users_login` (
  `id` int(30) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(16) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_login`
--

INSERT INTO `users_login` (`id`, `username`, `password`, `email`) VALUES
(2, 'neeru', 'Neeru@123', 'neerupraz123@gmail.com'),
(13, 'aashika', 'AA123', 'khatiwadaaashika@gmail.com'),
(14, 'sita', 'sita22', 'sita2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users_workout`
--

CREATE TABLE `users_workout` (
  `S.No.` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workout_id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `progress` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_workout`
--

INSERT INTO `users_workout` (`S.No.`, `user_id`, `workout_id`, `status`, `progress`, `date`) VALUES
(15, 13, 1, 'active', '', '2025-01-25'),
(18, 2, 2, 'active', '', '2025-02-22'),
(19, 2, 1, 'active', '', '2025-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `user_workout_tracking`
--

CREATE TABLE `user_workout_tracking` (
  `tracking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `workout_id` int(11) DEFAULT NULL,
  `completed_dates` text DEFAULT NULL,
  `times_completed` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_workout_tracking`
--

INSERT INTO `user_workout_tracking` (`tracking_id`, `user_id`, `workout_id`, `completed_dates`, `times_completed`) VALUES
(1, 2, 2, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `workout_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `frequency` varchar(500) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`workout_id`, `name`, `description`, `frequency`, `image_url`) VALUES
(1, 'Beginner Cardio', 'Low-intensity cardio workout for beginners', '3 times a week', 'images/beginner_cardio.jpg'),
(2, 'HIIT Cardio', 'High-intensity interval training for advanced cardio', '2 times a week', 'images/hit_cardio.jpg'),
(3, 'Strength Training - Upper Body', 'Upper body strength exercises focusing on arms, shoulders, and chest', '2 times a week', 'images/upper_body_strength.jpg'),
(4, 'Strength Training - Lower Body', 'Lower body strength exercises targeting legs and glutes', '2 times a week', 'images/lower_body_strength.jpg'),
(5, 'Yoga - Flexibility', 'A gentle yoga routine to improve flexibility', 'Daily', 'images/yoga_flexibility.jpg'),
(6, 'Core Workout', 'Intense core exercises to strengthen abs and lower back', '3 times a week', 'images/core_workout.jpg'),
(7, 'Full Body Workout', 'A balanced workout targeting all major muscle groups', '3 times a week', 'images/full_body_workout.jpg'),
(8, 'Pilates', 'Low-impact exercises to build strength and stability', 'Daily', 'images/pilates.jpg'),
(9, 'Weightlifting - Advanced', 'High-intensity weightlifting program for advanced users', '4 times a week', 'images/weightlifting_advanced.jpg'),
(10, 'Zumba Dance', 'Dance-based cardio for fun and fitness', '3 times a week', 'images/zumba.jpg'),
(11, 'Boxing Basics', 'Boxing techniques for beginners', '2 times a week', 'images/boxing_basics.jpg'),
(12, 'Balance and Stability', 'Exercises to improve balance and coordination', '3 times a week', 'images/balance_stability.jpg'),
(13, 'Stretch and Recovery', 'Gentle stretches to promote recovery and reduce soreness', 'Daily', 'images/stretch_recovery.jpg'),
(14, 'Running Endurance', 'Outdoor or treadmill running to build endurance', '4 times a week', 'images/running_endurance.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmi_recommendations`
--
ALTER TABLE `bmi_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workout_id` (`workout_id`);

--
-- Indexes for table `eqp_payment`
--
ALTER TABLE `eqp_payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `e_id` (`e_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `instructor_request`
--
ALTER TABLE `instructor_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `membership_requests`
--
ALTER TABLE `membership_requests`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `memb_payment`
--
ALTER TABLE `memb_payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pending_equipment`
--
ALTER TABLE `pending_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `e_id` (`e_id`,`req_userid`),
  ADD KEY `req_userid` (`req_userid`);

--
-- Indexes for table `pending_payment`
--
ALTER TABLE `pending_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `req_userid` (`req_userid`);

--
-- Indexes for table `pending_product`
--
ALTER TABLE `pending_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`,`req_userid`),
  ADD KEY `req_userid` (`req_userid`);

--
-- Indexes for table `pending_rental`
--
ALTER TABLE `pending_rental`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `e_id` (`e_id`,`user_id`);

--
-- Indexes for table `pending_rental_return`
--
ALTER TABLE `pending_rental_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `e_id` (`e_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_payment`
--
ALTER TABLE `product_payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `p_id` (`p_id`);

--
-- Indexes for table `rental_equipments`
--
ALTER TABLE `rental_equipments`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `rental_payment`
--
ALTER TABLE `rental_payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `rental_transactions`
--
ALTER TABLE `rental_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `rental_id` (`rental_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_payment`
--
ALTER TABLE `system_payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `fk_instructor` (`instructor_id`);

--
-- Indexes for table `users_login`
--
ALTER TABLE `users_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq_usern` (`username`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Indexes for table `users_workout`
--
ALTER TABLE `users_workout`
  ADD PRIMARY KEY (`S.No.`),
  ADD KEY `id` (`user_id`),
  ADD KEY `workout_id` (`workout_id`);

--
-- Indexes for table `user_workout_tracking`
--
ALTER TABLE `user_workout_tracking`
  ADD PRIMARY KEY (`tracking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `workout_id` (`workout_id`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`workout_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmi_recommendations`
--
ALTER TABLE `bmi_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `eqp_payment`
--
ALTER TABLE `eqp_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `instructor_request`
--
ALTER TABLE `instructor_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `membership_requests`
--
ALTER TABLE `membership_requests`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `memb_payment`
--
ALTER TABLE `memb_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `pending_equipment`
--
ALTER TABLE `pending_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pending_payment`
--
ALTER TABLE `pending_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pending_product`
--
ALTER TABLE `pending_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pending_rental`
--
ALTER TABLE `pending_rental`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pending_rental_return`
--
ALTER TABLE `pending_rental_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `product_payment`
--
ALTER TABLE `product_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rental_equipments`
--
ALTER TABLE `rental_equipments`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rental_payment`
--
ALTER TABLE `rental_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_transactions`
--
ALTER TABLE `rental_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_payment`
--
ALTER TABLE `system_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_login`
--
ALTER TABLE `users_login`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users_workout`
--
ALTER TABLE `users_workout`
  MODIFY `S.No.` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_workout_tracking`
--
ALTER TABLE `user_workout_tracking`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `workout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi_recommendations`
--
ALTER TABLE `bmi_recommendations`
  ADD CONSTRAINT `bmi_recommendations_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`workout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eqp_payment`
--
ALTER TABLE `eqp_payment`
  ADD CONSTRAINT `eqp_payment_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instructor_request`
--
ALTER TABLE `instructor_request`
  ADD CONSTRAINT `instructor_request_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `instructor_request_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pending_equipment`
--
ALTER TABLE `pending_equipment`
  ADD CONSTRAINT `pending_equipment_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pending_equipment_ibfk_2` FOREIGN KEY (`req_userid`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_payment`
--
ALTER TABLE `pending_payment`
  ADD CONSTRAINT `pending_payment_ibfk_1` FOREIGN KEY (`req_userid`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_product`
--
ALTER TABLE `pending_product`
  ADD CONSTRAINT `pending_product_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pending_product_ibfk_2` FOREIGN KEY (`req_userid`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_rental`
--
ALTER TABLE `pending_rental`
  ADD CONSTRAINT `pending_rental_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `rental_equipments` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pending_rental_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_rental_return`
--
ALTER TABLE `pending_rental_return`
  ADD CONSTRAINT `pending_rental_return_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `rental_equipments` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pending_rental_return_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_payment`
--
ALTER TABLE `product_payment`
  ADD CONSTRAINT `product_payment_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental_equipments`
--
ALTER TABLE `rental_equipments`
  ADD CONSTRAINT `rental_equipments_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`);

--
-- Constraints for table `rental_transactions`
--
ALTER TABLE `rental_transactions`
  ADD CONSTRAINT `rental_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`);

--
-- Constraints for table `users_info`
--
ALTER TABLE `users_info`
  ADD CONSTRAINT `fk_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `users_workout`
--
ALTER TABLE `users_workout`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `workout_id` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`workout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_workout_tracking`
--
ALTER TABLE `user_workout_tracking`
  ADD CONSTRAINT `user_workout_tracking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`id`),
  ADD CONSTRAINT `user_workout_tracking_ibfk_2` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`workout_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
