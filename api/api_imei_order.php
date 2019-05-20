<?php
	require_once("_init.php");
	$request= new request();
	$mysql = new mysql();
	$xml = new xml();
	$api = new api();
	$objImei = new imei();
	$objCredits = new credits();
	
	$objEmail = new email();
	$email_config = $objEmail->getEmailSettings();	
	$admin_email    = $email_config['admin_email'];
	$system_from 	= $email_config['system_email'];
	$from_display 	= $email_config['system_from'];
	
	
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
	
	
	
	
	/*******************************************
	 *****    Check and make IMEI Array    *****
	 *******************************************/
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
	
	$sql = 'select id from ' . IMEI_TOOL_MASTER . ' where id=' . $tool . ' and status=1';
	$query = $mysql->query($sql);
	// Return if there no IMEI supplied
    if($mysql->rowCount($query) == 0)
    {
		echo $xml->error($member->getUserId(), 'Invalid IMEI tool.', '30000', true);
		exit();
    }
	// Return if there no IMEI supplied
    if(($imeis == "" or $imeis == "0") && $imei == "")
    {
		echo $xml->error($member->getUserId(), 'No IMEI found, please try again!', '30000', true);
		exit();
    }
	
	/* Get package id for the user */
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	
	
	$api_key = $req->PostStr('api_key');
	$member = new member_api($api_key);
	
	$crM = $objCredits->getMemberCreditsAPI($member->getUserId());
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
	
	/************************************************
	 *****    check Pack and Special Credits    *****
	 ************************************************/
	$sql_cr = 'select
					tm.api_id, tm.api_service_id, tm.credits,
					tm.accept_duplicate, tm.verify_checksum,
					uscm.credits as splCr,
					pd.credits as packageCr
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_SPL_CREDITS . ' uscm on (tm.id = uscm.tool and uscm.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pd on(tm.id = pd.tool_id and pd.package_id=' . $package_id . ')
				where tm.id=' . $mysql->getInt($tool);
	$query_cr = $mysql->query($sql_cr);
	$rows_cr = $mysql->fetchArray($query_cr);
	$row_cr = $rows_cr[0];
	$accept_duplicate = $row_cr['accept_duplicate'];
	$verify_checksum = $row_cr['verify_checksum'];
	$cr = $row_cr['credits'];
	
	$cr_discount = 0;
	if($row_cr['packageCr'] != '')
	{
		$cr_discount = $cr - $row_cr['packageCr'];
		$cr = $row_cr['packageCr'];
	}
	if($row_cr['splCr'] != '')
	{
		$cr_discount = $cr - $row_cr['splCr'];
		$cr = $row_cr['splCr'];
	}
	$cr_amount = $cr;
	if($crM['default'] == 0)
	{
		$cr_amount = $cr * $crM['rate'];
	}

	$crAcc = 0;
	$sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $member->getUserId();
	$query_credits = $mysql->query($sql_credits);
	$row_credits = $mysql->fetchArray($query_credits);
	$crAcc = $row_credits[0]["credits"];
	$crTotal = $cr * count($imeiList);
	
	// Processing credits should not less be then credits in account
	if($crAcc < $crTotal)
	{
		echo $xml->error($member->getUserId(), 'Insufficient Credits', '30005', true);
		exit();
	}
	
	
	// Process if we have suffcient credits
    if($crAcc >= $crTotal)
    {
    	foreach($imeiList as $imei)
    	{
    		if(!$objImei->checkIMEI($imei))
    		{
				echo $xml->error($member->getUserId(), 'Invalid IMEI![' . $imei . ']', '30000', true);
				exit();
    		}
    	}
		
		
		$sql_api = 'select 
							tm.api_id, tm.custom_field_name,
							am.api_server, am.username, am.password, am.key, am.url,
							ad.service_id as api_service_id,
							ad.model, ad.provider, ad.network
						from ' . IMEI_TOOL_MASTER . ' tm 
						left join ' . API_MASTER . ' am on (tm.api_id = am.id)
						left join ' . API_DETAILS . ' ad on (ad.id = tm.api_service_id)
						where tm.id=' . $mysql->getInt($tool) . ' and am.status=1';
		$query_api = $mysql->query($sql_api);
		$args = array();
		$api_id = 0;
		$api_service_id = 0;
		$api_name = '';
		if($mysql->rowCount($query_api) > 0)
		{
			$rows_api = $mysql->fetchArray($query_api);
			if($rows_api[0]['api_id'] != "0")
			{
				$api_id = $rows_api[0]['api_id'];
				$api_name = $rows_api[0]['api_server'];
				$args['service_id'] = $rows_api[0]['api_service_id'];
				$api_service_id = $rows_api[0]['api_service_id'];
				$args['username'] = $rows_api[0]['username'];
				$args['password'] = $rows_api[0]['password'];
				$args['key'] = $rows_api[0]['key'];
				$args['url'] = $rows_api[0]['url'];
				$args['model'] = $rows_api[0]['model'];
				$args['provider'] = $rows_api[0]['provider'];
				$args['network'] = $rows_api[0]['network'];
				$args[$rows_api[0]['custom_field_name']] = $custom_value;
			}
		}
		
		
		
		$api = new api();
		$count = 1;
	   	foreach($imeiList as $imei)
	   	{
	   		if($imei != "")
	   		{


				// check for dupliate IMEIs
				$args['imei'] = $imei;
				if($accept_duplicate == 0)
				{
					$sql = 'select id
								from ' . ORDER_IMEI_MASTER . '
								where imei = ' . $mysql->quote($imei) . '
								and user_id = ' . $member->getUserId() . '
								and tool_id = ' . $mysql->getInt($tool) . '
								and status in (0,1,2)
								limit 1';
					$preCount = $mysql->rowCount($mysql->query($sql));
					if($preCount >= 1)
					{
						$rows_dup = $mysql->fetchArray($mysql->query($sql));
						echo $xml->error($member->getUserId(), 'Dulicate IMEI! [' . $imei . '] order ID:' . $rows_dup[0]['id'], '30000', true);
						exit();
					}
				}
			
				// $sql = 'select id from ' . ORDER_IMEI_MASTER . ' where tool_id=' . $tool . ' and  imei = ' . $mysql->quote($imei) . ' limit 1';
				// $preCount = $mysql->rowCount($mysql->query($sql));
				// //If the imei alredy exists in our database then just reject it
				// if($preCount >= 1)
				// {
					// $rows_dup = $mysql->fetchArray($mysql->query($sql));
					// echo $xml->error($member->getUserId(), 'Dulicate IMEI! [' . $imei . '] order ID:' . $rows_dup[0]['id'], '30000', true);
					// exit();
				// }
				
				//get IP address
				$ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
				$ip = $ip[0];
				
				
				
				// Insert imei and api details in the order table
				$sql = 'insert into ' . ORDER_IMEI_MASTER . ' 
							(tool_id, user_id, ip, imei, date_time,
								credits, credits_amount, credits_discount,
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
							' . $tool . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $cr . ',
							' . $cr_amount . ',
							' . $cr_discount . ',
							' . $brand . ',
							' . $model . ',
							' . $country . ',
							' . $network . ',
							' . $mep . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote($prd) . ',
							' . $mysql->quote($itype) . ',
							' . $mysql->quote($email) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . ',
							0
							)';
				$mysql->query($sql);

				$newOrderID = $mysql->insert_id();		
				
				//Cut member credits
				$objCredits->cutOrderCredits($newOrderID, $cr_amount, $member->getUserID(), 1);
				
				
				$val = '';
				$val .= $xml->element('imei', $imei);
				$val .= $xml->element('id', $newOrderID);
				echo $xml->parent('imei' . $count, $val);
				$count++;
				
				
				//Send API new IMEI Order to Admin
				$args = array(
							'to' => $admin_email,
							'from' => $system_from,
							'fromDisplay' => $from_display,
							'username' => $member->getUsername(),
							'order_id' => $newOrderID,
							'imei' => $imei,
							'tool_name' => $tool_name,
							'credits' => $amount,
							'send_mail'  => true
							);

				$objEmail->sendEmailTemplate('admin_order_imei_new', $args);
				
			}
		}
		echo $xml->end();
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?msg=' . urlencode('Insufficient Credits!'));
		exit();
	}

	
	
	
	

?>