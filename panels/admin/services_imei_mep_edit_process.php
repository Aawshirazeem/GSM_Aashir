<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_mep_edit_55453432');

	$id = $request->PostInt('id');
	$group_id = $request->PostInt('group_id');
	$mep= $request->PostStr('mep');
	
	$status = $request->PostInt('status');    
	
	if(trim($mep) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_edit.html?id=' . $id . '&group_id=' . $group_id . '&reply=' . urlencode('reply_service_imei_mep_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_MEP_MASTER . ' where mep=' . $mysql->quote($mep) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_MEP_MASTER . '
					set
					mep='. $mysql->quote($mep) . ',
					status=' . $mysql->getInt($status) . ' where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep.html?group_id=' . $group_id . '&reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_edit.html?group_id=' . $group_id . '&reply=' . urlencode('reply_service_imei_mep_duplicate'));
		exit();
	}	
?>