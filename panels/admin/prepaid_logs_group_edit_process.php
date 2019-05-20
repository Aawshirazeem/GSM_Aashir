<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
    $validator->formValidateAdmin('con_pre_log_group_edit_1488734312');
	
    $id = $request->PostInt('id');
    $group = $request->PostStr('group');
    $status = $request->PostInt('status');

	$sql = 'update ' . PREPAID_LOG_GROUP_MASTER . '
			set 
			group_name = ' . $mysql->quote($group) . ', 
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
          if($status==0)
        {
            $sql='update '.PREPAID_LOG_MASTER.' set status=0 where group_id=' . $mysql->getInt($id);
	$mysql->query($sql);
        }
        else
        {
        $sql='update '.PREPAID_LOG_MASTER.' set status=1 where group_id=' . $mysql->getInt($id);
	$mysql->query($sql);
        }
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_group.html?reply=" . urlencode('reply_update_success'));
	exit();
?>