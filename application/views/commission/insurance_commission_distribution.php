<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
function leave() {	
  var myWindow = window.open("", "_self");
  myWindow.document.write("");
  setTimeout (function() {myWindow.close();},1000);
}

function save() {
	var ans = confirm("Save comission details?");
	if(ans){
		window.location.href = "<?php echo base_url(); ?>commission/save_insurance";
	}
}

</script>
<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="col-sm-12" style="">
			<button type='button' class='btn btn-primary' value='' id='' onclick="save()">SAVE</button>
			<button type='button' class='btn btn-primary' value='' id='' onclick="leave()">CANCEL</button>	
		</div>
		<div class="col-sm-12" style=""><br/></div>
		<div class="col-sm-6">	
			<div class="panel panel-default">
				<div class="panel-heading"><h4>ADVISOR 01</h4></div>
				<div class="panel-body">
					<?php
						if($commission_arr1!=NULL){
							foreach ($commission_arr1 as $commission) {
								$monthly_prem = round($commission['policy_details']['monthly_prem'], 2);
								$annual_prem = round($commission['policy_details']['annual_prem'], 2);
								if($commission!=NULL){
									echo"
									<p>
									<u><b>POLICY DETAILS</b></u><br/>
									<table class='table table-striped' id='product_table'>
										<tbody>
											<tr>
											<td align='right'><b>POLICY NUMBER : </b></td>
											<td>".$commission['policy_details']['policy_number']."</td>
											</tr>

											<tr>
											<td align='right'><b>CLIENT : </b></td>
											<td>".$commission['policy_details']['client']."</td>
											</tr>											

											<tr>
											<td align='right'><b>COMPANY : </b></td>
											<td>".$commission['policy_details']['company_name']."</td>
											</tr>

											<tr>
											<td align='right'><b>PRODUCT : </b></td>
											<td>".$commission['policy_details']['product_name']."</td>
											</tr>

											<tr>
											<td align='right'><b>MONTHLY: </b></td>
											<td>$".number_format(round($commission['policy_details']['monthly_prem'], 2))."</td>
											</tr>

											<tr>
											<td align='right'><b>ANNUAL: </b></td>
											<td>$".number_format(round($commission['policy_details']['annual_prem'], 2))."</td>
											</tr>

											<tr>
											<td align='right'><b>FYC : </b></td>
											<td>".$commission['policy_details']['curr_fyc']."%</td>
											</tr>																						
										</tbody>
									</table>
									</p>";

									echo "<p>
									<u><b>AGENT</b></u><br/>
									<table class='table table-striped' id=''>
										<tbody>
											<tr>
												<td align='right'><b>NAME: </b></td>
												<td>".$commission['agent']['name']."</td>
											</tr>
											<tr>
												<td align='right'><b>MEMBER CODE: </b></td>
												<td>".$commission['agent']['code']."</td>
											</tr>
											<tr>
												<td align='right'><b>LEVEL :</b></td>
												<td>".$commission['agent']['level']."</td>
											</tr>
											<tr>
												<td align='right'><b>FYC COMMISSION :</td>
												<td>$".$commission['agent']['commission_fyc']."</td>
											</tr>

											<tr>
												<td align='right'><b>BONUS :</td>
												<td>$".$commission['agent']['commission_bonus']."</td>
											</tr>																					
										</tbody>
									</table>
									</p>";

									//uplines
									echo "<p>
									<u><b>UPLINES</b></u><br/>";
									if($commission['uplines'] != NULL){
										foreach ($commission['uplines'] as $upline) {
											echo "					
												<table class='table table-striped' id=''><tbody>
												<tr>
													<td align='right'><b>NAME: </b></td>
													<td>".$upline['name']."</td>
												</tr>
												<tr>
													<td align='right'><b>MEMBER CODE: </b></td>
													<td>".$upline['code']."</td>
												</tr>
												<tr>
													<td align='right'><b>LEVEL :</b></td>
													<td>".$upline['level']."</td>
												</tr>
												<tr>
													<td align='right'><b>OVERRIDE COMMISSION :</td>
													<td>$".$upline['commission_fyc']."</td>
												</tr>
		
												<tr>
													<td align='right'><b>BONUS :</td>
													<td>$".$upline['commission_bonus']."</td>
												</tr>
												</tbody>
											</table></p>									
											";
										}
									}

									echo "<p>
									<u><b>DIRECTORS</b></u><br/>";
									if($commission['directors'] != NULL){
										foreach ($commission['directors'] as $director) {
											echo "					
												<table class='table table-striped' id=''><tbody>
												<tr>
													<td align='right'><b>NAME: </b></td>
													<td>".$director['name']."</td>
												</tr>
												<tr>
													<td align='right'><b>MEMBER CODE: </b></td>
													<td>".$director['code']."</td>
												</tr>
												<tr>
													<td align='right'><b>LEVEL :</b></td>
													<td>".$director['level']."</td>
												</tr>
												<tr>
													<td align='right'><b>OVERRIDE COMMISSION :</td>
													<td>$".$director['commission']."</td>
												</tr>

												</tbody>
											</table></p>									
											";
										}	
									}								
								}								
							}
						}
					?>				
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>ADVISOR 02</h4></div>
				<div class="panel-body">
					<?php
						if($commission_arr2!=NULL){								
							foreach ($commission_arr2 as $commission) {
								if($commission!=NULL){
									echo"
									<p>
									<u><b>POLICY DETAILS</b></u><br/>
									<table class='table table-striped' id='product_table'>
										<tbody>
											<tr>
											<td align='right'><b>POLICY NUMBER: </b></td>
											<td>".$commission['policy_details']['policy_number']."</td>
											</tr>

											<tr>
											<td align='right'><b>CLIENT : </b></td>
											<td>".$commission['policy_details']['client']."</td>
											</tr>												

											<tr>
											<td align='right'><b>COMPANY: </b></td>
											<td>".$commission['policy_details']['company_name']."</td>
											</tr>

											<tr>
											<td align='right'><b>PRODUCT: </b></td>
											<td>".$commission['policy_details']['product_name']."</td>
											</tr>

											<tr>
											<td align='right'><b>MONTHLY: </b></td>
											<td>$".number_format(round($commission['policy_details']['monthly_prem'], 2))."</td>
											</tr>

											<tr>
											<td align='right'><b>ANNUAL: </b></td>
											<td>$".number_format(round($commission['policy_details']['annual_prem'], 2))."</td>
											</tr>
											
											<tr>
											<td align='right'><b>FYC : </b></td>
											<td>".$commission['policy_details']['curr_fyc']."%</td>
											</tr>																																
										</tbody>
									</table>
									</p>";

									echo "<p>
									<u><b>AGENT</b></u><br/>
									<table class='table table-striped' id=''>
										<tbody>
											<tr>
												<td align='right'><b>NAME: </b></td>
												<td>".$commission['agent']['name']."</td>
											</tr>
											<tr>
												<td align='right'><b>MEMBER CODE: </b></td>
												<td>".$commission['agent']['code']."</td>
											</tr>
											<tr>
												<td align='right'><b>LEVEL :</b></td>
												<td>".$commission['agent']['level']."</td>
											</tr>
											<tr>
												<td align='right'><b>FYC COMMISSION :</td>
												<td>$".$commission['agent']['commission_fyc']."</td>
											</tr>

											<tr>
												<td align='right'><b>BONUS :</td>
												<td>$".$commission['agent']['commission_bonus']."</td>
											</tr>											
										</tbody>
									</table>
									</p>";
	

									//uplines
									echo "<p>
									<u><b>UPLINES</b></u><br/>";
									if($commission['uplines'] != NULL){
										foreach ($commission['uplines'] as $upline) {
											echo "					
												<table class='table table-striped' id=''><tbody>
												<tr>
													<td align='right'><b>NAME: </b></td>
													<td>".$upline['name']."</td>
												</tr>
												<tr>
													<td align='right'><b>MEMBER CODE: </b></td>
													<td>".$upline['code']."</td>
												</tr>
												<tr>
													<td align='right'><b>LEVEL :</b></td>
													<td>".$upline['level']."</td>
												</tr>
												<tr>
													<td align='right'><b>OVERRIDE COMMISSION :</td>
													<td>$".$upline['commission_fyc']."</td>
												</tr>

												<tr>
													<td align='right'><b>BONUS :</td>
													<td>$".$upline['commission_bonus']."</td>
												</tr>
												</tbody>
											</table></p>									
											";
										}
									}

									echo "<p>
									<u><b>DIRECTORS</b></u><br/>";
									if($commission['directors'] != NULL){
										foreach ($commission['directors'] as $director) {
											echo "					
												<table class='table table-striped' id=''><tbody>
												<tr>
													<td align='right'><b>NAME: </b></td>
													<td>".$director['name']."</td>
												</tr>
												<tr>
													<td align='right'><b>MEMBER CODE: </b></td>
													<td>".$director['code']."</td>
												</tr>
												<tr>
													<td align='right'><b>LEVEL :</b></td>
													<td>".$director['level']."</td>
												</tr>
												<tr>
													<td align='right'><b>COMMISSION :</td>
													<td>$".$director['commission']."</td>
												</tr>

												</tbody>
											</table></p>									
											";
										}
									}									
								}								
							}
						}
					?>					
				</div>
			</div>
		</div>			
</div>
<br/>&nbsp;
<br/>&nbsp;
<br/>&nbsp;