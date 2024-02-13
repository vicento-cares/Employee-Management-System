-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2024 at 09:50 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emp_mgt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_access_locations`
--

CREATE TABLE `m_access_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_access_locations`
--

INSERT INTO `m_access_locations` (`id`, `dept`, `section`, `line_no`, `ip`, `date_updated`) VALUES
(1, 'IT', NULL, NULL, '172.25.112.133', '2024-01-24 13:01:13'),
(2, 'HR', NULL, NULL, '172.25.112.132', '2023-12-07 08:52:38'),
(3, 'PD1', 'FSP', 'Battery Initial', '172.25.114.229', '2024-01-24 11:54:39'),
(4, 'PD2', 'FAP1', '1008', '172.25.112.131', '2024-01-24 13:01:23'),
(5, 'PD2', 'FAP2', '5101', '172.25.111.113', '2023-12-07 08:54:44'),
(6, 'PD2', 'FAP3', '3169', '172.25.111.114', '2023-12-07 08:54:44'),
(7, 'PD2', 'FAP4', '7101', '172.25.111.115', '2023-12-07 08:56:02'),
(8, 'PD2', 'Annex', 'TCTM', '172.25.111.116', '2023-12-07 08:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `m_accounts`
--

CREATE TABLE `m_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_accounts`
--

INSERT INTO `m_accounts` (`id`, `emp_no`, `full_name`, `dept`, `section`, `line_no`, `shift_group`, `role`, `date_updated`) VALUES
(1, '22-08675', 'Alcantara, Vince Dale D.', 'IT', NULL, NULL, NULL, 'admin', '2023-11-30 07:46:49'),
(2, '13-0446', 'Ibana,  Gemlet D.', 'IT', NULL, NULL, NULL, 'admin', '2023-11-30 07:46:49'),
(3, '14-01871', 'Jalla, John Bernard L.', 'IT', NULL, NULL, NULL, 'admin', '2023-11-30 07:46:49'),
(4, '23-09881', 'Jonnel Guevarra M.', 'IT', NULL, NULL, NULL, 'admin', '2023-11-30 07:46:49'),
(5, '14-01899', 'Bathan, Laurice A.', 'IT', NULL, NULL, NULL, 'admin', '2023-11-30 07:46:49'),
(6, '15-03029', 'Fulo, Eduardo Jr. S.', 'IT', NULL, NULL, NULL, 'user', '2024-01-26 09:08:58'),
(8, '1', '1', 'PD2', 'FSP', 'Battery Initial', NULL, 'user', '2024-01-19 14:39:44'),
(9, '2', '2', 'PD2', 'FAP3', '3169', NULL, 'user', '2024-01-19 14:45:05'),
(10, '3', '3', 'PD2', 'FAP1', '1008', 'A', 'user', '2024-02-05 11:51:22'),
(11, '21-06347', 'Macatangay, Jake N.', 'IT', '', '', NULL, 'user', '2024-01-26 09:37:54'),
(12, '33', '33', 'QA', 'QA', '1008', 'B', 'user', '2024-02-05 11:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `m_clinic_accounts`
--

CREATE TABLE `m_clinic_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_clinic_accounts`
--

INSERT INTO `m_clinic_accounts` (`id`, `emp_no`, `full_name`, `dept`, `section`, `line_no`, `role`, `date_updated`) VALUES
(1, '22-08675', 'Alcantara, Vince Dale D.', 'IT', '', '', 'clinic', '2023-09-08 09:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `m_dept`
--

CREATE TABLE `m_dept` (
  `id` int(11) NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_dept`
--

INSERT INTO `m_dept` (`id`, `dept`, `date_updated`) VALUES
(1, 'Accounting', '2023-11-18 09:07:22'),
(2, 'EQD', '2023-11-18 09:07:22'),
(3, 'FG', '2023-11-18 09:07:38'),
(4, 'General Affairs', '2023-11-18 09:07:38'),
(5, 'HR', '2023-11-18 09:07:52'),
(6, 'IMPEX', '2023-11-18 09:07:52'),
(7, 'IT', '2023-11-18 09:08:04'),
(8, 'MM', '2023-11-18 09:08:04'),
(9, 'MP', '2023-11-18 09:08:12'),
(10, 'NF', '2023-11-18 09:08:12'),
(11, 'PDC', '2023-11-18 09:08:28'),
(12, 'PE-AME', '2023-11-18 09:08:28'),
(13, 'PE-MPPD', '2023-11-18 09:08:42'),
(14, 'PEC&C', '2023-11-18 09:08:42'),
(15, 'PMD-PC', '2023-11-18 09:08:58'),
(16, 'PPG', '2023-11-18 09:08:58'),
(17, 'QA', '2023-11-18 09:09:05'),
(18, 'QC', '2023-11-18 09:09:05'),
(19, 'QM', '2023-11-18 09:09:20'),
(20, 'RTS', '2023-11-18 09:09:20'),
(21, 'SAFETY', '2023-11-18 09:09:30'),
(22, 'PD1', '2023-11-18 09:09:46'),
(23, 'PD2', '2023-11-18 09:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `m_employees`
--

CREATE TABLE `m_employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_hired` date DEFAULT NULL,
  `address` varchar(625) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shuttle_route` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_js_s` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_js_s_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_sv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_sv_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_approver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_approver_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resigned` tinyint(1) DEFAULT 0,
  `resigned_date` date DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_employees`
--

INSERT INTO `m_employees` (`id`, `emp_no`, `full_name`, `dept`, `section`, `sub_section`, `process`, `line_no`, `position`, `provider`, `gender`, `shift_group`, `date_hired`, `address`, `contact_no`, `emp_status`, `shuttle_route`, `emp_js_s`, `emp_js_s_no`, `emp_sv`, `emp_sv_no`, `emp_approver`, `emp_approver_no`, `resigned`, `resigned_date`, `date_updated`) VALUES
(1, '13-0446', 'Ibana,  Gemlet D.', 'IT', NULL, NULL, NULL, NULL, 'Supervisor', 'FAS', 'F', 'B', '2013-07-16', 'Lipa Malapit', '09124396688', 'Regular', 'Lipa Malapit', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 08:17:12'),
(2, '14-01871', 'Jalla, John Bernard L.', 'IT', NULL, NULL, NULL, NULL, 'Supervisor', 'FAS', 'M', 'A', '2014-04-29', 'Malvar', '09464651674', 'Regular', 'Malvar', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:15'),
(3, '14-01899', 'Bathan, Laurice A.', 'IT', NULL, NULL, NULL, NULL, 'Staff', 'FAS', 'F', 'B', '2014-01-07', 'Ibaan', '09562565328', 'Regular', 'Ibaan', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:18'),
(4, '15-03029', 'Fulo, Eduardo Jr. S.', 'IT', NULL, NULL, NULL, NULL, 'Jr. Staff', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'Lipa Malayo', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 08:17:21'),
(5, '15-02782', 'Gutierrez,Maricar V.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'F', 'A', '2023-08-23', '', '', 'Regular', 'Lipa Malapit', '', '', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:23'),
(6, '15-02839', 'Mitra, Renelyn R.', 'IT', NULL, NULL, NULL, NULL, 'Jr. Staff', 'FAS', 'F', 'B', '2015-03-15', 'Lipa Malapit', '09453082127', 'Regular', 'Lipa Malapit', '', '', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 1, '2023-09-22', '2024-02-07 08:17:26'),
(7, '17-03139', 'Magpantay, Regine C.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'F', 'A', '2023-08-23', '', '', 'Regular', 'Sto. Tomas Malayo', 'Bathan, Laurice A.', '14-01899', '', '', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:30'),
(8, '17-03137', 'Marasigan, Gay B.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'F', 'A', '2023-08-23', '', '', 'Regular', 'Batangas', '', '', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 1, '2023-08-07', '2024-02-07 08:17:32'),
(9, '21-06814', 'Sauro, Jhon Paulo M.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'Sto. Tomas Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:35'),
(10, '21-06993', 'Ballesteros, John Denver B.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'B', '2023-08-23', '', '', 'Regular', 'San Jose', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:39'),
(11, '21-06733', 'Cena, Emanuel John R.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'Batangas', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:42'),
(12, '21-06347', 'Macatangay, Jake N.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'B', '2023-08-23', '', '', 'Regular', 'Lipa Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, '0000-00-00', '2024-02-07 08:17:45'),
(13, '22-08470', 'Fababaer, Cerijohn H.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'Lipa Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:17:48'),
(14, '23-09813', 'Herrera Ian Dave F.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'Lipa Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:17:50'),
(15, '23-09832', 'Martinez, Russel L.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'B', '2023-08-23', '', '', 'Regular', 'Malvar', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:17:53'),
(16, '23-09772', 'Bersabe John', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'B', '2023-08-23', '', '', 'Regular', 'Malvar', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:17:56'),
(17, 'EN69-7325', 'Fernandez. Raphael Ian M.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Probationary', 'Lipa Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:17:59'),
(18, 'BF-45276', 'Coz Chris Matthew L.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'MAXIM', 'M', 'B', '2023-08-23', '', '', 'Probationary', 'Rosario', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:18:02'),
(19, '22-07775', 'Kalaw, Joshua Clarence L.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Regular', 'San Lucas', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', 1, '2023-07-11', '2024-02-07 08:18:04'),
(20, 'EN69-8327', 'Dela Cruz, Leslie G.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'ONE SOURCE', 'F', 'A', '2023-08-23', '', '', 'Probationary', 'San Pablo via Lipa', 'Bathan, Laurice A.', '14-01899', '', '', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:18:07'),
(21, '22-08675', 'Alcantara, Vince Dale D.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'B', '2022-09-07', 'Malvar', '09458822422', 'Regular', 'Malvar', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:18:10'),
(22, 'MWM00018133', 'Javier, John Dave J.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'MEGATREND', 'M', 'B', '2023-08-23', 'Sto. Tomas Malayo', '0978174793', 'Probationary', 'Sto. Tomas Malayo', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:18:13'),
(23, '23-09881', 'Jonnel Guevarra M.', 'IT', NULL, NULL, NULL, NULL, 'Assistant Manager', 'FAS', 'M', 'A', '2023-04-23', 'Sto. Tomas Malayo', '09569149949', 'Probationary', 'Sto. Tomas Malayo', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:19:18'),
(24, 'MWM00019291', 'Fabul, John Benedict s.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'MEGATREND', 'M', 'B', '2023-08-23', '', '', 'Probationary', 'Malvar', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:19:20'),
(25, 'MWM00019300', 'Saudan, Gilbert M.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'MEGATREND', 'M', 'B', '2023-08-23', '', '', 'Probationary', 'Ibaan', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:19:24'),
(26, '23-10015', 'Montañano, Elaine Joyce S.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'F', 'A', '2023-08-23', '', '', 'Probationary', 'Batangas', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', 1, '2023-10-28', '2024-02-07 08:19:32'),
(27, 'MWM00016524', 'Maiquez, Jessabel C.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'MEGATREND', 'F', 'B', '2023-08-23', '', '', 'Probationary', 'Sto. Tomas Malayo', 'Bathan, Laurice A.', '14-01899', '', '', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:19:38'),
(28, '23-10284', 'Vergara, Raymart A.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'M', 'A', '2023-08-23', '', '', 'Probationary', 'Padre Garcia', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', 0, NULL, '2024-02-07 08:19:35'),
(29, '23-10525', 'Maranan, Allyssa Kate B.', 'IT', NULL, NULL, NULL, NULL, 'Associate', 'FAS', 'F', 'B', '2023-09-06', 'Alitagtag, Batangas', '09284692676', 'Probationary', 'Sta. Teresita', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 08:19:41'),
(41, '1', '1', 'PD2', 'FSP', NULL, NULL, 'Battery Initial', 'Associate', 'FAS', 'M', 'A', '2024-01-18', '1', '1', 'Probationary', 'Batangas', '', '', '', '', '', '', 0, NULL, '2024-02-07 08:19:46'),
(42, '2', '2', 'PD2', 'FAP3', NULL, NULL, '3169', 'Jr. Staff', 'FAS', 'F', 'B', '2024-01-01', '2', '2', 'Regular', 'Ibaan', '', '', '', '', '', '', 0, NULL, '2024-02-07 08:19:48'),
(43, '3', '3', 'PD2', 'FAP1', NULL, 'Assembly', '1008', 'Jr. Staff', 'FAS', 'M', 'A', '2023-10-29', '3', '3', 'Regular', 'Sto. Tomas Malayo', '', '', '', '', '', '', 0, NULL, '2024-02-07 10:30:11'),
(44, '3-1', '3-1', 'PD2', 'FAP1', NULL, 'Sub Assembly', '1008', '', 'FAS', '', 'A', '0000-00-00', '', '', '', 'Batangas', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:21'),
(45, '3-2', '3-2', 'PD2', 'FAP1', NULL, 'Assembly', '1008', '', 'FAS', '', 'A', '0000-00-00', '', '', '', 'Padre Garcia', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:29'),
(46, '3-3', '3-3', 'PD2', 'FAP1', NULL, 'Assembly', '1008', '', 'FAS', '', 'A', '0000-00-00', '', '', '', 'San Pablo via Lipa', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:34'),
(47, '3-4', '3-4', 'QA', 'QA', NULL, 'Inspection', '1008', '', 'FAS', '', 'B', '0000-00-00', '', '', '', 'Malvar', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:39'),
(48, '3-5', '3-5', 'PD2', 'FAP1', NULL, 'Sub Assembly', '1008', '', 'FAS', '', 'B', '0000-00-00', '', '', '', 'Lipa Malayo', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:57'),
(49, '3-6', '3-6', 'PD2', 'FAP1', NULL, 'Assembly', '1008', '', 'FAS', '', 'B', '0000-00-00', '', '', '', 'Malvar', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:51'),
(50, '33', '33', 'QA', 'QA', NULL, 'Inspection', '1008', '', 'FAS', '', 'B', '0000-00-00', '', '', '', 'Sto. Tomas Malayo', '', '', '', '', '', '', 0, '0000-00-00', '2024-02-07 10:30:44');

-- --------------------------------------------------------

--
-- Table structure for table `m_falp_groups`
--

CREATE TABLE `m_falp_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `falp_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_falp_groups`
--

INSERT INTO `m_falp_groups` (`id`, `falp_group`) VALUES
(1, 'Factory 2'),
(2, 'FAP1'),
(3, 'FAP2'),
(4, 'FAP3'),
(5, 'FAP4'),
(6, 'First Process'),
(9, 'QA'),
(7, 'Secondary 1 Process'),
(8, 'Secondary 2 Process');

-- --------------------------------------------------------

--
-- Table structure for table `m_hr_accounts`
--

CREATE TABLE `m_hr_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_hr_accounts`
--

INSERT INTO `m_hr_accounts` (`id`, `emp_no`, `full_name`, `dept`, `section`, `line_no`, `role`, `date_updated`) VALUES
(1, '22-08675', 'Alcantara, Vince Dale D.', 'IT', '', '', 'hr', '2023-09-08 09:09:32'),
(2, '23-09881', 'Jonnel Guevarra M.', 'IT', '', '', 'hr', '2023-11-21 10:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `m_positions`
--

CREATE TABLE `m_positions` (
  `id` int(11) NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_positions`
--

INSERT INTO `m_positions` (`id`, `position`, `date_updated`) VALUES
(1, 'Associate', '2023-11-17 17:48:18'),
(2, 'Jr. Staff', '2023-11-17 17:48:18'),
(3, 'Staff', '2023-11-17 17:48:18'),
(4, 'Supervisor', '2023-11-17 17:48:18'),
(5, 'Assistant Manager', '2023-11-17 17:48:18'),
(6, 'Section Manager', '2023-11-17 17:48:18'),
(7, 'Manager', '2023-11-17 17:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `m_process`
--

CREATE TABLE `m_process` (
  `id` int(10) UNSIGNED NOT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_process`
--

INSERT INTO `m_process` (`id`, `process`) VALUES
(2, 'Assembly'),
(3, 'Inspection'),
(1, 'Sub Assembly');

-- --------------------------------------------------------

--
-- Table structure for table `m_providers`
--

CREATE TABLE `m_providers` (
  `id` int(11) NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_providers`
--

INSERT INTO `m_providers` (`id`, `provider`, `date_updated`) VALUES
(1, 'FAS', '2023-11-17 17:41:16'),
(2, 'PKIMT', '2023-11-17 17:41:16'),
(3, 'MAXIM', '2023-11-17 17:41:16'),
(4, 'ONE SOURCE', '2023-11-17 17:41:16'),
(5, 'MEGATREND', '2023-11-17 17:41:16'),
(6, 'ADD EVEN', '2023-11-17 17:41:16'),
(7, 'GOLDENHAND', '2023-11-17 17:41:16');

-- --------------------------------------------------------

--
-- Table structure for table `m_shuttle_routes`
--

CREATE TABLE `m_shuttle_routes` (
  `id` int(10) UNSIGNED NOT NULL,
  `shuttle_route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_shuttle_routes`
--

INSERT INTO `m_shuttle_routes` (`id`, `shuttle_route`, `date_updated`) VALUES
(1, 'Batangas', '2023-11-17 17:39:50'),
(2, 'San Jose', '2023-11-17 17:39:50'),
(3, 'Ibaan', '2023-11-17 17:39:50'),
(4, 'Rosario', '2023-11-17 17:39:50'),
(5, 'Padre Garcia', '2023-11-17 17:39:50'),
(6, 'Lipa Malayo', '2023-11-17 17:39:50'),
(7, 'Lipa Malapit', '2023-11-17 17:39:50'),
(9, 'Malvar', '2023-11-17 17:39:50'),
(10, 'Sto. Tomas Malayo', '2023-11-17 17:39:50'),
(11, 'San Pablo via Lipa', '2023-11-17 17:39:50'),
(12, 'San Pablo via Sto. Tomas', '2023-11-17 17:39:50'),
(13, 'Sta. Teresita', '2023-11-17 17:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `t_absences`
--

CREATE TABLE `t_absences` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL DEFAULT current_timestamp(),
  `shift_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `absent_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_absences`
--

INSERT INTO `t_absences` (`id`, `emp_no`, `day`, `shift_group`, `absent_type`, `reason`, `date_updated`) VALUES
(2, '14-01871', '2023-07-28', NULL, 'SL', 'Flu', '2024-02-05 17:09:09'),
(3, '14-01899', '2023-07-28', NULL, 'VL', 'Family Gathering', '2024-02-05 17:09:11'),
(4, '3-1', '2024-02-05', 'A', 'VL', 'blah', '2024-02-05 17:25:31');

-- --------------------------------------------------------

--
-- Table structure for table `t_leave_form`
--

CREATE TABLE `t_leave_form` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_form_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_filed` date NOT NULL DEFAULT current_timestamp(),
  `address` varchar(625) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_date_from` date NOT NULL,
  `leave_date_to` date NOT NULL,
  `total_leave_days` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `irt_phone_call` tinyint(1) UNSIGNED NOT NULL,
  `irt_letter` tinyint(1) UNSIGNED NOT NULL,
  `irb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `js_s` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disapproved_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_form_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `sl_r1_1_hrs` int(10) UNSIGNED NOT NULL,
  `sl_r1_1_date` date DEFAULT NULL,
  `sl_r1_1_time_in` time DEFAULT NULL,
  `sl_r1_1_time_out` time DEFAULT NULL,
  `sl_r1_2_days` int(10) UNSIGNED NOT NULL,
  `sl_r1_3_date` date DEFAULT NULL,
  `sl_rc_1_days` int(10) UNSIGNED NOT NULL,
  `sl_rc_2_from` date DEFAULT NULL,
  `sl_rc_2_to` date DEFAULT NULL,
  `sl_rc_3_oc` tinyint(1) UNSIGNED NOT NULL,
  `sl_rc_4_hm` tinyint(1) UNSIGNED NOT NULL,
  `sl_rc_mgh` tinyint(1) UNSIGNED NOT NULL,
  `sl_r2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sl_dr_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sl_dr_date` date DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_leave_form`
--

INSERT INTO `t_leave_form` (`id`, `leave_form_id`, `emp_no`, `date_filed`, `address`, `contact_no`, `leave_type`, `leave_date_from`, `leave_date_to`, `total_leave_days`, `irt_phone_call`, `irt_letter`, `irb`, `reason`, `issued_by`, `js_s`, `sv`, `approver`, `disapproved_by`, `leave_form_status`, `sl_r1_1_hrs`, `sl_r1_1_date`, `sl_r1_1_time_in`, `sl_r1_1_time_out`, `sl_r1_2_days`, `sl_r1_3_date`, `sl_rc_1_days`, `sl_rc_2_from`, `sl_rc_2_to`, `sl_rc_3_oc`, `sl_rc_4_hm`, `sl_rc_mgh`, `sl_r2`, `sl_dr_name`, `sl_dr_date`, `date_updated`) VALUES
(5, 'LAF-2311111209874', '22-08675', '2023-11-11', 'Tanauan', '09458822422', 'LWOP', '2023-11-14', '2023-11-14', 1, 1, 0, 'Jonnel Guevarra', 'Family Matters', 'Alcantara, Vince Dale D.', '', '', '', '', 'pending', 0, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, 0, '', '', NULL, '2023-11-11 12:51:06'),
(6, 'LAF-231111015d397', '23-10525', '2023-11-11', 'Alitagtag, Batangas', '09284692676', 'SL', '2023-11-11', '2023-11-11', 1, 1, 0, 'John Bernard Jalla', 'Fever', 'Maranan, Allyssa Kate B.', '', '', '', '', 'pending', 0, NULL, NULL, NULL, 1, '2023-11-14', 0, NULL, NULL, 1, 0, 1, 'Take Medications', 'Alcantara, Vince Dale D.', '2023-11-11', '2023-11-11 13:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `t_leave_form_history`
--

CREATE TABLE `t_leave_form_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_form_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_filed` date NOT NULL DEFAULT current_timestamp(),
  `address` varchar(625) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_date_from` date NOT NULL,
  `leave_date_to` date NOT NULL,
  `total_leave_days` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `irt_phone_call` tinyint(1) UNSIGNED NOT NULL,
  `irt_letter` tinyint(1) UNSIGNED NOT NULL,
  `irb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `js_s` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disapproved_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_form_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `sl_r1_1_hrs` int(10) UNSIGNED NOT NULL,
  `sl_r1_1_date` date DEFAULT NULL,
  `sl_r1_1_time_in` time DEFAULT NULL,
  `sl_r1_1_time_out` time DEFAULT NULL,
  `sl_r1_2_days` int(10) UNSIGNED NOT NULL,
  `sl_r1_3_date` date DEFAULT NULL,
  `sl_rc_1_days` int(10) UNSIGNED NOT NULL,
  `sl_rc_2_from` date DEFAULT NULL,
  `sl_rc_2_to` date DEFAULT NULL,
  `sl_rc_3_oc` tinyint(1) UNSIGNED NOT NULL,
  `sl_rc_4_hm` tinyint(1) UNSIGNED NOT NULL,
  `sl_rc_mgh` tinyint(1) UNSIGNED NOT NULL,
  `sl_r2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sl_dr_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sl_dr_date` date DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_leave_form_history`
--

INSERT INTO `t_leave_form_history` (`id`, `leave_form_id`, `emp_no`, `date_filed`, `address`, `contact_no`, `leave_type`, `leave_date_from`, `leave_date_to`, `total_leave_days`, `irt_phone_call`, `irt_letter`, `irb`, `reason`, `issued_by`, `js_s`, `sv`, `approver`, `disapproved_by`, `leave_form_status`, `sl_r1_1_hrs`, `sl_r1_1_date`, `sl_r1_1_time_in`, `sl_r1_1_time_out`, `sl_r1_2_days`, `sl_r1_3_date`, `sl_rc_1_days`, `sl_rc_2_from`, `sl_rc_2_to`, `sl_rc_3_oc`, `sl_rc_4_hm`, `sl_rc_mgh`, `sl_r2`, `sl_dr_name`, `sl_dr_date`, `date_updated`) VALUES
(1, 'LAF-23082605f4e4a', '22-08675', '2023-08-26', 'Malvar', '09458822422', 'VL', '2023-08-27', '2023-08-28', 2, 1, 0, 'Maam Ghemlet', 'Going to Province', 'Alcantara, Vince Dale D.', '', 'Jalla, John Bernard L.', 'Jonnel Guevarra M.', '', 'approved', 0, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, 0, '', '', NULL, '2023-11-10 11:11:26'),
(2, 'LAF-230908012c3ac', '22-08675', '2023-09-08', 'Malvar', '09458822422', 'SL', '2023-09-08', '2023-09-08', 1, 1, 0, 'Maam Ghemlet', 'LBM', 'Alcantara, Vince Dale D.', '', '', '', 'Jonnel Guevarra M.', 'disapproved', 0, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, 1, 'Take medications', 'Alcantara, Vince Dale D.', '2023-09-08', '2023-11-10 11:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `t_line_support`
--

CREATE TABLE `t_line_support` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_support_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL DEFAULT current_timestamp(),
  `shift` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_by_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_status_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_status_by_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'added',
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_line_support_history`
--

CREATE TABLE `t_line_support_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_support_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL DEFAULT current_timestamp(),
  `shift` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_by_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_status_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `set_status_by_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'added',
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_line_support_history`
--

INSERT INTO `t_line_support_history` (`id`, `line_support_id`, `emp_no`, `day`, `shift`, `line_no_from`, `line_no_to`, `set_by`, `set_by_no`, `set_status_by`, `set_status_by_no`, `status`, `date_updated`) VALUES
(1, 'LS:240119098e60d', 'MWM00016524', '2024-01-19', 'DS', '', 'Battery Initial', 'Alcantara, Vince Dale D.', '22-08675', '1', '1', 'accepted', '2024-01-19 14:40:10'),
(2, 'LS:240119098e60d', '17-03139', '2024-01-19', 'DS', '', '3169', 'Alcantara, Vince Dale D.', '22-08675', '2', '2', 'rejected', '2024-01-19 14:45:22'),
(3, 'LS:240119098e60d', '13-0446', '2024-01-19', 'DS', '', '1008', 'Alcantara, Vince Dale D.', '22-08675', '3', '3', 'accepted', '2024-01-19 14:49:12'),
(4, 'LS:24012211986a3', '3', '2024-01-22', 'DS', '1008', 'Battery Initial', 'Alcantara, Vince Dale D.', '22-08675', '1', '1', 'accepted', '2024-01-22 11:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `t_notif_line_support`
--

CREATE TABLE `t_notif_line_support` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pending_ls` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `accepted_ls` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rejected_ls` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_notif_line_support`
--

INSERT INTO `t_notif_line_support` (`id`, `emp_no`, `pending_ls`, `accepted_ls`, `rejected_ls`) VALUES
(1, '1', 0, 0, 0),
(2, '13-0446', 0, 0, 0),
(3, '14-01871', 0, 0, 0),
(4, '14-01899', 0, 0, 0),
(5, '15-03029', 0, 0, 0),
(6, '2', 0, 0, 0),
(7, '22-08675', 0, 0, 0),
(8, '23-09881', 0, 0, 0),
(9, '3', 0, 0, 0),
(16, '21-06347', 0, 0, 0),
(17, '33', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `t_shuttle_allocation`
--

CREATE TABLE `t_shuttle_allocation` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL,
  `shift` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shuttle_route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `out_5` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `out_6` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `out_7` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `out_8` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `set_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_shuttle_allocation`
--

INSERT INTO `t_shuttle_allocation` (`id`, `emp_no`, `dept`, `section`, `line_no`, `day`, `shift`, `shift_group`, `shuttle_route`, `out_5`, `out_6`, `out_7`, `out_8`, `set_by`, `date_updated`) VALUES
(10, '13-0446', 'IT', '', '', '2023-07-28', 'DS', NULL, 'Lipa Malapit', 0, 0, 0, 1, 'Alcantara, Vince Dale D.', '2023-09-12 09:18:26'),
(12, '22-08675', 'IT', '', '', '2023-07-28', 'DS', NULL, 'Malvar', 1, 0, 0, 0, 'Alcantara, Vince Dale D.', '2023-09-12 09:17:50'),
(13, '22-08675', 'IT', '', '', '2023-11-11', 'DS', NULL, 'Malvar', 0, 0, 0, 1, 'Alcantara, Vince Dale D.', '2023-11-11 12:40:54'),
(14, '13-0446', 'IT', '', '', '2023-11-21', 'DS', NULL, 'Lipa Malapit', 1, 0, 0, 0, 'Alcantara, Vince Dale D.', '2023-11-21 17:32:14'),
(15, '3', 'PD2', 'FAP1', '1008', '2024-02-07', 'DS', 'A', 'Malvar', 0, 0, 0, 1, '3', '2024-02-08 10:16:40'),
(16, '3', 'PD2', 'FAP1', '1008', '2024-02-08', 'DS', 'A', 'Sto. Tomas Malayo', 0, 0, 0, 1, '3', '2024-02-08 14:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `t_time_in_out`
--

CREATE TABLE `t_time_in_out` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL DEFAULT current_timestamp(),
  `shift` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_in` datetime NOT NULL DEFAULT current_timestamp(),
  `time_out` datetime DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_time_in_out`
--

INSERT INTO `t_time_in_out` (`id`, `emp_no`, `day`, `shift`, `time_in`, `time_out`, `ip`, `date_updated`) VALUES
(25, '22-08675', '2023-07-26', 'DS', '2023-07-26 15:27:57', NULL, NULL, '2023-07-27 09:37:30'),
(26, '22-08675', '2023-07-27', 'DS', '2023-07-27 09:00:00', '2023-07-27 11:00:00', NULL, '2023-07-27 13:23:18'),
(27, '13-0446', '2023-07-28', 'DS', '2023-07-28 11:47:24', '2023-07-28 12:47:53', NULL, '2023-07-28 13:03:05'),
(28, '22-08675', '2023-07-28', 'DS', '2023-07-28 14:47:28', '2023-07-28 14:50:17', NULL, '2023-07-28 14:50:17'),
(29, '22-08675', '2023-08-01', 'DS', '2023-08-01 10:50:27', '2023-08-01 10:50:39', NULL, '2023-08-01 10:50:39'),
(30, '22-08675', '2023-08-08', 'NS', '2023-08-08 18:36:19', '2023-08-08 18:49:34', NULL, '2023-08-08 18:49:34'),
(31, '22-08675', '2023-08-23', 'DS', '2023-08-23 08:29:05', NULL, NULL, '2023-08-23 08:29:05'),
(32, '22-08675', '2023-09-12', 'DS', '2023-09-12 09:15:53', NULL, NULL, '2023-09-12 09:15:53'),
(33, '22-08675', '2023-11-11', 'DS', '2023-11-11 07:14:39', NULL, NULL, '2023-11-11 07:14:39'),
(34, '13-0446', '2023-11-21', 'DS', '2023-11-21 14:57:58', '2023-11-21 17:32:20', NULL, '2023-11-21 17:32:20'),
(39, '13-0446', '2023-11-28', 'DS', '2023-11-28 09:27:01', NULL, '172.25.119.120', '2023-11-28 09:27:01'),
(50, '13-0446', '2023-12-02', 'DS', '2023-12-02 07:10:35', NULL, '172.25.112.131', '2023-12-02 07:10:35'),
(51, '14-01871', '2023-12-02', 'DS', '2023-12-02 07:10:37', NULL, '172.25.112.131', '2023-12-02 07:10:37'),
(52, '14-01899', '2023-12-02', 'DS', '2023-12-02 07:10:37', NULL, '172.25.112.131', '2023-12-02 07:10:37'),
(53, '15-02782', '2023-12-02', 'DS', '2023-12-02 07:10:38', NULL, '172.25.112.131', '2023-12-02 07:10:38'),
(54, '15-03029', '2023-12-02', 'DS', '2023-12-02 07:10:39', NULL, '172.25.112.131', '2023-12-02 07:10:39'),
(55, '17-03139', '2023-12-02', 'DS', '2023-12-02 07:10:52', NULL, '172.25.112.131', '2023-12-02 07:10:52'),
(56, '21-06814', '2023-12-02', 'DS', '2023-12-02 07:10:54', NULL, '172.25.112.131', '2023-12-02 07:10:54'),
(57, '23-10525', '2023-12-12', 'NS', '2023-12-12 16:15:25', NULL, '172.25.112.131', '2023-12-12 16:15:25'),
(58, '21-06814', '2023-12-12', 'NS', '2023-12-12 16:15:27', NULL, '172.25.112.131', '2023-12-12 16:15:27'),
(59, '21-06993', '2023-12-12', 'NS', '2023-12-12 16:15:29', NULL, '172.25.112.131', '2023-12-12 16:15:29'),
(60, '23-09772', '2023-12-12', 'NS', '2023-12-12 16:15:30', NULL, '172.25.112.131', '2023-12-12 16:15:30'),
(61, 'MWM00016524', '2023-12-12', 'NS', '2023-12-12 16:15:31', NULL, '172.25.112.131', '2023-12-12 16:15:31'),
(62, 'MWM00019291', '2023-12-12', 'NS', '2023-12-12 16:15:31', NULL, '172.25.112.131', '2023-12-12 16:15:31'),
(63, '13-0446', '2024-01-18', 'DS', '2024-01-18 14:52:01', NULL, '172.25.112.131', '2024-01-18 14:52:01'),
(64, '15-03029', '2024-01-18', 'DS', '2024-01-18 14:52:03', NULL, '172.25.112.131', '2024-01-18 14:52:03'),
(65, '17-03139', '2024-01-18', 'DS', '2024-01-18 14:52:04', NULL, '172.25.112.131', '2024-01-18 14:52:04'),
(66, '21-06993', '2024-01-18', 'DS', '2024-01-18 14:52:05', NULL, '172.25.112.131', '2024-01-18 14:52:05'),
(67, '22-08470', '2024-01-18', 'DS', '2024-01-18 14:52:06', NULL, '172.25.112.131', '2024-01-18 14:52:06'),
(68, '23-09772', '2024-01-18', 'DS', '2024-01-18 14:52:06', NULL, '172.25.112.131', '2024-01-18 14:52:06'),
(69, 'EN69-8327', '2024-01-18', 'DS', '2024-01-18 14:52:07', NULL, '172.25.112.131', '2024-01-18 14:52:07'),
(70, '23-09881', '2024-01-18', 'DS', '2024-01-18 14:52:08', NULL, '172.25.112.131', '2024-01-18 14:52:08'),
(71, 'MWM00016524', '2024-01-18', 'DS', '2024-01-18 14:52:09', NULL, '172.25.112.131', '2024-01-18 14:52:09'),
(72, '13-0446', '2024-01-19', 'DS', '2024-01-19 09:38:04', NULL, '172.25.112.131', '2024-01-19 09:38:04'),
(73, '15-03029', '2024-01-19', 'DS', '2024-01-19 09:38:05', NULL, '172.25.112.131', '2024-01-19 09:38:05'),
(74, '17-03139', '2024-01-19', 'DS', '2024-01-19 09:38:05', NULL, '172.25.112.131', '2024-01-19 09:38:05'),
(75, '21-06993', '2024-01-19', 'DS', '2024-01-19 09:38:06', NULL, '172.25.112.131', '2024-01-19 09:38:06'),
(76, '22-08470', '2024-01-19', 'DS', '2024-01-19 09:38:06', NULL, '172.25.112.131', '2024-01-19 09:38:06'),
(77, '23-09772', '2024-01-19', 'DS', '2024-01-19 09:38:07', NULL, '172.25.112.131', '2024-01-19 09:38:07'),
(78, 'EN69-8327', '2024-01-19', 'DS', '2024-01-19 09:38:07', NULL, '172.25.112.131', '2024-01-19 09:38:07'),
(79, '23-09881', '2024-01-19', 'DS', '2024-01-19 09:38:08', NULL, '172.25.112.131', '2024-01-19 09:38:08'),
(80, 'MWM00016524', '2024-01-19', 'DS', '2024-01-19 09:38:12', NULL, '172.25.112.131', '2024-01-19 09:38:12'),
(81, '3', '2024-01-22', 'DS', '2024-01-22 09:55:34', '2024-01-22 11:43:33', '172.25.111.112', '2024-01-22 11:43:33'),
(82, '15-02782', '2024-01-22', 'DS', '2024-01-22 09:55:35', '2024-01-22 09:57:56', '172.25.112.131', '2024-01-22 09:57:56'),
(83, '21-06733', '2024-01-22', 'DS', '2024-01-22 09:55:40', '2024-01-22 09:58:00', '172.25.112.131', '2024-01-22 09:58:00'),
(84, '23-09813', '2024-01-22', 'DS', '2024-01-22 09:55:41', '2024-01-22 09:58:02', '172.25.112.131', '2024-01-22 09:58:02'),
(85, 'EN69-7325', '2024-01-22', 'DS', '2024-01-22 09:55:42', '2024-01-22 09:58:02', '172.25.112.131', '2024-01-22 09:58:02'),
(86, '22-08675', '2024-01-22', 'DS', '2024-01-22 09:55:43', '2024-01-22 09:58:04', '172.25.112.131', '2024-01-22 09:58:04'),
(87, 'MWM00019291', '2024-01-22', 'DS', '2024-01-22 09:55:44', '2024-01-22 09:58:29', '172.25.112.131', '2024-01-22 09:58:29'),
(88, '23-10284', '2024-01-22', 'DS', '2024-01-22 09:55:48', '2024-01-22 09:58:09', '172.25.112.131', '2024-01-22 09:58:09'),
(90, '1', '2024-01-24', 'DS', '2024-01-24 13:04:34', NULL, '172.25.114.229', '2024-01-24 13:04:34'),
(91, '3', '2024-01-26', 'DS', '2024-01-26 10:08:55', NULL, '172.25.112.131', '2024-01-26 10:08:55'),
(92, '3', '2024-02-05', 'NS', '2024-02-05 17:22:57', NULL, '172.25.112.131', '2024-02-05 17:22:57'),
(93, '3', '2024-02-06', 'DS', '2024-02-06 08:03:07', NULL, '172.25.112.131', '2024-02-06 08:03:07'),
(94, '3', '2024-02-07', 'DS', '2024-02-07 09:56:06', NULL, '172.25.112.131', '2024-02-07 09:56:06'),
(95, '3', '2024-02-08', 'DS', '2024-02-08 07:48:54', NULL, '172.25.112.131', '2024-02-08 07:48:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_access_locations`
--
ALTER TABLE `m_access_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `m_accounts`
--
ALTER TABLE `m_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `m_clinic_accounts`
--
ALTER TABLE `m_clinic_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `m_dept`
--
ALTER TABLE `m_dept`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dept` (`dept`);

--
-- Indexes for table `m_employees`
--
ALTER TABLE `m_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `m_falp_groups`
--
ALTER TABLE `m_falp_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `falp_group` (`falp_group`);

--
-- Indexes for table `m_hr_accounts`
--
ALTER TABLE `m_hr_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `m_positions`
--
ALTER TABLE `m_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider` (`position`),
  ADD UNIQUE KEY `position` (`position`);

--
-- Indexes for table `m_process`
--
ALTER TABLE `m_process`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `process` (`process`);

--
-- Indexes for table `m_providers`
--
ALTER TABLE `m_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider` (`provider`);

--
-- Indexes for table `m_shuttle_routes`
--
ALTER TABLE `m_shuttle_routes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shuttle_route` (`shuttle_route`);

--
-- Indexes for table `t_absences`
--
ALTER TABLE `t_absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `day` (`day`),
  ADD KEY `shift_group` (`shift_group`);

--
-- Indexes for table `t_leave_form`
--
ALTER TABLE `t_leave_form`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_form_id` (`leave_form_id`),
  ADD KEY `emp_no` (`emp_no`);

--
-- Indexes for table `t_leave_form_history`
--
ALTER TABLE `t_leave_form_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_form_id` (`leave_form_id`),
  ADD KEY `emp_no` (`emp_no`);

--
-- Indexes for table `t_line_support`
--
ALTER TABLE `t_line_support`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `day` (`day`),
  ADD KEY `shift` (`shift`),
  ADD KEY `line_support_id` (`line_support_id`);

--
-- Indexes for table `t_line_support_history`
--
ALTER TABLE `t_line_support_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `day` (`day`),
  ADD KEY `shift` (`shift`),
  ADD KEY `line_support_id` (`line_support_id`);

--
-- Indexes for table `t_notif_line_support`
--
ALTER TABLE `t_notif_line_support`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `t_shuttle_allocation`
--
ALTER TABLE `t_shuttle_allocation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `day` (`day`),
  ADD KEY `shift` (`shift`),
  ADD KEY `shift_group` (`shift_group`);

--
-- Indexes for table `t_time_in_out`
--
ALTER TABLE `t_time_in_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `day` (`day`),
  ADD KEY `shift` (`shift`),
  ADD KEY `ip` (`ip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_access_locations`
--
ALTER TABLE `m_access_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_accounts`
--
ALTER TABLE `m_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `m_clinic_accounts`
--
ALTER TABLE `m_clinic_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_dept`
--
ALTER TABLE `m_dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `m_employees`
--
ALTER TABLE `m_employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `m_falp_groups`
--
ALTER TABLE `m_falp_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_hr_accounts`
--
ALTER TABLE `m_hr_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_positions`
--
ALTER TABLE `m_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_process`
--
ALTER TABLE `m_process`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_providers`
--
ALTER TABLE `m_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_shuttle_routes`
--
ALTER TABLE `m_shuttle_routes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_absences`
--
ALTER TABLE `t_absences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_leave_form`
--
ALTER TABLE `t_leave_form`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_leave_form_history`
--
ALTER TABLE `t_leave_form_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_line_support`
--
ALTER TABLE `t_line_support`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `t_line_support_history`
--
ALTER TABLE `t_line_support_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `t_notif_line_support`
--
ALTER TABLE `t_notif_line_support`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `t_shuttle_allocation`
--
ALTER TABLE `t_shuttle_allocation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `t_time_in_out`
--
ALTER TABLE `t_time_in_out`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
