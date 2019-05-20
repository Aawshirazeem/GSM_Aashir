<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_tools_spl_cr_user_list_789971255d2');
	
	$tool_id=$request->PostInt('tool_id');
	$user_ids=$_POST['user_ids'];
	
	$check_user= (isset($_POST['check_user'])) ? $_POST['check_user'] : array();
	
	$sql='delete from ' . IMEI_SPL_CREDITS . ' where tool_id=' . $tool_id;
	$mysql->query($sql);
	
	$sqlInsert = $sqlDel = '';
	foreach($user_ids as $user_id)
	{
		$splCr=$request->PostFloat('spl_' . $user_id);
		if($splCr!='' && !in_array($user_id, $check_user))
		{
			$sqlInsert .= 'insert into ' 	. IMEI_SPL_CREDITS . '
						(amount, tool_id, user_id)
						values(' . $splCr . ', ' . $tool_id . ', ' . $user_id .');';
						
			//$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id='. $user_id  . ';';
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
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_spl_cr_user_list.html?id=" . $tool_id . "&reply=" . urlencode('reply_credit_update_success'));
	exit();

?>