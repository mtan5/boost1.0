<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $member_functions_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container">	
	<div class="col-sm-12" style="padding-top:5%;">
		<div class="panel panel-default">
		<div class="col-sm-12">
			<h2>Membership Requests</h2>
		</div>

		<form method="post" action="<?php echo base_url().'/admin/requests'; ?>" id="search_member_form">
			<div class="col-sm-2">First Name: <input type="text" name="search_fname" id="search_fname" class="form-control" value="<?php echo $first_name; ?>"></div>			
			<div class="col-sm-2">Last Name: <input type="text" name="search_lname" id="search_lname" class="form-control" value="<?php echo $last_name; ?>"></div>
			<div class="col-sm-4">Date of Registration: <input type="text" name="dor" id="dor" class="form-control datepicker" value="<?php echo $dor; ?>"></div>
			<div class="col-sm-4"><br/><button type="button" class="btn btn-primary pull-right" id="search_member_btn">SEARCH</button></div>
		</form>

		<table class="table table-striped">
			<thead>
			  <tr>
			    <th>&nbsp;</th>
			    <th>Member Code</th>
			    <th><a href="#" onclick="sortTable('member_list_table', 1)" style="font-weight:bold;">Name</a></th>
			    <th><a href="#" onclick="sortTable('member_list_table', 4)" style="font-weight:bold;">Date Registered</a></th>
			    <th>Level</th>
			    <th>Payment ID</th>
			    <th>Status</th>
			  </tr>
			</thead>
			<tbody>
				<?php
				if(count($requests) > 0){
					foreach($requests as $req){
						echo "
							<tr>
								<td>
									<button type='button' class='btn btn-info btn-xs view_member' value='".$req->id."'>VIEW</button>
									<button type='button' class='btn btn-default btn-xs approve_req' value='".$req->id."'>APPROVE</button>
								</td>
								<td>".$req->code."</td>
								<td>".$req->first_name." ".$req->last_name."</td>
								<td>".$req->reg_date."</td>
								<td>".$req->membership_level."</td>
								<td>".$req->payment_id."</td>
								<td>".$req->record_status."</td>
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
<br/>&nbsp;
<br/>&nbsp;