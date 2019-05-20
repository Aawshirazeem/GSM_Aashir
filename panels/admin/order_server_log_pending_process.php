<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$objCredits = new credits();
	
	$admin->checkLogin();
	$admin->reject();

	$qStrIds = "";
	$Ids = $_POST['Ids'];
	$type = $request->PostStr('type');
	$supplier_id = $request->PostStr('supplier_id');
	$server_log_id = $request->PostInt('server_log_id');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');
	
	$pString='';
	if($supplier_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($ip != '')
	{
		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	if($server_log_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'server_log_id=' . $server_log_id;
	}	
	$pString = trim($pString, '&');
	
	
	$objEmail 			= new email();
	$email_config 		= $objEmail->getEmailSettings();
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
	
	foreach($Ids as $id)
	{
	
		$username=$request->PostStr('username_' . $id);
		$email=$request->PostStr('email_' . $id);
		$order_id=$id;
		$user_id=$request->PostStr('user_id_' . $id);
		$server_log_name=$request->PostStr('server_log_name_' . $id);
		$credits=$request->PostStr('credits_' . $id);			
		if(isset($_POST['locked_' . $id]))
		{
			$qStrIds .= $mysql->getInt($id) . ',';
		}
		
		if(isset($_POST['accept_' . $id]))
		{
		
			$sql = 'select * from ' . ORDER_SERVER_LOG_MASTER . ' oim where id=' . $mysql->getInt($id);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
				
			$objCredits->processServerLog($id, $rows[0]['user_id'], $rows[0]['credits']);
			
			$sql = 'update 
						' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where um.id = im.user_id and im.id=' . $mysql->getInt($id);

			$mysql->query($sql);
			
			
			$args = array(
						'to' => $email,
						'from' => $from_admin,
						'fromDisplay' => $admin_from_disp,
						'order_id' => $order_id,
						'user_id' => $user_id,
						'save' => '1',
						'username' => $username,
						'server_log_name' => $server_log_name,
						'username' => $username,
						'credits'=>	$credits,
						'site_admin' => $admin_from_disp,
						'send_mail' => true
						);
						
			$objEmail->sendEmailTemplate('admin_user_order_server_log_avail', $args);
		}
		if(isset($_POST['reject_' . $id]))
		{
			$sql = 'select * from ' . ORDER_SERVER_LOG_MASTER . ' oim where id=' . $mysql->getInt($id);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			if(!isset($_POST['accept_' . $id]))
			{
				$objCredits->returnServerLog($id, $rows[0]['user_id'], $rows[0]['credits']);
				
				$sql = 'update 
							' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=2, 
								im.reply_date_time=now(),
								im.message=' . $mysql->quote($_POST['un_remarks_' . $id]) . ',
								um.credits = um.credits + im.credits,
								um.credits_inprocess = um.credits_inprocess - im.credits
							where um.id = im.user_id and im.id=' . $mysql->getInt($id);
				$mysql->query($sql);
				
				
				$args = array(
							'to' => $email,
							'from' => $from_admin,
							'fromDisplay' => $admin_from_disp,
							'order_id' => $order_id,
							'user_id' => $user_id,
							'save' => '1',
							'username' => $username,
							'server_log_name' => $server_log_name,
							'username' => $username,
							'credits'=>	$credits,
							'site_admin' => $admin_from_disp,
							'send_mail' => true
							);
				$objEmail->sendEmailTemplate('admin_user_order_server_log_unavail', $args);

			}
		}
	}

	$qStrIds = substr($qStrIds, 0, strlen($qStrIds)-1);
	
	if($qStrIds != '')
	{
		$sql = 'update ' . ORDER_SERVER_LOG_MASTER . ' set status=-1 where id in (' . $qStrIds . ')';
		$mysql->query($sql);
	}
	
	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_server_log.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_server_log_update'));
	exit();
?>