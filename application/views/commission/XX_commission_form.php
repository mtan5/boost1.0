<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $admin_functions_path; ?>"></script>
<script src="<?php echo $commission_functions_path; ?>"></script>

<div class="container">	
<form method="post" action="<?php echo $form_action; ?>" id="commission_form" target="_blank">		
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="col-sm-6">			
			<div class="panel panel-default">
				<div class="panel-heading"><h4>AGENT 01</h4></div>
				<div class="panel-body">
					<input type="hidden" id="agent_num" value="" />
					NAME:
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-info show_member" type="button" data-toggle="modal" data-target="#MemberModal" value="1">ADD</button>
				      </span>
				      <input type="hidden" id="agent_id_1" name="agent_id_1" />
				      <input type="text" class="form-control" placeholder="" id="agent_name_1" disabled>
				    </div>						

				    <input type="hidden" id="trainee_num" value="" />
				    TRAINEE:
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-info show_trainee" type="button" data-toggle="modal" data-target="#TraineeModal" value="1">ADD</button>
				      </span>
				      <input type="hidden" id="trainee_id_1" name="trainee_id_1">
				      <input type="text" class="form-control" placeholder="" id="trainee_name_1" disabled>
				    </div>					
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>AGENT 02</h4></div>
				<div class="panel-body">
					NAME:
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-info show_member" type="button" data-toggle="modal" data-target="#MemberModal" value="2">ADD</button>
				      </span>
				      <input type="hidden" id="agent_id_2" name="agent_id_2">
				      <input type="text" class="form-control" placeholder="" id="agent_name_2" disabled>
				    </div>						

				    TRAINEE:
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-info show_trainee" type="button" data-toggle="modal" data-target="#TraineeModal" value="2">ADD</button>
				      </span>
				      <input type="hidden" placeholder="" id="trainee_id_2" name="trainee_id_2">
				      <input type="text" class="form-control" id="trainee_name_2" placeholder="" disabled>
				    </div>					
				</div>
			</div>
		</div>				
</div>

<div class="col-sm-12" style="padding:3%;">
	<div class="panel panel-default">
		<div class="panel-heading"><h4>POLICY DETAILS</h4></div>
		<div class="panel-body">
			<div class="col-sm-12">
			POLICY NUMBER:<input type="text" name="policy_number" id="policy_number" class="form-control" value="" />
			COMPANY:
		    <?php echo form_dropdown("select_companies", $companies, "","class='form-control' id='select_companies'"); ?>					
		    <br/><input type="hidden" name="selected_products" id="selected_products">
			<div class="panel panel-default">
				<div class="panel-heading">PRODUCTS</div>
				<div class="panel-body">
					<table class="table table-striped" id="product_table">
						<thead>
						  <tr>
						    <th>Product</th>
						    <th>Monthly Premium</th>
						  </tr>
						</thead>
						<tbody>

						</tbody>
					</table>							
				</div>
			</div>
			<br/>
			<div class="btn-group pull-right">
				<button type='button' class='btn btn-primary' value='' id='pre_compute_btn'>COMPUTE</button>						
			</div>
			</div>					
		</div>
	</div>
</div>
</form>	
<!-- Modal add new-->
<div class="modal fade" id="MemberModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Select Member:</h4>
	    </div>
	    <div class="modal-body" style='height:475px;overflow:auto;'>
			<table class="table table-striped">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
				    <th>Member Code</th>
				    <th>Name</th>
				    <th>Level</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					if(count($members) > 0){
						foreach($members as $member){
							echo "
								<tr>
									<td>
										<input type='radio' class='form-control rd_member' id='member_agent' name='member_agent' value='".$member->id."_".$member->first_name."_".$member->last_name."' />
									</td>
									<td>".$member->code."</td>
									<td>".$member->first_name." ".$member->last_name."</td>
									<td>".$member->membership_level."</td>
								</tr>
							";
						}						
					}
					?>
				</tbody>
			</table>
	    </div>
	    <div class="modal-footer">
	    	<button type="button" class="btn btn-primary" data-dismiss="modal" id="set_agent">SELECT</button>
	    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="TraineeModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Select Trainee</h4>
	    </div>
	    <div class="modal-body" style='height:475px;overflow:auto;'>
			<table class="table table-striped">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
				    <th>Member Code</th>
				    <th>Name</th>
				    <th>Level</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					if(count($trainees) > 0){
						foreach($trainees as $member){
							echo "
								<tr>
									<td>
										<input type='radio' class='form-control rd_trainee' id='trainee_agent' name='trainee_agent' value='".$member->id."_".$member->first_name."_".$member->last_name."' />
									</td>
									<td>".$member->code."</td>
									<td>".$member->first_name." ".$member->last_name."</td>
									<td>".$member->membership_level."</td>
								</tr>
							";
						}						
					}
					?>
				</tbody>
			</table>      	
	    </div>
	    <div class="modal-footer">
	    	<button type="button" class="btn btn-primary" data-dismiss="modal" id="set_trainee">SELECT</button>
	    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="CompanyModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Select Company</h4>
	    </div>
	    <div class="modal-body" style='height:475px;overflow:auto;'>
	      <div id="">
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ProductModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title" style='height:475px;overflow:auto;'>Select Product</h4>
	    </div>
	    <div class="modal-body">
	      <div id="">
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>