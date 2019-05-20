<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_group_edit_54964566h34');
	
	$checkIds = $_POST['checkids'];
	$UIds = $_POST['user_ids'];
	$Ids=implode(',',$UIds);
    $group_id = $request->PostInt('group_id');
    $group_name = $request->PostStr('group_name');
	if(isset($_POST['user_ids']))
	{
		$sql='delete from ' . USER_GROUP_DETAIL . '
				where group_id=' . $mysql->getInt($group_id) .'
				and	user_id in(' . $Ids .  ')';
		$mysql->query($sql);
	}
	$sql = 'insert into ' . USER_GROUP_DETAIL . ' (group_id,user_id) values';
	if(isset($_POST['checkids']))
	{
		foreach($checkIds as $checkId)
		{
				$sql .='(' . $mysql->getInt($group_id) . ',' . $checkId . '),'; 
		}
		$sql=trim($sql,',') . ';';
		$mysql->query($sql);
	}		
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group_allot.html?group_id=" . $group_id . '&group_name=' . $group_name . "&reply=" . urlencode('reply_success'));
	exit();	
?>