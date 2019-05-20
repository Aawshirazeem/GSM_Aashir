<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_tools_spl_cr_user_list_789971255d2');
	
	$service_id=$request->PostInt('service_id');
	$user_ids=$_POST['user_ids'];
	
	$check_user= (isset($_POST['check_user'])) ? $_POST['check_user'] : array();
	
	$sql='delete from ' . FILE_SPL_CREDITS . ' where service_id=' . $service_id;
	$mysql->query($sql);
	
	$sqlInsert = $sqlDel = '';
	foreach($user_ids as $user_id)
	{
		$splCr=$request->PostFloat('spl_' . $user_id);
		if($splCr!='' && !in_array($user_id, $check_user))
		{
			$sqlInsert .= 'insert into ' 	. FILE_SPL_CREDITS . '
						(amount, service_id, user_id)
						values(' . $splCr . ', ' . $service_id . ', ' . $user_id .');';
						
			$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id='. $user_id  . ';';
		}
	}
	if($sqlInsert != '')
	{
		$mysql->multi_query($sqlInsert);
	}
	if($sqlDel != '')
	{
		$mysql->multi_query($sqlDel);
	}
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_spl_cr_user_list.html?id=" . $service_id . "&reply=" . urlencode('reply_credit_update_success'));
	exit();

?>