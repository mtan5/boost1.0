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
			<h2>Member Levels</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/admin/save_levels'; ?>" id="search_member_form">
			<table class="table table-striped">
				<thead>
				  <tr>
				    <th>ID</th>
				    <th>Membership Level</th>
				    <th>Bonus</th>
				    <th>FYC Requirements</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					if(count($levels) > 0){
						foreach($levels as $lvl){
							echo "<tr>";
							echo "<td>".$lvl->level_tbl_id."</td>";
							echo "<td><input type='text' class='form-control' value='".$lvl->membership_level."' id='' name='level_".$lvl->level_tbl_id."'/></td>";
							echo "<td><input type='text' class='form-control' value='".$lvl->commission_factor."' id='' name='commission_factor_".$lvl->level_tbl_id."'/></td>";
							echo "<td><input type='text' class='form-control' value='".$lvl->level_up_rqrmnts."' id='' name='level_up_rqrmnts_".$lvl->level_tbl_id."'/></td>";
							echo "</tr>";
						}
					}
					?>
				</tbody>
			</table>
			<button type="submit" class="btn btn-primary" id="">SAVE CHANGES</button>
		</form>
	</div>		
</div>
<br/>&nbsp;
<br/>&nbsp;
