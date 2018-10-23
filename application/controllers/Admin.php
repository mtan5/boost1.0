<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
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
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}	

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		
		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu',$menu_data);		
		$this->load->view('/admin/admin_page');
		$this->load->view('footer');		
	}	

	public function levels(){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$upline_id = $this->session->userdata('member_table_id');

		if($sess_username=="" || $upline_id==""){
			redirect('/home/logout');
		}

		$levels = $this->member_database->get_all_membership_level();

		$menu_data = array('username'=>$sess_username);
		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_functions_path'=>member_functions_path());
		$data1 += array('function_lib_path'=>function_lib_path());
		$data1 += array('jqueryui_path'=>jqueryui_path());
		$data1 += array('jqueryuicss_path'=>jqueryuicss_path());		
		$data2 = array('levels'=>$levels);

		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);		
		$this->load->view('/member/member_levels', $data2);	
		$this->load->view('footer');		
	}

	public function save_levels(){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$upline_id = $this->session->userdata('member_table_id');

		if($sess_username=="" || $upline_id==""){
			redirect('/home/logout');
		}

		$levels = $this->member_database->get_all_membership_level();
		$all_update = true;
		foreach($levels as $lvl){
			$level=$this->input->post('level_'.$lvl->level_tbl_id);
			$fyc_rqr=$this->input->post('level_up_rqrmnts_'.$lvl->level_tbl_id);
			$bonus=$this->input->post('commission_factor_'.$lvl->level_tbl_id);
			$proceed = true;

			if(!is_numeric($fyc_rqr) || !is_numeric($bonus)){
				echo "
				<script>
					alert('Saving Membership Level Failed! Membership Level #".$lvl->level_tbl_id." has some invalid inputs..');
				</script>
				";
				$proceed = false;
				$all_update = false;
			}
			
			if($proceed){
				$level_details = array(
						'membership_level'=>$level,
						'level_up_rqrmnts'=>$fyc_rqr,
						'commission_factor'=>$bonus
					);
				$this->member_database->update_membership_levels($lvl->level_tbl_id, $level_details);
			}
		}

		if($all_update){
				echo "
				<script>
					alert('Saving Membership Level was successful!');
				</script>
				";	
		}

		echo "
		<script>		
			window.location.href = '".base_url()."admin/levels'			
		</script>
		";
		//redirect('/admin/levels');
	}

	public function list(){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$page_divider = 15;
		$dor="";
		$first_name=$this->input->post('search_fname');
		$last_name=$this->input->post('search_lname');
		$code=$this->input->post('search_code');
		$status=$this->input->post('search_status');
		$level=$this->input->post('search_level');
		$page_num = $this->input->post('page_number');
		if($page_num=="") $page_num = 1;

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		$data1 += array('function_lib_path'=>function_lib_path());
		$data1 += array('jqueryui_path'=>jqueryui_path());
		$data1 += array('jqueryuicss_path'=>jqueryuicss_path());
		//$data1 += array('member_functions_path'=>member_functions_path());
		$data1 += array('admin_functions_path'=>admin_functions_path());		
		$member_list = $this->member_database->get_all_members($first_name, $last_name, $code, $level, $status, $dor);
		$db_membership_levels = $this->member_database->get_all_membership_level();
		$membership_levels[0]="";
		foreach($db_membership_levels as $lvl) {
			$membership_levels[$lvl->level_tbl_id]=$lvl->membership_level;
		}	
		
		$db_membership_status = $this->member_database->get_all_membership_status();
		$membership_status[0]="";
		foreach($db_membership_status as $stat) {
			$membership_status[$stat->stat_tbl_id]=$stat->record_status;
		}	

		$page_count = ceil((count($member_list) / $page_divider));

		if($page_num=="all"){
			$page_start = 1;
			$page_end = "";			
			$rec_start = 1;
			$rec_end = count($member_list);
		}
		else{
			$page_start = $page_num - 1;
			$page_end = $page_num;			
			$rec_start = $page_start * $page_divider + 1;
			$rec_end = $page_end * $page_divider;						
		}

		$list = array('member_list'=>$member_list);
		$list += array('membership_levels'=>$membership_levels);
		$list += array('membership_status'=>$membership_status);
		$list += array('dor'=>$dor);
		$list += array('status'=>$status);
		$list += array('level'=>$level);
		$list += array('code'=>$code);
		$list += array('last_name'=>$last_name);
		$list += array('first_name'=>$first_name);
		$list += array('page_count'=>$page_count);
		$list += array('rec_start'=>$rec_start);
		$list += array('rec_end'=>$rec_end);
		$list += array('page_num'=>$page_num);

		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);	
		if(count($member_list) > 0){
			$list += array('message'=>'');
		}
		else{
			$list += array('message'=>'No Member records found..');
		}
		$this->load->view('/member/member_list', $list);
		$this->load->view('footer');		
	}

	public function requests(){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$dor=$this->input->post('dor');
		$first_name=$this->input->post('search_fname');
		$last_name=$this->input->post('search_lname');
		$code="";
		$status="2";
		$level="";
		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		$data1 += array('member_functions_path'=>member_functions_path());
		$data1 += array('admin_functions_path'=>admin_functions_path());
		$data1 += array('function_lib_path'=>function_lib_path());
		$data1 += array('jqueryui_path'=>jqueryui_path());
		$data1 += array('jqueryuicss_path'=>jqueryuicss_path());

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}	
		$db_requests = $this->member_database->get_all_members($first_name, $last_name, $code, $level, $status, $dor);
		$requests = array('requests'=>$db_requests);
		if(count($requests) > 0){
			$requests += array('message'=>'');
		}
		else{
			$requests += array('message'=>'No Member records found..');
		}
		$requests += array('dor'=>$dor);
		$requests += array('last_name'=>$last_name);
		$requests += array('first_name'=>$first_name);
		
		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);
		$this->load->view('/member/member_requests', $requests);
		$this->load->view('footer');		
	}

	public function view_member($member_tbl_id){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}
		$this->session->set_userdata('member_table_id', $member_tbl_id);
		redirect('/member/info');
	}

	public function edit_member($member_tbl_id){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}
		$this->session->set_userdata('member_table_id', $member_tbl_id);
		redirect('/member/form/edit');
	}	

	public function getAccessDetails(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type=="1"){
			$access = $this->member_database->get_member_access($this->input->post('id'));
			if($access > 0){
				$arr = array(
					'username' => $access[0]->username,
					); 
			}
			else{
				$arr = array(
					'username' => "NONE",
					); 
			}
		}	
		else{
				$arr = array(
					'username' => "ERROR",
					); 
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );				
	}

	public function saveAccess(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		
		if($sess_username=="" || $sess_user_type=="1"){

			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$member_id = $this->input->post('member_id');
			$process=$this->input->post('process');			

			$access=array(
				'username'=>$username,
				'password'=>md5($password),
				'user_type'=>2,
				'member_tbl_id'=>$member_id,
				'access_status'=>'Active'
				);
			if($process=="edit"){
				$this->member_database->update_member_access($member_id, $access);
				$arr = array('result' => 1); //success
			}
			else{
				$count = $this->member_database->get_count_username($username);
				if($count>0){
					$arr = array('result' => 2); //username already exists
				}
				else{
					$this->member_database->insert_member_access($access);		
					$arr = array('result' => 1); //success
				}
				
			}				
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );			
	}		

	public function approve_member($id){

		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}
		$info = array(
			'status'=>"1",
			'approved_date'=>date("Y-m-d h:i:sa")
		);
		$this->member_database->update_member_info($id, $info);
		$member_info = $this->member_database->get_member_info($id);
		echo 
		"<script>
			alert('Approval was successful for ".$member_info[0]->first_name." ".$member_info[0]->last_name."');
			window.location.href='".base_url()."admin/requests';
		</script>";
	}
}
