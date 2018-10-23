<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//Load the Model here   
		$this->load->model('products_database'); 
		$this->load->model('policy_database'); 
		$this->load->model('company_database'); 
		$this->load->model('member_database'); 
		$this->load->model('commission_database'); 
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

		$search_company_id = $this->input->post('search_company_id');
		$search_product_name = $this->input->post('search_product_name');
		$search_status = $this->input->post('search_status');
		$search_prod_type = $this->input->post('search_prod_type');
		$page_num = $this->input->post('page_number');
		if($page_num=="") $page_num = 1;

		if($sess_username=="" || $sess_user_type!="1"){
			redirect('/home/logout');
		}		

		$message = "";
		$company_list['']='';
		$prod_types['']='';
		$page_divider = 15;
		$product_list = $this->products_database->get_product("",$search_product_name, $search_company_id, $search_status, $search_prod_type);
		
		if (count($product_list) == 0) $message = "No records found";
		$db_company_list = $this->company_database->get_company("","","","1");
		foreach($db_company_list as $rec) {
			$company_list[$rec->company_id]=$rec->name;
		}

		$db_prod_types = $this->products_database->get_product_types();
		foreach($db_prod_types as $rec) {
			$prod_types[$rec->product_type_id]=$rec->product_type;
		}		

		$page_count = ceil((count($product_list) / $page_divider));
		if($page_num=="all"){
			$page_start = 1;
			$page_end = "";			
			$rec_start = 1;
			$rec_end = count($product_list);
		}
		else{
			$page_start = $page_num - 1;
			$page_end = $page_num;			
			$rec_start = $page_start * $page_divider + 1;
			$rec_end = $page_end * $page_divider;						
		}		

		$data1 = $this->session->userdata('script_links');
		$menu_data = array('username'=>$sess_username);
		$data2 = array('message' => $message);
		$data2 += array('product_functions_path'=>product_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());				
		$data2 += array('product_list'=>$product_list);
		$data2 += array('company_list'=>$company_list);
		$data2 += array('prod_types'=>$prod_types);
		$data2 += array('search_company_id'=>$search_company_id);
		$data2 += array('search_status'=>$search_status);		
		$data2 += array('search_product_name'=>$search_product_name);	
		$data2 += array('search_prod_type'=>$search_prod_type);	
		$data2 += array(
		'page_count'=>$page_count,
		'rec_start'=>$rec_start,
		'rec_end'=>$rec_end,
		'page_num'=>$page_num
		);	
		
		$this->load->view('header', $data1);
		$this->load->view('/admin/admin_menu', $menu_data);
		$this->load->view('/product/product_list', $data2);
		$this->load->view('footer');	
	}	

	public function sold($get_member_id=""){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username=="" || $sess_member_id==""){
			//echo "<script>alert('Sorry your session has expired, please login again!);</script>";
			redirect('/home/logout');
		}

		$member_id="";
		if($get_member_id=="" && $sess_user_type!="1"){
			redirect('/home/logout');
		}
		else{
			$this->session->set_userdata('sales_member_id', $get_member_id);
			$member_id = $this->session->userdata('sales_member_id');
		}


		//input post variables
		$search_company = $this->input->post('search_company');
		$search_product = $this->input->post('search_product');		
		$search_prod_type = $this->input->post('search_prod_type');
		$search_status = $this->input->post('search_status');
		$search_policy_id = "";
		$search_policy_number = $this->input->post('search_policy_number');
		$search_date_purchased_start = $this->input->post('search_date_purchased_start');
		$search_date_purchased_end = $this->input->post('search_date_purchased_end');

		$company_list['']='';
		$prod_list['']='';
		$prod_types['']='';

		$db_prod_types = $this->products_database->get_product_types();
		foreach($db_prod_types as $rec) {
			$prod_types[$rec->product_type_id]=$rec->product_type;
		}	

		$db_company_list = $this->company_database->get_company("","","","1");
		foreach($db_company_list as $rec) {
			$company_list[$rec->company_id]=$rec->name;
		}	

		$db_product_list = $this->products_database->get_product("","", "", "1", "");
		foreach($db_product_list as $rec) {
			$prod_list[$rec->prod_id]=$rec->prod_name;
		}

		$product_sold = $this->products_database->get_product_sold(
			$search_policy_id,
			$search_policy_number,
			$search_company,
			$search_product,
			$search_date_purchased_start,
			$search_date_purchased_end,
			$search_status,
			$member_id);

		$message = "";
		$showAdminBtns = "none";
		$member_name_label = "";
		if(COUNT($product_sold) == 0) $message = "No Records Found!";
		if($sess_user_type=="1")$showAdminBtns = "block";
		if($member_id!=""){
			$member_info = $this->member_database->get_member_info($member_id);
			$member_name_label = $member_info[0]->first_name." ".$member_info[0]->last_name."'s sales";
		}

		$menu_data = array('username'=>$sess_username, 'member_id'=>$sess_member_id);
		$data1 = $this->session->userdata('script_links');
		$data2 = array(
			'product_sold'=>$product_sold,
			'prod_types'=>$prod_types,
			'company_list'=>$company_list,
			'prod_list'=>$prod_list,
			'search_company'=>$search_company,
			'search_product'=>$search_product,
			'search_prod_type'=>$search_prod_type,
			'search_policy_number'=>$search_policy_number,
			'search_date_purchased_start'=>$search_date_purchased_start,
			'search_date_purchased_end'=>$search_date_purchased_end,
			'message'=>$message,
			'member_id'=>$member_id,
			'showAdminBtns'=>$showAdminBtns,
			'member_name'=>$member_name_label
			);
		$data2 += array('admin_functions_path'=>admin_functions_path());
		$data2 += array('function_lib_path'=>function_lib_path());
		$data2 += array('jqueryui_path'=>jqueryui_path());
		$data2 += array('jqueryuicss_path'=>jqueryuicss_path());
		$data2 += array('product_functions_path'=>product_functions_path());

		$this->load->view('header', $data1);
		if($sess_user_type=="1")$this->load->view('/admin/admin_menu', $menu_data);
		if($sess_user_type=="2")$this->load->view('/member/member_menu', $menu_data);
		$this->load->view('/product/product_sold', $data2);
		$this->load->view('footer');			
	}

	public function addNew(){
		$product_name = $this->input->post('product_name');
		$company_id = $this->input->post('company_id');
		$fyc = $this->input->post('fyc');	
		$prod_type = $this->input->post('prod_type');	

		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_user_type=="1"){
			$get_prod = $this->products_database->get_product("",$company_id,$product_name,"1");
			if(count($get_prod) > 0){
				$arr = array('result' => 2); //an active product already exists
			}
			else{
				$details=array(
					'company_id'=>$company_id,
					'prod_name'=>$product_name,
					'fyc'=>$fyc,
					'prod_type'=>$prod_type,
					'status'=>"1"
					);
				$this->products_database->insert_product($details);
				$arr = array('result' => 1); //success
			}			
		}
		else{
			$arr = array('result' => 3); //session expired
		}
	    //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );
	    exit;			
	}

	public function save(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_user_type=="1"){
			$product_name = $this->input->post('product_name');
			$product_id = $this->input->post('product_id');
			$company_id = $this->input->post('company_id');
			$fyc = $this->input->post('fyc');
			$status = $this->input->post('status');
			$prod_type = $this->input->post('prod_type');

			$details = array(
				'prod_name'=>$product_name,
				'company_id'=>$company_id,
				'fyc'=>$fyc,
				'prod_type'=>$prod_type,
				'status'=>$status
				);

			$this->products_database->update_product($product_id,$details);
			$arr = array('result' => 1); 			
		}
		else{
			$arr = array('result' => 3); 
		}
	    //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );
	    exit;			
	}

	public function delete($prod_id="0"){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		if($sess_username!="" || $sess_user_type=="1"){
			$details = array('status'=>0);
			$this->products_database->update_product($prod_id, $details);
			echo "
			<script>
				alert('Product was succesfully removed!');				
			</script>
			";				
			redirect('/product/list');
		}
		else{
			redirect('/home/logout');
		}			
	}	

	public function get_products(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');	
		if($sess_username!="" || $sess_member_id!=""){
			$company_id = $this->input->post('company_id');		
			$products = $this->products_database->get_product("", "", $company_id);
			if($products > 0){
				$arr = array('result' => $products); 
			}
			else{
				$arr = null;    
			}
		}	
		else{
			$arr = null;    		
		}	

	   //add the header here
	   header('Content-Type: application/json');
	   echo json_encode( $arr );				
	}	

	public function savePolicyDetailsChanges(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');	
		if($sess_username!="" || $sess_user_type=="1"){
			$policy_number = $this->input->post('policy_number');	
			$date_purchased = $this->input->post('date_purchased');	
			$edit_policy_id = $this->input->post('edit_policy_id');	
			$client = $this->input->post('client');	
			$this->products_database->update_policy($edit_policy_id,array('policy_number'=>$policy_number,'date_purchased'=>$date_purchased,'client'=>$client));
			$arr = array('result' => 1); 
		}	
		else{
			$arr = null;    		
		}	

	   //add the header here
	   header('Content-Type: application/json');
	   echo json_encode( $arr );		
	}	

	public function cancel_policy_product(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');	
		if($sess_username!="" || $sess_user_type=="1"){			
			//cancel policy			
			$cancel_policy_sale_id = $this->input->post('cancel_policy_sale_id');
			$cancel_prod_id = $this->input->post('cancel_prod_id');
			$charge_backs = $this->input->post('charge_backs');
			$cancel_policy_id = $this->input->post('cancel_policy_id');

			//$charge_backs = "8|3|75|1;2|2|45|1";
			$this->products_database->update_policy_sold($cancel_policy_sale_id,array('status'=>'0'));

			//process charge backs
			$commissions = null;
			$counter=0;
			
			if($charge_backs!=""){
				$details = explode(';', $charge_backs);
				foreach ($details as $row) {
					$row_details = explode('|', $row);
					$commissions[$counter] = array(
						'policy_id'=>$cancel_policy_id,
						'product_id'=>$cancel_prod_id,
						'member_id'=>$row_details[0],
						'member_level_id'=>$row_details[1],
						'commission_fyc'=>$row_details[2],
						'commission_amt_bonus'=>0,
						'date_charged'=>date("Y-m-d"),
						'commission_type_id'=>$row_details[3],
						'status'=>3
					);	
					$counter++;
				}
				$this->commission_database->insert_commission_records($commissions);				
			}

			$arr = array('result' => 1); 
		}	
		else{
			$arr = null;    		
		}	

	   //add the header here
	   header('Content-Type: application/json');
	   echo json_encode( $arr );		
	}

	public function delete_policy(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');	
		if($sess_username!="" || $sess_user_type=="1"){			
			$policy_id = $this->input->post('policy_id');
			$this->products_database->delete_policy_sold($policy_id);
			$arr = array('result' => 1); 
		}	
		else{
			$arr = null;    		
		}	

	   //add the header here
	   header('Content-Type: application/json');
	   echo json_encode( $arr );		
	}

	public function ifPolicyNumberExists(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');

		$arr = array('result' => 1); //POLICY NUMBER ALREADY EXISTS
		if($sess_username!="" || $sess_user_type=="1"){
			$policy_number = $this->session->userdata('policy_number');
			$policy_count = $this->policy_database->count_policy_rec($policy_number);
			if($policy_count == 0){
				$arr = array('result' => 0); //POLICY NUMBER DOESN'T EXISTS
			}
		}

	    //add the header here
	    header('Content-Type: application/json');
	    echo json_encode( $arr );
	    exit;			
	}

	public function save_new_policy(){
		$sess_username = $this->session->userdata('username');
		$sess_member_id = $this->session->userdata('member_table_id');
		$sess_user_type = $this->session->userdata('user_type');	
		
		if($sess_username!="" || $sess_user_type=="1"){		
			$policy_number = $this->input->post('policy_number');
			$date_purchased = $this->input->post('date_purchased');
			$client = $this->input->post('policy_client');			
			$company_id = $this->input->post('select_companies');
			$selected_products = $this->input->post('selected_products');

			$policy_count = $this->policy_database->count_policy_rec($policy_number);

			if($policy_count == 0){
				if($selected_products!=""){
					$policy_id = $this->policy_database->insert_policy(array(
						'policy_number'=>$policy_number,
						'date_purchased'=>$date_purchased,
						'client'=>$client
						));

					$selected = explode(",",$selected_products);
					foreach ($selected as $value) {
						$annual_premium = 0;
						$pieces = explode("=",$value);
						$product_id = $pieces[0];				
						$annual_premium = $pieces[1];
						$monthly_premium = 0;
						if($annual_premium!=0 && $annual_premium!="") $monthly_premium = round($annual_premium / 12, 2);
						$product_details = $this->products_database->get_product($product_id,"","","1","");

						$this->policy_database->insert_policy_sold(array(
						'policy_id'=>$policy_id,
						'company_id'=>$company_id,
						'product_id'=>$product_id,
						'annual_prem'=>$annual_premium,
						'monthly_prem'=>$monthly_premium,
						'curr_fyc'=>$product_details[0]->fyc,
						'status'=>1
						));						
					}
				}
				echo "<script>alert('New POLICY: ".$policy_number." has been added!);</script>";				
			}			
			else{
				echo "<script>alert('POLICY: ".$policy_number." already exists!);</script>";
			}
			redirect('/commission/insurance_form');
		}	
		else{
			redirect('/home/logout');    		
		}		
	}			
}
