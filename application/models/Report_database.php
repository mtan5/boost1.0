<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_database extends CI_Model {
	function __construct(){

	}		

	function get_commission_statement($member_id, $period_start, $period_end, $file_name="", $id=""){
		$this->db->select('
			tbl_commission_statements.id,
			tbl_commission_statements.member_id,
			tbl_commission_statements.file_name,
			tbl_commission_statements.date_created,
			tbl_commission_statements.statement_period,
			tbl_member_info.first_name,
			tbl_member_info.last_name
			');
		$this->db->from('tbl_commission_statements');
		$this->db->join('tbl_member_info','tbl_member_info.id=tbl_commission_statements.member_id');
		if($id!="")$this->db->where("tbl_commission_statements.id",$id);
		if($member_id!="")$this->db->where("tbl_commission_statements.member_id",$member_id);
		if($file_name!="")$this->db->where("file_name like '%".$file_name."%'");		

		if(($period_start!="") && !is_null($period_end)){			
			$end = new DateTime($period_end);
			$end->add(new DateInterval('P1D'));
			$end = $end->format('Y-m-d');
			$this->db->where("tbl_commission_statements.date_created between '".$period_start."' and '".$end."'");
			//$this->db->where("tbl_commission_statements.date_created >= '".$period_start."'");
			//$this->db->where("tbl_commission_statements.date_created <= '".$end."'");			
		}

		$query = $this->db->get();
		$result = $query->result();	
		echo $this->db->last_query();	
		return $result;			
	}

	function insert_commission_statement($details){
		$this->db->insert('tbl_commission_statements',$details);
	}

	function delete_commission_statement($id){
		$this->db->where('id', $id);
		$this->db->delete('tbl_commission_statements'); 		
	}
}