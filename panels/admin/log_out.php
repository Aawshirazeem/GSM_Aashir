<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->logout();
	header('location:' . CONFIG_PATH_SITE_ADMIN . 'index.html?reply=' . urlencode('reply_signed_out'));
?>