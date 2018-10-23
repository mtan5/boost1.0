<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container-fluid">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
			<div class="col-sm-12">
				<h2>Member List</h2>
			</div>

			<div class="col-sm-12">
			<form method="post" action="<?php echo base_url().'/admin/list'; ?>" id="search_member_form">
				<div class="col-sm-2">Member's Code: <input type="text" name="search_code" id="search_code" class="form-control" value="<?php echo $code; ?>"></div>
				<div class="col-sm-2">First Name: <input type="text" name="search_fname" id="search_fname" class="form-control" value="<?php echo $first_name; ?>"></div>			
				<div class="col-sm-2">Last Name: <input type="text" name="search_lname" id="search_lname" class="form-control" value="<?php echo $last_name; ?>"></div>
				<div class="col-sm-2">Level: <?php echo form_dropdown("search_level", $membership_levels, $level,"class='form-control' id='search_level'"); ?></div>
				<div class="col-sm-2">Status: <?php echo form_dropdown("search_status", $membership_status, $status,"class='form-control' id='search_status'"); ?></div>
				<div class="col-sm-2"><br/><button type="button" class="btn btn-primary pull-right" id="search_member_btn">SEARCH</button></div>
				<input type="hidden" name="page_number" id="page_number" value=""/>
			</form>
			</div>

			<div class="col-sm-12">	
				<div class="col-sm-2"><br/><button type="button" class="btn btn-primary" id="show_all_btn" onclick="window.location.href='<?php echo base_url(); ?>/admin/list/all'">SHOW ALL</button></div>
				<div class="col-sm-10"><ul class="pagination">
				<?php
					for($x=1; $x<=$page_count; $x++){
						$active="";
						if($page_num == $x)$active="active";
						echo "<li class='".$active."'><a href='#' class='next_page'>".$x."</a></li>";
						//echo "<li class='".$active."'><a href='".base_url()."/admin/list/".$x."'>".$x."</a></li>";
					}
				?>
				</ul> 			
				</div>
			</div>

			<div class="col-sm-12">			
				<div class="col-sm-10"></div>
				<div class="col-sm-2">					
				</div>
			</div>

			<table class="table table-striped" id="member_list_table">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
				    <th><a href="#" onclick="sortTable('member_list_table', 1)" style="font-weight:bold;">Name</a></th>
				    <th>Member Code</th>
				    <th>Username</th>
				    <th><a href="#" onclick="sortTable('member_list_table', 4)" style="font-weight:bold;">Date Registered</a></th>
				    <th>Level</th>
				    <th>Status</th>
				  </tr>
				</thead>
				<tbody>
					<?php					
					if(count($member_list) > 0){
						$cntr = 0;
						foreach($member_list as $member){
							$cntr++;
							if($cntr >= $rec_start && $cntr <= $rec_end){
								echo "
									<tr>
										<td>
											<div class='btn-group'>
											<button type='button' class='btn btn-info btn-xs view_member' value='".$member->id."'>VIEW</button>											
											<button type='button' class='btn btn-default btn-xs heirarchy' value='".$member->id."'>DOWNLINES</button>
											</div>

											<div class='btn-group'>
											<button type='button' class='btn btn-info btn-xs edit_member' value='".$member->id."'>EDIT</button>
											<button type='button' class='btn btn-success btn-xs set_username' value='".$member->id."_".$member->username."' data-toggle='modal' data-target='#AccessModal'>ACCESS</button>";
											
											if($member->status == "1"){
												echo "<button type='button' class='btn btn-warning deactivate_member btn-xs ' value='".$member->id."'>DEACTIVATE</button>";
											}
											else if ($member->status == "3"){
												echo "<button type='button' class='btn btn-primary activate_member btn-xs ' value='".$member->id."'>ACTIVATE</button>";
											}
											else{
												echo "<button type='button' class='btn btn-warning btn-xs ' value='".$member->id."' disabled>DEACTIVATE</button>";
											}
											
											
								echo "			
											</div>
										</td>
										<td id='member_name_".$member->id."'>".$member->first_name." ".$member->last_name."</td>
										<td>".$member->code."</td>
										<td>".$member->username."</td>
										<td>".$member->reg_date."</td>
										<td>".$member->membership_level."</td>
										<td>".$member->record_status."</td>
									</tr>
								";
							}
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

<!-- Modal -->
<div class="modal fade" id="AccessModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Member Access</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      		<p>MEMBER RECORD ID: <span id="member_id"></span></p>	      		
	      		<p>MEMBER: <span id="member_name"></span></p>
	      		<p>USERNAME: <input type="text" name="username" id="username" class="form-control" value=""></p>	      		
				<p>PASSWORD<input type="text" name="password" id="password" class="form-control" value=""></p>
				<input type="hidden" name="process" id="process" class="" value="">
	      		<p><button type='button' class='btn btn-info btn-sm' value='' id="save_access">SAVE ACCESS</button></p>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>