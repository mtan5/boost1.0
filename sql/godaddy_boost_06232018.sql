
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DELIMITER $$
CREATE PROCEDURE `GetAllDownlines` (`mbr_id` INT(4))  
BEGIN 

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

CREATE PROCEDURE `GetAllDownlineSales` (`mbr_id` INT(4), `period_start` DATETIME, `period_end` DATETIME)  BEGIN 

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
(4, 'I.A.P.', 1),
(5, 'Equitable Life', 1),
(6, 'Humania', 1),
(7, 'Desjardin', 1),
(8, 'Ivari', 1);

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
(1, 'Trainee', 0, '0.00'),
(2, 'Associate', 80, '30000.00'),
(3, 'Supervisor', 100, '50000.00'),
(4, 'Manager', 120, '80000.00'),
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
(1, 'admin01', 'c680cd379b6fc58d5394ceb20aee63b4', 1, 0, 'Active'),
(2, 'bcruz', '2d77f4ee8774023753dc33e2439ecbc2', 2, 1, 'Active'),
(3, 'ethan05', 'e10adc3949ba59abbe56e057f20f883e', 2, 11, 'Active'),
(4, 'flortan', '785bd98a5c41d11400c6c7e362dafa2c', 2, 9, 'Active'),
(5, 'wowie', 'e10adc3949ba59abbe56e057f20f883e', 2, 2, 'Active'),
(6, 'mrttan', '4ab81afca77637e9b40f524aeb7df4aa', 2, 10, 'Active'),
(7, 'naruto', '2e97725f74c7c2f959e9ba31837a4247', 2, 17, 'Active'),
(8, 'bcruz', '5067a9ebbed832e2e5f66936e1482b95', 2, 8, 'Active');

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
(1, 'Bryan', 'Cruz', 'Santos', '664097177', '2018-01-30', '15 Amberstone Road', 'Winnipeg', 'Manitoba', 'R2P1K8', '2049550130', 'bryancruz@boostfinancial.ca', 0, 0, 0, 0, 1, 5, 'BC013018', 1, '2018-06-20 12:56:51', NULL, 'Admin', '0', '');

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
  `status` int(1) NOT NULL,
  `client` varchar(256) DEFAULT NULL
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

CREATE TABLE `tbl_product_type` (
  `product_type_id` int(3) NOT NULL,
  `product_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `company_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_member_info`
--
ALTER TABLE `tbl_member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_member_record_status`
--
ALTER TABLE `tbl_member_record_status`
  MODIFY `stat_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_member_trustee_details`
--
ALTER TABLE `tbl_member_trustee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `prod_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_product_type`
--
ALTER TABLE `tbl_product_type`
  MODIFY `product_type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user_types`
--
ALTER TABLE `tbl_user_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
