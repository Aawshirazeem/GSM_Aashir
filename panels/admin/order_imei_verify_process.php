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
	
	
	//// if IMEI Download button is pressed ////
	$download=$request->PostStr('download');
	if($download == 'Download')
	{
		include(CONFIG_PATH_ADMIN_ABSOLUTE . 'order_imei_download.php');
		exit();
	}
	////////////////////////////////////////////
	$emailObj = new email();
	$body = '';
	
	
	$emailObj = new email();
	$email_config = $emailObj->getEmailSettings();
	$admin_email 		= $email_config['admin_email'];
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
	$support_email		= $email_config['support_email'];
	$signatures			= "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
	
	foreach($Ids as $id)
	{
		$sql = 'select um.imei,u.email,um.reply,um.credits
				from 
				'.ORDER_IMEI_MASTER.' um, '.USER_MASTER.' u
				where
				um.user_id = u.id
				AND
				um.id=' . $mysql->getInt($id);
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);
		
		if(isset($_POST['unavailable_' . $id]))
		{

			
			$objCredits->refundIMEI($id, $rows[0]['user_id'], $rows[0]['credits']);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set 
						im.status=3,
						im.verify=2,
						im.reply="", 
						im.reply_date_time=now(),
						im.message=' . $mysql->quote(base64_encode($_POST['un_remarks_' . $id])) . ',
						um.credits = um.credits + im.credits, um.credits_used = um.credits_used - im.credits
						where im.status=2 and um.id = im.user_id and im.id=' . $mysql->getInt($id);
			$mysql->query($sql);
			//entery credit log
                        		$sql= '	insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
									credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
									
									select 
											oim.user_id,
											now(),
											oim.credits,
											um.credits - oim.credits,
											um.credits_inprocess + oim.credits,
											um.credits_used,
											um.credits,
											um.credits_inprocess,
											um.credits_used,
											oim.id,
											' . $mysql->quote("Verification Rejected") . ',
											0,
											' . $mysql->quote($ip) . '
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id =' . $mysql->getInt($id) . ';';
                                        $mysql->query($sql);
			/// Email code
				
				$body = '
				<h2><font color="red">Your Order is Not Verified</font></h2>
				<p><b>IMEI:</b>' . $rows[0]['imei'] . '<p>
				<p><b>Unlock Code:</b>' . base64_decode($row[0]['reply']) . '<p>
				<p><b>Credits:</b>' . $rows[0]['credits']. '<p>
				<p><b>Order ID :</b>' . $id. '<p>
				<p>--------------------------------------------------				
				';
				
				$emailObj->setTo($rows[0]['email']);
				$emailObj->setFrom($from_admin);
				$emailObj->setFromDisplay($admin_from_disp);
				$emailObj->setSubject("Your Order is Not Verified");
				$emailObj->setBody($body);
				//$sent = $emailObj->sendMail();
                                 $save = $emailObj->queue();
		}
		else
		{
			$unlock_code = $request->PostStr('unlock_code_' . $id);
			$unlock_code2 = $request->PostStr('unlock_code_' . $id . '_2');
			if((isset($_POST['verify_' . $id])))
			{
				$sql = 'update ' . ORDER_IMEI_MASTER . '
								set reply_date_time=now(),
								verify=2,
								reply=' . $mysql->quote(base64_encode($unlock_code)) . '
							where status=2 and id=' . $mysql->getInt($id);
				$mysql->query($sql);
				
				/// Email code
				
				$body = '
				<h2>Your Order is Verified</h2>
				<p><b>IMEI:</b>' . $rows[0]['imei'] . '<p>
				<p><b>Unlock Code:</b>' .$unlock_code. '<p>
				<p><b>Credits:</b>' . $rows[0]['credits']. '<p>
				<p><b>Order ID :</b>' . $id. '<p>
				<p>--------------------------------------------------				
				';
				
				$emailObj->setTo($rows[0]['email']);
				$emailObj->setFrom($from_admin);
				$emailObj->setFromDisplay($admin_from_disp);
				$emailObj->setSubject("Your Order is Verified");
				$emailObj->setBody($body);
			//	$sent = $emailObj->sendMail();
                                 $save = $emailObj->queue();
			}
			
		}
	}
	$qStrIds = substr($qStrIds, 0, strlen($qStrIds)-1);
	
	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_imei.html?type=" . $type . "&supplier_id=" . $supplier_id . "&reply=" .urlencode('reply_imei_update_success'));
	exit();
?>