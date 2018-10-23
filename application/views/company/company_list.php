<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
			<div class="col-sm-12">
				<h2>Company List</h2>
			</div>
			<form method="post" action="<?php echo base_url().'/company/list'; ?>" id="search_member_form">
				<div class="col-sm-4">Company Name: <input type="text" name="search_company_name" id="search_company_name" class="form-control" value="<?php echo $search_company_name; ?>"></div>
				<div class="col-sm-4">Status: 
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
				<div class="col-sm-4"><br/>
					<div class="btn-group">					
						<button type="submit" class="btn btn-primary" id="search_company_btn">SEARCH</button>
						<button type="button" class="btn btn-primary" id="" data-toggle="modal" data-target="#CompanyModal">ADD</button>
					</div>
				</div>
			</form>
			<table class="table table-striped">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
				    <th>Company Record ID</th>
				    <th>Comapny Name</th>
				    <th>Status</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					if(count($company_list) > 0){
						foreach($company_list as $company){						
							if($company->status=="0"){
								$color="#b2b4b7";
								$access = "disabled";
								$label_status = "Removed";
							}
							else{
								$color = "#000000";
								$access = "";
								$label_status = "Active";
							}
							echo "
								<tr>
									<td>
										<button type='button' class='btn btn-warning btn-xs delete_company' value='".$company->company_id."' ".$access.">REMOVE</button>
										<button type='button' class='btn btn-default btn-xs edit_company' value='".$company->company_id."' data-toggle='modal' data-target='#EditCompanyModal' ".$access.">EDIT</button>
									</td>
									<td style='color:".$color.";'>".$company->company_id."</td>
									<td style='color:".$color.";'><span id='name_".$company->company_id."'>".$company->name."</span></td>
									<td style='color:".$color.";'>".$label_status."</td>
								</tr>
							";
						}
					}
					?>
				</tbody>
			</table>
			<h4><?php echo $message; ?></h4>
		</div>
	</div>		
</div>
<br/>&nbsp;
<br/>&nbsp;

<!-- Modal add new-->
<div class="modal fade" id="CompanyModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Add New Company</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      		<p>COMPANY NAME: <input type="text" name="new_company_name" id="new_company_name" class="form-control" value=""></p>
	      		<p><button type='button' class='btn btn-info btn-xs' value='' id="save_new_company">SAVE</button></p>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="EditCompanyModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Edit Company Name</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      		<p>RECORD ID: <span id="company_rec_id"></span></p>
	      		<p>COMPANY NAME: <input type="text" name="edit_company_name" id="edit_company_name" class="form-control" value=""></p>
	      		<p><button type='button' class='btn btn-info btn-xs' value='' id="save_name_changes">SAVE CHANGES</button></p>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>