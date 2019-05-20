<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_model_add_54456ghh2');

	$brand_id = $request->PostInt('brand_id');
	
	$model= $request->PostStr('model');
	
	$status = $request->PostInt('status');    
	
	if(trim($model) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models_add.html?brand_id=' . $brand_id . '&reply=' . urlencode('reply_service_imei_mep_models_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_MODEL_MASTER . '
					where brand=' . $mysql->getInt($brand_id) . ' and model=' . $mysql->quote($model);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'insert into ' . IMEI_MODEL_MASTER . ' (model, brand, status)
					values(
					'. $mysql->quote($model) . ',
					'. $mysql->getInt($brand_id) . ',
					' . $mysql->getInt($status) . ')';
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
