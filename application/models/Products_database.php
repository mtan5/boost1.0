<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products_database extends CI_Model {
	function __construct(){

	}

	function get_product($id="", $prod_name="", $company_id="", $status="1", $prod_type=""){
		$this->db->select('
			tbl_products.prod_id,
			tbl_products.company_id,
			tbl_company.name as company_name,
			tbl_products.prod_name,
			tbl_products.fyc,
			tbl_products.status,
			tbl_products.prod_type as product_type_id,
			tbl_product_type.product_type
			');
		$this->db->from('tbl_products');
		$this->db->join('tbl_company','tbl_company.company_id=tbl_products.company_id');
		$this->db->join('tbl_product_type','tbl_product_type.product_type_id=tbl_products.prod_type');
		if($id!="")$this->db->where("tbl_products.prod_id",$id);
		if($company_id!="")$this->db->where("tbl_products.company_id",$company_id);
		if($prod_type!="")$this->db->where("tbl_products.prod_type",$prod_type);
		if($prod_name!="")$this->db->where("tbl_products.prod_name like '%".$prod_name."%'");
		if($status!="")$this->db->where("tbl_products.status",$status);

		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}	

	function get_product_sold($policy_id="", $policy_number="", $company_id="", $prod_id="", $date_purchased_start="", $date_purchased_end="", $status="", $member_id=""){
		$this->db->select('
			tbl_policy_sold.policy_sale_id,
			tbl_policy_sold.policy_id,
			tbl_policy.policy_number,
			tbl_policy_sold.company_id,
			tbl_company.name as company_name,
			tbl_policy_sold.product_id,
			tbl_products.prod_name,
			tbl_policy.client,
			tbl_policy.date_purchased,
			tbl_policy_sold.annual_prem,
			tbl_policy_sold.monthly_prem,						
			tbl_policy_sold.curr_fyc,
			tbl_policy_sold.status,
			tbl_products.prod_type as product_type_id,
			tbl_product_type.product_type
			');	
		$this->db->from('tbl_policy_sold');
		$this->db->join('tbl_products','tbl_products.prod_id=tbl_policy_sold.product_id');
		$this->db->join('tbl_company','tbl_company.company_id=tbl_policy_sold.company_id');	
		$this->db->join('tbl_product_type','tbl_product_type.product_type_id=tbl_products.prod_type');
		$this->db->join('tbl_policy','tbl_policy.id=tbl_policy_sold.policy_id');

		if($member_id!="") $this->db->join('tbl_commissions','tbl_commissions.policy_id=tbl_policy_sold.policy_id');
		if($policy_id!="")$this->db->where("tbl_policy_sold.policy_id",$policy_id);
		if($policy_number!="")$this->db->where("tbl_policy.policy_number",$policy_number);
		if($prod_id!="")$this->db->where("tbl_policy_sold.product_id",$prod_id);
		if($company_id!="")$this->db->where("tbl_policy_sold.company_id",$company_id);
		if($status!="")$this->db->where("tbl_policy_sold.status",$status);		
		if($member_id!=""){
			$this->db->where("tbl_commissions.member_id",$member_id);		
			$this->db->where("tbl_commissions.commission_type","agent");		
		}

		if(($date_purchased_start!="") && ($date_purchased_end!="")){			
			$end = new DateTime($date_purchased_end);
			$end->add(new DateInterval('P1D'));
			$end = $end->format('Y-m-d');
			$this->db->where("tbl_policy.date_purchased > '".$date_purchased_start."'");
			$this->db->where("tbl_policy.date_purchased < '".$end."'");			
		}		
		$this->db->order_by('tbl_policy.date_purchased', 'ASC');
		$query = $this->db->get();
		$result = $query->result();		
		//echo $this->db->last_query();
		return $result;			
	}

	function get_product_types(){
		$this->db->select('*');
		$this->db->from('tbl_product_type');
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}		

	function update_product($id, $details){
		$this->db->where("prod_id",$id);
		$this->db->update("tbl_products",$details);
	}

	function update_policy_sold($id,$details){
		$this->db->where("policy_sale_id",$id);
		$this->db->update("tbl_policy_sold",$details);
	}	

	function update_policy($id,$details){
		$this->db->where("id",$id);
		$this->db->update("tbl_policy",$details);
	}		

	function delete_policy_sold($id){
		$this->db->trans_start();
		$this->db->where("id",$id);
		$this->db->delete('tbl_policy'); 

		$this->db->where("policy_id",$id);
		$this->db->delete('tbl_policy_sold'); 

		$this->db->where("policy_id",$id);
		$this->db->delete('tbl_commissions'); 		
		$this->db->trans_complete();		
	}		

	function insert_product($details){
		$this->db->insert('tbl_products',$details);
	}	
}