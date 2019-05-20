<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('service_imei_file_edit_14832342');

	$id = $request->GetInt('id');
        $type=$request->GetInt('type');
	if($type==1){
	$sql = 'update ' . API_MASTER . ' set status=1 where id=' . $id;
	$mysql->query($sql);
	
	header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_list.html?reply' . urlencode('reply_api_activated_successfully'));
	exit();
        }
        else if($type==2)
        {
            $sql = 'update ' . API_MASTER . ' set is_visible=0 where id=' . $id;
	$mysql->query($sql);
	
	header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_list.html?reply' . urlencode('reply_api_activated_successfully'));
	exit();
        }
        else
        {
            
        }
?>