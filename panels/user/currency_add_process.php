<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$form_id = $request->PostStr('form_id');
	$form_key = $request->PostStr('form_key');
	
	$member->checkLogin();
	$member->reject();
    //$validator->formValidateAdmin('');
	
    $currency = $request->PostStr('currency');
    $prefix = $request->PostStr('prefix');
    $prefix_code = $request->PostStr('prefix_code');
	$suffix = $request->PostStr('suffix');
    $rate = $request->PostStr('rate');
   
   
    $sql = 'insert into ' . CURRENCY_MASTER . '
			(currency,prefix,prefix_code,suffix,rate)
			values(
			' . $mysql->quote($currency) . ',
			' . $mysql->quote($prefix) . ',
			' . $mysql->quote($prefix_code) . ',
			' . $mysql->quote($suffix) . ',
			' . $mysql->quote($rate).')';
			
	$mysql->query($sql);
	
	
	header("location:" . CONFIG_PATH_SITE_USER . "currency.html?reply=" . urlencode('lbl_currency_add'));
	exit();
?>