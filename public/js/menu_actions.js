$(document).ready(function(){
    //var host=window.location.host;
    //var protocol=window.location.protocol;
    //var appname="boost";
    //var url=protocol+"//"+host+"/"+appname;
    //!!!variables are defined in url_config.js!!!////


    $("#logout_button").click(function(){
    	ans = confirm("Are you sure you want to log out from boost MLM?");
    	if(ans){
    		window.location.href=url+'/home/logout';
    	}
    }); 

    //admin menu
    $(".levels").click(function(){
        window.location.href=url+'/admin/levels';
    }); 

    $("#admin_home").click(function(){
        window.location.href=url+'/admin';
    }); 

    $(".add_member").click(function(){
        window.location.href=url+'/member/form';
    });   

    $(".member_list").click(function(){
        window.location.href=url+'/admin/list';
    });   

    $(".member_request").click(function(){        
        window.location.href=url+'/admin/requests';
    });  

    $('.company_list').click(function(){   
        window.location.href=url+"/company/list/";
    });   

    $('.product_list').click(function(){   
        window.location.href=url+"/product/list/";
    });

    $('.product_sold').click(function(){   
        window.location.href=url+"/product/sold/";
    });      

    $('.new_insurance_commission').click(function(){   
        window.location.href=url+"/commission/insurance_form/";
    });

    $(".commission_list").click(function(){
        window.location.href=url+'/commission/list';
    });   

    $(".commission_statement").click(function(){
        window.location.href=url+'/reports/commission_statement';
    });

    $(".downline_report").click(function(){
        window.location.href=url+'/reports/downline_report';
    });    

    $(".rel_statements").click(function(){
        window.location.href=url+'/commission/released_statements';
    });    

    //member menu
    $(".profile").click(function(){
        window.location.href=url+"/member/info";
    }); 

    $("#home").click(function(){
        window.location=url+"/member";
    }); 

    $(".commissions").click(function(){
        window.location=url+"/commission/list";
    });         

    $(".member_sales").click(function(){
        id = $(this).val();        
        window.location.href=url+"/product/sold/"+id;
    });         

    $(".tree").click(function(){
    	window.location.href=url+'/member/heirarachy/';
    }); 

    $(".edit_loggedin_member_info").click(function(){
        window.location.href=url+'/member/form/edit';
    });    

    $(".member_rel_statements").click(function(){
        window.location.href=url+'/commission/released_statements';
    });           
                 
});