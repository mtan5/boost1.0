<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Commission_database extends CI_Model {
	function __construct(){

	}

	function get_commission(
		$company_id="", 
		$product_id="", 
		$member_id="", 
		$date_start="", 
		$date_end="", 
		$policy_number="", 
		$commission_status="", 
		$policy_id="", 
		$commission_type=""
	){
		$this->db->select('
			tbl_policy_sold.policy_id,
			tbl_policy.policy_number,
			tbl_policy.client,
			tbl_policy.date_purchased,
			tbl_policy_sold.annual_prem,
			tbl_policy_sold.monthly_prem,
			tbl_policy_sold.company_id,
			tbl_policy_sold.curr_fyc,
			tbl_company.name,
			tbl_policy_sold.product_id,
			tbl_products.prod_name,
			tbl_products.prod_type,
			tbl_product_type.product_type,
			tbl_commissions.member_id,
			tbl_member_info.first_name,
			tbl_member_info.mid_name,
			tbl_member_info.last_name,
			tbl_member_info.code,
			tbl_commissions.member_level_id,
			tbl_membership_level.membership_level,
			tbl_membership_level.commission_factor,
			tbl_commissions.comm_id,
			tbl_commissions.commission_fyc,
			tbl_commissions.commission_amt_bonus,
			tbl_commissions.date_charged,
			tbl_commissions.date_modified,
			tbl_commissions.status,
			tbl_commission_status.commission_status,
			tbl_commissions.commission_type_id,
			tbl_commission_type.commission_type
			');
		$this->db->from('tbl_commissions');
		$this->db->join('tbl_member_info','tbl_member_info.id=tbl_commissions.member_id');
		$this->db->join('tbl_membership_level','tbl_membership_level.level_tbl_id=tbl_commissions.member_level_id');
		$this->db->join('tbl_policy','tbl_policy.id=tbl_commissions.policy_id');
		$this->db->join('tbl_policy_sold','tbl_policy_sold.policy_id=tbl_commissions.policy_id AND tbl_policy_sold.product_id=tbl_commissions.product_id');		
		$this->db->join('tbl_company','tbl_policy_sold.company_id=tbl_company.company_id');
		$this->db->join('tbl_products','tbl_products.prod_id=tbl_policy_sold.product_id');
		$this->db->join('tbl_product_type','tbl_products.prod_type=tbl_product_type.product_type_id');
		$this->db->join('tbl_commission_status','tbl_commission_status.commission_status_id=tbl_commissions.status');
		$this->db->join('tbl_commission_type','tbl_commission_type.id=tbl_commissions.commission_type_id');
		
		if($company_id!="")$this->db->where("tbl_policy_sold.company_id",$company_id);	
		if($product_id!="")$this->db->where("tbl_policy_sold.product_id",$product_id);
		if($member_id!="")$this->db->where("tbl_commissions.member_id",$member_id);
		if($policy_number!="")$this->db->where("tbl_policy.policy_number",$policy_number);
		if($policy_id!="")$this->db->where("tbl_policy_sold.policy_id",$policy_id);
		if($commission_status!="")$this->db->where("tbl_commissions.status",$commission_status);
		if($commission_type!="")$this->db->where("tbl_commissions.commission_type_id",$commission_type);

		if(($date_start!="") && !is_null($date_end)){			
			$end = new DateTime($date_end);
			$end->add(new DateInterval('P1D'));
			$end = $end->format('Y-m-d');
			$this->db->where("tbl_commissions.date_charged between '".$date_start."' and '".$end."'");
			//$this->db->where("tbl_commissions.date_charged >= '".$date_start."'");
			//$this->db->where("tbl_commissions.date_charged <= '".$end."'");			
		}	

		$query = $this->db->get();
		$result = $query->result();		
		//echo $this->db->last_query();
		return $result;	
	}	

	function getTotalFyc($member_id, $period_start, $period_end){
		$this->db->select('SUM(commission_fyc) as fyc_sum');
		$this->db->from('tbl_commissions');
		if(($period_start!="") && ($period_end!="")){			
			$this->db->where("tbl_commissions.date_charged > '".$period_start."'");
			$this->db->where("tbl_commissions.date_charged < '".$period_end."'");			
		}	
		$this->db->where("tbl_commissions.commission_type_id","1"); //direct commissions only
		$this->db->where("tbl_commissions.status !=","3"); //not charged back
		$query = $this->db->get();
		$result = $query->result();		
		//echo $this->db->last_query();
		return $result;	
	}

	function get_policy_sold($member_id, $purchased_from="", $purchased_end=""){
		$this->db->select('
			tbl_policy_sold.policy_id,
			tbl_policy.policy_number,
			tbl_policy.client,
			tbl_company.name');
		$this->db->from('tbl_policy_sold');
		$this->db->join('tbl_policy','tbl_policy.id=tbl_policy_sold.policy_id');
		$this->db->join('tbl_company','tbl_company.company_id=tbl_policy_sold.company_id');
		$this->db->join('tbl_commissions','tbl_policy_sold.policy_id=tbl_commissions.policy_id');
		if($member_id!="")$this->db->where("tbl_commissions.member_id",$member_id);	
		if($purchased_from!="" && $purchased_end!=""){
			$end = new DateTime($purchased_end);
			$end->add(new DateInterval('P1D'));
			$end = $end->format('Y-m-d');			
			$this->db->where("tbl_commissions.date_charged >= '".$purchased_from."'");	
			$this->db->where("tbl_commissions.date_charged <= '".$end."'");	
		}
		//if($prod_name!="")$this->db->where("tbl_products.prod_name like '%".$prod_name."%'");		
		$this->db->distinct('tbl_policy_sold.policy_number');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}		

	function get_commission_status(){
		$this->db->select('*');
		$this->db->from('tbl_commission_status');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;			
	}

	function get_commission_types(){
		$this->db->select('*');
		$this->db->from('tbl_commission_type');
		$query = $this->db->get();
		$result = $query->result();		
		return $result;			
	}	


	function update_commission($id,$details){
		$this->db->where("comm_id",$id);
		$this->db->update("tbl_commissions",$details);	
	}

	function charged_back_commissions($policy_id){
		$this->db->where("policy_id",$policy_id);
		$this->db->update("tbl_commissions",array('status'=>3));			
	}

	function insert_commission_record($details){
		$this->db->insert('tbl_commissions',$details);
	}

	function insert_commission_records($details_arr){
		$this->db->trans_start();
		foreach ($details_arr as $commission) {
			$this->db->insert('tbl_commissions',$commission);
		}
		$this->db->trans_complete();
	}	

	function insert_commission($policy, $policy_sold, $agent, $upline_arr, $director_arr, $policy_id){
		$this->db->trans_start();
		
		if($policy!=NULL && $policy_id==""){
			$this->db->insert('tbl_policy',$policy);
			$policy_id = $this->db->insert_id();
		}

		if($policy_sold!=NULL){
			$policy_sold += array('policy_id'=> $policy_id);
			$this->db->insert('tbl_policy_sold',$policy_sold);
		}
		
		$agent += array('policy_id'=> $policy_id);
		$this->db->insert('tbl_commissions',$agent);

		if($upline_arr!=NULL){
			foreach ($upline_arr as $upline) {
				$upline += array('policy_id'=> $policy_id);
				$this->db->insert('tbl_commissions',$upline);
			}
		}

		if($director_arr!=NULL){
			foreach ($director_arr as $director) {
				$director += array('policy_id'=>$policy_id);
				$this->db->insert('tbl_commissions',$director);
			}		
		}

		$this->db->trans_complete();
		//echo $this->db->last_query();
		//return $policy_id; 
	}
}