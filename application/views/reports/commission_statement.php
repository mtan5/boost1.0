<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<script src="<?php echo $report_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container-fluid">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="col-sm-12">
			<h2>Commission Statement</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/reports/commission_statement'; ?>" id="search_commission_form">
		<div class='row'>
			<div class="col-sm-12">			
				<div class="col-sm-3">Member: <?php echo form_dropdown("search_member", $member_list, $search_member,"class='form-control' id='member_list' ".$search_member_access); ?></div>				
				<div class="col-sm-2">Period Start:<input type="text" name="start_date_charged" id="start_date_charged" class="form-control datepicker" value="<?php echo $start_date_charged; ?>"></div>
				<div class="col-sm-2">Period End:<input type="text" name="end_date_charged" id="end_date_charged" class="form-control datepicker" value="<?php echo $end_date_charged; ?>"></div>
				<div class="col-sm-2">Commission:
					<select name="commission_view" id="" class="form-control">
						<option value="overall" <?php if($commission_view=="overall") echo "SELECTED"; ?> >Over All</option>
						<option value="fyconly" <?php if($commission_view=="fyconly") echo "SELECTED"; ?> >FYC only</option>
					</select>
				</div>																	
				<div class="col-sm-3"><br/>&nbsp;
					<div class="btn-group pull-right">
						<button type="submit" class="btn btn-primary" id="">SEARCH</button>
						<button type="button" class="btn btn-primary" id="save_commission_pdf">SAVE AS PDF</button>
					</div>					
				</div>
			</div>
		</div>		
 		</form>
				
		<div class="row">
			<div class="col-sm-6">
			<?php
				if(count($commission_list) > 0){			
					echo "
					<p>
						<table class='table table-striped'>
							<tr>
								<td style='width:20%;'><b>AGENT : </b></td>
								<td>".$commission_list[0]->first_name." ".$commission_list[0]->last_name."</td>
							</tr>
							<tr>
								<td style='width:20%;'><b>MEMBER CODE: </b></td>
								<td>".$commission_list[0]->code."</td>
							</tr>														
						</table>
					</p>
					";
				}
				else{
					echo "<h4>No records found..</h4>";
				}
			?>
			</div>
		</div>
		<br/>		

		<?php
		$total=0;
		$commission=0;
		if(count($product_list) > 0){			
			foreach($product_list as $policy){	
			$total = 0;
			echo "
			<div class='row'>
				<div class='col-sm-6'>				
				<p>
					<table class='table table-striped'>
						<tr>
							<td style='width:20%;'><b>POLICY :</b> ".$policy->policy_number."</td>
							<td style='width:20%;'><b>COMPANY :</b> ".$policy->name."</td>	
							<td style='width:20%;'><b>CLIENT :</b> ".$policy->client."</td>						
						</tr>
					</table>
				</p>
				</div>
			</div>
			";			
		?>
		<table class="table table-striped">
			<thead>
			  <tr>
			    <th>Product</th>
			    <th>Product Type</th>
			    <th>Annual Premium</th>
			    <th>Level</th>
			    <th>FYC </th>
			    <th>FYC Commission</th>			    
			    <th <?php if($commission_view=="fyconly") echo "style='display:none;'"; ?> >Bonus</th>
			    <th <?php if($commission_view=="fyconly") echo "style='display:none;'"; ?> >Over All</th>
			    <th>Date Charged</th>
			  </tr>
			</thead>
			<tbody>
				<?php							
				if(count($commission_list) > 0){	
					$total = 0;			
					foreach($commission_list as $comm){	
						if($comm->policy_id== $policy->policy_id){	
														
							if($commission_view=="fyconly"){
								$style="style='display:none;'";
								$overall = $comm->commission_fyc;
							}
							else{
								$style="";	
								$overall = $comm->commission_fyc + $comm->commission_amt_bonus;
							}

							echo "
								<tr>
									<td>".$comm->prod_name."</td>
									<td>".$comm->product_type."</td>
									<td>".$comm->annual_prem."</td>
									<td>".$comm->membership_level."</td>
									<td>".$comm->curr_fyc."%</td>
									<td>$".$comm->commission_fyc."</td>
									<td ".$style.">$".$comm->commission_amt_bonus."</td>
									<td ".$style.">$".number_format($overall, 2)."</td>							
									<td>".$comm->date_charged."</td>
								</tr>
							";
							$total = $total + $overall;
						}
					}
				}
				?>
			</tbody>
		</table>

		<h4><?php echo $message; ?></h4>
		<div class="panel panel-default">
			<div class="panel-heading">TOTAL:&nbsp;<span style="font-size:30px;"><?php echo "$".number_format($total, 2); ?></span></div>
			<?php $commission = $commission + $total; ?> 
		</div>

		<?php }} ?>

	</div>	
	
	<div class="panel panel-default">
		<div class="panel-heading">TOTAL COMMISSION AMOUNT:&nbsp;<span style="font-size:30px;text-align:right;"><?php echo "$".number_format($commission, 2); ?></span></div>
	</div>					
</div>

<!-- Modal add new-->
<div class="modal fade" id="" role="dialog">
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

	      </div>	      	
	    </div>
	    <div class="modal-footer">
	    	<button type='button' class='btn btn-info' value='' id="save_commission_change">SAVE</button>
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