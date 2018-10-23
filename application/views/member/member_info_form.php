<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $member_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">	
			<br/>
			<div class="col-sm-10">
				<h2>Member Information Form</h2>
			</div>
			<div class="col-sm-2">
				<br/>&nbsp;
				<div class="btn-group">
					<button type="button" class="btn btn-primary" id="SaveMemberInfoBtn">SAVE</button>		
					<button type="button" class="btn btn-primary back_btn" id="" value="<?php echo $back_loc; ?>" style="display:<?php echo $back_btn_visibility; ?>;">BACK</button>
				</div>
			</div>
			<div class="col-sm-12">
				<span style="color:red;font-size:15px;" id="lbl_record"></span>
			</div>
			<input type="hidden" id="process" value="<?php echo $process; ?>">
			<input type="hidden" id="member_id" value="<?php echo $member_id; ?>">
			<form method="post" action="<?php echo $form_action; ?>" id="new_member_form">		
			<table class="table table-bordered">
			    <tbody>
			      <tr>
			        <td>FIRST NAME</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_first_name"></span>
			        	<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $first_name; ?>">
			        </td>

			        <td>LAST NAME</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_last_name"></span>
			        	<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $last_name; ?>">
			        </td>

			        <td>MIDDLE NAME</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_mid_name"></span>
			        	<input type="text" name="mid_name" id="mid_name" class="form-control" value="<?php echo $mid_name; ?>">
			        </td>
			      </tr>


			      <tr>
			        <td>EMAIL</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_email"></span>
			        	<input type="text" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
			        </td> 

			        <td>ADDRESS</td>
			        <td colspan="3">
			        	<span style="color:red;font-size:10px;" id="lbl_address"></span>
			        	<input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>">
			        </td>
			      </tr>

			      <tr>
			        <td>CITY / TOWN</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_city"></span>
			        	<input type="text" name="city" id="city" class="form-control" value="<?php echo $city; ?>">
			        </td>

			        <td>PROVINCE</td>
			        <td>
			        	<?php echo form_dropdown("province", $province_list, $province,"class='form-control' id='province'"); ?>
			        </td>

			        <td>POSTAL</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_postal"></span>
			        	<input type="text" name="postal" id="postal" class="form-control" value="<?php echo $postal; ?>">
			        </td>
			      </tr>		      

			      <tr>
			        <td>SIN</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_sin"></span>
			        	<input type="text" name="sin" id="sin" class="form-control" value="<?php echo $sin; ?>">
			        </td>

			        <td>DATE OF BIRTH</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_dob"></span>
			        	<input type="text" name="dob" id="dob" class="form-control datepicker" value="<?php echo $dob; ?>">
			        </td>

			        <td>CONTACT</td>
			        <td>
			        	<span style="color:red;font-size:10px;" id="lbl_contact"></span>
			        	<input type="text" name="contact" id="contact" class="form-control" value="<?php echo $contact; ?>">
			        </td>
			      </tr>	 

			      <tr>
			        <td>RECRUITER</td>
			        <td>
			        	<?php echo form_dropdown("recruiter", $recruiters, $upline_id,"class='form-control' id='recruiter'"); ?>
			        </td>

			        <td>DIRECTOR</td>
			        <td>
			        	<?php echo form_dropdown("director", $directors, $director_id,"class='form-control' id='director'"); ?>
			        </td>	

			        <td>MEMBERSHIP LEVEL</td>
			        <td>
			        	<?php echo form_dropdown("membership_level", $membership_levels, $level_id,"class='form-control' id='level'"); ?>
			        </td>		        	        
			      </tr>
			    </tbody>
			  </table>	

			  <div class="col-sm-12">
				<p>Have you ever plead guilty or been found guilty of an offense under any law of which you have not been pardoned under the Criminal Records Act (Canada), or are you currently the subject of charges under such a law? Some examples of these offences are fraud, theft, weapons charges, drug trafficking, physical assault, impaired driving and tax evasion. You are not required to disclose any conviction of an offense in respect of any provincial enactment, including minor traffic infractions such as speeding or parking violations?</p>
				<input type="radio" name="guilty" id="guilty_yes" value="1" <?php if($is_plead_guilty=="1") echo "checked"; ?>>&nbsp; Yes<br/>			
				<input type="radio" name="guilty" id="guilty_no" value="0" <?php if($is_plead_guilty=="0" || $is_plead_guilty=="") echo "checked"; ?>>&nbsp;No<br/>

				<p>	
					If yes please provide a separate written explanation and documentation.<br/>
					<textarea class="form-control" name="guilty_exp" id="guilty_exp" <?php echo $guilty_exp_txtarea; ?>><?php echo $guilty_explanation; ?></textarea>
				</p>
			  </div>
			  <br/>&nbsp;
			  <br/>&nbsp;
			  <div class="col-sm-12">
				<p>Have you ever been declared bankrupt or made a voluntary assignment in bankruptcy, or are you currently an undischarged bankrupt?</p>
				<input type="radio" name="bankrupt" id="bankrupt_yes" value="1" <?php if($is_bankrupt=="1") echo "checked"; ?>>&nbsp; Yes<br/>			
				<input type="radio" name="bankrupt" id="bankrupt_no" value="0" <?php if($is_bankrupt=="0" || $is_bankrupt=="") echo "checked"; ?>>&nbsp;No
				<br/>&nbsp;
				<p>
					<table class="table table-bordered" id="trustee_table" style="display:<?php echo $tbl_trustee_display; ?>;">
						<tr>
							<td align="right">TRUSTEE NAME:</td>
							<td><input type="text" name="trustee_name" class="form-control" value="<?php echo $trustee_name; ?>"></td>

							<td align="right">TRUSTEE ADDRESS:</td>
							<td><input type="text" name="trustee_address" class="form-control" value="<?php echo $trustee_address; ?>"></td>
						</tr>					
						<tr>
							<td align="right">LOCATION OF BANKRUPTCY:</td>
							<td><input type="text" name="lob" class="form-control" value="<?php echo $location_bankruptcy; ?>"></td>

							<td align="right">ASSIGNMENT OF BANKRUPTCY:</td>
							<td><input type="text" name="aob" class="form-control" value="<?php echo $assignment_bankruptcy; ?>"></td>
						</tr>					
						<tr>
							<td align="right">STATEMENT OF AFFAIRS:</td>
							<td><input type="text" name="soa" class="form-control" value="<?php echo $statement_of_affairs; ?>"></td>

							<td colspan="2">EXPLANATION:<br/><textarea class="form-control" name="bankrupt_explanation" id=""><?php echo $bankrupt_explanation; ?></textarea></td>
						</tr>																
					</table>
				</p>
			  </div>
				<br/>&nbsp;
				<br/>&nbsp;
			  <div class="col-sm-12">
				<p>Excluding work permits, are you legally entitled to work in Canada?</p>
				<input type="radio" name="workpermit" id="workpermit_yes" value="1" <?php if($is_legal_to_work=="1" || $is_legal_to_work=="") echo "checked"; ?>>&nbsp; Yes<br/>			
				<input type="radio" name="workpermit" id="workpermit_no" value="0" <?php if($is_legal_to_work=="0") echo "checked"; ?>>&nbsp;No
			  </div>		  		  
				<br/>&nbsp;
				<br/>&nbsp;			
			  </form>	
		</div>		
	</div>		
</div>
<br/>&nbsp;
<br/>&nbsp;