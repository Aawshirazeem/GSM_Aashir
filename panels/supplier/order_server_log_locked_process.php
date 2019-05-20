<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_server_log_33245d3345d2');

	$objCredits = new credits();
	
	$Ids = (isset($_POST['Ids']))? $_POST['Ids'] : array();
	$type = $request->postStr('type');
		
	foreach($Ids as $id)
	{
		$username=$request->PostStr('username_' . $id);
		$email=$request->PostStr('email_' . $id);
		$order_id=$id;
		$user_id=$request->PostStr('user_id_' . $id);
		$server_log_name=$request->PostStr('server_log_name_' . $id);
		$credits=$request->PostStr('credits_' . $id);			
		
		$sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($id);
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);

		if(isset($_POST['accept_' . $id]))
		{
			$objCredits->processServerLog($id, $rows[0]['user_id'], $rows[0]['credits_amount']);
			
			$sql = 'update 
						' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
						im.reply_date_time=now(),
						im.supplier_id=' . $supplier->getUserId() . ',
						um.credits_inprocess = um.credits_inprocess - im.credits_amount, um.credits_used = um.credits_used + im.credits_amount
						where um.id = im.user_id and im.id=' . $mysql->getInt($id);

			$mysql->query($sql);
			$objEmail = new email();
			$args = array(
						'to' => $email,
						'from' => CONFIG_EMAIL,
						'fromDisplay' => CONFIG_SITE_NAME,
						'order_id' => $order_id,
						'user_id' => $user_id,
						'save' => '1',
						'username' => $username,
						'server_log_name' => $server_log_name,
						'username' => $username,
						'credits'=>	$credits,
						'site_admin' => CONFIG_SITE_NAME
						);
			$objEmail->sendEmailTemplate('supplier_user_order_server_log_avail', $args);
		}
		if(isset($_POST['reject_' . $id]))
		{
			if(!isset($_POST['accept_' . $id]))
			{
				$objCredits->returnServerLog($id, $rows[0]['user_id'], $rows[0]['credits_amount']);
				
				$sql = 'update 
							' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
							im.reply_date_time=now(),
							im.supplier_id=' . $supplier->getUserId() . ',
							im.message=' . $mysql->quote($_POST['un_remarks_' . $id]) . ',
							um.credits = um.credits + im.credits_amount, um.credits_inprocess = um.credits_inprocess - im.credits_amount
							where um.id = im.user_id and im.id=' . $mysql->getInt($id);
				$mysql->query($sql);
				
				$objEmail = new email();
				$args = array(
						'to' => $email,
						'from' => CONFIG_EMAIL,
						'fromDisplay' => CONFIG_SITE_NAME,
						'order_id' => $order_id,
						'user_id' => $user_id,
						'save' => '1',
						'username' => $username,
						'server_log_name' => $server_log_name,
						'username' => $username,
						'credits'=>	$credits,
						'site_admin' => CONFIG_SITE_NAME
						);
						
			$objEmail->sendEmailTemplate('supplier_user_order_server_log_unavail', $args);
			}
		}
	}

	header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_server_log.html?type=" . $type . "&reply=" .urlencode('eply_imi_succ'));
	exit();
?>
