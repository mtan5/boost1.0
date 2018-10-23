$(document).ready(function(){
    //var host=window.location.host;
    //var protocol=window.location.protocol;
    //var appname="boost";
    //var url=protocol+"//"+host+"/"+appname;
    //!!!variables are defined in url_config.js!!!////
        
    $(".delete_statement").click(function(){
        var ans = confirm("Delete selected statement?");
        if(ans){                
            id = $(this).val();
            window.location.href=url+"/commission/delete_commission_statement/" + id;
        }
    }); 
            
    $('.approve_req').click(function(){   
        var ans = confirm("Approve Member request?");             
        if(ans){
            id = $(this).val();
            window.location.href=url+"/admin/approve_member/" + id;        
        }
    });  

    $(".next_page").click(function(){
        page_num = $(this).html();
        $("#page_number").val(page_num);
        $("#search_member_form").submit();        
    });  

    $("#search_member_btn").click(function(){                     
        $("#search_member_form").submit();
    });

    $('.set_username').click(function(){   
        var username_id = $(this).val();
        var details = username_id.split("_");
        username = details[1];
        member_id = details[0];
        member_name = $("#member_name_" + member_id).html();
        $("#member_id").html(member_id);
        $("#username").val(username);
        $("#process").val("edit");
        $("#member_name").html(member_name);
        if(username=="")$("#process").val("new");
    });  

    $("#save_access").click(function(){                     
        member_id = $("#member_id").html();
        username =  $("#username").val();
        password =  $("#password").val();
        process =  $("#process").val();
        if(username!="" && password!=""){
            var ans = confirm("Save Access for "+ username +"?");   
            if(ans){        
                $.ajax({ 
                   type : "POST",                
                   dataType:'json',
                   url: url + '/admin/saveAccess', 
                   cache : false, 
                   data: { 
                        'username':username,
                        'password':password,
                        'member_id':member_id,
                        'process':process
                   },                                 
                   success : function(response){                  
                        result = response['result'];
                        if(result=="1"){
                            alert("Setting Access for "+ username +" was successful!");
                            window.location.href=url+"/admin/list/";
                        }
                        else if(result=="2"){
                            alert("Username "+ username +" already exists!");
                        }
                        else{
                            alert("Setting Access failed!");
                        }
                    },
                    error: function (request, status, error) {
                        alert("Ajax: " + status + " " + error);
                    }                  
                });                
            }        
        }
    });    

    $('#save_new_company').click(function(){            
        var name = $("#new_company_name").val(); 
        if(name!=""){
            var ans = confirm("Save New Company?");   
            if(ans){
                    $.ajax({ 
                       type : "POST",                
                       dataType:'json',
                       url: url + '/company/addNew', 
                       cache : false, 
                       data: { 
                           'company_name': name
                       },                                 
                       success : function(response){                  
                            result = response['result'];
                            if(result == "1"){
                                alert("Saving "+ name +" was successful");
                                window.location.href=url+"/company/list/";
                            }
                            else if(result == "2"){
                                alert("Saving "+ name +" failed. Company name already exists!");
                            }
                            else if(result == "3"){
                                alert("Your Session has expired, Please login again!");
                            }                            
                            else{
                                alert("Saving new company failed! Please contact administrator!");                             
                            }
                        },
                        error: function (request, status, error) {
                            alert("Ajax: " + status + " | " + error);
                        }                  
                    }); 
            }
        }        
    });

    $('#save_name_changes').click(function(){            
        var name = $("#edit_company_name").val();
        var company_id = $("#company_rec_id").html();
        if(name!=""){
            var ans = confirm("Change company name to "+name+"?");   
            if(ans){
                    $.ajax({ 
                       type : "POST",                
                       dataType:'json',
                       url: url + '/company/save', 
                       cache : false, 
                       data: { 
                           'company_name': name,
                           'company_id':company_id,
                           'status':1
                       },                                 
                       success : function(response){                  
                            result = response['result'];
                            if(result == "1"){
                                alert("Saving "+ name +" was successful");
                                window.location.href=url+"/company/list/";
                            }
                            else if(result == "3"){
                                alert("Your Session has expired, Please login again!");
                            }                             
                            else{
                                alert("Saving company name failed! Please contact administrator!");                             
                            }
                        },
                        error: function (request, status, error) {
                            alert("Ajax: " + status + " | " + error);
                        }                  
                    }); 
            }
        }        
    });

    $('.deactivate_member').click(function(){   
        var ans = confirm("Deactivate Selected Member?");             
        if(ans){
            id = $(this).val();
            window.location.href=url+"/member/deactivate_member/" + id;        
        }
    }); 

    $('.activate_member').click(function(){   
        var ans = confirm("Activate Selected Member?");             
        if(ans){
            id = $(this).val();
            window.location.href=url+"/member/activate_member/" + id;        
        }
    });     

    $('.delete_company').click(function(){   
        var ans = confirm("Delete selected company?");             
        if(ans){
            id = $(this).val();
            window.location.href=url+"/company/delete/" + id;        
        }
    });      

    $('.heirarchy').click(function(){          
        var id = $(this).val();
        window.location.href=url+"/member/heirarachy/" + id;    
    }); 

    $('.edit_company').click(function(){          
        var id = $(this).val();
        var name = $("#name_" + id).html();
        if(id==""){
            id="0000";                
        }
        $("#edit_company_name").val(name);         
        $("#company_rec_id").html(id);         
    });                  
});
