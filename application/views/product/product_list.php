<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<script src="<?php echo $product_functions_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>
<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="col-sm-12">
			<h2>Product List</h2>
		</div>
		<form method="post" action="<?php echo base_url().'/product/list'; ?>" id="search_product_form">
			<input type="hidden" name="page_number" id="page_number" value=""/>
			<div class="col-sm-2">Company:<?php echo form_dropdown("search_company_id", $company_list, $search_company_id,"class='form-control' id='search_company_id'"); ?></div>
			<div class="col-sm-2">Product Name: <input type="text" name="search_product_name" id="search_product_name" class="form-control" value="<?php echo $search_product_name; ?>"></div>
			<div class="col-sm-2">PRODUCT TYPE: <?php echo form_dropdown("search_prod_type", $prod_types, $search_prod_type,"class='form-control' id='search_prod_type'"); ?></div>
			<div class="col-sm-2">Status: 
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
			<div class="col-sm-2"><br/>
				<div class="btn-group">					
					<button type="submit" class="btn btn-primary" id="search_company_btn">SEARCH</button>
					<button type="button" class="btn btn-primary" id="" data-toggle="modal" data-target="#ProductModal">ADD</button>
				</div>
			</div>
		</form>
		<br/>		
		<div class="row">
			<div class="col-sm-12">	
				<ul class="pagination">
				<?php
					for($x=1; $x<=$page_count; $x++){
						$active="";
						if($page_num == $x)$active="active";
						echo "<li class='".$active."'><a href='#' class='next_page'>".$x."</a></li>";
					}
				?>
				</ul> 					
			</div>
		</div>		
		<table class="table table-striped">
			<thead>
			  <tr>
			    <th>&nbsp;</th>
			    <th>Product Record ID</th>
			    <th>Company</th>
			    <th>Product Name</th>
			    <th>FYC</th>
			    <th>Product Type</th>
			    <th>Status</th>
			  </tr>
			</thead>
			<tbody>
				<?php
				if(count($product_list) > 0){
					$cntr = 0;
					foreach($product_list as $prod){
						$cntr++;
						if($prod->status=="0"){
							$color="#b2b4b7";
							$access = "disabled";
							$label_status = "Removed";
						}
						else{
							$color = "#000000";
							$access = "";
							$label_status = "Active";
						}		
						if($cntr >= $rec_start && $cntr <= $rec_end){				
							echo "
								<tr>
									<td>
										<button type='button' class='btn btn-warning btn-xs delete_prod' value='".$prod->prod_id."' ".$access.">REMOVE</button>
										<button type='button' class='btn btn-default btn-xs edit_prod' value='".$prod->prod_id."_".$prod->company_id."_".$prod->product_type_id."' data-toggle='modal' data-target='#EditProductModal' ".$access.">EDIT</button>
									</td>
									<td>".$prod->prod_id."</td>
									<td>".$prod->company_name."</td>
									<td style='color:".$color.";'><span id='product_".$prod->prod_id."'>".$prod->prod_name."</span></td>
									<td style='color:".$color.";'><span id='fyc_".$prod->prod_id."'>".$prod->fyc."</td>
									<td style='color:".$color.";'>".$prod->product_type."</td>
									<td style='color:".$color.";'>".$label_status."</td>
								</tr>
							";
						}
					}
				}
				?>
			</tbody>
		</table>
		<h4><?php echo $message; ?></h4>
	</div>		
</div>
<br/>&nbsp;
<br/>&nbsp;

<!-- Modal add new-->
<div class="modal fade" id="ProductModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Add New Product</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      		<p>COMPANY:<?php echo form_dropdown("new_company", $company_list, "","class='form-control' id='new_company'"); ?></p>
	      		<p>PRODUCT: <input type="text" name="new_product_name" id="new_product_name" class="form-control" value=""></p>
	      		<p>PRODUCT TYPE: <?php echo form_dropdown("new_prod_type", $prod_types, "","class='form-control' id='new_prod_type'"); ?></p>  		
	      		<p>FYC%: <input type="text" name="new_fyc" id="new_fyc" class="form-control" value=""></p>	      		
	      		<p><button type='button' class='btn btn-info btn-sm' value='' id="save_new_product">SAVE</button></p>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="EditProductModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	      <h4 class="modal-title">Edit Product</h4>
	    </div>
	    <div class="modal-body">
	      <div id="company name">
	      		<p>PRODUCT ID: <span id="product_id"></span></p>
	      		<p>COMPANY: <?php echo form_dropdown("edit_company", $company_list, "","class='form-control' id='edit_company'"); ?></p>
	      		<p>PRODUCT NAME: <input type="text" name="edit_prod_name" id="edit_prod_name" class="form-control" value=""></p>	    
	      		<p>PRODUCT TYPE: <?php echo form_dropdown("edit_prod_type", $prod_types, "","class='form-control' id='edit_prod_type'"); ?></p>  		
				<p>FYC%: <input type="text" name="edit_fyc" id="edit_fyc" class="form-control" value=""></p>	      		
	      		<p><button type='button' class='btn btn-info btn-sm' value='' id="save_product_changes">SAVE CHANGES</button></p>
	      </div>	      	
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	  
	</div>
</div>