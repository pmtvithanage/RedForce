-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 09:45 AM
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
-- Database: `redforce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(500) NOT NULL,
  `target_roles` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `description`, `image_path`, `target_roles`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(2, 'vvcxvcxvxv', 'dfvdvdvddvdvfdvfdvfdvfvvdfvxcvxvfdvfdv gfdfddfdfd', '/uploads/advertisements/ad_1770489624_69878718e7c23.jpg', 'premise officer', 1, 'inactive', '2026-02-07 18:40:24', '2026-02-08 15:12:40'),
(3, 'dgdffdg dfgdf dfdfdg dfgfdg', 'dsvkjsdnjskjsdd fsfdsfdsfdsfsfsdgfdgfdsgsfdgfdgdgdgfdgfdgfdgfdgfdgfdf fdgdgdsfgfds dfgdfgdf fdgdfgdfs fdgfdg gfdgfd fdgdfg fdgfdg dgdf gfdgfd dfgdf fdgegtesg fdgdfg fdsgfd gtgtegfd fdgfd gdfgfdgdfsgfdgfd dgdffdgdg', '/uploads/advertisements/ad_1770490457_69878a5928cf8.png', 'premise officer,caretaker,mobile rider,supervisor', 1, 'active', '2026-02-07 18:54:17', '2026-02-08 15:12:32'),
(5, 'GVH', 'GC FV', '/uploads/advertisements/ad_1770594780_698921dca34fa.jpg', 'all', 1, 'active', '2026-02-08 23:53:00', '2026-02-08 23:53:00'),
(6, 'dsvdvd', 'sdvssvsvsd', '/uploads/advertisements/ad_1770597500_69892c7c7439b.jpeg', 'all', 1, 'active', '2026-02-09 00:38:20', '2026-03-31 08:22:27'),
(7, 'dsafasg', 'ergresgagg', '/uploads/advertisements/ad_1770597603_69892ce36f1a2.png', 'all', 33, 'inactive', '2026-02-09 00:40:03', '2026-02-16 02:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `officer_id` varchar(50) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `status` enum('present','absent') DEFAULT 'present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caretaker_notes`
--

CREATE TABLE `caretaker_notes` (
  `id` int(11) NOT NULL,
  `caretaker_id` int(11) NOT NULL COMMENT 'Foreign key to Users table',
  `title` varchar(255) NOT NULL,
  `note_content` text NOT NULL,
  `category` enum('General','Important','Reminder','Observation') NOT NULL DEFAULT 'General',
  `priority` enum('Low','Medium','High') NOT NULL DEFAULT 'Medium',
  `reminder_date` date DEFAULT NULL COMMENT 'Optional reminder date',
  `is_pinned` tinyint(1) DEFAULT 0 COMMENT '1 = pinned to top',
  `is_completed` tinyint(1) DEFAULT 0 COMMENT '1 = completed reminder',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caretaker_notes`
--

INSERT INTO `caretaker_notes` (`id`, `caretaker_id`, `title`, `note_content`, `category`, `priority`, `reminder_date`, `is_pinned`, `is_completed`, `created_at`, `updated_at`) VALUES
(1, 25, 'Check main gate lock', 'Need to inspect the main gate lock mechanism as it was sticking yesterday. May need lubrication or replacement.', 'Observation', 'High', '2026-02-09', 0, 1, '2026-02-09 08:13:55', '2026-02-09 13:39:33'),
(2, 25, 'Submit monthly report', 'Monthly security report due by end of week. Include all incident logs and patrol summaries.', 'Reminder', 'Medium', '2026-02-12', 0, 1, '2026-02-09 08:13:55', '2026-02-16 02:58:59'),
(3, 25, 'Equipment inventory', 'Flashlight batteries running low. Radio channel 3 has static. Need to request new uniform.', 'General', 'Low', NULL, 0, 0, '2026-02-09 08:13:55', '2026-02-09 08:13:55'),
(4, 25, 'dfddfdfdfv', 'fvfvfddfvfdvfdvdvdvfdvdvfvd', 'General', 'Medium', '2026-02-10', 0, 1, '2026-02-09 08:15:35', '2026-02-10 11:04:07'),
(7, 54, 'xz z', 'z z xz cx cxz xzc cx dc vdfnhmjmkj,lk,', 'Important', 'Medium', '2026-02-13', 0, 1, '2026-02-12 16:57:01', '2026-02-13 01:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `caretaker_site_assignments`
--

CREATE TABLE `caretaker_site_assignments` (
  `id` int(11) NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `caretaker_id` int(11) NOT NULL,
  `assignment_start` date NOT NULL,
  `assignment_end` date DEFAULT NULL,
  `status` enum('Active','Completed','Cancelled') DEFAULT 'Active',
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_at` datetime DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `caretaker_site_assignments`
--

INSERT INTO `caretaker_site_assignments` (`id`, `site_id`, `caretaker_id`, `assignment_start`, `assignment_end`, `status`, `assigned_by`, `assigned_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 54, '2026-02-04', '2026-02-04', 'Completed', 1, '2026-02-04 16:05:38', NULL, '2026-02-04 10:35:38', '2026-02-04 10:42:31'),
(2, 5, 25, '2026-02-04', NULL, 'Active', 1, '2026-02-04 16:12:47', NULL, '2026-02-04 10:42:47', '2026-02-04 10:42:47'),
(3, 5, 27, '2026-02-04', NULL, 'Active', 1, '2026-02-04 16:18:02', NULL, '2026-02-04 10:48:02', '2026-02-04 10:48:02'),
(4, 28, 54, '2026-04-06', NULL, 'Active', 1, '2026-04-06 10:29:46', NULL, '2026-04-06 04:59:46', '2026-04-06 04:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `care_taker`
--

CREATE TABLE `care_taker` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `caretakerID` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `NIC` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `employment_status` enum('Active','On Leave','Terminated','Suspended') DEFAULT 'Active',
  `rank` enum('Junior','Senior') DEFAULT 'Junior',
  `rating` decimal(3,2) DEFAULT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `shift_pattern` varchar(50) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `care_taker`
--

INSERT INTO `care_taker` (`id`, `userID`, `caretakerID`, `date_of_birth`, `NIC`, `gender`, `address`, `district`, `city`, `hire_date`, `employment_status`, `rank`, `rating`, `shift_pattern`, `application_id`, `created_at`, `updated_at`) VALUES
(1, 25, 'CT001', '2025-12-18', '199612782449', 'Female', 'Kumudu, Addarawatta, Kodagoda,', 'Mullaitivu', 'Kokuthoduvai', '2025-12-31', 'Active', 'Junior', NULL, NULL, NULL, '2025-12-31 03:35:01', '2025-12-31 03:35:01'),
(2, 27, 'CT002', '2026-01-16', '112233445566', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Mullaitivu', 'Alampil East', '2026-01-02', 'Active', 'Junior', NULL, NULL, NULL, '2026-01-02 04:20:24', '2026-01-02 04:20:24'),
(7, 54, 'CT003', '2026-02-18', '1122334455', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Thirukkovil', '2026-02-01', 'Active', 'Junior', NULL, NULL, NULL, '2026-02-01 15:03:05', '2026-02-01 15:03:05');

-- --------------------------------------------------------

--
-- Stand-in structure for view `care_taker_full_details`
-- (See below for the actual view)
--
CREATE TABLE `care_taker_full_details` (
`care_taker_id` int(11)
,`caretakerID` varchar(50)
,`date_of_birth` date
,`NIC` varchar(20)
,`gender` enum('Male','Female','Other')
,`address` text
,`district` varchar(50)
,`city` varchar(50)
,`hire_date` date
,`employment_status` enum('Active','On Leave','Terminated','Suspended')
,`rank` enum('Junior','Senior')
,`rating` decimal(3,2)
,`shift_pattern` varchar(50)
,`application_id` int(11)
,`caretaker_record_created` timestamp
,`caretaker_record_updated` timestamp
,`user_id` int(11)
,`user_identifier` varchar(50)
,`name` varchar(255)
,`email` varchar(255)
,`role` enum('admin','supervisor','premise officer','mobile rider','client','caretaker')
,`phone_number` varchar(50)
,`profile_image` varchar(255)
,`user_status` enum('active','inactive','suspended')
,`user_account_created` timestamp
,`user_account_updated` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `contact_person_name`) VALUES
(1, 10, 'P.M.T Vithanage'),
(2, 28, 'P.M.T Vithanage'),
(5, 32, 'P.M.T Vithanage'),
(8, 40, 'P.M.T Vithanage'),
(9, 41, 'P.M.T Vithanage'),
(11, 46, 'cxfddsgfncgnhg'),
(12, 56, 'kumara');

-- --------------------------------------------------------

--
-- Table structure for table `client_requests`
--

CREATE TABLE `client_requests` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `legal_company_name` varchar(255) DEFAULT NULL COMMENT 'Legal/Registered company name as on registration documents',
  `company_type` varchar(50) DEFAULT NULL COMMENT 'Company type: LLC, Inc., Partnership, Sole Proprietor, PLC, etc.',
  `business_registration_number` varchar(100) DEFAULT NULL COMMENT 'VAT Number, GSTIN, EIN, Company Number, CIF/NIF, etc.',
  `registered_address` text DEFAULT NULL COMMENT 'Official legal/registered business address',
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `logo_path` varchar(500) DEFAULT NULL,
  `business_document` varchar(255) DEFAULT NULL COMMENT 'Business registration/incorporation certificate filename',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_requests`
--

INSERT INTO `client_requests` (`id`, `company_name`, `legal_company_name`, `company_type`, `business_registration_number`, `registered_address`, `email`, `phone_number`, `contact_person_name`, `logo_path`, `business_document`, `created_at`, `status`, `approved_by`, `approved_at`, `client_id`) VALUES
(1, 'People&apos;s Bank', NULL, NULL, NULL, NULL, 'peoplesbank@gmail.com', '0113740740', 'P.M.T Vithanage', '1766304642_peoples-bank.png', NULL, '2025-12-21 08:10:42', 'approved', 1, '2025-12-21 08:26:28', 10),
(3, 'Company Example', NULL, NULL, NULL, NULL, 'company@gmail.com', '0775623123', 'P.M.T Vithanage', '1767335768_c1.jpg', NULL, '2026-01-02 06:36:08', 'approved', 1, '2026-01-02 06:37:23', 28),
(4, 'Company Example 2', NULL, NULL, NULL, NULL, 'abc@gmail.com', '0776523874', 'P.M.T Vithanage', '1767335820_c2.jpg', NULL, '2026-01-02 06:37:00', 'approved', 1, '2026-01-21 14:18:58', 41),
(5, 'Example', NULL, NULL, NULL, NULL, 'manujakaushika@gmail.com', '0912287654', 'P.M.T Vithanage', '1767756375_Peoples-Bank-Galkiriyagama.jpg', NULL, '2026-01-07 03:26:15', 'approved', 1, '2026-01-07 04:06:10', 32),
(6, 'Example Company', NULL, NULL, NULL, NULL, 'genz48155@gmail.com', '0912287654', 'P.M.T Vithanage', '1768998330_c3.jpg', NULL, '2026-01-21 12:25:30', 'approved', 1, '2026-01-21 12:38:45', 40),
(7, 'pasan', NULL, NULL, NULL, NULL, 'pmtvithanage@gmail.com', '0764465234', 'cxfddsgfncgnhg', '1769943484_s1.jpeg', NULL, '2026-02-01 10:58:04', 'approved', 1, '2026-02-01 11:04:23', 46),
(8, 'UCSC', 'UCSC', 'Other', 'VS22134332', 'kohuwela', 'UCSC@gmail.com', '0921133456', 'kumara', '1770443070_c1.jpg', '1770443070_Activity 01.png', '2026-02-07 05:44:30', 'approved', 1, '2026-02-07 05:45:06', 56);

-- --------------------------------------------------------

--
-- Table structure for table `conversation_participants`
--

CREATE TABLE `conversation_participants` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_requests`
--

CREATE TABLE `equipment_requests` (
  `id` int(11) NOT NULL,
  `caretaker_id` int(11) NOT NULL,
  `equipment_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `estimated_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `total_cost` decimal(10,2) GENERATED ALWAYS AS (`quantity` * ifnull(`actual_cost`,`estimated_cost`)) STORED,
  `reason` text NOT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `status` enum('Pending','Approved','Rejected','Supervisor Approved','Client Approved','Completed') DEFAULT 'Pending',
  `requested_date` date NOT NULL,
  `approved_date` date DEFAULT NULL,
  `supervisor_notes` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `supervisor_approved_by` int(11) DEFAULT NULL,
  `supervisor_approved_date` datetime DEFAULT NULL,
  `client_approved_by` int(11) DEFAULT NULL,
  `client_approved_date` datetime DEFAULT NULL,
  `client_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_requests`
--

INSERT INTO `equipment_requests` (`id`, `caretaker_id`, `equipment_name`, `quantity`, `estimated_cost`, `actual_cost`, `reason`, `priority`, `status`, `requested_date`, `approved_date`, `supervisor_notes`, `approved_by`, `created_at`, `updated_at`, `supervisor_approved_by`, `supervisor_approved_date`, `client_approved_by`, `client_approved_date`, `client_notes`, `rejection_reason`) VALUES
(1, 1, 'Flashlight', 2, 500.00, NULL, 'Previous flashlights broken during night patrol', 'High', 'Pending', '2025-11-26', NULL, NULL, NULL, '2026-02-04 10:00:18', '2026-02-04 10:00:18', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'Uniform Shirt', 1, 2500.00, 2300.00, 'Current uniform worn out', 'Medium', 'Approved', '2025-11-25', '2025-11-25', 'Approved. Purchased at discount.', NULL, '2026-02-04 10:00:18', '2026-02-04 10:00:18', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 'Radio', 1, 3000.00, NULL, 'Backup radio needed', 'Low', 'Rejected', '2025-11-24', NULL, NULL, NULL, '2026-02-04 10:00:18', '2026-02-04 10:00:18', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 9, 'Gloves', 4, 234.00, NULL, 'f4wwffwfwefwfeeeeffvgvd\r\nerreregreregregregre', 'Low', 'Approved', '2026-02-04', '2026-02-04', '', 41, '2026-02-04 10:06:16', '2026-02-04 15:37:35', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 9, 'Flashlight', 1, 324.00, NULL, 'ffdfsfffffffffff', 'Medium', 'Pending', '2026-02-04', NULL, NULL, NULL, '2026-02-04 10:09:32', '2026-02-04 10:09:32', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 54, 'Uniform', 1, 123.00, NULL, 'dvvfdvfdvdvdvdvdfvfdvfdvfdv', 'Medium', 'Pending', '2026-02-04', NULL, NULL, NULL, '2026-02-04 15:46:20', '2026-02-04 15:46:20', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 25, 'Uniform', 1, 4455667.00, NULL, 'hjjnn nnn  n nb n nn', 'Medium', 'Approved', '2026-02-04', '2026-02-05', '', 17, '2026-02-04 16:28:25', '2026-02-05 04:59:27', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 25, 'Helmet', 1, 10000.00, NULL, 'donnewfewfsrgdgrggr rwgrgregegeggd', 'Medium', 'Approved', '2026-02-05', '2026-02-05', '', 17, '2026-02-05 05:06:34', '2026-02-05 05:43:48', NULL, NULL, NULL, NULL, 'dvfdvdvfvd', NULL),
(9, 25, 'First Aid Kit', 1, 12355.00, NULL, 'ewfwlkjhiof wdhfojwfwfrfrw', 'Medium', 'Rejected', '2026-02-05', '2026-02-05', 'fsvffvfdfdv', 17, '2026-02-05 05:08:23', '2026-02-05 05:08:51', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 25, 'Raincoat', 1, 10000.00, NULL, 'eavvdsvds sdvsvasvs sdvsdvasv', 'Medium', 'Approved', '2026-02-05', '2026-02-05', '', 17, '2026-02-05 14:19:54', '2026-02-05 14:28:18', NULL, NULL, NULL, NULL, 'cxdcsdcdcds', NULL),
(11, 25, 'Radio', 2, 1234.00, NULL, 'dscssdcewfwfwfwfewfewfewrfefew', 'Medium', 'Supervisor Approved', '2026-02-09', '2026-02-17', '', 17, '2026-02-09 09:03:30', '2026-02-17 11:47:25', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `officer_name` varchar(100) NOT NULL,
  `officer_role` varchar(100) NOT NULL,
  `property_site` varchar(50) DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to sites table',
  `incident_type` varchar(50) NOT NULL,
  `incident_date` date NOT NULL,
  `status` varchar(50) DEFAULT 'Open',
  `incident_time` time NOT NULL,
  `incident_description` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium' COMMENT 'Incident priority level',
  `additional_details` varchar(255) DEFAULT NULL,
  `people_involved` text DEFAULT NULL COMMENT 'Names of people involved',
  `latitude` decimal(10,8) DEFAULT NULL COMMENT 'Incident location latitude',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT 'Incident location longitude',
  `media_files` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `user_id`, `officer_name`, `officer_role`, `property_site`, `site_id`, `incident_type`, `incident_date`, `status`, `incident_time`, `incident_description`, `action_taken`, `severity`, `priority`, `additional_details`, `people_involved`, `latitude`, `longitude`, `media_files`, `created_at`, `updated_at`) VALUES
(7, 37, 'Kulathunga', 'mobile rider', '12', 12, 'Unauthorized Access', '2026-01-26', 'In Progress', '17:14:00', 'fvdvdsvfd', '', 'Medium', 'Medium', NULL, '', 5.93698990, 80.57852060, '697753bd0663f_peoples-bank.png', '2026-01-26 11:45:01', '2026-01-27 10:43:14'),
(8, 37, 'Kulathunga', 'mobile rider', '13', 13, 'Security Breach', '2026-01-26', 'In Progress', '18:12:00', 'fvdvdvfdvfdvfdvdfv', '', 'Medium', 'Medium', NULL, '', 5.94290210, 80.58218150, '6977617529767_WhatsApp Image 2026-01-21 at 9.46.53 AM.jpeg', '2026-01-26 12:43:33', '2026-01-27 03:13:48'),
(9, 37, 'Kulathunga', 'mobile rider', '10', 10, 'Security Breach', '2026-01-26', 'Resolved', '18:13:00', 'dscdscdsc', 'wcew', 'Medium', 'Medium', NULL, '', 5.93809210, 80.57613440, '697761c023522_c1.jpg,697761c023550_c2.jpg,697761c023565_c3.jpg', '2026-01-26 12:44:48', '2026-01-26 19:18:08'),
(10, 29, 'Gajanayake', 'mobile rider', '4', 4, 'Theft', '2026-01-26', 'In Progress', '23:46:00', 'gnhngn', '', 'Medium', 'Medium', NULL, '', 6.91735540, 79.85503250, NULL, '2026-01-26 18:17:10', '2026-01-27 05:10:54'),
(11, 24, 'P.M.T Vithanage', 'mobile rider', '8', 8, 'Unauthorized Access', '2026-01-26', 'Resolved', '23:55:00', 'rrrv', 'evevevevever', 'Medium', 'Medium', NULL, '', 6.06707280, 80.22607360, '6977b1a8b685c_WhatsApp Image 2026-01-21 at 9.46.53 AM.jpeg', '2026-01-26 18:25:44', '2026-01-26 19:08:00'),
(12, 17, 'Sarath Madushanka', 'supervisor', '5', 5, 'Equipment Malfunction', '2026-01-27', 'Resolved', '15:13:00', 'dfvdfvfdv', '', 'Critical', 'Critical', NULL, '', 6.92233530, 79.86215110, NULL, '2026-01-27 09:43:37', '2026-01-27 10:21:01'),
(13, 17, 'Sarath Madushanka', 'supervisor', '5', 5, 'Security Breach', '2026-01-27', 'Resolved', '21:36:00', 'evvevev', 'wefwe', 'Medium', 'Medium', NULL, '', 6.92233530, 79.86215110, NULL, '2026-01-27 16:07:10', '2026-01-29 05:13:22'),
(14, 17, 'Sarath Madushanka', 'supervisor', '5', 5, 'Security Breach', '2026-01-27', 'Resolved', '21:42:00', 'vjc', 'jhhjb', 'Low', 'Low', NULL, '', 6.92233530, 79.86215110, NULL, '2026-01-27 16:12:32', '2026-01-28 04:46:04'),
(15, 17, 'Sarath Madushanka', 'supervisor', '5', 5, 'Security Breach', '2026-02-01', 'Resolved', '19:24:00', 'hvhvh', '', 'Critical', 'Critical', NULL, '', 6.92233530, 79.86215110, '697f5b4dce15f_Activity 01.png,697f5b4dce1b0_Activity 02.png,697f5b4dce1dc_c1.jpg,697f5b4dce201_c2.jpg,697f5b4dce224_c3.jpg,697f5b4dce246_Copy of Non-inverting Op-amp.png', '2026-02-01 13:55:25', '2026-02-02 04:24:24'),
(16, 17, 'Sarath Madushanka', 'supervisor', '5', 5, 'Fire Alarm', '2026-02-06', 'Pending', '11:49:00', 'edeewdadadad', '', 'Low', 'Low', NULL, '', 6.92233530, 79.86215110, '698588186878f_c1.jpg', '2026-02-06 06:20:08', '2026-02-06 06:20:08');

-- --------------------------------------------------------

--
-- Table structure for table `incident_reviews`
--

CREATE TABLE `incident_reviews` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL COMMENT 'Foreign key to incident_reports table',
  `user_id` int(11) NOT NULL COMMENT 'User who added the review',
  `reviewer_name` varchar(255) NOT NULL COMMENT 'Name of the reviewer',
  `review_type` enum('Update','Action','Comment','Follow-up') DEFAULT 'Comment' COMMENT 'Type of review',
  `review_title` varchar(255) NOT NULL COMMENT 'Brief title for the review',
  `review_details` text NOT NULL COMMENT 'Detailed review content',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reviews`
--

INSERT INTO `incident_reviews` (`id`, `incident_id`, `user_id`, `reviewer_name`, `review_type`, `review_title`, `review_details`, `created_at`, `updated_at`) VALUES
(1, 9, 37, 'Kulathunga', 'Update', 'grggrg', 'efvfvfv', '2026-01-26 16:41:20', '2026-01-26 16:41:20'),
(2, 9, 37, 'Kulathunga', 'Update', 'hgvhg h', 'ghngngng', '2026-01-26 16:45:58', '2026-01-26 16:45:58'),
(3, 9, 37, 'Kulathunga', 'Action', 'wdcdc', 'evfevfev', '2026-01-26 16:51:24', '2026-01-26 16:51:24'),
(4, 8, 37, 'Kulathunga', 'Update', 'grggrg', 'dcdec', '2026-01-26 16:58:06', '2026-01-26 16:58:06'),
(5, 11, 24, 'P.M.T Vithanage', 'Update', 'fvfd', 'ffdvdvfdv', '2026-01-26 18:26:03', '2026-01-26 18:26:03'),
(6, 11, 1, 'System Administrator', 'Action', 'dvdfvfdv', 'dvfdvdfvfdv', '2026-01-26 18:52:07', '2026-01-26 18:52:07'),
(7, 11, 1, 'System Administrator', 'Action', 'evrev', 'eveverve', '2026-01-26 19:08:00', '2026-01-26 19:08:00'),
(8, 10, 1, 'System Administrator', 'Comment', 'ererf', 'evfev', '2026-01-26 19:12:02', '2026-01-26 19:12:02'),
(9, 9, 1, 'System Administrator', 'Action', 'dcwdc', 'wdcwcwc', '2026-01-26 19:17:58', '2026-01-26 19:17:58'),
(10, 9, 1, 'System Administrator', 'Action', 'wwcwdc', 'wewewc', '2026-01-26 19:18:08', '2026-01-26 19:18:08'),
(11, 7, 37, 'Kulathunga', 'Action', 'ewdeww', 'dscdscdsc', '2026-01-27 02:47:13', '2026-01-27 02:47:13'),
(12, 8, 1, 'System Administrator', 'Follow-up', 'hgvhg h', 'efvedv', '2026-01-27 03:13:48', '2026-01-27 03:13:48'),
(13, 10, 1, 'System Administrator', 'Action', 'scdscs', 'scsc', '2026-01-27 05:10:54', '2026-01-27 05:10:54'),
(14, 12, 17, 'Sarath Madushanka', 'Update', 'hvcg', 'hgcg', '2026-01-27 09:53:58', '2026-01-27 09:53:58'),
(15, 12, 17, 'Sarath Madushanka', 'Update', 'hgvhg h', 'hng h', '2026-01-27 09:57:25', '2026-01-27 09:57:25'),
(16, 12, 1, 'System Administrator', 'Update', 'hgvhg h', 'sdcds', '2026-01-27 10:13:42', '2026-01-27 10:13:42'),
(17, 12, 1, 'System Administrator', 'Action', 'd', 'dfdfewew', '2026-01-27 10:21:01', '2026-01-27 10:21:01'),
(18, 7, 1, 'System Administrator', 'Update', 'grggrg', 'ds', '2026-01-27 10:43:14', '2026-01-27 10:43:14'),
(19, 13, 17, 'Sarath Madushanka', 'Update', 'efvev', 'ev', '2026-01-27 16:07:27', '2026-01-27 16:07:27'),
(20, 13, 29, 'Gajanayake', 'Update', 'grggrg', 'efvfev', '2026-01-27 16:08:07', '2026-01-27 16:08:07'),
(21, 14, 29, 'Gajanayake', 'Update', 'gch', 'v', '2026-01-27 16:13:44', '2026-01-27 16:13:44'),
(22, 14, 1, 'System Administrator', 'Update', 'vv', 'gfcf', '2026-01-27 16:14:12', '2026-01-27 16:14:12'),
(23, 14, 1, 'System Administrator', 'Action', 'hj', 'n n  nn', '2026-01-28 04:46:04', '2026-01-28 04:46:04'),
(24, 13, 1, 'System Administrator', 'Update', 'evev', 'evev', '2026-01-29 05:12:55', '2026-01-29 05:12:55'),
(25, 13, 1, 'System Administrator', 'Action', 'wfwfwew', 'eewfewwefwewfe', '2026-01-29 05:13:22', '2026-01-29 05:13:22'),
(26, 15, 29, 'Gajanayake', 'Update', 'ftccftxxr', 'edededed', '2026-02-01 14:10:00', '2026-02-01 14:10:00'),
(27, 15, 17, 'Sarath Madushanka', 'Update', 'eded', 'frfrf', '2026-02-01 14:12:06', '2026-02-01 14:12:06'),
(28, 15, 1, 'System Administrator', 'Update', '33r3', 'efrref', '2026-02-01 14:13:15', '2026-02-01 14:13:15'),
(29, 15, 29, 'Gajanayake', 'Update', '3f33f3f3f3f', '3f3f3f3f', '2026-02-01 14:14:52', '2026-02-01 14:14:52'),
(30, 15, 17, 'Sarath Madushanka', 'Update', 'fvfddfbdggfbfgffg', 'fdbdbb', '2026-02-01 14:24:33', '2026-02-01 14:24:33'),
(31, 15, 1, 'System Administrator', 'Update', 'sfsfdfdfdv', 'fdbdfbdbfdd', '2026-02-01 14:25:09', '2026-02-01 14:25:09'),
(32, 15, 1, 'System Administrator', 'Action', 'ytfyft', 'guuuu', '2026-02-02 04:24:24', '2026-02-02 04:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `jobapplication`
--

CREATE TABLE `jobapplication` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `qualifications` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'closed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `caretaker_id` int(11) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `mobilerider_id` int(11) DEFAULT NULL,
  `premiseofficer_id` int(11) DEFAULT NULL,
  `leave_type` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `admin_response` text DEFAULT NULL COMMENT 'Reason for rejection',
  `reviewed_by` int(11) DEFAULT NULL COMMENT 'Admin user ID who reviewed',
  `reviewed_at` timestamp NULL DEFAULT NULL COMMENT 'When the request was reviewed',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `caretaker_id`, `supervisor_id`, `mobilerider_id`, `premiseofficer_id`, `leave_type`, `reason`, `start_date`, `end_date`, `proof_file`, `status`, `admin_response`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 25, NULL, NULL, NULL, 'Sick Leave', 'rrggff', '2026-02-09', '2026-02-09', NULL, 'Approved', NULL, 1, '2026-02-09 07:47:35', '2026-02-09 09:01:38', '2026-02-09 07:47:35'),
(2, NULL, NULL, NULL, 3, 'Sick Leave', 'sdvfdsvs', '2026-02-10', '2026-02-12', NULL, 'Rejected', 'zccdscdcs', 1, '2026-02-09 05:31:17', '2026-02-09 10:26:45', '2026-02-09 05:31:17'),
(3, NULL, NULL, NULL, 3, 'Annual Leave', 'cascsa', '2026-02-10', '2026-02-10', '/uploads/leave_proofs/proof_1770616685_6989776de7d73.png', 'Approved', NULL, 1, '2026-02-09 07:47:29', '2026-02-09 11:28:05', '2026-02-09 07:47:29'),
(4, NULL, NULL, 5, NULL, 'Sick Leave', 'sda', '2026-02-13', '2026-02-20', '/uploads/leave_proofs/proof_1770618433_69897e4120065.png', 'Approved', NULL, 1, '2026-02-09 07:47:31', '2026-02-09 11:57:13', '2026-02-09 07:47:31'),
(5, NULL, 19, NULL, NULL, 'Maternity Leave', 'wddevfvvsdvdsvsvs', '2026-02-10', '2026-02-19', '/uploads/leave_proofs/proof_1770623154_698990b283291.png', 'Approved', NULL, 1, '2026-02-09 07:47:25', '2026-02-09 13:15:54', '2026-02-09 07:47:25'),
(6, 25, NULL, NULL, NULL, 'Maternity Leave', 'egeg', '2026-02-12', '2026-02-18', '/uploads/leave_proofs/proof_1770623928_698993b8e22f8.png', 'Rejected', 'fvfv', 1, '2026-02-09 07:59:17', '2026-02-09 13:28:48', '2026-02-09 07:59:17'),
(7, NULL, NULL, NULL, 26, 'Paternity Leave', 'vcccccvdvfdvfdsfdfdsdffdgdsgdgdsgsgdsfvdv', '2026-02-11', '2026-02-13', '/uploads/leave_proofs/proof_1770690748_698a98bcea3ac.png', 'Pending', NULL, NULL, NULL, '2026-02-10 08:02:28', '2026-02-10 02:32:28'),
(8, NULL, NULL, 5, NULL, 'Annual Leave', 'dcscd', '2026-02-13', '2026-02-14', NULL, 'Pending', NULL, NULL, NULL, '2026-02-12 22:26:15', '2026-02-12 16:56:15'),
(9, 54, NULL, NULL, NULL, 'Sick Leave', 'dvfdsbsfddsdsdfvfdvfdvfdsvddsfdv', '2026-02-13', '2026-02-25', NULL, 'Pending', NULL, NULL, NULL, '2026-02-12 23:14:36', '2026-02-12 17:44:36'),
(10, NULL, NULL, NULL, 60, 'Sick Leave', 'vomitting', '2026-04-08', '2026-04-10', '/uploads/leave_proofs/proof_1775452599_69d341b7899a7.jpg', 'Approved', NULL, 1, '2026-04-06 05:19:51', '2026-04-06 10:46:39', '2026-04-06 05:19:51'),
(11, NULL, NULL, NULL, 60, 'Sick Leave', 'sick', '2026-05-14', '2026-05-15', '/uploads/leave_proofs/proof_1775455363_69d34c831f5b1.jpg', 'Approved', NULL, 1, '2026-04-06 06:03:19', '2026-04-06 11:32:43', '2026-04-06 06:03:19'),
(12, NULL, NULL, NULL, 60, 'Sick Leave', 'stomach', '2026-05-20', '2026-05-21', '/uploads/leave_proofs/proof_1775457474_69d354c2793d0.jpg', 'Approved', 'Approved with replacement officer assignment for leave period', 1, '2026-04-06 06:50:56', '2026-04-06 12:07:54', '2026-04-06 06:50:56'),
(13, NULL, NULL, NULL, 60, 'Sick Leave', 'ache', '2026-05-24', '2026-05-26', '/uploads/leave_proofs/proof_1775458907_69d35a5bd9541.jpg', 'Approved', 'Approved with replacement officer assignment for leave period', 1, '2026-04-06 07:02:36', '2026-04-06 12:31:47', '2026-04-06 07:02:36'),
(14, NULL, NULL, NULL, 60, 'Sick Leave', 'fever', '2026-04-29', '2026-04-30', '/uploads/leave_proofs/proof_1775918561_69da5de1a34f9.jpg', 'Approved', 'Approved with replacement officer assignment for leave period', 1, '2026-04-11 14:43:52', '2026-04-11 20:12:41', '2026-04-11 14:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL COMMENT 'References Users.id',
  `recipient_id` int(11) NOT NULL COMMENT 'References Users.id',
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `is_edited` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores all messages between users in the system';

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `is_read`, `read_at`, `is_edited`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 29, 34, 'wdwdcwd', 1, NULL, 0, 0, '2026-01-28 17:46:39', '2026-01-28 17:46:39'),
(2, 29, 34, 'wwcc', 1, NULL, 0, 0, '2026-01-28 17:46:45', '2026-01-28 17:46:45'),
(3, 29, 34, 'wefwefwefwe', 1, NULL, 0, 0, '2026-01-28 17:51:11', '2026-01-28 17:51:11'),
(4, 1, 29, 'This message was deleted', 1, NULL, 0, 1, '2026-01-28 17:59:18', '2026-01-28 18:52:28'),
(5, 1, 37, 'evfveverrev', 1, '2026-01-29 00:29:38', 0, 0, '2026-01-28 17:59:24', '2026-01-28 18:59:38'),
(6, 1, 37, 'ec', 1, '2026-01-29 00:29:38', 0, 0, '2026-01-28 18:29:56', '2026-01-28 18:59:38'),
(7, 1, 37, 'rfrewfefewrf', 1, '2026-01-29 00:29:38', 0, 0, '2026-01-28 18:30:39', '2026-01-28 18:59:38'),
(8, 1, 37, 'x xzxz', 1, '2026-01-29 00:29:38', 0, 0, '2026-01-28 18:39:10', '2026-01-28 18:59:38'),
(9, 1, 37, 'xz xz', 1, '2026-01-29 00:29:38', 0, 0, '2026-01-28 18:39:19', '2026-01-28 18:59:38'),
(10, 1, 37, 'x zsdcs', 1, '2026-01-29 00:29:38', 1, 0, '2026-01-28 18:39:34', '2026-01-28 18:59:38'),
(12, 37, 1, 'This message was deleted', 1, NULL, 1, 1, '2026-01-28 18:44:37', '2026-01-28 18:47:36'),
(13, 1, 37, 'This message was deleted', 1, '2026-01-29 00:26:57', 0, 1, '2026-01-28 18:56:57', '2026-01-28 19:10:48'),
(14, 37, 1, 'ewewfw', 1, '2026-01-29 00:27:39', 0, 0, '2026-01-28 18:57:39', '2026-01-28 18:57:39'),
(15, 1, 37, 'This message was deleted', 1, '2026-01-29 00:30:03', 1, 1, '2026-01-28 19:00:03', '2026-01-28 19:10:34'),
(16, 1, 8, 'efefc', 1, '2026-01-29 00:31:01', 0, 0, '2026-01-28 19:01:01', '2026-01-28 19:01:01'),
(17, 1, 37, 'This message was deleted', 1, '2026-01-29 00:33:08', 0, 1, '2026-01-28 19:02:48', '2026-01-28 19:08:13'),
(18, 37, 1, 's s s s', 1, '2026-01-29 00:33:43', 0, 0, '2026-01-28 19:03:13', '2026-01-28 19:03:43'),
(19, 37, 1, 'ddgvgdg', 1, '2026-01-29 06:15:46', 0, 0, '2026-01-29 00:45:45', '2026-01-29 00:45:46'),
(20, 1, 37, 'vdfvfdv', 1, '2026-01-29 06:15:57', 0, 0, '2026-01-29 00:45:55', '2026-01-29 00:45:57'),
(21, 37, 1, 'dfvdfvdf', 1, '2026-01-29 06:16:16', 0, 0, '2026-01-29 00:46:08', '2026-01-29 00:46:16'),
(22, 37, 1, 'ucccccccghch', 1, '2026-01-29 06:17:22', 0, 0, '2026-01-29 00:47:22', '2026-01-29 00:47:22'),
(23, 1, 37, 'hjvvh', 1, '2026-01-29 06:18:03', 0, 0, '2026-01-29 00:48:00', '2026-01-29 00:48:03'),
(24, 1, 37, 'ytfytfyyt', 1, '2026-01-29 06:24:31', 0, 0, '2026-01-29 00:54:28', '2026-01-29 00:54:31'),
(25, 1, 37, 'hgcgcgh', 1, '2026-01-29 06:29:05', 0, 0, '2026-01-29 00:59:02', '2026-01-29 00:59:05'),
(26, 37, 1, 'hgcggcg', 1, '2026-01-29 06:29:13', 0, 0, '2026-01-29 00:59:10', '2026-01-29 00:59:13'),
(27, 37, 1, 'nvvhv', 1, '2026-01-29 06:29:19', 0, 0, '2026-01-29 00:59:17', '2026-01-29 00:59:19'),
(28, 37, 1, 'vxxggfxg', 1, '2026-01-29 06:29:37', 0, 0, '2026-01-29 00:59:31', '2026-01-29 00:59:37'),
(29, 1, 29, 'vhhv', 1, '2026-01-31 20:11:32', 0, 0, '2026-01-29 00:59:45', '2026-01-31 14:41:32'),
(30, 37, 1, 'hvhvhjhj', 1, '2026-01-29 10:33:44', 0, 0, '2026-01-29 01:00:07', '2026-01-29 05:03:44'),
(31, 1, 10, 'dvsdsvsffv', 1, NULL, 0, 0, '2026-01-29 06:01:15', '2026-01-29 06:01:15'),
(32, 17, 18, 'dfvdv', 0, NULL, 0, 0, '2026-01-29 06:18:39', '2026-01-29 06:18:39'),
(33, 17, 1, 'dgdb', 1, '2026-01-29 11:58:34', 0, 0, '2026-01-29 06:18:55', '2026-01-29 06:28:34'),
(34, 1, 17, 'dvdfvf', 1, '2026-01-29 11:51:21', 0, 0, '2026-01-29 06:19:15', '2026-01-29 06:21:21'),
(35, 1, 17, 'fbdbdbfddbdbdfdbdfbdb', 1, '2026-01-29 11:58:08', 0, 0, '2026-01-29 06:24:29', '2026-01-29 06:28:08'),
(36, 1, 10, 'dwcdecdc', 1, NULL, 0, 0, '2026-01-29 06:27:45', '2026-01-29 06:27:45'),
(37, 1, 17, 'sdcdscs', 1, '2026-01-29 11:58:08', 0, 0, '2026-01-29 06:27:54', '2026-01-29 06:28:08'),
(38, 17, 1, 'dccdscscd', 1, '2026-01-29 11:58:34', 0, 0, '2026-01-29 06:28:13', '2026-01-29 06:28:34'),
(39, 1, 17, 'vdvdvd', 1, '2026-01-29 11:58:56', 0, 0, '2026-01-29 06:28:44', '2026-01-29 06:28:56'),
(40, 29, 1, 'gfgfdfdgfdg', 1, '2026-02-02 08:46:12', 0, 0, '2026-01-31 14:41:47', '2026-02-02 03:16:12'),
(41, 37, 1, 'fsdfdsffsfsdfs', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:48', '2026-01-31 14:43:14'),
(42, 37, 1, 'efefewf', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:50', '2026-01-31 14:43:14'),
(43, 37, 1, 'wefewf', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:51', '2026-01-31 14:43:14'),
(44, 37, 1, 'wefewf', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:52', '2026-01-31 14:43:14'),
(45, 37, 1, 'wefew', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:53', '2026-01-31 14:43:14'),
(46, 37, 1, 'wefewf', 1, '2026-01-31 20:13:14', 0, 0, '2026-01-31 14:42:54', '2026-01-31 14:43:14'),
(47, 1, 37, 'gfxgxg', 1, NULL, 0, 0, '2026-02-04 12:15:41', '2026-02-04 12:15:41'),
(48, 1, 37, 'This message was deleted', 1, NULL, 1, 1, '2026-02-04 12:15:47', '2026-02-04 12:16:02'),
(49, 1, 37, 'refreg', 1, NULL, 0, 0, '2026-02-06 06:27:09', '2026-02-06 06:27:09'),
(50, 17, 1, 'eweewwe', 1, '2026-02-07 08:06:50', 0, 0, '2026-02-06 06:28:31', '2026-02-07 02:36:50'),
(51, 25, 1, 'efeevdevc', 1, '2026-02-07 08:06:41', 0, 0, '2026-02-07 02:35:58', '2026-02-07 02:36:41'),
(52, 25, 1, 'wfrewfew', 1, '2026-02-07 08:06:41', 0, 0, '2026-02-07 02:35:59', '2026-02-07 02:36:41'),
(53, 25, 1, 'wfwef', 1, '2026-02-07 08:06:41', 0, 0, '2026-02-07 02:36:00', '2026-02-07 02:36:41'),
(54, 25, 1, 'ewfwf', 1, '2026-02-07 08:06:41', 0, 0, '2026-02-07 02:36:00', '2026-02-07 02:36:41'),
(55, 25, 1, 'dfsdf', 1, '2026-02-07 08:06:41', 0, 0, '2026-02-07 02:36:13', '2026-02-07 02:36:41'),
(56, 1, 25, 'dvdfvfdvfdv', 1, '2026-02-07 08:07:16', 0, 0, '2026-02-07 02:36:45', '2026-02-07 02:37:16'),
(57, 1, 25, 'sdvsd', 1, '2026-02-07 08:07:16', 0, 0, '2026-02-07 02:36:46', '2026-02-07 02:37:16'),
(58, 1, 25, 'svs', 1, '2026-02-07 08:07:16', 0, 0, '2026-02-07 02:36:47', '2026-02-07 02:37:16'),
(59, 1, 25, 'svsv', 1, '2026-02-07 08:07:16', 0, 0, '2026-02-07 02:36:48', '2026-02-07 02:37:16'),
(60, 25, 1, 'fffsdfsdd', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:40:51', '2026-02-07 02:41:31'),
(61, 25, 1, 'ewfrewf', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:40:52', '2026-02-07 02:41:31'),
(62, 25, 1, 'wfw', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:40:53', '2026-02-07 02:41:31'),
(63, 25, 1, 'wfwf', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:40:53', '2026-02-07 02:41:31'),
(64, 25, 1, 'wef', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:40:54', '2026-02-07 02:41:31'),
(65, 25, 1, 'f', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:41:02', '2026-02-07 02:41:31'),
(66, 25, 1, 'sdfsf', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:41:02', '2026-02-07 02:41:31'),
(67, 25, 1, 'wfw', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:41:03', '2026-02-07 02:41:31'),
(68, 25, 1, 'wfw', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:41:03', '2026-02-07 02:41:31'),
(69, 25, 1, 'wfew', 1, '2026-02-07 08:11:31', 0, 0, '2026-02-07 02:41:04', '2026-02-07 02:41:31'),
(70, 41, 33, 'jvv', 1, NULL, 0, 0, '2026-02-10 02:12:06', '2026-03-09 08:36:03'),
(71, 41, 1, 'hgchc', 1, '2026-02-10 07:47:32', 0, 0, '2026-02-10 02:12:17', '2026-02-10 02:17:32'),
(72, 1, 41, 'jhvjhvjv', 1, '2026-02-10 07:43:11', 0, 0, '2026-02-10 02:12:45', '2026-02-10 02:13:11'),
(73, 41, 1, 'This message was deleted', 0, NULL, 1, 1, '2026-02-10 02:13:58', '2026-02-10 02:14:12'),
(74, 41, 1, 'hvvhghv', 1, '2026-02-10 07:47:32', 0, 0, '2026-02-10 02:17:15', '2026-02-10 02:17:32'),
(75, 1, 41, 'hjvvh', 1, '2026-02-10 07:47:57', 0, 0, '2026-02-10 02:17:41', '2026-02-10 02:17:57'),
(76, 26, 1, 'ggggg', 1, '2026-02-10 16:57:44', 0, 0, '2026-02-10 05:31:29', '2026-02-10 11:27:44'),
(77, 41, 1, 'dsv', 1, '2026-02-10 16:57:20', 0, 0, '2026-02-10 06:21:30', '2026-02-10 11:27:20'),
(78, 1, 41, 'wefwefwefwe', 1, '2026-02-12 20:37:56', 0, 0, '2026-02-10 11:27:40', '2026-02-12 15:07:56'),
(79, 41, 1, 'eegeer', 1, '2026-02-12 20:38:00', 0, 0, '2026-02-12 15:07:58', '2026-02-12 15:08:00'),
(80, 1, 41, 'fevevfd', 1, '2026-02-12 20:38:11', 0, 0, '2026-02-12 15:08:09', '2026-02-12 15:08:11'),
(81, 1, 41, 'vfvfd', 1, '2026-02-12 20:38:17', 0, 0, '2026-02-12 15:08:14', '2026-02-12 15:08:17'),
(82, 41, 1, 'f f', 1, '2026-02-12 20:38:21', 0, 0, '2026-02-12 15:08:20', '2026-02-12 15:08:21'),
(83, 41, 1, 'fevfdv', 1, '2026-02-12 20:38:57', 0, 0, '2026-02-12 15:08:57', '2026-02-12 15:08:57'),
(84, 41, 1, 'dfdf', 1, '2026-02-12 20:39:03', 0, 0, '2026-02-12 15:09:01', '2026-02-12 15:09:03'),
(85, 1, 41, 'wfwfdcs', 1, '2026-02-12 20:39:07', 0, 0, '2026-02-12 15:09:06', '2026-02-12 15:09:07'),
(86, 1, 41, 'fvfdvd', 1, '2026-02-12 20:39:10', 0, 0, '2026-02-12 15:09:09', '2026-02-12 15:09:10'),
(87, 1, 41, 'dfvfv', 1, '2026-02-12 20:39:13', 0, 0, '2026-02-12 15:09:13', '2026-02-12 15:09:13'),
(88, 41, 1, 'ererge', 1, '2026-02-16 14:40:27', 0, 0, '2026-02-16 09:10:26', '2026-02-16 09:10:27'),
(89, 1, 41, 'ffff', 1, NULL, 0, 0, '2026-03-28 01:38:43', '2026-03-28 01:38:43'),
(90, 1, 26, 'hi', 1, NULL, 0, 0, '2026-04-16 18:30:14', '2026-04-16 18:30:14'),
(91, 1, 26, 'hiD', 1, NULL, 0, 0, '2026-04-16 18:32:00', '2026-04-16 18:32:01');

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_rider`
--

CREATE TABLE `mobile_rider` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `riderID` varchar(50) NOT NULL,
  `routeID` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `NIC` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `employment_status` enum('Active','On Leave','Terminated','Suspended') DEFAULT 'Active',
  `rank` enum('Junior','Senior') DEFAULT 'Junior',
  `rating` decimal(3,2) DEFAULT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `shift_pattern` varchar(50) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobile_rider`
--

INSERT INTO `mobile_rider` (`id`, `userID`, `riderID`, `routeID`, `date_of_birth`, `NIC`, `gender`, `address`, `district`, `city`, `hire_date`, `employment_status`, `rank`, `rating`, `shift_pattern`, `application_id`, `created_at`, `updated_at`) VALUES
(1, 24, 'MR003', NULL, '2025-12-12', '901234567', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Thirukkovil', '2025-12-31', 'Active', 'Junior', NULL, NULL, NULL, '2025-12-31 03:24:38', '2025-12-31 03:24:38'),
(2, 29, 'MR004', NULL, '2026-01-15', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Irakkamam', '2026-01-02', 'Active', 'Junior', NULL, NULL, NULL, '2026-01-02 06:43:01', '2026-01-02 06:43:01'),
(3, 37, 'MR005', NULL, '2026-01-15', '299226773997', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Matara', 'Handiya', '2026-01-21', 'Active', 'Junior', NULL, NULL, NULL, '2026-01-21 12:05:23', '2026-01-21 12:05:23');

-- --------------------------------------------------------

--
-- Stand-in structure for view `mobile_rider_full_details`
-- (See below for the actual view)
--
CREATE TABLE `mobile_rider_full_details` (
`mobile_rider_id` int(11)
,`riderID` varchar(50)
,`date_of_birth` date
,`NIC` varchar(20)
,`gender` enum('Male','Female','Other')
,`address` text
,`district` varchar(50)
,`city` varchar(50)
,`hire_date` date
,`employment_status` enum('Active','On Leave','Terminated','Suspended')
,`rank` enum('Junior','Senior')
,`rating` decimal(3,2)
,`shift_pattern` varchar(50)
,`application_id` int(11)
,`rider_record_created` timestamp
,`rider_record_updated` timestamp
,`route_id` varchar(50)
,`route_name` varchar(100)
,`route_description` text
,`location` text
,`distance_km` decimal(6,2)
,`difficulty_level` enum('Easy','Medium','Hard')
,`route_status` enum('Active','Inactive')
,`user_id` int(11)
,`user_identifier` varchar(50)
,`name` varchar(255)
,`email` varchar(255)
,`role` enum('admin','supervisor','premise officer','mobile rider','client','caretaker')
,`phone_number` varchar(50)
,`profile_image` varchar(255)
,`user_status` enum('active','inactive','suspended')
,`user_account_created` timestamp
,`user_account_updated` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Recipient user ID from Users table',
  `type` varchar(50) NOT NULL DEFAULT 'info' COMMENT 'Notification type: info, success, warning, danger',
  `title` varchar(255) NOT NULL COMMENT 'Notification title/heading',
  `message` text NOT NULL COMMENT 'Notification message content',
  `link` varchar(500) DEFAULT NULL COMMENT 'Optional link/URL for the notification',
  `icon` varchar(100) DEFAULT 'notifications' COMMENT 'Material icon name',
  `is_read` tinyint(1) DEFAULT 0 COMMENT 'Read status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `from_user_id` int(11) DEFAULT NULL COMMENT 'Sender user ID',
  `to_user_id` int(11) DEFAULT NULL COMMENT 'Recipient user ID (alternative to user_id column)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `updated_at`, `from_user_id`, `to_user_id`) VALUES
(1, 2, 'info', 'Welcome to Notifications', 'This is your first notification!', '/dashboard', 'notifications', 1, '2026-01-29 16:57:44', '2026-01-30 03:19:19', NULL, NULL),
(10, 19, 'registration', 'Application Approved', 'Congratulations! Your Care Taker application has been approved.', '/caretaker/dashboard', 'check_circle', 1, '2026-02-01 10:49:24', '2026-02-01 15:31:49', 1, NULL),
(12, 46, 'registration', 'Registration Approved', 'Your registration has been approved. Welcome to RED FORCE!', '/client/dashboard', 'check_circle', 0, '2026-02-01 11:04:23', '2026-02-01 11:04:23', 1, NULL),
(13, 34, 'warning', 'New Incident Reported', 'Sarath Madushanka reported a Security Breach incident at Site ID: 5. Priority: Critical', 'http://localhost/RedForce/admin/incidents', 'warning', 0, '2026-02-01 13:55:25', '2026-02-01 13:55:25', 17, NULL),
(14, 33, 'warning', 'New Incident Reported', 'Sarath Madushanka reported a Security Breach incident at Site ID: 5. Priority: Critical', 'http://localhost/RedForce/admin/incidents', 'warning', 0, '2026-02-01 13:55:25', '2026-02-01 13:55:25', 17, NULL),
(16, 29, 'warning', 'New Incident Reported', 'Sarath Madushanka reported a Security Breach incident at Site ID: 5. Priority: Critical', 'http://localhost/RedForce/mobilerider/incidents', 'warning', 1, '2026-02-01 13:55:25', '2026-02-01 14:06:17', 17, NULL),
(17, 34, 'info', 'New Review on Incident #15', 'Sarath Madushanka added a review: \"eded\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:12:06', '2026-02-01 14:12:06', 17, NULL),
(18, 33, 'info', 'New Review on Incident #15', 'Sarath Madushanka added a review: \"eded\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:12:06', '2026-02-01 14:12:06', 17, NULL),
(20, 29, 'info', 'New Review on Incident #15', 'Sarath Madushanka added a review: \"eded\" on incident #15', 'http://localhost/RedForce/mobilerider/incidents', 'comment', 1, '2026-02-01 14:12:06', '2026-02-01 14:14:26', 17, NULL),
(21, 34, 'info', 'New Review on Incident #15', 'Sarath Madushanka (Supervisor) added a review: \"fvfddfbdggfbfgffg\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:24:33', '2026-02-01 14:24:33', 17, NULL),
(22, 33, 'info', 'New Review on Incident #15', 'Sarath Madushanka (Supervisor) added a review: \"fvfddfbdggfbfgffg\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:24:33', '2026-02-01 14:24:33', 17, NULL),
(23, 1, 'info', 'New Review on Incident #15', 'Sarath Madushanka (Supervisor) added a review: \"fvfddfbdggfbfgffg\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 1, '2026-02-01 14:24:33', '2026-02-01 14:29:41', 17, NULL),
(24, 29, 'info', 'New Review on Incident #15', 'Sarath Madushanka (Supervisor) added a review: \"fvfddfbdggfbfgffg\" on incident #15', 'http://localhost/RedForce/MobileRider/incidents', 'comment', 1, '2026-02-01 14:24:33', '2026-02-01 14:44:20', 17, NULL),
(25, 34, 'info', 'New Review on Incident #15', 'System Administrator (Admin) added a review: \"sfsfdfdfdv\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:25:09', '2026-02-01 14:25:09', 1, NULL),
(26, 33, 'info', 'New Review on Incident #15', 'System Administrator (Admin) added a review: \"sfsfdfdfdv\" on incident #15', 'http://localhost/RedForce/admin/incidents', 'comment', 0, '2026-02-01 14:25:09', '2026-02-01 14:25:09', 1, NULL),
(27, 17, 'info', 'New Review on Incident #15', 'System Administrator (Admin) added a review: \"sfsfdfdfdv\" on incident #15', 'http://localhost/RedForce/supervisor/viewIncident/15', 'comment', 0, '2026-02-01 14:25:09', '2026-02-01 14:25:09', 1, NULL),
(28, 29, 'info', 'New Review on Incident #15', 'System Administrator (Admin) added a review: \"sfsfdfdfdv\" on incident #15', 'http://localhost/RedForce/MobileRider/incidents', 'comment', 1, '2026-02-01 14:25:09', '2026-02-01 14:44:23', 1, NULL),
(29, 54, 'success', 'Application Approved', 'Congratulations! Your Care Taker application has been approved. Welcome to RED FORCE!', '/caretaker/dashboard', 'check_circle', 1, '2026-02-01 15:03:05', '2026-02-01 15:03:50', 1, NULL),
(30, 19, 'info', 'Rank Updated', 'Your rank has been updated to: Senior. Please check your profile for more details.', '/premiseofficer/dashboard', 'star', 1, '2026-02-01 15:28:18', '2026-02-01 15:31:49', 1, NULL),
(31, 19, 'info', 'Rank Updated', 'Your rank has been updated to: Supervisor. Please check your profile for more details.', '/premiseofficer/dashboard', 'star', 1, '2026-02-01 15:28:27', '2026-02-01 15:31:49', 1, NULL),
(32, 46, 'info', 'New Site Added', 'A new site \'Hampden Lane\' has been added to your account.', 'client/viewsite/19', 'business', 0, '2026-02-02 02:50:59', '2026-02-02 02:50:59', 1, NULL),
(33, 54, 'assignment', 'New Site Assignment', 'You have been assigned as caretaker to Darley Road.', '/caretaker/dashboard', 'location_on', 0, '2026-02-04 10:35:38', '2026-02-04 10:35:38', 1, NULL),
(34, 25, 'assignment', 'New Site Assignment', 'You have been assigned as caretaker to Darley Road.', '/caretaker/dashboard', 'location_on', 1, '2026-02-04 10:42:47', '2026-02-06 02:52:30', 1, NULL),
(35, 27, 'assignment', 'New Site Assignment', 'You have been assigned as caretaker to Darley Road.', '/caretaker/dashboard', 'location_on', 0, '2026-02-04 10:48:02', '2026-02-04 10:48:02', 1, NULL),
(36, 41, 'info', 'Equipment Request Approved by Supervisor', 'Equipment request for Uniform from P.M.T Vithanage has been approved by supervisor and needs your approval.', 'http://localhost/RedForce/client/equipmentApprovals', 'approval', 0, '2026-02-05 04:59:27', '2026-02-05 04:59:27', 17, NULL),
(37, 41, 'info', 'Equipment Request Approved by Supervisor', 'Equipment request for Helmet from P.M.T Vithanage has been approved by supervisor and needs your approval.', 'http://localhost/RedForce/client/equipmentApprovals', 'approval', 0, '2026-02-05 05:07:18', '2026-02-05 05:07:18', 17, NULL),
(38, 25, 'warning', 'Equipment Request Rejected', 'Your equipment request for First Aid Kit has been rejected by supervisor. Reason: fsvffvfdfdv', 'http://localhost/RedForce/caretaker/equipment', 'cancel', 1, '2026-02-05 05:08:51', '2026-02-06 02:52:30', 17, NULL),
(39, 41, 'info', 'Equipment Request Approved by Supervisor', 'Equipment request for Raincoat from P.M.T Vithanage has been approved by supervisor and needs your approval.', 'http://localhost/RedForce/client/equipmentApprovals', 'approval', 0, '2026-02-05 14:23:19', '2026-02-05 14:23:19', 17, NULL),
(40, 34, 'info', 'New Incident Reported', 'Sarath Madushanka reported a Fire Alarm incident at Site ID: 5. Priority: Low', 'http://localhost/RedForce/admin/incidents', 'warning', 0, '2026-02-06 06:20:08', '2026-02-06 06:20:08', 17, NULL),
(41, 33, 'info', 'New Incident Reported', 'Sarath Madushanka reported a Fire Alarm incident at Site ID: 5. Priority: Low', 'http://localhost/RedForce/admin/incidents', 'warning', 0, '2026-02-06 06:20:08', '2026-02-06 06:20:08', 17, NULL),
(42, 1, 'info', 'New Incident Reported', 'Sarath Madushanka reported a Fire Alarm incident at Site ID: 5. Priority: Low', 'http://localhost/RedForce/admin/incidents', 'warning', 1, '2026-02-06 06:20:08', '2026-02-06 06:32:09', 17, NULL),
(43, 29, 'info', 'New Incident Reported', 'Sarath Madushanka reported a Fire Alarm incident at Site ID: 5. Priority: Low', 'http://localhost/RedForce/MobileRider/incidents', 'warning', 0, '2026-02-06 06:20:08', '2026-02-06 06:20:08', 17, NULL),
(44, 1, 'info', 'New Message from P.M.T Vithanage', 'fffsdfsdd', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:40:51', '2026-02-07 02:41:40', 25, NULL),
(45, 1, 'info', 'New Message from P.M.T Vithanage', 'ewfrewf', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:40:52', '2026-02-07 02:41:40', 25, NULL),
(46, 1, 'info', 'New Message from P.M.T Vithanage', 'wfw', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:40:53', '2026-02-07 02:41:40', 25, NULL),
(47, 1, 'info', 'New Message from P.M.T Vithanage', 'wfwf', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:40:53', '2026-02-07 02:41:40', 25, NULL),
(48, 1, 'info', 'New Message from P.M.T Vithanage', 'wef', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:40:54', '2026-02-07 02:41:40', 25, NULL),
(49, 1, 'info', 'New Message from P.M.T Vithanage', 'f', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:41:02', '2026-02-07 02:41:40', 25, NULL),
(50, 1, 'info', 'New Message from P.M.T Vithanage', 'sdfsf', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:41:02', '2026-02-07 02:41:40', 25, NULL),
(51, 1, 'info', 'New Message from P.M.T Vithanage', 'wfw', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:41:03', '2026-02-07 02:41:40', 25, NULL),
(52, 1, 'info', 'New Message from P.M.T Vithanage', 'wfw', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-07 02:41:03', '2026-02-07 02:41:40', 25, NULL),
(54, 56, 'success', 'Registration Approved', 'Your registration has been approved. Welcome to RED FORCE!', '/client/dashboard', 'check_circle', 0, '2026-02-07 05:45:06', '2026-02-07 05:45:06', 1, NULL),
(55, 3, 'alert', 'Leave Request Rejected', 'Your leave request has been rejected. Reason: zccdscdcs', '/premiseOfficer/leaverequests', 'cancel', 0, '2026-02-09 05:31:17', '2026-02-09 05:31:17', 1, NULL),
(56, 19, 'leave', 'Leave Request Approved', 'Your leave request from 2026-02-10 to 2026-02-19 has been approved.', '/premiseOfficer/leaverequests', 'check_circle', 0, '2026-02-09 07:47:25', '2026-02-09 07:47:25', 1, NULL),
(57, 3, 'leave', 'Leave Request Approved', 'Your leave request from 2026-02-10 to 2026-02-10 has been approved.', '/premiseOfficer/leaverequests', 'check_circle', 0, '2026-02-09 07:47:29', '2026-02-09 07:47:29', 1, NULL),
(58, 5, 'leave', 'Leave Request Approved', 'Your leave request from 2026-02-13 to 2026-02-20 has been approved.', '/premiseOfficer/leaverequests', 'check_circle', 0, '2026-02-09 07:47:31', '2026-02-09 07:47:31', 1, NULL),
(59, 25, 'leave', 'Leave Request Approved', 'Your leave request from 2026-02-09 to 2026-02-09 has been approved.', '/premiseOfficer/leaverequests', 'check_circle', 0, '2026-02-09 07:47:35', '2026-02-09 07:47:35', 1, NULL),
(60, 34, 'info', 'New Leave Request', 'P.M.T Vithanage (Caretaker) submitted a leave request for Maternity Leave from 2026-02-12 to 2026-02-18', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-09 07:58:48', '2026-02-09 07:58:48', 25, NULL),
(61, 33, 'info', 'New Leave Request', 'P.M.T Vithanage (Caretaker) submitted a leave request for Maternity Leave from 2026-02-12 to 2026-02-18', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-09 07:58:48', '2026-02-09 07:58:48', 25, NULL),
(62, 1, 'info', 'New Leave Request', 'P.M.T Vithanage (Caretaker) submitted a leave request for Maternity Leave from 2026-02-12 to 2026-02-18', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 1, '2026-02-09 07:58:48', '2026-03-30 09:23:04', 25, NULL),
(63, 25, 'warning', 'Leave Request Rejected', 'Your Maternity Leave leave request from 2026-02-12 to 2026-02-18 was rejected. Reason: fvfv', 'http://localhost/RedForce/caretaker/leaverequests', 'cancel', 0, '2026-02-09 07:59:17', '2026-02-09 07:59:17', 1, NULL),
(64, 33, 'info', 'New Message from Company Example 2', 'jvv', 'http://localhost/RedForce/Admin/messages', 'message', 0, '2026-02-10 02:12:06', '2026-02-10 02:12:06', 41, NULL),
(65, 1, 'info', 'New Message from Company Example 2', 'hgchc', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-10 02:12:17', '2026-03-30 09:23:04', 41, NULL),
(66, 41, 'info', 'New Message from System Administrator', 'jhvjhvjv', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-10 02:12:45', '2026-02-10 02:12:45', 1, NULL),
(67, 1, 'info', 'New Message from Company Example 2', 'hgchcvffd', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-10 02:13:58', '2026-03-30 09:23:04', 41, NULL),
(68, 1, 'info', 'New Message from Company Example 2', 'hvvhghv', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-10 02:17:15', '2026-03-30 09:23:04', 41, NULL),
(69, 41, 'info', 'New Message from System Administrator', 'hjvvh', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-10 02:17:41', '2026-02-10 02:17:41', 1, NULL),
(70, 34, 'info', 'New Leave Request', 'Wimalasiri (Premise Officer) submitted a leave request for Paternity Leave from 2026-02-11 to 2026-02-13', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-10 02:32:28', '2026-02-10 02:32:28', 26, NULL),
(71, 33, 'info', 'New Leave Request', 'Wimalasiri (Premise Officer) submitted a leave request for Paternity Leave from 2026-02-11 to 2026-02-13', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-10 02:32:28', '2026-02-10 02:32:28', 26, NULL),
(72, 1, 'info', 'New Leave Request', 'Wimalasiri (Premise Officer) submitted a leave request for Paternity Leave from 2026-02-11 to 2026-02-13', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 1, '2026-02-10 02:32:28', '2026-03-30 09:23:04', 26, NULL),
(73, 1, 'info', 'New Message from Wimalasiri', 'ggggg', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-10 05:31:29', '2026-03-30 09:23:04', 26, NULL),
(74, 1, 'info', 'New Message from Company Example 2', 'dsv', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-10 06:21:30', '2026-03-30 09:23:04', 41, NULL),
(75, 41, 'info', 'New Message from System Administrator', 'wefwefwefwe', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-10 11:27:40', '2026-02-10 11:27:40', 1, NULL),
(76, 41, 'info', 'New Site Added', 'A new site \'VFS Global Visa Application Centre in Colombo\' has been added to your account.', 'client/viewsite/20', 'business', 0, '2026-02-10 11:35:54', '2026-02-10 11:35:54', 1, NULL),
(77, 1, 'info', 'New Message from Company Example 2', 'eegeer', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-12 15:07:58', '2026-03-30 09:23:04', 41, NULL),
(78, 41, 'info', 'New Message from System Administrator', 'fevevfd', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-12 15:08:09', '2026-02-12 15:08:09', 1, NULL),
(79, 41, 'info', 'New Message from System Administrator', 'vfvfd', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-12 15:08:14', '2026-02-12 15:08:14', 1, NULL),
(80, 1, 'info', 'New Message from Company Example 2', 'f f', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-12 15:08:20', '2026-03-30 09:23:04', 41, NULL),
(81, 1, 'info', 'New Message from Company Example 2', 'fevfdv', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-12 15:08:57', '2026-03-30 09:23:04', 41, NULL),
(82, 1, 'info', 'New Message from Company Example 2', 'dfdf', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-12 15:09:01', '2026-03-30 09:23:04', 41, NULL),
(83, 41, 'info', 'New Message from System Administrator', 'wfwfdcs', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-12 15:09:06', '2026-02-12 15:09:06', 1, NULL),
(84, 41, 'info', 'New Message from System Administrator', 'fvfdvd', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-12 15:09:09', '2026-02-12 15:09:09', 1, NULL),
(85, 41, 'info', 'New Message from System Administrator', 'dfvfv', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-02-12 15:09:13', '2026-02-12 15:09:13', 1, NULL),
(86, 34, 'info', 'New Leave Request', 'Sanjaya Peris (Mobile Rider) submitted a leave request for Annual Leave from 2026-02-13 to 2026-02-14', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-12 16:56:15', '2026-02-12 16:56:15', 5, NULL),
(87, 33, 'info', 'New Leave Request', 'Sanjaya Peris (Mobile Rider) submitted a leave request for Annual Leave from 2026-02-13 to 2026-02-14', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-12 16:56:15', '2026-02-12 16:56:15', 5, NULL),
(88, 1, 'info', 'New Leave Request', 'Sanjaya Peris (Mobile Rider) submitted a leave request for Annual Leave from 2026-02-13 to 2026-02-14', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 1, '2026-02-12 16:56:15', '2026-03-30 09:23:04', 5, NULL),
(89, 34, 'info', 'New Leave Request', 'Kalum Nanayakkara (Caretaker) submitted a leave request for Sick Leave from 2026-02-13 to 2026-02-25', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-12 17:44:36', '2026-02-12 17:44:36', 54, NULL),
(90, 33, 'info', 'New Leave Request', 'Kalum Nanayakkara (Caretaker) submitted a leave request for Sick Leave from 2026-02-13 to 2026-02-25', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-02-12 17:44:36', '2026-02-12 17:44:36', 54, NULL),
(91, 1, 'info', 'New Leave Request', 'Kalum Nanayakkara (Caretaker) submitted a leave request for Sick Leave from 2026-02-13 to 2026-02-25', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 1, '2026-02-12 17:44:36', '2026-03-30 09:23:04', 54, NULL),
(92, 1, 'info', 'New Message from Company Example 2', 'ererge', 'http://localhost/RedForce/Admin/messages', 'message', 1, '2026-02-16 09:10:26', '2026-03-30 09:23:04', 41, NULL),
(93, 41, 'info', 'Equipment Request Approved by Supervisor', 'Equipment request for Radio from P.M.T Vithanage has been approved by supervisor and needs your approval.', 'http://localhost/RedForce/client/equipmentApprovals', 'approval', 0, '2026-02-17 11:47:25', '2026-02-17 11:47:25', 17, NULL),
(94, 34, 'success', 'Payment Received', 'Company Example 2 has made a payment of LKR 1,710.00. Order: ORD_69ad6e55c5f2d_1772973653', '/admin/clients_payments', 'payments', 0, '2026-03-08 12:41:25', '2026-03-08 12:41:25', 41, NULL),
(95, 33, 'success', 'Payment Received', 'Company Example 2 has made a payment of LKR 1,710.00. Order: ORD_69ad6e55c5f2d_1772973653', '/admin/clients_payments', 'payments', 0, '2026-03-08 12:41:25', '2026-03-08 12:41:25', 41, NULL),
(96, 1, 'success', 'Payment Received', 'Company Example 2 has made a payment of LKR 1,710.00. Order: ORD_69ad6e55c5f2d_1772973653', '/admin/clients_payments', 'payments', 1, '2026-03-08 12:41:25', '2026-03-30 09:23:04', 41, NULL),
(97, 41, 'info', 'New Message from System Administrator', 'ffff', 'http://localhost/RedForce/Client/messages', 'message', 0, '2026-03-28 01:38:43', '2026-03-28 01:38:43', 1, NULL),
(98, 34, 'success', 'Payment Received', 'System Administrator has made a payment of LKR 850.00. Order: ORD_69cccb0b4d464_1775029003', '/admin/clients_payments', 'payments', 0, '2026-04-01 07:43:18', '2026-04-01 07:43:18', 1, NULL),
(99, 33, 'success', 'Payment Received', 'System Administrator has made a payment of LKR 850.00. Order: ORD_69cccb0b4d464_1775029003', '/admin/clients_payments', 'payments', 0, '2026-04-01 07:43:18', '2026-04-01 07:43:18', 1, NULL),
(100, 1, 'success', 'Payment Received', 'System Administrator has made a payment of LKR 850.00. Order: ORD_69cccb0b4d464_1775029003', '/admin/clients_payments', 'payments', 0, '2026-04-01 07:43:18', '2026-04-01 07:43:18', 1, NULL),
(101, 57, 'assignment', 'New Site Assignment', 'You have been assigned to alvaroo alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 08:10:49', '2026-04-01 08:10:49', 1, NULL),
(102, 58, 'assignment', 'New Site Assignment', 'You have been assigned to alvaroo alto (Night).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 08:11:15', '2026-04-01 08:11:15', 1, NULL),
(103, 59, 'assignment', 'New Site Assignment', 'You have been assigned to alvaroo alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 08:11:29', '2026-04-01 08:11:29', 1, NULL),
(104, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccef073b9e2_1775038215', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:10:47', '2026-04-01 10:10:47', 40, NULL),
(105, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccef073b9e2_1775038215', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:10:47', '2026-04-01 10:10:47', 40, NULL),
(106, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccef073b9e2_1775038215', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:10:47', '2026-04-01 10:10:47', 40, NULL),
(107, 60, 'assignment', 'New Site Assignment', 'You have been assigned to University of Ruhuna (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 10:11:31', '2026-04-01 10:11:31', 1, NULL),
(108, 61, 'assignment', 'New Site Assignment', 'You have been assigned to University of Ruhuna (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 10:11:48', '2026-04-01 10:11:48', 1, NULL),
(109, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccf3a56cb81_1775039397', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:30:17', '2026-04-01 10:30:17', 40, NULL),
(110, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccf3a56cb81_1775039397', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:30:17', '2026-04-01 10:30:17', 40, NULL),
(111, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 650.00. Order: ORD_69ccf3a56cb81_1775039397', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:30:17', '2026-04-01 10:30:17', 40, NULL),
(112, 60, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 10:30:47', '2026-04-01 10:30:47', 1, NULL),
(113, 61, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 10:30:59', '2026-04-01 10:30:59', 1, NULL),
(114, 62, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-01 10:35:30', '2026-04-01 10:35:30', 1, NULL),
(115, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccf8d8c3034_1775040728', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:52:37', '2026-04-01 10:52:37', 40, NULL),
(116, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccf8d8c3034_1775040728', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:52:37', '2026-04-01 10:52:37', 40, NULL),
(117, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccf8d8c3034_1775040728', '/admin/clients_payments', 'payments', 0, '2026-04-01 10:52:37', '2026-04-01 10:52:37', 40, NULL),
(118, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccfcb20f15f_1775041714', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:09:00', '2026-04-01 11:09:00', 40, NULL),
(119, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccfcb20f15f_1775041714', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:09:00', '2026-04-01 11:09:00', 40, NULL),
(120, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69ccfcb20f15f_1775041714', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:09:00', '2026-04-01 11:09:00', 40, NULL),
(121, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cd00cc6b2e9_1775042764', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:26:33', '2026-04-01 11:26:33', 40, NULL),
(122, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cd00cc6b2e9_1775042764', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:26:33', '2026-04-01 11:26:33', 40, NULL),
(123, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cd00cc6b2e9_1775042764', '/admin/clients_payments', 'payments', 0, '2026-04-01 11:26:33', '2026-04-01 11:26:33', 40, NULL),
(124, 63, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-01 11:44:47', '2026-04-01 11:44:47', 1, NULL),
(125, 69, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 11:47:15', '2026-04-01 11:47:15', 1, NULL),
(126, 70, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 11:47:28', '2026-04-01 11:47:28', 1, NULL),
(127, 67, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 11:47:39', '2026-04-01 11:47:39', 1, NULL),
(128, 68, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-01 11:47:50', '2026-04-01 11:47:50', 1, NULL),
(129, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69ce0017ad976_1775108119', '/admin/clients_payments', 'payments', 0, '2026-04-02 05:36:03', '2026-04-02 05:36:03', 40, NULL),
(130, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69ce0017ad976_1775108119', '/admin/clients_payments', 'payments', 0, '2026-04-02 05:36:03', '2026-04-02 05:36:03', 40, NULL),
(131, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69ce0017ad976_1775108119', '/admin/clients_payments', 'payments', 0, '2026-04-02 05:36:03', '2026-04-02 05:36:03', 40, NULL),
(132, 71, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-02 05:36:42', '2026-04-02 05:36:42', 1, NULL),
(133, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cf64314a66b_1775199281', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:55:23', '2026-04-03 06:55:23', 40, NULL),
(134, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cf64314a66b_1775199281', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:55:23', '2026-04-03 06:55:23', 40, NULL),
(135, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,300.00. Order: ORD_69cf64314a66b_1775199281', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:55:23', '2026-04-03 06:55:23', 40, NULL),
(136, 60, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-03 06:55:51', '2026-04-03 06:55:51', 1, NULL),
(137, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf6512716fc_1775199506', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:58:46', '2026-04-03 06:58:46', 40, NULL),
(138, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf6512716fc_1775199506', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:58:46', '2026-04-03 06:58:46', 40, NULL),
(139, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf6512716fc_1775199506', '/admin/clients_payments', 'payments', 0, '2026-04-03 06:58:46', '2026-04-03 06:58:46', 40, NULL),
(140, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7364a08cc_1775203172', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:00:16', '2026-04-03 08:00:16', 40, NULL),
(141, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7364a08cc_1775203172', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:00:16', '2026-04-03 08:00:16', 40, NULL),
(142, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7364a08cc_1775203172', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:00:16', '2026-04-03 08:00:16', 40, NULL),
(143, 71, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-03 08:00:49', '2026-04-03 08:00:49', 1, NULL),
(144, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7450346be_1775203408', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:03:47', '2026-04-03 08:03:47', 40, NULL),
(145, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7450346be_1775203408', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:03:47', '2026-04-03 08:03:47', 40, NULL),
(146, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7450346be_1775203408', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:03:47', '2026-04-03 08:03:47', 40, NULL),
(147, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf782ad7b29_1775204394', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:20:20', '2026-04-03 08:20:20', 40, NULL),
(148, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf782ad7b29_1775204394', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:20:20', '2026-04-03 08:20:20', 40, NULL),
(149, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf782ad7b29_1775204394', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:20:20', '2026-04-03 08:20:20', 40, NULL),
(150, 64, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-03 08:20:50', '2026-04-03 08:20:50', 1, NULL),
(151, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7c0416ba3_1775205380', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:36:49', '2026-04-03 08:36:49', 40, NULL),
(152, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7c0416ba3_1775205380', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:36:49', '2026-04-03 08:36:49', 40, NULL),
(153, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,500.00. Order: ORD_69cf7c0416ba3_1775205380', '/admin/clients_payments', 'payments', 0, '2026-04-03 08:36:49', '2026-04-03 08:36:49', 40, NULL),
(154, 63, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-03 08:48:34', '2026-04-03 08:48:34', 1, NULL),
(155, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,750.00. Order: ORD_69cf90fc5b5a3_1775210748', '/admin/clients_payments', 'payments', 0, '2026-04-03 10:06:20', '2026-04-03 10:06:20', 40, NULL),
(156, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,750.00. Order: ORD_69cf90fc5b5a3_1775210748', '/admin/clients_payments', 'payments', 0, '2026-04-03 10:06:20', '2026-04-03 10:06:20', 40, NULL),
(157, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 2,750.00. Order: ORD_69cf90fc5b5a3_1775210748', '/admin/clients_payments', 'payments', 0, '2026-04-03 10:06:20', '2026-04-03 10:06:20', 40, NULL),
(158, 65, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-03 10:06:52', '2026-04-03 10:06:52', 1, NULL),
(159, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,000.00. Order: ORD_69d33d5aebcd2_1775451482', '/admin/clients_payments', 'payments', 0, '2026-04-06 04:58:51', '2026-04-06 04:58:51', 40, NULL),
(160, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,000.00. Order: ORD_69d33d5aebcd2_1775451482', '/admin/clients_payments', 'payments', 0, '2026-04-06 04:58:51', '2026-04-06 04:58:51', 40, NULL),
(161, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,000.00. Order: ORD_69d33d5aebcd2_1775451482', '/admin/clients_payments', 'payments', 0, '2026-04-06 04:58:51', '2026-04-06 04:58:51', 40, NULL),
(162, 54, 'assignment', 'New Site Assignment', 'You have been assigned as caretaker to alto.', '/caretaker/dashboard', 'location_on', 0, '2026-04-06 04:59:46', '2026-04-06 04:59:46', 1, NULL),
(163, 34, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-08 to 2026-04-10', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 05:16:39', '2026-04-06 05:16:39', 60, NULL),
(164, 33, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-08 to 2026-04-10', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 05:16:39', '2026-04-06 05:16:39', 60, NULL),
(165, 1, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-08 to 2026-04-10', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 05:16:39', '2026-04-06 05:16:39', 60, NULL),
(166, 60, 'success', 'Leave Request Approved', 'Your Sick Leave leave request from 2026-04-08 to 2026-04-10 has been approved.', 'http://localhost/RedForce/premiseOfficer/leaverequests', 'check_circle', 0, '2026-04-06 05:19:51', '2026-04-06 05:19:51', 1, NULL),
(167, 34, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-14 to 2026-05-15', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:02:43', '2026-04-06 06:02:43', 60, NULL),
(168, 33, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-14 to 2026-05-15', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:02:43', '2026-04-06 06:02:43', 60, NULL),
(169, 1, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-14 to 2026-05-15', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:02:43', '2026-04-06 06:02:43', 60, NULL),
(170, 60, 'success', 'Leave Request Approved', 'Your Sick Leave leave request from 2026-05-14 to 2026-05-15 has been approved.', 'http://localhost/RedForce/premiseOfficer/leaverequests', 'check_circle', 0, '2026-04-06 06:03:19', '2026-04-06 06:03:19', 1, NULL),
(171, 34, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-20 to 2026-05-21', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:37:54', '2026-04-06 06:37:54', 60, NULL),
(172, 33, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-20 to 2026-05-21', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:37:54', '2026-04-06 06:37:54', 60, NULL),
(173, 1, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-20 to 2026-05-21', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 06:37:54', '2026-04-06 06:37:54', 60, NULL),
(174, 60, 'success', 'Leave Request Approved', 'Your Sick Leave request from 2026-05-20 to 2026-05-21 has been approved with replacement coverage.', 'http://localhost/RedForce/premiseOfficer/leaverequests', 'check_circle', 0, '2026-04-06 06:50:56', '2026-04-06 06:50:56', 1, NULL),
(175, 77, 'assignment', 'Temporary Leave Coverage Assigned', 'You have been assigned to cover alto from 2026-05-20 to 2026-05-21.', 'http://localhost/RedForce/premiseOfficer/schedule', 'calendar_today', 0, '2026-04-06 06:50:56', '2026-04-06 06:50:56', 1, NULL),
(176, 34, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-24 to 2026-05-26', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 07:01:47', '2026-04-06 07:01:47', 60, NULL),
(177, 33, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-24 to 2026-05-26', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 07:01:47', '2026-04-06 07:01:47', 60, NULL),
(178, 1, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-05-24 to 2026-05-26', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-06 07:01:47', '2026-04-06 07:01:47', 60, NULL),
(179, 60, 'success', 'Leave Request Approved', 'Your Sick Leave request from 2026-05-24 to 2026-05-26 has been approved with replacement coverage.', 'http://localhost/RedForce/premiseOfficer/leaverequests', 'check_circle', 0, '2026-04-06 07:02:36', '2026-04-06 07:02:36', 1, NULL),
(180, 78, 'assignment', 'Temporary Leave Coverage Assigned', 'You have been assigned to cover alto from 2026-05-24 to 2026-05-26.', 'http://localhost/RedForce/premiseOfficer/schedule', 'calendar_today', 0, '2026-04-06 07:02:36', '2026-04-06 07:02:36', 1, NULL),
(181, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,980.00. Order: ORD_69da5cf9c9b42_1775918329', '/admin/clients_payments', 'payments', 0, '2026-04-11 14:39:09', '2026-04-11 14:39:09', 40, NULL),
(182, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,980.00. Order: ORD_69da5cf9c9b42_1775918329', '/admin/clients_payments', 'payments', 0, '2026-04-11 14:39:09', '2026-04-11 14:39:09', 40, NULL),
(183, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 3,980.00. Order: ORD_69da5cf9c9b42_1775918329', '/admin/clients_payments', 'payments', 0, '2026-04-11 14:39:09', '2026-04-11 14:39:09', 40, NULL),
(184, 79, 'assignment', 'New Site Assignment', 'You have been assigned to alto (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-11 14:40:37', '2026-04-11 14:40:37', 1, NULL),
(185, 19, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to alto.', '/supervisor/dashboard', 'location_on', 0, '2026-04-11 14:40:51', '2026-04-11 14:40:51', 1, NULL),
(186, 34, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-29 to 2026-04-30', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-11 14:42:41', '2026-04-11 14:42:41', 60, NULL),
(187, 33, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-29 to 2026-04-30', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-11 14:42:41', '2026-04-11 14:42:41', 60, NULL),
(188, 1, 'info', 'New Leave Request', 'Test PO 04 (Premise Officer) submitted a leave request for Sick Leave from 2026-04-29 to 2026-04-30', 'http://localhost/RedForce/admin/pendings', 'calendar_today', 0, '2026-04-11 14:42:41', '2026-04-11 14:42:41', 60, NULL),
(189, 60, 'success', 'Leave Request Approved', 'Your Sick Leave request from 2026-04-29 to 2026-04-30 has been approved with replacement coverage.', 'http://localhost/RedForce/premiseOfficer/leaverequests', 'check_circle', 0, '2026-04-11 14:43:52', '2026-04-11 14:43:52', 1, NULL),
(190, 80, 'assignment', 'Temporary Leave Coverage Assigned', 'You have been assigned to cover alto from 2026-04-29 to 2026-04-30.', 'http://localhost/RedForce/premiseOfficer/schedule', 'calendar_today', 0, '2026-04-11 14:43:52', '2026-04-11 14:43:52', 1, NULL),
(191, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,030.00. Order: ORD_69dd2e3979cd7_1776102969', '/admin/clients_payments', 'payments', 0, '2026-04-13 17:56:44', '2026-04-13 17:56:44', 40, NULL),
(192, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,030.00. Order: ORD_69dd2e3979cd7_1776102969', '/admin/clients_payments', 'payments', 0, '2026-04-13 17:56:44', '2026-04-13 17:56:44', 40, NULL),
(193, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,030.00. Order: ORD_69dd2e3979cd7_1776102969', '/admin/clients_payments', 'payments', 0, '2026-04-13 17:56:44', '2026-04-13 17:56:44', 40, NULL),
(194, 79, 'assignment', 'New Site Assignment', 'You have been assigned to Final test (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-13 18:07:44', '2026-04-13 18:07:44', 1, NULL),
(195, 81, 'assignment', 'New Site Assignment', 'You have been assigned to Final test (Day).', '/premiseofficer/dashboard', 'location_on', 0, '2026-04-13 18:07:57', '2026-04-13 18:07:57', 1, NULL),
(196, 65, 'assignment', 'New Site Assignment', 'You have been assigned as supervisor to Final test.', '/supervisor/dashboard', 'location_on', 0, '2026-04-13 18:08:10', '2026-04-13 18:08:10', 1, NULL),
(197, 34, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,080.00. Order: ORD_69e12782608b5_1776363394', '/admin/clients_payments', 'payments', 0, '2026-04-16 18:17:36', '2026-04-16 18:17:36', 40, NULL),
(198, 33, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,080.00. Order: ORD_69e12782608b5_1776363394', '/admin/clients_payments', 'payments', 0, '2026-04-16 18:17:36', '2026-04-16 18:17:36', 40, NULL),
(199, 1, 'success', 'Payment Received', 'Example Company has made a payment of LKR 5,080.00. Order: ORD_69e12782608b5_1776363394', '/admin/clients_payments', 'payments', 0, '2026-04-16 18:17:36', '2026-04-16 18:17:36', 40, NULL),
(200, 26, 'info', 'New Message from System Administrator', 'hi', 'http://localhost/RedForce/PremiseOfficer/messages', 'message', 0, '2026-04-16 18:30:14', '2026-04-16 18:30:14', 1, NULL),
(201, 26, 'info', 'New Message from System Administrator', 'hiD', 'http://localhost/RedForce/PremiseOfficer/messages', 'message', 0, '2026-04-16 18:32:00', '2026-04-16 18:32:00', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officer_attendance`
--

CREATE TABLE `officer_attendance` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL COMMENT 'References Users table',
  `officer_id` varchar(50) NOT NULL COMMENT 'Officer ID from Users table',
  `officer_name` varchar(255) NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status` enum('Present','Absent','Late','Half Day') NOT NULL DEFAULT 'Present',
  `notes` text DEFAULT NULL,
  `duty_point` varchar(120) DEFAULT NULL,
  `staff_role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer_attendance`
--

INSERT INTO `officer_attendance` (`id`, `supervisor_id`, `officer_id`, `officer_name`, `attendance_date`, `check_in_time`, `check_out_time`, `status`, `notes`, `duty_point`, `staff_role`, `created_at`, `updated_at`) VALUES
(2, 63, 'PO914', 'Test PO 05', '2026-04-13', NULL, NULL, 'Present', '', 'Main Gate', 'Premise Officer', '2026-04-13 15:43:23', '2026-04-13 15:43:23'),
(3, 63, 'PO903', 'Test PO 903', '2026-04-13', NULL, NULL, 'Absent', 'bkjbkj', 'Back gate', 'Premise Officer', '2026-04-13 15:47:08', '2026-04-13 15:47:08'),
(4, 63, 'PO920', 'Test PO 92', '2026-04-13', NULL, NULL, 'Present', '', 'Back gate', 'Premise Officer', '2026-04-13 16:25:06', '2026-04-13 16:25:06'),
(5, 65, 'PO903', 'Test PO 903', '2026-05-14', NULL, NULL, 'Present', '', 'Main Gate', 'Premise Officer', '2026-04-13 18:13:50', '2026-04-13 18:13:50');

-- --------------------------------------------------------

--
-- Table structure for table `officer_performance_ratings`
--

CREATE TABLE `officer_performance_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `officer_user_id` int(11) NOT NULL,
  `reviewer_user_id` int(11) NOT NULL,
  `reviewer_role` enum('client','supervisor') NOT NULL,
  `rating_date` date NOT NULL,
  `rating_value` tinyint(3) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `officer_performance_ratings`
--

INSERT INTO `officer_performance_ratings` (`id`, `site_id`, `officer_user_id`, `reviewer_user_id`, `reviewer_role`, `rating_date`, `rating_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 31, 79, 40, 'client', '2026-05-01', 5, 'good', '2026-04-15 18:02:46', '2026-04-15 18:02:46'),
(3, 28, 61, 63, 'supervisor', '2026-04-15', 3, '', '2026-04-15 18:04:47', '2026-04-15 18:04:47'),
(4, 28, 68, 63, 'supervisor', '2026-04-15', 3, '', '2026-04-15 18:05:41', '2026-04-15 18:05:41'),
(5, 31, 81, 40, 'client', '2026-05-01', 5, 'b b', '2026-04-17 05:08:44', '2026-04-17 05:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `officer_site_assignments`
--

CREATE TABLE `officer_site_assignments` (
  `id` int(11) NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` int(11) NOT NULL,
  `shift_type` enum('Day','Night','Full Time','Flexible','Supervisor') DEFAULT 'Full Time',
  `assignment_start` date NOT NULL,
  `assignment_end` date DEFAULT NULL,
  `status` enum('Active','Completed','Cancelled') DEFAULT 'Active',
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_at` datetime DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer_site_assignments`
--

INSERT INTO `officer_site_assignments` (`id`, `site_id`, `officer_id`, `shift_type`, `assignment_start`, `assignment_end`, `status`, `assigned_by`, `assigned_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 8, 20, 'Night', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 10:55:39', NULL, '2026-01-27 05:25:39', '2026-01-27 05:25:54'),
(2, 5, 17, 'Day', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 11:05:19', NULL, '2026-01-27 05:35:19', '2026-01-27 08:35:55'),
(3, 15, 19, 'Day', '2026-01-27', '2026-04-06', 'Completed', 1, '2026-01-27 14:00:03', NULL, '2026-01-27 08:30:03', '2026-04-06 06:39:10'),
(4, 5, 17, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:11:25', NULL, '2026-01-27 08:41:25', '2026-01-27 08:53:57'),
(5, 5, 18, 'Day', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:20:15', NULL, '2026-01-27 08:50:15', '2026-01-27 08:57:57'),
(6, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:23:42', NULL, '2026-01-27 08:53:42', '2026-01-27 08:54:06'),
(7, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:24:29', NULL, '2026-01-27 08:54:29', '2026-01-27 08:56:24'),
(8, 5, 17, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:25:04', NULL, '2026-01-27 08:55:04', '2026-01-27 09:03:50'),
(9, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:26:38', NULL, '2026-01-27 08:56:38', '2026-01-27 08:56:53'),
(10, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:27:12', NULL, '2026-01-27 08:57:12', '2026-01-27 09:08:36'),
(11, 5, 18, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:28:23', NULL, '2026-01-27 08:58:23', '2026-01-27 09:04:37'),
(12, 5, 26, 'Day', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:31:40', NULL, '2026-01-27 09:01:40', '2026-01-27 09:08:33'),
(13, 5, 17, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:35:27', NULL, '2026-01-27 09:05:27', '2026-01-27 09:16:46'),
(14, 5, 18, 'Day', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:39:22', NULL, '2026-01-27 09:09:22', '2026-01-27 09:16:54'),
(15, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:39:36', NULL, '2026-01-27 09:09:36', '2026-01-27 09:13:43'),
(16, 5, 20, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:45:24', NULL, '2026-01-27 09:15:24', '2026-01-27 09:16:50'),
(17, 5, 17, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:47:10', NULL, '2026-01-27 09:17:10', '2026-01-27 09:24:03'),
(18, 5, 20, 'Supervisor', '2026-01-27', NULL, 'Active', 1, '2026-01-27 14:49:45', NULL, '2026-01-27 09:19:45', '2026-01-27 09:19:45'),
(19, 5, 18, 'Supervisor', '2026-01-27', '2026-01-27', 'Completed', 1, '2026-01-27 14:49:59', NULL, '2026-01-27 09:19:59', '2026-01-27 10:24:30'),
(20, 5, 17, 'Supervisor', '2026-01-27', '2026-01-30', 'Completed', 1, '2026-01-27 14:54:59', NULL, '2026-01-27 09:24:59', '2026-01-30 03:50:22'),
(21, 5, 26, 'Day', '2026-01-29', '2026-01-30', 'Completed', 1, '2026-01-29 11:20:24', NULL, '2026-01-29 05:50:24', '2026-01-30 03:51:27'),
(22, 5, 17, 'Supervisor', '2026-01-30', NULL, 'Active', 1, '2026-01-30 09:20:47', NULL, '2026-01-30 03:50:47', '2026-01-30 03:50:47'),
(23, 5, 26, 'Day', '2026-01-30', NULL, 'Active', 1, '2026-01-30 09:21:39', NULL, '2026-01-30 03:51:39', '2026-01-30 03:51:39'),
(24, 17, 18, 'Supervisor', '2026-01-30', NULL, 'Active', 1, '2026-01-30 12:33:23', NULL, '2026-01-30 07:03:23', '2026-01-30 07:03:23'),
(25, 26, 57, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 13:40:49', NULL, '2026-04-01 08:10:49', '2026-04-01 08:10:49'),
(26, 26, 58, 'Night', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 13:41:15', NULL, '2026-04-01 08:11:15', '2026-04-01 08:11:15'),
(27, 26, 59, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 13:41:29', NULL, '2026-04-01 08:11:29', '2026-04-01 08:11:29'),
(30, 28, 60, 'Day', '2026-04-01', '2026-04-02', 'Completed', 1, '2026-04-01 16:00:47', NULL, '2026-04-01 10:30:47', '2026-04-02 05:58:04'),
(31, 28, 61, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 16:00:59', NULL, '2026-04-01 10:30:59', '2026-04-01 10:30:59'),
(32, 28, 62, 'Supervisor', '2026-04-01', NULL, 'Active', 1, '2026-04-01 16:05:30', NULL, '2026-04-01 10:35:30', '2026-04-01 10:35:30'),
(33, 28, 63, 'Supervisor', '2026-04-01', '2026-04-03', 'Completed', 1, '2026-04-01 17:14:47', NULL, '2026-04-01 11:44:47', '2026-04-03 08:32:35'),
(34, 28, 69, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 17:17:15', NULL, '2026-04-01 11:47:15', '2026-04-01 11:47:15'),
(35, 28, 70, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 17:17:28', NULL, '2026-04-01 11:47:28', '2026-04-01 11:47:28'),
(36, 28, 67, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 17:17:39', NULL, '2026-04-01 11:47:39', '2026-04-01 11:47:39'),
(37, 28, 68, 'Day', '2026-04-01', '2026-05-31', 'Active', 1, '2026-04-01 17:17:50', NULL, '2026-04-01 11:47:50', '2026-04-01 11:47:50'),
(38, 28, 71, 'Day', '2026-04-02', '2026-04-02', 'Completed', 1, '2026-04-02 11:06:42', NULL, '2026-04-02 05:36:42', '2026-04-02 05:57:59'),
(39, 28, 60, 'Day', '2026-04-03', '2026-04-28', 'Active', 1, '2026-04-03 12:25:51', NULL, '2026-04-03 06:55:51', '2026-04-11 14:43:52'),
(41, 28, 71, 'Day', '2026-04-03', '2026-05-31', 'Active', 1, '2026-04-03 13:30:49', NULL, '2026-04-03 08:00:49', '2026-04-03 08:00:49'),
(44, 28, 64, 'Supervisor', '2026-04-03', NULL, 'Active', 1, '2026-04-03 13:50:50', NULL, '2026-04-03 08:20:50', '2026-04-03 08:20:50'),
(45, 28, 63, 'Supervisor', '2026-04-03', NULL, 'Active', 1, '2026-04-03 14:18:34', NULL, '2026-04-03 08:48:34', '2026-04-03 08:48:34'),
(46, 28, 65, 'Supervisor', '2026-04-03', '2026-04-11', 'Completed', 1, '2026-04-03 15:36:52', NULL, '2026-04-03 10:06:52', '2026-04-11 14:06:04'),
(47, 28, 77, 'Day', '2026-05-20', '2026-05-21', 'Active', 1, '2026-04-06 12:20:56', 'Temporary leave cover for leave request #12', '2026-04-06 06:50:56', '2026-04-06 06:50:56'),
(48, 28, 60, 'Day', '2026-05-22', '2026-04-11', 'Completed', 1, '2026-04-06 12:20:56', 'Reassignment after leave request #12', '2026-04-06 06:50:56', '2026-04-11 14:05:57'),
(49, 28, 78, 'Day', '2026-05-24', '2026-05-26', 'Active', 1, '2026-04-06 12:32:36', 'Temporary leave cover for leave request #13', '2026-04-06 07:02:36', '2026-04-06 07:02:36'),
(50, 28, 60, 'Day', '2026-05-27', '2026-05-31', 'Active', 1, '2026-04-06 12:32:36', 'Reassignment after leave request #13', '2026-04-06 07:02:36', '2026-04-06 07:02:36'),
(51, 28, 79, 'Day', '2026-04-11', '2026-04-13', 'Completed', 1, '2026-04-11 20:10:37', NULL, '2026-04-11 14:40:37', '2026-04-13 18:07:05'),
(52, 28, 19, 'Supervisor', '2026-04-11', NULL, 'Active', 1, '2026-04-11 20:10:51', NULL, '2026-04-11 14:40:51', '2026-04-11 14:40:51'),
(53, 28, 80, 'Day', '2026-04-29', '2026-04-30', 'Active', 1, '2026-04-11 20:13:52', 'Temporary leave cover for leave request #14', '2026-04-11 14:43:52', '2026-04-11 14:43:52'),
(54, 28, 60, 'Day', '2026-05-01', '2026-04-13', 'Completed', 1, '2026-04-11 20:13:52', 'Reassignment after leave request #14', '2026-04-11 14:43:52', '2026-04-13 18:07:01'),
(55, 31, 79, 'Day', '2026-04-13', '2026-05-31', 'Active', 1, '2026-04-13 23:37:44', NULL, '2026-04-13 18:07:44', '2026-04-13 18:07:44'),
(56, 31, 81, 'Day', '2026-04-13', '2026-05-31', 'Active', 1, '2026-04-13 23:37:57', NULL, '2026-04-13 18:07:57', '2026-04-13 18:07:57'),
(57, 31, 65, 'Supervisor', '2026-04-13', NULL, 'Active', 1, '2026-04-13 23:38:10', NULL, '2026-04-13 18:08:10', '2026-04-13 18:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `number_of_officers` int(11) NOT NULL DEFAULT 0,
  `number_of_supervisors` int(11) NOT NULL DEFAULT 0,
  `number_of_caretakers` int(11) NOT NULL DEFAULT 0,
  `package_price` decimal(10,2) NOT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'System default packages cannot be deleted',
  `price_per_officer` decimal(10,2) DEFAULT 0.00 COMMENT 'Price for each officer in custom packages',
  `price_per_supervisor` decimal(10,2) DEFAULT 0.00 COMMENT 'Price for each supervisor in custom packages',
  `price_per_caretaker` decimal(10,2) DEFAULT 0.00 COMMENT 'Price for each caretaker in custom packages'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `description`, `number_of_officers`, `number_of_supervisors`, `number_of_caretakers`, `package_price`, `background_image`, `status`, `created_by`, `created_at`, `updated_at`, `is_default`, `price_per_officer`, `price_per_supervisor`, `price_per_caretaker`) VALUES
(14, 'Basic Package', 'Essential security coverage for small premises with basic protection needs. Includes security officers for day shift monitoring.', 2, 0, 0, 400.00, NULL, 'Active', NULL, '2026-02-16 03:45:47', '2026-02-16 03:51:18', 0, 200.00, 250.00, 180.00),
(15, 'Standard Package', 'Comprehensive security solution for medium-sized establishments. Includes officers and a supervisor for enhanced coordination.', 3, 1, 0, 850.00, NULL, 'Active', NULL, '2026-02-16 03:45:47', '2026-02-16 03:51:22', 0, 200.00, 250.00, 180.00),
(16, 'Premium Package', 'Complete security coverage for large facilities requiring 24/7 monitoring. Full team with officers, supervisors, and caretakers.', 4, 2, 1, 1480.00, NULL, 'Inactive', NULL, '2026-02-16 03:45:47', '2026-03-09 09:34:47', 0, 200.00, 250.00, 180.00),
(17, 'Enterprise Package', 'Maximum security solution for high-risk or large-scale operations. Comprehensive team for round-the-clock protection.', 6, 2, 2, 2060.00, NULL, 'Inactive', NULL, '2026-02-16 03:45:47', '2026-02-16 03:52:17', 0, 200.00, 250.00, 180.00),
(18, 'Custom Package', 'Flexible security solution tailored to specific requirements. Personnel and pricing configured based on client needs.', 0, 0, 0, 0.00, '', 'Active', NULL, '2026-02-16 03:45:47', '2026-02-16 03:47:44', 1, 200.00, 250.00, 180.00),
(19, 'Extra Security Officer', 'Add an additional security officer to your existing package for enhanced coverage during peak hours or special requirements.', 1, 0, 0, 200.00, NULL, 'Active', NULL, '2026-02-16 03:50:08', '2026-02-16 03:52:03', 1, 200.00, 250.00, 180.00),
(20, 'Extra Supervisor', 'Add an additional supervisor to your team for improved coordination and management of security operations.', 0, 1, 0, 250.00, NULL, 'Active', NULL, '2026-02-16 03:50:08', '2026-02-16 03:52:03', 1, 200.00, 250.00, 180.00),
(21, 'Extra Caretaker', 'Add an additional caretaker to your security team for maintenance and support duties at your premises.', 0, 0, 1, 180.00, NULL, 'Active', NULL, '2026-02-16 03:50:08', '2026-02-16 03:52:03', 1, 200.00, 250.00, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `package_requests`
--

CREATE TABLE `package_requests` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `site_address` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `number_of_guards` int(11) NOT NULL,
  `day_guards` int(11) DEFAULT NULL,
  `night_guards` int(11) DEFAULT NULL,
  `package_price` decimal(10,2) NOT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `payment_id` int(11) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `submitted_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `draft_site_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_requests`
--

INSERT INTO `package_requests` (`id`, `client_id`, `package_name`, `site_name`, `city`, `district`, `site_address`, `latitude`, `longitude`, `phone_number`, `image_name`, `start_date`, `end_date`, `number_of_guards`, `day_guards`, `night_guards`, `package_price`, `comments`, `status`, `payment_status`, `payment_id`, `admin_notes`, `approved_by`, `approved_at`, `submitted_date`, `updated_at`, `draft_site_id`) VALUES
(38, 41, 'Business Package', 'Test Site B', 'Colombo', 'Colombo', '456 Park Rd, Colombo', NULL, NULL, NULL, NULL, '2026-02-23', '2026-03-25', 3, 1, 1, 7500.00, 'Paid - should show green locked', 'Pending', 'paid', 63, NULL, NULL, NULL, '2026-02-16 11:09:20', '2026-04-03 15:39:57', 30),
(45, 41, 'standardpackage', 'Darley Road', 'colombo', '', 'Darley Rd, Colombo, Sri Lanka', NULL, NULL, '', NULL, '2026-04-01', '2026-04-30', 3, 0, 0, 850.00, NULL, 'Pending', 'unpaid', NULL, NULL, NULL, NULL, '2026-03-09 15:16:29', '2026-03-09 15:16:29', NULL),
(46, 40, 'standardpackage', 'alvaroo alto', 'Colombo 12', 'Colombo', '30b Welikadawatte, Sri Jayawardenepura Kotte, Sri Lanka', 6.90393810, 79.89816441, '0743476508', '1775028872_Alvaro Aalto.jpg', '2026-05-01', '2026-05-31', 3, 0, 0, 850.00, 'Newly added site', 'Approved', 'paid', 150, NULL, 1, '2026-04-01 13:47:17', '2026-04-01 13:04:32', '2026-04-01 13:47:17', 26),
(47, 40, 'basicpackage', 'University of Ruhuna', 'Mathara', '', 'A2, Matara, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 2, 1, 0, 650.00, NULL, 'Rejected', 'paid', 151, NULL, 1, '2026-04-01 15:58:15', '2026-04-01 15:39:30', '2026-04-01 15:58:15', NULL),
(48, 40, 'basicpackage', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', 6.92866681, 79.86726537, '0743476508', '1775039384_Alvaro Aalto.jpg', '2026-05-01', '2026-05-31', 2, 1, 0, 650.00, 'Newly added site', 'Approved', 'paid', 152, NULL, 1, '2026-04-01 16:05:37', '2026-04-01 15:59:44', '2026-04-01 16:05:37', 28),
(49, 40, 'Custom Package', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 4, 1, 0, 1050.00, NULL, 'Approved', 'paid', 155, NULL, 1, '2026-04-01 16:36:22', '2026-04-01 16:21:58', '2026-04-01 16:36:22', 28),
(50, 40, 'Custom Package', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 4, 1, 0, 1050.00, NULL, 'Approved', 'paid', 161, NULL, 1, '2026-04-01 16:39:37', '2026-04-01 16:37:14', '2026-04-01 16:39:37', 28),
(51, 40, 'Custom Package', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 4, 1, 0, 1050.00, NULL, 'Approved', 'paid', 164, NULL, 1, '2026-04-01 17:17:55', '2026-04-01 16:56:00', '2026-04-01 17:17:55', 28),
(52, 40, 'extrasecurityofficer', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 1, 0, 0, 200.00, NULL, 'Approved', 'paid', 167, NULL, 1, '2026-04-02 11:07:00', '2026-04-02 11:02:01', '2026-04-02 11:07:00', 28),
(53, 40, 'extrasecurityofficer', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 1, 0, 0, 200.00, NULL, 'Approved', 'paid', 170, NULL, 1, '2026-04-03 12:25:56', '2026-04-03 12:24:33', '2026-04-03 12:25:56', 28),
(55, 40, 'extrasecurityofficer', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 1, 0, 0, 200.00, NULL, 'Approved', 'paid', 176, NULL, 1, '2026-04-03 13:31:04', '2026-04-03 13:29:27', '2026-04-03 13:31:04', 28),
(56, 40, 'extrasupervisor', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 0, 0, 0, 0.00, NULL, 'Rejected', 'paid', 179, NULL, 1, '2026-04-03 13:42:11', '2026-04-03 13:33:23', '2026-04-03 13:42:11', NULL),
(57, 40, 'extrasupervisor', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 0, 0, 0, 0.00, NULL, 'Rejected', 'paid', 182, NULL, 1, '2026-04-03 14:05:56', '2026-04-03 13:49:51', '2026-04-03 14:05:56', NULL),
(58, 40, 'extrasupervisor', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 0, 0, 0, 0.00, NULL, 'Approved', 'paid', 185, NULL, 1, '2026-04-03 14:18:38', '2026-04-03 14:06:16', '2026-04-03 14:18:38', 28),
(59, 40, 'extrasupervisor', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 0, 0, 0, 0.00, NULL, 'Approved', 'paid', 188, NULL, 1, '2026-04-03 15:38:04', '2026-04-03 15:35:45', '2026-04-03 15:38:04', 28),
(60, 40, 'extracaretaker', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 0, 0, 0, 0.00, NULL, 'Approved', 'paid', 191, NULL, 1, '2026-04-06 10:29:51', '2026-04-06 10:27:48', '2026-04-06 10:29:51', 28),
(61, 40, 'Custom Package', 'alto', 'Colombo 8', 'Colombo', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', NULL, NULL, '', NULL, '2026-05-01', '2026-05-31', 1, 1, 0, 450.00, NULL, 'Approved', 'paid', 200, NULL, 1, '2026-04-11 20:11:00', '2026-04-11 20:06:08', '2026-04-11 20:11:00', 28),
(62, 40, 'basicpackage', 'Final test', 'Colombo 10', 'Colombo', 'WVFH+HP7, Colombo 01000, Sri Lanka', 6.92464082, 79.87953918, '0743476508', '1776102960_9 - Image-Led Storytelling.jpg', '2026-05-01', '2026-05-31', 2, 1, 0, 650.00, 'Newly added site', 'Approved', 'paid', 203, NULL, 1, '2026-04-13 23:41:57', '2026-04-13 23:26:00', '2026-04-13 23:41:57', 31),
(63, 40, 'Custom Package', 'Hello', 'Colombo 10', 'Colombo', 'WRQP+32 Port City Colombo, Sri Lanka', 6.93769767, 79.83504229, '0743476508', '1776363377_9 - Image-Led Storytelling.jpg', '2026-05-01', '2026-05-31', 1, 1, 0, 450.00, 'Newly added site', 'Pending', 'paid', 207, NULL, NULL, NULL, '2026-04-16 23:46:17', '2026-04-16 23:47:52', 32);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_request_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'e.g., bank_transfer, cash, card, online',
  `transaction_reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `client_id`, `site_id`, `package_request_id`, `invoice_number`, `amount`, `description`, `payment_date`, `due_date`, `status`, `payment_method`, `transaction_reference`, `created_at`, `updated_at`) VALUES
(25, 41, 17, NULL, 'ORD_69929c20092e8_1771215904-SITE-17', 250.00, 'Monthly Security Service Payment - Colombo City Centre Mall and Residences', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216324', '2026-02-16 04:25:04', '2026-02-16 04:32:04'),
(26, 41, 5, NULL, 'ORD_69929c20092e8_1771215904-SITE-5', 1060.00, 'Monthly Security Service Payment - Darley Road', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216324', '2026-02-16 04:25:04', '2026-02-16 04:32:04'),
(27, 41, 15, NULL, 'ORD_69929c20092e8_1771215904-SITE-15', 200.00, 'Monthly Security Service Payment - Jafferjee Brothers', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216324', '2026-02-16 04:25:04', '2026-02-16 04:32:04'),
(28, 41, 17, NULL, 'ORD_69929cb648b43_1771216054-SITE-17', 250.00, 'Monthly Security Service Payment - Colombo City Centre Mall and Residences', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216296', '2026-02-16 04:27:34', '2026-02-16 04:31:36'),
(29, 41, 5, NULL, 'ORD_69929cb648b43_1771216054-SITE-5', 1060.00, 'Monthly Security Service Payment - Darley Road', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216296', '2026-02-16 04:27:34', '2026-02-16 04:31:36'),
(30, 41, 15, NULL, 'ORD_69929cb648b43_1771216054-SITE-15', 200.00, 'Monthly Security Service Payment - Jafferjee Brothers', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'TEST_1771216296', '2026-02-16 04:27:34', '2026-02-16 04:31:36'),
(34, 41, NULL, 32, 'ORD_69929e03aa5d1_1771216387-REQ-32', 6390.00, 'Package Request Payment - Custom Package for Darley Road', '2026-02-16', '2026-03-18', 'paid', 'PayHere Online', 'ORD_69929e03aa5d1_1771216387', '2026-02-16 04:33:07', '2026-02-16 04:41:39'),
(63, 41, NULL, 38, 'INV_TEST_1771220360', 7500.00, 'Test paid package request', '2026-02-16', NULL, 'paid', 'PayHere Online', NULL, '2026-02-16 05:39:20', '2026-02-16 05:39:20'),
(138, 41, 17, NULL, 'ORD_69ad6e55c5f2d_1772973653-SITE-17', 250.00, 'Monthly Security Service Payment - Colombo City Centre Mall and Residences', '2026-03-08', '2026-04-07', 'paid', 'PayHere Online', 'ORD_69ad6e55c5f2d_1772973653', '2026-03-08 12:40:53', '2026-03-08 12:41:25'),
(139, 41, 5, NULL, 'ORD_69ad6e55c5f2d_1772973653-SITE-5', 1060.00, 'Monthly Security Service Payment - Darley Road', '2026-03-08', '2026-04-07', 'paid', 'PayHere Online', 'ORD_69ad6e55c5f2d_1772973653', '2026-03-08 12:40:53', '2026-03-08 12:41:25'),
(140, 41, 15, NULL, 'ORD_69ad6e55c5f2d_1772973653-SITE-15', 200.00, 'Monthly Security Service Payment - Jafferjee Brothers', '2026-03-08', '2026-04-07', 'paid', 'PayHere Online', 'ORD_69ad6e55c5f2d_1772973653', '2026-03-08 12:40:53', '2026-03-08 12:41:25'),
(141, 41, NULL, 43, 'ORD_69ad6e55c5f2d_1772973653-REQ-43', 200.00, 'Package Request Payment - extrasecurityofficer for Darley Road', '2026-03-08', '2026-04-07', 'paid', 'PayHere Online', 'ORD_69ad6e55c5f2d_1772973653', '2026-03-08 12:40:53', '2026-03-08 12:41:25'),
(146, 41, 17, NULL, 'ORD_69ae97cd0605c_1773049805-SITE-17', 250.00, 'Monthly Security Service Payment - Colombo City Centre Mall and Residences', '2026-03-09', '2026-04-08', 'pending', 'PayHere Online', 'ORD_69ae97cd0605c_1773049805', '2026-03-09 09:50:05', '2026-03-09 09:50:05'),
(147, 41, 5, NULL, 'ORD_69ae97cd0605c_1773049805-SITE-5', 1060.00, 'Monthly Security Service Payment - Darley Road', '2026-03-09', '2026-04-08', 'pending', 'PayHere Online', 'ORD_69ae97cd0605c_1773049805', '2026-03-09 09:50:05', '2026-03-09 09:50:05'),
(148, 41, 15, NULL, 'ORD_69ae97cd0605c_1773049805-SITE-15', 200.00, 'Monthly Security Service Payment - Jafferjee Brothers', '2026-03-09', '2026-04-08', 'pending', 'PayHere Online', 'ORD_69ae97cd0605c_1773049805', '2026-03-09 09:50:05', '2026-03-09 09:50:05'),
(149, 41, NULL, 45, 'ORD_69ae97cd0605c_1773049805-REQ-45', 850.00, 'Package Request Payment - standardpackage for Darley Road', '2026-03-09', '2026-04-08', 'pending', 'PayHere Online', 'ORD_69ae97cd0605c_1773049805', '2026-03-09 09:50:05', '2026-03-09 09:50:05'),
(150, 1, NULL, 46, 'ORD_69cccb0b4d464_1775029003-REQ-46', 850.00, 'Package Request Payment - standardpackage for alvaroo alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69cccb0b4d464_1775029003', '2026-04-01 07:36:43', '2026-04-01 07:43:18'),
(151, 40, NULL, 47, 'ORD_69ccef073b9e2_1775038215-REQ-47', 650.00, 'Package Request Payment - basicpackage for University of Ruhuna', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccef073b9e2_1775038215', '2026-04-01 10:10:15', '2026-04-01 10:10:47'),
(152, 40, NULL, 48, 'ORD_69ccf3a56cb81_1775039397-REQ-48', 650.00, 'Package Request Payment - basicpackage for alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccf3a56cb81_1775039397', '2026-04-01 10:29:57', '2026-04-01 10:30:17'),
(153, 40, 28, NULL, 'ORD_69ccf8d8c3034_1775040728-SITE-28', 650.00, 'Monthly Security Service Payment - alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccf8d8c3034_1775040728', '2026-04-01 10:52:08', '2026-04-01 10:52:37'),
(154, 40, 26, NULL, 'ORD_69ccf8d8c3034_1775040728-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccf8d8c3034_1775040728', '2026-04-01 10:52:08', '2026-04-01 10:52:37'),
(155, 40, NULL, 49, 'ORD_69ccf8d8c3034_1775040728-REQ-49', 1050.00, 'Package Request Payment - Custom Package for alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccf8d8c3034_1775040728', '2026-04-01 10:52:08', '2026-04-01 10:52:37'),
(159, 40, 28, NULL, 'ORD_69ccfcb20f15f_1775041714-SITE-28', 650.00, 'Monthly Security Service Payment - alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccfcb20f15f_1775041714', '2026-04-01 11:08:34', '2026-04-01 11:09:00'),
(160, 40, 26, NULL, 'ORD_69ccfcb20f15f_1775041714-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccfcb20f15f_1775041714', '2026-04-01 11:08:34', '2026-04-01 11:09:00'),
(161, 40, 28, 50, 'ORD_69ccfcb20f15f_1775041714-REQ-50', 1050.00, 'Package Request Payment - Custom Package for alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69ccfcb20f15f_1775041714', '2026-04-01 11:08:34', '2026-04-01 11:09:00'),
(162, 40, 28, NULL, 'ORD_69cd00cc6b2e9_1775042764-SITE-28', 650.00, 'Monthly Security Service Payment - alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69cd00cc6b2e9_1775042764', '2026-04-01 11:26:04', '2026-04-01 11:26:33'),
(163, 40, 26, NULL, 'ORD_69cd00cc6b2e9_1775042764-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69cd00cc6b2e9_1775042764', '2026-04-01 11:26:04', '2026-04-01 11:26:33'),
(164, 40, 28, 51, 'ORD_69cd00cc6b2e9_1775042764-REQ-51', 1050.00, 'Package Request Payment - Custom Package for alto', '2026-04-01', '2026-05-01', 'paid', 'PayHere Online', 'ORD_69cd00cc6b2e9_1775042764', '2026-04-01 11:26:04', '2026-04-01 11:26:33'),
(165, 40, 28, NULL, 'ORD_69ce0017ad976_1775108119-SITE-28', 1700.00, 'Monthly Security Service Payment - alto', '2026-04-02', '2026-05-02', 'paid', 'PayHere Online', 'ORD_69ce0017ad976_1775108119', '2026-04-02 05:35:19', '2026-04-02 05:36:03'),
(166, 40, 26, NULL, 'ORD_69ce0017ad976_1775108119-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-02', '2026-05-02', 'paid', 'PayHere Online', 'ORD_69ce0017ad976_1775108119', '2026-04-02 05:35:19', '2026-04-02 05:36:03'),
(167, 40, 28, 52, 'ORD_69ce0017ad976_1775108119-REQ-52', 200.00, 'Package Request Payment - extrasecurityofficer for alto', '2026-04-02', '2026-05-02', 'paid', 'PayHere Online', 'ORD_69ce0017ad976_1775108119', '2026-04-02 05:35:19', '2026-04-02 05:36:03'),
(168, 40, 28, NULL, 'ORD_69cf64314a66b_1775199281-SITE-28', 1500.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf64314a66b_1775199281', '2026-04-03 06:54:41', '2026-04-03 06:55:23'),
(169, 40, 26, NULL, 'ORD_69cf64314a66b_1775199281-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf64314a66b_1775199281', '2026-04-03 06:54:41', '2026-04-03 06:55:23'),
(170, 40, 28, 53, 'ORD_69cf64314a66b_1775199281-REQ-53', 200.00, 'Package Request Payment - extrasecurityofficer for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf64314a66b_1775199281', '2026-04-03 06:54:41', '2026-04-03 06:55:23'),
(171, 40, 28, NULL, 'ORD_69cf6512716fc_1775199506-SITE-28', 1700.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf6512716fc_1775199506', '2026-04-03 06:58:26', '2026-04-03 06:58:46'),
(172, 40, 26, NULL, 'ORD_69cf6512716fc_1775199506-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf6512716fc_1775199506', '2026-04-03 06:58:26', '2026-04-03 06:58:46'),
(173, 40, 28, 54, 'ORD_69cf6512716fc_1775199506-REQ-54', 200.00, 'Package Request Payment - extrasecurityofficer for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf6512716fc_1775199506', '2026-04-03 06:58:26', '2026-04-03 06:58:46'),
(174, 40, 28, NULL, 'ORD_69cf7364a08cc_1775203172-SITE-28', 1700.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7364a08cc_1775203172', '2026-04-03 07:59:32', '2026-04-03 08:00:16'),
(175, 40, 26, NULL, 'ORD_69cf7364a08cc_1775203172-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7364a08cc_1775203172', '2026-04-03 07:59:32', '2026-04-03 08:00:16'),
(176, 40, 28, 55, 'ORD_69cf7364a08cc_1775203172-REQ-55', 200.00, 'Package Request Payment - extrasecurityofficer for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7364a08cc_1775203172', '2026-04-03 07:59:32', '2026-04-03 08:00:16'),
(177, 40, 28, NULL, 'ORD_69cf7450346be_1775203408-SITE-28', 1900.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7450346be_1775203408', '2026-04-03 08:03:28', '2026-04-03 08:03:47'),
(178, 40, 26, NULL, 'ORD_69cf7450346be_1775203408-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7450346be_1775203408', '2026-04-03 08:03:28', '2026-04-03 08:03:47'),
(179, 40, 28, 56, 'ORD_69cf7450346be_1775203408-REQ-56', 0.00, 'Package Request Payment - extrasupervisor for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7450346be_1775203408', '2026-04-03 08:03:28', '2026-04-03 08:03:47'),
(180, 40, 28, NULL, 'ORD_69cf782ad7b29_1775204394-SITE-28', 1900.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf782ad7b29_1775204394', '2026-04-03 08:19:54', '2026-04-03 08:20:20'),
(181, 40, 26, NULL, 'ORD_69cf782ad7b29_1775204394-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf782ad7b29_1775204394', '2026-04-03 08:19:54', '2026-04-03 08:20:20'),
(182, 40, 28, 57, 'ORD_69cf782ad7b29_1775204394-REQ-57', 0.00, 'Package Request Payment - extrasupervisor for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf782ad7b29_1775204394', '2026-04-03 08:19:54', '2026-04-03 08:20:20'),
(183, 40, 28, NULL, 'ORD_69cf7c0416ba3_1775205380-SITE-28', 1900.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7c0416ba3_1775205380', '2026-04-03 08:36:20', '2026-04-03 08:36:49'),
(184, 40, 26, NULL, 'ORD_69cf7c0416ba3_1775205380-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7c0416ba3_1775205380', '2026-04-03 08:36:20', '2026-04-03 08:36:49'),
(185, 40, 28, 58, 'ORD_69cf7c0416ba3_1775205380-REQ-58', 0.00, 'Package Request Payment - extrasupervisor for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf7c0416ba3_1775205380', '2026-04-03 08:36:20', '2026-04-03 08:36:49'),
(186, 40, 28, NULL, 'ORD_69cf90fc5b5a3_1775210748-SITE-28', 2150.00, 'Monthly Security Service Payment - alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf90fc5b5a3_1775210748', '2026-04-03 10:05:48', '2026-04-03 10:06:20'),
(187, 40, 26, NULL, 'ORD_69cf90fc5b5a3_1775210748-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf90fc5b5a3_1775210748', '2026-04-03 10:05:48', '2026-04-03 10:06:20'),
(188, 40, 28, 59, 'ORD_69cf90fc5b5a3_1775210748-REQ-59', 0.00, 'Package Request Payment - extrasupervisor for alto', '2026-04-03', '2026-05-03', 'paid', 'PayHere Online', 'ORD_69cf90fc5b5a3_1775210748', '2026-04-03 10:05:48', '2026-04-03 10:06:20'),
(189, 40, 28, NULL, 'ORD_69d33d5aebcd2_1775451482-SITE-28', 2400.00, 'Monthly Security Service Payment - alto', '2026-04-06', '2026-05-06', 'paid', 'PayHere Online', 'ORD_69d33d5aebcd2_1775451482', '2026-04-06 04:58:02', '2026-04-06 04:58:51'),
(190, 40, 26, NULL, 'ORD_69d33d5aebcd2_1775451482-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-06', '2026-05-06', 'paid', 'PayHere Online', 'ORD_69d33d5aebcd2_1775451482', '2026-04-06 04:58:02', '2026-04-06 04:58:51'),
(191, 40, 28, 60, 'ORD_69d33d5aebcd2_1775451482-REQ-60', 0.00, 'Package Request Payment - extracaretaker for alto', '2026-04-06', '2026-05-06', 'paid', 'PayHere Online', 'ORD_69d33d5aebcd2_1775451482', '2026-04-06 04:58:02', '2026-04-06 04:58:51'),
(198, 40, 28, NULL, 'ORD_69da5cf9c9b42_1775918329-SITE-28', 2930.00, 'Monthly Security Service Payment - alto', '2026-04-11', '2026-05-11', 'paid', 'PayHere Online', 'ORD_69da5cf9c9b42_1775918329', '2026-04-11 14:38:49', '2026-04-11 14:39:09'),
(199, 40, 26, NULL, 'ORD_69da5cf9c9b42_1775918329-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-11', '2026-05-11', 'paid', 'PayHere Online', 'ORD_69da5cf9c9b42_1775918329', '2026-04-11 14:38:49', '2026-04-11 14:39:09'),
(200, 40, 28, 61, 'ORD_69da5cf9c9b42_1775918329-REQ-61', 450.00, 'Package Request Payment - Custom Package for alto', '2026-04-11', '2026-05-11', 'paid', 'PayHere Online', 'ORD_69da5cf9c9b42_1775918329', '2026-04-11 14:38:49', '2026-04-11 14:39:09'),
(201, 40, 28, NULL, 'ORD_69dd2e3979cd7_1776102969-SITE-28', 3780.00, 'Monthly Security Service Payment - alto', '2026-04-13', '2026-05-13', 'paid', 'PayHere Online', 'ORD_69dd2e3979cd7_1776102969', '2026-04-13 17:56:09', '2026-04-13 17:56:44'),
(202, 40, 26, NULL, 'ORD_69dd2e3979cd7_1776102969-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-13', '2026-05-13', 'paid', 'PayHere Online', 'ORD_69dd2e3979cd7_1776102969', '2026-04-13 17:56:09', '2026-04-13 17:56:44'),
(203, 40, NULL, 62, 'ORD_69dd2e3979cd7_1776102969-REQ-62', 650.00, 'Package Request Payment - basicpackage for Final test', '2026-04-13', '2026-05-13', 'paid', 'PayHere Online', 'ORD_69dd2e3979cd7_1776102969', '2026-04-13 17:56:09', '2026-04-13 17:56:44'),
(204, 40, 28, NULL, 'ORD_69e12782608b5_1776363394-SITE-28', 3380.00, 'Monthly Security Service Payment - alto', '2026-04-16', '2026-05-16', 'paid', 'PayHere Online', 'ORD_69e12782608b5_1776363394', '2026-04-16 18:16:34', '2026-04-16 18:17:36'),
(205, 40, 26, NULL, 'ORD_69e12782608b5_1776363394-SITE-26', 600.00, 'Monthly Security Service Payment - alvaroo alto', '2026-04-16', '2026-05-16', 'paid', 'PayHere Online', 'ORD_69e12782608b5_1776363394', '2026-04-16 18:16:34', '2026-04-16 18:17:36'),
(206, 40, 31, NULL, 'ORD_69e12782608b5_1776363394-SITE-31', 650.00, 'Monthly Security Service Payment - Final test', '2026-04-16', '2026-05-16', 'paid', 'PayHere Online', 'ORD_69e12782608b5_1776363394', '2026-04-16 18:16:34', '2026-04-16 18:17:36'),
(207, 40, NULL, 63, 'ORD_69e12782608b5_1776363394-REQ-63', 450.00, 'Package Request Payment - Custom Package for Hello', '2026-04-16', '2026-05-16', 'paid', 'PayHere Online', 'ORD_69e12782608b5_1776363394', '2026-04-16 18:16:34', '2026-04-16 18:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `premise_officers`
--

CREATE TABLE `premise_officers` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `officerID` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `NIC` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `employment_status` enum('Active','On Leave','Terminated','Suspended') DEFAULT 'Active',
  `rank` enum('Junior','Senior','Supervisor') DEFAULT 'Junior',
  `rating` decimal(3,2) DEFAULT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `shift_pattern` varchar(50) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `premise_officers`
--

INSERT INTO `premise_officers` (`id`, `userID`, `officerID`, `date_of_birth`, `NIC`, `gender`, `address`, `district`, `city`, `hire_date`, `employment_status`, `rank`, `rating`, `shift_pattern`, `application_id`, `created_at`, `updated_at`) VALUES
(1, 17, 'PO003', '1996-02-03', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Damana', '2025-12-24', 'On Leave', 'Supervisor', NULL, NULL, NULL, '2025-12-24 05:16:13', '2026-01-27 07:10:01'),
(2, 18, 'PO004', '2002-06-11', '200227901779', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Galle', 'Yakkalamulla', '2025-12-29', 'Active', 'Supervisor', NULL, NULL, NULL, '2025-12-29 04:00:07', '2026-01-27 08:42:21'),
(3, 19, 'PO005', '2010-06-23', '901234567', 'Male', 'No. 45, Galle Road, Colombo 03', 'Colombo', 'Colombo 3', '2025-12-29', 'Active', 'Supervisor', NULL, NULL, NULL, '2025-12-29 06:26:25', '2026-02-01 15:28:27'),
(4, 20, 'PO006', '2025-12-24', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Trincomalee', 'Eachchilampattai', '2025-12-30', 'Active', 'Supervisor', NULL, NULL, NULL, '2025-12-30 08:37:51', '2026-01-27 08:53:13'),
(5, 26, 'PO007', '2026-01-15', '198022786336', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Trincomalee', 'Kanniya', '2026-01-02', 'Active', 'Junior', NULL, NULL, NULL, '2026-01-02 01:51:07', '2026-01-02 01:51:07'),
(6, 57, 'PO910', '1995-01-10', '951001910V', 'Male', 'No 10, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Junior', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(7, 58, 'PO911', '1994-02-11', '941001911V', 'Female', 'No 11, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Senior', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(8, 59, 'PO912', '1993-03-12', '931001912V', 'Male', 'No 12, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Junior', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(9, 60, 'PO913', '1992-04-13', '921001913V', 'Female', 'No 13, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Senior', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(10, 61, 'PO914', '1991-05-14', '911001914V', 'Male', 'No 14, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Junior', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(11, 62, 'PO915', '1989-06-15', '891001915V', 'Male', 'No 15, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(12, 63, 'PO916', '1988-07-16', '881001916V', 'Female', 'No 16, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(13, 64, 'PO917', '1987-08-17', '871001917V', 'Male', 'No 17, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(14, 65, 'PO918', '1986-09-18', '861001918V', 'Female', 'No 18, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(15, 66, 'PO919', '1985-10-19', '851001919V', 'Male', 'No 19, Test Road', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', NULL, NULL, NULL, '2026-04-01 08:10:22', '2026-04-01 08:10:22'),
(16, 67, 'PO920', '1995-01-01', 'TESTNIC920.00', 'Male', 'Test Address 1', 'Galle', 'Galle', '2026-04-01', 'Active', 'Junior', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(17, 68, 'PO921', '1995-01-01', 'TESTNIC921.00', 'Male', 'Test Address 2', 'Galle', 'Galle', '2026-04-01', 'Active', 'Junior', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(18, 69, 'PO922', '1995-01-01', 'TESTNIC922.00', 'Male', 'Test Address 3', 'Colombo', 'Colombo', '2026-04-01', 'Active', 'Junior', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(19, 70, 'PO923', '1995-01-01', 'TESTNIC923.00', 'Male', 'Test Address 4', 'Colombo', 'Colombo', '2026-04-01', 'Active', 'Junior', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(20, 71, 'PO924', '1995-01-01', 'TESTNIC924.00', 'Male', 'Test Address 5', 'Kandy', 'Kandy', '2026-04-01', 'Active', 'Junior', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(21, 72, 'PO925', '1995-01-01', 'TESTNIC925.00', 'Male', 'Test Address 6', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(22, 73, 'PO926', '1995-01-01', 'TESTNIC926.00', 'Male', 'Test Address 7', 'Galle', 'Galle', '2026-04-01', 'Active', 'Supervisor', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(23, 74, 'PO927', '1995-01-01', 'TESTNIC927.00', 'Male', 'Test Address 8', 'Colombo', 'Colombo', '2026-04-01', 'Active', 'Supervisor', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(24, 75, 'PO928', '1995-01-01', 'TESTNIC928.00', 'Male', 'Test Address 9', 'Colombo', 'Colombo', '2026-04-01', 'Active', 'Supervisor', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(25, 76, 'PO929', '1995-01-01', 'TESTNIC929.00', 'Male', 'Test Address 10', 'Kandy', 'Kandy', '2026-04-01', 'Active', 'Supervisor', 4.50, 'Full Time', NULL, '2026-04-01 11:46:58', '2026-04-01 11:46:58'),
(26, 77, 'PO901', '1998-01-11', '199801110901', 'Male', 'No 101, Test Lane', 'Colombo', 'Colombo 3', '2026-04-06', 'Active', 'Junior', NULL, 'Day', NULL, '2026-04-06 06:49:57', '2026-04-06 06:49:57'),
(27, 78, 'PO902', '1997-03-14', '199703140902', 'Female', 'No 102, Test Lane', 'Colombo', 'Nugegoda', '2026-04-06', 'Active', 'Junior', NULL, 'Day', NULL, '2026-04-06 06:49:57', '2026-04-06 06:49:57'),
(28, 79, 'PO903', '1999-05-21', '199905210903', 'Male', 'No 103, Test Lane', 'Gampaha', 'Negombo', '2026-04-06', 'Active', 'Junior', NULL, 'Day', NULL, '2026-04-06 06:49:57', '2026-04-06 06:49:57'),
(29, 80, 'PO904', '1996-08-09', '199608090904', 'Female', 'No 104, Test Lane', 'Kandy', 'Kandy', '2026-04-06', 'Active', 'Junior', NULL, 'Day', NULL, '2026-04-06 06:49:57', '2026-04-06 06:49:57'),
(30, 81, 'PO905', '2000-11-30', '200011300905', 'Male', 'No 105, Test Lane', 'Kalutara', 'Panadura', '2026-04-06', 'Active', 'Junior', NULL, 'Day', NULL, '2026-04-06 06:49:57', '2026-04-06 06:49:57');

-- --------------------------------------------------------

--
-- Stand-in structure for view `premise_officers_full_details`
-- (See below for the actual view)
--
CREATE TABLE `premise_officers_full_details` (
`premise_officer_id` int(11)
,`officerID` varchar(50)
,`date_of_birth` date
,`NIC` varchar(20)
,`gender` enum('Male','Female','Other')
,`address` text
,`district` varchar(50)
,`city` varchar(50)
,`hire_date` date
,`employment_status` enum('Active','On Leave','Terminated','Suspended')
,`rank` enum('Junior','Senior','Supervisor')
,`rating` decimal(3,2)
,`shift_pattern` varchar(50)
,`application_id` int(11)
,`officer_record_created` timestamp
,`officer_record_updated` timestamp
,`user_id` int(11)
,`user_identifier` varchar(50)
,`name` varchar(255)
,`email` varchar(255)
,`role` enum('admin','supervisor','premise officer','mobile rider','client','caretaker')
,`phone_number` varchar(50)
,`profile_image` varchar(255)
,`user_status` enum('active','inactive','suspended')
,`user_account_created` timestamp
,`user_account_updated` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `recent_activities`
--

CREATE TABLE `recent_activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(500) NOT NULL,
  `activity_titel` varchar(500) DEFAULT NULL,
  `activity_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recent_activities`
--

INSERT INTO `recent_activities` (`id`, `user_id`, `activity_type`, `activity_titel`, `activity_details`, `created_at`) VALUES
(11, 3, 'registration', 'New Officer Registered', 'Officer John Doe has been registered to the system', '2026-01-01 08:20:59'),
(12, 5, 'shift', 'Shift Assigned', 'Officer Sarah Smith assigned to Day Shift (08:00 - 16:00) at Central Station', '2026-01-01 08:20:59'),
(13, 7, 'leave', 'Leave Request Submitted', 'Officer Mike Johnson submitted annual leave request for 5 days', '2026-01-01 08:20:59'),
(14, 2, 'alert', 'Emergency Alert Sent', 'High priority alert about traffic disruption on Main Street sent to all officers', '2026-01-01 08:20:59'),
(15, 8, 'incident', 'New Incident Reported', 'Report #IN-2024-045: Traffic accident at Downtown intersection', '2026-01-01 08:20:59'),
(16, 4, 'assignment', 'Patrol Duty Assigned', 'Assigned patrol route Downtown-01 to Officer Robert Brown', '2026-01-01 08:20:59'),
(17, 6, 'update', 'Profile Information Updated', 'Officer Lisa White updated contact information and emergency contacts', '2026-01-01 08:20:59'),
(18, 9, 'update', 'Training Completed', 'Officer David Lee completed Advanced First Aid Training course', '2026-01-01 08:20:59'),
(19, 3, 'assignment', 'Equipment Assigned', 'Assigned patrol car #PD-205 and standard equipment kit to Officer James Wilson', '2026-01-01 08:20:59'),
(20, 1, 'leave', 'Leave Request Approved', 'Approved medical leave for Officer Emma Davis from Jan 15-20, 2024', '2026-01-01 08:20:59'),
(21, 1, 'registration', 'Job Application Created', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:10:06'),
(22, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08(Not Completed)', '2026-01-01 10:11:19'),
(23, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:11:43'),
(24, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:12:41'),
(25, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:13:00'),
(26, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08wefewffwfe regregrewgewrgwregew g egregregegwerg e gergreg(Not Completed)', '2026-01-01 10:20:10'),
(27, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:20:29'),
(28, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:20:34'),
(29, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:48'),
(30, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:49'),
(31, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:50'),
(32, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:50'),
(33, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:51'),
(34, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 10:21:52'),
(35, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:38'),
(36, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:39'),
(37, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:40'),
(38, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:41'),
(39, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:42'),
(40, 1, 'registration', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-10', '2026-01-01 10:32:43'),
(41, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08 (Not Completed)', '2026-01-01 15:37:30'),
(42, 1, 'registration', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 15:37:37'),
(43, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 15:39:30'),
(44, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-01-09', '2026-01-01 15:42:48'),
(45, 1, 'alert', 'Client Rejected', 'Client #2 registration was rejected', '2026-01-01 15:56:58'),
(46, 1, 'alert', 'Client Request Deleted', 'Client request #2 was permanently deleted', '2026-01-01 15:57:21'),
(47, 1, 'alert', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-01 15:58:39'),
(48, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 15:58:39'),
(49, 1, 'alert', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-01 15:59:13'),
(50, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 15:59:13'),
(51, 1, 'alert', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-01 15:59:46'),
(52, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 15:59:46'),
(53, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08 (Not Completed)', '2026-01-01 16:00:06'),
(54, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 16:00:11'),
(55, 1, 'alert', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-01 16:00:15'),
(56, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-01 16:00:15'),
(57, 1, 'shift', 'New Site Added', 'Site \'afegrwtehtrhyrhy\' added for client ID: 10', '2026-01-01 16:18:19'),
(58, 1, 'alert', 'Site Deleted', 'Site \'Galkiriyagama Branch\' (ID: 1) was deleted', '2026-01-01 16:18:54'),
(59, 1, 'alert', 'Site Deleted', 'Site \'afegrwtehtrhyrhy\' (ID: 2) was deleted', '2026-01-01 16:19:20'),
(60, 1, 'registration', 'Officer Application Accepted', 'Premise Officer application #14 was approved', '2026-01-02 01:51:07'),
(61, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-08', '2026-01-02 04:20:16'),
(62, 1, 'registration', 'Officer Application Accepted', 'Care Taker application #15 was approved', '2026-01-02 04:20:24'),
(63, 1, 'updregistrationate', 'Client Accepted', 'Client #3 registration was approved', '2026-01-02 06:37:23'),
(64, 1, 'registration', 'Officer Application Accepted', 'Mobile Rider application #17 was approved', '2026-01-02 06:43:01'),
(65, 1, 'updregistrationate', 'Client Accepted', 'Client #5 registration was approved', '2026-01-07 03:26:30'),
(66, 1, 'updregistrationate', 'Client Accepted', 'Client #5 registration was approved', '2026-01-07 03:36:12'),
(67, 1, 'shift', 'New Site Added', 'Site \'rewfwe\' added for client ID: 31', '2026-01-07 03:40:53'),
(68, 1, 'alert', 'Site Deleted', 'Site \'rewfwe\' (ID: 3) was deleted', '2026-01-07 03:41:27'),
(69, 1, 'updregistrationate', 'Client Accepted', 'Client #5 registration was approved', '2026-01-07 04:06:10'),
(70, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithanage\' added for client ID: ', '2026-01-07 10:21:39'),
(71, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithanage\' added', '2026-01-07 10:31:19'),
(72, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithanage\' added', '2026-01-07 10:33:46'),
(73, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithanage\' added', '2026-01-07 11:23:01'),
(74, 1, 'alert', 'Officer Application Deleted', 'Officer application #4 was permanently deleted', '2026-01-17 03:08:18'),
(75, 1, 'shift', 'New Site Added', 'Site \'Colombo City Centre Mall and Residences\' added for client ID: 32', '2026-01-21 11:15:17'),
(76, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:17:53'),
(77, 1, 'leave', 'Job Application Status Changed', 'Mobile Rider job application status changed to: closed', '2026-01-21 11:18:34'),
(78, 1, 'message', 'Job Application Status Changed', 'Mobile Rider job application status changed to: open', '2026-01-21 11:18:39'),
(79, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:19:08'),
(80, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:24:53'),
(81, 1, 'leave', 'Job Application Status Changed', 'Mobile Rider job application status changed to: closed', '2026-01-21 11:25:00'),
(82, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:25:37'),
(83, 1, 'message', 'Job Application Status Changed', 'Mobile Rider job application status changed to: open', '2026-01-21 11:26:13'),
(84, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:29:40'),
(85, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:29:45'),
(86, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:31:42'),
(87, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:32:19'),
(88, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:34:36'),
(89, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:34:49'),
(90, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:36:56'),
(91, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:16'),
(92, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:16'),
(93, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:16'),
(94, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(95, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(96, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(97, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(98, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(99, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(100, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(101, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:17'),
(102, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:18'),
(103, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:18'),
(104, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:39:23'),
(105, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:39:26'),
(106, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:39:27'),
(107, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:39:27'),
(108, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:29'),
(109, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:30'),
(110, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:30'),
(111, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:30'),
(112, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:30'),
(113, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:39:32'),
(114, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(115, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(116, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(117, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(118, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(119, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(120, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:32'),
(121, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:36'),
(122, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:37'),
(123, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:37'),
(124, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:37'),
(125, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:38'),
(126, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:38'),
(127, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:40:38'),
(128, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:42'),
(129, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:43'),
(130, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:43'),
(131, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:43'),
(132, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:43'),
(133, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:40:43'),
(134, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:57'),
(135, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:57'),
(136, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:58'),
(137, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:58'),
(138, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:58'),
(139, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:58'),
(140, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:41:59'),
(141, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:10'),
(142, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:11'),
(143, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:11'),
(144, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:11'),
(145, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:11'),
(146, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:42:11'),
(147, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:14'),
(148, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:15'),
(149, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:15'),
(150, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:15'),
(151, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:15'),
(152, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:42:15'),
(153, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 11:49:43'),
(154, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-21 11:49:48'),
(155, 1, 'alert', 'Job Application Removed', 'The Care Taker job application was removed.', '2026-01-21 12:00:00'),
(156, 1, 'update', 'Job Application Created', 'New Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:12'),
(157, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:14'),
(158, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:15'),
(159, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:15'),
(160, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-01-21 12:00:16'),
(161, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:16'),
(162, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:17'),
(163, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:17'),
(164, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-01-21 12:00:17'),
(165, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:17'),
(166, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:17'),
(167, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:17'),
(168, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-01-21 12:00:18'),
(169, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:18'),
(170, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:18'),
(171, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:18'),
(172, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-01-21 12:00:18'),
(173, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:18'),
(174, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:19'),
(175, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:19'),
(176, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-01-21 12:00:19'),
(177, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:19'),
(178, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-01-21 12:00:19'),
(179, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-01-23', '2026-01-21 12:00:19'),
(180, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-01-30', '2026-01-21 12:01:09'),
(181, 1, 'message', 'Job Application Status Changed', 'Mobile Rider job application status changed to: open', '2026-01-21 12:01:10'),
(182, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-01-30', '2026-01-21 12:01:10'),
(183, 1, 'leave', 'Job Application Status Changed', 'Mobile Rider job application status changed to: closed', '2026-01-21 12:01:11'),
(184, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-01-30', '2026-01-21 12:01:11'),
(185, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-29', '2026-01-21 12:01:20'),
(186, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-21 12:01:21'),
(187, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-01-29', '2026-01-21 12:01:21'),
(188, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-01-21 12:02:05'),
(189, 1, 'message', 'Job Application Status Changed', 'Mobile Rider job application status changed to: open', '2026-01-21 12:03:58'),
(190, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-01-30', '2026-01-21 12:03:58'),
(191, 1, 'incident', 'Officer Application Rejected', 'Officer application #18 was rejected', '2026-01-21 12:05:20'),
(192, 1, 'registration', 'Officer Application Accepted', 'Mobile Rider application #16 was approved', '2026-01-21 12:05:23'),
(193, 1, 'updregistrationate', 'Client Accepted', 'Client #6 registration was approved', '2026-01-21 12:25:47'),
(194, 1, 'updregistrationate', 'Client Accepted', 'Client #6 registration was approved', '2026-01-21 12:29:41'),
(195, 1, 'updregistrationate', 'Client Accepted', 'Client #6 registration was approved', '2026-01-21 12:38:45'),
(196, 1, 'updregistrationate', 'Client Accepted', 'Client #4 registration was approved', '2026-01-21 14:18:58'),
(197, 1, 'shift', 'New Site Added', 'Site \'Darley Road\' added for client ID: 41', '2026-01-21 14:19:55'),
(198, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-03-04', '2026-01-21 14:21:36'),
(199, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-04-01', '2026-01-21 14:21:44'),
(200, 1, 'shift', 'New Site Added', 'Site \'University of Sri Jayewardenepura\' added for client ID: 41', '2026-01-22 09:08:25'),
(201, 1, 'shift', 'New Site Added', 'Site \'Peoples Bank ATM\' added for client ID: 10', '2026-01-22 09:41:20'),
(202, 1, 'shift', 'New Site Added', 'Site \'National Hospital Galle\' added for client ID: 41', '2026-01-22 12:58:57'),
(203, 1, 'shift', 'New Site Added', 'Site \'Highland Milk Bar - National Hospital Galle\' added for client ID: 40', '2026-01-22 16:03:00'),
(204, 1, 'shift', 'New Site Added', 'Site \'University of Ruhuna\' added for client ID: 40', '2026-01-22 16:06:22'),
(205, 1, 'shift', 'New Site Added', 'Site \'Department of Sociology\' added for client ID: 41', '2026-01-22 16:08:10'),
(206, 1, 'shift', 'New Site Added', 'Site \'Faculty of Management and Finance, University of Ruhuna\' added for client ID: 41', '2026-01-22 16:09:23'),
(207, 1, 'shift', 'New Site Added', 'Site \'Thotte house\' added for client ID: 41', '2026-01-22 16:11:01'),
(208, 1, 'shift', 'New Site Added', 'Site \'Thusitha Auto Engineering\' added for client ID: 41', '2026-01-22 16:13:04'),
(209, 1, 'alert', 'Site Deleted', 'Site \'Department of Sociology\' (ID: 11) was deleted', '2026-01-23 08:45:37'),
(210, 1, 'success', 'Route Created', 'New route \'efwe\' (ID: R009) was created', '2026-01-24 06:20:59'),
(211, 1, 'success', 'Site Assigned to Route', 'Site \'Colombo City Centre Mall and Residences\' was assigned to route \'efwe\'', '2026-01-24 06:21:48'),
(212, 1, 'alert', 'Route Deleted', 'Route \'efwe\' (ID: R009) was permanently deleted', '2026-01-24 06:22:31'),
(213, 1, 'info', 'Site Removed from Route', 'Site \'Thusitha Auto Engineering\' was removed from route \'Galle\'', '2026-01-24 06:35:09'),
(214, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'P.M.T Vithanage\' was assigned to route \'Colombo\'', '2026-01-24 07:09:30'),
(215, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Gajanayake\' was assigned to route \'Colombo\'', '2026-01-24 07:09:41'),
(216, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Gajanayake\' was assigned to route \'Galle\'', '2026-01-24 07:13:23'),
(217, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'P.M.T Vithanage\' was assigned to route \'Galle\'', '2026-01-24 07:13:33'),
(218, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Kulathunga\' was assigned to route \'Ruhuna\'', '2026-01-24 07:14:08'),
(219, 1, 'success', 'Route Created', 'New route \'wdwfewfwe\' (ID: R009) was created', '2026-01-24 07:31:42'),
(220, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Kulathunga\' was assigned to route \'Ruhuna\'', '2026-01-24 07:38:39'),
(221, 1, 'alert', 'Route Deleted', 'Route \'Colombo\' (ID: R005) was permanently deleted', '2026-01-24 07:39:30'),
(222, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Gajanayake\' was assigned to route \'wdwfewfwe\'', '2026-01-24 07:39:40'),
(223, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Gajanayake\' was assigned to route \'wdwfewfwe\'', '2026-01-24 08:21:54'),
(224, 1, 'success', 'Route Created', 'New route \'Example\' (ID: R010) was created', '2026-01-24 08:33:17'),
(225, 1, 'info', 'Route Location Updated', 'Location area for route \'Example\' was updated', '2026-01-24 08:33:47'),
(226, 1, 'success', 'Route Created', 'New route \'wfwfewffewd\' (ID: R011) was created', '2026-01-25 13:56:52'),
(227, 37, 'visit', 'Site Visit Completed', 'Marked University of Ruhuna as visited', '2026-01-26 03:52:59'),
(228, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-26 04:05:05'),
(229, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-01-26 04:05:05'),
(230, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-01-26 04:06:07'),
(231, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-01-26 04:06:07'),
(232, 1, 'leave', 'Job Application Status Changed', 'Premise Officer job application status changed to: closed', '2026-01-26 04:06:14'),
(233, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-01-26 04:06:14'),
(234, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-01-26 04:06:21'),
(235, 24, 'visit', 'Site Visit Completed', 'Marked Highland Milk Bar - National Hospital Galle as visited', '2026-01-26 04:09:59'),
(236, 24, 'visit', 'Site Visit Completed', 'Marked National Hospital Galle as visited', '2026-01-26 04:10:03'),
(237, 37, 'visit', 'Site Visit Completed', 'Marked Faculty of Management and Finance, University of Ruhuna as visited - Condition: Excellent', '2026-01-26 04:31:38'),
(238, 37, 'visit', 'Site Visit Completed', 'Marked University of Ruhuna as visited - Condition: Good', '2026-01-26 04:42:39'),
(239, 37, 'visit', 'Site Visit Completed', 'Marked Faculty of Management and Finance, University of Ruhuna as visited - Condition: Good', '2026-01-26 04:59:30'),
(240, 37, 'visit', 'Site Visit Completed', 'Marked University of Ruhuna as visited - Condition: Good', '2026-01-26 05:05:05'),
(241, 1, 'alert', 'Route Deleted', 'Route \'wdwfewfwe\' (ID: R009) was permanently deleted', '2026-01-26 05:12:47'),
(242, 1, 'success', 'Route Created', 'New route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\' (ID: R012) was created', '2026-01-26 05:14:02'),
(243, 1, 'success', 'Mobile Rider Assigned to Route', 'Mobile rider \'Gajanayake\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-26 05:14:19'),
(244, 1, 'success', 'Site Assigned to Route', 'Site \'Colombo City Centre Mall and Residences\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-26 05:14:35'),
(245, 29, 'visit', 'Site Visit Completed', 'Marked Colombo City Centre Mall and Residences as visited - Condition: Good', '2026-01-26 05:16:28'),
(246, 1, 'success', 'Route Created', 'New route \'ttctrtrct\' (ID: R013) was created', '2026-01-26 06:44:40'),
(247, 1, 'success', 'Site Assigned to Route', 'Site \'Darley Road\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-26 06:45:07'),
(248, 37, 'visit', 'Site Visit Completed', 'Marked University of Ruhuna as visited - Condition: Excellent', '2026-01-26 06:50:45'),
(249, 37, 'visit', 'Site Visit Completed', 'Marked Faculty of Management and Finance, University of Ruhuna as visited - Condition: Good', '2026-01-26 06:51:40'),
(250, 1, 'success', 'Site Assigned to Route', 'Site \'Peoples Bank ATM\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-26 07:57:12'),
(251, 1, 'success', 'Site Assigned to Route', 'Site \'Thotte house\' was assigned to route \'Ruhuna\'', '2026-01-26 07:57:22'),
(252, 1, 'success', 'Site Assigned to Route', 'Site \'Thusitha Auto Engineering\' was assigned to route \'Galle\'', '2026-01-26 07:57:31'),
(253, 1, 'success', 'Site Assigned to Route', 'Site \'University of Sri Jayewardenepura\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-26 07:57:43'),
(254, 1, 'info', 'Route Location Updated', 'Location area for route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\' was updated', '2026-01-26 07:58:25'),
(255, 37, 'visit', 'Site Visit Completed', 'Marked Thotte house as visited - Condition: Fair', '2026-01-26 10:54:19'),
(256, 37, 'incident_report', 'New Incident Reported', 'Reported incident: Unauthorized Access at site ID 12', '2026-01-26 11:45:01'),
(257, 37, 'incident_report', 'New Incident Reported', 'Reported incident: Security Breach at site ID 13', '2026-01-26 12:43:33'),
(258, 37, 'incident_report', 'New Incident Reported', 'Reported incident: Security Breach at site ID 10', '2026-01-26 12:44:48'),
(259, 37, 'incident_review', 'Added Review to Incident', 'Added review to incident #9: grggrg', '2026-01-26 16:41:20'),
(260, 37, 'incident_review', 'Added Review to Incident', 'Added review to incident #9: hgvhg h', '2026-01-26 16:45:58'),
(261, 37, 'incident_review', 'Added Review to Incident', 'Added review to incident #9: wdcdc', '2026-01-26 16:51:24'),
(262, 37, 'incident_review', 'Added Review to Incident', 'Added review to incident #8: grggrg', '2026-01-26 16:58:06'),
(263, 29, 'incident_report', 'New Incident Reported', 'Reported incident: Theft at site ID 4', '2026-01-26 18:17:10'),
(264, 24, 'incident_report', 'New Incident Reported', 'Reported incident: Unauthorized Access at site ID 8', '2026-01-26 18:25:44'),
(265, 24, 'incident_review', 'Added Review to Incident', 'Added review to incident #11: fvfd', '2026-01-26 18:26:03'),
(266, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #11', '2026-01-26 18:52:07'),
(267, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #11 as resolved', '2026-01-26 19:08:00'),
(268, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #10', '2026-01-26 19:12:02'),
(269, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #9', '2026-01-26 19:17:58'),
(270, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #9 as resolved', '2026-01-26 19:18:08'),
(271, 37, 'incident_review', 'Added Review to Incident', 'Added review to incident #7: ewdeww', '2026-01-27 02:47:13'),
(272, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #8', '2026-01-27 03:13:48'),
(273, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #10', '2026-01-27 05:10:54'),
(274, 1, 'shift', 'New Site Added', 'Site \'Jafferjee Brothers\' added for client ID: 41', '2026-01-27 06:15:58'),
(275, 1, 'shift', 'New Site Added', 'Site \'Ampara Kema Kade\' added for client ID: 41', '2026-01-27 06:17:20'),
(276, 1, 'officer', 'Rank Updated', 'Officer ID: 17 - rank changed to: Senior', '2026-01-27 07:08:55'),
(277, 1, 'officer', 'Employment status Updated', 'Officer ID: 17 - employment_status changed to: On Leave', '2026-01-27 07:09:04'),
(278, 1, 'officer', 'Rank Updated', 'Officer ID: 17 - rank changed to: Junior', '2026-01-27 07:09:44'),
(279, 1, 'officer', 'Rank Updated', 'Officer ID: 17 - rank changed to: Supervisor', '2026-01-27 07:10:01'),
(280, 1, 'officer', 'Rank Updated', 'Officer ID: 18 - rank changed to: Senior', '2026-01-27 08:42:18'),
(281, 1, 'officer', 'Rank Updated', 'Officer ID: 18 - rank changed to: Supervisor', '2026-01-27 08:42:21'),
(282, 1, 'officer', 'Rank Updated', 'Officer ID: 20 - rank changed to: Supervisor', '2026-01-27 08:53:13'),
(283, 17, 'incident', 'New Incident Reported', 'Reported incident: Equipment Malfunction at site ID 5', '2026-01-27 09:43:37'),
(284, 17, 'incident_review', 'Added Review to Incident', 'Added review to incident #12: hvcg', '2026-01-27 09:53:58'),
(285, 17, 'incident_review', 'Added Review to Incident', 'Added review to incident #12: hgvhg h', '2026-01-27 09:57:26'),
(286, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #12', '2026-01-27 10:13:42'),
(287, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #12 as resolved', '2026-01-27 10:21:01'),
(288, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #7', '2026-01-27 10:43:14'),
(289, 37, 'visit', 'Site Visit Completed', 'Marked Faculty of Management and Finance, University of Ruhuna as visited - Condition: Good', '2026-01-27 15:54:15'),
(290, 37, 'visit', 'Site Visit Completed', 'Marked Thotte house as visited - Condition: Fair', '2026-01-27 15:55:52'),
(291, 37, 'visit', 'Site Visit Completed', 'Marked University of Ruhuna as visited - Condition: Good', '2026-01-27 15:56:02'),
(292, 17, 'incident', 'New Incident Reported', 'Reported incident: Security Breach at site ID 5', '2026-01-27 16:07:10'),
(293, 17, 'incident_review', 'Added Review to Incident', 'Added review to incident #13: efvev', '2026-01-27 16:07:27'),
(294, 29, 'incident_review', 'Added Review to Incident', 'Added review to incident #13: grggrg', '2026-01-27 16:08:07'),
(295, 17, 'incident', 'New Incident Reported', 'Reported incident: Security Breach at site ID 5', '2026-01-27 16:12:32'),
(296, 29, 'incident_review', 'Added Review to Incident', 'Added review to incident #14: gch', '2026-01-27 16:13:44'),
(297, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #14', '2026-01-27 16:14:12'),
(298, 29, 'visit', 'Site Visit Completed', 'Marked Colombo City Centre Mall and Residences as visited - Condition: Good', '2026-01-27 16:20:09'),
(299, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #14 as resolved', '2026-01-28 04:46:04'),
(300, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #13', '2026-01-29 05:12:55'),
(301, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #13 as resolved', '2026-01-29 05:13:22'),
(302, 1, 'officer', 'Employment status Updated', 'Officer ID: 17 - employment_status changed to: On Leave', '2026-01-29 05:51:38'),
(303, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithsvssanage\' added', '2026-01-29 08:44:19'),
(304, 1, 'shift', 'New Site Added', 'Site \'Colombo City Centre Mall and Residences\' added for client ID: 41', '2026-01-30 07:02:53'),
(305, 1, 'shift', 'New Site Added', 'Site \'Suvinlan Resort Inn\' added for client ID: 41', '2026-01-31 14:38:30'),
(306, 1, 'success', 'Site Assigned to Route', 'Site \'Suvinlan Resort Inn\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-01-31 14:39:50'),
(307, 29, 'visit', 'Site Visit Completed', 'Marked Colombo City Centre Mall and Residences as visited - Condition: Good', '2026-01-31 14:41:09'),
(308, 1, 'registration', 'Officer Application Accepted', 'Care Taker application #19 was approved', '2026-02-01 10:48:25'),
(309, 1, 'registration', 'Officer Application Accepted', 'Care Taker application #19 was approved', '2026-02-01 10:49:24'),
(310, 1, 'registration', 'Client Accepted', 'Client #7 registration was approved', '2026-02-01 10:58:21'),
(311, 1, 'registration', 'Client Accepted', 'Client #7 registration was approved', '2026-02-01 11:04:23'),
(312, 1, 'shift', 'New Admin Added', 'Admin \'Jayaweera\' added', '2026-02-01 11:08:49'),
(313, 1, 'shift', 'New Admin Added', 'Admin \'Kula\' added', '2026-02-01 11:14:46'),
(314, 1, 'shift', 'New Admin Added', 'Admin \'erfre\' added', '2026-02-01 11:15:33'),
(315, 1, 'shift', 'New Admin Added', 'Admin \'mhrngpsn@gmail.com\' added', '2026-02-01 11:26:49'),
(316, 17, 'incident', 'New Incident Reported', 'Reported incident: Security Breach at site ID 5', '2026-02-01 13:55:25'),
(317, 29, 'incident_review', 'Added Review to Incident', 'Added review to incident #15: ftccftxxr', '2026-02-01 14:10:00'),
(318, 17, 'incident_review', 'Added Review to Incident', 'Added review to incident #15: eded', '2026-02-01 14:12:06'),
(319, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #15', '2026-02-01 14:13:15'),
(320, 29, 'incident_review', 'Added Review to Incident', 'Added review to incident #15: 3f33f3f3f3f', '2026-02-01 14:14:52'),
(321, 17, 'incident_review', 'Added Review to Incident', 'Added review to incident #15: fvfddfbdggfbfgffg', '2026-02-01 14:24:33'),
(322, 1, 'Incident', 'Incident Review Added', 'Admin added a review to incident #15', '2026-02-01 14:25:09'),
(323, 1, 'registration', 'Officer Application Accepted', 'Care Taker application #19 was approved', '2026-02-01 15:03:05'),
(324, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Senior', '2026-02-01 15:07:31'),
(325, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:12:25'),
(326, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Junior', '2026-02-01 15:14:00'),
(327, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:14:18'),
(328, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Junior', '2026-02-01 15:14:34'),
(329, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:14:38'),
(330, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Junior', '2026-02-01 15:15:19'),
(331, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:15:25'),
(332, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Junior', '2026-02-01 15:18:53'),
(333, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:18:56'),
(334, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Senior', '2026-02-01 15:22:49'),
(335, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:22:54'),
(336, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Senior', '2026-02-01 15:25:28'),
(337, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:25:31'),
(338, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Senior', '2026-02-01 15:28:18'),
(339, 1, 'officer', 'Rank Updated', 'Officer ID: 19 - rank changed to: Supervisor', '2026-02-01 15:28:27'),
(340, 1, 'shift', 'New Site Added', 'Site \'Hampden Lane\' added for client ID: 46', '2026-02-02 02:50:59'),
(341, 1, 'Incident', 'Incident Resolved', 'Admin marked incident #15 as resolved', '2026-02-02 04:24:24'),
(342, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-02-02 04:30:21'),
(343, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-02-03', '2026-02-02 04:30:21'),
(344, 1, 'success', 'Site Assigned to Route', 'Site \'Ampara Kema Kade\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-02-02 05:35:23'),
(345, 1, 'success', 'Site Assigned to Route', 'Site \'Jafferjee Brothers\' was assigned to route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\'', '2026-02-02 05:35:37'),
(346, 1, 'info', 'Route Location Updated', 'Location area for route \'137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka\' was updated', '2026-02-02 05:36:00'),
(347, 24, 'visit', 'Site Visit Completed', 'Marked Highland Milk Bar - National Hospital Galle as visited - Condition: Good', '2026-02-06 01:51:34'),
(348, 17, 'incident', 'New Incident Reported', 'Reported incident: Fire Alarm at site ID 5', '2026-02-06 06:20:08'),
(349, 1, 'shift', 'New Admin Added', 'Admin \'P.M.T Vithanage\' added', '2026-02-07 02:52:43'),
(350, 1, 'registration', 'Client Accepted', 'Client #8 registration was approved', '2026-02-07 05:45:06'),
(351, 25, 'leave_request', 'Leave Request Submitted', 'Submitted Maternity Leave leave request from 2026-02-12 to 2026-02-18', '2026-02-09 07:58:48'),
(352, 25, 'leave_rejected', 'Leave Request Rejected', 'Your Maternity Leave leave request from 2026-02-12 to 2026-02-18 was rejected', '2026-02-09 07:59:17'),
(353, 1, 'leave_rejection', 'Leave Request Rejected', 'Rejected P.M.T Vithanage\'s (Caretaker) Maternity Leave leave request from 2026-02-12 to 2026-02-18. Reason: fvfv', '2026-02-09 07:59:17'),
(354, 25, 'note_created', 'Note Created', 'Created note: dfddfdfdfv - Category: General', '2026-02-09 08:15:35'),
(355, 25, 'equipment_request', 'Equipment Request Submitted', 'Requested 2 x Radio - Priority: Medium', '2026-02-09 09:03:30'),
(356, 26, 'leave_request', 'Leave Request Submitted', 'Submitted Paternity Leave leave request from 2026-02-11 to 2026-02-13', '2026-02-10 02:32:28'),
(357, 1, 'shift', 'New Site Added', 'Site \'VFS Global Visa Application Centre in Colombo\' added for client ID: 41', '2026-02-10 11:35:54'),
(358, 1, 'update', 'Package Updated', 'Security package \'Basic Security\' (ID: 1) was updated', '2026-02-10 19:36:33'),
(359, 1, 'update', 'Package Updated', 'Security package \'Custom Package\' (ID: 6) was updated', '2026-02-10 20:02:42'),
(360, 1, 'update', 'Package Status Changed', 'Security package \'Basic Security\' was deactivated', '2026-02-10 20:26:32'),
(361, 1, 'update', 'Package Status Changed', 'Security package \'Budget Guardian\' was deactivated', '2026-02-10 20:26:38'),
(362, 1, 'update', 'Package Status Changed', 'Security package \'Vigilant Watch\' was deactivated', '2026-02-10 20:26:43'),
(363, 1, 'update', 'Package Updated', 'Security package \'Custom Package\' (ID: 6) was updated', '2026-02-12 05:46:21'),
(364, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 05:50:51'),
(365, 1, 'update', 'Package Status Changed', 'Security package \'Extra Supervisor\' was deactivated', '2026-02-12 05:51:35'),
(366, 1, 'update', 'Package Status Changed', 'Security package \'Extra Supervisor\' was activated', '2026-02-12 05:51:37'),
(367, 1, 'update', 'Package Status Changed', 'Security package \'Custom Package\' was deactivated', '2026-02-12 05:58:26'),
(368, 1, 'update', 'Package Status Changed', 'Security package \'Custom Package\' was activated', '2026-02-12 05:58:31'),
(369, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 0 package(s) automatically recalculated.', '2026-02-12 06:06:37'),
(370, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 08:27:53'),
(371, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 16:09:04'),
(372, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 16:27:51'),
(373, 5, 'leave_request', 'Leave Request Submitted', 'Submitted Annual Leave leave request from 2026-02-13 to 2026-02-14', '2026-02-12 16:56:15'),
(374, 54, 'note_created', 'Note Created', 'Created note: xz z - Category: Important', '2026-02-12 16:57:01'),
(375, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 17:08:52'),
(376, 54, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-02-13 to 2026-02-25', '2026-02-12 17:44:36'),
(377, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-12 17:49:05'),
(378, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-13 01:42:37'),
(379, 37, 'visit', 'Site Visit Completed', 'Marked Faculty of Management and Finance, University of Ruhuna as visited - Condition: Good', '2026-02-13 06:58:41'),
(380, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 8 package(s) automatically recalculated.', '2026-02-16 02:41:32'),
(381, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 4 package(s) automatically recalculated.', '2026-02-16 03:47:44'),
(382, 1, 'update', 'Custom Package Updated', 'Custom Package unit prices updated. 3 package(s) automatically recalculated.', '2026-02-16 03:52:03'),
(383, 1, 'update', 'Package Status Changed', 'Security package \'Enterprise Package\' was deactivated', '2026-02-16 03:52:17'),
(384, 17, 'equipment_approval', 'Equipment Request Approved', 'Approved equipment request for Radio from P.M.T Vithanage', '2026-02-17 11:47:25'),
(385, 1, 'update', 'Package Status Changed', 'Security package \'Premium Package\' was deactivated', '2026-03-09 09:34:47'),
(386, 1, 'update', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-06-20', '2026-03-28 01:36:40'),
(387, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-08-13', '2026-03-28 01:36:54'),
(388, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-07-03', '2026-03-28 01:37:06'),
(389, 1, 'message', 'Job Application Status Changed', 'Care Taker job application status changed to: open', '2026-03-28 01:37:13'),
(390, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-07-03', '2026-03-28 01:37:13'),
(391, 1, 'leave', 'Job Application Status Changed', 'Care Taker job application status changed to: closed', '2026-03-28 01:37:14'),
(392, 1, 'update', 'Job Application Updated', 'Updated Care Taker job application created with due date: 2026-07-03', '2026-03-28 01:37:14'),
(393, 1, 'leave', 'Job Application Status Changed', 'Mobile Rider job application status changed to: closed', '2026-03-28 01:37:18');
INSERT INTO `recent_activities` (`id`, `user_id`, `activity_type`, `activity_titel`, `activity_details`, `created_at`) VALUES
(394, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-08-13', '2026-03-28 01:37:18'),
(395, 1, 'message', 'Job Application Status Changed', 'Mobile Rider job application status changed to: open', '2026-03-28 01:37:19'),
(396, 1, 'update', 'Job Application Updated', 'Updated Mobile Rider job application created with due date: 2026-08-13', '2026-03-28 01:37:19'),
(397, 60, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-04-08 to 2026-04-10', '2026-04-06 05:16:39'),
(398, 60, 'leave_approved', 'Leave Request Approved', 'Your Sick Leave leave request from 2026-04-08 to 2026-04-10 was approved', '2026-04-06 05:19:51'),
(399, 1, 'leave_approval', 'Leave Request Approved', 'Approved Test PO 04\'s (Premise Officer) Sick Leave leave request from 2026-04-08 to 2026-04-10', '2026-04-06 05:19:51'),
(400, 60, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-05-14 to 2026-05-15', '2026-04-06 06:02:43'),
(401, 60, 'leave_approved', 'Leave Request Approved', 'Your Sick Leave leave request from 2026-05-14 to 2026-05-15 was approved', '2026-04-06 06:03:19'),
(402, 1, 'leave_approval', 'Leave Request Approved', 'Approved Test PO 04\'s (Premise Officer) Sick Leave leave request from 2026-05-14 to 2026-05-15', '2026-04-06 06:03:19'),
(403, 60, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-05-20 to 2026-05-21', '2026-04-06 06:37:54'),
(404, 60, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-05-24 to 2026-05-26', '2026-04-06 07:01:47'),
(405, 60, 'leave_request', 'Leave Request Submitted', 'Submitted Sick Leave leave request from 2026-04-29 to 2026-04-30', '2026-04-11 14:42:41'),
(406, 1, 'message', 'Job Application Status Changed', 'Premise Officer job application status changed to: open', '2026-04-16 16:53:56'),
(407, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-06-20 (Not Completed)', '2026-04-16 16:53:56'),
(408, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-06-20 (Not Completed)', '2026-04-16 16:54:04'),
(409, 1, 'alert', 'Job Application Updated', 'Updated Premise Officer job application created with due date: 2026-06-16 (Not Completed)', '2026-04-16 16:55:06'),
(410, 1, 'alert', 'Job Application Removed', 'The Premise Officer job application was removed.', '2026-04-16 17:00:44'),
(411, 1, 'alert', 'Job Application Removed', 'The Mobile Rider job application was removed.', '2026-04-16 17:01:15'),
(412, 1, 'alert', 'Job Application Removed', 'The Mobile Rider job application was removed.', '2026-04-16 17:02:25'),
(413, 1, 'alert', 'Job Application Removed', 'The Care Taker job application was removed.', '2026-04-16 17:04:00'),
(414, 1, 'alert', 'Officer Application Deleted', 'Officer application #18 was permanently deleted', '2026-04-16 17:10:38'),
(415, 1, 'alert', 'Site Deleted', 'Site \'Highland Milk Bar - National Hospital Galle\' (ID: 9) was deleted', '2026-04-16 18:13:39'),
(416, 1, 'shift', 'New Admin Added', 'Admin \'Haritha Gamage\' added', '2026-04-17 05:14:45'),
(417, 1, 'shift', 'New Admin Added', 'Admin \'Haritha Gamage\' added', '2026-04-17 07:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` varchar(50) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `cities_covered` text DEFAULT NULL,
  `distance_km` decimal(6,2) DEFAULT NULL,
  `difficulty_level` enum('Easy','Medium','Hard') DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `location` text DEFAULT NULL,
  `assigned_rider_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_name`, `description`, `cities_covered`, `distance_km`, `difficulty_level`, `status`, `created_by`, `created_at`, `updated_at`, `location`, `assigned_rider_id`) VALUES
('R007', 'Galle', 'ervew erfewfew wrewfew ewfrewfer ewfrefew ewferfhy6ujyu jyjyuj tyntrhfnr ymjnjthrnhgnhtfnn ynmthtnggfbytn tnjtmjgbgfbgbhtnhrnhn tnhjynnrhgbhgnjymnjynmhjtnhbrgb thnthnhnhynyhnjyjymynhynyjnjynjynjynjynjnynhynyjmmjumujujynnnnnn', NULL, NULL, NULL, 'Active', 1, '2026-01-22 13:11:23', '2026-01-24 07:13:33', '[{\"lat\":6.068890372286321,\"lng\":80.22417243927157},{\"lat\":6.080188413695444,\"lng\":80.25186356514132},{\"lat\":6.060707412495743,\"lng\":80.25193866699374},{\"lat\":6.065967138847419,\"lng\":80.22179063766634}]', 24),
('R008', 'Ruhuna', 'Uni', NULL, NULL, NULL, 'Active', 1, '2026-01-22 16:05:34', '2026-01-24 07:14:08', '[{\"lat\":5.938904717411796,\"lng\":80.57573967050017},{\"lat\":5.938200414319987,\"lng\":80.57872228692473},{\"lat\":5.936394299624564,\"lng\":80.58243982861937},{\"lat\":5.934630864350454,\"lng\":80.58075003693999},{\"lat\":5.936674421198595,\"lng\":80.57657651971282},{\"lat\":5.9377095358749665,\"lng\":80.57431273530425}]', 37),
('R010', 'Example', 'dvevev', NULL, NULL, NULL, 'Active', 1, '2026-01-24 08:33:17', '2026-01-24 08:33:47', '[{\"lat\":6.927432996663983,\"lng\":79.84795148022128},{\"lat\":6.936049182459632,\"lng\":79.86514980442477},{\"lat\":6.916122016905856,\"lng\":79.8814898217435},{\"lat\":6.896534849449009,\"lng\":79.85740358478976}]', NULL),
('R011', 'wfwfewffewd', 'ddcdscds', NULL, NULL, NULL, 'Active', 1, '2026-01-25 13:56:52', '2026-01-25 13:56:52', '[{\"lat\":6.859594451350357,\"lng\":79.8609832659387},{\"lat\":6.858742287621242,\"lng\":79.8679355517053},{\"lat\":6.852734490082056,\"lng\":79.86651934534544},{\"lat\":6.854950140542821,\"lng\":79.86158408075804}]', NULL),
('R012', '137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka', 'this is a example route', NULL, NULL, NULL, 'Active', 1, '2026-01-26 05:14:02', '2026-02-02 05:36:00', '[{\"lat\":6.91994562685649,\"lng\":79.8541302168425},{\"lat\":6.972919136393548,\"lng\":79.87199372888169},{\"lat\":6.955252910833285,\"lng\":79.91142220140061},{\"lat\":6.888506965405136,\"lng\":79.93986434579453},{\"lat\":6.83441933112176,\"lng\":79.89649839044175},{\"lat\":6.916032550513978,\"lng\":79.8519308054503}]', 29),
('R013', 'ttctrtrct', 'tcftct', NULL, NULL, NULL, 'Active', 1, '2026-01-26 06:44:40', '2026-01-26 06:44:40', '[{\"lat\":6.85419638263842,\"lng\":79.90370363292692},{\"lat\":6.853067252578022,\"lng\":79.90641802844999},{\"lat\":6.85161855348957,\"lng\":79.90540951786039},{\"lat\":6.852886165432703,\"lng\":79.90231961307524}]', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `route_sites`
--

CREATE TABLE `route_sites` (
  `route_id` varchar(50) NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route_sites`
--

INSERT INTO `route_sites` (`route_id`, `site_id`) VALUES
('R007', 8),
('R007', 14),
('R008', 10),
('R008', 12),
('R008', 13),
('R012', 4),
('R012', 5),
('R012', 6),
('R012', 7),
('R012', 15),
('R012', 16),
('R012', 18);

-- --------------------------------------------------------

--
-- Table structure for table `sent_emails`
--

CREATE TABLE `sent_emails` (
  `id` int(11) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `body` text DEFAULT NULL,
  `status` enum('sent','failed') DEFAULT 'sent',
  `error` text DEFAULT NULL,
  `meta` text DEFAULT NULL COMMENT 'JSON metadata',
  `sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `guard_type` enum('Armed','Regular') NOT NULL,
  `guard_count` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','In Progress','Completed') DEFAULT 'Pending',
  `submitted_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `package_request_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `client_id`, `site_name`, `address`, `district`, `city`, `phone_number`, `created_at`, `updated_at`, `image`, `latitude`, `longitude`, `is_draft`, `package_request_id`) VALUES
(4, 32, 'Colombo City Centre Mall and Residences', '137 Sir James Pieris Mawatha, Colombo 00200, Sri Lanka', NULL, 'Colombo', '0971122777', '2026-01-21 11:15:17', '2026-01-21 11:15:17', '1768994117_p7.jpg', 6.91735540, 79.85503250, 0, NULL),
(5, 41, 'Darley Road', 'Darley Rd, Colombo, Sri Lanka', NULL, 'colombo', '0912244325', '2026-01-21 14:19:55', '2026-01-21 14:19:55', '1769005195_c2.jpg', 6.92233530, 79.86215110, 0, NULL),
(6, 41, 'University of Sri Jayewardenepura', 'ගංගොඩවිල, Sri Soratha Mawatha, Nugegoda, Sri Lanka', NULL, 'Colombo', '0912277345', '2026-01-22 09:08:25', '2026-01-22 09:08:25', '1769072905_s2.jpeg', 6.85276700, 79.90358460, 0, NULL),
(7, 10, 'Peoples Bank ATM', 'WV5Q+VGF, Colombo, Sri Lanka', NULL, 'Colombo', '0771188234', '2026-01-22 09:41:20', '2026-01-22 09:41:20', '1769074880_s3.jpeg', 6.90969190, 79.88878550, 0, NULL),
(8, 41, 'National Hospital Galle', 'Galle 80000, Sri Lanka', NULL, 'karapitiy', '0912234567', '2026-01-22 12:58:57', '2026-01-22 12:58:57', '1769086737_s1.jpeg', 6.06707280, 80.22607360, 0, NULL),
(10, 40, 'University of Ruhuna', 'A2, Matara, Sri Lanka', NULL, 'Mathara', '0912233665', '2026-01-22 16:06:22', '2026-01-22 16:06:22', '1769097982_s3.jpeg', 5.93809210, 80.57613440, 0, NULL),
(12, 41, 'Faculty of Management and Finance, University of Ruhuna', 'Wellamadama, Matara 81000, Sri Lanka', NULL, 'mathara', '0917788923', '2026-01-22 16:09:23', '2026-01-22 16:09:23', '1769098163_s2.jpeg', 5.93698990, 80.57852060, 0, NULL),
(13, 41, 'Thotte house', 'WHRJ+WR8, Matara, Sri Lanka', NULL, 'wdwdew', '0912233456', '2026-01-22 16:11:01', '2026-01-22 16:11:01', '1769098261_s3.jpeg', 5.94290210, 80.58218150, 0, NULL),
(14, 41, 'Thusitha Auto Engineering', '15A ketakalagaha watta, Kapuhempala 80000, Sri Lanka', NULL, 'cdscwc', '0917788234', '2026-01-22 16:13:04', '2026-01-22 16:13:04', '1769098384_s1.jpeg', 6.06180980, 80.24560750, 0, NULL),
(15, 41, 'Jafferjee Brothers', '150 St. Joseph&apos;s St, Colombo 01400, Sri Lanka', 'Jaffna', 'Kondavil', '0912233456', '2026-01-27 06:15:58', '2026-01-27 06:15:58', '1769494558_Peoples-Bank-Galkiriyagama.jpg', 6.94971420, 79.87388490, 0, NULL),
(16, 41, 'Ampara Kema Kade', 'No:, 7, Wellampitiya, Sri Lanka', 'Ampara', 'Damana', '0913322345', '2026-01-27 06:17:20', '2026-01-27 06:17:20', '1769494640_s3.jpeg', 6.94637180, 79.89868190, 0, NULL),
(17, 41, 'Colombo City Centre Mall and Residences', 'Erragadda, Hyderabad, Telangana, India', 'Trincomalee', 'Kanniya', '0912287654', '2026-01-30 07:02:53', '2026-01-30 07:02:53', '1769756573_p4.jpeg', 17.45586700, 78.42519670, 0, NULL),
(18, 41, 'Suvinlan Resort Inn', 'VWCH+V7F, Sri Jayawardenepura Kotte, Sri Lanka', 'Mannar', 'Adampan', '0913344234', '2026-01-31 14:38:30', '2026-01-31 14:38:30', '1769870310_s1.jpeg', 6.87219520, 79.92817480, 0, NULL),
(19, 46, 'Hampden Lane', 'Hampden Ln, Colombo, Sri Lanka', 'Hambantota', 'Nonagama', '0712233456', '2026-02-02 02:50:59', '2026-02-02 02:50:59', '1770000659_Activity 02.png', 6.87427700, 79.86604680, 0, NULL),
(20, 41, 'VFS Global Visa Application Centre in Colombo', '1st Floor- 5th Floor, 675 Dr Danister De Silva Mawatha, Colombo 00900, Sri Lanka', 'Colombo', 'Colombo 9', '0912233459', '2026-02-10 11:35:54', '2026-02-10 11:35:54', '1770723354_Screenshot from 2025-12-24 19-37-23.png', 6.93937150, 79.87781750, 0, NULL),
(21, 9, 'Suvinlan Resort Inn', 'VWCH+V7F, Sri Jayawardenepura Kotte, Sri Lanka', 'Mannar', 'Adampan', '', '2026-02-12 08:25:44', '2026-02-12 08:25:44', NULL, NULL, NULL, 1, 1),
(22, 9, 'Darley Road', 'Darley Rd, Colombo, Sri Lanka', '', 'colombo', '', '2026-02-12 14:23:20', '2026-02-12 14:23:20', NULL, NULL, NULL, 1, 18),
(26, 8, 'alvaroo alto', '30b Welikadawatte, Sri Jayawardenepura Kotte, Sri Lanka', 'Colombo', 'Colombo 12', '0743476508', '2026-04-01 07:43:27', '2026-04-01 07:43:27', '1775028872_Alvaro Aalto.jpg', 6.90393810, 79.89816441, 0, 46),
(28, 8, 'alto', 'WVH8+GXC, 115 Sri Vajiragnana Mawatha, Colombo 01000, Sri Lanka', 'Colombo', 'Colombo 8', '0743476508', '2026-04-01 10:30:31', '2026-04-01 10:30:31', '1775039384_Alvaro Aalto.jpg', 6.92866681, 79.86726537, 0, 48),
(30, 9, 'Test Site B', '456 Park Rd, Colombo', 'Colombo', 'Colombo', '0776523874', '2026-04-03 10:09:57', '2026-04-03 10:09:57', NULL, NULL, NULL, 1, 38),
(31, 8, 'Final test', 'WVFH+HP7, Colombo 01000, Sri Lanka', 'Colombo', 'Colombo 10', '0743476508', '2026-04-13 17:57:47', '2026-04-13 17:57:47', '1776102960_9 - Image-Led Storytelling.jpg', 6.92464082, 79.87953918, 0, 62),
(32, 8, 'Hello', 'WRQP+32 Port City Colombo, Sri Lanka', 'Colombo', 'Colombo 10', '0743476508', '2026-04-16 18:17:52', '2026-04-16 18:17:52', '1776363377_9 - Image-Led Storytelling.jpg', 6.93769767, 79.83504229, 1, 63);

-- --------------------------------------------------------

--
-- Table structure for table `site_visits`
--

CREATE TABLE `site_visits` (
  `id` int(11) NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `visit_time` datetime NOT NULL,
  `officer_attendance_satisfactory` tinyint(1) DEFAULT 0 COMMENT '1 = satisfactory, 0 = not satisfactory',
  `officer_activities` text DEFAULT NULL COMMENT 'Description of officer activities observed',
  `site_condition` enum('Excellent','Good','Fair','Poor') DEFAULT 'Good',
  `issues_found` text DEFAULT NULL COMMENT 'Any issues or concerns identified',
  `notes` text DEFAULT NULL COMMENT 'Additional notes about the visit',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_visits`
--

INSERT INTO `site_visits` (`id`, `site_id`, `user_id`, `visit_time`, `officer_attendance_satisfactory`, `officer_activities`, `site_condition`, `issues_found`, `notes`, `created_at`) VALUES
(6, 10, 37, '2026-01-26 12:20:45', 1, 'Patrolling', 'Excellent', NULL, NULL, '2026-01-26 06:50:45'),
(7, 12, 37, '2026-01-26 12:21:40', 1, 'Monitoring entrance', 'Good', NULL, NULL, '2026-01-26 06:51:40'),
(8, 13, 37, '2026-01-26 16:24:19', 1, 'Monitoring entrance', 'Fair', 'Officer absent', NULL, '2026-01-26 10:54:19'),
(9, 12, 37, '2026-01-27 21:24:15', 1, 'Patrolling', 'Good', NULL, NULL, '2026-01-27 15:54:15'),
(10, 13, 37, '2026-01-27 21:25:52', 1, 'Monitoring entrance', 'Fair', 'Officer absent', NULL, '2026-01-27 15:55:52'),
(11, 10, 37, '2026-01-27 21:26:02', 1, 'Patrolling', 'Good', NULL, NULL, '2026-01-27 15:56:02'),
(12, 4, 29, '2026-01-27 21:50:09', 1, 'Patrolling', 'Good', NULL, NULL, '2026-01-27 16:20:09'),
(13, 4, 29, '2026-01-31 20:11:09', 1, 'Patrolling', 'Good', NULL, NULL, '2026-01-31 14:41:09'),
(15, 12, 37, '2026-02-13 12:28:41', 1, 'Monitoring entrance', 'Good', NULL, NULL, '2026-02-13 06:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `submittedapplications`
--

CREATE TABLE `submittedapplications` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `NIC` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submittedapplications`
--

INSERT INTO `submittedapplications` (`id`, `name`, `email`, `phone_number`, `date_of_birth`, `NIC`, `gender`, `address`, `district`, `city`, `cv`, `photo`, `submitted_at`, `role`, `status`, `approved_by`, `approved_at`) VALUES
(2, 'Kamal Gunarathne', 'Kamal@gmail.com', '0778912134', NULL, NULL, 'Male', NULL, NULL, NULL, '1766307460_SCS2312 T2.pdf', '1766307460_p1.jpg', '2025-12-21 08:57:40', 'po', 'rejected', NULL, NULL),
(3, 'Jagath Kulathunga', 'Jamath@gmail.com', '0781122353', NULL, NULL, 'Male', NULL, NULL, NULL, '1766307579_SCS2312 T2.pdf', '1766307579_p2.jpg', '2025-12-21 08:59:39', 'ct', 'rejected', NULL, NULL),
(6, 'Sarath Madushanka', 'sarathmadushanka@gamil.com', '0772134123', '1996-02-03', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Damana', '1766486503_SCS2312 T2.pdf', '1766486503_p6.png', '2025-12-23 10:41:43', 'po', 'approved', 1, '2025-12-24 05:16:13'),
(8, 'P.M.T Vithanage', 'pmihirdddangaa321@gmail.com', '0912287654', '2002-06-11', '200227901779', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Galle', 'Yakkalamulla', '1766980785_SCS2312 T2.pdf', '1766980785_p7.jpg', '2025-12-29 03:59:45', 'po', 'approved', 1, '2025-12-29 04:00:07'),
(9, 'Chamara Perera', 'chamara.perera@example.com', '0771234567', '2010-06-23', '901234567', 'Male', 'No. 45, Galle Road, Colombo 03', 'Colombo', 'Colombo 3', '1766989569_SCS2312 T2.pdf', '1766989569_p2.jpg', '2025-12-29 06:26:09', 'po', 'approved', 1, '2025-12-29 06:26:25'),
(10, 'P.M.T Vithanage', 'pmihirandfsfgaa321@gmail.com', '0912287654', '2025-12-24', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Trincomalee', 'Eachchilampattai', '1767083845_SCS2312 T2.pdf', '1767083845_p2.jpg', '2025-12-30 08:37:25', 'po', 'approved', 1, '2025-12-30 08:37:51'),
(11, 'P.M.T Vithanage', 'pmihirangassa321@gmail.com', '0912287654', '2025-12-18', '199612782449', 'Female', 'Kumudu, Addarawatta, Kodagoda,', 'Mullaitivu', 'Kokuthoduvai', '1767148530_SCS2312 T2.pdf', '1767148530_p3.jpg', '2025-12-31 02:35:30', 'ct', 'approved', 1, '2025-12-31 03:35:01'),
(12, 'P.M.T Vithanage', 'pmihirangdddaa321@gmail.com', '0912287654', '2025-12-06', '200227901779', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Mullaitivu', 'Karunaadampan', '1767148584_SCS2312 T2.pdf', '1767148584_p1.jpg', '2025-12-31 02:36:24', 'mr', 'approved', 1, '2025-12-31 02:36:42'),
(13, 'P.M.T Vithanage', 'pmihirangdsasddssa321@gmail.com', '0912287654', '2025-12-12', '901234567', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Thirukkovil', '1767150518_SCS2312 T2.pdf', '1767150518_p1.jpg', '2025-12-31 03:08:38', 'mr', 'approved', 1, '2025-12-31 03:24:38'),
(14, 'Wimalasiri', 'wimalasiri@gmail.com', '0912287654', '2026-01-15', '198022786336', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Trincomalee', 'Kanniya', '1767316857_SCS2312 T2.pdf', '1767316857_p1.jpg', '2026-01-02 01:20:57', 'po', 'approved', 1, '2026-01-02 01:51:07'),
(15, 'Chandrakumara', 'chandra@gmail.com', '0912287623', '2026-01-16', '112233445566', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Mullaitivu', 'Alampil East', '1767327601_SCS2312 T2.pdf', '1767327601_p5.png', '2026-01-02 04:20:01', 'ct', 'approved', 1, '2026-01-02 04:20:24'),
(16, 'Kulathunga', 'kula@gmail.com', '0917723654', '2026-01-15', '299226773997', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Matara', 'Handiya', '1767335484_SCS2312 T2.pdf', '1767335484_p7.jpg', '2026-01-02 06:31:24', 'mr', 'approved', 1, '2026-01-21 12:05:23'),
(17, 'Gajanayake', 'gse@gmail.com', '0772345127', '2026-01-15', '199612782449', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Irakkamam', '1767335555_SCS2312 T2.pdf', '1767335555_p4.jpeg', '2026-01-02 06:32:35', 'mr', 'approved', 1, '2026-01-02 06:43:01'),
(19, 'Kalum Nanayakkara', 'pasanmihiranga04@gmail.com', '0775645234', '2026-02-18', '1122334455', 'Male', 'Kumudu, Addarawatta, Kodagoda,', 'Ampara', 'Thirukkovil', '1769942870_puSubWork (2).pdf', '1769942870_p4.jpeg', '2026-02-01 10:47:50', 'ct', 'approved', 1, '2026-02-01 15:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_duty_points`
--

CREATE TABLE `supervisor_duty_points` (
  `id` int(11) NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` int(11) NOT NULL,
  `duty_point_name` varchar(120) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor_duty_points`
--

INSERT INTO `supervisor_duty_points` (`id`, `site_id`, `created_by`, `duty_point_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 28, 63, 'Main Gate', 'Active', '2026-04-13 15:42:43', '2026-04-13 15:42:43'),
(2, 28, 63, 'Hallway', 'Active', '2026-04-13 15:43:01', '2026-04-13 15:43:01'),
(3, 28, 63, 'Back gate', 'Active', '2026-04-13 15:46:49', '2026-04-13 15:46:49'),
(4, 31, 65, 'Main Gate', 'Active', '2026-04-13 18:13:27', '2026-04-13 18:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_leave_requests`
--

CREATE TABLE `supervisor_leave_requests` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `admin_response` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_online` tinyint(1) DEFAULT 0,
  `last_seen` datetime DEFAULT NULL,
  `role` enum('admin','supervisor','premise officer','mobile rider','client','caretaker') NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userID`, `name`, `email`, `password`, `is_online`, `last_seen`, `role`, `phone_number`, `profile_image`, `status`, `created_at`, `updated_at`, `permissions`) VALUES
(1, 'ADMIN001', 'System Administrator', 'admin@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, '2026-04-17 12:32:09', 'admin', '0112866490', '1767780941_p2jpg', 'active', '2025-12-21 02:43:23', '2026-04-17 07:02:09', '[\"add_officers\",\"add_clients\",\"accept_client_requests\",\"assign_officers\",\"accept_leave_requests\",\"create_advertisements\",\"view_payments\",\"handle_incidents\",\"create_routes\",\"edit_officer_profiles\"]'),
(2, 'SUP001', 'John Supervisor', 'supervisor@redforce.com', '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m', 0, '2026-04-15 23:11:09', 'supervisor', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-04-15 17:41:09', NULL),
(3, 'PO001', 'Nuwan Perera', 'nuwan.perera@redforce.com', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 1, '2026-04-17 10:36:45', 'premise officer', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-04-17 05:06:45', NULL),
(4, 'PO002', 'Kasun Silva', 'kasun.silva@redforce.com', '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W', 0, NULL, 'premise officer', NULL, NULL, 'active', '2025-12-21 02:43:23', '2025-12-21 02:43:23', NULL),
(5, 'MR001', 'Sanjaya Peris', 'sanjaya.peris@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, '2026-03-09 14:23:16', 'mobile rider', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-03-09 08:53:16', NULL),
(6, 'MR002', 'Ramesh Nuwan', 'ramesh.nuwan@redforce.com', '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m', 0, NULL, 'mobile rider', NULL, NULL, 'active', '2025-12-21 02:43:23', '2025-12-21 02:43:23', NULL),
(7, 'CLIENT001', 'Peoples Bank', 'contact@peoplesbank.com', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, '2026-03-31 15:03:44', 'client', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-03-31 09:33:44', NULL),
(8, 'CLIENT002', 'Cargills PLC', 'security@cargills.com', '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W', 0, '2026-02-05 19:28:31', 'client', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-02-05 13:58:31', NULL),
(9, 'CARETAKER001', 'Michael Johnson', 'johnson.michael@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, '2026-02-07 08:03:50', 'caretaker', NULL, NULL, 'active', '2025-12-21 02:43:23', '2026-02-07 02:33:50', NULL),
(10, 'CLIENT003', 'People&apos;s Bank', 'peoplesbank@gmail.com', '$2y$10$8Plkho6K4FtbazY8WJ5iIOTcWHEU8rJ0APRqm4qj75NEe/YFEAxgi', 0, '2026-02-05 19:49:07', 'client', '0113740740', '1766304642_peoples-bank.png', 'active', '2025-12-21 08:26:28', '2026-02-05 14:19:07', NULL),
(17, 'PO003', 'Sarath Madushanka', 'sarathmadushanka@gamil.com', '$2y$10$CHosfleaY8.iZDJ8CxECmOZDbPdljY4AC.4TZUkUwYcmdqx6U3btG', 0, '2026-04-17 10:36:11', 'premise officer', '0772134123', '1766486503_p6.png', 'active', '2025-12-24 05:16:13', '2026-04-17 05:06:11', NULL),
(18, 'PO004', 'P.M.T Vithanage', 'pmihirdddangaa321@gmail.com', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 0, NULL, 'premise officer', '0912287654', '1766980785_p7.jpg', 'active', '2025-12-29 04:00:07', '2025-12-29 04:00:07', NULL),
(19, 'PO005', 'Chamara Perera', 'chamara.perera@example.com', '$2y$10$0aC52xYuZR4pzDOQsUZHreQcI.Pn4s6GcgtRIGs/dZB7DCS1BVnYi', 0, '2026-02-17 17:16:33', 'premise officer', '0771234567', '1766989569_p2.jpg', 'active', '2025-12-29 06:26:25', '2026-02-17 11:46:33', NULL),
(20, 'PO006', 'P.M.T Vithanage', 'pmihirandfsfgaa321@gmail.com', '$2y$10$QFdVISDTakxQ2b9C2RviX.QbvqHiN2q/95km2BICQWq3.OW7pzrMi', 0, NULL, 'premise officer', '0912287654', '1767083845_p2.jpg', 'active', '2025-12-30 08:37:51', '2025-12-30 08:37:51', NULL),
(24, 'MR003', 'P.M.T Vithanage', 'pmihirangdsasddssa321@gmail.com', '$2y$10$w0FWbwrHbivQNe2L4gI55OukLMXdOJBq3cXoMog508aF9KQoyf.li', 0, '2026-03-09 14:23:51', 'mobile rider', '0912287654', '1767150518_p1.jpg', 'active', '2025-12-31 03:24:38', '2026-03-09 08:53:51', NULL),
(25, 'CT001', 'P.M.T Vithanage', 'pmihirangassa321@gmail.com', '$2y$10$qCZqt4aqTfpoUrspa4us9uM.dRuLQOFG4gjFUEHkynWFJu1AdJDa2', 0, '2026-03-09 14:22:52', 'caretaker', '0912287654', '1767148530_p3.jpg', 'active', '2025-12-31 03:35:01', '2026-03-09 08:52:52', NULL),
(26, 'PO007', 'Wimalasiri', 'wimalasiri@gmail.com', '$2y$10$wZg6UKitWJ7migG1jUxM0uxGz7yGwhwFmk4rlrhEfrsQKRY6k8y/W', 0, '2026-03-31 14:33:09', 'premise officer', '0912287654', '1767316857_p1.jpg', 'active', '2026-01-02 01:51:07', '2026-03-31 09:03:09', NULL),
(27, 'CT002', 'Chandrakumara', 'chandra@gmail.com', '$2y$10$UAWcuNbaYT2E5Z3/sGd1nufqMUQfE61hmJfxMrdDeyh2JSeToRG7i', 0, NULL, 'caretaker', '0912287623', '1767327601_p5.png', 'active', '2026-01-02 04:20:24', '2026-01-02 04:20:24', NULL),
(28, 'CLIENT004', 'Company Example', 'company@gmail.com', '$2y$10$uSSYpx0T6ou2MAFDGfmEyeWc5cK4ArdzExciTDIpARCVTXCCMZcF.', 0, NULL, 'client', '0775623123', '1767335768_c1.jpg', 'active', '2026-01-02 06:37:23', '2026-01-02 06:37:23', NULL),
(29, 'MR004', 'Gajanayake', 'gse@gmail.com', '$2y$10$PZ.o24wBB/UQv3iZ5uSAI.Jl5bO/pgX.ic.02Kyqr.B9B51Ps4zba', 0, '2026-03-31 14:32:49', 'mobile rider', '0772345127', '1767335555_p4.jpeg', 'active', '2026-01-02 06:43:01', '2026-03-31 09:02:49', NULL),
(32, 'CLIENT005', 'Example', 'manujakaushika@gmail.com', '$2y$10$NFqyEv82g4sec6KKKyh7Re2FpQT/rfOeX4cUnlxikdkA4L4/Wgw0u', 0, NULL, 'client', '0912287654', '1767756375_Peoples-Bank-Galkiriyagama.jpg', 'active', '2026-01-07 04:06:10', '2026-01-07 04:06:10', NULL),
(33, 'ADMIN002', 'P.M.T Vithanage', 'pmihirwdwedangaa321@gmail.com', '$2y$10$4agfWyKw12IXzIGMHbyOhe1lQr3giGtQk6TXHy1O9V5d.PHnbkpmS', 0, '2026-04-17 11:52:14', 'admin', '0912287654', '69e1d18d78f4a_uwp3164919.jpeg', 'inactive', '2026-01-07 10:21:39', '2026-04-17 06:22:14', NULL),
(34, 'ADMIN003', 'P.M.T Vithanage', 'pmihirdwdwangaa321@gmail.com', '$2y$10$SSSYYIouBJxYoLgTJoOGFuZxqPQbv0utANqGm9ZAXVBP0Um77SOgW', 0, '2026-02-07 08:17:48', 'admin', '0759726372', '1769677203_p7.jpg', 'active', '2026-01-07 10:31:19', '2026-02-07 02:47:48', NULL),
(37, 'MR005', 'Kulathunga', 'kula@gmail.com', '$2y$10$yz8fW8UKV7yDeC4RhBDL4u88QpITZcyXBs6PePl6OZhj0c5W95auq', 0, '2026-02-13 12:30:25', 'mobile rider', '0917723654', '1767335484_p7.jpg', 'active', '2026-01-21 12:05:23', '2026-02-13 07:00:25', NULL),
(40, 'CLIENT006', 'Example Company', 'genz48155@gmail.com', '$2y$10$X97hJUKqmfMfpq9i1ZZBKuTQ4//1elc8gNWuEYJvTQ8EuZBTwTo32', 0, '2026-04-17 11:59:12', 'client', '0912287654', '1768998330_c3.jpg', 'active', '2026-01-21 12:38:45', '2026-04-17 06:29:12', NULL),
(41, 'CLIENT007', 'Company Example 2', 'abc@gmail.com', '$2y$10$dRapsenoxYY4tsIUZr0Tk.GLqXn0.6AizDQTIgqvLamJwFeH38g72', 0, '2026-03-09 15:22:56', 'client', '0776523874', '1767335820_c2.jpg', 'active', '2026-01-21 14:18:58', '2026-03-09 09:52:56', NULL),
(46, 'CLIENT008', 'pasan', 'pmtvithanage@gmail.com', '$2y$10$rXGVaTLya1sioDvBAClge.dFe3PbRDtoyyIoJvtj8UpDlCE80rnKe', 1, '2026-03-31 15:04:46', 'client', '0764465234', '1769943484_s1.jpeg', 'active', '2026-02-01 11:04:23', '2026-03-31 09:34:46', NULL),
(54, 'CT003', 'Kalum Nanayakkara', 'pasanmihiranga04@gmail.com', '$2y$10$4onEQrYiZH9r8X5W9k.mDe2/p5pMwIip5Gy99lKshjS2Sa4FaN2VG', 0, '2026-02-17 17:13:59', 'caretaker', '0775645234', '1769942870_p4.jpeg', 'active', '2026-02-01 15:03:05', '2026-02-17 11:43:59', NULL),
(56, 'CLIENT009', 'UCSC', 'UCSC@gmail.com', '$2y$10$TngivJ0FCJdngFWVEsYLgebxG/vdlyU78EKCiPqlZe9lt.GDWfZgO', 0, NULL, 'client', '0921133456', '1770443070_c1.jpg', 'active', '2026-02-07 05:45:06', '2026-02-07 05:45:06', NULL),
(57, 'PO910', 'Test PO 01', 'po910@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111910', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(58, 'PO911', 'Test PO 02', 'po911@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111911', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(59, 'PO912', 'Test PO 03', 'po912@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111912', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(60, 'PO913', 'Test PO 04', 'po913@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 1, '2026-04-17 12:02:31', 'premise officer', '0711111913', NULL, 'active', '2026-04-01 08:10:22', '2026-04-17 06:32:31', NULL),
(61, 'PO914', 'Test PO 05', 'po914@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 1, '2026-04-13 23:24:32', 'premise officer', '0711111914', NULL, 'active', '2026-04-01 08:10:22', '2026-04-13 17:54:32', NULL),
(62, 'PO915', 'Test SUP 01', 'po915@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111915', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(63, 'PO916', 'Test SUP 02', 'po916@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 1, '2026-04-17 10:35:51', 'premise officer', '0711111916', NULL, 'active', '2026-04-01 08:10:22', '2026-04-17 05:05:51', NULL),
(64, 'PO917', 'Test SUP 03', 'po917@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111917', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(65, 'PO918', 'Test SUP 04', 'po918@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, '2026-04-13 23:44:03', 'premise officer', '0711111918', NULL, 'active', '2026-04-01 08:10:22', '2026-04-13 18:14:03', NULL),
(66, 'PO919', 'Test SUP 05', 'po919@test.local', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 0, NULL, 'premise officer', '0711111919', NULL, 'active', '2026-04-01 08:10:22', '2026-04-01 08:10:22', NULL),
(67, 'PO920', 'Test PO 92', 'test.po.920.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077700920.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(68, 'PO921', 'Test PO 92', 'test.po.921.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077700921.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(69, 'PO922', 'Test PO 92', 'test.po.922.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077700922.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(70, 'PO923', 'Test PO 92', 'test.po.923.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077700923.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(71, 'PO924', 'Test PO 92', 'test.po.924.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, '2026-04-01 17:24:01', 'premise officer', '077700924.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:54:01', NULL),
(72, 'PO925', 'Test SUP 92', 'test.sup.925.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077800925.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(73, 'PO926', 'Test SUP 92', 'test.sup.926.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077800926.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(74, 'PO927', 'Test SUP 92', 'test.sup.927.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077800927.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(75, 'PO928', 'Test SUP 92', 'test.sup.928.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077800928.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(76, 'PO929', 'Test SUP 92', 'test.sup.929.00000000000000000000000000000000000000@redforce.local', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 0, NULL, 'premise officer', '077800929.', NULL, 'active', '2026-04-01 11:46:58', '2026-04-01 11:46:58', NULL),
(77, 'PO901', 'Test PO 901', 'test.po901@redforce.local', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 1, '2026-04-06 12:27:19', 'premise officer', '0719000001', NULL, 'active', '2026-04-06 06:49:57', '2026-04-06 06:57:19', NULL),
(78, 'PO902', 'Test PO 902', 'test.po902@redforce.local', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 0, NULL, 'premise officer', '0719000002', NULL, 'active', '2026-04-06 06:49:57', '2026-04-06 06:49:57', NULL),
(79, 'PO903', 'Test PO 903', 'test.po903@redforce.local', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 1, '2026-04-13 23:45:12', 'premise officer', '0719000003', NULL, 'active', '2026-04-06 06:49:57', '2026-04-13 18:15:12', NULL),
(80, 'PO904', 'Test PO 904', 'test.po904@redforce.local', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 0, NULL, 'premise officer', '0719000004', NULL, 'active', '2026-04-06 06:49:57', '2026-04-06 06:49:57', NULL),
(81, 'PO905', 'Test PO 905', 'test.po905@redforce.local', '$2y$10$uHiIYfrduLj6QnL9cHVFpezK/QOsMAL.X.gDRJO0rbgtatv2JNLge', 0, NULL, 'premise officer', '0719000005', NULL, 'active', '2026-04-06 06:49:57', '2026-04-06 06:49:57', NULL),
(82, 'ADMIN004', 'Haritha Gamage', '1221harwewewfweitha@gmail.com', '$2y$10$94BEJ3upc35zICvq5vVM7.9sz.H2ViOwePDC3Lqm0dFUEbRq6idgm', 0, '2026-04-17 11:52:59', 'admin', '0743476508', '1776402885_Archigram.jpg', 'active', '2026-04-17 05:14:45', '2026-04-17 06:22:59', '[\"add_officers\",\"add_clients\",\"assign_officers\",\"handle_incidents\"]'),
(83, 'ADMIN005', 'Haritha Gamage', 'm200227901880@gmail.com', '$2y$10$KCafEAXWLRN5g8oPSpbLL.Y2ZMDBOq1Tx4IrgfGbMjLWtr72ffxqO', 1, '2026-04-17 12:51:08', 'admin', '0743476508', '1776409249_Immersive 3D Interaction.jpeg', 'active', '2026-04-17 07:00:49', '2026-04-17 07:21:08', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `additional_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_info`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_password_resets`
--

CREATE TABLE `user_password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `temp_password_hash` varchar(255) NOT NULL,
  `old_password_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_password_resets`
--

INSERT INTO `user_password_resets` (`id`, `user_id`, `temp_password_hash`, `old_password_hash`, `expires_at`, `used`, `created_at`, `used_at`) VALUES
(1, 83, '$2y$10$oaYQP69l7Ud6JU6LCVPbTe51JZLYoF5NnwPd1wrcolakKwKA47xV6', '$2y$10$qjUP3UOeqQcUAZFNT2NRZu4TzP.XHudNJ8Ti9hZdjJI.vD2SBCIHe', '2026-04-17 12:34:03', 3, '2026-04-17 12:33:03', '2026-04-17 12:40:07'),
(3, 83, '$2y$10$evpj93tS3NrBNYoNo062X.96UaFO/l1z1LTe07uc8qjdtGOZltHJW', '$2y$10$qjUP3UOeqQcUAZFNT2NRZu4TzP.XHudNJ8Ti9hZdjJI.vD2SBCIHe', '2026-04-17 12:41:07', 1, '2026-04-17 12:40:07', '2026-04-17 12:41:06'),
(4, 83, '$2y$10$A7OFae8GONuf7KK.zj3F3uLasjdYLlthsXmbD7f7HLFbAZGZcydhW', '$2y$10$evpj93tS3NrBNYoNo062X.96UaFO/l1z1LTe07uc8qjdtGOZltHJW', '2026-04-17 12:42:30', 2, '2026-04-17 12:41:30', '2026-04-17 12:43:24'),
(5, 83, '$2y$10$q5AsxP51g1Obn0G3PprMLuBLoC5PJa1wg9vk3RDv/.fHS3ttL0YQi', '$2y$10$evpj93tS3NrBNYoNo062X.96UaFO/l1z1LTe07uc8qjdtGOZltHJW', '2026-04-17 12:44:41', 1, '2026-04-17 12:43:41', '2026-04-17 12:44:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `care_taker_full_details`
--
DROP TABLE IF EXISTS `care_taker_full_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `care_taker_full_details`  AS SELECT `ct`.`id` AS `care_taker_id`, `ct`.`caretakerID` AS `caretakerID`, `ct`.`date_of_birth` AS `date_of_birth`, `ct`.`NIC` AS `NIC`, `ct`.`gender` AS `gender`, `ct`.`address` AS `address`, `ct`.`district` AS `district`, `ct`.`city` AS `city`, `ct`.`hire_date` AS `hire_date`, `ct`.`employment_status` AS `employment_status`, `ct`.`rank` AS `rank`, `ct`.`rating` AS `rating`, `ct`.`shift_pattern` AS `shift_pattern`, `ct`.`application_id` AS `application_id`, `ct`.`created_at` AS `caretaker_record_created`, `ct`.`updated_at` AS `caretaker_record_updated`, `u`.`id` AS `user_id`, `u`.`userID` AS `user_identifier`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`role` AS `role`, `u`.`phone_number` AS `phone_number`, `u`.`profile_image` AS `profile_image`, `u`.`status` AS `user_status`, `u`.`created_at` AS `user_account_created`, `u`.`updated_at` AS `user_account_updated` FROM (`care_taker` `ct` left join `users` `u` on(`ct`.`userID` = `u`.`id`)) WHERE `u`.`role` = 'care taker' OR `ct`.`userID` is not null ;

-- --------------------------------------------------------

--
-- Structure for view `mobile_rider_full_details`
--
DROP TABLE IF EXISTS `mobile_rider_full_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `mobile_rider_full_details`  AS SELECT `mr`.`id` AS `mobile_rider_id`, `mr`.`riderID` AS `riderID`, `mr`.`date_of_birth` AS `date_of_birth`, `mr`.`NIC` AS `NIC`, `mr`.`gender` AS `gender`, `mr`.`address` AS `address`, `mr`.`district` AS `district`, `mr`.`city` AS `city`, `mr`.`hire_date` AS `hire_date`, `mr`.`employment_status` AS `employment_status`, `mr`.`rank` AS `rank`, `mr`.`rating` AS `rating`, `mr`.`shift_pattern` AS `shift_pattern`, `mr`.`application_id` AS `application_id`, `mr`.`created_at` AS `rider_record_created`, `mr`.`updated_at` AS `rider_record_updated`, `r`.`id` AS `route_id`, `r`.`route_name` AS `route_name`, `r`.`description` AS `route_description`, `r`.`location` AS `location`, `r`.`distance_km` AS `distance_km`, `r`.`difficulty_level` AS `difficulty_level`, `r`.`status` AS `route_status`, `u`.`id` AS `user_id`, `u`.`userID` AS `user_identifier`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`role` AS `role`, `u`.`phone_number` AS `phone_number`, `u`.`profile_image` AS `profile_image`, `u`.`status` AS `user_status`, `u`.`created_at` AS `user_account_created`, `u`.`updated_at` AS `user_account_updated` FROM ((`mobile_rider` `mr` left join `users` `u` on(`mr`.`userID` = `u`.`id`)) left join `routes` `r` on(`mr`.`routeID` = `r`.`id`)) WHERE `u`.`role` = 'mobile rider' OR `mr`.`userID` is not null ;

-- --------------------------------------------------------

--
-- Structure for view `premise_officers_full_details`
--
DROP TABLE IF EXISTS `premise_officers_full_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `premise_officers_full_details`  AS SELECT `po`.`id` AS `premise_officer_id`, `po`.`officerID` AS `officerID`, `po`.`date_of_birth` AS `date_of_birth`, `po`.`NIC` AS `NIC`, `po`.`gender` AS `gender`, `po`.`address` AS `address`, `po`.`district` AS `district`, `po`.`city` AS `city`, `po`.`hire_date` AS `hire_date`, `po`.`employment_status` AS `employment_status`, `po`.`rank` AS `rank`, `po`.`rating` AS `rating`, `po`.`shift_pattern` AS `shift_pattern`, `po`.`application_id` AS `application_id`, `po`.`created_at` AS `officer_record_created`, `po`.`updated_at` AS `officer_record_updated`, `u`.`id` AS `user_id`, `u`.`userID` AS `user_identifier`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`role` AS `role`, `u`.`phone_number` AS `phone_number`, `u`.`profile_image` AS `profile_image`, `u`.`status` AS `user_status`, `u`.`created_at` AS `user_account_created`, `u`.`updated_at` AS `user_account_updated` FROM (`premise_officers` `po` left join `users` `u` on(`po`.`userID` = `u`.`id`)) WHERE `u`.`role` = 'premise officer' OR `po`.`userID` is not null ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_officer_id` (`officer_id`),
  ADD KEY `idx_supervisor_id` (`supervisor_id`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indexes for table `caretaker_notes`
--
ALTER TABLE `caretaker_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_caretaker` (`caretaker_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_reminder_date` (`reminder_date`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `caretaker_site_assignments`
--
ALTER TABLE `caretaker_site_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_site_id` (`site_id`),
  ADD KEY `idx_caretaker_id` (`caretaker_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_assigned_by` (`assigned_by`);

--
-- Indexes for table `care_taker`
--
ALTER TABLE `care_taker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `caretakerID` (`caretakerID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `client_requests`
--
ALTER TABLE `client_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_participant` (`conversation_id`,`user_id`),
  ADD KEY `fk_conv_user` (`user_id`);

--
-- Indexes for table `equipment_requests`
--
ALTER TABLE `equipment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_caretaker_id` (`caretaker_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_requested_date` (`requested_date`),
  ADD KEY `idx_status_date` (`status`,`requested_date`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_incident_user` (`user_id`),
  ADD KEY `idx_site_id` (`site_id`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_incident_date` (`incident_date`);

--
-- Indexes for table `incident_reviews`
--
ALTER TABLE `incident_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_incident_id` (`incident_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_review_type` (`review_type`);

--
-- Indexes for table `jobapplication`
--
ALTER TABLE `jobapplication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caretaker_id` (`caretaker_id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `mobilerider_id` (`mobilerider_id`),
  ADD KEY `premiseofficer_id` (`premiseofficer_id`),
  ADD KEY `fk_reviewed_by` (`reviewed_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_recipient` (`recipient_id`),
  ADD KEY `idx_conversation` (`sender_id`,`recipient_id`),
  ADD KEY `idx_read_status` (`is_read`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_read_at` (`read_at`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_message_attach` (`message_id`);

--
-- Indexes for table `mobile_rider`
--
ALTER TABLE `mobile_rider`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `riderID` (`riderID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `routeID` (`routeID`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notes_user_id` (`userID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_user_read` (`user_id`,`is_read`);

--
-- Indexes for table `officer_attendance`
--
ALTER TABLE `officer_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_officer_date` (`officer_id`,`attendance_date`),
  ADD KEY `idx_supervisor_date` (`supervisor_id`,`attendance_date`),
  ADD KEY `idx_officer_id` (`officer_id`),
  ADD KEY `idx_attendance_date` (`attendance_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `officer_performance_ratings`
--
ALTER TABLE `officer_performance_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_officer_rating` (`site_id`,`officer_user_id`,`reviewer_user_id`,`reviewer_role`,`rating_date`),
  ADD KEY `idx_rated_officer` (`officer_user_id`),
  ADD KEY `idx_rating_site` (`site_id`),
  ADD KEY `idx_rating_reviewer` (`reviewer_user_id`,`reviewer_role`),
  ADD KEY `idx_rating_date` (`rating_date`);

--
-- Indexes for table `officer_site_assignments`
--
ALTER TABLE `officer_site_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_site` (`site_id`),
  ADD KEY `idx_officer` (`officer_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_officer_assignment_assigner` (`assigned_by`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_package_name` (`package_name`);

--
-- Indexes for table `package_requests`
--
ALTER TABLE `package_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_client` (`client_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_payment_id` (`payment_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_site_id` (`site_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_date` (`payment_date`),
  ADD KEY `idx_due_date` (`due_date`),
  ADD KEY `idx_package_request_id` (`package_request_id`);

--
-- Indexes for table `premise_officers`
--
ALTER TABLE `premise_officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officerID` (`officerID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `recent_activities`
--
ALTER TABLE `recent_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_routes_assigned_rider` (`assigned_rider_id`);

--
-- Indexes for table `route_sites`
--
ALTER TABLE `route_sites`
  ADD PRIMARY KEY (`route_id`,`site_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `sent_emails`
--
ALTER TABLE `sent_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipient` (`recipient`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sent_at` (`sent_at`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_submitted_date` (`submitted_date`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`,`site_name`);

--
-- Indexes for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_site_user` (`site_id`,`user_id`),
  ADD KEY `idx_visit_date` (`visit_time`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `submittedapplications`
--
ALTER TABLE `submittedapplications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supervisor_duty_points`
--
ALTER TABLE `supervisor_duty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_supervisor_duty_points_creator` (`created_by`),
  ADD KEY `idx_supervisor_duty_points_site` (`site_id`),
  ADD KEY `idx_supervisor_duty_points_status` (`status`);

--
-- Indexes for table `supervisor_leave_requests`
--
ALTER TABLE `supervisor_leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userID` (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_is_online` (`is_online`),
  ADD KEY `idx_last_seen` (`last_seen`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_used` (`user_id`,`used`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caretaker_notes`
--
ALTER TABLE `caretaker_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `caretaker_site_assignments`
--
ALTER TABLE `caretaker_site_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `care_taker`
--
ALTER TABLE `care_taker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `client_requests`
--
ALTER TABLE `client_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_requests`
--
ALTER TABLE `equipment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `incident_reviews`
--
ALTER TABLE `incident_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `jobapplication`
--
ALTER TABLE `jobapplication`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mobile_rider`
--
ALTER TABLE `mobile_rider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `officer_attendance`
--
ALTER TABLE `officer_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `officer_performance_ratings`
--
ALTER TABLE `officer_performance_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_site_assignments`
--
ALTER TABLE `officer_site_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `package_requests`
--
ALTER TABLE `package_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `premise_officers`
--
ALTER TABLE `premise_officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `recent_activities`
--
ALTER TABLE `recent_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `sent_emails`
--
ALTER TABLE `sent_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `submittedapplications`
--
ALTER TABLE `submittedapplications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `supervisor_duty_points`
--
ALTER TABLE `supervisor_duty_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supervisor_leave_requests`
--
ALTER TABLE `supervisor_leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `advertisements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caretaker_notes`
--
ALTER TABLE `caretaker_notes`
  ADD CONSTRAINT `fk_notes_caretaker` FOREIGN KEY (`caretaker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caretaker_site_assignments`
--
ALTER TABLE `caretaker_site_assignments`
  ADD CONSTRAINT `caretaker_site_assignments_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `caretaker_site_assignments_ibfk_2` FOREIGN KEY (`caretaker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `caretaker_site_assignments_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `care_taker`
--
ALTER TABLE `care_taker`
  ADD CONSTRAINT `care_taker_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `care_taker_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `submittedapplications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `Clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_requests`
--
ALTER TABLE `client_requests`
  ADD CONSTRAINT `client_requests_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD CONSTRAINT `fk_conv_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_requests`
--
ALTER TABLE `equipment_requests`
  ADD CONSTRAINT `equipment_requests_ibfk_1` FOREIGN KEY (`caretaker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `fk_incident_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_incident_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incident_reviews`
--
ALTER TABLE `incident_reviews`
  ADD CONSTRAINT `fk_review_incident` FOREIGN KEY (`incident_id`) REFERENCES `incident_reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `fk_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`caretaker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`mobilerider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_4` FOREIGN KEY (`premiseofficer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `fk_attach_message` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mobile_rider`
--
ALTER TABLE `mobile_rider`
  ADD CONSTRAINT `mobile_rider_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mobile_rider_ibfk_2` FOREIGN KEY (`routeID`) REFERENCES `routes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mobile_rider_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `submittedapplications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_notes_user_id` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_attendance`
--
ALTER TABLE `officer_attendance`
  ADD CONSTRAINT `officer_attendance_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_performance_ratings`
--
ALTER TABLE `officer_performance_ratings`
  ADD CONSTRAINT `fk_officer_ratings_officer` FOREIGN KEY (`officer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_officer_ratings_reviewer` FOREIGN KEY (`reviewer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_officer_ratings_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_site_assignments`
--
ALTER TABLE `officer_site_assignments`
  ADD CONSTRAINT `fk_officer_assignment_assigner` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_officer_assignment_officer` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_officer_assignment_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `package_requests`
--
ALTER TABLE `package_requests`
  ADD CONSTRAINT `package_requests_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payments_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `premise_officers`
--
ALTER TABLE `premise_officers`
  ADD CONSTRAINT `premise_officers_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `premise_officers_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `submittedapplications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recent_activities`
--
ALTER TABLE `recent_activities`
  ADD CONSTRAINT `recent_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `fk_routes_assigned_rider` FOREIGN KEY (`assigned_rider_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `route_sites`
--
ALTER TABLE `route_sites`
  ADD CONSTRAINT `route_sites_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `route_sites_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD CONSTRAINT `site_visits_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_visits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisor_duty_points`
--
ALTER TABLE `supervisor_duty_points`
  ADD CONSTRAINT `fk_supervisor_duty_points_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_supervisor_duty_points_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisor_leave_requests`
--
ALTER TABLE `supervisor_leave_requests`
  ADD CONSTRAINT `supervisor_leave_requests_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisor_leave_requests_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
