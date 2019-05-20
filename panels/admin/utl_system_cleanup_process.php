<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$tables = array('nxt_api_error_log', 'nxt_stats_admin_login_master', 'nxt_stats_user_login_master', 'nxt_user_register_master');
	
	if(isset($_POST['tbls']))
	{
		$tbls = $_POST['tbls'];
		foreach($tbls as $tbl)
		{
			if (in_array($tbl, $tables))
			{
				$sql = 'truncate table ' . $tbl . ';';
				$mysql->query($sql);
			}
		}
		header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_system_cleanup.html?reply=" . urlencode('reply_done'));
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_system_cleanup.html?reply=" . urlencode('reply_no_selection'));
	}
	exit();
?>