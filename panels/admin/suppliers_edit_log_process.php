<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$request = new request();
	$mysql = new mysql();
	
	$admin =  new admin();
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('suppliers_edit_54964566hh2');

    $id = $request->GetInt('id');
    $enable = $request->GetInt('enable');
	
	$sql = 'update ' . SUPPLIER_MASTER . '
			set 
			service_logs = ' . $enable . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_edit.html?id=" . $id . "&reply=" . urlencode('reply_success_suppliers_edit_log') . '#tabs-5');
	exit();
?>