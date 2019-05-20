<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$supplier->logout();
	header('location:' . CONFIG_PATH_SITE_SUPPLIER . 'index.html?reply=' . urlencode('reply_ot_succ'));
?>