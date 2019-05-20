<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$objCredits = new credits();

	$admin->checkLogin();
	$admin->reject();
	
	$qStrIds = "";
	$Ids = (isset($_POST['Ids']))? $_POST['Ids'] : array() ;
	$type=$request->PostStr('type');
	$supplier_id=$request->PostInt('supplier_id');
	$search_tool_id = $request->GetInt('search_tool_id');
	$limit=$request->PostInt('limit');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');

	
	include(CONFIG_PATH_SUPPLIER_ABSOLUTE . 'order_imei_download.php');
	exit();
	
?>
