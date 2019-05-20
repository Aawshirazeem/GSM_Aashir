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
	$cIds=implode(',',$checkIds);
	$UIds = $_POST['user_ids'];
	$Ids=implode(',',$UIds);
    $package_id = $request->PostInt('package_id');
 
  
 
  
	if(isset($_POST['user_ids']))
	{
		$sql='delete from ' . PACKAGE_USERS . '
				where package_id=' . $mysql->getInt($package_id) .'
				and	user_id in(' . $Ids .  ')';
		$mysql->query($sql);
	}
	
	
	$IDsNew = '';
	
	$sql = 'insert into ' . PACKAGE_USERS . ' (package_id,user_id) values';
	if(isset($_POST['checkids']))
	{
		foreach($checkIds as $checkId)
		{
			$sql .='(' . $mysql->getInt($package_id) . ',' . $checkId . '),'; 
			$IDsNew .= $checkId . ',';
		}
		
		$IDsNew=trim($IDsNew,',');
		$sql_del='delete from ' . PACKAGE_USERS . '
				where user_id in(' . $IDsNew .  ')';
		$mysql->query($sql_del);
		
		
		$sql=trim($sql,',') . ';';
		$mysql->query($sql);
	}	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "package_users.html?package_id=" . $package_id . "&reply=" . urlencode('reply_users_alotted'));
	exit();	
?>