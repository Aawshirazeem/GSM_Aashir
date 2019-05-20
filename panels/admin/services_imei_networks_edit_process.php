<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_network_edit_534dfrr2');

	$id = $request->PostInt('id');
	$country_id = $request->PostInt('country_id');
	
	$network= $request->PostStr('network');
	
	$status = $request->PostInt('status');    
	
	if(trim($network) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks_edit.html?id=' . $id . '&country_id=' . $country_id . '&reply=' . urlencode('reply_service_imei_mep_networks_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_NETWORK_MASTER . ' where network=' . $mysql->quote($network) . ' and country=' . $mysql->getInt($country_id) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_NETWORK_MASTER . '
					set
					network='. $mysql->quote($network) . ',
					status=' . $mysql->getInt($status) . ' where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks.html?country_id=' . $country_id . '&reply=' . urlencode('reply_update_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks.html?country_id=' . $country_id . '&reply=' . urlencode('reply_service_imei_mep_networks_duplicate'));
		exit();
	}	
?>