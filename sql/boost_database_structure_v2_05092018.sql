-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2018 at 06:15 AM
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

--USE martan4_boost;
USE `boost`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `GetAllDownlines`$$
CREATE PROCEDURE `GetAllDownlines` (`mbr_id` INT(4))  BEGIN 

SELECT COUNT(*) FROM `tbl_member_info` WHERE upline_id=mbr_id INTO @downline_count;

IF @downline_count > 0 THEN

	CREATE TEMPORARY TABLE output_downline_members (id INT(4), first_name VARCHAR(64), last_name VARCHAR(64), mid_name VARCHAR(64), code VARCHAR(24), upline_id INT(4)) AS (SELECT id, first_name, last_name, mid_name, code, upline_id from tbl_member_info WHERE upline_id=mbr_id);
	CREATE TEMPORARY TABLE downline_members (id INT(4)) AS (SELECT id from tbl_member_info WHERE upline_id=mbr_id);
	SELECT COUNT(*) FROM `downline_members` INTO @downline_count;

	WHILE @downline_count > 0 DO
	
		SELECT id FROM downline_members LIMIT 1 INTO mbr_id; 

		INSERT INTO downline_members(id)
		SELECT id FROM tbl_member_info where upline_id=mbr_id;
    
		INSERT INTO output_downline_members(id, first_name, last_name, mid_name, code, upline_id)
		SELECT id, first_name, last_name, mid_name, code, upline_id FROM tbl_member_info where upline_id=mbr_id;    

		DELETE FROM downline_members where id = mbr_id;

		SELECT COUNT(*) FROM downline_members INTO @downline_count;

	END WHILE;

	SELECT * FROM output_downline_members;
	DROP TABLE output_downline_members;
	DROP TABLE downline_members;
    
END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_status`
--

DROP TABLE IF EXISTS `tbl_commission_status`;
CREATE TABLE IF NOT EXISTS `tbl_commission_status` (
  `commission_status_id` int(3) NOT NULL AUTO_INCREMENT,
  `commission_status` varchar(16) NOT NULL,
  PRIMARY KEY (`commission_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commissions`
--

DROP TABLE IF EXISTS `tbl_commissions`;
CREATE TABLE IF NOT EXISTS `tbl_commissions` (
  `comm_id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(9) NOT NULL,
  `product_id` int(5) NOT NULL,
  `member_id` int(5) NOT NULL,
  `member_level_id` int(3) NOT NULL,
  `commission_amt` decimal(5,2) NOT NULL,
  `date_charged` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `commission_type` varchar(16) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company`
--

DROP TABLE IF EXISTS `tbl_company`;
CREATE TABLE IF NOT EXISTS `tbl_company` (
  `company_id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_access`
--

DROP TABLE IF EXISTS `tbl_member_access`;
CREATE TABLE IF NOT EXISTS `tbl_member_access` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `user_type` int(3) NOT NULL,
  `member_tbl_id` int(6) NOT NULL,
  `access_status` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_info`
--

DROP TABLE IF EXISTS `tbl_member_info`;
CREATE TABLE IF NOT EXISTS `tbl_member_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `payment_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_record_status`
--

DROP TABLE IF EXISTS `tbl_member_record_status`;
CREATE TABLE IF NOT EXISTS `tbl_member_record_status` (
  `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_status` varchar(12) NOT NULL,
  PRIMARY KEY (`stat_tbl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_trustee_details`
--

DROP TABLE IF EXISTS `tbl_member_trustee_details`;
CREATE TABLE IF NOT EXISTS `tbl_member_trustee_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `trustee_name` varchar(256) DEFAULT NULL,
  `trustee_address` varchar(256) DEFAULT NULL,
  `location_bankruptcy` varchar(150) DEFAULT NULL,
  `assignment_bankruptcy` varchar(150) DEFAULT NULL,
  `statement_of_affairs` varchar(150) DEFAULT NULL,
  `explanation` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_level`
--

DROP TABLE IF EXISTS `tbl_membership_level`;
CREATE TABLE IF NOT EXISTS `tbl_membership_level` (
  `level_tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_level` varchar(32) NOT NULL,
  `commission_factor` int(3) DEFAULT NULL,
  PRIMARY KEY (`level_tbl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_payment_rec`
--

DROP TABLE IF EXISTS `tbl_membership_payment_rec`;
CREATE TABLE IF NOT EXISTS `tbl_membership_payment_rec` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `transaction_date` date NOT NULL,
  `payment_mode` varchar(32) NOT NULL,
  `transaction_ref` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plead_guilty_explanation`
--

DROP TABLE IF EXISTS `tbl_plead_guilty_explanation`;
CREATE TABLE IF NOT EXISTS `tbl_plead_guilty_explanation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `explanation` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_policy_holders`
--

DROP TABLE IF EXISTS `tbl_policy_holders`;
CREATE TABLE IF NOT EXISTS `tbl_policy_holders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `holder_first_name` varchar(32) NOT NULL,
  `holder_last_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_policy_sold`
--

DROP TABLE IF EXISTS `tbl_policy_sold`;
CREATE TABLE IF NOT EXISTS `tbl_policy_sold` (
  `policy_id` int(9) NOT NULL AUTO_INCREMENT,
  `policy_number` varchar(32) NOT NULL,
  `company_id` int(3) NOT NULL,
  `product_id` int(4) NOT NULL,
  `date_purchased` date NOT NULL,
  `annual_prem` decimal(8,2) DEFAULT NULL,
  `monthly_prem` decimal(8,2) DEFAULT NULL,
  `curr_fyc` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type`
--

DROP TABLE IF EXISTS `tbl_product_type`;
CREATE TABLE IF NOT EXISTS `tbl_product_type` (
  `product_type_id` int(3) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(32) NOT NULL,
  PRIMARY KEY (`product_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

DROP TABLE IF EXISTS `tbl_products`;
CREATE TABLE IF NOT EXISTS `tbl_products` (
  `prod_id` int(5) NOT NULL AUTO_INCREMENT,
  `company_id` int(4) NOT NULL,
  `prod_name` varchar(64) NOT NULL,
  `fyc` decimal(5,2) NOT NULL,
  `prod_type` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_types`
--

DROP TABLE IF EXISTS `tbl_user_types`;
CREATE TABLE IF NOT EXISTS `tbl_user_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
