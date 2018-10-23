<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('member_database'); 
		$this->load->model('commission_database'); 
		$this->load->helper('form');
		$this->load->helper('script_path_helper');
		$this->load->helper('member_info_helper');
		$this->load->library('session');

		//load href links of bootstrap and user defined css and JS
		$this->session->set_userdata('script_links', get_default_script_libraries());							
	}

	public function index($page_num="1")
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		
		$search_first_name=$this->input->post('search_fname');
		$search_last_name=$this->input->post('search_lname');
		$code=$this->input->post('search_code');
		$status=$this->input->post('search_status');
		$level=$this->input->post('search_level');
		$totalFyc = 0;
		$monthlyFyc = 0;
		$level_up_rqrmnts = 0;

		if($sess_username=="" || $sess_member_id=="" || $sess_user_type!="2"){
			redirect('/home/logout');
		}	

		$member_info = $this->member_database->get_member_info($sess_member_id);
		$downline = $this->member_database->get_all_members($search_first_name, $search_last_name, $code, $level, $status,"",$sess_member_id);
		$message =  "";
		if(count($downline) == 0) $message =  "No Records Found.";		
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

		$page_divider = 15;
		$page_start = $page_num - 1;
		$page_end = $page_num;
		$rec_start = $page_start * $page_divider + 1;
		$rec_end = $page_end * $page_divider;
		$page_count = ceil((count($member_info) / $page_divider));

		$data2 = array(
				'first_name'=> "",
				'last_name'=> "",
				'mid_name'=> ""
			);		

		if(count($member_info) != 0){
			$data2 = array(
				'first_name'=> $member_info[0]->first_name,
				'last_name'=> $member_info[0]->last_name,
				'mid_name'=> $member_info[0]->mid_name,
			);
			$this->session->set_userdata('login_fname', $member_info[0]->first_name);
			$this->session->set_userdata('login_lname', $member_info[0]->last_name);
			$this->session->set_userdata('login_mname', $member_info[0]->mid_name);

			if( $member_info[0]->membership_level!=1){ //not a trainee
				$level_up_rqrmnts = $member_info[0]->level_up_rqrmnts;

				$date = new DateTime(date("Y-m-d"));
				$period_start = $date->sub(new DateInterval('P6M'));
				$period_start = $period_start->format('Y-m-d');
				$period_end = new DateTime(date("Y-m-d"));
				$period_end = $period_end->format('Y-m-d');
				$totalFyc = $this->commission_database->getTotalFyc($sess_member_id,$period_start, $period_end);
				
				$date = new DateTime(date("Y")."-".date("m")."-1");
				$period_start = $date->format('Y-m-d');
				$monthlyFyc = $this->commission_database->getTotalFyc($sess_member_id,$period_start, $period_end);
			}
		}

		$menu_data = array('member_id'=>$sess_member_id);
		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_functions_path'=>member_functions_path());

		$data2 += array(
			'downline'=>$downline, 
			'message'=>$message,
			'status'=>$status,
			'level'=>$level,
			'code'=>$code,
			'search_last_name'=>$search_last_name,
			'search_first_name'=>$search_first_name,
			'membership_levels'=>$membership_levels,
			'membership_status'=>$membership_status,
			'page_count'=>$page_count,
			'rec_start'=>$rec_start,
			'rec_end'=>$rec_end,
			'page_num'=>$page_num,
			'totalFyc' => $totalFyc,
			'monthlyFyc'=>$monthlyFyc,
			'level_up_rqrmnts'=>$level_up_rqrmnts
			);

		$this->load->view('header', $data1);
		$this->load->view('/member/member_menu', $menu_data);		
		$this->load->view('/member/member_home', $data2);	
		$this->load->view('footer');
	}	

	public function send_login_credentials(){

		$config['mailtype'] = 'html';
		$this->email->initialize($config);		

		$email= $this->input->post('email');
		$member_info = $this->member_database->getMemberInfoByEmail($email);

		if(COUNT($member_info) > 0 && $email!=""){
			$tempo_password = "boost_temp_".time();
			$this->email->to($email);
			$this->email->from('support@boostfinancialteam.ca','boost financial team');
			$this->email->subject('boost financial team LOGIN / PASSWORD');
			$this->email->message("Your password has been reset. <br/> Once you have successfully login to boostfinancialteam webapp, please change your password immediately.<br/><br/>USERNAME: ".$member_info[0]->username." <br/> PASSWORD: ".$tempo_password."<br/><br/><br/>Sincerely,<br/>boostfinancialteam" );
			$this->email->send();
			
			$this->member_database->update_member_access($member_info[0]->id, array('password'=>md5($tempo_password)));
			echo "<script>alert('Your login credentials was sent to your email: ".$email."');</script>";			
		}
		else{
			echo "<script>alert('No record was found for ".$email."! Please contact boost financial admin');</script>";		
		}
		echo "<script>window.location='http://boostfinancialteam.ca';</script>";					
	}

	public function info($info_type="")
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');		

		if($sess_username=="" || $sess_member_id==""){
			redirect('/home/logout');
		}	

		$member_id = $sess_member_id;
		if($info_type=="downline")$member_id = $this->session->userdata('downline_member_id');

		$member_info = $this->member_database->get_member_info($member_id);
		$data2 = member_info_empty();

		if(count($member_info) != 0){
			$data2 = set_member_info($member_info);
		}
		$data2 += array('info_type'=>$info_type);

		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_id'=>$sess_member_id);		
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
			
		$this->load->view('header', $data1);
		if($sess_user_type=="2"){ //member
			$menu_data = array('member_id'=>$sess_member_id);
			$this->load->view('/member/member_menu', $menu_data);
			$data2 += array('back_loc'=>base_url()."member");
		}

		if($sess_user_type=="1"){ //admin
			$menu_data = array('username'=>$sess_username);
			$this->load->view('/admin/admin_menu', $menu_data);
			$data2 += array('back_loc'=>base_url()."admin/list");
		}

		$this->load->view('/member/member_info', $data2);
		$this->load->view('footer');
	}	

	public function heirarachy($id=""){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_member_id==""){
			//echo "<script>alert('Sorry your session has expired, please login again!);</script>";
			redirect('/home/logout');
		}

		if($id=="")
		{
			//member access
			$id = $sess_member_id;
		}
		else{
			//admin can view other member heirarchy
			//echo "<script>alert('Sorry but you can't access this page!);</script>";
			if($sess_user_type=="" || $sess_user_type!="1") redirect('/home/logout');
		}

		$data2 = array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());			
		$data2 += array('jstree_path'=>jstree_path());
		$data2 += array('jstree_style_path'=>jstree_style_path());

		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_id'=>$sess_member_id);		
		$this->load->view('header', $data1);

		if($sess_user_type=="2"){ //member
			$menu_data = array('member_id'=>$sess_member_id);
			$this->load->view('/member/member_menu', $menu_data);
			$data2 += array('back_loc'=>base_url()."member");
		}

		if($sess_user_type=="1"){ //admin
			$menu_data = array('username'=>$sess_username);
			$this->load->view('/admin/admin_menu', $menu_data);
			$data2 += array('back_loc'=>base_url()."admin/list");
		}
		
		//get downlines
		$member_info = $this->member_database->get_member_info($id);
		$downlines = $this->member_database->get_member_downlines($id);		

		$data2 += array('downlines'=>$downlines);		
		$data2 += array('member_id'=>$id);
		$data2 += array(
			'first_name'=> $member_info[0]->first_name,
			'last_name'=> $member_info[0]->last_name,
			'mid_name'=> $member_info[0]->mid_name,
		);		
		
		$this->load->view('/member/member_downlines', $data2);
		$this->load->view('footer');		
	}

	public function view_downline($member_tbl_id){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$upline_id = $this->session->userdata('member_table_id');

		if($sess_username=="" || $upline_id==""){
			redirect('/home/logout');
		}
		//if($this->member_database->isUpline($member_tbl_id, $upline_id)){
		//	$this->session->set_userdata('downline_member_id', $member_tbl_id);	
		//}
		//else{
		//	$this->session->set_userdata('downline_member_id', "0");
		//}
		$this->session->set_userdata('downline_member_id', $member_tbl_id);	
		redirect('/member/info/downline');
	}

	public function save_payment(){
		$sess_username = $this->session->userdata('username');
		$sess_user_type = $this->session->userdata('user_type');
		$sess_member_id = $this->session->userdata('member_table_id');

		if($sess_username!="" || $sess_user_type!=""){
			$member_id = $this->input->post('member_id');	
			$payment_id = $this->input->post('payment_id');	
			$member_info = $this->member_database->get_member_info($member_id);
			if(count($member_info) > 0){
				$this->member_database->update_member_info($member_id, array('payment_id'=>$payment_id));
				
				$first_name = $member_info[0]->first_name;
				$last_name = $member_info[0]->last_name;
				$email = $member_info[0]->email;

				//email sending codes here

				$message = "Membership fee for ".$first_name." ".$last_name." was succesfully submited and saved to our records.<br/>Payment details were also sent to this email address : ". $email;			
			}
			else{
				$message = "Unidentified Membership ID : ".$member_id.".<br/>Please contact administrator.";	
			}
		}	
		else{
			$message = "Membership fee for ".$first_name." ".$last_name." was not saved to our records. Payment Details was sent to the member's email address : ". $email;			
		}
		

		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_functions_path'=>member_functions_path());
		$menu_data = array('member_id'=>$sess_member_id);
		

		$data2 = array('payment_id'=>$payment_id, 'message'=>$message);

		$this->load->view('header', $data1);
		$this->load->view('/member/member_menu', $menu_data);		
		$this->load->view('/member/member_payment', $data2);	
		$this->load->view('footer');
	}		

	public function form($process="new"){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		$member_info = array('function_lib_path'=>function_lib_path());
		$member_info += array('jqueryui_path'=>jqueryui_path());
		$member_info += array('jqueryuicss_path'=>jqueryuicss_path());
		$member_info += array('member_functions_path'=>member_functions_path());		
		
		if($sess_username=="" || $sess_member_id==""){
			redirect('/home/logout');
		}

		//set member levels
		$membership_levels = array(''=>'');
		$db_membership_levels = $this->member_database->get_all_membership_level();
		foreach($db_membership_levels as $level) {
			$membership_levels[$level->level_tbl_id]=$level->membership_level;
		}					
		$member_info += array('membership_levels'=>$membership_levels);

		//get directors
		$directors = array(''=>'');
		$db_directors = $this->member_database->get_all_members("","","","5","1");
		foreach($db_directors as $dir) {
			$directors[$dir->id]=$dir->first_name." ".$dir->last_name;
		}			
		//sort($directors);		
		$member_info += array('directors'=>$directors);	

		$recruiters = array(''=>'');
		$db_managers = $this->member_database->get_all_members("","","","4","1");
		$db_supervisors = $this->member_database->get_all_members("","","","3","1");
		$db_associates = $this->member_database->get_all_members("","","","2","1");
		$db_directors = $this->member_database->get_all_members("","","","5","1");
		$db_recruiters = array_merge($db_associates,$db_directors);
		$db_recruiters = array_merge($db_recruiters,$db_managers);
		$db_recruiters = array_merge($db_recruiters,$db_supervisors);
		$db_recruiters = array_merge($db_recruiters,$db_directors);
		foreach($db_recruiters as $rec) {
			$recruiters[$rec->id]=$rec->first_name." ".$rec->last_name;
		}				
		//sort($recruiters);	
		$member_info += array('recruiters'=>$recruiters);				

		//set member info details
		if($process=="edit"){
			$db_member_info = $this->member_database->get_member_info($sess_member_id);
			if(count($db_member_info) != 0){
				$member_info += set_member_info($db_member_info);
			}
		}
		else{
			$member_info += member_info_empty();
		}

		//set provinces
		$member_info += array('province_list'=>get_provinces());
		
		$data1 = $this->session->userdata('script_links');
		$data1 += array('member_id'=>$sess_member_id);		

		$this->load->view('header', $data1);
		if($sess_user_type=="2"){
			$menu_data = array('member_id'=>$sess_member_id);
			$this->load->view('/member/member_menu', $menu_data); //if member	
			$member_info += array('back_loc'=>base_url()."member/");
		} 
		if($sess_user_type=="1"){
			$menu_data = array('username'=>$sess_username);
			$this->load->view('/admin/admin_menu', $menu_data); //if admin
			$member_info += array('back_loc'=>base_url()."admin/list");
		} 

		if($process=="new"){
			$member_info += array('form_action'=>base_url()."/member/addNewMember", 'back_btn_visibility'=>'none');
		}
		else if($process=="edit"){
			$member_info += array('form_action'=>base_url()."/member/saveMemberInfoChanges", 'back_btn_visibility'=>'block');
		}
		
		$member_info += array('process'=>$process, 'member_id'=>$sess_member_id);
		
		$this->load->view('/member/member_info_form', $member_info);	
		$this->load->view('footer');	
	}	

	public function addNewMember(){
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		$sess_username = $this->session->userdata('username');
		$process="Admin";
		$payment_id="0";
		$fname=$this->input->post('first_name');
		$lname=$this->input->post('last_name');		
		$dob=$this->input->post('dob');
		$maxid=$this->member_database->getMaxRowMember();
		$member_record_status="1";
		$code = $fname[0].$lname[0].date('m',strtotime($dob)).date('d',strtotime($dob)).date('y',strtotime($dob)).$maxid[0]->MaxId;
		
		if($sess_username=="" || $sess_member_id==""){
			redirect('/home/logout');
		}		

		if($sess_user_type=="2"){ //for non-admins
			$process="Payment";
			//insert payment details
		}

		if($sess_user_type=="1"){ //for admins
			$member_record_status="1";
			$redirect_page='/admin/list';
		}
		else{
			$member_record_status="2"; //for members
			$redirect_page='/member/';
		}

		if($sess_username!=""){
			$info = array(
				'first_name'=>$this->input->post('first_name'),
				'mid_name'=>$this->input->post('mid_name'),
				'last_name'=>$this->input->post('last_name'),
				'address'=>$this->input->post('address'),
				'email'=>$this->input->post('email'),
				'city'=>$this->input->post('city'),
				'postal'=>$this->input->post('postal'),				
				'province'=>$this->input->post('province'),
				'sin'=>$this->input->post('sin'),
				'dob'=>$this->input->post('dob'),
				'contact'=>$this->input->post('contact'),
				'upline_id'=>$this->input->post('recruiter'),
				'director_id'=>$this->input->post('director'),
				'is_plead_guilty '=>$this->input->post('guilty'),
				'is_bankrupt'=>$this->input->post('bankrupt'),
				'is_legal_to_work'=>$this->input->post('workpermit'),
				'level_id'=>$this->input->post('membership_level'),
				'code'=>$code,
				'status'=>$member_record_status,
				'reg_date'=>date("Y-m-d h:i:sa"),
				'membership_process'=>$process,
				'payment_id'=>$payment_id
			);

			if($this->input->post('guilty_exp')!=""){
				$guilty = array(
				'explanation'=>$this->input->post('guilty_exp')
				);	
			}	
			else{
				$guilty = NULL;
			}			

			if(
				$this->input->post('bankrupt_explanation')!="" ||
				$this->input->post('trustee_address')!="" ||
				$this->input->post('trustee_name')!="" ||
				$this->input->post('lob')!="" ||
				$this->input->post('aob')!="" ||
				$this->input->post('soa')!=""
				)
			{
				$trustee = array(
				'trustee_name'=>$this->input->post('trustee_name'),
				'trustee_address'=>$this->input->post('trustee_address'),
				'location_bankruptcy'=>$this->input->post('lob'),
				'assignment_bankruptcy'=>$this->input->post('aob'),
				'statement_of_affairs'=>$this->input->post('soa'),
				'explanation'=>$this->input->post('bankrupt_explanation')
				);
			}
			else{
				$trustee = NULL;
			}			
			$member_id = $this->member_database->insert_member_details($info, $guilty, $trustee);
			
			/*
			echo 
			"<script>
				alert('Approval was successful for ".$this->input->post('first_name')." ".$this->input->post('last_name')."');
				window.location.href='".base_url().$redirect_page."';
			</script>";	
			*/
			redirect($redirect_page);
		}
		else{
			redirect('/home/logout');
		}		
	}

	public function ifMemberExists(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_member_id!=""){
			$countExist = $this->member_database->countMemberExist($this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('dob'));
			if($countExist > 0){
				$arr = array('result' => 1); 
			}
			else{
				$arr = array('result' => 0);    
			}
		}	
		else{
			$arr = array('result' => 0);    
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );				
	}

	public function ifEmailExists(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		$email = $this->input->post('email');			
		if($sess_username!="" || $sess_member_id!=""){
			$countExist = $this->member_database->countEmailExist($email);
			if($countExist > 0){
				$arr = array('result' => 1); 
			}
			else{
				$arr = array('result' => 0);    
			}
		}	
		else{
			$arr = array('result' => 0);    
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );				
	}	

	public function deactivate_member($id){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_member_id!=""){		
			$member = array('status'=>3);
			$this->member_database->update_member_info($id, $member);
		}
		redirect('/admin/list');
	}

	public function activate_member($id){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_member_id!=""){		
			$member = array('status'=>1);
			$this->member_database->update_member_info($id, $member);
		}
		redirect('/admin/list');		
	}
	
	public function checkUplineConflict(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		$member_id = $this->input->post('member_id');
		$upline_id = $this->input->post('upline_id');
		
		if($sess_username!="" || $sess_member_id!=""){
			$downlines = $this->member_database->get_member_downlines($member_id);
			$arr = array('result' => 0); 
			foreach($downlines as $dl){
				if($dl->id == $upline_id){				
					$arr = array('result' => 1);					
				}
			}
		}	
		else{
			$arr = array('result' => 101);    
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );		
	}	

	public function saveMemberInfoChanges(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_member_id==""){
			redirect('/home/logout');
		}	

		if($sess_user_type=="1"){ //for admins
			$redirect_page='/admin/list';
		}
		else{
			$redirect_page='/member/info'; //for members
		}		

		if($sess_member_id!=""){
			$info = array(
				'first_name'=>$this->input->post('first_name'),
				'mid_name'=>$this->input->post('mid_name'),
				'last_name'=>$this->input->post('last_name'),
				'address'=>$this->input->post('address'),
				'email'=>$this->input->post('email'),
				'city'=>$this->input->post('city'),
				'postal'=>$this->input->post('postal'),				
				'province'=>$this->input->post('province'),
				'sin'=>$this->input->post('sin'),
				'dob'=>$this->input->post('dob'),
				'contact'=>$this->input->post('contact'),
				'upline_id'=>$this->input->post('recruiter'),
				'director_id'=>$this->input->post('director'),
				'is_plead_guilty '=>$this->input->post('guilty'),
				'is_bankrupt'=>$this->input->post('bankrupt'),
				'is_legal_to_work'=>$this->input->post('workpermit'),
				'level_id'=>$this->input->post('membership_level')
			);
			$this->member_database->update_member_info($sess_member_id, $info);

			if($this->input->post('guilty_exp')!=""){
				$guilty = array('explanation'=>$this->input->post('guilty_exp'));	
				$countRec = $this->member_database->countGuiltyRecords($sess_member_id);
				if($countRec > 0){
					$this->member_database->update_guilty_info($sess_member_id, $guilty);	
				}
				else{
					$guilty += array('member_id'=>$sess_member_id);
					$this->member_database->insert_member_explanation($guilty);		
				}
				
			}
	
			if(
				$this->input->post('bankrupt_explanation')!="" ||
				$this->input->post('trustee_address')!="" ||
				$this->input->post('trustee_name')!="" ||
				$this->input->post('lob')!="" ||
				$this->input->post('aob')!="" ||
				$this->input->post('soa')!=""
				)
			{
				$trustee = array(
				'trustee_name'=>$this->input->post('trustee_name'),
				'trustee_address'=>$this->input->post('trustee_address'),
				'location_bankruptcy'=>$this->input->post('lob'),
				'assignment_bankruptcy'=>$this->input->post('aob'),
				'statement_of_affairs'=>$this->input->post('soa'),
				'explanation'=>$this->input->post('bankrupt_explanation')
				);

				$countRec = $this->member_database->countTrusteeRecords($sess_member_id);
				if($countRec > 0){
					$this->member_database->update_trustee_info($sess_member_id, $trustee);	
				}
				else{
					$trustee += array('member_id'=>$sess_member_id);
					$this->member_database->insert_member_trustee($trustee);		
				}				
			}
			
			/*echo 
			"<script>
				alert('Approval was successful for ".$this->input->post('first_name')." ".$this->input->post('last_name')."');
				window.location.href='".base_url().$redirect_page."';
			</script>";	*/
			redirect($redirect_page);		
		}

	}
}