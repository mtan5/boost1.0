<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<style type="text/css">
		.login-form {
			width: 340px;
	    	margin: 50px auto;
		}
	    .login-form form {
	    	margin-bottom: 15px;
	        background: #f7f7f7;
	        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	        padding: 30px;
	    }
	    .login-form h2 {
	        margin: 0 0 15px;
	    }
	    .form-control, .btn {
	        min-height: 38px;
	        border-radius: 2px;
	    }
	    .btn {        
	        font-size: 15px;
	        font-weight: bold;
	    }
	</style>
</head>
<body>


	<div class="login-form">
    <form action="<?php echo site_url('home/authenticate') ?>" method="post">
    	<img src="<?php echo base_url()."/"; ?>public/images/boost.png" style="height:40%;width:40%;"  class="img-responsive center-block"/>
        <h2 class="text-center">Login</h2>       
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Username" required="required" name="txt_username">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Password" required="required" name="txt_password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </div>
        <div class="clearfix">
            <!--<label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label>-->
            <a href="#"  data-toggle='modal' data-target='#ForgotLogin' class="pull-right">Forgot UserName / Password?</a>
        </div>        
    </form>
    <p class="text-center"><b><font color="red"><?php echo $message;?></font></b></p>
	</div>

<!-- MODAL -->
<div class="modal fade" id="ForgotLogin" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Forgot Login Credentials</h4>
	    </div>
	    <div class="modal-body">
	    	<form action="<?php echo site_url('home/send_login_credentials') ?>" method="post">
		 <div class="row">
		 	<div class="col-sm-10">Input your boostfinancialteam account EMAIL : <input type="text" name="email" id="email" class="form-control">	</div>
		 <div class="col-sm-2"><br/><button type="submit" class="btn btn-default">GO</button></div>
		 </div>
		</form>	 
	    </div>
	    <div class="modal-footer">	        
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>


</body>
</html>