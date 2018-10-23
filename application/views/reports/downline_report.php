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
			<h2>Downline Report</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/reports/downline_report'; ?>" id="search_dr_form">
		<div class='row'>
			<div class="col-sm-12">			
				<div class="col-sm-3">Member: <?php echo form_dropdown("search_member", $member_list, $search_member,"class='form-control' id='member_list'".$search_member_access); ?></div>				
				<div class="col-sm-2">Period Start:<input type="text" name="start_date_charged" id="start_date_charged" class="form-control datepicker" value="<?php echo $start_date_charged; ?>"></div>
				<div class="col-sm-2">Period End:<input type="text" name="end_date_charged" id="end_date_charged" class="form-control datepicker" value="<?php echo $end_date_charged; ?>"></div>																	
				<div class="col-sm-2">Downline Level:
					<select name="downline_level" id="" class="form-control">
						<option value="1" <?php if($downline_level=="1") echo "SELECTED"; ?> >First Level</option>
						<option value="2" <?php if($downline_level=="2") echo "SELECTED"; ?> >All levels</option>
					</select>
				</div>
				<div class="col-sm-3"><br/>&nbsp;
					<div class="btn-group pull-right">
						<button type="submit" class="btn btn-primary" id="">SEARCH</button>
						<button type="button" class="btn btn-primary" id="save_downline_report_pdf">SAVE AS PDF</button>
					</div>					
				</div>
			</div>
		</div>		
 		</form>
				
		<div class="row">
			<div class="col-sm-12">			
					<?php	
					if(count($commission_list) > 0){				
						foreach($advisor_list as $adv){	
							$total = 0;

							echo "
							<br/>
							<div class='row'>
							<div class='col-sm-3'>							
							<table class='table table-striped'>
								<tbody>
								  <tr>
								  	<td><b>Advisor</b></td>								  	
								    <td>".$adv['name']."</td>
								  </tr>
								  <tr>
								  	<td><b>Level</b></td>
								    <td>".$adv['level']."</td>
								  </tr>
								  <tr>
								  	<td><b>Upline</b></td>
								    <td>".$adv['upline']."</td>								    
								  </tr>
								</tbody>
							</table>
							</div>
							</div>
							";

							echo "
							<table class='table table-striped'>
								<thead>
								  <tr>
								    <th>Policy Number</th>
								    <th>Company</th>
								    <th>Product</th>
								    <th>Annual Premium</th>
								    <th>FYC Rate</th>
								    <th>FYC</th>
								    <th>FYC Commission</th>
								    <th>Bonus</th>
								    <th>Date Charged</th>
								  </tr>
								</thead>
								<tbody>
							";

							foreach($commission_list as $comm){	
								if($adv['id'] == $comm->id){
									$fyc = number_format($comm->annual_prem * ($comm->curr_fyc / 100), 2);
									//$bonus = number_format($comm->commission_amt - $fyc, 2);
									echo "
										<tr>
											<td>".$comm->policy_number."</td>
											<td>".$comm->company_name."</td>
											<td>".$comm->prod_name."</td>
											<td>$".number_format($comm->annual_prem, 2)."</td>											
											<td>".number_format($comm->curr_fyc, 0)."%</td>
											<td>$".$fyc."</td>									
											<td>$".number_format($comm->commission_fyc, 2)."</td>
											<td>$".number_format($comm->commission_amt_bonus, 2)."</td>
											<td>".$comm->date_charged."</td>								
										</tr>
									";
									$total = $total + $comm->commission_fyc;
								}
							}
							echo "
								</tbody>
							</table>
							";												

							echo "<div class='panel panel-default'>
								<div class='panel-heading'>FYC TOTAL:&nbsp;<span style='font-size:30px;'>$".number_format($total, 2)."</span></div>
							</div>";

						}
					}
					?>
			</div>
		</div>
		<br/>		

		<h4><?php echo $message; ?></h4>

	</div>		
</div>

&nbsp;
<br/>
&nbsp;
<br/>
&nbsp;
<br/>