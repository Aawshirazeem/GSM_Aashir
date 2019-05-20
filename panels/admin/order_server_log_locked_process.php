<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$objCredits = new credits();

	$admin->checkLogin();
	$admin->reject();

	$Ids = $_POST['Ids'];
	$type = $request->PostStr('type');
	$supplier_id = $request->PostStr('supplier_id');
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
	$pString = trim($pString, '&');
	
	//$objEmail 			= new email();
	$email_config 		= $objEmail->getEmailSettings();
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
		
	foreach($Ids as $id)
	{
		$username=$request->PostStr('username_' . $id);
		$email=$request->PostStr('email_' . $id);
                $successmailyn=$request->PostStr('sucmail_' . $id);
                $rejectmailyn=$request->PostStr('rejmail_' . $id);
		$order_id=$id;
		$user_id=$request->PostStr('user_id_' . $id);
		$server_log_name=$request->PostStr('server_log_name_' . $id);
		$credits=$request->PostStr('credits_' . $id);
		
		$sql = 'select * from ' . ORDER_SERVER_LOG_MASTER . ' oim where id=' . $mysql->getInt($id);
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);
		
		if(isset($_POST['accept_' . $id]))
		{
			//$objCredits->processServerLog($id, $rows[0]['user_id'], $rows[0]['credits']);
			
			$sql = 'update 
						' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
						im.reply_date_time=now(),
                                                im.reply=' . $mysql->quote($_POST['acc_remarks_' . $id]) . ',
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where um.id = im.user_id and im.id=' . $mysql->getInt($id);

			if($mysql->query($sql))
			{
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
						
                                if($successmailyn=="1")
                                    $objEmail->sendEmailTemplate('admin_user_order_server_log_avail', $args);
			}
                        $sql='select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits 
 from nxt_order_server_log_master as a
 left join nxt_server_log_amount_details as b
 on a.server_log_id=b.log_id
left join nxt_server_log_spl_credits_reseller as c
on a.server_log_id=c.log_id
left join nxt_user_master as d
on a.user_id=d.id
where a.id='.$id.'
and b.currency_id=d.currency_id';
	                         $query=$mysql->query($sql);
                                 $rows1=$mysql->fetchArray($query);
                                 $profit=$rows1[0]["profit"];  
                                 $reseller_id=$rows1[0]["reseller_id"];
                                 $credits_after=$rows1[0]["credits"];
                                 $credits_after=$credits_after + $profit;
                                         if($reseller_id!=0)
                                        {
                                            $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + '.$profit.'
									where um.id = '.$reseller_id.';
                                                                            ';
                                                                    
                                            $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              '.$reseller_id.',
											now(),
											'.$profit.',
                                                                                        '.$credits_after.',
                                                                                        '.$id.',    
											' . $mysql->quote("Reseller Profit from Server Log Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                                            $mysql->multi_query($sqlAvail);
                                        }
		}
		if(isset($_POST['reject_' . $id]))
		{
			if(!isset($_POST['accept_' . $id]))
			{
				$objCredits->returnServerLog($id, $rows[0]['user_id'], $rows[0]['credits']);
				
				$sql = 'update 
							' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
							im.reply_date_time=now(),
							im.message=' . $mysql->quote($_POST['un_remarks_' . $id]) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where um.id = im.user_id and im.id=' . $mysql->getInt($id);
				if($mysql->query($sql))
				{
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
                                        if($rejectmailyn=="1")	
                                            $objEmail->sendEmailTemplate('admin_user_order_server_log_unavail', $args);
				}
			}
		}
	}

	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_server_log.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_server_log_update'));
	exit();
?>