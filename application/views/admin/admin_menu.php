<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="body">
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#boostNavBar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>                        
	      </button>	    	
	      <a class="navbar-brand" href="#">
	      	 <img src="<?php echo base_url()."/"; ?>public/images/boost2.png" class="img-responsive" width="30" height="30" alt="">
	      </a>
	    </div>
	    <div class="collapse navbar-collapse" id="boostNavBar">
		    <ul class="nav navbar-nav">	      
		      <li class=""><a href="#" id="admin_home">HOME</a></li>
		      <li class="">
		      	<a href="#" id="" class="dropdown-toggle" data-toggle="dropdown">MEMBER</a>
				<ul class="dropdown-menu">
					<li><a href="#" class="add_member">ADD NEW</a></li>
					<li><a href="#" class="member_list">DASHBOARD</a></li>
					<li><a href="#" class="levels">LEVELS SETTINGS</a></li>
					<li><a href="#" class="member_request">REQUESTS</a></li>
				</ul>		      	
		      </li>
		      <li class=""><a href="#" class="company_list">COMPANY</a></li>
		      <li class="">
		      		<a href="#" class="dropdown-toggle" data-toggle="dropdown">PRODUCT</a>
		      		<ul class="dropdown-menu">
		      			<li><a href="#" class="product_list">INVENTORY</a></li>
		      			<li><a href="#" class="product_sold">SALES</a></li>
		      		</ul>
		      </li>
		      <li class="">
		      	<a href="#" class="dropdown-toggle" data-toggle="dropdown">COMMISSIONS</a>
				<ul class="dropdown-menu">
					<li><a href="#" class="new_insurance_commission">ADD INSURANCE</a></li>
					<li><a href="#" class="">ADD INVESTMENT</a></li>
					<li><a href="#" class="commission_list">DASHBOARD</a></li>
					<li><a href="#" class="rel_statements">RELEASED COMMISSION STATEMENTS</a></li>
				</ul>			      	
		      </li>

		      <li class="">
		      	<a href="#" id="reports_button" data-toggle="dropdown">REPORTS</a>
				<ul class="dropdown-menu">
					<li><a href="#" class="commission_statement">COMISSION STATEMENT</a></li>
					<li><a href="#" class="downline_report">DOWNLINE SALES</a></li>
				</ul>			      	
		      </li> 
		      <li class=""><a href="#" id="logout_button">LOGOUT</a></li>
		    </ul>

		    <ul class="nav navbar-nav navbar-right">
		      <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;ADMIN: <?php echo $username; ?></a></li>
		      <!--<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
		    </ul>		    
		</div>
	  </div>
	</nav>	