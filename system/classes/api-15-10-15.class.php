<?php
/*

		 #####   #####  #     #    #######                                           
		#     # #     # ##   ##    #       #####  ###### ###### #####   ####  #    # 
		#       #       # # # #    #       #    # #      #      #    # #    # ##  ## 
		#  ####  #####  #  #  #    #####   #    # #####  #####  #    # #    # # ## # 
		#     #       # #     #    #       #####  #      #      #    # #    # #    # 
		#     # #     # #     #    #       #   #  #      #      #    # #    # #    # 
 		 #####   #####  #     #    #       #    # ###### ###### #####   ####  #    # 

*/
class api{
	public function get($api_id, $args)
	{
		
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				return $api_gsmfreedom->gsmfreedom_get($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				return $api_super_htc->superhtc_get($args);
				break;
			case '3':
				return $this->lgtool_net_get($args);
				break;
			case '4':
				return $this->infinity_online_service_get($args);
				break;
			case '5':
				return $this->imeicheck_get($args);
				break;
			case '6':
				return $this->blockcheck_get($args);
				break;
			case '7':
				return $this->bruteforcemarket_get($args);
				break;
			case '8':
				return $this->dhrufusionapi_get($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				return $UnlockBase->api_get_code($args);
				break;
			case '10':
				$api_hotunlockcode = new api_hotunlockcode();
				return $api_hotunlockcode->api_get_code($args);
				break;
			case '11':
				$api_eu_unlock = new api_eu_unlock();
				return $api_eu_unlock->api_get_code($args);
				break;
			case '12':
				$api_codedesk = new api_codedesk();
				return $api_codedesk->api_get_code($args);
				break;
			case '13':
				$api_ubox = new api_ubox();
				return $api_ubox->api_get_code($args);
				break;
			case '14':
				$api_dlgsm = new api_dlgsm();
				return $api_dlgsm->api_get_code($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	public function send($api_id, $args)
	{
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				return $api_gsmfreedom->gsmfreedom_send($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				return $api_super_htc->superhtc_send($args);
				break;
			case '3':
				return $this->lgtool_net_send($args);
				break;
			case '4':
				return $this->infinity_online_service_send($args);
				break;
			case '5':
				return $this->imeicheck_send($args);
				break;
			case '6':
				return $this->blockcheck_send($args);
				break;
			case '7':
				return $this->bruteforcemarket_send($args);
				break;
			case '8':
				return $this->dhrufusionapi_send($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				return $UnlockBase->api_place_order($args);
				break;
			case '10':
				$api_hotunlockcode = new api_hotunlockcode();
				return $api_hotunlockcode->api_place_order($args);
				break;
			case '11':
				$api_eu_unlock = new api_eu_unlock();
				return $api_eu_unlock->api_place_order($args);
				break;
			case '12':
				$api_codedesk = new api_codedesk();
				return $api_codedesk->api_place_order($args);
				break;
			case '13':
				$api_ubox = new api_ubox();
				return $api_ubox->api_place_order($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	public function credits($api_id, $args)
	{
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				return $api_gsmfreedom->gsmfreedom_credits($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				return $api_super_htc->superhtc_credits($args);
				break;
			case '3':
				return $this->lgtool_net_credits($args);
				break;
			case '4':
				return $this->infinity_online_service_credits($args);
				break;
			case '5':
				//return $this->imeicheck_credits($args);
				break;
			case '6':
				return $this->blockcheck_credits($args);
				break;
			case '7':
				return $this->bruteforcemarket_credits($args);
				break;
			case '8':
				return $this->dhrufusionapi_credits($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				return $UnlockBase->api_get_credits($args);
				break;
			case '11':
				$api_eu_unlock = new api_eu_unlock();
				return $api_eu_unlock->api_get_credits($args);
				break;
			case '12':
				$api_codedesk = new api_codedesk();
				return $api_codedesk->api_get_credits($args);
				break;
			case '13':
				$api_ubox = new api_ubox();
				return $api_ubox->api_credits($args);
				break;
			case '14':
				$api_dlgsm = new api_dlgsm();
				return $api_dlgsm->api_credits($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	
	public function sync_tools($api_id, $args)
	{
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				return $api_gsmfreedom->gsmfreedom_sync_tools($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				return $api_super_htc->superhtc_sync_tools($args);
				break;
			case '3':
				return $this->lgtool_net_sync_tools($args);
				break;
			case '4':
				return $this->infinity_online_service_sync_tools($args);
				break;
			case '5':
				return $this->imeicheck_sync_tools($args);
				break;
			case '6':
				return $this->bruteforcemarket_sync_tools($args);
				break;
			case '7':
				return $this->blockcheck_sync_tools($args);
				break;
			case '8':
				return $this->dhrufusionapi_sync_tools($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				return $UnlockBase->api_sync_tools($args);
				break;
			case '11':
				$api_eu_unlock = new api_eu_unlock();
				return $api_eu_unlock->api_sync_tools($args);
				break;
			case '12':
				$api_codedesk = new api_codedesk();
				return $api_codedesk->api_sync_tools($args);
				break;
			case '13':
				$api_ubox = new api_ubox();
				return $api_ubox->api_sync_tools($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	public function sync_brands($api_id, $args)
	{
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				//return $api_gsmfreedom->gsmfreedom_sync_brands($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				//return $api_super_htc->superhtc_sync_brands($args);
				break;
			case '3':
				//return $this->lgtool_net_sync_brands($args);
				break;
			case '4':
				//return $this->infinity_online_service_sync_brands($args);
				break;
			case '5':
				//return $this->imeicheck_sync_brands($args);
				break;
			case '6':
				//return $this->bruteforcemarket_sync_brands($args);
				break;
			case '7':
				//return $this->blockcheck_sync_brands($args);
				break;
			case '8':
				//return $this->dhrufusionapi_sync_brands($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				//return $UnlockBase->api_sync_brands($args);
				break;
			case '12':
				$api_codedesk = new api_codedesk();
				//return $UnlockBase->api_sync_brands($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	
	public function sync_country($api_id, $args)
	{
		switch($api_id)
		{
			case '1':
				$api_gsmfreedom = new api_gsmfreedom();
				//return $api_gsmfreedom->gsmfreedom_sync_country($args);
				break;
			case '2':
				$api_super_htc = new api_super_htc();
				//return $api_super_htc->superhtc_sync_country($args);
				break;
			case '3':
				//return $this->lgtool_net_sync_country($args);
				break;
			case '4':
				//return $this->infinity_online_service_sync_country($args);
				break;
			case '5':
				//return $this->imeicheck_sync_country($args);
				break;
			case '6':
				//return $this->bruteforcemarket_sync_country($args);
				break;
			case '7':
				//return $this->blockcheck_sync_country($args);
				break;
			case '8':
				//return $this->dhrufusionapi_sync_country($args);
				break;
			case '9':
				$UnlockBase = new UnlockBase();
				//return $UnlockBase->api_sync_country($args);
				break;
			default:
				break;
		}
		return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
	}
	
	
	/*****************************************
	******************************************
				Bruteforce Market
	******************************************
	******************************************/
	public function bruteforcemarket_send($args)
	{
		$api = new api_bruteforcemarket($url, $username, $key);
		$response = $api->upload($imei, $custom);
		if(isset($response['error']))
		{
			return array('id' => '-1', 'msg' => $response['error_msg']);
		}
		if($response['success'])
		{
			return array('id' => 0, 'msg' => "ok");
		}
		else
		{
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.');
		}
	}
	
	
	
	
	
	
	/*****************************************
	******************************************
	            Dhru Fusion API
	******************************************
	******************************************/
	
	public function dhrufusionapi_credits($args)
	{
		
		$api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
		$request = $api->action('accountinfo');
		if(isset($request['ERROR']))
		{
			return array('credits' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $request['ERROR'][0]['MESSAGE']);
		}
		return array('credits' => $request['SUCCESS'][0]['AccoutInfo']['credit'], 'msg' => '');
	}
	
	public function dhrufusionapi_get($args)
	{
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
		$mysql = new mysql();
		
		$para['ID'] = $args['extern_id'];
		$request = $api->action('getimeiorder', $para);

		if (defined("debug")){
			error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruCheck.log");
			print_r($request);
		}

		if(isset($request['SUCCESS']))
		{
			$code = $request['SUCCESS']['0']['CODE'];
			switch($request['SUCCESS']['0']['STATUS'])
			{
				case '3':
					$mysql = new mysql();
					$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
					$query = $mysql->query($sql);
					$rows = $mysql->fetchArray($query);
					
					$sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
					$mysql->query($sql);
					//if($mysql->rowCount($query) > 0)
					{
						$objCredits = new credits();
						$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
					}
					echo "IMEI Return : " . $args['imei'] . PHP_EOL;
				// if unlock code is available
				case '4':
					$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
					$query = $mysql->query($sql);
					$rows = $mysql->fetchArray($query);
					
					$sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=2, reply_by=3,
								reply=' . $mysql->quote(base64_encode($code)) . ',
								im.reply_date_time=now(),
								um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
								where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
					$mysql->query($sql);
					
					$objCredits = new credits();
					$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
					
					echo "IMEI Processed" . $args['imei'] . PHP_EOL;
					break;
					
				default:
					if (defined("debug")){
						print_r($request);
					}
					break;
			}
		}
		if(isset($request['ERROR']))
		{
			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set
						remarks=' . $mysql->quote($request['ERROR'][0]['MESSAGE']) . '
					where im.id=' . $mysql->getInt($args['order_id']);
			$mysql->query($sql);
			if($mysql->rowCount($query) > 0)
			{
				//$objCredits = new credits();
				//$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
			}
			echo "IMEI Return : " . $args['imei'] . PHP_EOL;
		}
		
	}
	
	public function dhrufusionapi_send($args)
	{
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		

		$api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
		
		$para['IMEI'] = $args['imei'];
		$para['ID'] = $args['service_id'];
		$para['MEP'] = $args['mep'];
		$para['MODELID'] = $args['model'];
		$para['PROVIDERID'] = $args['network'];
		$para['PIN'] = $args['mep'];
		$para['KBH'] = $args['kbh'];
		$para['PRD'] = $args['prd'];
		$para['TYPE'] = $args['mep'];
		$para['REFERENCE'] = $args['mep'];
		
		$request = $api->action('placeimeiorder', $para);
		echo '<HR />';
		//print_r($request);
		echo '<HR />';
		error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruR.log");

		if(isset($request['ERROR']))
		{
		
			/*
				REPLY FROM SERVER
				Array
				(
					[ID] => 1382
					[IMEI] => 111111111111116
					[ERROR] => Array
						(
							[0] => Array
								(
									[MESSAGE] => ValidationError123139847
									[FULL_DESCRIPTION] => error: This IMEI is already available or pending in your Account
								)
						)
					[apiversion] => 2.0.0
				)
			*/
			

			
			$mysql = new mysql();
			$msg = (isset($request['ERROR'][0]['MESSAGE'])) ? strip_tags($request['ERROR'][0]['MESSAGE']) : '';
			$msg .= (isset($request['ERROR'][0]['FULL_DESCRIPTION'][1])) ? strip_tags($request['ERROR'][0]['FULL_DESCRIPTION'][1]) : '';
			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=3,
						reply_by=3,
						im.reply_date_time=now(),
						im.message = ' . $mysql->quote($msg) . ',
						um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
					where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set
						im.remarks = ' . $mysql->quote($msg) . '
					where im.status=0 and im.id=' . $mysql->getInt($args['order_id']);
			$mysql->query($sql);
			/*if($mysql->rowCount($query) > 0)
			{
				$objCredits = new credits();
				$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
			}*/
			return array('credits' => '-1', 'msg' => $msg);
		}
		
		return array('id' => $request['SUCCESS'][0]['REFERENCEID'], 'msg' => '');
	}
	public function dhrufusionapi_sync_tools($args)
	{
		
		
		$mysql = new mysql();


		/* 
			File Service
		************************************/
		/*echo '<H2>Brands</H2>';
		$api = new api_dhrufusionapi();
		$request = $api->action('modellist');
		
		
		$sql = 'TRUNCATE TABLE ' . IMEI_BRAND_MASTER;
		$mysql->query($sql);
		$sql = 'TRUNCATE TABLE ' . IMEI_MODEL_MASTER;
		$mysql->query($sql);
		if(isset($request['SUCCESS']))
		{
			$groups = $request['SUCCESS'][0]['LIST'];
			if(is_array($groups))
			{
				foreach($groups as $group)
				{
					$brands = $group['MODELS'];
					$brandName = $group['BRAND'];

					$sql = 'insert into ' . IMEI_BRAND_MASTER . ' (id, brand, status)
								values(
								'. $group['ID'] . ',
								'. $mysql->quote($brandName) . ',
								1)';
					$mysql->query($sql);
					$brand_id = $group['ID'];

					if(is_array($brands))
					{
						foreach($brands as $model)
						{
							$sql = 'insert into ' . IMEI_MODEL_MASTER . ' (id, model, brand, status)
										values(
										'. $model['ID'] . ',
										'. $mysql->quote($model['NAME']) . ',
										'. $mysql->getInt($brand_id) . ',
										1)';
							$mysql->query($sql);
							echo 'Brand: ' . $model['NAME'] . '<br />';
							//ob_flush();
						}
					}
				}
			}
		}*/

		/* 
			IMEI
		************************************/
		echo '<H2>IMEI Services</H2>';
		$api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
		$request = $api->action('imeiservicelist');
		if(isset($request['ERROR']))
		{
			return array('credits' => '-1', 'msg' => 'Can\'t sync. services! ' . $request['ERROR'][0]['MESSAGE']);
		}
		
		$sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
		$mysql->query($sql);
		
		$groups = $request['SUCCESS'][0]['LIST'];
		if(is_array($groups))
		{
			foreach($groups as $group)
			{
				$tools = $group['SERVICES'];
				$groupName = $group['GROUPNAME'];
				foreach($tools as $tool)
				{
					/*
					echo '<td>' . $tool['ID'] . '</td>';
					echo '<td>' . $tool['TOOL_NAME'] . '</td>';
					echo '<td>' . $tool['TOOL_ALIAS'] . '</td>';
					echo '<td>' . $tool['CREDITS'] . '</td>';
					echo '<td>' . $tool['DELIVERY_TIME'] . '</td>';
					echo '<td>' . $tool['VERIFICATION'] . '</td>';
					*/
					$sql = '
							insert into ' . API_DETAILS . '
								(api_id, group_name, service_id, service_name, credits, delivery_time, type, info) 
								VALUES (
									' . $args['id'] . ',
									' . $mysql->quote($groupName) . ', 
									' . $tool['SERVICEID'] . ',
									' . $mysql->quote($tool['SERVICENAME']) . ', 
									' . $mysql->getInt($tool['CREDIT']) . ',
									' . $mysql->quote(addslashes($tool['TIME'])) . ',
									1,
									' . $mysql->quote(addslashes($tool['INFO'])) . '
									)';
					$mysql->query($sql);
					echo 'Updateing Tool: ' . $tool['SERVICENAME'] . '<br />';
					//ob_flush();
				}
			}
		}
		
		
		/* 
			File Service
		************************************/
		echo '<H2>File Services</H2>';
		$api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
		$request = $api->action('fileservicelist');
		if(isset($request['ERROR']))
		{
			$msg = isset($request['ERROR'][0]['MESSAGE']) ? $request['ERROR'][0]['MESSAGE'] : '';
			return array('credits' => '-1', 'msg' => 'Can\'t sync. services! ' . $msg);
		}
		
		

		$groups = $request['SUCCESS'][0]['LIST'];
		if(is_array($groups))
		{
			foreach($groups as $group)
			{
				$tools = $group['SERVICES'];
				$groupName = $group['GROUPNAME'];
				if(is_array($tools))
				{
					foreach($tools as $tool)
					{
						/*
						echo '<td>' . $tool['ID'] . '</td>';
						echo '<td>' . $tool['TOOL_NAME'] . '</td>';
						echo '<td>' . $tool['TOOL_ALIAS'] . '</td>';
						echo '<td>' . $tool['CREDITS'] . '</td>';
						echo '<td>' . $tool['DELIVERY_TIME'] . '</td>';
						echo '<td>' . $tool['VERIFICATION'] . '</td>';
						*/
						$sql = '
								insert into ' . API_DETAILS . '
									(api_id, group_name, service_id, service_name, credits, delivery_time, type) 
									VALUES (
										' . $args['id'] . ',
										' . $mysql->quote($groupName) . ', 
										' . $tool['SERVICEID'] . ',
										' . $mysql->quote($tool['SERVICENAME']) . ', 
										' . $tool['CREDIT'] . ',
										' . $mysql->quote($tool['TIME']) . ', 2)';
						$mysql->query($sql);
						echo 'File Service: ' . $tool['SERVICENAME'] . '<br />';
						//ob_flush();
					}
				}
			}
		}



		
		
		
		
		$sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
		$mysql->query($sql);
		
		//print_r($resultArray);
		return true;
	}
	
	
	
	
	
	
	/*****************************************
	******************************************
	               BlockCheck.net
	******************************************
	******************************************/
	public function blockcheck_get($args)
	{
		// Put your API Access key here
		if(!defined("SHTC_API_ACCESS_KEY"))
			define('SHTC_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("SHTC_API_DEBUG"))
			define('SHTC_API_DEBUG', true);
		
		// Link to the web api
		if(!defined("SHTC_API_URL"))
			define('SHTC_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_blockcheck = new api_blockcheck();
		
		$api_blockcheck->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));
		
		$resultArray = $api_blockcheck->parse2Array($api_blockcheck->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			echo $resultArray['RESULT']['ERR'];
			return;
		}
		$resultArray = $resultArray['RESULT']['IMEI1'];
		
		switch($resultArray['STATUS'])
		{
			// if unlock code is available
			case '0':
				echo 'Pending';
				echo '<pre>' . print_r($resultArray, true) . '</pre>';
				break;
			// if unlock code is available
			case '1':
			case '2':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$result = (($resultArray['STATUS']==1) ? 'Locked ' : 'Unlocked ') . '<br />';
				$result .= 'SN: ' . $resultArray['NETWORK'] . '<br />';
				$result .= 'Network: ' . $resultArray['NETWORK'] . '<br />';
				$result .= 'Model: ' . $resultArray['NETWORK'] . '<br />';
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2,
							reply_by=3,
							reply=' . $mysql->quote(base64_encode($result)) . ',
							im.reply_date_time=now(),
							um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Processed";
				break;
				
			// if imei is rejected
			case '3':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=3,
							reply_by=3,
							im.reply_date_time=now(),
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Return";
				break;
			
			default:
				if (defined("debug")){
					print_r($resultArray);
				}
				break;
		}
	}
	
	public function blockcheck_send($args)
	{
		// Put your API Access key here
		define('IMCK_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		define('IMCK_API_DEBUG', false);
		
		// Link to the web api
		define('IMCK_API_URL', $args['url']);

		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_blockcheck = new api_blockcheck();
		
		$api_blockcheck->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei'], 'country_id' => $args['model']));
		$resultArray = $api_blockcheck->parse2Array($api_blockcheck->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
		}
		//print_r($resultArray);
		return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
		
	}
	
	
	/*****************************************
	******************************************
	               IMEICheck.net
	******************************************
	******************************************/
	public function imeicheck_get($args)
	{
		// Put your API Access key here
		if(!defined("IMCK_API_ACCESS_KEY"))
			define('IMCK_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("IMCK_API_DEBUG"))
			define('IMCK_API_DEBUG', true);
		
		// Link to the web api
		if(!defined("IMCK_API_URL"))
			define('IMCK_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_imeicheck = new api_imeicheck();
		
		$api_imeicheck->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));
		
		$resultArray = $api_imeicheck->parse2Array($api_imeicheck->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			echo $resultArray['RESULT']['ERR'];
			return;
		}
		$resultArray = $resultArray['RESULT']['IMEI1'];
		
		switch($resultArray['STATUS'])
		{
			// if unlock code is available
			case '0':
				echo 'Pending';
				echo '<pre>' . print_r($resultArray, true) . '</pre>';
				break;
			// if unlock code is available
			case '1':
			case '2':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$result = (($resultArray['STATUS']==1) ? 'Locked ' : 'Unlocked ') . '<br />';
				//$result .= 'SN: ' . $resultArray['NETWORK'] . '<br />';
				$result .= 'Network: ' . $resultArray['NETWORK'] . '<br />';
				//$result .= 'Model: ' . $resultArray['NETWORK'] . '<br />';
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2,
							reply_by=3,
							reply=' . $mysql->quote(base64_encode($result)) . ',
							im.reply_date_time=now(),
							um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Processed";
				break;
				
			// if imei is rejected
			case '3':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=3,
							reply_by=3,
							im.reply_date_time=now(),
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Return";
				break;
			
			default:
				print_r($resultArray);
				break;
		}
	}
	
	public function imeicheck_send($args)
	{
		
		// Put your API Access key here
		if(!defined("IMCK_API_ACCESS_KEY"))
			define('IMCK_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("IMCK_API_DEBUG"))
			define('IMCK_API_DEBUG', true);
		
		// Link to the web api
		if(!defined("IMCK_API_URL"))
			define('IMCK_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_imeicheck = new api_imeicheck();
		
		$api_imeicheck->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei']));
		$resultArray = $api_imeicheck->parse2Array($api_imeicheck->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
		}
		//print_r($resultArray);
		return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
		
	}
	
	
	
	/*****************************************
	******************************************
	          Infinity Onlnie service
	******************************************
	******************************************/
	public function infinity_online_service_get($args)
	{
			
		//import infinity api
		require_once(CONFIG_PATH_EXTERNAL_ABSOLUTE . 'infinity/iosapi.php');
		

		$reply = $Api->SL3JobCheck($Imei);
		if($reply['Code'] != '')
		{
			$mysql = new mysql();
			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=2,
						reply_by=3,
						reply=' . $mysql->quote(base64_encode($reply['Code'])) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
			$mysql->query($sql);
			//if($mysql->rowCount($query) > 0)
			{
				$objCredits = new credits();
				$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
			}
			echo "IMEI Processed";
			break;
		}
		elseif($reply['Result'] != '0')
		{
			$mysql = new mysql();
			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=3,
						reply_by=3,
						im.reply_date_time=now(),
						um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
					where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
			$mysql->query($sql);
			//if($mysql->rowCount($query) > 0)
			{
				$objCredits = new credits();
				$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
			}
			echo "IMEI Return";
		}
		else
		{
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $reply['Comment']);
		}
	}
	
	public function infinity_online_service_send($args)
	{

		//import infinity api
		require_once(CONFIG_PATH_EXTERNAL_ABSOLUTE . 'infinity/iosapi.php');

		//15 chars
		$Imei = $args['imei'];

		//40 chars (20 bytes hash as Hex string)
		$Hash = trim($args['custom_value']);

		$reply = $Api->SL3JobAdd($Imei, $Hash);
		if(isset($reply['jobId']))
		{
			return array('id' => $reply['jobId'], 'msg' => '');
		}
		else
		{
			$comment = isset($reply['Comment']) ? $reply['Comment'] : '';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.:' . $comment);
		}
		
	}
	
	
	/*****************************************
	******************************************
	                LGtool.net
	******************************************
	******************************************/
	public function lgtool_net_get($args)
	{
		// Put your API Access key here
		if(!defined("SHTC_API_ACCESS_KEY"))
			define('SHTC_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("SHTC_API_DEBUG"))
			define('SHTC_API_DEBUG', true);
		
		// Link to the web api
		if(!defined("SHTC_API_URL"))
			define('SHTC_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_super_htc = new api_super_htc();
		
		$api_super_htc->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));
		
		$resultArray = $api_super_htc->parse2Array($api_super_htc->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			echo $resultArray['RESULT']['ERR'];
			return;
		}
		$resultArray = $resultArray['RESULT']['IMEI1'];
		
		switch($resultArray['STATUS'])
		{
			// if unlock code is available
			case '0':
				echo 'Pending';
				echo '<pre>' . print_r($resultArray, true) . '</pre>';
				break;
			// if unlock code is available
			case '1':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2,
							reply_by=3,
							reply=' . $mysql->quote(base64_encode($resultArray['UNLOCK_CODE'])) . ',
							im.reply_date_time=now(),
							um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Processed";
				break;
				
			// if imei is rejected
			case '2':
				$mysql = new mysql();
				$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				
				$sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=3,
							reply_by=3,
							im.reply_date_time=now(),
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
				$mysql->query($sql);
				//if($mysql->rowCount($query) > 0)
				{
					$objCredits = new credits();
					$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
				}
				echo "IMEI Return";
				break;
			
			default:
				print_r($resultArray);
				break;
		}
	}
	
	public function lgtool_net_send($args)
	{
		
		// Put your API Access key here
		define('LGTN_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		define('LGTN_API_DEBUG', false);
		
		// Link to the web api
		define('LGTN_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$api_lgtool_net = new api_lgtool_net();
		
		$api_lgtool_net->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei']));
		$resultArray = $api_lgtool_net->parse2Array($api_lgtool_net->getResult());
		
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
		}
		//print_r($resultArray);
		return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
		
	}

	


	
}
?>