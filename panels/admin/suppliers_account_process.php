<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('con_ser_log_add_1488732');

    $id = $request->PostInt('id');
	$comments = $request->PostStr('comments'); 
	$credits_to_pay = $request->PostFloat('credits_to_pay'); 

	
	if($credits_to_pay <= 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_account.html?id=".$id."&reply=" . urlencode('reply_credit_purchase_missing') . '&type=error');
		exit();
	}
	
	

	$sql = 'insert into ' . SUPPLIER_PAYMENT . '
			(supplier_id, comments, credits_paid, date_time)
			values(
			' . $id . ',
			' . $mysql->quote($comments) . ',
			' . $credits_to_pay . ',
			now())';
	$mysql->query($sql);
	
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_account.html?id=" . $id . "&reply=" . urlencode('reply_add_success'));
	exit();
?>