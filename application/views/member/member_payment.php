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
<div class="" style='margin-top:6%;'>	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading"><h4>PAYMENT ID : <?php echo $payment_id ?></h4></div>
			<div class="panel-body">
				<?php echo $message; ?>.
			</div>
		</div>
	</div>
</div>