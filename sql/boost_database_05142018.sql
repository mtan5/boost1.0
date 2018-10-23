-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2018 at 06:56 AM
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
CREATE DATABASE IF NOT EXISTS `boost` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `boost`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `GetAllDownlines`$$
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

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commissions`
--

DROP TABLE IF EXISTS `tbl_commissions`;
CREATE TABLE `tbl_commissions` (
  `comm_id` int(11) NOT NULL,
  `policy_id` int(9) NOT NULL,
  `product_id` int(5) NOT NULL,
  `member_id` int(5) NOT NULL,
  `member_level_id` int(3) NOT NULL,
  `commission_amt` decimal(8,2) NOT NULL,
  `date_charged` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `commission_type` varchar(16) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_commissions`
--

INSERT INTO `tbl_commissions` (`comm_id`, `policy_id`, `product_id`, `member_id`, `member_level_id`, `commission_amt`, `date_charged`, `date_modified`, `commission_type`, `status`) VALUES
(61, 17, 2, 17, 2, '328.05', '2018-04-22', '2018-05-15', 'agent', 2),
(62, 17, 2, 13, 2, '0.00', '2018-04-22', NULL, 'upline', 1),
(63, 17, 2, 1, 3, '48.60', '2018-04-22', NULL, 'upline', 1),
(64, 17, 2, 8, 5, '97.20', '2018-04-22', NULL, 'upline', 1),
(65, 18, 1, 17, 2, '810.00', '2018-04-27', '2018-05-15', 'agent', 2),
(66, 18, 1, 13, 2, '0.00', '2018-04-27', NULL, 'upline', 1),
(67, 18, 1, 1, 3, '120.00', '2018-04-27', NULL, 'upline', 1),
(68, 18, 1, 8, 5, '240.00', '2018-04-27', NULL, 'upline', 1),
(69, 19, 2, 20, 2, '692.55', '2018-04-30', NULL, 'agent', 1),
(70, 19, 2, 12, 2, '0.00', '2018-04-30', NULL, 'upline', 1),
(71, 19, 2, 10, 4, '205.20', '2018-04-30', NULL, 'upline', 1),
(72, 19, 2, 9, 5, '102.60', '2018-04-30', NULL, 'upline', 1),
(73, 20, 1, 3, 3, '540.00', '2018-04-30', NULL, 'agent', 1),
(74, 20, 1, 1, 3, '0.00', '2018-04-30', NULL, 'upline', 1),
(75, 20, 1, 8, 5, '144.00', '2018-04-30', NULL, 'upline', 1),
(76, 21, 1, 26, 2, '734.40', '2018-05-02', NULL, 'agent', 1),
(77, 21, 1, 13, 2, '0.00', '2018-05-02', NULL, 'upline', 1),
(78, 21, 1, 1, 3, '81.60', '2018-05-02', NULL, 'upline', 1),
(79, 21, 1, 8, 5, '163.20', '2018-05-02', NULL, 'upline', 1),
(80, 22, 2, 25, 3, '999.99', '2018-05-02', NULL, 'agent', 1),
(81, 22, 2, 12, 2, '0.00', '2018-05-02', NULL, 'upline', 1),
(82, 22, 2, 10, 4, '516.24', '2018-05-02', NULL, 'upline', 1),
(83, 22, 2, 9, 5, '258.12', '2018-05-02', '2018-05-03', 'upline', 1),
(88, 24, 3, 20, 2, '602.64', '2018-05-05', NULL, 'agent', 1),
(89, 24, 3, 12, 2, '0.00', '2018-05-05', NULL, 'upline', 1),
(90, 24, 3, 10, 4, '267.84', '2018-05-05', NULL, 'upline', 1),
(91, 24, 3, 9, 5, '133.92', '2018-05-05', NULL, 'upline', 1),
(92, 25, 3, 20, 2, '630.00', '2018-05-05', NULL, 'agent', 1),
(93, 25, 3, 12, 2, '0.00', '2018-05-05', NULL, 'upline', 1),
(94, 25, 3, 10, 4, '280.00', '2018-05-05', NULL, 'upline', 1),
(95, 25, 3, 9, 5, '140.00', '2018-05-05', '2018-05-10', 'upline', 1),
(96, 26, 3, 25, 3, '111.00', '2018-05-09', NULL, 'agent', 1),
(97, 26, 3, 12, 2, '0.00', '2018-05-09', NULL, 'upline', 1),
(98, 26, 3, 10, 4, '29.60', '2018-05-09', NULL, 'upline', 1),
(99, 26, 3, 9, 5, '14.80', '2018-05-09', NULL, 'upline', 1),
(100, 27, 2, 13, 2, '44.55', '2018-05-10', NULL, 'agent', 1),
(101, 27, 2, 1, 3, '4.95', '2018-05-10', NULL, 'upline', 1),
(102, 27, 2, 8, 5, '9.90', '2018-05-10', NULL, 'upline', 1),
(103, 28, 4, 24, 2, '297.00', '2018-05-15', NULL, 'agent', 1),
(104, 28, 4, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(105, 28, 4, 1, 3, '33.00', '2018-05-15', NULL, 'upline', 1),
(106, 28, 4, 8, 5, '66.00', '2018-05-15', NULL, 'upline', 1),
(107, 29, 2, 13, 2, '35.24', '2018-05-15', NULL, 'agent', 1),
(108, 29, 2, 1, 3, '5.22', '2018-05-15', NULL, 'upline', 1),
(109, 29, 2, 8, 5, '10.44', '2018-05-15', NULL, 'upline', 1),
(110, 31, 1, 18, 2, '972.00', '2018-05-15', NULL, 'agent', 1),
(111, 31, 1, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(112, 31, 1, 1, 3, '108.00', '2018-05-15', NULL, 'upline', 1),
(113, 31, 1, 8, 5, '216.00', '2018-05-15', NULL, 'upline', 1),
(114, 32, 2, 18, 2, '101.25', '2018-05-15', NULL, 'agent', 1),
(115, 32, 2, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(116, 32, 2, 1, 3, '11.25', '2018-05-15', NULL, 'upline', 1),
(117, 32, 2, 8, 5, '22.50', '2018-05-15', NULL, 'upline', 1),
(118, 33, 1, 18, 2, '972.00', '2018-05-15', NULL, 'agent', 1),
(119, 33, 1, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(120, 33, 1, 1, 3, '108.00', '2018-05-15', NULL, 'upline', 1),
(121, 33, 1, 8, 5, '216.00', '2018-05-15', NULL, 'upline', 1),
(122, 34, 2, 18, 2, '101.25', '2018-05-15', NULL, 'agent', 1),
(123, 34, 2, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(124, 34, 2, 1, 3, '11.25', '2018-05-15', NULL, 'upline', 1),
(125, 34, 2, 8, 5, '22.50', '2018-05-15', NULL, 'upline', 1),
(126, 35, 1, 18, 2, '972.00', '2018-05-15', NULL, 'agent', 1),
(127, 35, 1, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(128, 35, 1, 1, 3, '108.00', '2018-05-15', NULL, 'upline', 1),
(129, 35, 1, 8, 5, '216.00', '2018-05-15', NULL, 'upline', 1),
(130, 36, 1, 18, 2, '999.99', '2018-05-15', NULL, 'agent', 1),
(131, 36, 1, 13, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(132, 36, 1, 1, 3, '148.00', '2018-05-15', NULL, 'upline', 1),
(133, 36, 1, 8, 5, '296.00', '2018-05-15', NULL, 'upline', 1),
(134, 37, 1, 20, 2, '999.99', '2018-05-15', NULL, 'agent', 1),
(135, 37, 1, 12, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(136, 37, 1, 10, 4, '296.32', '2018-05-15', NULL, 'upline', 1),
(137, 37, 1, 9, 5, '148.16', '2018-05-15', NULL, 'upline', 1),
(138, 38, 2, 20, 2, '132.03', '2018-05-15', NULL, 'agent', 1),
(139, 38, 2, 12, 2, '0.00', '2018-05-15', NULL, 'upline', 1),
(140, 38, 2, 10, 4, '29.34', '2018-05-15', NULL, 'upline', 1),
(141, 38, 2, 9, 5, '14.67', '2018-05-15', NULL, 'upline', 1),
(142, 39, 1, 3, 3, '1108.00', '2018-05-15', '2018-05-15', 'agent', 2),
(143, 39, 1, 1, 3, '0.00', '2018-05-15', NULL, 'upline', 1),
(144, 39, 1, 8, 5, '221.60', '2018-05-15', NULL, 'upline', 1),
(145, 40, 2, 3, 3, '85.50', '2018-05-15', '2018-05-15', 'agent', 2),
(146, 40, 2, 1, 3, '0.00', '2018-05-15', NULL, 'upline', 1),
(147, 40, 2, 8, 5, '17.10', '2018-05-15', NULL, 'upline', 1),
(148, 41, 1, 3, 3, '1191.20', '2018-05-15', '2018-05-15', 'agent', 2),
(149, 41, 1, 1, 3, '0.00', '2018-05-15', NULL, 'upline', 1),
(150, 41, 1, 8, 5, '238.24', '2018-05-15', NULL, 'upline', 1),
(151, 42, 2, 3, 3, '108.00', '2018-05-15', '2018-05-15', 'agent', 2),
(152, 42, 2, 1, 3, '0.00', '2018-05-15', NULL, 'upline', 1),
(153, 42, 2, 8, 5, '21.60', '2018-05-15', NULL, 'upline', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_status`
--

DROP TABLE IF EXISTS `tbl_commission_status`;
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
-- Table structure for table `tbl_company`
--

DROP TABLE IF EXISTS `tbl_company`;
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
(3, 'Sample Company', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_level`
--

DROP TABLE IF EXISTS `tbl_membership_level`;
CREATE TABLE `tbl_membership_level` (
  `level_tbl_id` int(11) NOT NULL,
  `membership_level` varchar(32) NOT NULL,
  `commission_factor` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

DROP TABLE IF EXISTS `tbl_member_access`;
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

DROP TABLE IF EXISTS `tbl_member_info`;
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
(1, 'Mark', 'Tan', 'Longanillaz', '123456', '1984-01-18', '1058 Dugald Rd', 'Sample City', 'New Brunswick', 'R2J0H123', '2044303401', 'marktan@email.com', 8, 0, 0, 0, 0, 3, 'MT0106841', 1, '2018-04-11 00:00:00', NULL, 'admin', NULL),
(2, 'Rowena', 'Tan', 'Ersando', '456123', '1985-03-14', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'rowena.ersando14@gmail.com', 1, 0, 1, 1, 1, 1, 'RP0314851', 2, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(3, 'Luis', 'Tan', 'Longanilla', '78946', '1984-12-17', '1058 Dugald Road', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'luis@yahoo.com', 1, 0, 1, 1, 1, 3, 'LT1217842', 1, '2018-04-08 00:00:00', '2018-04-13 04:51:36', 'Admin', 0),
(8, 'Bryan', 'Cruz', 'N', 'na', '2018-04-17', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', NULL, NULL, 0, 0, 1, 5, 'BC0417183', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(9, 'Florence', 'Tan', 'Gopela', 'na', '2018-04-09', 'na', 'na', 'Manitoba', 'na', 'na', 'na', 3, 0, 0, 0, 1, 5, 'FT0409188', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(10, 'Marietta', 'Tan', 'Longanilla', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 9, 0, 1, 1, 1, 4, 'MT0416189', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(11, 'Ethan', 'Tan', 'G', 'nna', '2018-04-10', 'na', 'Winnipeg', 'Alberta', 'na', 'na', 'nana', 1, 0, 0, 0, 1, 1, 'ET04101810', 2, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(12, 'Khayla', 'Perey', 'E', 'na', '2018-04-10', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 10, 8, 0, 0, 1, 2, 'KP04101811', 1, '2018-04-12 00:00:00', NULL, 'Admin', 0),
(13, 'Ramon', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 1, 8, 0, 0, 1, 2, 'RR07191812', 1, '2018-04-13 05:27:56', NULL, 'Admin', 0),
(14, 'Daisy', 'Reyes', 'N', 'na', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 9, 0, 0, 1, 1, 'DR07191813', 1, '2018-04-13 05:35:43', NULL, 'Admin', 0),
(17, 'Naruto', 'Uzumaki', 'N', 'na', '2018-04-16', 'na', 'Winnipeg', 'New Brunswick', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'NU04161814', 1, '2018-04-13 05:39:25', NULL, 'Admin', 0),
(18, 'Kenshin', 'Himura', 'N', 'na', '1984-12-17', 'na', 'Winnipeg', 'British Columbia', 'na', 'na', 'na', 13, 9, 0, 0, 1, 2, 'KH12178417', 1, '2018-04-13 05:53:06', NULL, 'Admin', 0),
(19, 'Eric', 'Cadena', 'na', 'na', '1985-03-14', 'sa', 'sa', 'Manitoba', '123456', 'na', 'saa', 9, 8, 0, 0, 1, 2, 'EC03148518', 1, '2018-04-29 12:16:10', NULL, 'Admin', 0),
(20, 'Kathleen', 'Casanillo', 'na', 'na', '1984-12-17', 'na', 'Winnipeg', 'Manitoba', 'R2J0H1', '2044303401', 'na', 12, 8, 0, 0, 1, 2, 'KC12178419', 1, '2018-04-29 12:16:45', NULL, 'Admin', 0),
(21, 'Leo', 'Alamis', 'N', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 1, 'LA04161820', 1, '2018-04-29 12:17:22', NULL, 'Admin', 0),
(22, 'Linus', 'Reyes', 'N', 'na', '2018-04-18', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 17, 8, 0, 0, 1, 1, 'LR04181821', 1, '2018-04-29 12:18:50', NULL, 'Admin', 0),
(23, 'Joma', 'Llamas', 'N', 'nna', '2018-07-19', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 1, 'JL07191822', 1, '2018-04-29 12:19:32', NULL, 'Admin', 0),
(24, 'Bernadette', 'Tan', 'n', 'na', '2018-04-16', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 13, 8, 0, 0, 1, 2, 'BT04161823', 1, '2018-04-29 12:20:28', NULL, 'Admin', 0),
(25, 'Rachel', 'Green', 'N', 'na', '2018-04-24', 'na', 'Winnipeg', 'Manitoba', 'na', 'na', 'na', 12, 8, 0, 0, 1, 3, 'RG04241824', 1, '2018-04-29 12:20:51', NULL, 'Admin', 0),
(26, 'Joey ', 'Tribiani', 'N', 'na', '1984-01-13', 'na', 'Winnipeg', 'Manitoba', 'na', '2044303401', 'marktan@email.com', 13, 8, 0, 0, 1, 2, 'JT01138425', 1, '2018-04-29 12:21:13', NULL, 'Admin', 0),
(27, 'Chandler', 'Bing', 'na', '123', '1984-01-13', '23c', 'Winnipeg', 'British Columbia', 'R2J0H123', '546', 'sample', 12, 9, 1, 0, 1, 1, 'JT04161826', 1, '2018-04-29 12:23:10', NULL, 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_record_status`
--

DROP TABLE IF EXISTS `tbl_member_record_status`;
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

DROP TABLE IF EXISTS `tbl_member_trustee_details`;
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

DROP TABLE IF EXISTS `tbl_plead_guilty_explanation`;
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
-- Table structure for table `tbl_policy_holders`
--

DROP TABLE IF EXISTS `tbl_policy_holders`;
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

DROP TABLE IF EXISTS `tbl_policy_sold`;
CREATE TABLE `tbl_policy_sold` (
  `policy_id` int(9) NOT NULL,
  `policy_number` varchar(32) NOT NULL,
  `client` varchar(256) NOT NULL,
  `company_id` int(3) NOT NULL,
  `product_id` int(4) NOT NULL,
  `date_purchased` date NOT NULL,
  `annual_prem` decimal(8,2) DEFAULT NULL,
  `monthly_prem` decimal(8,2) DEFAULT NULL,
  `curr_fyc` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_policy_sold`
--

INSERT INTO `tbl_policy_sold` (`policy_id`, `policy_number`, `client`, `company_id`, `product_id`, `date_purchased`, `annual_prem`, `monthly_prem`, `curr_fyc`, `status`) VALUES
(17, 'PLCY015263', '', 2, 2, '2018-04-22', '540.00', '45.00', '45.00', 1),
(18, 'GDF123456', '', 2, 1, '2018-04-19', '999.99', '125.00', '40.00', 1),
(19, 'PLDf123456', '', 2, 2, '2018-04-30', '999.99', '95.00', '45.00', 1),
(20, 'PLCY1234567', '', 2, 1, '2018-04-30', '900.00', '75.00', '40.00', 1),
(21, 'JPL1234569', '', 2, 1, '2018-05-02', '999.99', '85.00', '40.00', 1),
(22, 'PLDT4356788', '', 2, 2, '2018-05-02', '999.99', '239.00', '45.00', 1),
(24, 'SMPL234555', '', 1, 3, '2018-05-05', '1674.00', '139.50', '40.00', 1),
(25, 'POL3456625', '', 1, 3, '2018-05-05', '1750.00', '145.83', '40.00', 1),
(26, 'POLS2344HG12', '', 1, 3, '2018-04-24', '185.00', '15.42', '40.00', 1),
(27, 'POLICY55669ODD', '', 2, 2, '2018-05-17', '55.00', '4.58', '45.00', 1),
(28, 'PDF123456789', '', 1, 4, '2018-05-17', '330.00', '27.50', '50.00', 1),
(29, 'KLC8523658', '', 2, 2, '2018-05-10', '58.00', '4.83', '45.00', 1),
(31, 'KHS5822009', '', 2, 1, '2018-05-10', '1350.00', '112.50', '40.00', 1),
(32, 'KHS5822009', '', 2, 2, '2018-05-10', '125.00', '10.42', '45.00', 1),
(33, 'KHS5822009', '', 2, 1, '2018-05-10', '1350.00', '112.50', '40.00', 1),
(34, 'KHS5822009', '', 2, 2, '2018-05-10', '125.00', '10.42', '45.00', 1),
(35, 'KHS5822125', '', 2, 1, '2018-05-10', '1350.00', '112.50', '40.00', 1),
(36, 'MOKHS5822125', '', 2, 1, '2018-05-10', '1850.00', '154.17', '40.00', 1),
(37, 'MKIJU258JKNM123', '', 2, 1, '2018-05-09', '1852.00', '154.33', '40.00', 1),
(38, 'MKIJU258JKNM123', '', 2, 2, '2018-05-09', '163.00', '13.58', '45.00', 1),
(39, 'LIK52836690', '', 2, 1, '2018-05-22', '1385.00', '115.42', '40.00', 1),
(40, 'LIK52836690', '', 2, 2, '2018-05-22', '95.00', '7.92', '45.00', 1),
(41, 'PLO9852003', 'John and Marsha Santos', 2, 1, '2018-05-22', '1489.00', '124.08', '40.00', 1),
(42, 'PLO9852003', 'John and Marsha Santos', 2, 2, '2018-05-22', '120.00', '10.00', '45.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

DROP TABLE IF EXISTS `tbl_products`;
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
(5, 1, 'T65', '50.00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_types`
--

DROP TABLE IF EXISTS `tbl_user_types`;
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
-- Indexes for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  ADD PRIMARY KEY (`commission_status_id`);

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
-- AUTO_INCREMENT for table `tbl_commissions`
--
ALTER TABLE `tbl_commissions`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `tbl_commission_status`
--
ALTER TABLE `tbl_commission_status`
  MODIFY `commission_status_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
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
-- AUTO_INCREMENT for table `tbl_policy_holders`
--
ALTER TABLE `tbl_policy_holders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_policy_sold`
--
ALTER TABLE `tbl_policy_sold`
  MODIFY `policy_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prod_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
