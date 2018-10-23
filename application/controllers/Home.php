<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('member_database'); 
		$this->load->helper('form');
		$this->load->helper('script_path_helper');
		$this->load->library('session');

		//load href links of bootstrap and user defined css and JS
		$this->session->set_userdata('script_links', get_default_script_libraries());							
	}

	public function index()
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" && $sess_member_id!="" && $sess_user_type!=""){
			if($sess_user_type=="2")redirect('/member');
			if($sess_user_type=="1")redirect('/admin');
		}		

		$data1 = $this->session->userdata('script_links');
		$data2 = array('message' => '');
		$this->load->view('header', $data1);
		$this->load->view('home_login', $data2);
	}

	public function authenticate(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_password = $this->session->userdata('password');
		$sess_attempt = $this->session->userdata('attempts');

		if($sess_username!="" && $sess_password!=""){
			$username = $sess_username;
			$password = md5($sess_password);
		}
		else{
			$username = $this->input->post('txt_username');
			$password = md5($this->input->post('txt_password'));
		}


		if($sess_username!="" && $sess_member_id!=""){
			//redirect('/schedule');
		}		

		$member_access_info = $this->member_database->authenticateMember($username, $password);

		if(count($member_access_info) == 0){	
			$message = "Authentication Failed!";
			$data1 = $this->session->userdata('script_links');			
			$data2 = array('message' => $message);
			$this->load->view('header', $data1);
			$this->load->view('home_login', $data2);
		}
		else{
			//set session variables
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('password', $password);
			$this->session->set_userdata('member_table_id', $member_access_info[0]->member_tbl_id);
			$this->session->set_userdata('user_type', $member_access_info[0]->user_type);

			if($member_access_info[0]->user_type == "1"){				
				redirect('/admin');
			}
			else{				
				redirect('/member');
			}
		}				
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('/home');
	}	
}
