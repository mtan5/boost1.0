<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<link rel="stylesheet" type="text/css" href="<?php echo $jstree_style_path; ?>"></link>
<style type="text/css">
	
</style>
<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="panel-heading"><b>Member Downlines Heirarchy<b></div>
			<div class="col-sm-10"></div>
			<div class="col-sm-2"><br/><button type="button" class="btn btn-primary pull-right back_btn" id="" value="<?php echo $back_loc; ?>">BACK</button></div>			  
			</div>			
			<?php
				////////functions/////////////////
			    function get_downlines($db_downlines, $upline_id){
			        $filtered_array = array();
			        foreach ($db_downlines as $downline) {			        	
			            if (isset($downline->upline_id)) {
			                if ($downline->upline_id == $upline_id) {
			                    $filtered_array[] = $downline;
			                }
			            }
			        }

			        return $filtered_array;
			    } 

			    function display_downline_heirarchy($downline_list, $db_downlines){					
					echo "<ul>";
					foreach ($downline_list as $dl) {		
						echo "<li data-jstree='{\"icon\":\"".base_url()."/public/images/user.png\"}'>";
						echo $dl->first_name." ".$dl->last_name;
						$next_level = get_downlines($db_downlines, $dl->id);
						if(COUNT($next_level)>0){
							display_downline_heirarchy($next_level, $db_downlines);
						}
						echo "</li>";
					}
					echo "</ul>";					
			    }	
				////////END///////////////////////

				echo "<b>".$first_name." ".$last_name."</b><br/>";
				echo "<div id='boost_member_downline' class='col-sm-12' style='overflow:auto; border:1px solid silver; min-height:100px;'>";
				$initial_downline_level = get_downlines($downlines, $member_id);
				display_downline_heirarchy($initial_downline_level, $downlines);
				echo "</div>";
			?>		
	</div>
</div>
<br/>&nbsp;
<br/>&nbsp;

<script src="<?php echo $jstree_path; ?>"></script>
<script>
$('#boost_member_downline').jstree();	
</script>