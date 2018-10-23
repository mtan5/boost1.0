<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('commission_helper'))
{   
    class commission_distribition{
        private $commission_factor = "200";
        private $remaining_commission = "";
        private $CI =""; 
        private $direct_commission = "1";
        private $override_commission = "2";
        private $trainee_commission = "3";
        private $trailing_commission = "4";
        
        function __construct($annual_premium) {
            $this->remaining_commission = $annual_premium * ($this->commission_factor / 100);
            $this->CI=& get_instance();
            $this->CI->load->model('member_database');
            $this->CI->load->model('products_database'); 
            $this->CI->load->model('policy_database'); 
            $this->CI->load->model('commission_database');                        
        }
        //seters - geters
        public function setRemaining_commission($remaining_commission) {
          $this->remaining_commission = $remaining_commission;
        }

        public function getRemaining_commission() {
          return $this->remaining_commission;
        }                 
        
        //methods
        public function agent_compute($agent_id, $trainee_id, $annual_premium, $company, $product, $policy_number, $monthly_prem, $trainee_commission_rate="25", $date_purchased, $policy_client, $hasOtherAgent=0){
            $uplineCtr = 0;

            $withTrainee=false;
            $agent_upline_list=null;
            //$boost_trainee = null;
            $director_list = "";

            
            //$trainee = $this->CI->member_database->get_member_info($trainee_id);
            if($agent_id=="")$agent_id = $trainee_id;
            $agent = $this->CI->member_database->get_member_info($agent_id);
            $upline = $this->CI->member_database->get_member_info($agent[0]->upline_id);

            if($trainee_id!="")$withTrainee = true;
            $agent_level = $this->CI->member_database->get_membership_level($agent[0]->level_id);
            $commission_factor = $agent_level[0]->commission_factor;            
            $initial_commission = $this->compute_initial_commission($annual_premium, $product[0]->fyc);
            $commission_type=$this->direct_commission;
            if($hasOtherAgent==1) {
                if($withTrainee){
                    if($agent[0]->level_id=="1"){ //if trainee
                        $initial_commission = $initial_commission * (($trainee_commission_rate)/100);
                        $commission_type=$this->trainee_commission;
                    }
                    else{
                        $initial_commission = $initial_commission * ((100 - $trainee_commission_rate)/100);    
                        $commission_type=$this->direct_commission;
                    }                    
                }
                else{
                    $initial_commission = $initial_commission / 2;
                    $commission_type=$this->direct_commission;
                }
            }

            $agent_commission_fyc = $initial_commission;
            $agent_commission_bonus = $initial_commission * ($commission_factor / 100);
            //if(count($trainee) > 0)$boost_trainee = $this->trainee_compute($initial_commission, $commission_factor, $trainee, $trainee_commission_rate);

            $boost_agent = array(
                    'id'=>$agent[0]->id,
                    'name'=>$agent[0]->first_name." ".$agent[0]->last_name,
                    'code'=>$agent[0]->code,
                    'level_id'=>$agent[0]->level_id,
                    'level'=>$agent[0]->membership_level,         
                    'commission_fyc'=> $agent_commission_fyc,
                    'commission_bonus'=> $agent_commission_bonus,
                    'commission_type'=> $commission_type
                    );     
            $agent_level_id = $agent[0]->level_id;
            $upline_level_id = "";

            if($product[0]->product_type_id!="3") //if not travel type product
            {
    	        if(COUNT($upline) > 0)$upline_level_id = $upline[0]->level_id;

                //upline_computation
                while($agent[0]->id!="" && $agent[0]->upline_id!=""){
                    if($agent[0]->level_id == 5) {
                        $director_list = $this->director_compute($agent, $agent_commission_fyc);
                        break;
                    }
                    
                    $agent = $this->CI->member_database->get_member_info($upline[0]->id);
                    $upline = $this->CI->member_database->get_member_info($agent[0]->upline_id);                                

                    if($agent_level_id < $upline_level_id){
                        $agent_level = $this->CI->member_database->get_membership_level($agent_level_id);
                        $upline_level = $this->CI->member_database->get_membership_level($upline_level_id);
                        $commission_factor = $upline_level[0]->commission_factor - $agent_level[0]->commission_factor;

                        //$agent_commission = $this->compute_upline_commission($initial_commission, $commission_factor);
                        $agent_commission_fyc = $agent_commission_fyc * ($commission_factor / 100);
                        $agent_commission_bonus = 0;

                        $agent_upline_list[$uplineCtr]=array(
                            'id'=>$agent[0]->id,
                            'name'=>$agent[0]->first_name." ".$agent[0]->last_name,
                            'level_id'=>$agent[0]->level_id,
                            'level'=>$agent[0]->membership_level,
                            'code'=>$agent[0]->code,
                            //'commission'=> $agent_commission        
                            'commission_fyc'=> $agent_commission_fyc,
                            'commission_bonus'=> $agent_commission_bonus,
                            'commission_type'=> $this->override_commission                           
                        );                
                    }
                    else{
                        $agent_upline_list[$uplineCtr]=array(
                            'id'=>$agent[0]->id,
                            'name'=>$agent[0]->first_name." ".$agent[0]->last_name,
                            'level_id'=>$agent[0]->level_id,
                            'level'=>$agent[0]->membership_level,
                            'code'=>$agent[0]->code,
                            //'commission'=> 0
                            'commission_fyc'=> 0,
                            'commission_bonus'=> 0,
                            'commission_type'=> $this->override_commission                         
                        );      
                    }

                    $agent_level_id = 0;
                    if(!empty($agent))$agent_level_id = $agent[0]->level_id;
                    $upline_level_id = 0;
                    if(!empty($upline))$upline_level_id = $upline[0]->level_id;

                    $uplineCtr++;
                    if($uplineCtr >= 3)break;
                }
            }

            $policy_details = array(
                'company_id'=>$company[0]->company_id,
                'company_name'=>$company[0]->name,
                'product_id'=>$product[0]->prod_id,
                'product_name'=>$product[0]->prod_name,
                'policy_number'=>$policy_number,
                'client'=>$policy_client,
                'monthly_prem'=> $monthly_prem,
                'annual_prem'=>$annual_premium,
                'curr_fyc'=>$product[0]->fyc,
                'date_purchased'=>$date_purchased
                );

            $commissions['policy_details'] = $policy_details;
            $commissions['agent'] = $boost_agent;
            //$commissions['trainee'] = $boost_trainee;
            $commissions['uplines'] = $agent_upline_list;
            $commissions['directors'] = $director_list;            

            return $commissions;
        }

        public function compute_initial_commission($annual_premium, $fyc){
            $initial = $annual_premium * ($fyc/100);
            return $initial;
        }

        public function compute_agent_commission($initial_commission, $commission_factor, $withTrainee, $trainee_commission_rate="0"){      
            $commission = $initial_commission + $initial_commission * ($commission_factor / 100);
            if($withTrainee) {
                $commission = $commission * round((100 - $trainee_commission_rate) / 100, 2);   
            }  
            $this->remaining_commission = $this->remaining_commission - $commission;      
            return $commission;
        }

        public function compute_upline_commission($initial_commission, $commission_factor){      
            $commission = $initial_commission * ($commission_factor / 100);  
            $this->remaining_commission = $this->remaining_commission - $commission;      
            return $commission;
        }        

        public function trainee_compute($initial_commission, $commission_factor, $trainee, $commission_rate="25"){
            $commission = $initial_commission + $initial_commission * ($commission_factor / 100);
            $commission = $commission * (round($commission_rate / 100, 2));

            $trainee_commission = array(
                    'id'=>$trainee[0]->id,
                    'name'=>$trainee[0]->first_name." ".$trainee[0]->last_name,
                    'code'=>$trainee[0]->code,
                    'level_id'=>$trainee[0]->level_id,
                    'level'=>$trainee[0]->membership_level,         
                    'commission'=> $commission
                    );
            $this->remaining_commission = $this->remaining_commission - $commission;
            return $trainee_commission;
        }  

        public function director_compute($director, $agent_commission){
            $director_list = NULL;
            $upline1 = NULL; 
            $upline2 = NULL;            
            if($director[0]->upline_id!="" && $director[0]->upline_id=="5"){
                $upline1 = $this->CI->member_database->get_member_info($agent[0]->upline_id);
                $commission_amt = round($agent_commission * 0.09, 2); //9%
                $this->remaining_commission = $this->remaining_commission - $commission_amt;
                $director_list[] = array();
                $director_list[0] = array(
                    'id'=>$upline1->id,
                    'name'=>$upline1->first_name." ".$upline1->last_name,
                    'code'=>$upline1->code,
                    'level_id'=>$upline1->level_id,
                    'level'=>$upline1->membership_level,         
                    'commission_fyc'=> $commission_amt,
                    'commission_bonus'=>0,
                    'commission_type'=> $this->override_commission  
                    );
            }

            if($upline1!=NULL){
                if($upline1[0]->upline_id!="" && $upline1[0]->upline_id=="5"){
                    $upline2 = $this->CI->member_database->get_member_info($upline2[0]->upline_id);
                    $commission_amt = round($agent_commission * 0.05, 2); //9%
                    $this->remaining_commission = $this->remaining_commission - $commission_amt;
                    $director_list[1] = array(
                        'id'=>$upline2->id,
                        'name'=>$upline2->first_name." ".$upline1->last_name,
                        'code'=>$upline2->code,
                        'level_id'=>$upline2->level_id,
                        'level'=>$upline2->membership_level,         
                        'commission_fyc'=> $commission_amt,
                        'commission_bonus'=>0,
                        'commission_type'=> $this->override_commission  
                        );          
                }
            }

            if($upline2!=NULL){
                if($upline2[0]->upline_id!="" && $upline2[0]->upline_id=="5"){
                    $upline3 = $this->CI->member_database->get_member_info($upline3[0]->upline_id);
                    $commission_amt = rount($agent_commission * 0.03, 2); //9%
                    $this->remaining_commission = $this->remaining_commission - $commission_amt;
                    $director_list[2] = array(
                        'id'=>$upline3->id,
                        'name'=>$upline3->first_name." ".$upline1->last_name,
                        'code'=>$upline3->code,
                        'level_id'=>$upline3->level_id,
                        'level'=>$upline3->membership_level,         
                        'commission_fyc'=> $commission_amt,
                        'commission_bonus'=>0,
                        'commission_type'=> $this->override_commission  
                        ); 
                }
            }
            return $director_list;
        }    
    }

    function saveCommission($commission_arr, $savePolicy=true){
        $CI=& get_instance();
        $CI->load->model('policy_database');
        $CI->load->model('products_database'); 
        $CI->load->model('commission_database');  

        if($commission_arr!=NULL){  
            $policy = NULL;  
            $policy_id="";             
            foreach ($commission_arr as $commission) { 
                $monthly_prem = round($commission['policy_details']['monthly_prem'], 2);
                $annual_prem = round($commission['policy_details']['annual_prem'], 2);
                $policy_number = $commission['policy_details']['policy_number'];
                $client = $commission['policy_details']['client'];
                $company_id = $commission['policy_details']['company_id'];
                $product_id = $commission['policy_details']['product_id'];
                $date_purchased = $commission['policy_details']['date_purchased'];
                $curr_fyc = $commission['policy_details']['curr_fyc'];            	
                $policy_sold = NULL;                

            	if($savePolicy){
                    //create policy details instance                
                    if($policy==NULL){
                        $policy = array(
                            'policy_number'=>$policy_number,
                            'client'=>$client,
                            'date_purchased'=>$date_purchased
                            );   
                    }   
                    else{
                        //get policy id
                        $policy_db = $CI->products_database->get_product_sold("", $policy_number, $company_id, "", "", "", "", "");
                        $policy_id=$policy_db[0]->policy_id;
                    }                                   

                    $policy_sold = array(
                        'company_id'=>$company_id,
                        'product_id'=>$product_id,
                        'annual_prem'=>$annual_prem,
                        'monthly_prem'=>$monthly_prem,
                        'curr_fyc'=>$curr_fyc,
                        'status'=>1,
                        );
                }
                else{
                    //get policy id
                    $policy_db = $CI->products_database->get_product_sold("", $policy_number, $company_id, "", "", "", "", "");
                    $policy_id=$policy_db[0]->policy_id;
                }

                if($policy_number==""){
                    return "ERROR: Policy Number has no value!";
                }
                //else{
                    //$countRec = $CI->policy_database->countPolicyRec($policy_number);
                    //if($countRec > 0){
                        //return "ERROR: Policy Number already exists!";       
                    //}
                //}                       

                $member_id = $commission['agent']['id'];
                $member_level_id = $commission['agent']['level_id'];
                $commission_fyc = $commission['agent']['commission_fyc'];
                $commission_bonus = $commission['agent']['commission_bonus'];
                $commission_type_id = $commission['agent']['commission_type'];
                $agent = array(
                    'product_id'=>$product_id,
                    'member_id'=>$member_id,
                    'member_level_id'=>$member_level_id,
                    'commission_fyc'=>$commission_fyc,
                    'commission_amt_bonus'=>$commission_bonus,
                    'date_charged'=>date("Y-m-d"),                    
                    'commission_type_id'=>$commission_type_id,
                    'status'=>1,                    
                    );

                $upline_ctr = 0;
                $db_uplines = NULL;
                if($commission['uplines'] != NULL){
                    foreach ($commission['uplines'] as $upline) {
                        $member_id = $upline['id'];
                        $member_level_id = $upline['level_id'];
                        $commission_fyc = $upline['commission_fyc'];
                        $commission_bonus = $upline['commission_bonus'];
                        $commission_type_id = $upline['commission_type'];

                        $db_uplines[$upline_ctr] = array(
                        'product_id'=>$product_id,
                        'member_id'=>$member_id,
                        'member_level_id'=>$member_level_id,
                        'commission_fyc'=>$commission_fyc,
                        'commission_amt_bonus'=>$commission_bonus,
                        'date_charged'=>date("Y-m-d"),                    
                        'commission_type_id'=>$commission_type_id,
                        'status'=>1,                    
                        );
                        $upline_ctr++;              
                    }
                }

                $director_ctr = 0;
                $db_directors = NULL;
                if($commission['directors'] != NULL){
                    foreach ($commission['directors'] as $director) {
                        $member_id = $director['id'];
                        $member_level_id = $director['level_id'];
                        $commission_amt = $director['commission'];
                        $commission_fyc = $upline['commission_fyc'];
                        $commission_bonus = $upline['commission_bonus'];
                        $commission_type_id = $upline['commission_type'];                        

                        $db_directors[$director_ctr] = array(
                        'product_id'=>$product_id,
                        'member_id'=>$member_id,
                        'member_level_id'=>$member_level_id,
                        'commission_fyc'=>$commission_fyc,
                        'commission_amt_bonus'=>$commission_bonus,
                        'date_charged'=>date("Y-m-d"),                    
                        'commission_type_id'=>$commission_type_id,
                        'status'=>1,                    
                        );
                        $director_ctr++;
                    }  
                }
                $CI->commission_database->insert_commission($policy, $policy_sold, $agent, $db_uplines, $db_directors, $policy_id);                     
            }            
        }
        else{
            return "ERROR: Sorry! Saving Commission Failed, Please contact administrator.";
        }
    }    
}