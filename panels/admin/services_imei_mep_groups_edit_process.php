<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_mep_group_edit_4565646');

	$id = $request->PostInt('id');
	
	$mep_group= $request->PostStr('mep_group');
	
	$status = $request->PostInt('status');    
	
	if(trim($mep_group) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups_edit.html?id=' . $id . '&reply=' . urlencode('reply_service_imei_mep_group_missing!'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_MEP_GROUP_MASTER . '
					where mep_group=' . $mysql->quote($mep_group) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_MEP_GROUP_MASTER . '
					set
					mep_group='. $mysql->quote($mep_group) . ',
					status=' . $mysql->getInt($status) . ' where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups.html?reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups_edit.html?reply=' . urlencode('reply_service_imei_mep_group_duplicate!'));
		exit();
	}	
?>