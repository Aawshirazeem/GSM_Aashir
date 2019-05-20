<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$validator->formValidateUser('user_credit_tran_198898');
	$objCredits = new credits();
    
    $username = $request->PostStr('username');
    $email = $request->PostStr('email');
    $credits = $request->PostInt('credits');
    
    if($credits <= 0)
    {
		header("location:" . CONFIG_PATH_SITE_USER . "credits_transfer.html?reply=" . urlencode('reply_0_credit'));
		exit();
    }
    
	$sql = 'select credits,id,username,email, user_credit_transaction_limit from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$cr = $rows[0]['credits'];
	$cr_transac_limit = $rows[0]['user_credit_transaction_limit'];
	$username_1 = $rows[0]['username'];
	$user_id = $rows[0]['id'];
	$email_1 = $rows[0]['email'];
	
	//echo $cr . ' <= 0 or ' . $cr  . ' <= ' . $credits;
	if($cr <= 0 or $cr <= $credits)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "credits_transfer.html?reply=" . urlencode('reply_insufficient_credit'));
		exit();
	}
	if($cr_transac_limit > 0 && $cr_transac_limit < $credits)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "credits_transfer.html?reply=" . urlencode('reply_credit_limit'));
		exit();
	}
	
    $sql = 'select * from ' . USER_MASTER . ' where username=' . $mysql->quote($username) . ' and email=' . $mysql->quote($email);
    $query = $mysql->query($sql);
    if($mysql->rowCount($query) > 0)
    {
    	$rows = $mysql->fetchArray($query);
    	$id = $rows[0]['id'];
	    if($id == $member->getUserId())
	    {
			header("location:" . CONFIG_PATH_SITE_USER . "credits_transfer.html?reply=" . urlencode('reply_own_acccont_credit'));
			exit();
	    }

		$objCredits->transfer($member->getUserID(), $id, $credits, 'Credit Transection');
		$sql = 'update ' . USER_MASTER . ' um1, ' . USER_MASTER . ' um2
					set
					um1.credits=um1.credits + '. $mysql->getFloat($credits) . ',
					um2.credits=um2.credits - '. $mysql->getFloat($credits) . ' 
					where um1.id=' . $mysql->getInt($id) . ' and um2.id=' . $mysql->getInt($member->getUserId());
		
		$mysql->query($sql);
		
		$objEmail = new email();
		$args = array(
					'to' => $email_1,
					'from' => $email_1,
					'fromDisplay' => CONFIG_SITE_NAME,
					'user_id' => $user_id,
					'save' => '1',
					'username_1' => $username_1,
					'username' => $username,
					'credits'=>	$credits,
					'site_admin' => CONFIG_SITE_NAME
					);

		$objEmail->sendEmailTemplate('user_transfer_credit_user', $args);
		$objEmail = new email();
		$args = array(
					'to' => CONFIG_EMAIL,
					'from' => $email_1,
					'fromDisplay' => CONFIG_SITE_NAME,
					'username_1' => $username_1,
					'username' => $username,
					'credits'=>	$credits,
					'site_admin' => CONFIG_SITE_NAME
					);
		$objEmail->sendEmailTemplate('user_transfer_credit_admin', $args);
		$objEmail = new email();
		$args = array(
					'to' => $email,
					'from' => $email_1,
					'fromDisplay' => CONFIG_SITE_NAME,
					'user_id' => $id,
					'save' => '1',
					'username_1' => $username_1,
					'username' => $username,
					'credits'=>	$credits,
					'site_admin' => CONFIG_SITE_NAME
					);

		$objEmail->sendEmailTemplate('user_transfer_credit_other_user', $args);
		
		
		
		header("location:" . CONFIG_PATH_SITE_USER . "credits_history.html?reply=" . urlencode('reply_transfer_credit'));
		exit();
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_USER . "credits_transfer.html?reply=" . urlencode('reply_invalid_login'));
		exit();
	}
?>