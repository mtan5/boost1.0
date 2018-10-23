$(document).ready(function(){
    //var host=window.location.host;
    //var protocol=window.location.protocol;
    //var appname="boost";
    //var url=protocol+"//"+host+"/"+appname;
    //!!!variables are defined in url_config.js!!!////

    $(".show_member").click(function(){
        $(".rd_member").prop('checked', false);
        num = $(this).val();
        $("#agent_num").val(num);
    });     

    $(document).on('change', '#select_chkbx',function(){
        if(this.checked) {
            $('input:checkbox').prop('checked',true);
        } 
        else{
            $('input:checkbox').prop('checked',false);
        }
    });         

    $("#bulk_change").click(function(){
        selected = "";
        comm_status = $("#comm_status").val();
        $("#selected_ids").val("");
        $( ".commission_checkbox" ).each(function( index ) {                        
            if($(this).prop('checked')){
                selected = selected + $(this).val() + ",";
            }
        });  

        if(selected != "")
        {
            if(comm_status!=""){
                selected = selected.slice(0,-1); //remove last comma(,)
                $("#selected_ids").val(selected);
                var ans = confirm("Are you sure you want to save changes to the selected records?");
                if(ans){
                    $("#change_commission_status_form").submit();
                }                
            }
            else{
                alert("Please select a commission status you wish to apply.");    
            }
        }
        else{
            alert("Please select some commission records.");
        }              
    });

    $("#save_commission_change").click(function(){
        comm_fyc = $("#comm_fyc").val();
        comm_bonus = $("#comm_bonus").val();
        commission_id = $("#commission_id").val();
        commission_status = $("#edit_status").val();
        if($.isNumeric(comm_fyc) && $.isNumeric(comm_bonus)){
            ans = confirm("Save commission amount change?");
            if(ans){
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/commission/saveCommissionAmountChange', 
                   cache : false, 
                   data: { 
                        'commission_id':commission_id,
                        'comm_fyc':comm_fyc,  
                        'comm_bonus':comm_bonus,  
                        'commission_status':commission_status
                   },                                 
                   success : function(response){
                        if(jQuery.isEmptyObject(response['result'])==1){
                            alert("Saving amount changes was successful!");
                            location.reload();
                        }
                        else{
                            alert("Saving amount changes failed! Please contact administrator...");                               
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " | " + error);
                    }                  
                });
            }
        } 
        else{
            alert("Commission Amount: " + comm_amt + " is not a number!");
            $("#commission_id").val("");
        }
    });     

    $("#AddCommissionBtn").click(function(){
        //clear values
        $("#new_policy").val("");
        $("#new_member").val("");
        $("#new_comm_fyc").val("");
        $("#new_comm_bonus").val(""); 
        $("#new_commission_status").val("");        
    });   

    $("#SaveNewCommissionBtn").click(function(){    
        proceed = true;

        new_policy = $("#new_policy").val();
        if(new_policy==""){
            proceed = false;
            alert("Please select a policy");
        }

        new_member = $("#new_member").val();
        if(new_member==""){
            proceed = false;
            alert("Please select an advisor");
        }

        new_comm_fyc = $("#new_comm_fyc").val();
        if(new_comm_fyc==""){
            proceed = false;
            alert("Please specify a FYC commission");
        }
        else{
            if($.isNumeric(new_comm_fyc)){
                proceed = true;
            } 
            else{
                proceed = false;
                alert("FYC: " + new_comm_fyc + " is not a number!");
                $("#new_comm_fyc").val("");
            } 
        }


        new_comm_bonus = $("#new_comm_bonus").val(); 
        if(new_comm_bonus==""){
            $("#new_comm_bonus").val("0");
        }
        else{
            if($.isNumeric(new_comm_bonus)){
                proceed = true;
            } 
            else{
                proceed = false;
                alert("FYC: " + new_comm_bonus + " is not a number!");
                $("#new_comm_bonus").val("");
            } 
        }

        new_commission_status = $("#new_commission_status").val();        
        if(new_commission_status==""){
            proceed = false;
            alert("Please select a commission status");
        }

        new_commission_type = $("#new_commission_type").val();        
        if(new_commission_type==""){
            proceed = false;
            alert("Please select a commission type");
        }

        if(proceed){
            ans = confirm("would you like to add new commission?");
            if(ans){
                $("#add_commission_form").submit();
            }
        }

    });   

    $(".edit_commission").click(function(){
        comm_details = $(this).val();
        details = comm_details.split("|");

        $("#policy_label").html(details[5]);
        $("#agent_name_label").html(details[1] + " " + details[2]);
        $("#agent_level_label").html(details[3]);
        $("#company_label").html(details[4]);
        $("#product_label").html(details[6]);
        $("#date_label").html(details[7]);
        $("#comm_fyc").val(details[8]);
        $("#commission_id").val(details[0]);
        $('#edit_status').val(details[9]);
        $("#comm_bonus").val(details[10]);
    });   

    $(".view_policy_details").click(function(){
        plcy_details = $(this).val();
        details = plcy_details.split("|");

        $("#view_policy_number").html(details[0]);
        $("#view_date_purchased").html(details[1]);
        $("#view_company_label").html(details[2]);
        $("#view_product_label").html(details[3]);
        $("#view_annual_prem").html("$" + details[4].toLocaleString('en'));
        $("#view_monthly_prem").html("$" + details[5].toLocaleString('en'));
    });       

    $(".show_trainee").click(function(){
        $(".rd_trainee").prop('checked', false);
        num = $(this).val();
        $("#trainee_num").val(num);        
    });  

    $(".clear_agent_input").click(function(){
        idToClear = $(this).val();
        $("#agent_name_" + idToClear).val("");
        $("#agent_id_" + idToClear).val("");        
    }); 

    $(".next_page").click(function(){
        page_num = $(this).html();
        $("#page_number").val(page_num);
        $("#search_commission_form").submit();        
    });  

    $(".clear_trainee_input").click(function(){
        idToClear = $(this).val();
        $("#trainee_name_" + idToClear).val("");
        $("#trainee_id_" + idToClear).val("");        
    });          

    $("#set_agent").click(function(){        
        num = $("#agent_num").val();
        selected_member = $('input[name=member_agent]:radio:checked').val();       
        details = selected_member.split("_");
        $("#agent_id_"+ num).val(details[0]);
        $("#agent_name_"+ num).val(details[1] + " " + details[2]);

        //clear trainee fields
        $("#trainee_name_" + num).val("");
        $("#trainee_id_" + num).val("");  
    }); 

    $("#set_trainee").click(function(){        
        num = $("#trainee_num").val();
        selected_trainee = $('input[name=trainee_agent]:radio:checked').val();       
        details = selected_trainee.split("_");
        $("#trainee_id_"+ num).val(details[0]);
        $("#trainee_name_"+ num).val(details[1] + " " + details[2]);

        //clear advisor fields
        $("#agent_name_" + num).val("");
        $("#agent_id_" + num).val("");           
    }); 

    $("#save_policy").click(function(){
        policy_number = $("#policy_number").val();
        date_purchased = $("#date_purchased").val();
        selected_products = $("#selected_products").val();
        policy_client = $("#policy_client").val();
        proceed = true;

        $("#policy_number_label").html("");
        $("#client_label").html("");
        $("#date_purchased_label").html("");
        $("#products_label").html("");  

        $(".prod_prem").each(function() { 
            monthly_prem = $(this).val();
            prod_id = $(this).attr('id');          
            selected = prod_id + "=" + monthly_prem;
            
            if(monthly_prem!=""){
                if(selected_products!=""){
                    selected_products = selected_products + "," + selected;
                }
                else{
                    selected_products = selected;
                }
            }
        }); 
        $("#selected_products").val(selected_products);    

        if(policy_number==""){
            alert("Please provide a policy number.");
            $("#policy_number_label").html("Please provide a policy number.");
            proceed = false;
        }

        if(date_purchased==""){
            alert("Please provide the date purchased of the policy.");
            $("#date_purchased_label").html("Please provide the date purchased of the policy.");
            proceed = false;
        }

        if(selected_products==""){
            alert("Please provide some insurance product for this policy.");
            $("#products_label").html("Please provide some insurance product for this policy.");
            proceed = false;
        }

        if(policy_client==""){
            alert("Please provide a client.");
            $("#client_label").html("Please provide a client."); 
            proceed = false;
        }                        

        if(proceed){
            var ans = confirm("Save policy details?");
            if(ans){
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/product/ifPolicyNumberExists', 
                   cache : false, 
                   data: { 
                       'policy_number': policy_number
                   },                                 
                   success : function(response){                        
                        result = response['result'];
                        if(result == "1"){
                            alert( policy_number +" already exists in our records.");
                        }
                        else{
                            $("#commission_form").attr('action', url+'/product/save_new_policy');
                            $("#commission_form").attr('target', '');
                            $("#commission_form").submit();
                        }
                    },
                    error: function (request, status, error) {
                        alert("ifPolicyNumberExists Ajax: " + status + " | " + error);
                    }                  
                });            
            }
        }
    });

    $("#pre_compute_btn").click(function(){  
        var selected_products = "";
        proceedSubmit = false;
        $(".prod_prem").each(function() { 
            monthly_prem = $(this).val();
            prod_id = $(this).attr('id');          
            selected = prod_id + "=" + monthly_prem;
            
            if(monthly_prem!=""){
                if(selected_products!=""){
                    selected_products = selected_products + "," + selected;
                }
                else{
                    selected_products = selected;
                }
            }
            
            if(monthly_prem!="") {
                if($.isNumeric(monthly_prem)){
                    proceedSubmit = true;
                } 
                else{
                    proceedSubmit = false;
                    alert("Premium: " + monthly_prem + " is not a number!");
                    $(this).val("");
                }
            }
        });

        policy_number = $("#policy_number").val();
        if(policy_number==""){
            proceedSubmit = false;
            alert("Please provide a policy number!");
        }

        agent1 = $("#agent_name_1").val();
        agent2 = $("#agent_name_2").val();
        trainee1 = $("#trainee_name_1").val();
        trainee2 = $("#trainee_name_2").val();
        if(agent1==""){
            proceedSubmit = false;
            alert("Please provide an Agent!");
        }
        
        if(agent2!="" && (agent1==agent2)){
            proceedSubmit = false;
            alert("First agent should not be the same with the second agent!");            
        }

        if(trainee2!="" && (trainee1==trainee2)){
            proceedSubmit = false;
            alert("First trainee should not be the same with the second trainee!");            
        }        

        $("#selected_products").val("");
        $("#selected_products").val(selected_products);

        if(proceedSubmit){
            $("#commission_form").submit();
        } 
        else{
            alert("Please complete the form before submitting!");
        }
    });     

    $("#select_companies").change(function(){        
        company_id = $(this).val();
        $.ajax({ 
           type : "POST",                
           dataType:'json',
           url: url + '/product/get_products', 
           cache : false, 
           data: { 
               'company_id':company_id
           },                                 
           success : function(response){
                $("#product_table > tbody").html("");
                if(jQuery.isEmptyObject(response['result'])){
                    $("#product_table > tbody").html("<b>Sorry, No products found!</b>");
                }
                else{
                    products = response['result'];
                    for(counter in products){
                        $("#product_table > tbody").append("<tr><td>" + products[counter].prod_name + "</td><td><input type='text' class='form-control prod_prem numbersOnly' placeholder='' id='"+ products[counter].prod_id +"' value=''></td></tr>");
                    }                               
                }
            },
            error: function (request, status, error) {
                alert("Ajax: " + status + " | " + error);
            }                  
        });                 
    });                    

    $("#search_company").change(function(){        
        company_id = $(this).val();
        $.ajax({ 
           type : "POST",                
           dataType:'json',
           url: url + '/product/get_products', 
           cache : false, 
           data: { 
               'company_id':company_id
           },                                 
           success : function(response){
                $("#search_product").children('option:not(:first)').remove();
                if(jQuery.isEmptyObject(response['result'])){
                    $("#search_product")
                     .append($("<option></option>")
                     .attr("value","")
                     .text("No records.."));
                }
                else{
                    products = response['result'];
                    for(counter in products){
                        //alert(products[counter].prod_id);
                        $("#search_product")
                         .append($("<option></option>")
                         .attr("value",products[counter].prod_id)
                         .text(products[counter].prod_name)); 
                    }                               
                }
            },
            error: function (request, status, error) {
                alert("Ajax: " + status + " | " + error);
            }                  
        });
                         
    }); 

});