<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_key_33455gkgk5d2');

	$objCredits = new credits();
	

	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : '';
	$type = $request->postStr('type');
	
	if($Ids == '')
	{
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_n_imi'));
	}
	
	foreach($Ids as $id)
	{
		$order_id=$id;
		$file_service=$request->PostStr('service_name_' . $id);
		$username=$request->PostStr('username_' . $id);
		$user_id=$request->PostStr('user_id_' . $id);
		$file_name=$request->PostStr('file_name_' . $id);
		$email=$request->PostStr('email_' . $id);
		$credits=$request->PostStr('credits_' . $id);
	
		
		$sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($id);
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);

		if(isset($_POST['accept_' . $id]))
		{
			$objCredits->processFile($id, $rows[0]['user_id'], $rows[0]['credits_amount']);
			
			$sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
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
						'user_id' => $user_id,
						'save' => '1',
						'username' => $username,
						'order_id' => $order_id,
						'file_service' =>$file_service,
						'file_name' =>$file_name,
						'credits' =>$credits,
						'site_admin' => CONFIG_SITE_NAME
						);
			$objEmail->sendEmailTemplate('supplier_user_order_file_avail', $args);
		}
		if(isset($_POST['reject_' . $id]))
		{
			if(!isset($_POST['accept_' . $id]))
			{
				$objCredits->returnFile($id, $rows[0]['user_id'], $rows[0]['credits_amount']);
				
				$sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
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
							'user_id' => $user_id,
							'save' => '1',
							'username' => $username,
							'order_id' => $order_id,
							'file_service' =>$file_service,
							'file_name' =>$file_name,
							'credits' =>$credits,
							'site_admin' => CONFIG_SITE_NAME
							);
				$objEmail->sendEmailTemplate('supplier_user_order_file_unavail', $args);
			}
		}
	}
	
	
	header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?type=" . $type . "&reply=" .urlencode('reply_ord_succ'));
	exit();
?>