<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$res = $supplier->login($_SESSION['tempUsername'],$_SESSION['tempPassword']);
	unset($_SESSION['tempUsername']);
	unset($_SESSION['tempPassword']);
	if($res == true)
	{
		header("location:" . CONFIG_PATH_SITE_SUPPLIER . "dashboard.html");
		exit();
	}
	elseif($res == false)
	{
		header("location:" . CONFIG_PATH_SITE_SUPPLIER . "index.html?reply=" . urlencode('reply_in_pass'));
		exit();
	}
?>