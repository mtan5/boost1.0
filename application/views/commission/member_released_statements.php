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
			<h2>Released Statement</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/commission/released_statements'; ?>" id="search_statement_form">
		<div class='row'>
			<div class="col-sm-12">				
				<div class="col-sm-3">Period Start:<input type="text" name="start_date_created" id="start_date_created" class="form-control datepicker" value="<?php echo $start_date_created; ?>"></div>
				<div class="col-sm-3">Period End:<input type="text" name="end_date_created" id="end_date_created" class="form-control datepicker" value="<?php echo $end_date_created; ?>"></div>																	
																	
				<div class="col-sm-3"><br/>&nbsp;
					<div class="btn-group pull-right">
						<button type="submit" class="btn btn-primary" id="">SEARCH</button>
					</div>					
				</div>
			</div>
		</div>		
 		</form>
				
		<br/>		

		<table class="table table-striped">
			<thead>
			  <tr>
			    <th>ID</th>
			    <th>File Name</th>
			    <th>Statement Period</th>
			    <th>Statement Created</th>
			  </tr>
			</thead>
			<tbody>
				<?php				
				if(count($statement_list) > 0){	
					$total = 0;			
					foreach($statement_list as $statement){	
						echo "
							<tr>
								<td>".$statement->id."</td>
								<td><a href='".base_url()."documents/commission_statements/".$statement->file_name."' target='_blank'>".$statement->file_name."</a></td>
								<td>".$statement->statement_period."</td>
								<td>".$statement->date_created."</td>
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

&nbsp;
<br/>
&nbsp;
<br/>
&nbsp;
<br/>