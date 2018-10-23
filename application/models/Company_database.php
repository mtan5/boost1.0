<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company_database extends CI_Model {
	function __construct(){

	}

	function get_company($id="", $like_company_name="", $company_name="", $status="1"){
		$this->db->select('*');
		$this->db->order_by('tbl_company.name', 'ASC');
		$this->db->from('tbl_company');
		if($id!="")$this->db->where("company_id",$id);
		if($company_name!="")$this->db->where("name",$company_name);
		if($like_company_name!="")$this->db->where("name like '%".$like_company_name."%'");
		if($status!="")$this->db->where("status",$status);

		$query = $this->db->get();
		$result = $query->result();		
		return $result;	
	}	

	function update_company($id, $details){
		$this->db->where("company_id",$id);
		$this->db->update("tbl_company",$details);
	}

	function insert_company($name){
		$this->db->insert('tbl_company',array('name'=>$name,'status'=>'1'));
	}
}