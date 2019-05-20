<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller_add.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
	
	$form_id = $request->PostStr('form_id');
	$form_key = $request->PostStr('form_key');
	
	$admin->checkLogin();
	$admin->reject();
    $validator->formValidateAdmin('config_reseller_add_148148548');
	
    $reseller = $request->PostStr('reseller');
    $type = $request->PostInt('type');
    $address = $request->PostStr('address');
    $country = $request->PostInt('country');
    $email = $request->PostStr('email');
    $mobile = $request->PostStr('mobile');
    $phone = $request->PostStr('phone');
    $website = $request->PostStr('website');
    $yahoo = $request->PostStr('yahoo');
    $msn = $request->PostStr('msn');
    $skype = $request->PostStr('skype');
    $icq = $request->PostStr('icq');
    $sonork = $request->PostStr('sonork');

		
	if($reseller == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller_add.html?reply=" . urlencode('reply_title_missing'));
		exit();
	}
	if($email == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller_add.html?reply=" . urlencode('reply_email_missing'));
		exit();
	}
		
	$sql = 'insert into ' . RESELLER_MASTER . '
			(reseller, type, address, country, email, mobile, phone, website, yahoo, msn, skype, icq, sonork)
			values(
			' . $mysql->quote($reseller) . ',
			' . $mysql->getInt($type) . ', 
			' . $mysql->quote($address) . ',
			' . $mysql->getInt($country) . ',
			' . $mysql->quote($email) . ',
			' . $mysql->quote($mobile) . ',
			' . $mysql->quote($phone) . ',
			' . $mysql->quote($website) . ',
			' . $mysql->quote($yahoo) . ',
			' . $mysql->quote($msn) . ',
			' . $mysql->quote($skype) . ',
			' . $mysql->quote($icq) . ',
			' . $mysql->quote($sonork) . ')';
	$mysql->query($sql);
	
	$objEmail = new email();
	$args = array(
				'to' => CONFIG_EMAIL,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'reseller' => $reseller,
				'site_admin' => CONFIG_SITE_NAME
				);
	$objEmail->sendEmailTemplate('admin_add_reseller', $args);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller.html?reply=" . urlencode('reply_success'));
	exit();
?>
