<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$id=$request->postInt('id');
	
	$user_id=$member->getUserId();
	$sql_detail='select username,email from ' . USER_MASTER . ' where id=' . $user_id ;
	$query_detail=$mysql->query($sql_detail);
	
	$member->checkLogin();
	$member->reject();	
    
        $details = $request->PostStr('details');
	$id = $request->PostInt('id');
	
	$sql_1='select subject from ' . TICKET_MASTER . ' where ticket_id=' . $id;
	$query_1=$mysql->query($sql_1);
			
	if($details == "")
	{
		header("location:" . CONFIG_PATH_SITE_USER . "ticket_details.html?id=" . $id . "&reply=" . urlencode('reply_empty_reply'));
		exit();
	}
	
	
	$sql = 'insert into ' . TICKET_DETAILS . '
			(ticket_id, comments, user_admin, date_time)
			values(
			' . $mysql->getInt($id) . ',
			' . $mysql->quote($details) . ', 
			0, now())';
	$mysql->query($sql);
	if($mysql->rowCount($query_1)>0)
	{
		$rows_1=$mysql->fetchArray($query_1);
		$ticket_subject=$rows_1[0]['subject'];
		if($mysql->rowCount($query_detail)>0)
		{
			
			$rows_detail=$mysql->fetchArray($query_detail);
			$username=$rows_detail[0]['username'];
			$email=$rows_detail[0]['email'];
			$subjects=$mysql->fetchArray($query_1);	
			//mail to user
			$objEmail = new email();
                       $email_config = $objEmail->getEmailSettings();
            $admin_email = $email_config['admin_email'];
            $system_from = $email_config['system_email'];
            $from_display = $email_config['system_from'];
			$args = array(
						'to' => $admin_email,
						'from' => $system_from,
						'fromDisplay' => $from_display,
						'user_id' => $id,
						'save' => '1',
						'username' => CONFIG_SITE_NAME,
						'ticket_subject' => $ticket_subject,
                                                'send_mail'  => true
						);
			//$objEmail->sendEmailTemplate('user_add_post_ticket_user', $args);
			// mail to admin
			//$objEmail = new email();
			$args = array(
						'to' => $admin_email,
						'from' => $system_from ,
						'fromDisplay' => $from_display,
						'username' => $username,
						'ticket_subject' => $ticket_subject,
						'site_admin' => 'Admin',
                                                'send_mail'  => true
						);
			$objEmail->sendEmailTemplate('user_add_post_ticket_admin', $args);
		}
	}
	header("location:" . CONFIG_PATH_SITE_USER . "ticket_details.html?id=" . $id . "&reply=" . urlencode('reply_post_ticket_success'));
	exit();
?>