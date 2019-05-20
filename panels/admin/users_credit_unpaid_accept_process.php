<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$id = $request->GetInt('id');
	$sql_in = 'update '.INVOICE_MASTER	.' set paid_status=1 where paid_status=0 and id='.$id;
	$mysql->query($sql_in);
		
	//header("location:" . CONFIG_PATH_SITE_ADMIN . "users_credit_invoices.html?reply=" . urlencode('reply_invoice_accepted'));
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_credit_invoices.html?status=0");
	exit();
?>