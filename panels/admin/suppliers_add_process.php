<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('suppliers_add_549883whh2');
	
    $username = $request->PostStr('username');
    $password = $request->PostStr('password');
    $email = $request->PostStr('email');
    $credits = $request->PostFloat('credits');
    $service_imei = $request->PostInt('service_imei');
    $service_file = $request->PostInt('service_file');
    $service_logs = $request->PostInt('service_logs');
    $service_shop = $request->PostInt('service_shop');
    $status = $request->PostInt('status');
    $first_name = $request->PostStr('first_name');
    $last_name = $request->PostStr('last_name');
    $company = $request->PostStr('company');
    $address = $request->PostStr('address');
    $city = $request->PostStr('city');
    $language = $request->PostInt('language');
    $timezone = $request->PostInt('timezone');
    $country = $request->PostInt('country');
    $phone = $request->PostStr('phone');
    $mobile = $request->PostStr('mobile');

		
	if($username == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_add.html?reply=" . urlencode('reply_suppliers_missing'));
		exit();
	}
	if($password == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_add.html?reply=" . urlencode('reply_suppliers_password_missing'));
		exit();
	}
	if($email == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_add.html?reply=" . urlencode('reply_suppliers_email_missing'));
		exit();
	}
	
	$sql = 'select username from ' . SUPPLIER_MASTER . ' where username=' . $mysql->quote($username);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_add.html?reply=" . urlencode('reply_suppliers_user_duplicate'));
		exit();
	}
	
	
	$sql = 'select username from ' . SUPPLIER_MASTER . ' where email=' . $mysql->quote($email);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_add.html?&reply" . urlencode("reply_suppliers_email_duplicate"));
		exit();
	}

	$keyword = new keyword();
    $key = $request->GetStr('key');
    
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
	
	$sql = 'insert into ' . SUPPLIER_MASTER . '
			(username, password, login_key, email, reseller_id, user_type, service_imei, service_file, service_logs, service_shop, 
				status, first_name, last_name, company, address, country_id)
			values(
			' . $mysql->quote($username) . ',
			' . $mysql->quote($objPass->generate($password)) . ', 
			' . $mysql->quote($keyNew) . ',
			' . $mysql->quote($email) . ', 
			' . $admin->getUserId() . ', 
			0, 
			' . $mysql->getInt($service_imei) . ', 
			' . $mysql->getInt($service_file) . ' , 
			' . $mysql->getInt($service_logs) . ', 
			' . $mysql->getInt($service_shop) . ',
			' . $mysql->getInt($status) . ', 
			' . $mysql->quote($first_name) . ', 
			' . $mysql->quote($last_name) . ', 
			' . $mysql->quote($company) . ', 
			' . $mysql->quote($address) . ', 
			' . $mysql->getInt($country) . ')';

	$mysql->query($sql);
	
	$id = $mysql->insert_id();
	
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

	$objEmail->sendEmailTemplate('admin_supplier_add', $args);

	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers.html?reply=" . urlencode('reply_success'));
	
	
	
?>