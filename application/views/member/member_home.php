<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $member_functions_path; ?>"></script>
<style type="text/css">
	.welcome-div{
		background-color:#e3e0e0;
		height:250px;
		padding:5% 3% 3% 3%;		
	}

	.welcome-sub-div{
		background-color:#e3e0e0;
		height:100px;
		padding:1% 2% 2% 2%;
		border:10px solid white;		
	}	
</style>
<div class="row" style='margin-top:4%;'>	
	<div class="col-sm-2">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $first_name." ".$last_name; ?></div>
			<div class="panel-body">
				<img src="<?php echo base_url()."/"; ?>public/images/boost.png" style="height:40%;width:40%;"  class="img-responsive center-block"/>
				<p><button type="button" class="btn btn-default profile" id="" style="width:100%;"><span class="glyphicon glyphicon-user"></span>&nbsp;My PROFILE</a></button></p>
				<p><button type="button" class="btn btn-default member_sales" id="" style="width:100%;" value="<?php echo $member_id; ?>"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;My SALES</a></button></p>
				<p><button type="button" class="btn btn-default tree" id="" style="width:100%;" value=""><span class="glyphicon glyphicon-link"></span>&nbsp;My NETWORK</a></button></p>
				<p><button type="button" class="btn btn-default commissions" id="" style="width:100%;"><span class="glyphicon glyphicon-usd"></span>&nbsp;My COMISSIONS</a></button></p>
				<p><button type="button" class="btn btn-default" id="" style="width:100%;"><span class="glyphicon glyphicon-usd"></span>&nbsp;My REPORTS</a></button></p>
			</div>
		</div>
	</div>

	<div class="col-sm-10">
		<div class="row" style=''>
			<div class="col-sm-6">
				<div class="panel panel-default">		
					<div class="panel-heading">Latest FYC Sales</div>
					<div class="panel-body">
						<?php 						
							echo "<h1>$".number_format($monthlyFyc[0]->fyc_sum, 2)."</h1>";
						?>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="panel panel-default">		
					<div class="panel-heading">Level Progress Status</div>
				  		<?php
				  			$width=0;
				  			if($totalFyc[0]->fyc_sum!=0 && $level_up_rqrmnts!=0){
				  				$width = ($totalFyc[0]->fyc_sum / $level_up_rqrmnts) * 100;
				  			}
				  		?>					
						<div style="padding:5% 2% 2% 2%;">
							FYC : <b>$<?php echo number_format($totalFyc[0]->fyc_sum, 2) ?></b> out of <b>$<?php echo number_format($level_up_rqrmnts, 2) ?></b>
							  	<div class="progress">
							    	<div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="" style="width:<?php echo number_format($width)?>%"></div>
							  	</div>					
						</div>
					<div class="panel-body"></div>
				</div>				
			</div>			
		</div>

		<div class="row" style=''>
			<div class="panel panel-default">
				<div class="panel-heading">My Downline</div>
				<div class="panel-body">
					<div class="col-sm-12">
					<form method="post" action="<?php echo base_url().'/member/'; ?>" id="search_member_form">
						<div class="col-sm-2">Member's Code: <input type="text" name="search_code" id="search_code" class="form-control" value="<?php echo $code; ?>"></div>
						<div class="col-sm-2">First Name: <input type="text" name="search_fname" id="search_fname" class="form-control" value="<?php echo $search_first_name; ?>"></div>			
						<div class="col-sm-2">Last Name: <input type="text" name="search_lname" id="search_lname" class="form-control" value="<?php echo $search_last_name; ?>"></div>
						<div class="col-sm-2">Level: <?php echo form_dropdown("search_level", $membership_levels, $level,"class='form-control' id='search_level'"); ?></div>
						<div class="col-sm-2">Status: <?php echo form_dropdown("search_status", $membership_status, $status,"class='form-control' id='search_status'"); ?></div>
						<div class="col-sm-2"><br/>
							<div class="btn-group">
							<button type="submit" class="btn btn-primary btn-xs" id="">SEARCH</button>
							<button type="button" class="btn btn-primary add_member btn-xs" id="">ADD NEW</button>
							</div>
						</div>
					</form>
					</div>

					<div class="col-sm-12">				
						<ul class="pagination">
						<?php
							for($x=1; $x<=$page_count; $x++){
								$active="";
								if($page_num == $x)$active="active";
								echo "<li class='".$active."'><a href='".base_url()."/admin/list/".$x."'>".$x."</a></li>";
							}
						?>
						</ul> 			
					</div>

					<div class="col-sm-12">				
						<table class="table table-striped">
							<thead>
							  <tr>
							    <th>&nbsp;</th>
							    <th>Member Code</th>
							    <th>Name</th>
							    <th>Date Registered</th>
							    <th>Level</th>
							    <th>Status</th>
							  </tr>
							</thead>
							<tbody>
								<?php
								if(count($downline) > 0){
									foreach($downline as $member){
										echo "
											<tr>
												<td>
													<button type='button' class='btn btn-info btn-xs view_downline' value='".$member->id."'>VIEW</button>
												</td>
												<td>".$member->code."</td>
												<td>".$member->first_name." ".$member->last_name."</td>
												<td>".$member->reg_date."</td>
												<td>".$member->membership_level."</td>
												<td>".$member->record_status."</td>
											</tr>
										";
									}
								}
								?>
							</tbody>
						</table>
						<h2><?php echo $message; ?></h2>	
					</div>
					
				</div>
			</div>
		</div>		
	</div>
</div>