-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: martan4_boost
-- Source Schemata: martan4_boost
-- Created: Fri Jun 15 19:23:25 2018
-- Workbench Version: 6.3.6
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema martan4_boost
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `martan4_boost` ;
CREATE SCHEMA IF NOT EXISTS `martan4_boost` ;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_commission_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_commission_status` (
  `commission_status_id` INT(3) NOT NULL AUTO_INCREMENT,
  `commission_status` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`commission_status_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_commission_type
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_commission_type` (
  `id` INT(3) NOT NULL AUTO_INCREMENT,
  `commission_type` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_commissions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_commissions` (
  `comm_id` INT(11) NOT NULL AUTO_INCREMENT,
  `policy_id` INT(9) NOT NULL,
  `product_id` INT(5) NOT NULL,
  `member_id` INT(5) NOT NULL,
  `member_level_id` INT(3) NOT NULL,
  `commission_fyc` DECIMAL(8,2) NOT NULL,
  `commission_amt_bonus` DECIMAL(8,2) NOT NULL,
  `date_charged` DATE NOT NULL,
  `date_modified` DATE NULL DEFAULT NULL,
  `commission_type_id` INT(3) NOT NULL,
  `status` INT(1) NOT NULL,
  PRIMARY KEY (`comm_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 80
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_company
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_company` (
  `company_id` INT(2) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `status` INT(1) NOT NULL,
  PRIMARY KEY (`company_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_member_access
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_member_access` (
  `id` INT(9) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(32) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `user_type` INT(3) NOT NULL,
  `member_tbl_id` INT(6) NOT NULL,
  `access_status` VARCHAR(8) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_member_info
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_member_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(64) NOT NULL,
  `last_name` VARCHAR(64) NOT NULL,
  `mid_name` VARCHAR(64) NOT NULL,
  `sin` VARCHAR(15) NOT NULL,
  `dob` DATE NOT NULL,
  `address` VARCHAR(256) NOT NULL,
  `city` VARCHAR(32) NOT NULL,
  `province` VARCHAR(24) NOT NULL,
  `postal` VARCHAR(12) NOT NULL,
  `contact` VARCHAR(24) NOT NULL,
  `email` VARCHAR(64) NOT NULL,
  `upline_id` INT(2) NULL DEFAULT NULL,
  `director_id` INT(2) NULL DEFAULT NULL,
  `is_plead_guilty` INT(2) NOT NULL,
  `is_bankrupt` INT(2) NOT NULL,
  `is_legal_to_work` INT(2) NOT NULL,
  `level_id` INT(2) NULL DEFAULT NULL,
  `code` VARCHAR(24) NOT NULL,
  `status` INT(2) NOT NULL,
  `reg_date` DATETIME NOT NULL,
  `approved_date` DATETIME NULL DEFAULT NULL,
  `membership_process` VARCHAR(12) NOT NULL,
  `payment_id` VARCHAR(32) NULL DEFAULT NULL,
  `transaction_id` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_member_record_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_member_record_status` (
  `stat_tbl_id` INT(11) NOT NULL AUTO_INCREMENT,
  `record_status` VARCHAR(12) NOT NULL,
  PRIMARY KEY (`stat_tbl_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_member_trustee_details
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_member_trustee_details` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) NULL DEFAULT NULL,
  `trustee_name` VARCHAR(256) NULL DEFAULT NULL,
  `trustee_address` VARCHAR(256) NULL DEFAULT NULL,
  `location_bankruptcy` VARCHAR(150) NULL DEFAULT NULL,
  `assignment_bankruptcy` VARCHAR(150) NULL DEFAULT NULL,
  `statement_of_affairs` VARCHAR(150) NULL DEFAULT NULL,
  `explanation` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_membership_level
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_membership_level` (
  `level_tbl_id` INT(11) NOT NULL AUTO_INCREMENT,
  `membership_level` VARCHAR(32) NOT NULL,
  `commission_factor` INT(3) NULL DEFAULT NULL,
  `level_up_rqrmnts` DECIMAL(8,2) NOT NULL,
  PRIMARY KEY (`level_tbl_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_membership_payment_rec
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_membership_payment_rec` (
  `id` INT(6) NOT NULL AUTO_INCREMENT,
  `transaction_date` DATE NOT NULL,
  `payment_mode` VARCHAR(32) NOT NULL,
  `transaction_ref` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_plead_guilty_explanation
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_plead_guilty_explanation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) NOT NULL,
  `explanation` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_policy_holders
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_policy_holders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `policy_id` INT(11) NOT NULL,
  `holder_first_name` VARCHAR(32) NOT NULL,
  `holder_last_name` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_policy_sold
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_policy_sold` (
  `policy_id` INT(9) NOT NULL AUTO_INCREMENT,
  `policy_number` VARCHAR(32) NOT NULL,
  `client` VARCHAR(256) NOT NULL,
  `company_id` INT(3) NOT NULL,
  `product_id` INT(4) NOT NULL,
  `date_purchased` DATE NOT NULL,
  `annual_prem` DECIMAL(8,2) NULL DEFAULT NULL,
  `monthly_prem` DECIMAL(8,2) NULL DEFAULT NULL,
  `curr_fyc` DECIMAL(5,2) NOT NULL,
  `status` INT(1) NOT NULL,
  PRIMARY KEY (`policy_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_product_type
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_product_type` (
  `product_type_id` INT(3) NOT NULL AUTO_INCREMENT,
  `product_type` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`product_type_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_products
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_products` (
  `prod_id` INT(5) NOT NULL AUTO_INCREMENT,
  `company_id` INT(4) NOT NULL,
  `prod_name` VARCHAR(64) NOT NULL,
  `fyc` DECIMAL(5,2) NOT NULL,
  `prod_type` INT(2) NOT NULL,
  `status` INT(1) NOT NULL,
  PRIMARY KEY (`prod_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table martan4_boost.tbl_user_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `martan4_boost`.`tbl_user_types` (
  `id` INT(3) NOT NULL AUTO_INCREMENT,
  `user_type` VARCHAR(12) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Routine martan4_boost.GetAllDownlines
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `martan4_boost`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllDownlines`(`mbr_id` INT(4))
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

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine martan4_boost.GetAllDownlineSales
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `martan4_boost`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllDownlineSales`(IN `mbr_id` INT(4), IN `period_start` DATETIME, IN `period_end` DATETIME)
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
    tbl_commissions.commission_fyc,
    tbl_commissions.commission_amt_bonus,
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
SET FOREIGN_KEY_CHECKS = 1;
