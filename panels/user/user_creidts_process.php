<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_credit_64569j766428');	
	
	$objCredits = new credits();
    
    $id = $request->PostInt('id');
    $username = $request->PostStr('username');
    $email = $request->PostStr('email');
	
	$user_id=$member->getUserId();
	$sql='select * from ' . USER_MASTER .' where id=' . $user_id;
	$query=$mysql->query($sql);
        $rows=$mysql->fetchArray($query);
       
        
    $creditType = $request->PostInt('creditType');
    $crAdd = $request->PostInt('crAdd');
    $crRevoke = $request->PostInt('crRevoke');
   
    
    $sql='select * from ' . USER_MASTER .' where id=' . $id;
	$query=$mysql->query($sql);
        $rows1=$mysql->fetchArray($query);
         $reseller_id=$rows1[0]["reseller_id"];
          
    if ($user_id != $reseller_id) 
        {
    header("location:" . CONFIG_PATH_SITE_USER . "user_credits.html?id=" . $id . "&reply=" . urlencode('Error:This User Does not Belong to your user.'));
		exit();
        }
    
    
    if(($creditType == 1 and $crAdd == 0) or ($creditType == 0 and $crRevoke == 0))
    {
		header("location:" . CONFIG_PATH_SITE_USER . "user_credits.html?id=" . $id . "&reply=" . urlencode('reply_insuffi_credits'));
		exit();
    }
	
	if($creditType == 1)
	{
	
		$crAcc = 0;
		$sql_credits = 'select id, credits, user_credit_transaction_limit from ' . USER_MASTER . ' where id=' . $member->getUserId();
		$query_credits = $mysql->query($sql_credits);
		if($mysql->rowCount($query_credits)>0)
		{
			$row_credits = $mysql->fetchArray($query_credits);
			$crAcc = $row_credits[0]["credits"];
			$credit_limit=$row_credits[0]['user_credit_transaction_limit'];
			if($crAcc < $crAdd )
			{
				header('location:' . CONFIG_PATH_SITE_USER . 'user_credits.html?id=' . $id . '&reply=' . urlencode('Insufficient Credits'));
				exit();
			}
			
//			if($crAdd > $credit_limit)
//			{
//				header('location:' . CONFIG_PATH_SITE_USER . 'user_credits.html?id=' . $id . '&reply=' . urlencode('Increase Transaction Limit'));
//				exit();
//			}
		}
		$objCredits->transfer($member->getUserID(), $id, $crAdd, 'Credits Added');
		$sql = 'update ' . USER_MASTER . ' um1, ' . USER_MASTER . ' um2
					set
					um1.credits= um1.credits +' .$mysql->getFloat($crAdd) . ',
					um1.credits_used= um1.credits_used -' .$mysql->getFloat($crAdd) . ',
					um2.credits=um2.credits - '. $mysql->getFloat($crAdd) . ',
					um2.credits_used= um2.credits_used +' .$mysql->getFloat($crAdd) . '
					where um1.id=' . $mysql->getInt($id) . ' and um2.id=' . $mysql->getInt($member->getUserId());
		$mysql->query($sql);
		if($mysql->rowCount($query)>0)
		{
			
			$email_1=$rows[0]['email'];
			$username_1=$rows[0]['username'];
			$credits_add=$crAdd;
			$credits_revoke=$crRevoke;
			
        $email_config = $objEmail->getEmailSettings();
	$from_admin 		= $email_config['admin_email'];
	$admin_from_disp	= $email_config['system_from'];
			//mail to user
			$args = array(
				'to' => $email_1,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'user_id'=>$user_id,
				'save'=>1,
				'credits_add'=>$credits_add,
                                'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_add_credits_user', $args);
			//mail to other user
			$args = array(
				'to' => $email,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'user_id'=>$id,
				'save'=>1,
				'credits_add'=>$credits_add,
                                'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_add_credits_other_user', $args);
			// mail to admin
			$args = array(
				'to' => $from_admin,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'credits_add'=>$credits_add,
				'site_admin' => CONFIG_SITE_NAME,
                                'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_add_credits_admin', $args);
		
		
		}
		header("location:" . CONFIG_PATH_SITE_USER . "user_credits.html?id=' . $id . '&reply=" . urlencode('reply_credit_success'));
		exit();
	}
	else
	{
		$crAcc = 0;
		$sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($id);
		$query_credits = $mysql->query($sql_credits);
		$row_credits = $mysql->fetchArray($query_credits);
		$crAcc = $row_credits[0]["credits"];
		
		if($crAcc < $crRevoke)
		{
			header('location:' . CONFIG_PATH_SITE_USER . 'user_credits.html?id=' . $id . '&reply=' . urlencode('reply_insuff_credits'));
			exit();
		}
		
		$objCredits->transfer($id, $member->getUserID(), $crRevoke, 'Credits Revoked');
		$sql = 'update ' . USER_MASTER . ' um1, ' . USER_MASTER . ' um2
					set
					um1.credits=um1.credits - '. $mysql->getFloat($crRevoke) . ',
					um1.credits_used=um1.credits_used + '. $mysql->getFloat($crRevoke) . ',
					um2.credits=um2.credits + '. $mysql->getFloat($crRevoke) . ',
					um2.credits_used=um2.credits_used - '. $mysql->getFloat($crRevoke) . ' 
					where um1.id=' . $mysql->getInt($id) . ' and um2.id=' . $mysql->getInt($member->getUserId());
		$mysql->query($sql);
		
		if($mysql->rowCount($query)>0)
		{
			 $email_config = $objEmail->getEmailSettings();
	$from_admin 		= $email_config['admin_email'];
                        $rows=$mysql->fetchArray($query);
			$email_1=$rows[0]['email'];
			$username_1=$rows[0]['username'];
			$credits_revoke=$crAdd;
			$credits_revoke=$crRevoke;
			//mail to user
			$args = array(
				'to' => $email_1,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'user_id'=>$user_id,
				'save'=>1,
				'credits_revoke'=>$credits_revoke,
                             'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_revoke_credits_user', $args);
			//mail to other user
			$args = array(
				'to' => $email,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'user_id'=>$id,
				'save'=>1,
				'credits_revoke'=>$credits_revoke,
                             'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_revoke_credits_other_user', $args);
			// mail to admin
			$args = array(
				'to' => $from_admin,
				'from' => $email_1,
				'fromDisplay' => CONFIG_SITE_NAME,
				'username_1' => $username_1,
				'username' => $username,
				'credits_revoke'=>$credits_revoke,
				'site_admin' => CONFIG_SITE_NAME,
                             'send_mail' => true
				);
			$objEmail->sendEmailTemplate('user_revoke_credits_admin', $args);
		}
		header("location:" . CONFIG_PATH_SITE_USER . "user_credits.html?id=' . $id . '&reply=" . urlencode('reply_credit_revoke_success'));
		exit();
	}
	
	
	
?>