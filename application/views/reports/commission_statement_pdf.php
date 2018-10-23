<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="">
	<h4>Commission Statement</h4>	
	<div>			
		<div class="col-sm-3">Advisor: <?php echo $member; ?></div>				
		<div class="col-sm-3">Level: <?php echo $level; ?></div>				
		<div class="col-sm-2">Period Start: <?php echo $start_date_charged; ?></div>
		<div class="col-sm-2">Period End: <?php echo $end_date_charged; ?></div>																																
	</div>

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
		<table class="" border="1">
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
	<div class="panel-heading">TOTAL COMMISSION AMOUNT:&nbsp;<h1><?php echo "$".number_format($commission, 2); ?></h1></div>
</div>		
