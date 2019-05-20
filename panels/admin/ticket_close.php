<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
    
	$id = $request->GetInt('id');

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
                    $from_admin = $email_config['system_email'];
                    $admin_from_disp = $email_config['system_from'];
		$args = array(
					'to' => $email,
					'from' => $from_admin,
					'fromDisplay' => $admin_from_disp,
					'user_id' => $user_id,
					'save' => '1',
					'username' => $username,
					'ticket_subject' => $ticket_subject,
					'site_admin' => 'Admin',
                    'send_mail'  => true
					);
		$objEmail->sendEmailTemplate('admin_close_support_ticket_user', $args);
              //  var_dump($args);exit;
		// mail to admin
		//$objEmail = new email();
		$args = array(
					'to' => CONFIG_EMAIL,
					'from' => CONFIG_EMAIL,
					'fromDisplay' => CONFIG_SITE_NAME,
					'ticket_subject' => $ticket_subject,
					'username' => $username,
					'site_admin' => CONFIG_SITE_NAME
					);
		//$objEmail->sendEmailTemplate('admin_close_support_ticket_admin', $args);
	}
	
	header('location:' . CONFIG_PATH_SITE_ADMIN . 'ticket.html?id=' . $id . '&reply=' . urlencode('reply_tic_close'));
	exit();
?>