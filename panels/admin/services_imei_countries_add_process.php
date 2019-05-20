<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_countries_add_545512');

	$id = $request->PostInt('id');
	$country= $request->PostStr('country');
	
	$status = $request->PostInt('status');    
	
	if(trim($country) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries_add.html?reply=' . urlencode('reply_service_imei_countries_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_COUNTRY_MASTER . ' where country=' . $mysql->quote($country);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'insert into ' . IMEI_COUNTRY_MASTER . ' (country, status)
					values(
					'. $mysql->quote($country) . ',
					' . $mysql->getInt($status) . ')';
		$mysql->query($sql);
		
		$args = array(
				'to' => CONFIG_EMAIL,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'country' => $country,
				'site_admin' => CONFIG_SITE_NAME
				);
		$objEmail->sendEmailTemplate('admin_add_country', $args);
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries_add.html?reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries_add.html?reply=' . urlencode('reply_service_imei_countries_duplicate'));
		exit();
	}	
?>