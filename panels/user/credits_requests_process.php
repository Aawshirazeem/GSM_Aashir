<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$id = $request->GetInt('id'); 
	$uid = $request->GetInt('uid');
	$credits = $request->GetFloat('credits');
	
	$member->checkLogin();
	$member->reject();
	$validator->formValidateAdmin('user_credits_52134757d2');
	$objCredits = new credits();
    
	if($id>0)
	{
		$user_cr = $objCredits->getUserCredits($uid);
		
		$sql = 'update '. INVOICE_REQUEST .'
					set credits='.$mysql->getFloat($credits).' ,
						status=1 where id='.$id;
		$mysql->query($sql);
		
		$sql = 'update ' . USER_MASTER . '
						set credits=credits + '. $mysql->getFloat($credits) . '
					where id=' . $mysql->getInt($uid);
		$mysql->query($sql);
		
		
		
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
				(user_id, user_id2, date_time, credits,
				credits_acc, credits_acc_process, credits_acc_used,
				credits_after, credits_after_process, credits_after_used,
				info, trans_type,ip)
				values(
					' . $mysql->getInt($uid) . ',
					0,
					now(),
					' . $mysql->getFloat($credits) . ',

					' . $mysql->getFloat($user_cr['credits']) . ',
					' . $mysql->getFloat($user_cr['process']) . ',
					' . $mysql->getFloat($user_cr['used']) . ',
					' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
					' . $mysql->getFloat(($user_cr['process'])) . ',
					' . $mysql->getInt($user_cr['used']) . ',
												
					' . $mysql->quote("Credits Added by [Auto Pay]") . ',
					6,
					' . $mysql->quote($ip) . '
				);';
		$mysql->query($sql);
		header("location:" . CONFIG_PATH_SITE_USER .  "credits_reqeusts.html?reply=" . urlencode('reply_success'));
		exit();
	}
	
	
?>