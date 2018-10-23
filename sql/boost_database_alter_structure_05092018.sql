-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2018 at 06:03 AM
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

USE martan4_boost;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_status`
--

CREATE TABLE `tbl_commission_status` (
  `commission_status_id` int(3) NOT NULL,
  `commission_status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commissions`
--

CREATE TABLE `tbl_commissions` (
  `comm_id` int(11) NOT NULL,
  `policy_id` int(9) NOT NULL,
  `product_id` int(5) NOT NULL,
  `member_id` int(5) NOT NULL,
  `member_level_id` int(3) NOT NULL,
  `commission_amt` decimal(5,2) NOT NULL,
  `date_charged` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `commission_type` varchar(16) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_record_status`
--

CREATE TABLE `tbl_member_record_status` (
  `stat_tbl_id` int(11) NOT NULL,
  `record_status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_level`
--

CREATE TABLE `tbl_membership_level` (
  `level_tbl_id` int(11) NOT NULL,
  `membership_level` varchar(32) NOT NULL,
  `commission_factor` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_plead_guilty_explanation`
--

CREATE TABLE `tbl_plead_guilty_explanation` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `explanation` text NOT NULL
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
-- Table structure for table `tbl_policy_sold`
--

CREATE TABLE `tbl_policy_sold` (
  `policy_id` int(9) NOT NULL,
  `policy_number` varchar(32) NOT NULL,
  `company_id` int(3) NOT NULL,
  `product_id` int(4) NOT NULL,
  `date_purchased` date NOT NULL,
  `annual_prem` decimal(8,2) DEFAULT NULL,
  `monthly_prem` decimal(8,2) DEFAULT NULL,
  `curr_fyc` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type`
--

CREATE TABLE `tbl_product_type` (
  `product_type_id` int(3) NOT NULL,
  `product_type` varchar(32) NOT NULL
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
  `prod_type` int(2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_types`
--

CREATE TABLE `tbl_user_types` (
  `id` int(3) NOT NULL,
  `user_type` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  ADD PRIMARY KEY (`commission_status_id`);

--
-- Indexes for table `tbl_commissions`
--
ALTER TABLE `tbl_commissions`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `tbl_company`
--
ALTER TABLE `tbl_company`
  ADD PRIMARY KEY (`company_id`);

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
-- Indexes for table `tbl_plead_guilty_explanation`
--
ALTER TABLE `tbl_plead_guilty_explanation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_policy_sold`
--
ALTER TABLE `tbl_policy_sold`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `tbl_product_type`
--
ALTER TABLE `tbl_product_type`
  ADD PRIMARY KEY (`product_type_id`);

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
-- AUTO_INCREMENT for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  MODIFY `commission_status_id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_commissions`
--
ALTER TABLE `tbl_commissions`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_member_access`
--
ALTER TABLE `tbl_member_access`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_member_info`
--
ALTER TABLE `tbl_member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_member_record_status`
--
ALTER TABLE `tbl_member_record_status`
  MODIFY `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_member_trustee_details`
--
ALTER TABLE `tbl_member_trustee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_membership_level`
--
ALTER TABLE `tbl_membership_level`
  MODIFY `level_tbl_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_membership_payment_rec`
--
ALTER TABLE `tbl_membership_payment_rec`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_plead_guilty_explanation`
--
ALTER TABLE `tbl_plead_guilty_explanation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_policy_sold`
--
ALTER TABLE `tbl_policy_sold`
  MODIFY `policy_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_product_type`
--
ALTER TABLE `tbl_product_type`
  MODIFY `product_type_id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prod_id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
