<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_group_edit_547339932');

    $id = $request->PostInt('id');
    $group = $request->PostStr('group');
    $status = $request->PostInt('status');

	$sql = 'update ' . IMEI_GROUP_MASTER . '
			set 
			group_name = ' . $mysql->quote($group) . ', 
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
        if($status==0)
        {
            $sql='update '.IMEI_TOOL_MASTER.' set visible=0 where group_id=' . $mysql->getInt($id);
	$mysql->query($sql);
        }
        else
        {
        $sql='update '.IMEI_TOOL_MASTER.' set visible=1 where group_id=' . $mysql->getInt($id);
	$mysql->query($sql);
        }
	$imei_group=$group;
	$args = array(
					'to' => CONFIG_EMAIL,
					'from' => CONFIG_EMAIL,
					'fromDisplay' => CONFIG_SITE_NAME,
					'imei_group'=>$imei_group,
					'site_admin' => CONFIG_SITE_NAME
			);
	$objEmail->sendEmailTemplate('admin_edit_imei_group', $args);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_group.html?reply=" . urlencode("reply_success"));
	exit();
?>