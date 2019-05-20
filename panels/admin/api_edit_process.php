<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('service_imei_file_edit_14832342');

	$id = $request->PostInt('id');
	$api_server= $request->PostStr('api_server');
	$url= $request->PostStr('url');
	$key= $request->PostStr('key');
	$username= $request->PostStr('username');
	$password= $request->PostStr('password');
	
	$chk_services = $request->PostCheck('chk_services');
	$chk_brand = $request->PostCheck('chk_brand');
	$chk_country = $request->PostCheck('chk_country');
	$chk_mep = $request->PostCheck('chk_mep');
	$is_special = $request->PostCheck('is_special');
	
	$status = $request->PostInt('status');
	$submit = $request->PostStr('submit');
	
	$file_service = $request->PostInt('file_service'); 

	if($api_server == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "api_edit.html?id=" . $id . "?reply=". urlencode('reply_server_name_missing'));
		exit();
	}
	
	$sql_chk ='select * from ' . API_MASTER . '
					where status=1 and is_visible=1 and api_server=' . $mysql->quote($api_server) . ' and id!=' . $mysql->getInt($id);
	//echo $sql_chk;exit;
        $query_chk = $mysql->query($sql_chk);
	
	$sqlQ = '';
	if(isset($_POST['url']))
	{
		$sqlQ .= '`url`='. $mysql->quote($url) . ',';
	}
	if(isset($_POST['key']))
	{
		$sqlQ .= '`key`='. $mysql->quote($key) . ',';
	}
	if(isset($_POST['username']))
	{
		$sqlQ .= 'username='.$mysql->quote($username) . ',';
	}
	if(isset($_POST['password']))
	{
		$sqlQ .= 'password='. $mysql->quote($password) . ',';
	}
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update ' . API_MASTER . '
					set
					api_server='. $mysql->quote($api_server) . ',
					' . $sqlQ . '
					is_special=' . $is_special . ',
					status=' . $mysql->getInt($status) . ',
					file_service=' . $mysql->getInt($file_service) . '
				where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		$args = array(
				'to' => CONFIG_EMAIL,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'api_server' => $api_server,
				'site_admin' => CONFIG_SITE_NAME
				);
		$objEmail->sendEmailTemplate('admin_edit_api', $args);
		if($submit == 'Save')
		{
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_list.html?reply=' . urlencode('reply_success')) ;
			exit();
		}
		else
		{
			$temp = '&chk_services=' . $file_service;
			$temp .= '&chk_brand=' . $chk_brand;
			$temp .= '&chk_country=' . $chk_country;
			$temp .= '&chk_mep=' . $chk_mep;
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_sync.html?id=' . $id . $temp) ;
			exit();
		}
		
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_list.html?reply=' . urlencode('reply_service_imei_api_duplicate'));
		exit();
	}	
?>