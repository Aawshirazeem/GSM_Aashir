<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('con_ser_log_add_1488732');

    $server_log_name = $request->PostStr('server_log_name');
    $group_id = $request->PostInt('group_id');
    $delivery_time = $request->PostStr('delivery_time');
    $verification = $request->PostInt('verification');
    $status = $request->PostInt('status');
     $chimera = $request->PostInt('chimera');
        $user_id = $request->PostStr('user_id');
          $api_key = $request->PostStr('api_key');
          
    $custom_field_name = $request->PostStr('custom_field_name');
    $custom_field_message = $request->PostStr('custom_field_message');
    $custom_field_value = $request->PostStr('custom_field_value');
    
    $api_id = $request->PostInt('api_id');
    $api_service_id = $request->PostInt('api_service_id');
    
    $download_link = $request->PostStr('download_link');
    $faq_id = $request->PostInt('faq_id');
    $info = $request->PostStr('info');

		
	if($server_log_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_add.html?group_id=" . $group_id . "&reply=" . urlencode('reply_server_logs_missing'));
		exit();
	}
	
	$sql_chk ='select * from ' . SERVER_LOG_MASTER . ' where server_log_name=' . $mysql->quote($server_log_name) . ' and group_id=' . $group_id;
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{

		$sql = 'insert into ' . SERVER_LOG_MASTER . '
				(server_log_name , delivery_time , group_id , api_id , api_service_id ,
					custom_field_name , custom_field_message , custom_field_value , info , 
					download_link , faq_id , verification ,status,chimera_user_id,chimera_api_key,chimera)
				values(
				' . $mysql->quote($server_log_name) . ',
				' . $mysql->quote($delivery_time) . ',
				' . $mysql->getInt($group_id) . ',
				' . $mysql->getInt($api_id) . ',
				' . $mysql->getInt($api_service_id) . ',
				' . $mysql->quote($custom_field_name) . ',
				' . $mysql->quote($custom_field_message) . ',
				' . $mysql->quote($custom_field_value) . ',
				' . $mysql->quote($info) . ',
				' . $mysql->quote($download_link) . ',
				' . $mysql->getInt($faq_id) . ',
				' . $mysql->getInt($verification) . ',
				' . $mysql->getInt($status) . ',
                                ' . $mysql->quote($user_id) . ',
                                ' . $mysql->quote($api_key) . ',
                                ' . $mysql->getInt($chimera) . ')';
            //    echo $sql;exit;
		$mysql->query($sql);
		$id = $mysql->insert_id();



		/****************************************************
						UPDATE AMOUNT
		*****************************************************/
		if(isset($_POST['currency_id'])){
			$currencies = $_POST['currency_id'];

			$sql = 'delete from ' . SERVER_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
			$mysql->query($sql);

			foreach($currencies as $key => $currency_id){

				$amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
				$amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

				$sql = 'insert into ' . SERVER_LOG_AMOUNT_DETAILS . '
						(log_id, currency_id, amount , amount_purchase)
						values(
						' . $id . ',
						' . $mysql->getInt($currency_id) . ',
						' . $amount . ',
						' . $amount_purchase . ')';
				$mysql->query($sql);
			}
		}
		/*****************************************************/



		header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_add.html?group_id=" . $group_id . "&reply=" . urlencode('reply_add_success'));
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'server_logs_add.html?group_id=' . $group_id . '&reply=' . urlencode('reply_server_logs_duplicate!'));
		exit();
	}
	
	
	
	
	
?>