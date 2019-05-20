<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();
	//$validator->formValidateAdmin('');
    $id = $request->PostInt('id');
    $currency = $request->PostStr('currency');
    $prefix = $request->PostStr('prefix');
    $prefix_code = $request->PostStr('prefix_code');
	$suffix = $request->PostStr('suffix');
    $rate = $request->PostStr('rate');

	
	$sql = 'update ' . CURRENCY_MASTER . '
			set 
			currency = ' . $mysql->quote($currency) . ',
			prefix =' . $mysql->quote($prefix) . ',
			prefix_code =' . $mysql->quote($prefix_code) . ',
			suffix = ' . $mysql->quote($suffix) . ',
			rate = ' . $mysql->quote($rate) . '
			where id = ' . $mysql->getInt($id);
	      $mysql->query($sql);
	
	
	
	header("location:" . CONFIG_PATH_SITE_USER . "currency.html?reply=" . urlencode('lbl_currency_edit'));
	exit();
?>