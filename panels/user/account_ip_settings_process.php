<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_USER . "account_ip_settings.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
	
	$username=$request->PostStr('username');
	$email=$request->PostStr('email');
	
	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_acc_ip_148143438');
	
    $ip1a = $request->PostStr('ip1a');
    $ip1b = $request->PostStr('ip1b');

    $ip2a = $request->PostStr('ip2a');
    $ip2b = $request->PostStr('ip2b');

    $ip3a = $request->PostStr('ip3a');
    $ip3b = $request->PostStr('ip3b');

    $ip4a = $request->PostStr('ip4a');
    $ip4b = $request->PostStr('ip4b');

    $ip5a = $request->PostStr('ip5a');
    $ip5b = $request->PostStr('ip5b');

	
	$sql = 'update ' . USER_MASTER . '
				set
				ip1a=' . $mysql->quote($ip1a) . ',
				ip1b=' . $mysql->quote($ip1b) . ',
				ip2a=' . $mysql->quote($ip2a) . ',
				ip2b=' . $mysql->quote($ip2b) . ',
				ip3a=' . $mysql->quote($ip3a) . ',
				ip3b=' . $mysql->quote($ip3b) . ',
				ip4a=' . $mysql->quote($ip4a) . ',
				ip4b=' . $mysql->quote($ip4b) . ',
				ip5a=' . $mysql->quote($ip5a) . ',
				ip5b=' . $mysql->quote($ip5b) . '
				where id=' . $mysql->getInt($member->getUserId());
	$mysql->query($sql);
	
	$args = array(
				'to' => $email,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username' => $username,
				'user_id'=>$member->getUserId(),
				'save'=>1,
				'site_admin' => CONFIG_SITE_NAME
				);
	$objEmail->sendEmailTemplate('user_edit_ip', $args);
	header("location:" . CONFIG_PATH_SITE_USER . "account_ip_settings.html?reply=" . urlencode('reply_update_ip'));
	exit();
?>