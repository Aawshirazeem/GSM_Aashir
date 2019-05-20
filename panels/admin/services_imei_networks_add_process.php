<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_network_add_5445622h2');

	$country_id = $request->PostInt('country_id');
	
	$network= $request->PostStr('network');
	
	$status = $request->PostInt('status');    
	
	if(trim($network) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks_add.html?country_id=' . $country_id . '&reply=' . urlencode('reply_service_imei_mep_networks_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_NETWORK_MASTER . ' where network=' . $mysql->quote($network) . ' and country=' . $mysql->getInt($country_id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'insert into ' . IMEI_NETWORK_MASTER . ' (network, country, status)
					values(
					'. $mysql->quote($network) . ',
					'. $mysql->getInt($country_id) . ',
					' . $mysql->getInt($status) . ')';
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks.html?country_id=' . $country_id . '&reply=' . urlencode('reply_add_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks_add.html?country_id=' . $country_id . '&reply=' . urlencode('reply_service_imei_mep_networks_duplicate'));
		exit();
	}	
?>