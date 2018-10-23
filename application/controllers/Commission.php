<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('products_database'); 
		$this->load->model('report_database'); 
		$this->load->model('company_database'); 
		$this->load->model('member_database'); 
		$this->load->model('policy_database'); 
		$this->load->model('commission_database'); 
		$this->load->helper('form');
		$this->load->helper('script_path_helper');
		$this->load->helper('commission_helper');
		$this->load->helper("file");
		$this->load->library('session');

		//load href links of bootstrap and user defined css and JS
		$this->session->set_userdata('script_links', get_default_script_libraries());							
	}

	function compute_insurance(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		$this->session->set_userdata('commission_arr1', "");
		$this->session->set_userdata('commission_arr2', "");
		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}
		$data1 = $this->session->userdata('script_links');
		$data1 += array('function_lib_path'=>function_lib_path());
		$menu_data = array('username'=>$sess_username);

		$agent_id1=$this->input->post('agent_id_1'); 
		$agent_id2=$this->input->post('agent_id_2');
		//$trainee_id1=$this->input->post('trainee_id_1');
		$trainee_id2=$this->input->post('trainee_id_2');
		//$trainee1_commission_rate = $this->input->post('trainee1_rate');
		$trainee2_commission_rate = $this->input->post('trainee2_rate');
		$company_id=$this->input->post('select_companies');
		$policy_number = $this->input->post('policy_number');
		$policy_client = $this->input->post('policy_client');
		$selected_products = $this->input->post('selected_products'); 
		$date_purchased = $this->input->post('date_purchased');
		$commission_arr1 = array();
		$commission_arr2 = array();

		$company_details = $this->company_database->get_company($company_id);
		if($selected_products!=""){
			$selected = explode(",",$selected_products);
			foreach ($selected as $value) {
				$annual_premium = 0;
				$pieces = explode("=",$value);
				$product_id = $pieces[0];				
				$annual_premium = $pieces[1];
				$monthly_premium = 0;
				$hasOtherAgent = 0;
				if($annual_premium!=0 && $annual_premium!="") $monthly_premium = round($annual_premium / 12, 2);
				if($agent_id2!="" || $trainee_id2!="") $hasOtherAgent = 1;

				$product_details = $this->products_database->get_product($product_id,"","");
				$distribution = new commission_distribition($annual_premium);
				
				//agent 1
				if($agent_id1!=""){
					$commission = $distribution->agent_compute($agent_id1, $trainee_id2, $annual_premium, $company_details, $product_details, $policy_number, $monthly_premium, $trainee2_commission_rate, $date_purchased, $policy_client, $hasOtherAgent);
					if(is_null($commission_arr1)){
						$commission_arr1=$commission;
					}
					else{
						array_push($commission_arr1,$commission);
					}
				}

				//agent2
				if($agent_id2!="" || $trainee_id2!=""){	
					$commission = $distribution->agent_compute($agent_id2, $trainee_id2, $annual_premium, $company_details, $product_details, $policy_number, $monthly_premium, $trainee2_commission_rate, $date_purchased, $policy_client, $hasOtherAgent);
					if(is_null($commission_arr2)){
						$commission_arr2=$commission;
					}
					else{
						array_push($commission_arr2,$commission);
					}											
				}
			}
		}
		$data = array('commission_arr1'=>$commission_arr1);
		$data += array('commission_arr2'=>$commission_arr2);

		if($commission_arr1!=NULL)$this->session->set_userdata('commission_arr1', $commission_arr1);
		if($commission_arr2!=NULL)$this->session->set_userdata('commission_arr2', $commission_arr2);

		$this->load->view('header', $data1);
		//$this->load->view('admin_menu', $menu_data);
		$this->load->view('/commission/insurance_commission_distribution', $data);
		$this->load->view('footer');
	}

	function save_insurance(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}

		$commission_arr1 = $this->session->userdata('commission_arr1');
		$commission_arr2 = $this->session->userdata('commission_arr2');

		if($commission_arr1==NULL && $commission_arr2==NULL){
			echo "
			<script>
				alert('Commission session expired !Unable to save commission details');				
			</script>
			";
		}
		else{				
			if($commission_arr1!=NULL) {
				saveCommission($commission_arr1, true);		
				$this->session->set_userdata('commission_arr1', "");
			}

			if($commission_arr2!=NULL) {						
				saveCommission($commission_arr2, false);
				$this->session->set_userdata('commission_arr2', "");
			}

			echo "
			<script>
				alert('Saving Commission Successful!');
			</script>
			";			

			echo "
			<script>		
				//window.location.href = '".base_url()."commission/list'
				window.close();
			</script>
			";
		}
	}

	public function insurance_form(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}
		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		$data2 = array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('commission_functions_path'=>commission_functions_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());		

		//members
		$db_directors = $this->member_database->get_all_members("","","","5","1");		
		$db_managers = $this->member_database->get_all_members("","","","4","1");
		$db_supervisors = $this->member_database->get_all_members("","","","3","1");
		$db_associates = $this->member_database->get_all_members("","","","2","1");
		$members = array_merge($db_associates,$db_directors);
		$members = array_merge($members,$db_managers);
		$members = array_merge($members,$db_supervisors);
		$form_action = base_url().'/commission/compute_insurance';
		//trainees	
		$trainees = $this->member_database->get_all_members("","","","1","1");
		
		//companies	
		$db_companies = $this->company_database->get_company();
		$companies[0]="";
		foreach($db_companies as $rec) {
			$companies[$rec->company_id]=$rec->name;
		}

		$data2 += array(
			'members'=>$members,
			'trainees'=>$trainees,
			'companies'=>$companies
			);

		$data2 += array('form_action'=>$form_action);
		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);
		$this->load->view('/commission/insurance_commission_form', $data2);
		$this->load->view('footer');				
	}

	public function get_policy_commission(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type==""){
			$commission_list=NULL;
		}	
		else{
			$policy_id = $this->input->post('policy_id');
			$product_id = $this->input->post('product_id');			
			$commission_list = $this->commission_database->get_commission("", $product_id, "", "" , "", "", "", $policy_id, "");				
		}		

		header('Content-Type: application/json');
		echo json_encode( $commission_list );			
	}

	public function add(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		$page_divider = 15;

		if($sess_username=="" || $sess_user_type==""){
			redirect('/home/logout');
		}	
		else{		
			$input_member = $this->input->post('new_member');
			$input_policy = $this->input->post('new_policy');
			$input_comm_bonus = $this->input->post('new_comm_bonus');
			$input_comm_fyc = $this->input->post('new_comm_fyc');
			$input_commission_status = $this->input->post('new_commission_status');
			$input_commission_type = $this->input->post('new_commission_type');	

			$policy_details = $this->policy_database->get_policy($input_policy, "","","");
			$product_id = $policy_details[0]->product_id;

			$member_details = $this->member_database->get_member_info($input_member);
			$member_level_id = $member_details[0]->level_id;

			$commission_details = array(
				'policy_id'=>$input_policy,
				'member_id'=>$input_member,
				'product_id'=>$product_id,
				'member_level_id'=>$member_level_id,
				'commission_fyc'=>$input_comm_fyc,
				'commission_amt_bonus'=>$input_comm_bonus,
				'date_charged'=>date('Y-m-d'),
				'commission_type_id'=>$input_commission_type,
				'status'=>$input_commission_status
				);
			$this->commission_database->insert_commission_record($commission_details);
			redirect('/commission/list');
		}		

	}

	public function bulk_change_status(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');		
		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}		

		$selected_ids = $this->input->post('selected_ids');	
		$comm_status  = $this->input->post('comm_status');	
		$ids = explode(',',$selected_ids);
		if(COUNT($ids) > 0){
			foreach ($ids as $comm_id) {
				$this->commission_database->update_commission(
					$comm_id, 
					array('date_modified' => date('Y-m-d'), 
					'status'=>$this->input->post('comm_status')));	
			}

			echo "
			<script>
				alert('Commission Status successfully changed!');				
			</script>
			";					
		}
		else{
			echo "
			<script>
				alert('No commission were selected!');				
			</script>
			";					
		}

		redirect('/commission/list');
	}

	public function list()
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		$page_divider = 15;

		if($sess_username=="" || $sess_user_type==""){
			redirect('/home/logout');
		}		

		$search_company = $this->input->post('search_company');
		$search_product = $this->input->post('search_product');
		$search_policy = $this->input->post('search_policy');
		$search_status = $this->input->post('search_status');
		$search_commission_type = $this->input->post('search_commission_type');
		$start_date_charged = $this->input->post('start_date_charged');
		$end_date_charged = $this->input->post('end_date_charged');
		$page_num = $this->input->post('page_number');
		if($page_num=="") $page_num = 1;

		$search_member = "";
		$showMemberFilter = "none";
		if($sess_user_type=="1") {
			$search_member = $this->input->post('search_member');
			$showMemberFilter = "block";
		}
		if($sess_user_type=="2"){
			$search_member = $sess_member_id;	
		} 

		$message = "";

		$commission_list = $this->commission_database->get_commission($search_company, $search_product, $search_member,$start_date_charged ,$end_date_charged, $search_policy, $search_status, "",  $search_commission_type );		
		if(count($commission_list) == 0)$message = "No records found...";
		
		$page_count = ceil((count($commission_list) / $page_divider));

		if($page_num=="all"){
			$page_start = 1;
			$page_end = "";			
			$rec_start = 1;
			$rec_end = count($commission_list);
		}
		else{
			$page_start = $page_num - 1;
			$page_end = $page_num;			
			$rec_start = $page_start * $page_divider + 1;
			$rec_end = $page_end * $page_divider;						
		}

		$company_list['']='';
		$db_company_list = $this->company_database->get_company("","","","1");
		foreach($db_company_list as $rec) {
			$company_list[$rec->company_id]=$rec->name;
		}

		$commission_status['']='';
		$db_commission_status = $this->commission_database->get_commission_status();
		foreach($db_commission_status as $rec) {
			$commission_status[$rec->commission_status_id]=$rec->commission_status;
		}	

		$commission_types['']='';
		$db_commission_types = $this->commission_database->get_commission_types();
		foreach($db_commission_types as $rec) {
			$commission_types[$rec->id]=$rec->commission_type;
		}			

		$policy_list['']='';
		$db_policy_list = $this->policy_database->get_policy("","");
		foreach($db_policy_list as $rec) {
			$policy_list[$rec->policy_id]=$rec->policy_number;
		}		

		$product_list ['']='';		
		$db_product_list = $this->products_database->get_product("","","","");
		foreach($db_product_list as $rec) {
			$product_list [$rec->prod_id]=$rec->prod_name;
		}				
		
		$member_list = array(''=>'');
		$db_directors = $this->member_database->get_all_members("","","","5","1");
		$db_managers = $this->member_database->get_all_members("","","","4","1");
		$db_supervisors = $this->member_database->get_all_members("","","","3","1");
		$db_associates = $this->member_database->get_all_members("","","","2","1");
		$db_trainees = $this->member_database->get_all_members("","","","1","1");
		$db_recruiters = array_merge($db_associates,$db_directors);
		$db_recruiters = array_merge($db_recruiters,$db_managers);
		$db_recruiters = array_merge($db_recruiters,$db_supervisors);
		$db_recruiters = array_merge($db_recruiters,$db_trainees);
		foreach($db_recruiters as $rec) {
			$member_list[$rec->id]=$rec->first_name." ".$rec->last_name;
		}			

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data2 = array('message' => $message );
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
		$data2 += array('commission_functions_path'=>commission_functions_path());			
		
		$data2 += array('product_list'=>$product_list);
		$data2 += array('company_list'=>$company_list);
		$data2 += array('member_list'=>$member_list);
		$data2 += array('policy_list'=>$policy_list);
		$data2 += array('commission_types'=>$commission_types);
		$data2 += array('showMemberFilter'=>$showMemberFilter);
		
		$data2 += array(
		'search_company'=>$search_company,
		'search_product'=>$search_product,
		'search_member'=>$search_member,
		'search_status'=>$search_status,
		'search_commission_type'=>$search_commission_type,
		'start_date_charged'=>$start_date_charged,
		'end_date_charged'=>$end_date_charged,
		'search_policy' => $search_policy,
		'page_count'=>$page_count,
		'rec_start'=>$rec_start,
		'rec_end'=>$rec_end,
		'page_num'=>$page_num,
		'commission_list'=>$commission_list,
		'commission_status'=>$commission_status);

		$this->load->view('header', $data1);
		if($sess_user_type=="1")$this->load->view('/admin/admin_menu', $menu_data);
		if($sess_user_type=="2")$this->load->view('/member/member_menu', $menu_data);
		$this->load->view('/commission/commission_list', $data2);
		$this->load->view('footer');	
	}

	public function saveCommissionAmountChange(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_member_id!=""){
			$this->commission_database->update_commission($this->input->post('commission_id'), array('commission_fyc'=>$this->input->post('comm_fyc'), 'commission_amt_bonus'=>$this->input->post('comm_bonus'), 'date_modified' => date('Y-m-d'), 'status'=>$this->input->post('commission_status')));
			$arr = array('result' => 1); 		
		}	
		else{
			$arr = array('result' => 101);    
		}	

	   //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );				
	}	

	public function delete_commission_statement($id){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type=="")redirect('/home/logout');
		
		$statement_list = $this->report_database->get_commission_statement("","" ,"", "", $id);
		unlink(FCPATH."documents/commission_statements/".$statement_list[0]->file_name);	
		$this->report_database->delete_commission_statement($id);
		redirect('/commission/released_statements');
	}		

	public function released_statements(){

		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');
		
		if($sess_username=="" || $sess_user_type=="")redirect('/home/logout');

		$search_member = $this->input->post('search_member');
		if($sess_user_type=="2") $search_member = $sess_member_id; //if member
		$start_date_created = $this->input->post('start_date_created');
		$end_date_created = $this->input->post('end_date_created');

		$member_list = array(''=>'');
		$db_directors = $this->member_database->get_all_members("","","","5","1");
		$db_managers = $this->member_database->get_all_members("","","","4","1");
		$db_supervisors = $this->member_database->get_all_members("","","","3","1");
		$db_associates = $this->member_database->get_all_members("","","","2","1");
		$db_recruiters = array_merge($db_associates,$db_directors);
		$db_recruiters = array_merge($db_recruiters,$db_managers);
		$db_recruiters = array_merge($db_recruiters,$db_supervisors);
		foreach($db_recruiters as $rec) {
			$member_list[$rec->id]=$rec->first_name." ".$rec->last_name;
		}	

		$statement_list = $this->report_database->get_commission_statement($search_member,$start_date_created ,$end_date_created, "", "");		

		$message = "";
		if(Count($statement_list) ==0) $message = "No Records Found..";

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data2 = array('message' => $message );
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
		$data2 += array('statement_list'=>$statement_list);
		$data2 += array('member_list'=>$member_list);
		$data2 += array('message'=>$message);
		$data2 += array('search_member'=>$search_member);
		$data2 += array('start_date_created'=>$start_date_created);
		$data2 += array('end_date_created'=>$end_date_created);

		$this->load->view('header', $data1);
		
		if($sess_user_type=="2"){
			$this->load->view('/member/member_menu', $menu_data);
			$this->load->view('/commission/member_released_statements', $data2);
		} 
		else{
			$this->load->view('/admin/admin_menu', $menu_data);
			$this->load->view('/commission/released_statements', $data2);			
		}		
		$this->load->view('footer');			
	}				
}