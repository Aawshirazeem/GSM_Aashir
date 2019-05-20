<?php
	//Check and make IMEI Array
    $imeis = $request->PostStr('imeis');
    $imei = $request->PostStr('imei');
    $imeis = $imeis . "\n" . $imei;
    $imeis = str_replace("\n",",",$imeis);
	$tempImeiList = explode(",", $imeis);
	$count=0;
	foreach($tempImeiList as $tempImei)
	{
		if($tempImei != "")
		{
			$imeiList[$count] = trim($tempImei);
			$count++;
		}
	}
    if(($imeis == "" or $imeis == "0") && $imei == "")
    {
		header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=Please enter imei');
		exit();
    }

	
    $tool = $request->PostInt('tool');
    
    $brand = $request->PostInt('brand');
    $model = $request->PostInt('model');
    $country = $request->PostInt('country');
    $network = $request->PostInt('network');
    $mep = $request->PostInt('mep');
    $pin = $request->PostStr('pin');
    $prd = $request->PostStr('prd');
    $itype = $request->PostStr('itype');
    $custom_value = $request->PostStr('custom_value');
    
    $email = $request->PostStr('email');
    $mobile = $request->PostStr('mobile');
    $message = $request->PostStr('message');
    $remarks = $request->PostStr('remarks');
	

    //check Pack and Special Credits
	$sql_cr = '
					select tm.api_id, tm.api_service_id, tm.credits, uscm.credits as splCr
					from ' . IMEI_TOOL_MASTER . ' tm
					left join ' . IMEI_SPL_CREDITS . ' uscm on (tm.id = uscm.tool and uscm.user_id = ' . $member->getUserId() . ')
					left join ' . IMEI_PACKAGE_DETAIL . ' ipd on (tm.id = uscm.tool and uscm.user_id = ' . $member->getUserId() . ')
					where tm.id=' . $tool;
	$query_cr = $mysql->query($sql_cr);
	$rows_cr = $mysql->fetchArray($query_cr);
	$row_cr = $rows_cr[0];
	$cr = $row_cr['credits'];
	if($row_cr['splCr'] != "")
	{
		$cr = $row_cr['splCr'];
	}

	$crAcc = 0;
	$sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $member->getUserId();
	$query_credits = $mysql->query($sql_credits);
	$row_credits = $mysql->fetchArray($query_credits);
	$crAcc = $row_credits[0]["credits"];
	$crTotal = $cr * count($imeiList);
	
	
	if($crAcc < $crTotal)
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode('Insufficient credits'));
		exit();
	}
	
	
    if($crAcc >= $crTotal)
    {
    	foreach($imeiList as $imei)
    	{
    		if(!$objImei->checkIMEI($imei))
    		{
				header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode('Invalid IMEI!'));
				exit();
    		}
    	}
		
		$sql_api = 'select tm.api_id, tm.api_service_id, am.username, am.password, am.key, am.url, am.table_name from ' . IMEI_TOOL_MASTER . ' tm 
						left join ' . API_MASTER . ' am on (tm.api_id = am.id)
						where tm.id=' . $tool;
		
		$query_api = $mysql->query($sql_api);
		$rows_api = $mysql->fetchArray($query_api);
		
		$a_api_id = $a_service_id = $a_username = $a_password = $a_key = $a_url = $a_imei = $a_model = $a_provider = $a_network = "";
		
		if($rows_api[0]['api_id'] != "0")
		{
			$a_api_id = $rows_api[0]['api_id'];
			$a_service_id = $rows_api[0]['api_service_id'];
			$a_username = $rows_api[0]['username'];
			$a_password = $rows_api[0]['password'];
			$a_key = $rows_api[0]['key'];
			$a_url = $rows_api[0]['url'];
			$a_table_name = $rows_api[0]['table_name'];
			$sql_api_s = 'select model, provider, network from ' . $a_table_name . ' where service_id=' . $a_service_id . ' limit 1';
			$query_api_s = $mysql->query($sql_api_s);
			if($mysql->rowCount($query_api_s) > 0)
			{
				$rows_api_s = $mysql->fetchArray($query_api_s);
				$a_model = $rows_api_s[0]['model'];
				$a_provider = $rows_api_s[0]['provider'];
				$a_network = $rows_api_s[0]['network'];
			}
		}
		$api = new api();
	   	foreach($imeiList as $imei)
	   	{
	   		if($imei != "")
	   		{
			
				$sql = 'select id from ' . ORDER_IMEI_MASTER . ' where imei = ' . $mysql->quote($imei) . ' limit 1';
				$preCount = $mysql->rowCount($mysql->query($sql));
				if($preCount >= 1)
				{
					header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode('Duplicate [' . $imei . '] IMEI!'));
					exit();
				}
				$extern_id = 0;
				$ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
				if($a_api_id != "")
				{
					$response = $api->send($a_api_id, $a_service_id, $a_username, $a_password, $a_key, $a_url, $imei, $a_model, $a_provider, $a_network, $custom_value);
					$extern_id = $response['id'];
					//echo $a_api_id . " : " . $a_service_id . " : " . $a_username . " : " . $a_password . " : " . $a_key . " : " . $a_url . " : " . $imei . " : " . $a_model . " : " . $a_provider . " : " . $a_network . " : " . $custom_value;
					//exit();
					if($extern_id == "-1")
					{
						header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode($response['msg']));
						exit();
					}
				}
				$sql = 'insert into ' . ORDER_IMEI_MASTER . ' 
							(extern_id, tool_id, user_id, ip, imei, date_time, credits, 
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
							' . $extern_id . ',
							' . $tool . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip[0]) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $cr . ',
							' . $brand . ',
							' . $model . ',
							' . $country . ',
							' . $network . ',
							' . $mep . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . ',
							0
							)';
				$mysql->query($sql);

				$newOrderID = mysql_insert_id();				
								
				$objCredits->cutOrderCredits($newOrderID, $cr, $member->getUserID(), 1);

				
			}
		}
		header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?msg=' . urlencode('IMEI(s) submitted succesflly!'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode('Insufficient Credits!'));
		exit();
	}
?>