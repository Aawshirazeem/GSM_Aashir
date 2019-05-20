<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
    $validator->formValidateAdmin('con_pre_log_148873737312');

	

    
	$prepaid_log_name = $request->PostStr('prepaid_log_name');
    $group_id = $request->PostInt('group_id');
    $credits = $request->PostFloat('credits');
    $status = $request->PostInt('status');
    $info = $request->PostStr('info');
    $delivery_time = $request->PostStr('delivery_time');

		
	if($prepaid_log_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_add.html?group_id=" . $group_id . "&reply=" . urlencode('reply_prepaid_log_missing'));
		exit();
	}
	
	$sql_chk ='select * from ' . PREPAID_LOG_MASTER . ' where prepaid_log_name=' . $mysql->quote($prepaid_log_name) . ' and group_id=' . $mysql->getInt($group_id);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{

		$sql = 'insert into ' . PREPAID_LOG_MASTER . '
					(prepaid_log_name , delivery_time,group_id , info, status)
				values(
						' . $mysql->quote($prepaid_log_name) . ',
                                                    ' . $mysql->quote($delivery_time) . ',
						' . $mysql->getInt($group_id) . ',
						' . $mysql->quote($info) . ',
						' . $mysql->getInt($status) . ')';
		$mysql->query($sql);
		$id = $mysql->insert_id();


		/****************************************************
						UPDATE AMOUNT
		*****************************************************/
		if(isset($_POST['currency_id'])){
			$currencies = $_POST['currency_id'];

			$sql = 'delete from ' . PREPAID_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
			$mysql->query($sql);

			foreach($currencies as $key => $currency_id){

				$amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
				$amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

				$sql = 'insert into ' . PREPAID_LOG_AMOUNT_DETAILS . '
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


		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs.html?group_id=" . $group_id . "&reply=" . urlencode('reply_success'));
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_add.html?group_id=' . $group_id . '&reply=' . urlencode('reply_prepaid_logs_duplicate'));
		exit();
	}
	
	
	
	
	
?>