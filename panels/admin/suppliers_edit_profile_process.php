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
    $show_user = $request->PostStr('show_user');
    $show_credits = $request->PostStr('show_credits');

	
	$sql = 'update ' . SUPPLIER_MASTER . '
			set 
			first_name = ' . $mysql->quote($first_name) . ', 
			last_name = ' . $mysql->quote($last_name) . ', 
			company = ' . $mysql->quote($company) . ', 
			address = ' . $mysql->quote($address) . ', 
			city = ' . $mysql->quote($city) . ', 
			language_id = ' . $mysql->getInt($language) . ',
			timezone_id = ' . $mysql->getInt($timezone) . ',
			country_id = ' . $mysql->getInt($country) . ',
			phone = ' . $mysql->quote($phone) . ', 
			mobile = ' . $mysql->quote($mobile) . ',
			show_user = ' . $show_user . ',
			show_credits = ' . $show_credits . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers.html?reply=" . urlencode('reply_success_suppliers_edit'));
	
	
	
?>