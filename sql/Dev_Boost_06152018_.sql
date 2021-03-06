-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 10.123.0.53:3306
-- Generation Time: Jun 15, 2018 at 11:05 PM
-- Server version: 5.6.27
-- PHP Version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `martan4_boost`
--
DROP DATABASE IF EXISTS `martan4_boost`;
CREATE DATABASE IF NOT EXISTS `martan4_boost` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `martan4_boost`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `GetAllDownlines`$$
CREATE DEFINER=`martan4_boost`@`%` PROCEDURE `GetAllDownlines` (`mbr_id` INT(4))  BEGIN 

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
    
ELSE

  CREATE TEMPORARY TABLE output_downline_members (id INT(4), first_name VARCHAR(64), last_name VARCHAR(64), mid_name VARCHAR(64), code VARCHAR(24), upline_id INT(4)) 
  AS (SELECT id, first_name, last_name, mid_name, code, upline_id from tbl_member_info WHERE upline_id=mbr_id);

    SELECT * FROM output_downline_members;
    DROP TABLE output_downline_members;

END IF;

END$$

DROP PROCEDURE IF EXISTS `GetAllDownlineSales`$$
CREATE DEFINER=`martan4_boost`@`%` PROCEDURE `GetAllDownlineSales` (`mbr_id` INT(4), `period_start` DATETIME, `period_end` DATETIME)  BEGIN 

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

    SELECT 
    output_downline_members.id,
    output_downline_members.first_name, 
    output_downline_members.last_name, 
    output_downline_members.mid_name, 
    output_downline_members.code, 
    output_downline_members.upline_id,
    CONCAT(tbl_member_info.first_name, " ", tbl_member_info.last_name) AS upline_name,
    tbl_membership_level.membership_level,
    tbl_membership_level.commission_factor,
    tbl_policy_sold.policy_number,
    tbl_products.prod_name,
    tbl_company.name AS company_name,
    tbl_policy_sold.annual_prem,
    tbl_policy_sold.curr_fyc,
    tbl_commissions.commission_amt,
    tbl_commissions.status,
    tbl_commission_status.commission_status,
    tbl_commissions.date_charged
    
    FROM output_downline_members
    LEFT JOIN tbl_commissions ON tbl_commissions.member_id=output_downline_members.id    
    LEFT JOIN tbl_policy_sold ON tbl_policy_sold.policy_id=tbl_commissions.policy_id
    LEFT JOIN tbl_products ON tbl_products.prod_id = tbl_policy_sold.product_id
    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_policy_sold.company_id
    LEFT JOIN tbl_membership_level ON tbl_membership_level.level_tbl_id=tbl_commissions.member_level_id
    LEFT JOIN tbl_member_info ON tbl_member_info.id=output_downline_members.upline_id
    LEFT JOIN tbl_commission_status ON tbl_commission_status.commission_status_id=tbl_commissions.status
    
    WHERE tbl_commissions.date_charged BETWEEN DATE(period_start) AND DATE(period_end);
    
    DROP TABLE output_downline_members;
    DROP TABLE downline_members;
    
ELSE

  CREATE TEMPORARY TABLE output_downline_members (id INT(4), first_name VARCHAR(64), last_name VARCHAR(64), mid_name VARCHAR(64), code VARCHAR(24), upline_id INT(4)) 
  AS (SELECT id, first_name, last_name, mid_name, code, upline_id from tbl_member_info WHERE upline_id=mbr_id);

    SELECT * FROM output_downline_members;
    DROP TABLE output_downline_members;

END IF;

END$$

DELIMITER ;

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
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commissions`
--

INSERT INTO `tbl_commissions` (`comm_id`, `policy_id`, `product_id`, `member_id`, `member_level_id`, `commission_amt`, `date_charged`, `date_modified`, `commission_type`, `status`) VALUES
(65, 18, 1, 17, 2, '810.00', '2018-04-27', '2018-06-01', 'agent', 3),
(66, 18, 1, 13, 2, '0.00', '2018-04-27', '2018-06-01', 'upline', 3),
(67, 18, 1, 1, 3, '120.00', '2018-04-27', '2018-06-01', 'upline', 3),
(68, 18, 1, 8, 5, '240.00', '2018-04-27', '2018-06-01', 'upline', 3),
(69, 19, 2, 20, 2, '692.55', '2018-04-30', '2018-06-01', 'agent', 2),
(70, 19, 2, 12, 2, '0.00', '2018-04-30', '2018-06-01', 'upline', 2),
(71, 19, 2, 10, 4, '205.20', '2018-04-30', '2018-06-01', 'upline', 2),
(72, 19, 2, 9, 5, '102.60', '2018-04-30', '2018-06-01', 'upline', 2),
(73, 20, 1, 3, 3, '540.00', '2018-04-30', '2018-06-01', 'agent', 2),
(74, 20, 1, 1, 3, '0.00', '2018-04-30', '2018-06-01', 'upline', 2),
(75, 20, 1, 8, 5, '144.00', '2018-04-30', '2018-06-01', 'upline', 2),
(76, 21, 1, 26, 2, '734.40', '2018-05-02', '2018-06-01', 'agent', 2),
(77, 21, 1, 13, 2, '0.00', '2018-05-02', '2018-06-01', 'upline', 2),
(78, 21, 1, 1, 3, '81.60', '2018-05-02', '2018-06-01', 'upline', 2),
(79, 21, 1, 8, 5, '163.20', '2018-05-02', '2018-06-01', 'upline', 2),
(80, 22, 2, 25, 3, '999.99', '2018-05-02', '2018-06-01', 'agent', 2),
(81, 22, 2, 12, 2, '0.00', '2018-05-02', '2018-06-01', 'upline', 2),
(82, 22, 2, 10, 4, '516.24', '2018-05-02', '2018-06-01', 'upline', 2),
(83, 22, 2, 9, 5, '258.12', '2018-05-02', '2018-06-01', 'upline', 2),
(88, 24, 3, 20, 2, '602.64', '2018-05-05', '2018-06-01', 'agent', 2),
(89, 24, 3, 12, 2, '0.00', '2018-05-05', '2018-06-01', 'upline', 2),
(90, 24, 3, 10, 4, '267.84', '2018-05-05', '2018-06-01', 'upline', 2),
(91, 24, 3, 9, 5, '133.92', '2018-05-05', '2018-06-01', 'upline', 2),
(92, 25, 3, 20, 2, '630.00', '2018-05-05', '2018-06-01', 'agent', 2),
(93, 25, 3, 12, 2, '0.00', '2018-05-05', '2018-06-01', 'upline', 2),
(94, 25, 3, 10, 4, '280.00', '2018-05-05', '2018-06-01', 'upline', 2),
(95, 25, 3, 9, 5, '140.00', '2018-05-05', '2018-06-01', 'upline', 2),
(100, 27, 2, 13, 2, '44.55', '2018-05-10', '2018-06-01', 'agent', 2),
(101, 27, 2, 1, 3, '4.95', '2018-05-10', '2018-06-01', 'upline', 2),
(102, 27, 2, 8, 5, '9.90', '2018-05-10', '2018-06-01', 'upline', 2),
(103, 28, 1, 1, 5, '603.00', '2018-06-02', '2018-06-02', 'agent', 2),
(104, 28, 1, 21, 1, '201.00', '2018-06-02', NULL, 'trainee', 1),
(105, 29, 2, 1, 5, '303.75', '2018-06-02', '2018-06-02', 'agent', 2),
(106, 29, 2, 21, 1, '101.25', '2018-06-02', NULL, 'trainee', 1),
(107, 30, 1, 3, 3, '502.50', '2018-06-02', '2018-06-05', 'agent', 2),
(108, 30, 1, 22, 1, '167.50', '2018-06-02', NULL, 'trainee', 1),
(109, 30, 1, 1, 5, '134.00', '2018-06-02', '2018-06-02', 'upline', 2),
(110, 31, 2, 3, 3, '253.13', '2018-06-02', '2018-06-05', 'agent', 2),
(111, 31, 2, 22, 1, '84.38', '2018-06-02', NULL, 'trainee', 1),
(112, 31, 2, 1, 5, '67.50', '2018-06-02', '2018-06-02', 'upline', 2),
(113, 0, 1, 13, 2, '526.50', '2018-06-05', NULL, 'agent', 1),
(114, 0, 1, 23, 1, '175.50', '2018-06-05', NULL, 'trainee', 1),
(115, 0, 1, 1, 5, '234.00', '2018-06-05', NULL, 'upline', 1),
(116, 0, 2, 13, 2, '290.08', '2018-06-05', NULL, 'agent', 1),
(117, 0, 2, 23, 1, '96.69', '2018-06-05', NULL, 'trainee', 1),
(118, 0, 2, 1, 5, '128.93', '2018-06-05', NULL, 'upline', 1),
(119, 32, 1, 1, 5, '702.00', '2018-06-05', NULL, 'agent', 1),
(120, 32, 1, 21, 1, '234.00', '2018-06-05', NULL, 'trainee', 1),
(121, 33, 2, 1, 5, '386.78', '2018-06-05', NULL, 'agent', 1),
(122, 33, 2, 21, 1, '128.93', '2018-06-05', NULL, 'trainee', 1),
(123, 0, 32, 13, 2, '675.00', '2018-06-06', NULL, 'agent', 1),
(124, 0, 32, 23, 1, '225.00', '2018-06-06', NULL, 'trainee', 1),
(125, 0, 32, 1, 5, '300.00', '2018-06-06', NULL, 'upline', 1),
(126, 0, 40, 13, 2, '162.00', '2018-06-06', NULL, 'agent', 1),
(127, 0, 40, 23, 1, '54.00', '2018-06-06', NULL, 'trainee', 1),
(128, 0, 40, 1, 5, '72.00', '2018-06-06', NULL, 'upline', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_status`
--

DROP TABLE IF EXISTS `tbl_commission_status`;
CREATE TABLE IF NOT EXISTS `tbl_commission_status` (
  `commission_status_id` int(3) NOT NULL AUTO_INCREMENT,
  `commission_status` varchar(16) NOT NULL,
  PRIMARY KEY (`commission_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commission_status`
--

INSERT INTO `tbl_commission_status` (`commission_status_id`, `commission_status`) VALUES
(1, 'Pending'),
(2, 'Released'),
(3, 'ChargedBack');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_company`
--

INSERT INTO `tbl_company` (`company_id`, `name`, `status`) VALUES
(1, 'Manulife', 1),
(2, 'Assumption Life', 1),
(3, 'Sample Company', 0),
(4, 'I.A.P.', 1),
(5, 'Equitable Life', 1),
(6, 'Humania', 1),
(7, 'Desjardin', 1),
(8, 'Ivari', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_membership_level`
--

INSERT INTO `tbl_membership_level` (`level_tbl_id`, `membership_level`, `commission_factor`) VALUES
(1, 'Trainee', 0),
(2, 'Associate', 80),
(3, 'Supervisor', 100),
(4, 'Manager', 120),
(5, 'Director', 140);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_access`
--

INSERT INTO `tbl_member_access` (`id`, `username`, `password`, `user_type`, `member_tbl_id`, `access_status`) VALUES
(1, 'admin01', 'c680cd379b6fc58d5394ceb20aee63b4', 1, 0, 'Active'),
(2, 'mtan5', '0069b04af4b1169b580920af0888dbcd', 2, 1, 'Active'),
(3, 'ethan05', 'e10adc3949ba59abbe56e057f20f883e', 2, 11, 'Active'),
(4, 'flortan', '785bd98a5c41d11400c6c7e362dafa2c', 2, 9, 'Active'),
(5, 'wowie', 'e10adc3949ba59abbe56e057f20f883e', 2, 2, 'Active'),
(6, 'mrttan', '4ab81afca77637e9b40f524aeb7df4aa', 2, 10, 'Active'),
(7, 'naruto', '2e97725f74c7c2f959e9ba31837a4247', 2, 17, 'Active');

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
  `payment_id` varchar(32) DEFAULT NULL,
  `transaction_id` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_info`
--

INSERT INTO `tbl_member_info` (`id`, `first_name`, `last_name`, `mid_name`, `sin`, `dob`, `address`, `city`, `province`, `postal`, `contact`, `email`, `upline_id`, `director_id`, `is_plead_guilty`, `is_bankrupt`, `is_legal_to_work`, `level_id`, `code`, `status`, `reg_date`, `approved_date`, `membership_process`, `payment_id`, `transaction_id`) VALUES
(1, 'Mark', 'Tan', 'Longanillaz', '123456', '1984-01-18', '1058 Dugald Rd', 'Sample City', 'New Brunswick', 'R2J0H123', '2044303401', 'marktan@email.com', 8, 0, 0, 0, 0, 5, 'MT0106841', 1, '2018-04-11 00:00:00', NULL, 'admin', NULL, ''),
(2, 'Rowena', 'Tan', 'Ersando', '456123', '1985-03-14', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'rowena.ersando14@gmail.com', 1, 0, 1, 1, 1, 1, 'RP0314851', 2, '2018-04-12 00:00:00', NULL, 'Admin', 'PAY-6A22527911044811MLMHA3QQ', ''),
(3, 'Luis', 'Tan', 'Longanilla', '78946', '1984-12-17', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'luis@yahoo.com', 1, 0, 1, 1, 1, 3, 'LT1217842', 1, '2018-04-08 00:00:00', '2018-04-13 04:51:36', 'Admin', '0', ''),
(8, 'Bryan', 'Cruz', 'N', 'na', '2018-04-17', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', NULL, NULL, 0, 0, 1, 5, 'BC0417183', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(9, 'Florence', 'Tan', 'Gopela', 'na', '2018-04-09', 'na', 'na', 'Manitoba', 'na', 'na', 'na', 3, 0, 0, 0, 1, 5, 'FT0409188', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(10, 'Mayeth', 'Tan', 'Longanilla', 'na', '1956-10-23', 'na', 'Winnipeg2', 'Manitoba', 'na', 'na2', 'na', 9, 9, 0, 1, 1, 2, 'MT0416189', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(11, 'Ethan', 'Tan', 'G', 'nna', '2018-04-10', 'na', 'Winnipeg', 'Alberta', 'na', 'na', 'nana', 1, 0, 0, 0, 1, 1, 'ET04101810', 2, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(12, 'Khayla', 'Perey', 'E', 'na', '2018-04-10', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 10, 8, 0, 0, 1, 2, 'KP04101811', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(13, 'Ramon', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 1, 8, 0, 0, 1, 2, 'RR07191812', 1, '2018-04-13 05:27:56', NULL, 'Admin', '0', ''),
(14, 'Daisy', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 9, 0, 0, 1, 1, 'DR07191813', 1, '2018-04-13 05:35:43', NULL, 'Admin', '0', ''),
(17, 'Naruto', 'Uzumaki', 'N', 'na', '2018-04-16', 'na', 'Winnipeg', 'New Brunswick', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'NU04161814', 1, '2018-04-13 05:39:25', NULL, 'Admin', '0', ''),
(18, 'Kenshin', 'Himura', 'N', 'na', '1984-12-17', 'na', 'Winnipeg', 'British Columbia', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'KH12178417', 1, '2018-04-13 05:53:06', NULL, 'Admin', '0', ''),
(19, 'Eric', 'Cadena', 'na', 'na', '1985-03-14', 'sa', 'sa', 'Manitoba', '123456', 'na', 'saa', 9, 8, 0, 0, 1, 2, 'EC03148518', 1, '2018-04-29 12:16:10', NULL, 'Admin', '0', ''),
(20, 'Kathleen', 'Casanillo', 'na', 'na', '1984-12-17', 'na', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'na', 12, 8, 0, 0, 1, 2, 'KC12178419', 1, '2018-04-29 12:16:45', NULL, 'Admin', '0', ''),
(21, 'Leo', 'Alamis', 'N', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 1, 'LA04161820', 1, '2018-04-29 12:17:22', NULL, 'Admin', '0', ''),
(22, 'Linus', 'Reyes', 'N', 'na', '2018-04-18', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 17, 8, 0, 0, 1, 1, 'LR04181821', 1, '2018-04-29 12:18:50', NULL, 'Admin', '0', ''),
(23, 'Joma', 'Llamas', 'N', 'nna', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 1, 'JL07191822', 1, '2018-04-29 12:19:32', NULL, 'Admin', '0', ''),
(24, 'Bernadette', 'Tan', 'n', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 2, 'BT04161823', 1, '2018-04-29 12:20:28', NULL, 'Admin', '0', ''),
(25, 'Rachel', 'Green', 'N', 'na', '2018-04-24', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 12, 8, 0, 0, 1, 3, 'RG04241824', 1, '2018-04-29 12:20:51', NULL, 'Admin', '0', ''),
(26, 'Joey ', 'Tribiani', 'N', 'na', '1984-01-13', 'na', 'Winnipeg', 'Manitoba', 'na', '2044303401', 'marktan@email.com', 13, 8, 0, 0, 1, 2, 'JT01138425', 1, '2018-04-29 12:21:13', NULL, 'Admin', '0', ''),
(27, 'Chandler', 'Bing', 'na', '123', '1984-01-13', '23c', 'Winnipeg', 'British Columbia', 'R2J0H123', '546', 'sample', 12, 9, 1, 0, 1, 1, 'JT04161826', 1, '2018-04-29 12:23:10', NULL, 'Admin', '0', ''),
(28, 'Stephen', 'Servania', 'T', '635223KL34', '2005-02-16', 'Carmona', 'Cavite', 'Manitoba', 'R34KK213', '2153369845', 'none@email.com', 1, 8, 0, 1, 1, 2, 'SS02160527', 1, '2018-06-01 01:53:33', NULL, 'Admin', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_record_status`
--

DROP TABLE IF EXISTS `tbl_member_record_status`;
CREATE TABLE IF NOT EXISTS `tbl_member_record_status` (
  `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_status` varchar(12) NOT NULL,
  PRIMARY KEY (`stat_tbl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_trustee_details`
--

INSERT INTO `tbl_member_trustee_details` (`id`, `member_id`, `trustee_name`, `trustee_address`, `location_bankruptcy`, `assignment_bankruptcy`, `statement_of_affairs`, `explanation`) VALUES
(1, 3, 'Mark Tan', '513 Bannatyne', '675 leila', 'sample only aob', 'sample soa', 'Sample trustee only'),
(2, 10, 'Mark Tan2', '1058 Dugald2', 'Sample Loca Bank2', 'Sample AOB2', 'SOA Example2', 'Trial Only2'),
(3, 2, 'Liam', 'sample address', 'Jesse', 'Cucu', 'Katelyn', 'ok lang yan'),
(4, 28, 'Angelo Servania', 'Carmona, Cavite', 'Carmona, Cavite', 'none', 'sample only', 'sample - stephen');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_plead_guilty_explanation`
--

INSERT INTO `tbl_plead_guilty_explanation` (`id`, `member_id`, `explanation`) VALUES
(1, 3, 'Sample Only'),
(2, 10, 'Sample Only'),
(3, 2, 'sample explanation by rowena');

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
  `client` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_policy_sold`
--

INSERT INTO `tbl_policy_sold` (`policy_id`, `policy_number`, `company_id`, `product_id`, `date_purchased`, `annual_prem`, `monthly_prem`, `curr_fyc`, `status`, `client`) VALUES
(18, 'GDF123456-01', 2, 1, '2018-04-30', '999.99', '125.00', '40.00', 0, 'Michael Jorgie Jordan'),
(19, 'PLDf123456', 2, 2, '2018-04-30', '999.99', '95.00', '45.00', 1, 'Kobe Bryant'),
(20, 'PLCY1234567', 2, 1, '2018-04-30', '900.00', '75.00', '40.00', 1, NULL),
(21, 'JPL1234569', 2, 1, '2018-05-02', '999.99', '85.00', '40.00', 1, NULL),
(22, 'PLDT4356788', 2, 2, '2018-05-02', '999.99', '239.00', '45.00', 1, NULL),
(24, 'SMPL234555', 1, 3, '2018-05-05', '1674.00', '139.50', '40.00', 1, NULL),
(25, 'POL3456625', 1, 3, '2018-05-05', '1750.00', '145.83', '40.00', 1, NULL),
(27, 'POLICY55669ODD', 2, 2, '2018-05-17', '55.00', '4.58', '45.00', 1, NULL),
(28, 'PLK1245HNJ-02', 2, 1, '2018-05-16', '837.50', '139.58', '40.00', 1, 'Ellen Adarna'),
(29, 'PLK1245HNJ-02', 2, 2, '2018-05-16', '375.00', '62.50', '45.00', 1, 'Ellen Adarna'),
(30, 'PLK1245HNJ-02', 2, 1, '2018-05-16', '837.50', '139.58', '40.00', 1, 'Ellen Adarna'),
(31, 'PLK1245HNJ-02', 2, 2, '2018-05-16', '375.00', '62.50', '45.00', 1, 'Ellen Adarna'),
(32, 'LKJ25963', 2, 1, '2018-06-04', '1950.00', '162.50', '40.00', 1, 'Sample'),
(33, 'LKJ25963', 2, 2, '2018-06-04', '955.00', '79.58', '45.00', 1, 'Sample');

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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`prod_id`, `company_id`, `prod_name`, `fyc`, `prod_type`, `status`) VALUES
(1, 2, 'Flex Term 10-35', '40.00', 1, 1),
(2, 2, 'CI Term  15-25', '45.00', 1, 1),
(3, 1, 'T10', '40.00', 1, 1),
(4, 1, 'T20', '50.00', 1, 1),
(5, 1, 'T65', '50.00', 1, 0),
(6, 2, 'CIT75', '50.00', 1, 1),
(7, 2, 'Golden Protection', '45.00', 1, 1),
(8, 2, 'Non-med whole life', '45.00', 1, 1),
(9, 2, 'Total protection', '40.00', 1, 1),
(10, 2, 'Par Plus (whole life)', '60.00', 1, 1),
(11, 4, 'T10', '37.50', 1, 1),
(12, 4, 'T15', '42.50', 1, 1),
(13, 4, 'T20-40', '50.00', 1, 1),
(14, 4, 'UL', '60.00', 1, 1),
(15, 4, 'EQB', '50.00', 1, 1),
(16, 4, 'WL 10/20/65', '50.00', 1, 1),
(17, 4, 'WL100', '60.00', 1, 1),
(18, 4, 'CI T10', '37.50', 1, 1),
(19, 4, 'CIT20', '45.00', 1, 1),
(20, 4, 'CIT75', '50.00', 1, 1),
(21, 4, 'CI ROP', '30.00', 1, 1),
(22, 1, 'UL', '60.00', 1, 1),
(23, 1, 'WL', '50.00', 1, 1),
(24, 1, 'Synergy', '40.00', 1, 1),
(25, 1, 'CI T10', '40.00', 1, 1),
(26, 1, 'CI T20 / 65 / 75', '50.00', 1, 1),
(27, 1, 'QIT T10', '40.00', 1, 1),
(28, 1, 'QIT T20/65', '50.00', 1, 1),
(29, 1, 'Flex Care', '20.00', 1, 1),
(30, 1, 'Visitors Canada', '35.00', 1, 1),
(31, 1, 'Emergency Medical', '30.00', 1, 1),
(32, 5, 'T10', '40.00', 1, 1),
(33, 5, 'T20', '50.00', 1, 1),
(34, 5, 'T30/65', '50.00', 1, 1),
(35, 5, 'Final Protection', '45.00', 1, 1),
(36, 5, 'UL YRT', '60.00', 1, 1),
(37, 5, 'UL Level', '55.00', 1, 1),
(38, 5, 'WL Kids', '50.00', 1, 1),
(39, 5, 'T75 Kids', '45.00', 1, 1),
(40, 5, 'CI ROP', '30.00', 1, 1),
(41, 7, 'Child CI/UL', '40.00', 1, 1),
(42, 6, 'Hugo', '45.00', 1, 1),
(43, 6, 'Non-Medical', '35.00', 1, 1),
(44, 6, 'CI 4 Conditions', '35.00', 1, 1),
(45, 8, 'T10', '40.00', 1, 1),
(46, 8, 'T20', '50.00', 1, 1),
(47, 8, 'T30', '50.00', 1, 1),
(48, 8, 'T30', '50.00', 1, 1),
(49, 8, 'UL', '65.00', 1, 1),
(50, 8, 'CI T20/T65', '45.00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type`
--

DROP TABLE IF EXISTS `tbl_product_type`;
CREATE TABLE IF NOT EXISTS `tbl_product_type` (
  `product_type_id` int(3) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(32) NOT NULL,
  PRIMARY KEY (`product_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_type`
--

INSERT INTO `tbl_product_type` (`product_type_id`, `product_type`) VALUES
(1, 'Insurance'),
(2, 'Investment');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_types`
--

DROP TABLE IF EXISTS `tbl_user_types`;
CREATE TABLE IF NOT EXISTS `tbl_user_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_types`
--

INSERT INTO `tbl_user_types` (`id`, `user_type`) VALUES
(1, 'Admin'),
(2, 'Member');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
