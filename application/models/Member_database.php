<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member_database extends CI_Model {
	function __construct(){

	}

	function authenticateMember($username, $password){
		$this->db->select('*');
		$this->db->from('tbl_member_access');
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->where('access_status', "Active");
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}

	function get_all_members($first_name="", $last_name="", $code="", $level="", $status="", $dor="", $upline_id=""){
		$this->db->select('
			tbl_member_info.id,
			tbl_member_info.code, 
			tbl_member_info.first_name,
			tbl_member_info.last_name,
			tbl_member_info.reg_date,
			tbl_member_info.payment_id,
			tbl_member_info.status,
			tbl_member_record_status.record_status, 
			tbl_membership_level.membership_level, 
			tbl_member_access.username');
		$this->db->from('tbl_member_info');
		$this->db->join('tbl_member_record_status','tbl_member_record_status.stat_tbl_id=tbl_member_info.status');
		$this->db->join('tbl_membership_level','tbl_membership_level.level_tbl_id=tbl_member_info.level_id');
		$this->db->join('tbl_member_access','tbl_member_access.member_tbl_id=tbl_member_info.id', 'left');

		if($first_name!="")$this->db->where("first_name like '%".$first_name."%'");
		if($last_name!="")$this->db->where("last_name like '%".$last_name."%'");
		if($code!="")$this->db->where("tbl_member_info.code",$code);
		if($level!=0)$this->db->where("tbl_member_info.level_id",$level);
		if($status!=0)$this->db->where("tbl_member_info.status",$status);
		if($dor!="")$this->db->where("tbl_member_info.reg_date",$dor);
		if($upline_id!="")$this->db->where("tbl_member_info.upline_id",$upline_id);

		$this->db->order_by('tbl_member_info.first_name', 'ASC');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}

	function get_member_downlines($id){
        $query = $this->db->query("CALL GetAllDownlines(".$id.")");
        return $query->result();
	}

	function getMemberInfoByEmail($email){
		$this->db->select('tbl_member_info.id, tbl_member_info.email, tbl_member_access.username');
		$this->db->from('tbl_member_info');
		$this->db->join('tbl_member_access','tbl_member_access.member_tbl_id=tbl_member_info.id');		
		$this->db->where("tbl_member_info.email",$email);
		$query = $this->db->get();
		return $query->result();	
		//echo $this->db->last_query();	
	}	

	function get_member_downline_sales($id, $period_start, $period_end, $downline_level="1"){
        if($downline_level=="1"){
		    $this->db->select('
		    info2.id,
		    info2.first_name, 
		    info2.last_name, 
		    info2.mid_name, 
		    info2.code, 
		    info2.upline_id,
		    CONCAT(info2.first_name, " ", info2.last_name) AS upline_name,
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
		    tbl_commissions.date_charged');

			$this->db->from('tbl_member_info');
			$this->db->join('tbl_member_info AS info2','info2.upline_id = tbl_member_info.id');		    
			$this->db->join('tbl_commissions', 'tbl_commissions.member_id=info2.id');
		    $this->db->join('tbl_policy_sold','tbl_policy_sold.policy_id=tbl_commissions.policy_id AND tbl_policy_sold.product_id=tbl_commissions.product_id');
		    $this->db->join('tbl_policy','tbl_policy.id=tbl_commissions.policy_id');
		    $this->db->join('tbl_products','tbl_products.prod_id = tbl_policy_sold.product_id');
		    $this->db->join('tbl_company','tbl_company.company_id = tbl_policy_sold.company_id');
		    $this->db->join('tbl_membership_level','tbl_membership_level.level_tbl_id=tbl_commissions.member_level_id');
		    $this->db->join('tbl_commission_status','tbl_commission_status.commission_status_id=tbl_commissions.status');
		    
		    $this->db->where("tbl_member_info.id",$id);

		    if($period_start!="" && $period_end!="") $this->db->where("tbl_commissions.date_charged BETWEEN DATE('".$period_start."') AND DATE('".$period_end."')");

		    $query = $this->db->get();
        }
        else{
        	$query = $this->db->query("CALL GetAllDownlineSales(".$id.",'".$period_start."','".$period_end."')");	
        }      
        //echo $this->db->last_query();  
        return $query->result();
	}	

	function getMaxRowMember(){
		$this->db->select('MAX(id) as MaxId');
		$this->db->from('tbl_member_info');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;			
	}

	function getMemberCount($id=""){
		$this->db->select('COUNT(*) as RecCount');
		$this->db->from('tbl_member_info');
		$this->db->where("tbl_member_info.id",$id);
		$query = $this->db->get();		
		$result = $query->result();		
		return $result[0]->RecCount;			
	}	

	function get_all_membership_level(){
		$this->db->select('*');
		$this->db->from('tbl_membership_level');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}

	function get_membership_level($level_id){
		$this->db->select('*');
		$this->db->from('tbl_membership_level');
		$this->db->where("tbl_membership_level.level_tbl_id",$level_id);
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}		

	function get_count_username($username){
		$this->db->select('COUNT(*) as usercount');
		$this->db->from('tbl_member_access');
		$this->db->where("username",$username);
		$query = $this->db->get();
		$result = $query->result();		
		return $result[0]->usercount;	
	}		

	function get_member_access($id){
		$this->db->select('*');
		$this->db->from('tbl_member_access');
		$this->db->where("access_status","Active");
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}		

	function isUpline($member_id, $upline_id){
		$this->db->select('*');
		$this->db->from('tbl_member_info');
		$this->db->where("tbl_member_info.id",$member_id);
		$this->db->where("tbl_member_info.upline_id",$upline_id);
		$query = $this->db->get();
		$result = $query->result();	
		if(count($result) == 0) return false;
		return true;	
	}

	function get_all_membership_status(){
		$this->db->select('*');
		$this->db->from('tbl_member_record_status');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}		

	function get_member_info($id){
		$this->db->select("
			tbl_member_info.id,
			tbl_member_info.first_name,
			tbl_member_info.last_name,
			tbl_member_info.mid_name,
			tbl_member_info.sin,
			tbl_member_info.dob,
			tbl_member_info.address,
			tbl_member_info.city,
			tbl_member_info.province,
			tbl_member_info.postal,
			tbl_member_info.contact,
			tbl_member_info.email,
			tbl_member_info.upline_id,
			CONCAT(tbl_recruiter.first_name,' ',tbl_recruiter.last_name) as recruiter,
			tbl_member_info.director_id,
			CONCAT(tbl_director.first_name,' ',tbl_director.last_name) as director,
			tbl_member_info.is_plead_guilty,
			tbl_member_info.is_bankrupt,
			tbl_member_info.is_legal_to_work,
			tbl_member_info.level_id,
			tbl_membership_level.membership_level,
			tbl_membership_level.level_up_rqrmnts,
			tbl_member_info.code,
			tbl_member_info.status,
			tbl_member_record_status.record_status,
			tbl_member_info.reg_date,
			tbl_member_info.membership_process,
			tbl_member_info.payment_id,
			tbl_member_trustee_details.trustee_name,
			tbl_member_trustee_details.trustee_address,
			tbl_member_trustee_details.location_bankruptcy,
			tbl_member_trustee_details.assignment_bankruptcy,
			tbl_member_trustee_details.statement_of_affairs,
			tbl_member_trustee_details.explanation as bankrupt_explanation,
			tbl_plead_guilty_explanation.explanation,
			tbl_member_access.username
			");
		$this->db->from('tbl_member_info');		
		$this->db->join('tbl_member_record_status','tbl_member_record_status.stat_tbl_id=tbl_member_info.status');
		$this->db->join('tbl_membership_level','tbl_membership_level.level_tbl_id=tbl_member_info.level_id');
		$this->db->join('tbl_member_trustee_details','tbl_member_trustee_details.member_id=tbl_member_info.id','left');
		$this->db->join('tbl_plead_guilty_explanation','tbl_plead_guilty_explanation.member_id=tbl_member_info.id','left');
		$this->db->join('(SELECT first_name, last_name, id from tbl_member_info WHERE level_id!=1) AS tbl_recruiter','tbl_recruiter.id=tbl_member_info.upline_id','left');
		$this->db->join('(SELECT first_name, last_name, id from tbl_member_info WHERE level_id=5) AS tbl_director','tbl_director.id=tbl_member_info.director_id','left');
		$this->db->join('tbl_member_access','tbl_member_access.member_tbl_id=tbl_member_info.id', 'left');

		$this->db->where('tbl_member_info.id', $id);
		$query = $this->db->get();		
		$result = $query->result();		
		return $result;	
	}	

	function insert_member_details($member, $explanation, $trustee){
		$this->db->trans_start();
		$this->db->insert('tbl_member_info',$member);
		$member_id = $this->db->insert_id();
			
		if($explanation!=NULL){
			$explanation += array('member_id'=>$member_id);
			$this->db->insert('tbl_plead_guilty_explanation',$explanation);
		}

		if($trustee!=NULL){
			$trustee += array('member_id'=>$member_id);
			$this->db->insert('tbl_member_trustee_details',$trustee);
		}

		$this->db->trans_complete();
	}

	function insert_member_info($member){
		$this->db->insert('tbl_member_info',$member);
		return $this->db->insert_id();
	}

	function insert_member_explanation($explanation){
		$this->db->insert('tbl_plead_guilty_explanation',$explanation);
	}	

	function insert_member_access($access){
		$this->db->insert('tbl_member_access',$access);
	}		

	function insert_member_trustee($trustee){
		$this->db->insert('tbl_member_trustee_details',$trustee);
	}

	function update_member_access($member_id, $access){
		$this->db->where("member_tbl_id",$member_id);
		$this->db->update("tbl_member_access",$access); 		
	}

	function update_guilty_info($member_id, $explanation){
		$this->db->where("member_id",$member_id);
		$this->db->update("tbl_plead_guilty_explanation",$explanation); 		
	}	

	function update_trustee_info($member_id, $trustee){
		$this->db->where("member_id",$member_id);
		$this->db->update("tbl_member_trustee_details",$trustee); 		
	}	

	function update_member_info($id, $member_info){
		$this->db->where("id",$id);
		$this->db->update("tbl_member_info",$member_info); 		
	}	

	function update_membership_levels($id, $details){
		$this->db->where("level_tbl_id",$id);
		$this->db->update("tbl_membership_level",$details); 		
	}		

	function countMemberExist($fname, $lname, $dob){
		$this->db->select('COUNT(*) as count');
		$this->db->from('tbl_member_info');
		$this->db->where('first_name', $fname);
		$this->db->where('last_name', $lname);
		$this->db->where('dob', $dob);
		$query = $this->db->get();
		$result = $query->result();
		return $result[0]->count;		
	}

	function countEmailExist($email){
		$this->db->select('COUNT(*) as count');
		$this->db->from('tbl_member_info');
		$this->db->where('email', $email);
		$query = $this->db->get();
		$result = $query->result();
		return $result[0]->count;		
	}	

	function countGuiltyRecords($id){
		$this->db->select('COUNT(*) as count');
		$this->db->from('tbl_plead_guilty_explanation');
		$this->db->where('member_id', $id);
		$query = $this->db->get();
		$result = $query->result();
		return $result[0]->count;
	}

	function countTrusteeRecords($id){
		$this->db->select('COUNT(*) as count');
		$this->db->from('tbl_member_trustee_details');
		$this->db->where('member_id', $id);
		$query = $this->db->get();
		$result = $query->result();
		return $result[0]->count;
	}			

	function delete_member($id)
	{
		//$this->db->where('id', $id);
		//$this->db->delete('tbl_member_info'); 		
	}	
}