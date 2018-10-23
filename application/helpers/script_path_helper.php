<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('script_path_helper'))
{
    function main_template_path()
    {
        return base_url().'public/css/main_template.css';
    }

    function bootstrap_css_path()
    {
        return base_url().'public/bootstrap/css/bootstrap.min.css';
    }  
     
    function bootstrap_css_theme_path()
    {
        return base_url().'public/bootstrap/css/bootstrap-theme.css';
    }  

    function bootstrap_js_path()
    {
        return base_url().'public/bootstrap/js/bootstrap.min.js';
    }  

    function url_config_path()
    {
        return base_url().'public/js/url_config.js';
    }            

    function menu_actions_path()
    {
        return base_url().'public/js/menu_actions.js';
    }  

    function jquery_path()
    {
        return base_url().'public/js/jquery-3.2.1.min.js';
    }

    function jqueryui_path()
    {
        return base_url().'public/jqueryui/js/jquery-ui-1.9.2.custom.js';
    }    

    function jqueryuicss_path()
    {
        return base_url().'public/jqueryui/css/base/jquery-ui-1.9.2.custom.css';
    }           

    function function_lib_path(){
        return base_url().'public/js/function_lib.js';
    }

    function member_functions_path(){
        return base_url().'public/js/member_functions.js';
    } 

    function admin_functions_path(){
        return base_url().'public/js/admin_functions.js';
    } 

    function product_functions_path(){
        return base_url().'public/js/product_functions.js';
    }     

    function commission_functions_path(){
        return base_url().'public/js/commission_functions.js';
    }   

    function report_functions_path(){
        return base_url().'public/js/report_functions.js';
    }    

    function jstree_path(){
        return base_url().'public/jstree/jstree.min.js';
    }                

    function jstree_style_path(){
        return base_url().'public/jstree/themes/default/style.min.css';
    }     

    function get_default_script_libraries(){
        return array(
                'main_template_path' => main_template_path(),
                'bootstrap_css_path'=>bootstrap_css_path(),
                'bootstrap_css_theme_path'=>bootstrap_css_theme_path(),
                'bootstrap_js_path'=>bootstrap_js_path(),
                'menu_actions_path'=>menu_actions_path(),            
                'jquery_path'=>jquery_path(),
                'url_config_path'=>url_config_path()
                );  
    }                    
}
