<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_model_edit_534g6ghh2');

	$id = $request->PostInt('id');
	$brand_id = $request->PostInt('brand_id');
	
	$model= $request->PostStr('model');
	
	$status = $request->PostInt('status');    
	
	if(trim($model) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models_edit.html?id=' . $id . '&brand_id=' . $brand_id . '&reply=' . urlencode('reply_service_imei_mep_models_missing'));
	}
	$sql_chk ='select * from ' . IMEI_MODEL_MASTER . ' where brand=' . $mysql->getInt($brand_id) . ' and model=' . $mysql->quote($model) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_MODEL_MASTER . '
					set 
					model='. $mysql->quote($model) . ',
					status=' . $mysql->getInt($status) . '
				where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models.html?brand_id=' . $brand_id . '&reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models.html?brand_id=' . $brand_id . '&reply=' . urlencode('reply_service_imei_mep_models_duplicate'));
		exit();
	}	
?>