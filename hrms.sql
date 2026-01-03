-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 03, 2026 at 10:11 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT 0.00,
  `status` enum('present','absent','half-day','leave','weekend','holiday') DEFAULT 'absent',
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `head_employee_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `head_employee_id`, `is_active`, `created_at`) VALUES
(1, 'Human Resources', 'HR', 'Human Resources Department', NULL, 1, '2026-01-03 06:00:55'),
(2, 'Information Technology', 'IT', 'IT and Technical Support', NULL, 1, '2026-01-03 06:00:55'),
(3, 'Finance', 'FIN', 'Finance and Accounting', NULL, 1, '2026-01-03 06:00:55'),
(4, 'Marketing', 'MKT', 'Marketing and Sales', NULL, 1, '2026-01-03 06:00:55'),
(5, 'Operations', 'OPS', 'Operations Management', NULL, 1, '2026-01-03 06:00:55'),
(6, 'Customer Support', 'CS', 'Customer Service and Support', NULL, 1, '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `min_salary` decimal(10,2) DEFAULT 0.00,
  `max_salary` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `code`, `department_id`, `description`, `min_salary`, `max_salary`, `is_active`, `created_at`) VALUES
(1, 'HR Manager', 'HRM', 1, NULL, '50000.00', '120000.00', 1, '2026-01-03 06:00:55'),
(2, 'HR Executive', 'HRE', 1, NULL, '25000.00', '50000.00', 1, '2026-01-03 06:00:55'),
(3, 'Software Engineer', 'SE', 2, NULL, '40000.00', '100000.00', 1, '2026-01-03 06:00:55'),
(4, 'Senior Software Engineer', 'SSE', 2, NULL, '80000.00', '200000.00', 1, '2026-01-03 06:00:55'),
(5, 'Finance Manager', 'FM', 3, NULL, '60000.00', '150000.00', 1, '2026-01-03 06:00:55'),
(6, 'Accountant', 'ACC', 3, NULL, '25000.00', '60000.00', 1, '2026-01-03 06:00:55'),
(7, 'Marketing Manager', 'MM', 4, NULL, '45000.00', '120000.00', 1, '2026-01-03 06:00:55'),
(8, 'Sales Executive', 'SEX', 4, NULL, '20000.00', '50000.00', 1, '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'India',
  `pincode` varchar(10) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `job_type` enum('full-time','part-time','contract','intern') DEFAULT 'full-time',
  `joining_date` date DEFAULT NULL,
  `employment_status` enum('active','inactive','terminated','resigned') DEFAULT 'active',
  `basic_salary` decimal(10,2) DEFAULT 0.00,
  `hra` decimal(10,2) DEFAULT 0.00,
  `da` decimal(10,2) DEFAULT 0.00,
  `other_allowances` decimal(10,2) DEFAULT 0.00,
  `total_salary` decimal(10,2) DEFAULT 0.00,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relation` varchar(50) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `aadhar_path` varchar(255) DEFAULT NULL,
  `pan_path` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `phone`, `address`, `city`, `state`, `country`, `pincode`, `department`, `position`, `job_type`, `joining_date`, `employment_status`, `basic_salary`, `hra`, `da`, `other_allowances`, `total_salary`, `bank_name`, `account_number`, `ifsc_code`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `profile_pic`, `resume_path`, `aadhar_path`, `pan_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'New', 'User', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'Not Assigned', 'Not Assigned', 'full-time', '2026-01-03', 'active', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-03 06:21:03', '2026-01-03 06:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_structure`
--

CREATE TABLE `employee_salary_structure` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `type` enum('national','state','optional','company') DEFAULT 'national',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `date`, `type`, `description`, `is_active`, `created_at`) VALUES
(1, 'New Year', '2024-01-01', 'national', 'New Year Day', 1, '2026-01-03 06:00:55'),
(2, 'Republic Day', '2024-01-26', 'national', 'Republic Day of India', 1, '2026-01-03 06:00:55'),
(3, 'Holi', '2024-03-25', 'national', 'Festival of Colors', 1, '2026-01-03 06:00:55'),
(4, 'Good Friday', '2024-03-29', 'national', 'Good Friday', 1, '2026-01-03 06:00:55'),
(5, 'Independence Day', '2024-08-15', 'national', 'Independence Day of India', 1, '2026-01-03 06:00:55'),
(6, 'Gandhi Jayanti', '2024-10-02', 'national', 'Mahatma Gandhi Birthday', 1, '2026-01-03 06:00:55'),
(7, 'Diwali', '2024-10-31', 'national', 'Festival of Lights', 1, '2026-01-03 06:00:55'),
(8, 'Christmas', '2024-12-25', 'national', 'Christmas Day', 1, '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balance`
--

CREATE TABLE `leave_balance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `total_allotted` int(11) DEFAULT 0,
  `used` int(11) DEFAULT 0,
  `remaining` int(11) DEFAULT 0,
  `carried_over` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) DEFAULT 1,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `applied_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `short_code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `max_days_per_year` int(11) DEFAULT 0,
  `requires_approval` tinyint(1) DEFAULT 1,
  `is_paid` tinyint(1) DEFAULT 1,
  `color_code` varchar(7) DEFAULT '#007bff',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `short_code`, `description`, `max_days_per_year`, `requires_approval`, `is_paid`, `color_code`, `is_active`, `created_at`) VALUES
(1, 'Casual Leave', 'CL', 'Casual leave for personal work', 12, 1, 1, '#007bff', 1, '2026-01-03 06:00:55'),
(2, 'Sick Leave', 'SL', 'Leave due to illness', 12, 1, 1, '#28a745', 1, '2026-01-03 06:00:55'),
(3, 'Earned Leave', 'EL', 'Privilege or earned leave', 15, 1, 1, '#ffc107', 1, '2026-01-03 06:00:55'),
(4, 'Maternity Leave', 'ML', 'Maternity leave for female employees', 180, 1, 1, '#e83e8c', 1, '2026-01-03 06:00:55'),
(5, 'Paternity Leave', 'PL', 'Paternity leave for male employees', 7, 1, 1, '#17a2b8', 1, '2026-01-03 06:00:55'),
(6, 'Compensatory Off', 'CO', 'Compensatory leave for overtime', 0, 1, 1, '#6f42c1', 1, '2026-01-03 06:00:55'),
(7, 'Leave Without Pay', 'LWP', 'Unpaid leave', 0, 1, 0, '#dc3545', 1, '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `notice_type` enum('general','urgent','holiday','policy') DEFAULT 'general',
  `target_audience` enum('all','department','individual') DEFAULT 'all',
  `target_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `month_year` varchar(7) NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `basic_salary` decimal(10,2) DEFAULT 0.00,
  `hra` decimal(10,2) DEFAULT 0.00,
  `da` decimal(10,2) DEFAULT 0.00,
  `other_allowances` decimal(10,2) DEFAULT 0.00,
  `overtime_pay` decimal(10,2) DEFAULT 0.00,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `total_earnings` decimal(10,2) DEFAULT 0.00,
  `pf_deduction` decimal(10,2) DEFAULT 0.00,
  `tax_deduction` decimal(10,2) DEFAULT 0.00,
  `professional_tax` decimal(10,2) DEFAULT 0.00,
  `leave_deduction` decimal(10,2) DEFAULT 0.00,
  `other_deductions` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `payment_status` enum('pending','paid','hold') DEFAULT 'pending',
  `payment_mode` enum('bank_transfer','cash','cheque') DEFAULT 'bank_transfer',
  `transaction_id` varchar(100) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `generated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_components`
--

CREATE TABLE `salary_components` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('earning','deduction') NOT NULL,
  `calculation_type` enum('fixed','percentage') DEFAULT 'fixed',
  `percentage_of` varchar(50) DEFAULT NULL,
  `is_taxable` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_components`
--

INSERT INTO `salary_components` (`id`, `name`, `type`, `calculation_type`, `percentage_of`, `is_taxable`, `is_active`, `description`, `created_at`) VALUES
(1, 'Basic Salary', 'earning', 'fixed', NULL, 1, 1, 'Basic component of salary', '2026-01-03 06:00:55'),
(2, 'House Rent Allowance', 'earning', 'percentage', NULL, 1, 1, 'HRA for housing expenses', '2026-01-03 06:00:55'),
(3, 'Dearness Allowance', 'earning', 'percentage', NULL, 1, 1, 'DA for cost of living', '2026-01-03 06:00:55'),
(4, 'Conveyance Allowance', 'earning', 'fixed', NULL, 1, 1, 'Transport allowance', '2026-01-03 06:00:55'),
(5, 'Medical Allowance', 'earning', 'fixed', NULL, 1, 1, 'Medical expense allowance', '2026-01-03 06:00:55'),
(6, 'Special Allowance', 'earning', 'fixed', NULL, 1, 1, 'Special allowance', '2026-01-03 06:00:55'),
(7, 'Provident Fund', 'deduction', 'percentage', NULL, 0, 1, 'Employee PF contribution', '2026-01-03 06:00:55'),
(8, 'Professional Tax', 'deduction', 'fixed', NULL, 1, 1, 'Professional tax deduction', '2026-01-03 06:00:55'),
(9, 'Income Tax', 'deduction', 'percentage', NULL, 1, 1, 'TDS deduction', '2026-01-03 06:00:55'),
(10, 'Loan Recovery', 'deduction', 'fixed', NULL, 1, 1, 'Loan installment', '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','array','json') DEFAULT 'string',
  `category` varchar(50) DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Dayflow HRMS', 'string', 'general', 'Company name', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(2, 'company_address', '123 Business Park, Mumbai, India', 'string', 'general', 'Company address', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(3, 'company_email', 'hr@dayflow.com', 'string', 'general', 'Company email', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(4, 'company_phone', '+91 9876543210', 'string', 'general', 'Company phone', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(5, 'working_hours_per_day', '8', 'number', 'attendance', 'Standard working hours per day', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(6, 'work_start_time', '09:00:00', 'string', 'attendance', 'Office start time', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(7, 'work_end_time', '18:00:00', 'string', 'attendance', 'Office end time', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(8, 'lunch_start_time', '13:00:00', 'string', 'attendance', 'Lunch break start', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(9, 'lunch_end_time', '14:00:00', 'string', 'attendance', 'Lunch break end', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(10, 'attendance_tolerance', '15', 'number', 'attendance', 'Late tolerance in minutes', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(11, 'salary_payment_day', '5', 'number', 'payroll', 'Salary payment day of month', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(12, 'currency', 'INR', 'string', 'payroll', 'Currency code', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(13, 'currency_symbol', '₹', 'string', 'payroll', 'Currency symbol', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(14, 'financial_year_start', '04-01', 'string', 'payroll', 'Financial year start date (MM-DD)', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55'),
(15, 'financial_year_end', '03-31', 'string', 'payroll', 'Financial year end date (MM-DD)', 0, '2026-01-03 06:00:55', '2026-01-03 06:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 06:49:36'),
(2, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 06:56:58'),
(3, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 07:11:46'),
(4, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 07:20:29'),
(5, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 07:31:33'),
(6, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 08:14:38'),
(7, 1, 'user_login', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 09:08:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','hr','admin') DEFAULT 'employee',
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `email`, `password`, `role`, `is_verified`, `verification_token`, `created_at`, `updated_at`) VALUES
(1, '1', 'krupatanna@gmail.com', '$2y$10$OS6tisjiKUQZx7.bf.NtiORXASmKsDyavG3POCHUf1Q2GhSuCiEpK', 'employee', 1, NULL, '2026-01-03 06:21:03', '2026-01-03 06:21:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`employee_id`,`date`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_attendance_employee_date` (`employee_id`,`date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `head_employee_id` (`head_employee_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_employees_user_id` (`user_id`),
  ADD KEY `idx_employees_department` (`department`);

--
-- Indexes for table `employee_salary_structure`
--
ALTER TABLE `employee_salary_structure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_holiday` (`date`);

--
-- Indexes for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_balance` (`employee_id`,`leave_type_id`,`year`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_leave_requests_employee_status` (`employee_id`,`status`),
  ADD KEY `idx_leave_requests_status` (`status`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_payroll` (`employee_id`,`month_year`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_payroll_month_year` (`month_year`),
  ADD KEY `idx_payroll_employee_month` (`employee_id`,`month_year`);

--
-- Indexes for table `salary_components`
--
ALTER TABLE `salary_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_salary_structure`
--
ALTER TABLE `employee_salary_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leave_balance`
--
ALTER TABLE `leave_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_components`
--
ALTER TABLE `salary_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`head_employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `employee_salary_structure`
--
ALTER TABLE `employee_salary_structure`
  ADD CONSTRAINT `employee_salary_structure_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_salary_structure_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `salary_components` (`id`);

--
-- Constraints for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD CONSTRAINT `leave_balance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_balance_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`),
  ADD CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
