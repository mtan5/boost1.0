<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo $function_lib_path; ?>"></script>
<script src="<?php echo $jqueryui_path; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jqueryuicss_path; ?>"></link>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>

    // Render the PayPal button
    paypal.Button.render({
        // Set your environment
        env: 'sandbox', // sandbox | production

        // Specify the style of the button
        style: {
            label: 'pay',
            size:  'medium',    // small | medium | large | responsive
            shape: 'rect',     // pill | rect
            color: 'blue',     // gold | blue | silver | black
            tagline: false    
        },

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create
        client: {
            sandbox:    'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
            production: 'AfCN6usXx0ojFEmXzeu6xH9JGvoRnxiQLBvKRiSwXl_J23JDmgyVXWYl62G6cLd_kPEQix_Onv9TtWmV'
        },

        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '0.10', currency: 'CAD' }
                        }
                    ]
                }
            });
        },

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
                window.alert('Payment Submission Complete!');
                $("#payment_id").val(data.paymentID);
                $("#save_payment_form").submit();
            });
        }

    }, '#paypal-button');

</script>

<div class="container">	
	<div class="col-sm-12" style="padding-top:6%;">
		<div class="panel panel-default" style="padding:5px 5px 5px 5px;">
		<div class="panel-heading"><h4>Member Profile</h4></div>
			<div class="col-sm-10"></div>
			<div class="col-sm-2"><br/><button type="button" class="btn btn-primary pull-right back_btn" id="" value="<?php echo $back_loc; ?>">BACK</button></div>
			<form method="post" action="<?php echo base_url().'/member/save_payment'; ?>" id="save_payment_form">
				<input type="hidden" name="member_id" id="member_id" value="<?php echo $id; ?>">
				<input type="hidden" name="payment_id" id="payment_id" value="">
			</form>
			<table class="table table-bordered">
			    <tbody>
			      <tr>
			        <td width="200px"><b>FIRST NAME</b></td>
			        <td><?php echo $first_name; ?></td>
			      </tr>
			      
			      <tr>
			        <td width="200px"><b>LAST NAME</b></td>
			        <td><?php echo $last_name; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>MIDDLE NAME</b></td>
			        <td><?php echo $mid_name; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>SIN</b></td>
			        <td><?php echo $sin; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>DATE OF BIRTH</b></td>
			        <td><?php echo $dob; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>ADDRESS</b></td>
			        <td><?php echo $address; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>CITY</b></td>
			        <td><?php echo $city; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>PROVINCE</b></td>
			        <td><?php echo $province; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>POSTAL</b></td>
			        <td><?php echo $postal; ?></td>
			      </tr>		      

			      <tr>
			        <td width="200px"><b>CONTACT</b></td>
			        <td><?php echo $contact; ?></td>
			      </tr>

			      <tr>
			        <td width="200px"><b>EMAIL</b></td>
			        <td><?php echo $email; ?></td>
			      </tr>	

			      <tr>
			        <td width="200px"><b>RECRUITER</b></td>
			        <td><?php echo $recruiter; ?></td>
			      </tr>			      	      

			      <tr>
			        <td width="200px"><b>DIRECTOR</b></td>
			        <td><?php echo $director; ?></td>
			      </tr>	

			      <tr>
			        <td width="200px"><b>MEMBERSHIP STATUS</b></td>
			        <td><?php echo $record_status; ?></td>
			      </tr>				      		      
			    </tbody>
			  </table>	
			  <p>
				<table class="table table-bordered">
				    <tbody>		
				      <tr>
				        <td width="70%"><b>Have ever plead guilty?</b></td>
				        <td>
				        	<?php 
				        		if($is_plead_guilty=="1"){
				        			echo "Yes";
				        		}
				        		else{
				        			echo "No";
				        		}
				        	?>		        	
				        </td>
				      </tr>	

				      <tr>
				        <td width="70%"><b>Have ever been declared bankrupt?</b></td>
				        <td>
				        	<?php 
				        		if($is_bankrupt=="1"){
				        			echo "Yes";
				        		}
				        		else{
				        			echo "No";
				        		}
				        	?>
				        </td>
				      </tr>	

				      <tr>
				        <td width="70%"><b>Legally entitled to work in Canada?</b></td>
				        <td>
				        	<?php 
				        		if($is_legal_to_work=="1"){
				        			echo "Yes";
				        		}
				        		else{
				        			echo "No";
				        		}
				        	?>
				        </td>
				      </tr>	

				      <?php
				      if($status == "2" && $info_type=="downline"){
						echo "
						      <tr>
						      	<td><div id='paypal-button'></div></td>
						      </tr>
						";
				      }
				      ?>
				    </tbody>
				</table>
			</p>
		</div>	
	</div>	
</div>
<br/>&nbsp;
<br/>&nbsp;