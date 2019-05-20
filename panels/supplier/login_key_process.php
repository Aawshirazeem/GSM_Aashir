<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

    $key = $request->GetStr('key');
	
	$res = $supplier->loginKey($key);
	
	if($res == true)
	{
		header("location:" . CONFIG_PATH_SITE_SUPPLIER . "dashboard.html");
		exit();
	}
	elseif($res == false)
	{
		header("location:" . CONFIG_PATH_SITE_SUPPLIER . "login.html?reply=" . urlencode('reply_inv_acce'));
		exit();
	}
?>