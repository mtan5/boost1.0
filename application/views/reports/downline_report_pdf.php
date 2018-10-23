<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div>
	<h4>Downline Report</h4>
	<div>			
		<div class="col-sm-3">Advisor: <?php echo $member; ?></div>				
		<div class="col-sm-3">Level: <?php echo $level; ?></div>				
		<div class="col-sm-2">Period Start: <?php echo $start_date_charged; ?></div>
		<div class="col-sm-2">Period End: <?php echo $end_date_charged; ?></div>																																
	</div>	
</div>

<div class="row">
	<div class="col-sm-12">			
			<?php	
			if(count($commission_list) > 0){				
				foreach($advisor_list as $adv){	
					$total = 0;

					echo "
					<br/>							
					<table border='1'>
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
					";

					echo "
					<br/>
					<table border='1'>
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

					echo "<div>
						<div class='panel-heading'>FYC TOTAL:&nbsp;<span style='font-size:30px;'>$".number_format($total, 2)."</span></div>
					</div>";

				}
			}
			?>
	</div>
</div>
<br/>		

<h4><?php echo $message; ?></h4>
