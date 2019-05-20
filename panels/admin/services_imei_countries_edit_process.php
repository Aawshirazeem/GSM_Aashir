<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_countries_edit_509996512');

	$id = $request->PostInt('id');
	$country= $request->PostStr('country');
	
	$status = $request->PostInt('status');    
	
	if(trim($country) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries_edit.html?id=' . $id . '&reply=' . urlencode('reply_service_imei_countries_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_COUNTRY_MASTER . '
				where country=' . $mysql->quote($country) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_COUNTRY_MASTER . '
						set country='. $mysql->quote($country) . ',
						status=' . $mysql->getInt($status) . '
					where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		$args = array(
		'to' => CONFIG_EMAIL,
		'from' => CONFIG_EMAIL,
		'fromDisplay' => CONFIG_SITE_NAME,
		'country' => $country,
		'site_admin' => CONFIG_SITE_NAME
		);
		$objEmail->sendEmailTemplate('admin_edit_country', $args);
		print_r($args);
		exit();
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries.html?reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_countries_edit.html?id=' . $id . '&reply=' . urlencode('reply_service_imei_countries_duplicate'));
		exit();
	}	
?>