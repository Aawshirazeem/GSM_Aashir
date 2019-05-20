<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$member->checkLogin();
	
    $key = $request->GetStr('key');
	$res = $member->loginKey($key);

	if($res == true)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "dashboard.html");
		exit();
	}
	elseif($res == false)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "login.html?reply=" . urlencode('reply_invalid_key'));
		exit();
	}
?>