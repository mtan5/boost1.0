$(document).ready(function(){
    //var host=window.location.host;
    //var protocol=window.location.protocol;
    //var appname="boost";
    //var url=protocol+"//"+host+"/"+appname;
    //!!!variables are defined in url_config.js!!!////

    $('.delete_prod').click(function(){   
        var ans = confirm("Delete selected product?");             
        if(ans){
            id = $(this).val();
            window.location.href=url+"/product/delete/" + id;        
        }
    });    

    $('#save_new_product').click(function(){            
        var new_company = $("#new_company").val(); 
        var new_product_name = $("#new_product_name").val(); 
        var new_fyc = $("#new_fyc").val(); 
        var new_prod_type = $("#new_prod_type").val(); 

        if(new_product_name!="" && new_fyc!=""){
            var ans = confirm("Save New Product?");   
            if(ans){
                    $.ajax({ 
                       type : "POST",                
                       dataType:'json',
                       url: url + '/product/addNew', 
                       cache : false, 
                       data: { 
                           'company_id': new_company,
                           'product_name':new_product_name,
                           'fyc':new_fyc,
                           'prod_type':new_prod_type
                       },                                 
                       success : function(response){                        
                            result = response['result'];
                            if(result == "1"){
                                alert("Saving "+ new_product_name +" was successful.");
                                window.location.href=url+"/product/list/";
                            }
                            else if(result == "2"){
                                alert("Saving "+ new_product_name +" failed. Product name already exists!");
                            }
                            else if(result == "3"){
                                alert("Your Session has expired, Please login again!");
                            }                            
                            else{
                                alert("Saving new product failed! Please contact administrator!");                             
                            }
                        },
                        error: function (request, status, error) {
                            alert("Ajax: " + status + " | " + error);
                        }                  
                    }); 
            }
        }        
    });

    $('#save_product_changes').click(function(){            
        var edit_product_id = $("#product_id").html(); 
        var edit_product_name = $("#edit_prod_name").val(); 
        var edit_fyc = $("#edit_fyc").val();
        var edit_company_id = $("#edit_company").val();
        var edit_prod_type = $("#edit_prod_type").val(); 

        if(edit_product_name!="" && edit_fyc!=""){
            var ans = confirm("Save product changes?");   
            if(ans){
                    $.ajax({ 
                       type : "POST",                
                       dataType:'json',
                       url: url + '/product/save', 
                       cache : false, 
                       data: { 
                           'product_name':edit_product_name,
                           'product_id':edit_product_id,
                           'company_id':edit_company_id,
                           'fyc':edit_fyc,
                           'prod_type':edit_prod_type,
                           'status':1
                       },                                 
                       success : function(response){                     
                            result = response['result'];
                            if(result == "1"){
                                alert("Saving "+ edit_product_name +" was successful.");
                                window.location.href=url+"/product/list/";
                            }
                            else if(result == "3"){
                                alert("Your Session has expired, Please login again!");
                            }                            
                            else{
                                alert("Saving new product failed! Please contact administrator!");                             
                            }
                        },
                        error: function (request, status, error) {
                            alert("Ajax: " + status + " | " + error);
                        }                  
                    }); 
            }
        }        
    });

    $('.edit_prod').click(function(){          
        var prod_company_id = $(this).val();
        var prod_name = "000";
        var fyc = "000";
        var company_id="000";
        var product_id="000";

        if(prod_company_id!=""){
            details = prod_company_id.split("_");            
            company_id=details[1];
            product_id=details[0];
            product_type_id=details[2];
            prod_name = $("#product_" + product_id).html();
            fyc = $("#fyc_" + product_id).html();            
        }

        $("#edit_fyc").val(fyc);    
        $("#edit_prod_name").val(prod_name);    
        $("#product_id").html(product_id);    
        $("#edit_company").val(company_id); 
        $("#edit_prod_type").val(product_type_id);
    });  

    $(".view_member").click(function(){                     
        id = $(this).val();
        window.location.href=url+"/admin/view_member/" + id;
    }); 

    $(".edit_member").click(function(){                     
        id = $(this).val();
        window.location.href=url+"/admin/edit_member/" + id;
    });  


    $(".next_page").click(function(){
        page_num = $(this).html();
        $("#page_number").val(page_num);
        $("#search_product_form").submit();        
    });

    $(".edit_policy_details").click(function(){
        plcy_details = $(this).val();
        details = plcy_details.split("|");

        $("#edit_policy_id").val(details[0]);
        $("#policy_number").val(details[1]);
        $("#date_purchased").val(details[2]);
        $("#company_label").html(details[3]);
        $("#product_label").html(details[4]);
        $("#annual_prem").html(details[5]);
        $("#monthly_prem").html(details[6]);
        $("#client").val(details[7]);
    });  	

    $("#save_policy_changes").click(function(){
        policy_number = $("#policy_number").val();
        edit_policy_id = $("#edit_policy_id").val();
        date_purchased = $("#date_purchased").val();
        client = $("#client").val();
        if(policy_number!="" || date_purchased!="" || edit_policy_id!=""){
            ans = confirm("Do you wish to save policy details changes?");
            if(ans){
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/product/savePolicyDetailsChanges', 
                   cache : false, 
                   data: { 
                        'edit_policy_id':edit_policy_id,
                        'policy_number':policy_number,              
                        'date_purchased':date_purchased,
                        'client':client
                   },                                 
                   success : function(response){
                        if(jQuery.isEmptyObject(response['result'])==1){
                            alert("Saving changes was successful!");
                            location.reload();
                        }
                        else{
                            alert("Saving changes failed! Please contact administrator...");                               
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " | " + error);
                    }                  
                });
            }
        } 
        else{
            alert("Can't proceed with incomplete details.");            
        }
    }); 

    $(".get_policy_commissions").click(function(){
        plcy_details = $(this).val();
        details = plcy_details.split("|");

        $("#comm_policy_id").val(details[0]);
        $("#comm_policy_number").html(details[1]);
        $("#comm_date_purchased").html(details[2]);
        $("#comm_company_label").html(details[3]);
        $("#comm_product_label").html(details[4]);
        $("#comm_annual_prem").html(details[5]);
        //$("#monthly_prem").html(details[6]);
        $("#comm_client").html(details[7]);
        $("#comm_prod_id").val(details[8]);
        $("#policy_sale_id").val(details[9]);
        $("#comm_count").val(0);

        product_id = details[8];
        policy_id = details[0];        
        $.ajax({ 
           type : "POST",                
           dataType:'json',
           url: url + '/commission/get_policy_commission', 
           cache : false, 
           data: { 
                'policy_id':policy_id,
                'product_id':product_id
           },                                 
           success : function(response){            
                $('#tbl_commissions > tbody').empty();
                if(response.length > 0){
                    var arrayLength = response.length;   
                    $("#comm_count").val(arrayLength);                                     
                    for (var i = 0; i < arrayLength; i++) {                          
                        $('#tbl_commissions > tbody').append("<tr><td><input type='hidden' value='" + response[i].commission_type_id + "' id='commission_type_id_" + i +"'><input type='hidden' value='" + response[i].member_level_id + "' id='charged_back_member_lvl_id_" + i +"'><input type='hidden' value='" + response[i].member_id + "' id='charged_back_member_id_" + i +"'>" + response[i].first_name + " " + response[i].last_name +"</td><td>" + response[i].commission_fyc + "</td><td>" + response[i].commission_amt_bonus + "</td><td>" + response[i].commission_type + "</td><td><input type='text' value='' class='form-control datepicker' id='charge_back_amt_" +  i +"'/></td></tr>");
                    }                    
                }
                else{
                    alert("No commission records were retrieved.");                               
                }
            },
            error: function (request, status, error) {
                alert("Ajax: " + status + " | " + error);
            }                  
        });
    });

    $("#cancel_policy").click(function(){
        cancel_policy_sale_id = $("#policy_sale_id").val();
        cancel_prod_id = $("#comm_prod_id").val();
        comm_count = $("#comm_count").val();
        cancel_policy_id = $("#comm_policy_id").val();
        charge_backs = "";

        ans = confirm("Do you really want to cancel this policy?");
        proceedSubmit = true;
        if(ans){
            for(var i = 0; i < comm_count; i++){
                member_id=$("#charged_back_member_id_" + i).val();
                member_lvl_id=$("#charged_back_member_lvl_id_" + i).val();
                charge_back_amt=$("#charge_back_amt_" + i).val();
                commission_type_id = $("#commission_type_id_" + i).val();

                if(charge_back_amt!=""){
                    if($.isNumeric(charge_back_amt)){
                        proceedSubmit = true;
                    } 
                    else{
                        proceedSubmit = false;
                        alert(charge_back_amt + " is not a number!");
                        $("#charge_back_amt_" + i).val("");
                    }                
                }
                else{
                    charge_back_amt = "0";
                }
                if(charge_back_amt!=0)charge_backs = charge_backs + member_id + "|" + member_lvl_id + "|" + charge_back_amt + "|" + commission_type_id +";"            
            }
            
            if(charge_backs!="")charge_backs = charge_backs.slice(0, -1);
          
            if(proceedSubmit){
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/product/cancel_policy_product', 
                   cache : false, 
                   data: { 
                        'cancel_policy_sale_id':cancel_policy_sale_id,
                        'cancel_prod_id':cancel_prod_id,
                        'charge_backs':charge_backs,
                        'cancel_policy_id':cancel_policy_id
                   },                                 
                   success : function(response){            
                        if(jQuery.isEmptyObject(response['result'])==1){
                            alert("Cancelling policy was successful!");
                            location.reload();                
                        }
                        else{
                            alert("Cancelling Policy Failed.");  
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " | " + error);
                    }                  
                });
            }
        }
    });


    $(".delete_policy").click(function(){
        policy_id = $(this).val();
        ans = confirm("Are you sure you want to delete this policy? Doing this will also remove all the commissions from this policy.");
        if(ans){
            $.ajax({ 
               type : "POST",                
               dataType:'json',
               url: url + '/product/delete_policy', 
               cache : false, 
               data: { 
                    'policy_id':policy_id
               },                                 
               success : function(response){
                    if(jQuery.isEmptyObject(response['result'])==1){
                        alert("Deleting policy was successful!");
                        location.reload();
                    }
                    else{
                        alert("Deleting policy failed! Please contact administrator...");                               
                    }
                },
                error: function (request, status, error) {
                    alert("Ajax: " + status + " | " + error);
                }                  
            });
        }
    });           
});