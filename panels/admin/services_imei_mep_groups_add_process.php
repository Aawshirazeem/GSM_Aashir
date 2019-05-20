<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_mep_group_add_545767732');

	$id = $request->PostInt('id');
	
	$mep_group= $request->PostStr('mep_group');
	
	$status = $request->PostInt('status');    
	
	if(trim($mep_group) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups_add.html?reply=' . urlencode('reply_service_imei_mep_group_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_MEP_GROUP_MASTER . ' where mep_group=' . $mysql->quote($mep_group);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'insert into ' . IMEI_MEP_GROUP_MASTER . ' (mep_group, status)
					values(
					'. $mysql->quote($mep_group) . ',
					' . $mysql->getInt($status) . ')';
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups.html?reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups_add.html?reply=' . urlencode('reply_service_imei_mep_group_duplicate'));
		exit();
	}	
?>
