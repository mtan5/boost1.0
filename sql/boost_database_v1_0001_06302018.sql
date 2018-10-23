-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2018 at 07:18 AM
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
-- Database: `martan4_boost`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllDownlines` (`mbr_id` INT(4))  BEGIN 

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllDownlineSales` (IN `mbr_id` INT(4), IN `period_start` DATETIME, IN `period_end` DATETIME)  BEGIN 

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
    tbl_policy.policy_number,
    tbl_products.prod_name,
    tbl_company.name AS company_name,
    tbl_policy_sold.annual_prem,
    tbl_policy_sold.curr_fyc,
    tbl_commissions.commission_fyc,
    tbl_commissions.commission_amt_bonus,
    tbl_commissions.status,
    tbl_commission_status.commission_status,
    tbl_commissions.date_charged
    
    FROM output_downline_members
    LEFT JOIN tbl_commissions ON tbl_commissions.member_id=output_downline_members.id    
    LEFT JOIN tbl_policy_sold ON tbl_policy_sold.policy_id=tbl_commissions.policy_id AND tbl_policy_sold.product_id=tbl_commissions.product_id
    LEFT JOIN tbl_policy ON tbl_policy.id=tbl_policy_sold.policy_id 
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

CREATE TABLE `tbl_commissions` (
  `comm_id` int(11) NOT NULL,
  `policy_id` int(9) NOT NULL,
  `product_id` int(5) NOT NULL,
  `member_id` int(5) NOT NULL,
  `member_level_id` int(3) NOT NULL,
  `commission_fyc` decimal(8,2) NOT NULL,
  `commission_amt_bonus` decimal(8,2) NOT NULL,
  `date_charged` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `commission_type_id` int(3) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commissions`
--

INSERT INTO `tbl_commissions` (`comm_id`, `policy_id`, `product_id`, `member_id`, `member_level_id`, `commission_fyc`, `commission_amt_bonus`, `date_charged`, `date_modified`, `commission_type_id`, `status`) VALUES
(1, 1, 7, 18, 2, '475.00', '380.00', '2018-07-01', '2018-07-01', 1, 2),
(2, 1, 7, 13, 2, '0.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(3, 1, 7, 1, 3, '95.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(4, 1, 7, 8, 5, '38.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(5, 1, 11, 18, 2, '260.00', '208.00', '2018-07-01', '2018-07-01', 1, 2),
(6, 1, 11, 13, 2, '0.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(7, 1, 11, 1, 3, '52.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(8, 1, 11, 8, 5, '20.80', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(9, 2, 3, 18, 2, '190.00', '152.00', '2018-07-01', '2018-07-01', 1, 2),
(10, 2, 3, 13, 2, '0.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(11, 2, 3, 1, 3, '38.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(12, 2, 3, 8, 5, '15.20', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(13, 2, 14, 18, 2, '123.75', '99.00', '2018-07-01', '2018-07-01', 1, 2),
(14, 2, 14, 13, 2, '0.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(15, 2, 14, 1, 3, '24.75', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(16, 2, 14, 8, 5, '9.90', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(17, 2, 18, 18, 2, '123.50', '98.80', '2018-07-01', '2018-07-01', 1, 2),
(18, 2, 18, 13, 2, '0.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(19, 2, 18, 1, 3, '24.70', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(20, 2, 18, 8, 5, '9.88', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(21, 2, 3, 10, 4, '190.00', '228.00', '2018-07-01', '2018-07-01', 1, 2),
(22, 2, 3, 9, 5, '38.00', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(23, 2, 14, 10, 4, '123.75', '148.50', '2018-07-01', '2018-07-01', 1, 2),
(24, 2, 14, 9, 5, '24.75', '0.00', '2018-07-01', '2018-07-01', 2, 2),
(25, 2, 18, 10, 4, '123.50', '148.20', '2018-07-01', '2018-07-01', 1, 2),
(26, 2, 18, 9, 5, '24.70', '0.00', '2018-07-01', '2018-07-01', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_statements`
--

CREATE TABLE `tbl_commission_statements` (
  `id` int(9) NOT NULL,
  `member_id` int(9) NOT NULL,
  `file_name` varchar(128) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statement_period` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commission_statements`
--

INSERT INTO `tbl_commission_statements` (`id`, `member_id`, `file_name`, `date_created`, `statement_period`) VALUES
(1, 1, 'CommissionStatement_Mark Longanilla Tan_2018-06-01_2018-06-30_.pdf', '2018-06-24 13:16:38', '2018-06-01 to 2018-06-30'),
(3, 8, 'CommissionStatement_Bryan N Cruz_2018-06-01_2018-06-30_.pdf', '2018-06-24 13:17:19', '2018-06-01 to 2018-06-30'),
(4, 1, 'CommissionStatement_Mark Longanilla Tan_000_000.pdf', '2018-06-27 18:22:54', '000 to 000'),
(5, 1, 'CommissionStatement_Mark Longanilla Tan_000_000.pdf', '2018-06-28 20:19:55', '000 to 000'),
(6, 1, 'CommissionStatement_Mark Longanilla Tan_000_000.pdf', '2018-06-28 20:24:28', '000 to 000'),
(7, 1, 'CommissionStatement_Mark Longanilla Tan_000_000.pdf', '2018-06-28 20:27:09', '000 to 000'),
(8, 8, 'CommissionStatement_Bryan N Cruz_000_000.pdf', '2018-06-28 20:27:26', '000 to 000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_status`
--

CREATE TABLE `tbl_commission_status` (
  `commission_status_id` int(3) NOT NULL,
  `commission_status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commission_status`
--

INSERT INTO `tbl_commission_status` (`commission_status_id`, `commission_status`) VALUES
(1, 'Pending'),
(2, 'Released'),
(3, 'ChargedBack');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_type`
--

CREATE TABLE `tbl_commission_type` (
  `id` int(3) NOT NULL,
  `commission_type` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commission_type`
--

INSERT INTO `tbl_commission_type` (`id`, `commission_type`) VALUES
(1, 'direct'),
(2, 'override'),
(3, 'trainee'),
(4, 'trailing');

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
(2, 'Assumption Life', 1),
(3, 'Sample Company', 0),
(4, 'Equitable Life', 1),
(5, 'Industrial Life', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_level`
--

CREATE TABLE `tbl_membership_level` (
  `level_tbl_id` int(11) NOT NULL,
  `membership_level` varchar(32) NOT NULL,
  `commission_factor` int(3) DEFAULT NULL,
  `level_up_rqrmnts` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_membership_level`
--

INSERT INTO `tbl_membership_level` (`level_tbl_id`, `membership_level`, `commission_factor`, `level_up_rqrmnts`) VALUES
(1, 'Trainee', 80, '0.00'),
(2, 'Associate', 80, '35000.00'),
(3, 'Supervisor', 100, '55000.00'),
(4, 'Mentor', 120, '80000.00'),
(5, 'Director', 140, '100000.00');

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
(5, 'wowie', 'e10adc3949ba59abbe56e057f20f883e', 2, 2, 'Active');

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
  `payment_id` varchar(32) DEFAULT NULL,
  `transaction_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_member_info`
--

INSERT INTO `tbl_member_info` (`id`, `first_name`, `last_name`, `mid_name`, `sin`, `dob`, `address`, `city`, `province`, `postal`, `contact`, `email`, `upline_id`, `director_id`, `is_plead_guilty`, `is_bankrupt`, `is_legal_to_work`, `level_id`, `code`, `status`, `reg_date`, `approved_date`, `membership_process`, `payment_id`, `transaction_id`) VALUES
(1, 'Mark', 'Tan', 'Longanilla', '123456', '1984-01-18', '1058 Dugald Rd', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'markLtan@email.com', 8, 0, 0, 0, 0, 3, 'MT0106841', 1, '2018-04-11 00:00:00', NULL, 'admin', NULL, ''),
(2, 'Rowena', 'Tan', 'Ersando', '456123', '1985-03-14', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'rowena.ersando14@gmail.com', 1, 0, 1, 1, 1, 1, 'RP0314851', 1, '2018-04-12 00:00:00', '2018-06-08 05:36:13', 'Admin', '0', ''),
(3, 'Luis', 'Tan', 'Longanilla', '78946', '1984-12-17', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'luis@yahoo.com', 1, 0, 1, 1, 1, 2, 'LT1217842', 1, '2018-04-08 00:00:00', '2018-04-13 04:51:36', 'Admin', '0', ''),
(8, 'Bryan', 'Cruz', 'N', 'na', '2018-04-17', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', NULL, NULL, 0, 0, 1, 5, 'BC0417183', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(9, 'Florence', 'Tan', 'Gopela', 'na', '2018-04-09', 'na', 'na', 'Manitoba', 'na', 'na', 'na', 3, 0, 0, 0, 1, 5, 'FT0409188', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(10, 'Marietta', 'Tan', 'Longanilla', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 9, 0, 1, 1, 1, 4, 'MT0416189', 1, '2018-04-12 00:00:00', NULL, 'Admin', '0', ''),
(11, 'Ethan', 'Tan', 'G', 'nna', '2018-04-10', 'na', 'Winnipeg', 'Alberta', 'na', 'na', 'nana', 1, 0, 0, 0, 1, 1, 'ET04101810', 2, '2018-04-12 00:00:00', NULL, 'Admin', 'PAY-55U88861X1758850RLMHAUAQ', ''),
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
(26, 'Joey ', 'Tribiani', 'N', 'na', '1984-01-13', 'na', 'Winnipeg', 'Manitoba', 'na', '2044303401', 'Joey@email.com', 13, 8, 0, 0, 1, 2, 'JT01138425', 1, '2018-04-29 12:21:13', NULL, 'Admin', '0', ''),
(27, 'Chandler', 'Bing', 'na', '123', '1984-01-13', '23c', 'Winnipeg', 'British Columbia', 'R2J0H123', '546', 'sample', 12, 9, 1, 0, 1, 1, 'JT04161826', 1, '2018-04-29 12:23:10', NULL, 'Admin', '0', ''),
(28, 'Kevin', 'Durant', 'N', '215333685', '1984-01-18', 'None', 'Winnipeg', 'British Columbia', 'R2J0H1', 'na', 'na', 1, 9, 0, 0, 1, 2, 'KD01188427', 2, '2018-05-29 06:02:29', NULL, 'Payment', '0', ''),
(29, 'Michael ', 'Tan', 'L', '456123', '2018-04-17', '1058 Dugald Rd', 'Winnipeg', 'Manitoba', 'R2P1K8', '2049550130', 'marktan@email.com', 19, 9, 0, 0, 1, 1, 'MT04171828', 1, '2018-06-29 05:01:38', NULL, 'Admin', '0', '');

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
(3, 2, 'Liam', 'sample address', 'Jesse', 'Cucu', 'Katelyn', 'ok lang yan'),
(4, 24, 'sample', 'sample', 'sample', 'sample', 'sasmpe', 'sample');

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
  `id` int(9) NOT NULL,
  `policy_number` varchar(32) NOT NULL,
  `client` varchar(180) DEFAULT NULL,
  `date_purchased` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_policy`
--

INSERT INTO `tbl_policy` (`id`, `policy_number`, `client`, `date_purchased`) VALUES
(1, 'KN123885', 'Hitokiri Battousai', '2018-06-22'),
(2, '92536998520', 'Saito', '2018-06-21');

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
  `policy_sale_id` int(9) NOT NULL,
  `policy_id` int(9) NOT NULL,
  `company_id` int(3) NOT NULL,
  `product_id` int(4) NOT NULL,
  `annual_prem` decimal(8,2) DEFAULT NULL,
  `monthly_prem` decimal(8,2) DEFAULT NULL,
  `curr_fyc` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_policy_sold`
--

INSERT INTO `tbl_policy_sold` (`policy_sale_id`, `policy_id`, `company_id`, `product_id`, `annual_prem`, `monthly_prem`, `curr_fyc`, `status`) VALUES
(1, 1, 4, 7, '950.00', '79.17', '50.00', 1),
(2, 1, 4, 11, '650.00', '54.17', '40.00', 1),
(3, 2, 1, 3, '950.00', '79.17', '40.00', 1),
(4, 2, 1, 14, '450.00', '37.50', '55.00', 1),
(5, 2, 1, 18, '650.00', '54.17', '38.00', 1);

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

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`prod_id`, `company_id`, `prod_name`, `fyc`, `prod_type`, `status`) VALUES
(1, 2, 'Flex Term 10-35', '40.00', 1, 1),
(2, 2, 'CI Term  15-25', '45.00', 1, 1),
(3, 1, 'T10', '40.00', 1, 1),
(4, 1, 'T20', '50.00', 1, 1),
(5, 1, 'T65', '50.00', 1, 0),
(6, 4, 'Term 10', '45.00', 1, 1),
(7, 4, 'Term 15', '50.00', 1, 1),
(8, 4, 'Term 25', '55.00', 1, 1),
(9, 5, 'Term 30', '40.00', 1, 1),
(10, 4, 'Travel Insurance', '35.00', 3, 1),
(11, 4, 'CI35', '40.00', 1, 1),
(12, 5, 'CI02', '30.00', 1, 1),
(13, 1, 'UL', '55.00', 1, 1),
(14, 1, 'VUL', '55.00', 1, 1),
(15, 2, 'UL', '55.00', 1, 1),
(16, 4, 'Term 45', '55.00', 1, 1),
(17, 1, 'M-Travel', '35.00', 1, 1),
(18, 1, 'WVUL', '38.00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type`
--

CREATE TABLE `tbl_product_type` (
  `product_type_id` int(3) NOT NULL,
  `product_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_type`
--

INSERT INTO `tbl_product_type` (`product_type_id`, `product_type`) VALUES
(1, 'Insurance'),
(2, 'Investment'),
(3, 'travel');

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
-- Indexes for table `tbl_commissions`
--
ALTER TABLE `tbl_commissions`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `tbl_commission_statements`
--
ALTER TABLE `tbl_commission_statements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  ADD PRIMARY KEY (`commission_status_id`);

--
-- Indexes for table `tbl_commission_type`
--
ALTER TABLE `tbl_commission_type`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`policy_sale_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `tbl_product_type`
--
ALTER TABLE `tbl_product_type`
  ADD PRIMARY KEY (`product_type_id`);

--
-- Indexes for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_commissions`
--
ALTER TABLE `tbl_commissions`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `tbl_commission_statements`
--
ALTER TABLE `tbl_commission_statements`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  MODIFY `commission_status_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_commission_type`
--
ALTER TABLE `tbl_commission_type`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_member_info`
--
ALTER TABLE `tbl_member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `tbl_member_record_status`
--
ALTER TABLE `tbl_member_record_status`
  MODIFY `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_member_trustee_details`
--
ALTER TABLE `tbl_member_trustee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_plead_guilty_explanation`
--
ALTER TABLE `tbl_plead_guilty_explanation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_policy`
--
ALTER TABLE `tbl_policy`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_policy_sold`
--
ALTER TABLE `tbl_policy_sold`
  MODIFY `policy_sale_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prod_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tbl_product_type`
--
ALTER TABLE `tbl_product_type`
  MODIFY `product_type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
