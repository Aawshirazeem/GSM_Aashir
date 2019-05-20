<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$id = $request->PostInt('id');
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('config_reseller_edit_1488855448');
	
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
    $status = $request->PostInt('status');

	
	$sql = 'update ' . RESELLER_MASTER . '
					set 
					reseller = ' . $mysql->quote($reseller) . ',
					type = ' . $mysql->getInt($type) . ',
					address = ' . $mysql->quote($address) . ',
					country = ' . $mysql->quote($country) . ',
					email = ' . $mysql->quote($email) . ',
					mobile = ' . $mysql->quote($mobile) . ',
					phone = ' . $mysql->quote($phone) . ',
					website = ' . $mysql->quote($website) . ',
					yahoo = ' . $mysql->quote($yahoo) . ',
					msn = ' . $mysql->quote($msn) . ',
					skype = ' . $mysql->quote($skype) . ',
					icq = ' . $mysql->quote($icq) . ',
					sonork = ' . $mysql->quote($sonork) . ',
					status = ' . $mysql->getInt($status) . '
				where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	$args = array(
				'to' => CONFIG_EMAIL,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'reseller' => $reseller,
				'site_admin' => CONFIG_SITE_NAME
				);
	$objEmail->sendEmailTemplate('admin_edit_reseller', $args);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller.html?reply=" . urlencode('reply_update'));
	exit();
?>