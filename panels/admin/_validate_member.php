<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	if(!$admin->isLogedin())
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'login.html?msg=' . urlencode('Session timeout! Please sign in again.'));
		exit();
	}
?>