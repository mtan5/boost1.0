DELIMITER $$

DROP PROCEDURE IF EXISTS `GetAllDownlineSales`$$
CREATE PROCEDURE `GetAllDownlineSales` (`mbr_id` INT(4), period_start DATETIME, period_end DATETIME)  BEGIN 

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