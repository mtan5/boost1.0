<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('company_database'); 
		$this->load->helper('form');
		$this->load->helper('script_path_helper');
		$this->load->library('session');

		//load href links of bootstrap and user defined css and JS
		$this->session->set_userdata('script_links', get_default_script_libraries());							
	}

	public function list()
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		$search_company_name = $this->input->post('search_company_name');
		$search_status = $this->input->post('search_status');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}		

		$message = "";
		$company_list = $this->company_database->get_company("",$search_company_name,"",$search_status);
		if (count($company_list) == 0) $message = "No records found";
		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		$data2 = array('message' => $message);
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());				
		$data2 += array('company_list'=>$company_list);
		$data2 += array('search_company_name'=>$search_company_name);
		$data2 += array('search_status'=>$search_status);
		
		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);
		$this->load->view('/company/company_list', $data2);
		$this->load->view('footer');	
	}	

	public function addNew(){
		$company_name = $this->input->post('company_name');
		$get_company = $this->company_database->get_company("","",$company_name,"1");

		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');


		if($sess_username!="" || $sess_user_type=="1"){
			if(count($get_company) > 0){
				$arr = array('result' => 2); 
			}
			else{
				$this->company_database->insert_company($company_name);
				$arr = array('result' => 1); 
			}			
		}
		else{
			$arr = array('result' => 3); 
		}
	    //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );			
	}

	public function save(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_user_type=="1"){
			$company_name = $this->input->post('company_name');
			$company_id = $this->input->post('company_id');
			$status = $this->input->post('status');
			$details = array(
				'name'=>$company_name,
				'status'=>$status
				);

			$this->company_database->update_company($company_id,$details);
			$arr = array('result' => 1); 			
		}
		else{
			$arr = array('result' => 3); 
		}

	    //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );			
	}		

	public function delete($company_id="0"){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_user_type=="1"){
			$status = $this->input->post('status');
			$details = array('status'=>0);
			$this->company_database->update_company($company_id, $details);			
			redirect('/company/list');
		}
		else{
			redirect('/home/logout');
		}			
	}		
}
