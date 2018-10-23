$(document).ready(function(){
    //!!!variables are defined in url_config.js!!!////
    
    $("#guilty_yes").click(function(){            	
        $('#guilty_exp').prop('disabled',false);
    });    

    $("#guilty_no").click(function(){            	
        $('#guilty_exp').prop('disabled',true);
    });    

    $("#bankrupt_yes").click(function(){            	
        $('#trustee_table').show();
    });

    $("#bankrupt_no").click(function(){            	
        $('#trustee_table').hide();
    });               

    $(".view_downline").click(function(){                     
        id = $(this).val();
        window.location.href=url+"/member/view_downline/" + id;
    });                    

    $("#SaveMemberInfoBtn").click(function(){            	
        var submit=true;
        var first_name=$("#first_name").val();
        var last_name=$("#last_name").val();
        var mid_name=$("#mid_name").val();
        var email=$("#email").val();
        var address=$("#address").val();
        var city=$("#city").val();
        var postal=$("#postal").val();
        var sin=$("#sin").val();
        var dob=$("#dob").val();
        var contact=$("#contact").val();

        if(first_name==""){
        	submit=false;
        	$("#lbl_first_name").html("Please provide first name");
        }
        else if(first_name.length > 64){
            $("#lbl_first_name").html("first name should not be more than 64 characters");   
        }
        else{
        	$("#lbl_first_name").html("");	
        }

        if(last_name==""){
        	submit=false;
        	$("#lbl_last_name").html("Please provide last name");
        } 
        else if(last_name.length > 64){
            $("#lbl_last_name").html("last name should not be more than 64 characters");   
        }        
        else{
        	$("#lbl_last_name").html("");	
        } 

        if(mid_name==""){
        	submit=false;
        	$("#lbl_mid_name").html("Please provide middle name");
        } 
        else if(mid_name.length > 64){
            $("#lbl_mid_name").html("middle name should not be more than 64 characters");   
        }                        
        else{
        	$("#lbl_mid_name").html("");	
        } 

        if(email==""){
        	submit=false;
        	$("#lbl_email").html("Please provide email");
        }  
        else if(email.length > 64){
            $("#lbl_email").html("email should not be more than 64 characters");   
        }                        
        else{
        	$("#lbl_email").html("");	
        } 

        if(address==""){
        	submit=false;
        	$("#lbl_address").html("Please provide address");
        }  
        else if(address.length > 256){
            $("#lbl_address").html("address should not be more than 256 characters");   
        }          
        else{
        	$("#lbl_address").html("");	
        }  

        if(city==""){
        	submit=false;
        	$("#lbl_city").html("Please provide city");
        } 
        else if(city.length > 32){
            $("#lbl_city").html("city should not be more than 32 characters");   
        }         
        else{
        	$("#lbl_city").html("");	
        } 

        if(postal==""){
        	submit=false;
        	$("#lbl_postal").html("Please provide postal");
        }   
        else if(postal.length > 12){
            $("#lbl_postal").html("postal should not be more than 12 characters");   
        }                     
        else{
        	$("#lbl_postal").html("");	
        }   

        if(sin==""){
        	submit=false;
        	$("#lbl_sin").html("Please provide sin #");
        } 
        else if(sin.length > 15){
            $("#lbl_sin").html("SIN should not be more than 15 characters");   
        }          
        else{
        	$("#lbl_sin").html("");	
        }                        

        if(dob=="" || !isValidDate(dob)){
        	submit=false;
        	$("#lbl_dob").html("Please provide a valid date");
        } 
        else{
        	$("#lbl_dob").html("");	
        }        

        if(contact==""){
        	submit=false;
        	$("#lbl_contact").html("Please provide contact");
        }  
        else if(contact.length > 24){
            $("#lbl_contact").html("contacts should not be more than 24 characters");   
        }                        
        else{
        	$("#lbl_contact").html("");	
        }     

        if(submit==true){
            var action = $("#new_member_form").attr('action');
            if(action.includes("addNewMember")){
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/member/ifMemberExists', 
                   cache : false, 
                   data: { 
                       'first_name': first_name, 
                       'last_name': last_name,
                       'dob': dob
                   },                                 
                   success : function(response){                  
                        ifMemberExists = response['result'];
                        if(ifMemberExists == "1"){
                            $("#lbl_record").html("");
                            $("#lbl_record").html(first_name + " " + last_name + " with the date of birth " + dob + " already exists!");
                            alert(first_name + " " + last_name + " with the date of birth " + dob + " already exists!");
                            submit = false;
                        }
                        else{     
                            submit=true;           
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " " + error);
                    }                  
                });

                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/member/ifEmailExists', 
                   cache : false, 
                   data: { 
                       'email': email
                   },                                 
                   success : function(response){                  
                        ifEmailExists = response['result'];
                        if(ifEmailExists == "1"){
                            $("#lbl_record").html("");
                            $("#lbl_record").html(email + " already exists!");
                            alert(email + " already exists!");
                            submit = false;
                        }
                        else{     
                            var ans=confirm("Do you wish to save Member's Information?");
                            if(ans){
                                $("#new_member_form").submit();
                            }
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " " + error);
                    }                  
                });
            } 
            else{            
            	process=$("#process").val();                        
            	member_id=$("#member_id").val();
            	upline_id=$("#recruiter").val();                   

            	if(process=="edit"){
        			$.ajax({ 
        			   type : "POST",                
        			   dataType:'json',
        			   url: url + '/member/checkUplineConflict', 
        			   cache : false, 
        			   data: { 
        			       'member_id': member_id, 
        			       'upline_id': upline_id
        			   },                                 
        			   success : function(response){      
        			        ifConflict = response['result'];
        			        if(ifConflict == "1"){
        			            $("#lbl_record").html("");
        			            $("#lbl_record").html("Selected Recruiter has some conflict");
        			            alert("Selected Recruiter has some conflict!");
        			        }
        			        else{
        			            var ans=confirm("Do you wish to save Member's Information?");
        			            if(ans){
        			                $("#new_member_form").submit();
        			            }                             
        			        }
        			    },
        			    error: function (request, status, error) {
        			        alert("Ajax: " + status + " " + error);
        			    }                  
        			}); 
    		    }                       
    		    else{
                        var ans=confirm("Do you wish to save Member's Information?");
                        if(ans){
                            $("#new_member_form").submit();
                        }				
    		    }                         
            }
        }
        else{
        	alert("Please complete/correct membership information.");
        }
    });

});