<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="body">
	<!--<h2><img src='../public/images/voyager.png' class='voyager_logo'/></h2>-->
	<!--<div class="col-sm-12">
		<div class="col-sm-10"></div>
		<div class="col-sm-2"><img src="<?php echo base_url()."/"; ?>public/images/boost_financial.png" style="height:55%;width:55%;"  class="img-responsive pull-right"/></div>
	</div>
-->

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
		      <li class=""><a href="#" id="home">HOME</a></li>
		      <li class="">
		      	<a href="#" id="" class="dropdown-toggle" data-toggle="dropdown">MY PROFILE</a>
				<ul class="dropdown-menu">
					<li><a href="#" class="profile">VIEW</a></li>
					<li><a href="#" class="edit_loggedin_member_info">EDIT</a></li>
				</ul>
		      </li>
		      <li class=""><a href="#" id="" class="" onclick="location='<?php echo base_url()."/product/sold/".$member_id;?>'">MY SALES</a></li>
		      <li class=""><a href="#" id="" class="tree">MY NETWORK</a></li>
		      <li class=""><a href="#" id="" class="commissions">MY COMMISSIONS</a></li>
		      <li class="">
		      	<a href="#" id="reports_button" data-toggle="dropdown">REPORTS</a>
				<ul class="dropdown-menu">
					<li><a href="#" class="member_rel_statements">MY COMMISSION STATEMENTS</a></li>
					<li><a href="#" class="downline_report">DOWNLINE SALES</a></li>
				</ul>			      	
		      </li> 		      
		      <li class=""><a href="#" id="logout_button">LOGOUT</a></li>
		    </ul>
		</div>
	  </div>
	</nav>	