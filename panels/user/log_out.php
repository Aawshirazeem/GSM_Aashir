<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->logout();
	header('location:' . CONFIG_PATH_SITE . 'login.html?reply=' . urlencode('reply_logut_success'));
?>