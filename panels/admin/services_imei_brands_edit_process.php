<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_brands_edit_1483412');

	$id = $request->PostInt('id');
	$brand= $request->PostStr('brand');
	
	$status = $request->PostInt('status');    
	
	if(trim($brand) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_brands_edit.html?id=' . $id . '&reply=' . urlencode('reply_service_imei_brand_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_BRAND_MASTER . '
					where brand=' . $mysql->quote($brand) . ' and id!=' . $mysql->getInt($id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . IMEI_BRAND_MASTER . '
					set
					brand='. $mysql->quote($brand) . ',
					status=' . $mysql->getInt($status) . ' where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		$args = array(
				'to' => CONFIG_EMAIL,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'brand' => $brand,
				'site_admin' => CONFIG_SITE_NAME
				);
		$objEmail->sendEmailTemplate('admin_edit_brand', $args);
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models.html?brand_id='.$brand.'&reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models.html?brand_id='.$brand.'&reply=' . urlencode('reply_service_imei_brand_duplicate'));
		exit();
	}	
?>
