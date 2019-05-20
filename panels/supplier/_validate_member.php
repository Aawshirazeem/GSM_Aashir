<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	if(!$supplier->isLogedin())
	{
		header('location:' . CONFIG_PATH_SITE_SUPPLIER . 'login.html?reply=' . urlencode('reply_se_tout'));
		exit();
	}
?>