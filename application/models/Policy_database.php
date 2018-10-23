<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Policy_database extends CI_Model {
	function __construct(){

	}

	function get_policy($policy_id, $policy_number, $purchased_from="", $purchased_end=""){
		$this->db->select('
			tbl_policy_sold.policy_id,
			tbl_policy.policy_number,
			tbl_policy.client,
			tbl_policy.date_purchased,
			tbl_policy_sold.annual_prem,
			tbl_policy_sold.monthly_prem,
			tbl_policy_sold.company_id,
			tbl_policy_sold.curr_fyc,
			tbl_company.company_id, 
			tbl_company.name');
		$this->db->from('tbl_policy');
		$this->db->join('tbl_policy_sold','tbl_policy_sold.policy_id=tbl_policy.id');
		$this->db->join('tbl_company','tbl_company.company_id=tbl_policy_sold.company_id');
		if($policy_id!="")$this->db->where("tbl_policy_sold.policy_id",$policy_id);
		if($policy_number!="")$this->db->where("tbl_policy_sold.policy_number",$policy_number);	
		if($purchased_from!="" && $purchased_end!=""){
			$this->db->where("tbl_policy_sold.date_purchased > '".$purchased_from."'");	
			$this->db->where("tbl_policy_sold.date_purchased < '".$purchased_end."'");	
		}
		//if($prod_name!="")$this->db->where("tbl_products.prod_name like '%".$prod_name."%'");		
		$query = $this->db->get();
		$result = $query->result();	
		return $result;	
	}	

	function count_policy_rec($policy_number){
		$this->db->select('COUNT(*) as cntRec');
		$this->db->from('tbl_policy');
		$this->db->where("policy_number",$policy_number);	
		//if($prod_name!="")$this->db->where("tbl_products.prod_name like '%".$prod_name."%'");		
		$query = $this->db->get();
		$result = $query->result();		
		return $result[0]->cntRec;	
	}

	function update_policy($id,$details){
		$this->db->where("",$id);
		$this->db->update("",$details);
	}

	function insert_policy($policy){
		$this->db->insert('tbl_policy',$policy);
		return $this->db->insert_id();
	}

	function insert_policy_sold($policy_sold){
		$this->db->insert('tbl_policy_sold',$policy_sold);		
	}
}