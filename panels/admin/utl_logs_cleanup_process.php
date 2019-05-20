<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	//$tables = array('nxt_api_error_log', 'nxt_stats_admin_login_master', 'nxt_stats_user_login_master', 'nxt_user_register_master');
	//var_dump($_GET);exit;
	if(isset($_REQUEST['id']))
	{
		//$tbls = $_POST['tbls'];
            
            
            
            $idd=$_REQUEST['id'];
            
            if($idd==1)
            {
                
               file_put_contents(CONFIG_PATH_LOGS_ABSOLUTE . "/sql_error.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
            else if($idd==2)
            {
                file_put_contents(CONFIG_PATH_LOGS_ABSOLUTE . "/email_queue.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==3)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "dhruR.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==4)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "TimeoutErrorLog.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==5)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "sql_del.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==6)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==7)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
             else if($idd==8)
            {
                file_put_contents(CONFIG_PATH_SITE_ABSOLUTE . "paypal.log", ""); 
               header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_done'));
            }
 else {
     header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_no_selection'));
 }
		
		
            
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_logs_cleanup.html?reply=" . urlencode('reply_no_selection'));
	}
	exit();
?>