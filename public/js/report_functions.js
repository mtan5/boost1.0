$(document).ready(function(){
    $("#save_commission_pdf").click(function(){
        action = $("#search_commission_form").attr("action");
        $("#search_commission_form").attr("action", action+"_pdf");
        $("#search_commission_form").submit();
        $("#search_commission_form").attr("action", action);
    }); 

    $("#save_downline_report_pdf").click(function(){
        action = $("#search_dr_form").attr("action");
        $("#search_dr_form").attr("action", action+"_pdf");
        $("#search_dr_form").submit();
        $("#search_dr_form").attr("action", action);
    });               
});