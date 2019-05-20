<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$keyword = new keyword();
    
	$admin->checkLogin();
	$admin->reject();

    $id = $request->GetInt('id');
    $key = $request->GetStr('key');
    $email = $request->GetStr('email');
    $firstC = $request->GetStr('firstC');
    $limit = $request->GetInt('limit');
    $offset = $request->GetInt('offset');
    $username = $request->GetStr('username');
	
	$getString = "";
	if($firstC != '')
	{
		$getString .= '&firstC='. $firstC;
	}
	if($limit != 0)
	{
		$getString .= '&limit='. $limit;
	}
	if($offset != 0)
	{
		$getString .= '&offset='. $offset;
	}
	if($username != '')
	{
		$getString .= '&username='. $username;
	}
	$getString = trim($getString, '&');
    
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
    
    $keyLogin = $keyword->generate(20);
    
    $sql = 'select * from ' . USER_MASTER . ' where id=' . $id  . ' and api_key=' . $mysql->quote($key);echo $sql;exit;
    $query = $mysql->query($sql);
    
    if($mysql->rowCount($query) > 0)
    {
    	$sql = 'update ' . USER_MASTER . '
						set api_key=' . $mysql->quote($keyNew) . ',
						login_key=' . $mysql->quote($keyLogin) . '
					where id=' . $mysql->getInt($id);
    	$query = $mysql->query($sql);
		
		
		$objEmail = new email();
		$email_config 		= $objEmail->getEmailSettings();
		$from_admin 		= $email_config['system_email'];
		$admin_from_disp	= $email_config['system_from'];
	        $api_url="http://".CONFIG_DOMAIN."/app";
		$args = array(
				'to' => $email,
				'from' => $from_admin,
				'fromDisplay' => $admin_from_disp,
				'user_id' => $id,
				'save' => '1',
				'api_key' => $api_url,
                                'api_url' => $api_url,
				'username' => $username,
				'site_admin' => $admin_from_disp,
				'send_mail'	=> true
				);
		$objEmail->sendEmailTemplate('admin_user_api_reset', $args);
		
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_success_api'));
		exit();
    }
    else
    {
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_invalid_details'));
		exit();
    }
    
	
?>