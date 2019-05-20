<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
       // echo 'here';
       // exit;
        
	$print = print_r($_POST, true);
	error_log($print, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal.log");
	
	
	/*******************************************************
					Start Paypal API
	*******************************************************/
	$sql_gateway = 'select * from ' . GATEWAY_MASTER . ' where id =1';
	$query_gateway = $mysql->query($sql_gateway);
	if($mysql->rowCount($query_gateway) > 0)
	{
		error_log($sql_gateway, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
		$rows_gateway = $mysql->fetchArray($query_gateway);
		$row_gateway = $rows_gateway[0];
		$URL = '';
		if($row_gateway['demo_mode'] == "0")
		{
			$URL = 'https://www.paypal.com/cgi-bin/webscr';
		}
		else
		{	
			$URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$postFields = 'cmd=_notify-validate';
		
		foreach($_POST as $key => $value)
		{
			
			$postFields .= "&$key=" . urlencode($value);
		}
		
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $URL,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postFields
		));
		
		$result = curl_exec($ch);echo curl_error($ch);
		curl_close($ch);
		error_log($result, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal2.log");
		if($result == 'VERIFIED')
		{
                        if(count($_POST) > 0 && $_POST['payment_status'] == "Completed")
                        {
                           error_log("Completed", 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
			$id = $request->PostInt('custom');
			$mc_gross = $request->PostFloat('mc_gross');
			$txn_id = $request->PostStr('txn_id'); 
			
			
			//Check autopay
			$sql = 'select * from ' . INVOICE_REQUEST . ' where id=' . $id;
			$resultUser = $mysql->getResult($sql);
			$user_id = $resultUser['RESULT'][0]['user_id'];

			$sql_user = 'select auto_pay,currency_id from ' . USER_MASTER . ' where id=' . $user_id;
			$query_user = $mysql->query($sql_user);
			$rows_user = $mysql->fetchArray($query_user);
			$auto_play = $rows_user[0]['auto_pay'];
                        $currency_id = $rows_user[0]['currency_id'];
			if($auto_play == 1)
			{
				$user_cr = $objCredits->getUserCredits($id);
				
				$sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($txn_id) . ' ,
								status=3
							where id=' . $id;

				error_log($sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");

				$mysql->query($sql);
				
				$sql = 'select * from ' . INVOICE_REQUEST . ' where id=' . $id;
				$query = $mysql->query($sql);
				if($mysql->rowCount($query) > 0)
				{
					$rows = $mysql->fetchArray($query);
					$user_id = $rows[0]['user_id'];
					$credits = $rows[0]['credits'];
					
					$sql = 'update ' . USER_MASTER . '
									set credits=credits + '. $mysql->getFloat($credits) . '
								where id=' . $mysql->getInt($user_id);
					error_log("^^" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
					$mysql->query($sql);
					
					
					$ip = $_SERVER['REMOTE_ADDR'];
					$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
							(user_id, user_id2, date_time, credits,
							credits_acc, credits_acc_process, credits_acc_used,
							credits_after, credits_after_process, credits_after_used,
							info, trans_type,ip)
							values(
								' . $mysql->getInt($user_id) . ',
								0,
								now(),
								' . $mysql->getFloat($credits) . ',

								' . $mysql->getFloat($user_cr['credits']) . ',
								' . $mysql->getFloat($user_cr['process']) . ',
								' . $mysql->getFloat($user_cr['used']) . ',
								' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
								' . $mysql->getFloat(($user_cr['process'])) . ',
								' . $mysql->getInt($user_cr['used']) . ',
															
								' . $mysql->quote("Credits Added by [Auto Pay]") . ',
								6,
								' . $mysql->quote($ip) . '
							);';
					error_log("##" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
					$mysql->query($sql);
                                        $gateway_id=1;
                                         $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount, credits,gateway_id,currency_id, date_time,date_time_paid,paid_status,status) values('.$mysql->quote($txn_id).','.$user_id.','.$mysql->getFloat($credits).','.$credits.',1,'.$currency_id.',now(),now(),1,0)';
                                        //echo $sql_inv;
                                        $mysql->query($sql_inv);
                                         $last_id = $mysql->insert_id();
                                         //entery into invoice log
                                       $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values(' . $last_id . ', ' . $mysql->getFloat($credits) . ', ' . $credits . ', "Credits Added by [Auto Pay]", now(),' . $user_id . ',1,1)';
                                            $query_inv = $mysql->query($inv_log);
				}
				//***************************************************
			}
			else
			{
				$sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($txn_id) . '
							where id=' . $id;
				error_log("**" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
				$mysql->query($sql);
			}   
                            
                        }
                        
                        else if(count($_POST) > 0 )
                        {
                            if($_POST['payment_status'] == "Refunded" || $_POST['payment_status'] == "Reversed")
                            {
                             error_log($_POST['payment_status'], 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
			$id = $request->PostInt('custom');
			$mc_gross = $request->PostFloat('mc_gross');
			$txn_id = $request->PostStr('txn_id'); 
			
			
			//Check autopay
			$sql = 'select * from ' . INVOICE_REQUEST . ' where id=' . $id;
			$resultUser = $mysql->getResult($sql);
			$user_id = $resultUser['RESULT'][0]['user_id'];

			$sql_user = 'select auto_pay,currency_id from ' . USER_MASTER . ' where id=' . $user_id;
			$query_user = $mysql->query($sql_user);
			$rows_user = $mysql->fetchArray($query_user);
			$auto_play = $rows_user[0]['auto_pay'];
                        $currency_id = $rows_user[0]['currency_id'];
			if($auto_play == 1)
			{
				$user_cr = $objCredits->getUserCredits($id);
				
				$sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($txn_id) . ' ,
								status=2
							where id=' . $id;

				error_log($sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");

				$mysql->query($sql);
				
				$sql = 'select * from ' . INVOICE_REQUEST . ' where id=' . $id;
				$query = $mysql->query($sql);
				if($mysql->rowCount($query) > 0)
				{
					$rows = $mysql->fetchArray($query);
					$user_id = $rows[0]['user_id'];
					$credits = $rows[0]['credits'];
					
					$sql = 'update ' . USER_MASTER . '
									set credits=credits - '. $mysql->getFloat($credits) . '
								where id=' . $mysql->getInt($user_id);
					error_log("^^" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
					$mysql->query($sql);
					
					
					$ip = $_SERVER['REMOTE_ADDR'];
					$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
							(user_id, user_id2, date_time, credits,
							credits_acc, credits_acc_process, credits_acc_used,
							credits_after, credits_after_process, credits_after_used,
							info, trans_type,ip)
							values(
								' . $mysql->getInt($user_id) . ',
								0,
								now(),
								' . $mysql->getFloat($credits) . ',

								' . $mysql->getFloat($user_cr['credits']) . ',
								' . $mysql->getFloat($user_cr['process']) . ',
								' . $mysql->getFloat($user_cr['used']) . ',
								' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
								' . $mysql->getFloat(($user_cr['process'])) . ',
								' . $mysql->getInt($user_cr['used']) . ',
															
								' . $mysql->quote("Credits Revoked by [Auto Pay]") . ',
								6,
								' . $mysql->quote($ip) . '
							);';
                                        $sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
      (admin_id, user_id2, date_time, credits,
      credits_acc_2, credits_acc_process_2, credits_acc_used_2,
      credits_after_2, credits_after_process_2, credits_after_used_2,
      info, trans_type, ip)
      values(
       0,
       ' . $mysql->getInt($user_id) . ',
       now(),
       ' . $mysql->getFloat($credits) . ',
       
       ' . $mysql->getFloat($user_cr['credits']) . ',
       ' . $mysql->getFloat($user_cr['process']) . ',
       ' . $mysql->getFloat($user_cr['used']) . ',
       ' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
       ' . $mysql->getFloat(($user_cr['process'])) . ',
       ' . $mysql->getInt($user_cr['used']) . ',
       
       
       ' . $mysql->quote("Credits Revoked by [Auto Pay]") . ',
       6,
       ' . $mysql->quote($ip) . '
      );';
					error_log("##" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
					$mysql->query($sql);
                                        $gateway_id=1;
                                         $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount, credits,gateway_id,currency_id, date_time,date_time_paid,paid_status,status) values('.$mysql->quote($txn_id).','.$user_id.','.$mysql->getFloat($credits).','.$credits.',1,'.$currency_id.',now(),now(),3,0)';
                                        //echo $sql_inv;
                                        $mysql->query($sql_inv);
                                         $last_id = $mysql->insert_id();
                                         //entery into invoice log
                                       $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values(' . $last_id . ', ' . $mysql->getFloat($credits) . ', ' . $credits . ', "Credits Revoked by [Auto Pay]", now(),' . $user_id . ',1,1)';
                                            $query_inv = $mysql->query($inv_log);
				}
				//***************************************************
			}
			else
			{
				$sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($txn_id) . '
							where id=' . $id;
				error_log("**" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
				$mysql->query($sql);
			} 
                            }
                        }    
                        
                        else
                        {
                             error_log($_POST['payment_status'], 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
                            
                        }    
                      
		}
		else
		{
			error_log("Invalid details!", 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
			exit();
		}
		//error_log($result, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
	}
	else
	{
		error_log("There is some error!", 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log");
		exit();
	}
	/*^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
					End: Start Paypal API
	^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
	
    
	function allotCredits($id, $credits, $txn_id)
	{

		

	}
	
	
?>