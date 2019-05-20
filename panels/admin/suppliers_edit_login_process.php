<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('suppliers_edit_54964566hh2');

    $id = $request->PostInt('id');
	$username = $request->PostStr('username');
    $password = $request->PostStr('password');
    $status = $request->PostInt('status');
    $email = $request->PostStr('email');
    
	
	$qPassword = (trim($password) != '') ? 'password = \'' . $objPass->generate($password) . '\',' : '';
	
	$sql = 'update ' . SUPPLIER_MASTER . '
			set 
			' . $qPassword . '
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	$objEmail = new email();
	$args = array(
				'to' => $email,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'user_id' => $id,
				'save' => '1',
				'username' => $username,
				'password' => $password,
				'site_admin' => CONFIG_SITE_NAME
				);

	$objEmail->sendEmailTemplate('admin_supplier_password_change', $args);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers.html?reply=" . urlencode('reply_success_suppliers_edit_login'));
	exit();
?>