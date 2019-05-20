<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_key_33455gkgk5d2');

	$cookie = new cookie();
	$objImei = new imei();
	$objCredits = new credits();	
	
    $id = $request->PostInt('id');
	$user_id = $request->PostStr('user_id');
    $code = $request->PostStr('code');
    
	$sql_detail= 'select ofsm.*,
			um.username,
			um.email,
			slm.service_name
			from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
			left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)
			left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
			where um.id= ' . $user_id . '
			and ofsm.id= ' . $id . '
			order by ofsm.id DESC';
	$query_detail=$mysql->query($sql_detail);	
	
	// Check is file set
	if(isset($_FILES['file']))
	{
		// Get file name and check is it empty
		$tempName = $_FILES['file']['name'];
		if($code != "" or $tempName != "")
		{
			if($mysql->rowCount($query_detail))
			{
				$rows_detail=$mysql->fetchArray($query_detail);
				$row_detail=$rows_detail[0];
				$file_service=$row_detail['service_name'];
				$username=$row_detail['username'];
				$email=$row_detail['email'];
				$order_id=$id;
				$credits=$row_detail['credits'];
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
							'credits' =>$credits,
							'site_admin' => CONFIG_SITE_NAME
							);
				$objEmail->sendEmailTemplate('supplier_user_order_file_update', $args);
			}
		}
	}
		
	// Check is file set
	if(isset($_FILES['file']))
	{
		$uploadfile = "";
		// Get file name and check is it empty
		$tempName = $_FILES['file']['name'];
		if($tempName != "")
		{
			$uploadfile = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $tempName;
			if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				header('location:' . CONFIG_PATH_SITE_MEMBER . 'file_submit.html?reply=' . urlencode('reply_n_up'));
				exit();
			}
			if($uploadfile == "")
			{
				header('location:' . CONFIG_PATH_SITE_MEMBER . 'file_submit.html?reply=' . urlencode('reply_n_up_s'));
				exit();
			}
			
			
			//Update Database
			$sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.filerpl = ' . $mysql->quote($tempName) . ',
						im.status=1, 
						im.reply_date_time=now(),
						im.supplier_id=' . $supplier->getUserId() . ',
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where um.id = im.user_id and im.id=' . $mysql->getInt($id);
			$mysql->query($sql);
			
			header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_ord_succ'));
			exit();
		}
	}
	else if($code != "")
	{
		//Update Database
		$sql = 'update 
					' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
					set
					im.unlock_code = ' . $mysql->quote($code) . ',
					im.status=1,
					im.reply_date_time=now(),
					im.supplier_id=' . $supplier->getUserId() . ',									
					um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
					where um.id = im.user_id and im.id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_ord_succ'));
		exit();
	}
	else
	{
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file_update.html?id=" . $id . "&reply=" .urlencode('reply_en_cde_unl_succ'));
		exit();
	}
		
?>