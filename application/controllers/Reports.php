<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('products_database');
		$this->load->model('report_database'); 		
		$this->load->model('company_database'); 
		$this->load->model('member_database'); 
		$this->load->model('commission_database'); 
		$this->load->helper('form');
		$this->load->helper('script_path_helper');
		$this->load->library('session');

		//load href links of bootstrap and user defined css and JS
		$this->session->set_userdata('script_links', get_default_script_libraries());							
	}

	public function commission_statement()
	{
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type=="")redirect('/home/logout');				

		$start_date_charged = $this->input->post('start_date_charged');
		$end_date_charged = $this->input->post('end_date_charged');
		$search_member = $this->input->post('search_member');
		$commission_view = $this->input->post('commission_view');
		$search_member_access = "";
		if($sess_user_type=="2") {
			$search_member=$sess_member_id; //if currently log in is a member account
			$search_member_access = "disabled";
		}
		
		$commission_list = NULL;
		$product_list = NULL;
		if($search_member!=""){
			$commission_list = $this->commission_database->get_commission("", "", $search_member,$start_date_charged ,$end_date_charged, "", "2");	
			$product_list = $this->commission_database->get_policy_sold($search_member, $start_date_charged, $end_date_charged);
		} 
		$message = "";				
		if(count($commission_list) == 0)$message = "No records found...";
		
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

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data2 = array('message' => $message );
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
		$data2 += array('report_functions_path'=>report_functions_path());
		
		$data2 += array(
		'search_member'=>$search_member,
		'start_date_charged'=>$start_date_charged,
		'end_date_charged'=>$end_date_charged,
		'search_member_access' => $search_member_access,
		'commission_list'=>$commission_list,
		'product_list'=>$product_list,
		'member_list'=>$member_list,
		'commission_view'=>$commission_view);

		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);
		$this->load->view('/reports/commission_statement', $data2);
		$this->load->view('footer');	
	}	

	public function downline_report(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type=="")redirect('/home/logout');		

		$start_date_charged = $this->input->post('start_date_charged');
		$end_date_charged = $this->input->post('end_date_charged');
		$search_member = $this->input->post('search_member');
		$downline_level = $this->input->post('downline_level');

		$search_member_access="";
		if($sess_user_type=="2") {
			$search_member=$sess_member_id; //if currently log in is a member account
			$search_member_access = "disabled";
		}		

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

		$commission_list = NULL;
		$product_list = NULL;
		$advisor_list = NULL;
		if($search_member!=""){
			$commission_list = $this->member_database->get_member_downline_sales($search_member,$start_date_charged ,$end_date_charged, $downline_level);	
			//$product_list = $this->commission_database->get_policy_sold($search_member, $start_date_charged, $end_date_charged);
	
			if(count($commission_list) != 0) {
				$advisor_list = array(array('id'=>$commission_list[0]->id, 'name'=>$commission_list[0]->first_name." ".$commission_list[0]->last_name, 'level'=>$commission_list[0]->membership_level, 'upline'=>$commission_list[0]->upline_name));
				$prev_advisor = $commission_list[0]->id;
				foreach($commission_list as $comm){				
					if($comm->id != $prev_advisor){
						if(!in_array($comm->id, array_column($advisor_list, 'id'))){
							array_push($advisor_list, array('id'=>$comm->id, 'name'=>$comm->first_name." ".$comm->last_name, 'level'=>$comm->membership_level, 'upline'=>$comm->upline_name));
							$prev_advisor = $comm->id;
						}
					}
				}
			}
		} 

		$message = "";				
		if(count($commission_list) == 0)$message = "No records found...";			

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data2 = array('message' => $message );
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
		$data2 += array('report_functions_path'=>report_functions_path());				
		
		$data2 += array(
		'search_member'=>$search_member,
		'start_date_charged'=>$start_date_charged,
		'end_date_charged'=>$end_date_charged,
		'search_member_access' => $search_member_access,
		'commission_list'=>$commission_list,
		'advisor_list'=>$advisor_list,
		//'product_list'=>$product_list,
		'downline_level'=>$downline_level,
		'member_list'=>$member_list);

		$this->load->view('header', $data1);
		if($sess_user_type=="2"){
			$this->load->view('/member/member_menu', $menu_data);			
		} 
		else{		
			$this->load->view('/admin/admin_menu', $menu_data);
		}
		$this->load->view('/reports/downline_report', $data2);
		$this->load->view('footer');			
	}

	//PDF GENERATOR

	public function commission_statement_pdf(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type==""){
			redirect('/home/logout');	
		}
		else{	
			$start_date_charged = $this->input->post('start_date_charged');
			$end_date_charged = $this->input->post('end_date_charged');
			$search_member = $this->input->post('search_member');
			$commission_view = $this->input->post('commission_view');
			
			$member_details = $this->member_database->get_member_info($search_member);
			if(count($member_details)>0){
				$member = $member_details[0]->first_name." ".$member_details[0]->mid_name." ".$member_details[0]->last_name;
				$level = $member_details[0]->membership_level;				
			}
			else{
				$member="";
				$level="";
			}


			if($sess_user_type=="2") {
				$search_member=$sess_member_id; //if currently log in is a member account
			}
		
			$commission_list = NULL;
			$product_list = NULL;
			if($search_member!=""){
				$commission_list = $this->commission_database->get_commission("", "", $search_member,$start_date_charged ,$end_date_charged, "", "2");	
				$product_list = $this->commission_database->get_policy_sold($search_member, $start_date_charged, $end_date_charged);
			} 

			$message = "";				
			if(count($commission_list) == 0)$message = "No records found...";
					
			$data1 = $this->session->userdata('script_links');
			$data2 = array('message' => $message );
			$data2 += array('admin_functions_path'=>admin_functions_path());
			$data2 += array('function_lib_path'=>function_lib_path());
			$data2 += array('jqueryui_path'=>jqueryui_path());
			$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
			$data2 += array('commission_functions_path'=>commission_functions_path());					
			
			$data2 += array(
			'member'=>$member,
			'level'=>$level,
			'start_date_charged'=>$start_date_charged,
			'end_date_charged'=>$end_date_charged,
			'commission_list'=>$commission_list,
			'product_list'=>$product_list,
			'commission_view'=>$commission_view);
       		//$this->load->view('/reports/commission_statement_pdf', $data2);

	        //$pdfFilePath = "output_pdf_name.pdf";	 
	        //$this->load->library('m_pdf');	
	        //$this->m_pdf->pdf->WriteHTML("Sample COntent");	
	        //$this->m_pdf->pdf->Output($pdfFilePath, "D");    


			if($end_date_charged=="")$end_date_charged = "000";
			if($start_date_charged=="")$start_date_charged = "000";
			//$pdfFilePath ="CommissionStatement_".$member."_".$start_date_charged."_".$end_date_charged."_".time().".pdf";	
			$pdfFilePath ="CommissionStatement_".$member."_".$start_date_charged."_".$end_date_charged.".pdf";	
	        $this->load->library('m_pdf');	
			$header = $this->load->view('header', $data1, true);
			$body = $this->load->view('/reports/commission_statement_pdf', $data2, true);

	        $this->m_pdf->pdf->WriteHTML($header,1);
	        $this->m_pdf->pdf->WriteHTML($body);

	        $this->report_database->insert_commission_statement(array(
	        	'member_id'=>$search_member,
	        	'file_name'=>$pdfFilePath,
	        	'statement_period'=>$start_date_charged." to ".$end_date_charged 
	        	));

	        $this->m_pdf->pdf->Output(FCPATH."documents/commission_statements/".$pdfFilePath, "F");
			$this->m_pdf->pdf->Output($pdfFilePath, "D");			
		}
	}

	public function downline_report_pdf(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_user_type=="")redirect('/home/logout');		

		$start_date_charged = $this->input->post('start_date_charged');
		$end_date_charged = $this->input->post('end_date_charged');
		$search_member = $this->input->post('search_member');
		$downline_level = $this->input->post('downline_level');

		$search_member_access="";
		if($sess_user_type=="2") {
			$search_member=$sess_member_id; //if currently log in is a member account
			$search_member_access = "disabled";
		}		

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

		$member_details = $this->member_database->get_member_info($search_member);
		if(count($member_details)>0){
			$member = $member_details[0]->first_name." ".$member_details[0]->mid_name." ".$member_details[0]->last_name;
			$level = $member_details[0]->membership_level;				
		}
		else{
			$member="";
			$level="";
		}		

		$commission_list = NULL;
		$product_list = NULL;
		$advisor_list = NULL;
		if($search_member!=""){
			$commission_list = $this->member_database->get_member_downline_sales($search_member,$start_date_charged ,$end_date_charged, $downline_level);	
			//$product_list = $this->commission_database->get_policy_sold($search_member, $start_date_charged, $end_date_charged);
	
			if(count($commission_list) != 0) {
				$advisor_list = array(array('id'=>$commission_list[0]->id, 'name'=>$commission_list[0]->first_name." ".$commission_list[0]->last_name, 'level'=>$commission_list[0]->membership_level, 'upline'=>$commission_list[0]->upline_name));
				$prev_advisor = $commission_list[0]->id;
				foreach($commission_list as $comm){				
					if($comm->id != $prev_advisor){
						if(!in_array($comm->id, array_column($advisor_list, 'id'))){
							array_push($advisor_list, array('id'=>$comm->id, 'name'=>$comm->first_name." ".$comm->last_name, 'level'=>$comm->membership_level, 'upline'=>$comm->upline_name));
							$prev_advisor = $comm->id;
						}
					}
				}
			}
		} 

		$message = "";				
		if(count($commission_list) == 0)$message = "No records found...";			

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data2 = array('message' => $message );
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());	
						
		
		$data2 += array(
		'search_member'=>$search_member,
		'start_date_charged'=>$start_date_charged,
		'end_date_charged'=>$end_date_charged,
		'search_member_access' => $search_member_access,
		'commission_list'=>$commission_list,
		'advisor_list'=>$advisor_list,
		//'product_list'=>$product_list,
		'level'=>$level,
		'member'=>$member);

		//$this->load->view('header', $data1);
		//$this->load->view('/reports/downline_report_pdf', $data2);		

		if($end_date_charged=="")$end_date_charged = "000";
		if($start_date_charged=="")$start_date_charged = "000";
		$pdfFilePath ="DownlineReport_".$member."_".$start_date_charged."_".$end_date_charged."_".time().".pdf";			
        $this->load->library('m_pdf');	
		$header = $this->load->view('header', $data1, true);
		$body = $this->load->view('/reports/downline_report_pdf', $data2, true);

        $this->m_pdf->pdf->WriteHTML($header,1);
        $this->m_pdf->pdf->WriteHTML($body);

		$this->m_pdf->pdf->Output($pdfFilePath, "D");		
	}	
}