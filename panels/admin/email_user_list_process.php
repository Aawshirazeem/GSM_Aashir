<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

    $objCredits = new credits();
		
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('email_user_list_78454349971255d2');
	
	
	$body=$request->PostStr('body');
	$template_id=$request->PostStr('template_id');
	if($body_ta == "" and $body_select == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "email_user_list.html?reply=" . urlencode('reply_mailbody_missing'));
		exit();
	}
	
	
	if(isset($_POST['ids']))
	{
		$ids=$_POST['ids'];
		$uids=implode(',',$ids);
		
		$sql='select id,username,email from '. USER_MASTER . ' where id in(' . $uids . ')';
		$query=$mysql->query($sql);
		if($mysql->rowCount($query)>0)
		{
			$rows=$mysql->fetchArray($query);
			$objEmail = new email();
			if($body_ta !='')
			{
				foreach($rows as $row)
				{
					$args = array(
							'to' => $row['email'],
							'from' => CONFIG_EMAIL,
							'fromDisplay' => CONFIG_SITE_NAME,
							'username'=>$row['username'],
							'body' => $row['username'],
							'user_id'=>$row['id'],
							'save'=>1,
							'site_admin' => CONFIG_SITE_NAME
							);
							print_r($args);
					$objEmail->sendEmailTemplate('admin_user_email', $args);
				}
			}
			if($body_select !='')
			{
				foreach($rows as $row)
				{
					$args = array(
							'to' => $row['email'],
							'from' => CONFIG_EMAIL,
							'fromDisplay' => CONFIG_SITE_NAME,
							'username'=>$row['username'],
							'body' => $body_select,
							'user_id'=>$row['id'],
							'save'=>1,
							'site_admin' => CONFIG_SITE_NAME
							);
							print_r($args);
					$objEmail->sendEmailTemplate('admin_user_email', $args);
				}
			}
		}
	}
	header("location:" . CONFIG_PATH_SITE_ADMIN . "email_user_list.html?reply=" . urlencode('email_sent_success'));
	exit();
?>