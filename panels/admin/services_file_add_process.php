<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('con_services_file_add_148353412');

    $service_name = $request->PostStr('service_name');
    $credits = $request->PostFloat('credits');
    $delivery_time = $request->PostStr('delivery_time');
    $reply_type = $request->PostInt('reply_type');
    $verification = $request->PostInt('verification');
    $status = $request->PostInt('status');
    
    $download_link = $request->PostStr('download_link');
    $faq_id = $request->PostInt('faq_id');
    $info = $request->PostStr('info');
    
    $api_id = $request->PostInt('api_id');
    $api_service_id = $request->PostInt('api_service_id');
		
	if($service_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_add.html?reply=" . urlencode('reply_file_service_missing'));
		exit();
	}

	$sql = 'insert into ' . FILE_SERVICE_MASTER . '
			(service_name , api_id, api_service_id, delivery_time, reply_type, info , 
				download_link , faq_id , verification ,status)
			values(
			' . $mysql->quote($service_name) . ',
			' . $mysql->getInt($api_id) . ',
			' . $mysql->getInt($api_service_id) . ',
			' . $mysql->quote($delivery_time) . ',
			' . $mysql->getInt($reply_type) . ',
			' . $mysql->quote($info) . ',
			' . $mysql->quote($download_link) . ',
			' . $mysql->getInt($faq_id) . ',
			' . $mysql->getInt($verification) . ',
			' . $mysql->getInt($status) . ')';
	$mysql->query($sql);
	$id = $mysql->insert_id();
	$file_service=$service_name;


		/****************************************************
						UPDATE AMOUNT
		*****************************************************/
		if(isset($_POST['currency_id'])){
			$currencies = $_POST['currency_id'];

			$sql = 'delete from ' . FILE_SERVICE_AMOUNT_DETAILS . ' where service_id = ' . $id;
			$mysql->query($sql);

			foreach($currencies as $key => $currency_id){

				$amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
				$amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

				$sql = 'insert into ' . FILE_SERVICE_AMOUNT_DETAILS . '
						(service_id, currency_id, amount , amount_purchase)
						values(
						' . $id . ',
						' . $mysql->getInt($currency_id) . ',
						' . $amount . ',
						' . $amount_purchase . ')';
				$mysql->query($sql);
			}
		}
		/*****************************************************/




	$args = array(
					'to' => CONFIG_EMAIL,
					'from' => CONFIG_EMAIL,
					'fromDisplay' => CONFIG_SITE_NAME,
					'file_service' => $file_service,
					'site_admin' => CONFIG_SITE_NAME
				);
	$objEmail->sendEmailTemplate('admin_add_file_service', $args);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file.html?reply=" . urlencode('reply_success'));
	exit();
?>