<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<script src="<?php echo $product_functions_path; ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container-fluid">	
	<div class="row" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="col-sm-12">
			<h2>Products Sales</h2>
		</div>
		<form method="post" action="<?php echo base_url().'product/sold/'.$member_id; ?>" id="">
		<div class="col-sm-12">			
			<div class="col-sm-2">Date Purchased From:<input type="text" name="search_date_purchased_start" id="search_date_purchased_start" class="form-control datepicker" value="<?php echo $search_date_purchased_start; ?>"></div>
			<div class="col-sm-2">Date Purchased To:<input type="text" name="search_date_purchased_end" id="search_date_purchased_end" class="form-control datepicker" value="<?php echo $search_date_purchased_end; ?>"></div>									
			<div class="col-sm-2">Company: <?php echo form_dropdown("search_company", $company_list, $search_company,"class='form-control' id='search_company'"); ?></div>			
			<div class="col-sm-2">Product: <?php echo form_dropdown("search_product", $prod_list, $search_product,"class='form-control' id='search_product'"); ?></div>
			<div class="col-sm-2">Policy Number:  <input type="text" name="search_policy_number" value="<?php echo $search_policy_number ?>" class='form-control'></div>		
			<div class="col-sm-2">
			Status:
			<select name='search_status' class='form-control'>
				<?php
					$active = "";
					$removed = "";					
					if($search_status=="1"){
						$active = "selected";
						$removed = "";
					}
					else if($search_status=="0"){
						$active = "";
						$removed = "selected";							
					}
				?>
				<option value=''></option>
				<option value='1' <?php echo $active; ?>>Active</option>
				<option value='0' <?php echo $removed; ?>>Removed</option>
			</select>
			</div>
		</div>
		<br/>&nbsp;
		<div class="col-sm-12">
			<div class="btn-group pull-right">					
				<!--<button type="button" class="btn btn-primary" id="show_all_btn" onclick="window.location.href='<?php echo base_url(); ?>/commission/list/all'">SHOW ALL</button>-->
				<button type="submit" class="btn btn-primary" id="">SEARCH</button>
			</div>
		</div>			
 		</form>
 		<!--
		<div class="col-sm-12">	
			<ul class="pagination">
			<?php
				for($x=1; $x<=$page_count; $x++){
					$active="";
					if($page_num == $x)$active="active";
					echo "<li class='".$active."'><a href='".base_url()."/commission/list/".$x."'>".$x."</a></li>";
				}
			?>
			</ul> 					
		</div>
		-->	
		<br/>	
		<div class="col-sm-12"><h4><?php echo $member_name; ?></h4></div>
		<table class="table table-striped">
			<thead>
			  <tr>
			    <th>&nbsp;</th>
			    <th>Product</th>
			    <th>Company</th>
			    <th>Policy Number</th>
			    <th>Product Type</th>
			    <th>Date Purchased</th>
			    <th>Annual Premium</th>
			    <th>Monthly Premium</th>
			    <th>FYC</th>
			    <th>Status</th>
			  </tr>
			</thead>
			<tbody>
				<?php
				if(count($product_sold) > 0){
					$cntr = 0;
					foreach($product_sold as $sold){	
						if($sold->status=="0"){
							$color="#b2b4b7";
							$access = "disabled";
							$label_status = "Cancelled";
						}
						else{
							$color = "#000000";
							$access = "";
							$label_status = "Active";
						}						
						//$cntr++;
						//if($cntr >= $rec_start && $cntr <= $rec_end){
						echo "
							<tr>
								<td>
								<div class='btn-group'>
									<button type='button' class='btn btn-default btn-xs edit_policy_details' 
									value='".$sold->policy_id."|".$sold->policy_number."|".$sold->date_purchased."|".$sold->company_name."|".$sold->prod_name."|".$sold->annual_prem."|".$sold->monthly_prem."|".$sold->client."'
									style='display:".$showAdminBtns.";' 
									data-toggle='modal' data-target='#EditPolicyDetails'>EDIT</button>
									<button type='button' class='btn btn-warning btn-xs get_policy_commissions' value='".$sold->policy_id."|".$sold->policy_number."|".$sold->date_purchased."|".$sold->company_name."|".$sold->prod_name."|".$sold->annual_prem."|".$sold->monthly_prem."|".$sold->client."|".$sold->product_id."|".$sold->policy_sale_id."'
									 data-toggle='modal' data-target='#PolicyCommissions' ".$access.">CANCEL</button>
									<button type='button' class='btn btn-danger btn-xs delete_policy' value='".$sold->policy_id."' style='display:".$showAdminBtns.";' >DELETE</button>																		
								</div>
								</td>
								<td>".$sold->prod_name."</td>
								<td>".$sold->company_name."</td>
								<td>".$sold->policy_number."</td>
								<td>".$sold->product_type."</td>
								<td>".$sold->date_purchased."</td>
								<td>$".$sold->annual_prem."</td>
								<td>$".$sold->monthly_prem."</td>
								<td>".number_format($sold->curr_fyc)."%</td>
								<td style='color:".$color.";'>".$label_status."</td>
							</tr>
						";
						//}
					}
				}
				?>
			</tbody>
		</table>

		<h4><?php echo $message; ?></h4>
	</div>		
</div>

<!-- Modal add new-->
<div class="modal fade" id="EditPolicyDetails" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">POLICY DETAILS</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      	<input type="hidden" id="edit_policy_id" value="">	      	
	      	<table class="table table-striped">
	      		<tr>
	      			<td><b>POLICY : </b></td>
	      			<td><input tpye="text" class="form-control" id="policy_number" /></span></td>
	      		</tr>
	      		<tr>
	      			<td><b>DATE PURCHASED : </b></td>
	      			<td><input type="text" name="date_purchased" id="date_purchased" class="form-control datepicker" value=""></td>
	      		</tr>
	      		<tr>
	      			<td><b>CLIENT : </b></td>
	      			<td><input tpye="text" class="form-control" id="client" /></span></td>
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
	      			<td><b>ANNUAL PREMIUM : </b></td>
	      			<td><span id="annual_prem"></td>
	      		</tr>
	      		<tr>
	      			<td><b>MONTHLY PREMIUM : </b></td>
	      			<td><span id="monthly_prem"></td>
	      		</tr>	      		
	      	</table>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	    	<button type='button' class='btn btn-info' value='' id="save_policy_changes">SAVE</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	    </div>
	  </div>
	  
	</div>
</div>

<div class="modal fade" id="PolicyCommissions" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">POLICY COMMISSIONS</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      	<input type="hidden" id="comm_policy_id" value="">
	      	<input type="hidden" id="comm_count" value="">
	      	<input type="hidden" id="comm_prod_id" value="">
	      	<input type="hidden" id="policy_sale_id" value="">
	      	<table class="table table-striped">
	      		<tr>
	      			<td><b>POLICY : </b><span id="comm_policy_number"></td>
	      			
	      			<td><b>COMPANY : </b><span id="comm_company_label"></span></td>
	      			
	      		</tr>

	      		<tr>
	      			<td><b>PRODUCT : </b><span id="comm_product_label"></span></td>	      			

	      			<td><b>ANNUAL PREMIUM : </b><span id="comm_annual_prem"></span></td>	      			
	      		</tr>      		

	      		<tr>
	      			<td><b>DATE PURCHASED : </b><span id="comm_date_purchased"></td>	      			

	      			<td><b>CLIENT : </b><span id="comm_client"></td>	      			
	      		</tr>	      		

	      	</table>

	      	<table class="table table-striped" id="tbl_commissions">
				<thead>
				  <tr>
				    <th>Advisor</th>
				    <th>FCY Commission</th>
				    <th>Bonus</th>
				    <th>Type</th>
				    <th>Charge Back Amt</th>
				  </tr>
				</thead>

				<tbody>	      		
				</tbody>	      			
	      	</table>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	    	<button type='button' class='btn btn-info' value='' id='cancel_policy'>CANCEL POLICY</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
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