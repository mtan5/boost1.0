<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<script src="<?php echo $commission_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container-fluid">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="col-sm-12">
			<h2>Commission List</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/commission/list'; ?>" id="search_commission_form">
		<div class='row'>
			<input type="hidden" name="page_number" id="page_number" value=""/>
			<div class="col-sm-12">			
				<div class="col-sm-3">Company: <?php echo form_dropdown("search_company", $company_list, $search_company,"class='form-control' id='search_company'"); ?></div>			
				<div class="col-sm-3">Product: <?php echo form_dropdown("search_product", $product_list, $search_product,"class='form-control' id='search_product'"); ?></div>
				<div class="col-sm-3">Status:  <?php echo form_dropdown("search_status", $commission_status, $search_status,"class='form-control' id='search_status'"); ?></div>									
				<!--<div class="col-sm-3" style="display:<?php echo $showMemberFilter; ?>;">Policy:  <?php echo form_dropdown("search_policy", $policy_list, $search_policy,"class='form-control' id='search_policy'"); ?></div>-->
				<div class="col-sm-3" style="display:<?php echo $showMemberFilter; ?>;">Policy: <input type="text" name="search_policy" id="search_policy" class="form-control" value="<?php echo $search_policy; ?>"> </div>
			</div>
		</div>
		<br/>
		<div class='row'>
			<div class="col-sm-12">			
				<div class="col-sm-3">Commission Type:  <?php echo form_dropdown("search_commission_type", $commission_types, $search_commission_type,"class='form-control' id='search_commission_type'"); ?></div>													
				<div class="col-sm-3">Date Charged (Start:)<input type="text" name="start_date_charged" id="start_date_charged" class="form-control datepicker" value="<?php echo $start_date_charged; ?>"></div>
				<div class="col-sm-3">Date Charged (End:)<input type="text" name="end_date_charged" id="end_date_charged" class="form-control datepicker" value="<?php echo $end_date_charged; ?>"></div>													
				<div class="col-sm-3" style="display:<?php echo $showMemberFilter; ?>;">Member: <?php echo form_dropdown("search_member", $member_list, $search_member,"class='form-control' id='member_list'"); ?></div>
			</div>
		</div>

		<div class='row'>
			<div class="col-sm-12">			
				<div class="col-sm-9"></div>
				<div class="col-sm-3"><br/>&nbsp;
					<div class="btn-group pull-right">					
						<button type="button" class="btn btn-primary" id="AddCommissionBtn" data-toggle='modal' data-target='#AddCommission'>ADD NEW</button>
						<button type="button" class="btn btn-primary" id="show_all_btn" onclick="window.location.href='<?php echo base_url(); ?>/commission/list/all'">SHOW ALL</button>						
						<button type="submit" class="btn btn-primary" id="">SEARCH</button>						
					</div>					
				</div>
			</div>
		</div>				
 		</form>
				
		<br/>		
		<div class="row">
			<div class="col-sm-8">	
				<ul class="pagination">
				<?php
					for($x=1; $x<=$page_count; $x++){
						$active="";
						if($page_num == $x)$active="active";
						echo "<li class='".$active."'><a href='#' class='next_page'>".$x."</a></li>";
					}
				?>
				</ul> 					
			</div>			
			<div class="col-sm-4" style="display:<?php echo $showMemberFilter; ?>;"><br/>
				<div class="col-sm-9">
					<form method="post" action="<?php echo base_url().'/commission/bulk_change_status'; ?>" id="change_commission_status_form">
						<input type="hidden" name="selected_ids" id="selected_ids" value="">
						<?php echo form_dropdown("comm_status", $commission_status, '',"class='form-control' id='comm_status'"); ?>
					</form>
				</div>

				<div class="col-sm-3"><button type='button' class='btn btn-success btn' value='' id="bulk_change">GO</button></div>
			</div>
		</div>
		<table class="table table-striped">
			<thead>
			  <tr>
			  	<th><input type='checkbox' class='' id="select_chkbx" /></th>
			    <th>&nbsp;</th>
			    <th>Member</th>
			    <th>Level</th>
			    <th>Company</th>
			    <th>Policy</th>
			    <th>Product</th>
			    <th>Product Type</th>
			    <th>Commission Type</th>
			    <th>FYC Commission</th>
			    <th>Bonus</th>
			    <th>Over-all Commission</th>
			    <th>Date Charged</th>
			    <th>Status</th>
			  </tr>
			</thead>
			<tbody>
				<?php
				$total = 0;				
				if(count($commission_list) > 0){
					$cntr = 0;			
					foreach($commission_list as $comm){	
						$cntr++;
						$style="";
						if($comm->status==3)$style="style='color:red;'";
						$commission = 0;
						if($cntr >= $rec_start && $cntr <= $rec_end){
							$commission = $comm->commission_fyc + $comm->commission_amt_bonus;	
							echo "
								<tr>
									<td>
										<input type='checkbox' class='commission_checkbox' value='".$comm->comm_id."'/>
									</td>
									<td>
										<button type='button' class='btn btn-default btn-xs edit_commission' value='"
										.$comm->comm_id."|"
										.$comm->first_name."|"
										.$comm->last_name."|"
										.$comm->membership_level."|"
										.$comm->name."|"
										.$comm->policy_number."|"
										.$comm->prod_name."|"
										.$comm->date_charged."|"
										.$comm->commission_fyc."|"										
										.$comm->status."|"
										.$comm->commission_amt_bonus."' data-toggle='modal' data-target='#CommissionModal'
										style='display:".$showMemberFilter.";'
										>EDIT</button>
									</td>
									<td>".$comm->first_name." ".$comm->last_name."</td>
									<td>".$comm->membership_level."</td>
									<td>".$comm->name."</td>
									<td>
										<button type='button' class='btn btn-info btn-xs view_policy_details' value='"
											.$comm->policy_number."|"
											.$comm->date_purchased."|"
											.$comm->name."|"
											.$comm->prod_name."|"
											.$comm->annual_prem."|"
											.$comm->monthly_prem."|"
										."' data-toggle='modal' data-target='#PolicyModal'>".$comm->policy_number."</button></td>
									<td>".$comm->prod_name."</td>
									<td>".$comm->product_type."</td>
									<td>".$comm->commission_type."</td>
									<td><span ".$style.">$".$comm->commission_fyc."</span></td>
									<td><span ".$style.">$".$comm->commission_amt_bonus."</span></td>
									<td><span ".$style.">$".$commission."</span></td>
									<td>".$comm->date_charged."</td>									
									<td><span ".$style.">".$comm->commission_status."</span></td>	
								</tr>
							";
						}
						//if($comm->status!="3") $total = $total + $comm->commission_amt;
						$total = $total + ($commission);
					}
				}
				?>
			</tbody>
		</table>
		<h4><?php echo $message; ?></h4>
		<div class="panel panel-default">
			<div class="panel-heading">TOTAL:&nbsp;<span style="font-size:30px;"><?php echo "$".number_format($total, 2); ?></span></div>
		</div>
	</div>		
</div>

<!-- Modal add new-->
<div class="modal fade" id="CommissionModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Edit Commission</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      	<input type="hidden" id="commission_id" value="">
	      	<table class="table table-striped">
	      		<tr>
	      			<td><b>POLICY : </b></td>
	      			<td><span id="policy_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>ADVISOR : </b></td>
	      			<td><span id="agent_name_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>LEVEL : </b></td>
	      			<td><span id="agent_level_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>COMPANY : </b></td>
	      			<td><span id="company_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>PRODUCT : </b></td>
	      			<td><span id="product_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>DATE CHARGED : </b></td>
	      			<td><span id="date_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>FYC COMMISSION : </b></td>
	      			<td><input tpye="text" class="form-control" id="comm_fyc" /></td>
	      		</tr>
	      		<tr>
	      			<td><b>BONUS : </b></td>
	      			<td><input tpye="text" class="form-control" id="comm_bonus" /></td>
	      		</tr>	      		
	      		<tr>
					<td><b>Status: </b></td>
					<td><?php echo form_dropdown("edit_status", $commission_status, "","class='form-control' id='edit_status'"); ?></td>
	      		</tr>
	      	</table>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	    	<button type='button' class='btn btn-info' value='' id="save_commission_change">SAVE</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<div class="modal fade" id="PolicyModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Policy Details</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      	<input type="hidden" id="commission_id" value="">
	      	<table class="table table-striped">
	      		<tr>
	      			<td style='text-align:right;'><b>POLICY : </b></td>
	      			<td style='text-align:left; width:70%;'><span id="view_policy_number" /></span></td>
	      		</tr>
	      		<tr>
	      			<td style='text-align:right;'><b>DATE PURCHASED : </b></td>
	      			<td><span id="view_date_purchased" /></td>
	      		</tr>
	      		<tr>
	      			<td style='text-align:right;'><b>COMPANY : </b></td>
	      			<td><span id="view_company_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td style='text-align:right;'><b>PRODUCT : </b></td>
	      			<td><span id="view_product_label"></span></td>
	      		</tr>
	      		<tr>
	      			<td style='text-align:right;'><b>ANNUAL PREMIUM : </b></td>
	      			<td><span id="view_annual_prem"></td>
	      		</tr>
	      		<tr>
	      			<td style='text-align:right;'><b>MONTHLY PREMIUM : </b></td>
	      			<td><span id="view_monthly_prem"></td>
	      		</tr>	      		
	      	</table>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<div class="modal fade" id="AddCommission" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Add Commission</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      	<form method="post" action="<?php echo base_url().'/commission/add'; ?>" id="add_commission_form">
	      	<table class="table table-striped">
	      		<tr>
	      			<td><b>POLICY : </b></td>
	      			<td><?php echo form_dropdown("new_policy", $policy_list, "","class='form-control' id='new_policy'"); ?></td>
	      		</tr>
	      		<tr>
	      			<td><b>ADVISOR : </b></td>
	      			<td><?php echo form_dropdown("new_member", $member_list, $search_member,"class='form-control' id='new_member'"); ?></td>
	      		</tr>
	      		<tr>
	      			<td><b>FYC COMMISSION : </b></td>
	      			<td><input tpye="text" class="form-control" id="new_comm_fyc" name="new_comm_fyc"/></td>
	      		</tr>
	      		<tr>
	      			<td><b>BONUS : </b></td>
	      			<td><input tpye="text" class="form-control" id="new_comm_bonus" name="new_comm_bonus" /></td>
	      		</tr>	      		
	      		<tr>
					<td><b>Type: </b></td>
					<td><?php echo form_dropdown("new_commission_type", $commission_types, "","class='form-control' id='new_commission_type'"); ?></td>
	      		</tr>
	      		<tr>
					<td><b>Status: </b></td>
					<td><?php echo form_dropdown("new_commission_status", $commission_status, "","class='form-control' id='new_commission_status'"); ?></td>
	      		</tr>
	      	</table>
	      </form>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="SaveNewCommissionBtn">Add</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

&nbsp;
<br/>
&nbsp;
<br/>
&nbsp;
<br/>