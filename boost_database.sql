-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2018 at 04:42 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boost`
--
CREATE DATABASE IF NOT EXISTS `martan4_boost` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `martan4_boost`;
-- --------------------------------------------------------

--
-- Table structure for table `tbl_comissions`
--

CREATE TABLE `tbl_comissions` (
  `comm_id` int(11) NOT NULL,
  `policy_id` int(9) NOT NULL,
  `prod_id` int(5) NOT NULL,
  `member_id` int(5) NOT NULL,
  `date_charged` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company`
--

CREATE TABLE `tbl_company` (
  `company_id` int(2) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_company`
--

INSERT INTO `tbl_company` (`company_id`, `name`, `status`) VALUES
(1, 'Manulife', 1),
(2, 'Assumption Life', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_level`
--

CREATE TABLE `tbl_membership_level` (
  `level_tbl_id` int(11) NOT NULL,
  `membership_level` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_membership_level`
--

INSERT INTO `tbl_membership_level` (`level_tbl_id`, `membership_level`) VALUES
(1, 'Trainee'),
(2, 'Associate'),
(3, 'Supervisor'),
(4, 'Manager'),
(5, 'Director');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_payment_rec`
--

CREATE TABLE `tbl_membership_payment_rec` (
  `id` int(6) NOT NULL,
  `transaction_date` date NOT NULL,
  `payment_mode` varchar(32) NOT NULL,
  `transaction_ref` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_access`
--

CREATE TABLE `tbl_member_access` (
  `id` int(9) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `user_type` int(3) NOT NULL,
  `member_tbl_id` int(6) NOT NULL,
  `access_status` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_access`
--

INSERT INTO `tbl_member_access` (`id`, `username`, `password`, `user_type`, `member_tbl_id`, `access_status`) VALUES
(1, 'mtan06', '0069b04af4b1169b580920af0888dbcd', 1, 0, 'Active'),
(2, 'mtan5', '0069b04af4b1169b580920af0888dbcd', 2, 1, 'Active'),
(3, 'ethan05', 'e10adc3949ba59abbe56e057f20f883e', 2, 11, 'Active'),
(4, 'flortan', '785bd98a5c41d11400c6c7e362dafa2c', 2, 9, 'Active'),
(5, 'wowie', 'e10adc3949ba59abbe56e057f20f883e', 2, 2, 'Active'),
(6, 'flortan', '785bd98a5c41d11400c6c7e362dafa2c', 2, 9, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_info`
--

CREATE TABLE `tbl_member_info` (
  `id` int(11) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `mid_name` varchar(64) NOT NULL,
  `sin` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `address` varchar(256) NOT NULL,
  `city` varchar(32) NOT NULL,
  `province` varchar(24) NOT NULL,
  `postal` varchar(12) NOT NULL,
  `contact` varchar(24) NOT NULL,
  `email` varchar(64) NOT NULL,
  `upline_id` int(2) DEFAULT NULL,
  `director_id` int(2) DEFAULT NULL,
  `is_plead_guilty` int(2) NOT NULL,
  `is_bankrupt` int(2) NOT NULL,
  `is_legal_to_work` int(2) NOT NULL,
  `level_id` int(2) DEFAULT NULL,
  `code` varchar(24) NOT NULL,
  `status` int(2) NOT NULL,
  `reg_date` datetime NOT NULL,
  `approved_date` datetime DEFAULT NULL,
  `membership_process` varchar(12) NOT NULL,
  `payment_id` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_info`
--

INSERT INTO `tbl_member_info` (`id`, `first_name`, `last_name`, `mid_name`, `sin`, `dob`, `address`, `city`, `province`, `postal`, `contact`, `email`, `upline_id`, `director_id`, `is_plead_guilty`, `is_bankrupt`, `is_legal_to_work`, `level_id`, `code`, `status`, `reg_date`, `approved_date`, `membership_process`, `payment_id`) VALUES
(1, 'Mark', 'Sample', 'Longanillaz', '123456', '1984-01-18', '1058 Dugald Rd', 'Sample City', 'New Brunswick', 'R2J0H123', '2044303401', 'marktan@email.com', NULL, NULL, 0, 0, 0, 3, 'MT0106841', 1, '2018-04-11 00:00:00', NULL, 'admin', NULL),
(2, 'Rowena', 'Tan', 'Ersando', '456123', '1985-03-14', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'rowena.ersando14@gmail.com', 1, 0, 1, 1, 1, 1, 'RP0314851', 2, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(3, 'Luis', 'Tan', 'Longanilla', '78946', '1984-12-17', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'luis@yahoo.com', 1, NULL, 1, 1, 1, 1, 'LT1217842', 1, '2018-04-08 00:00:00', '2018-04-13 04:51:36', 'Admin', 0),
(8, 'Bryan', 'Cruz', 'N', 'na', '2018-04-17', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', NULL, NULL, 0, 0, 1, 5, 'BC0417183', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(9, 'Florence', 'Tan', 'Gopela', 'na', '2018-04-09', 'na', 'na', 'Manitoba', 'na', 'na', 'na', 1, NULL, 0, 0, 1, 5, 'FT0409188', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(10, 'Marietta', 'Tan', 'Longanilla', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 0, 0, 1, 1, 1, 4, 'MT0416189', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(11, 'Ethan', 'Tan', 'G', 'nna', '2018-04-10', 'na', 'Winnipeg', 'Alberta', 'na', 'na', 'nana', 1, 0, 0, 0, 1, 1, 'ET04101810', 2, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(12, 'Khayla', 'Perey', 'E', 'na', '2018-04-10', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 10, 8, 0, 0, 1, 2, 'KP04101811', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(13, 'Ramon', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 1, 8, 0, 0, 1, 2, 'RR07191812', 1, '2018-04-13 05:27:56', NULL, 'Admin', 0),
(14, 'Daisy', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 9, 0, 0, 1, 1, 'DR07191813', 1, '2018-04-13 05:35:43', NULL, 'Admin', 0),
(17, 'Naruto', 'Uzumaki', 'N', 'na', '2018-04-16', 'na', 'Winnipeg', 'New Brunswick', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'NU04161814', 1, '2018-04-13 05:39:25', NULL, 'Admin', 0),
(18, 'Kenshin', 'Himura', 'N', 'na', '1984-12-17', 'na', 'Winnipeg', 'British Columbia', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'KH12178417', 1, '2018-04-13 05:53:06', NULL, 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_record_status`
--

CREATE TABLE `tbl_member_record_status` (
  `stat_tbl_id` int(11) NOT NULL,
  `record_status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_record_status`
--

INSERT INTO `tbl_member_record_status` (`stat_tbl_id`, `record_status`) VALUES
(1, 'Active'),
(2, 'Pending'),
(3, 'Deactive');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_trustee_details`
--

CREATE TABLE `tbl_member_trustee_details` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `trustee_name` varchar(256) DEFAULT NULL,
  `trustee_address` varchar(256) DEFAULT NULL,
  `location_bankruptcy` varchar(150) DEFAULT NULL,
  `assignment_bankruptcy` varchar(150) DEFAULT NULL,
  `statement_of_affairs` varchar(150) DEFAULT NULL,
  `explanation` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_trustee_details`
--

INSERT INTO `tbl_member_trustee_details` (`id`, `member_id`, `trustee_name`, `trustee_address`, `location_bankruptcy`, `assignment_bankruptcy`, `statement_of_affairs`, `explanation`) VALUES
(1, 3, 'Mark Tan', '513 Bannatyne', '675 leila', 'sample only aob', 'sample soa', 'Sample trustee only'),
(2, 10, 'Mark Tan', '1058 Dugald', 'Sample Loca Bank', 'Sample AOB', 'SOA Example', 'Trial Only'),
(3, 2, 'Liam', 'sample address', 'Jesse', 'Cucu', 'Katelyn', 'ok lang yan');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plead_guilty_explanation`
--

CREATE TABLE `tbl_plead_guilty_explanation` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `explanation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_plead_guilty_explanation`
--

INSERT INTO `tbl_plead_guilty_explanation` (`id`, `member_id`, `explanation`) VALUES
(1, 3, 'Sample Only'),
(2, 10, 'Sample Only'),
(3, 2, 'sample explanation by rowena');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_policy`
--

CREATE TABLE `tbl_policy` (
  `policy_id` int(9) NOT NULL,
  `policy_number` varchar(32) NOT NULL,
  `company_id` int(3) NOT NULL,
  `product_id` int(4) NOT NULL,
  `date_purchased` date NOT NULL,
  `annual_prem` decimal(5,2) DEFAULT NULL,
  `monthly_prem` decimal(5,2) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_policy_holders`
--

CREATE TABLE `tbl_policy_holders` (
  `id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `holder_first_name` varchar(32) NOT NULL,
  `holder_last_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `prod_id` int(5) NOT NULL,
  `company_id` int(4) NOT NULL,
  `prod_name` varchar(64) NOT NULL,
  `fyc` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`prod_id`, `company_id`, `prod_name`, `fyc`, `status`) VALUES
(1, 2, 'Flex Term 10-35', '40.00', 1),
(2, 2, 'CI Term  15-25', '45.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_types`
--

CREATE TABLE `tbl_user_types` (
  `id` int(3) NOT NULL,
  `user_type` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_types`
--

INSERT INTO `tbl_user_types` (`id`, `user_type`) VALUES
(1, 'Admin'),
(2, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_comissions`
--
ALTER TABLE `tbl_comissions`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `tbl_company`
--
ALTER TABLE `tbl_company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `tbl_membership_level`
--
ALTER TABLE `tbl_membership_level`
  ADD PRIMARY KEY (`level_tbl_id`);

--
-- Indexes for table `tbl_membership_payment_rec`
--
ALTER TABLE `tbl_membership_payment_rec`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_member_access`
--
ALTER TABLE `tbl_member_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_member_info`
--
ALTER TABLE `tbl_member_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_member_record_status`
--
ALTER TABLE `tbl_member_record_status`
  ADD PRIMARY KEY (`stat_tbl_id`);

--
-- Indexes for table `tbl_member_trustee_details`
--
ALTER TABLE `tbl_member_trustee_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_plead_guilty_explanation`
--
ALTER TABLE `tbl_plead_guilty_explanation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_policy`
--
ALTER TABLE `tbl_policy`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_comissions`
--
ALTER TABLE `tbl_comissions`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_membership_level`
--
ALTER TABLE `tbl_membership_level`
  MODIFY `level_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_membership_payment_rec`
--
ALTER TABLE `tbl_membership_payment_rec`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_member_access`
--
ALTER TABLE `tbl_member_access`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_member_info`
--
ALTER TABLE `tbl_member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tbl_member_record_status`
--
ALTER TABLE `tbl_member_record_status`
  MODIFY `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_member_trustee_details`
--
ALTER TABLE `tbl_member_trustee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_plead_guilty_explanation`
--
ALTER TABLE `tbl_plead_guilty_explanation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_policy`
--
ALTER TABLE `tbl_policy`
  MODIFY `policy_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prod_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
