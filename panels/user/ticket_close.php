<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->checkLogin();
	$member->reject();
    
	$id = $request->GetInt('id');
        $details = $request->getStr('dt');

	$sql_1='select 
					um.username,um.email,tm.user_id,tm.subject
					from ' . USER_MASTER . ' um		
					left join ' . TICKET_MASTER . ' tm  on(um.id=tm.user_id)
					where tm.ticket_id=' . $id;
	$query_1=$mysql->query($sql_1);
	
	$sql = 'update ' . TICKET_MASTER . ' set status=0 where ticket_id=' . $mysql->getInt($id);
	$mysql->query($sql);
	
	
	if($mysql->rowCount($query_1))
	{
		$rows_1=$mysql->fetchArray($query_1);
		$username=$rows_1[0]['username'];		
		$user_id=$rows_1[0]['user_id'];		
		$email=$rows_1[0]['email'];		
		$ticket_subject=$rows_1[0]['subject'];
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
					'user_id' => $user_id,
					'save' => '1',
					'username' => $username,
					'ticket_subject' => $ticket_subject,
					'site_admin' => CONFIG_SITE_NAME,
                    'send_mail' => true
					);
		//$objEmail->sendEmailTemplate('user_close_support_ticket_user', $args);
		// mail to admin
		//$objEmail = new email();
		$args = array(
					'to' => $from_admin,
					'from' => $email,
					'fromDisplay' => $admin_from_disp,
					'ticket_subject' => $ticket_subject,
					'username' => $username,
                                        'tid' => $id,
					'site_admin' => CONFIG_SITE_NAME,
                                         'send_mail' => true
					);
		$objEmail->sendEmailTemplate('user_close_support_ticket_admin', $args);
	}
	header('location:' . CONFIG_PATH_SITE_USER . 'ticket.html?id=' . $id . '&reply=' . urlencode('reply_close_success'));
	exit();
?>