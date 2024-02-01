-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2023 at 04:27 AM
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
-- Database: `it_emp_mgt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_accounts`
--

CREATE TABLE `m_accounts` (
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
-- Dumping data for table `m_accounts`
--

INSERT INTO `m_accounts` (`id`, `emp_no`, `full_name`, `dept`, `section`, `line_no`, `role`, `date_updated`) VALUES
(1, '22-08675', 'Alcantara, Vince Dale D.', 'IT', '', '', 'admin', '2023-09-08 09:05:23'),
(2, '13-0446', 'Ibana,  Gemlet D.', 'IT', '', '', 'admin', '2023-09-13 07:21:23'),
(3, '14-01871', 'Jalla, John Bernard L.', 'IT', '', '', 'admin', '2023-09-13 07:21:46'),
(4, '23-09881', 'Jonnel Guevarra M.', 'IT', '', '', 'admin', '2023-09-13 07:22:08'),
(5, '14-01899', 'Bathan, Laurice A.', 'IT', '', '', 'admin', '2023-09-13 07:22:52'),
(6, '15-03029', 'Fulo, Eduardo Jr. S.', 'IT', '', '', 'admin', '2023-09-13 07:23:18'),
(7, '15-02839', 'Mitra, Renelyn R.', 'IT', '', '', 'admin', '2023-09-13 07:24:10');

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
-- Table structure for table `m_employees`
--

CREATE TABLE `m_employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dept` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_hired` date NOT NULL,
  `address` varchar(625) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shuttle_route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_js_s` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_js_s_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_sv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_sv_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_approver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_approver_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_employees`
--

INSERT INTO `m_employees` (`id`, `emp_no`, `full_name`, `dept`, `section`, `line_no`, `position`, `provider`, `date_hired`, `address`, `contact_no`, `emp_status`, `shuttle_route`, `emp_js_s`, `emp_js_s_no`, `emp_sv`, `emp_sv_no`, `emp_approver`, `emp_approver_no`, `date_updated`) VALUES
(1, '13-0446', 'Ibana,  Gemlet D.', 'IT', '', '', 'Supervisor', 'FAS', '2013-07-16', 'Lipa Malapit', '09124396688', 'Regular', 'Lipa Malapit', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:18:18'),
(2, '14-01871', 'Jalla, John Bernard L.', 'IT', '', '', 'Supervisor', 'FAS', '2014-04-29', 'Malvar', '09464651674', 'Regular', 'Malvar', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:18:51'),
(3, '14-01899', 'Bathan, Laurice A.', 'IT', '', '', 'Staff', 'FAS', '2014-01-07', 'Ibaan', '09562565328', 'Regular', 'Ibaan', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:18:56'),
(4, '15-03029', 'Fulo, Eduardo Jr. S.', 'IT', '', '', 'Jr. Staff', 'FAS', '2023-08-23', '', '', '', 'Lipa Malayo', '', '', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:19:15'),
(5, '15-02782', 'Gutierrez,Maricar V.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Lipa Malapit', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:20:41'),
(6, '15-02839', 'Mitra, Renelyn R.', 'IT', '', '', 'Jr. Staff', 'FAS', '2015-03-15', 'Lipa Malapit', '09453082127', 'Regular', 'Lipa Malapit', '', '', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:20:49'),
(7, '17-03139', 'Magpantay, Regine C.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Sto. Tomas Malayo', 'Bathan, Laurice A.', '14-01899', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:21:16'),
(8, '17-03137', 'Marasigan, Gay B.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Batangas', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:21:34'),
(9, '21-06814', 'Sauro, Jhon Paulo M.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Sto. Tomas Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:21:48'),
(10, '21-06993', 'Ballesteros, John Denver B.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'San Jose', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:22:02'),
(11, '21-06733', 'Cena, Emanuel John R.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Batangas', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:22:13'),
(12, '21-06347', 'Macatangay, Jake N.', 'IT', '', '', 'Associate', 'FAS', '2023-08-23', '', '', '', 'Lipa Malayo', 'Fulo, Eduardo Jr. S.', '15-03029', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:22:24'),
(13, '22-08470', 'Fababaer, Cerijohn H.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Lipa Malayo', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(14, '23-09813', 'Herrera Ian Dave F.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Lipa Malayo', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(15, '23-09832', 'Martinez, Russel L.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Malvar', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(16, '23-09772', 'Bersabe John', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Malvar', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(17, 'EN69-7325', 'Fernandez. Raphael Ian M.', 'IT', '', '', '', 'ONE SOURCE', '2023-08-23', '', '', '', 'Lipa Malayo', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(18, 'BF-45276', 'Coz Chris Matthew L.', 'IT', '', '', '', 'MAXIM', '2023-08-23', '', '', '', 'Rosario', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(19, '22-07775', 'Kalaw, Joshua Clarence L.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'San Lucas', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(20, 'EN69-8327', 'Dela Cruz, Leslie G.', 'IT', '', '', '', 'ONE SOURCE', '2023-08-23', '', '', '', 'San Pablo via Lipa', '', '', '', '', '', '', '2023-08-02 15:54:03'),
(21, '22-08675', 'Alcantara, Vince Dale D.', 'IT', '', '', 'Associate', 'FAS', '2022-09-07', 'Malvar', '09458822422', 'Regular', 'Malvar', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:22:45'),
(22, 'MWM00018133', 'Javier, John Dave J.', 'IT', '', '', 'Associate', 'MEGATREND', '2023-08-23', 'Sto. Tomas Malayo', '0978174793', 'Probationary', 'Sto. Tomas Malayo', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:22:58'),
(23, '23-09881', 'Jonnel Guevarra M.', 'IT', '', '', 'Assistant Manager', 'FAS', '2023-04-23', 'Sto. Tomas Malayo', '09569149949', 'Probationary', 'Sto. Tomas Malayo', '', '', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:23:07'),
(24, 'MWM00019291', 'Fabul, John Benedict s.', 'IT', '', '', '', 'MEGATREND', '2023-08-23', '', '', '', 'Malvar', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:23:19'),
(25, 'MWM00019300', 'Saudan, Gilbert M.', 'IT', '', '', '', 'MEGATREND', '2023-08-23', '', '', '', 'Ibaan', 'Mitra, Renelyn R.', '15-02839', 'Ibana, Gemlet D.', '13-0446', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:23:31'),
(26, '23-10015', 'Montañano, Elaine Joyce S.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Batangas', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:23:40'),
(27, 'MWM00016524', 'Maiquez, Jessabel C.', 'IT', '', '', '', 'MEGATREND', '2023-08-23', '', '', '', 'Sto. Tomas Malayo', 'Bathan, Laurice A.', '14-01899', '', '', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:23:49'),
(28, '23-10284', 'Vergara, Raymart A.', 'IT', '', '', '', 'FAS', '2023-08-23', '', '', '', 'Padre Garcia', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:24:00'),
(29, '23-10525', 'Maranan, Allyssa Kate B.', 'IT', '', '', 'Associate', 'FAS', '2023-09-06', 'Alitagtag, Batangas', '09284692676', 'Probationary', 'Sta. Teresita', '', '', 'Jalla, John Bernard L.', '14-01871', 'Jonnel Guevarra M.', '23-09881', '2023-09-12 11:24:09');

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
(1, '22-08675', 'Alcantara, Vince Dale D.', 'IT', '', '', 'hr', '2023-09-08 09:09:32');

-- --------------------------------------------------------

--
-- Table structure for table `m_shuttle_routes`
--

CREATE TABLE `m_shuttle_routes` (
  `id` int(10) UNSIGNED NOT NULL,
  `shuttle_route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_shuttle_routes`
--

INSERT INTO `m_shuttle_routes` (`id`, `shuttle_route`) VALUES
(1, 'Batangas'),
(3, 'Ibaan'),
(7, 'Lipa Malapit'),
(6, 'Lipa Malayo'),
(9, 'Malvar'),
(5, 'Padre Garcia'),
(4, 'Rosario'),
(2, 'San Jose'),
(8, 'San Lucas'),
(11, 'San Pablo via Lipa'),
(12, 'San Pablo via Sto. Tomas'),
(13, 'Sta. Teresita'),
(10, 'Sto. Tomas Malayo');

-- --------------------------------------------------------

--
-- Table structure for table `t_absences`
--

CREATE TABLE `t_absences` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` date NOT NULL DEFAULT current_timestamp(),
  `shift` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `absent_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_absences`
--

INSERT INTO `t_absences` (`id`, `emp_no`, `day`, `shift`, `absent_type`, `reason`, `date_updated`) VALUES
(2, '14-01871', '2023-07-28', 'DS', 'SL', 'May Sakit', '2023-08-04 11:26:04'),
(3, '14-01899', '2023-07-28', 'DS', 'VL', 'Bakasyon Bakasyon Bakasyon Bakasyon', '2023-08-04 13:52:38');

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
(1, 'LAF-23082605f4e4a', '22-08675', '2023-08-26', 'Malvar', '09458822422', 'VL', '2023-08-27', '2023-08-28', 2, 1, 0, 'Maam Ghemlet', 'Magbabakasyon', 'Alcantara, Vince Dale D.', '', 'Jalla, John Bernard L.', 'Jonnel Guevarra M.', '', 'approved', 0, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, 0, '', '', NULL, '2023-09-13 07:35:46'),
(2, 'LAF-230908012c3ac', '22-08675', '2023-09-08', 'Malvar', '09458822422', 'SL', '2023-09-08', '2023-09-08', 1, 1, 0, 'Maam Ghemlet', 'Pagod', 'Alcantara, Vince Dale D.', '', '', '', 'Jonnel Guevarra M.', 'disapproved', 0, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, 1, 'TEST REMARKS NURSE DOCTOR 1', 'Alcantara, Vince Dale D.', '2023-09-08', '2023-09-13 07:42:17');

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

INSERT INTO `t_shuttle_allocation` (`id`, `emp_no`, `dept`, `section`, `line_no`, `day`, `shift`, `shuttle_route`, `out_5`, `out_6`, `out_7`, `out_8`, `set_by`, `date_updated`) VALUES
(10, '13-0446', 'IT', '', '', '2023-07-28', 'DS', 'Lipa Malapit', 0, 0, 0, 1, 'Alcantara, Vince Dale D.', '2023-09-12 09:18:26'),
(12, '22-08675', 'IT', '', '', '2023-07-28', 'DS', 'Malvar', 1, 0, 0, 0, 'Alcantara, Vince Dale D.', '2023-09-12 09:17:50');

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
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_time_in_out`
--

INSERT INTO `t_time_in_out` (`id`, `emp_no`, `day`, `shift`, `time_in`, `time_out`, `date_updated`) VALUES
(25, '22-08675', '2023-07-26', 'DS', '2023-07-26 15:27:57', NULL, '2023-07-27 09:37:30'),
(26, '22-08675', '2023-07-27', 'DS', '2023-07-27 09:00:00', '2023-07-27 11:00:00', '2023-07-27 13:23:18'),
(27, '13-0446', '2023-07-28', 'DS', '2023-07-28 11:47:24', '2023-07-28 12:47:53', '2023-07-28 13:03:05'),
(28, '22-08675', '2023-07-28', 'DS', '2023-07-28 14:47:28', '2023-07-28 14:50:17', '2023-07-28 14:50:17'),
(29, '22-08675', '2023-08-01', 'DS', '2023-08-01 10:50:27', '2023-08-01 10:50:39', '2023-08-01 10:50:39'),
(30, '22-08675', '2023-08-08', 'NS', '2023-08-08 18:36:19', '2023-08-08 18:49:34', '2023-08-08 18:49:34'),
(31, '22-08675', '2023-08-23', 'DS', '2023-08-23 08:29:05', NULL, '2023-08-23 08:29:05'),
(32, '22-08675', '2023-09-12', 'DS', '2023-09-12 09:15:53', NULL, '2023-09-12 09:15:53');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `m_employees`
--
ALTER TABLE `m_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

--
-- Indexes for table `m_hr_accounts`
--
ALTER TABLE `m_hr_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`);

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
  ADD KEY `emp_no` (`emp_no`);

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
-- Indexes for table `t_shuttle_allocation`
--
ALTER TABLE `t_shuttle_allocation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`);

--
-- Indexes for table `t_time_in_out`
--
ALTER TABLE `t_time_in_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_no` (`emp_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_accounts`
--
ALTER TABLE `m_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_clinic_accounts`
--
ALTER TABLE `m_clinic_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_employees`
--
ALTER TABLE `m_employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `m_hr_accounts`
--
ALTER TABLE `m_hr_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_shuttle_routes`
--
ALTER TABLE `m_shuttle_routes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_absences`
--
ALTER TABLE `t_absences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_leave_form`
--
ALTER TABLE `t_leave_form`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_leave_form_history`
--
ALTER TABLE `t_leave_form_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_shuttle_allocation`
--
ALTER TABLE `t_shuttle_allocation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_time_in_out`
--
ALTER TABLE `t_time_in_out`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
