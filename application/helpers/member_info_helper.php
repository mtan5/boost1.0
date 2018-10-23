<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('member_info_helper'))
{
    function member_info_empty()
    {
        return array(
                'first_name'=> "",
                'last_name'=> "",
                'mid_name'=> "",
                'sin'=> "",
                'dob'=> "",
                'address'=> "",
                'city'=> "",
                'province'=> "",
                'postal'=> "",
                'contact'=> "",
                'email'=> "",
                'tbl_trustee_display'=>"none",
                'guilty_exp_txtarea'=>'disabled',
                'upline_id'=>"",
                'recruiter'=>"",
                'director_id'=>"",
                'director'=>"",
                'is_plead_guilty'=>"",
                'is_bankrupt'=>"",
                'is_legal_to_work'=>"",
                'level_id'=>"",
                'membership_level'=>"",
                'code'=> "",
                'status'=> "",
                'record_status'=> "",
                'reg_date'=> "",
                'membership_process'=> "",
                'payment_id'=> "",
                'trustee_name'=> "",
                'trustee_address'=> "",
                'location_bankruptcy'=> "",
                'assignment_bankruptcy'=> "",
                'statement_of_affairs'=> "",
                'guilty_explanation'=> "",
                'bankrupt_explanation'=> "", 
            );  
    }         

    function set_member_info($member_info){

        $tbl_trustee_display="none";
        $guilty_exp_txtarea="disabled";
        if($member_info[0]->is_bankrupt=="1"){
            $tbl_trustee_display="block";
        }
        if($member_info[0]->is_plead_guilty=="1"){
            $guilty_exp_txtarea="";
        }        

        return array(
                'id'=> $member_info[0]->id,
                'first_name'=> $member_info[0]->first_name,
                'last_name'=> $member_info[0]->last_name,
                'mid_name'=> $member_info[0]->mid_name,
                'sin'=> $member_info[0]->sin,
                'dob'=> $member_info[0]->dob,
                'address'=> $member_info[0]->address,
                'city'=> $member_info[0]->city,
                'province'=> $member_info[0]->province,
                'postal'=> $member_info[0]->postal,
                'contact'=> $member_info[0]->contact,
                'email'=> $member_info[0]->email,
                'upline_id'=>$member_info[0]->upline_id,
                'recruiter'=>$member_info[0]->recruiter,
                'director_id'=> $member_info[0]->director_id,
                'director'=>$member_info[0]->director,
                'is_plead_guilty'=> $member_info[0]->is_plead_guilty,
                'is_bankrupt'=> $member_info[0]->is_bankrupt,
                'is_legal_to_work'=> $member_info[0]->is_legal_to_work,
                'level_id'=> $member_info[0]->level_id,
                'membership_level'=> $member_info[0]->membership_level,
                'code'=> $member_info[0]->code,
                'status'=> $member_info[0]->status,
                'record_status'=> $member_info[0]->record_status,
                'reg_date'=> $member_info[0]->reg_date,
                'membership_process'=> $member_info[0]->membership_process,
                'payment_id'=> $member_info[0]->payment_id,
                'trustee_name'=> $member_info[0]->trustee_name,
                'trustee_address'=> $member_info[0]->trustee_address,
                'location_bankruptcy'=> $member_info[0]->location_bankruptcy,
                'assignment_bankruptcy'=> $member_info[0]->assignment_bankruptcy,
                'statement_of_affairs'=> $member_info[0]->statement_of_affairs,
                'guilty_explanation'=> $member_info[0]->explanation,
                'bankrupt_explanation'=> $member_info[0]->bankrupt_explanation,
                'tbl_trustee_display'=>$tbl_trustee_display,
                'guilty_exp_txtarea'=>$guilty_exp_txtarea
            );
    }    

    function get_provinces(){
        $province["Alberta"]="Alberta";
        $province["British Columbia"]="British Columbia";
        $province["Manitoba"]="Manitoba";
        $province["New Brunswick"]="New Brunswick";
        $province["New Foundland and Labrador"]="New Foundland and Labrador";
        $province["Nova Scotia"]="Nova Scotia";
        $province["Ontario"]="Ontario";
        $province["Prince Edward Island"]="Prince Edward Island";
        $province["Quebec"]="Quebec";

        return $province;
    }                
}
