<?php

class api_gsmfreedom{

	var $gf_channel;
	
	var $result = array();
	
	public function gsmfreedom_credits($args)
	{
		
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		$this->sendCommand('GET_CREDITS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		//print_r($resultArray);
		return array('credits' => $resultArray['RESULT']['CREDITS'], 'msg' => '');
		
	}
	
	public function gsmfreedom_get($args)
	{
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		
		$this->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));
		
		$resultArray = $this->parse2Array($this->getResult());
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
				print_r($resultArray);
				break;
		}
	}
	
	public function gsmfreedom_send($args)
	{
		
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		
		$this->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei'], 'tool' => $args['service_id']));
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
		}
		//print_r($resultArray);
		return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
		
	}
	public function gsmfreedom_sync_tools($args)
	{
		$mysql = new mysql();
		
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		
		/* 
			IMEI
		************************************/
		echo '<H2>IMEI Services</H2>';
		$this->sendCommand('GET_TOOLS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
		$mysql->query($sql);
		
		$total = count($resultArray['RESULT']);
		for($count=1;$count<=$total;$count++)
		{
			$tool = $resultArray['RESULT']['TOOL' . $count];
			$sql = '
					insert into ' . API_DETAILS . '
						(api_id, group_name, service_id, service_name, credits, delivery_time, type) 
						VALUES (
							' . $args['id'] . ',
							' . $mysql->quote($tool['GROUP_NAME']) . ', 
							' . $tool['ID'] . ',
							' . $mysql->quote($tool['TOOL_NAME']) . ', 
							' . $tool['CREDITS'] . ',
							' . $mysql->quote($tool['DELIVERY_TIME']) . ', 1)';
			$mysql->query($sql);
			echo 'Updateing Tool: ' . $tool['TOOL_NAME'] . '<br />';
			ob_flush();
		}
		
		
		
		
		/* 
			File Services
		************************************/
		echo '<H2>File Services</H2>';
		$this->sendCommand('GET_SERVICES', array());
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$total = count($resultArray['RESULT']);
		for($count=1;$count<=$total;$count++)
		{
			$tool = $resultArray['RESULT']['SERVICE' . $count];
			$sql = '
					insert into ' . API_DETAILS . '
						(api_id, service_id, service_name, credits, delivery_time, type) 
						VALUES (
							' . $args['id'] . ',
							' . $tool['ID'] . ',
							' . $mysql->quote($tool['SERVICE_NAME']) . ', 
							' . $tool['CREDITS'] . ',
							' . $mysql->quote($tool['DELIVERY_TIME']) . ', 2)';
			$mysql->query($sql);
			echo 'Updateing File Service: ' . $tool['SERVICE_NAME'] . '<br />';
			ob_flush();
		}
		
		
		
		
		/* 
			IMEI
		************************************/
		echo '<H2>Server Logs</H2>';
		$this->sendCommand('GET_SERVER_LOGS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$total = count($resultArray['RESULT']);
		for($count=1;$count<=$total;$count++)
		{
			$tool = $resultArray['RESULT']['ORDER' . $count];
			$sql = '
					insert into ' . API_DETAILS . '
						(api_id, group_name, service_id, service_name, credits, delivery_time, type) 
						VALUES (
							' . $args['id'] . ',
							' . $mysql->quote($tool['GROUP_NAME']) . ', 
							' . $tool['ID'] . ',
							' . $mysql->quote($tool['SERVER_LOG_NAME']) . ', 
							' . $tool['CREDITS'] . ',
							' . $mysql->quote($tool['DELIVERY_TIME']) . ', 3)';
			$mysql->query($sql);
			echo 'Updateing Server Logs: ' . $tool['SERVER_LOG_NAME'] . '<br />';
			ob_flush();
		}
		
		
		$sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
		$mysql->query($sql);
		
		
		//print_r($resultArray);
		return true;
		
	}
	
	public function gsmfreedom_sync_brands($args)
	{
		$mysql = new mysql();
		
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		
		/***********************************
			Brands & Models
		************************************/
		echo '<H2>Sync. Brands</H2>';
		$this->sendCommand('GET_MODELS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$sql = 'truncate table ' . IMEI_BRAND_MASTER;
		$mysql->query($sql);
		$sql = 'truncate table ' . IMEI_MODEL_MASTER;
		$mysql->query($sql);
		
		$models = $resultArray['RESULT'];
		$brand_id = 1;
		foreach($models as $model)
		{
			$sql_group = 'select * from ' . IMEI_BRAND_MASTER . ' where brand=' . $mysql->quote($model['BRAND']);
			$query_group = $mysql->query($sql_group);
			
			if($mysql->rowCount($query_group) == 0)
			{
				$sql = 'insert into ' . IMEI_BRAND_MASTER . ' (brand, status)
								values (' . $mysql->quote($model['BRAND']) . ', 1)';
				$mysql->query($sql);
				$brand_id = $mysql->insert_id();
			}
			
			$sql = 'insert into ' . IMEI_MODEL_MASTER . ' (model, brand, status)
						values(
							' . $mysql->quote($model['MODEL']) . ',
							' . $brand_id . ',
							1
						)';
			$mysql->query($sql);
			echo 'Updateing Brand/Model: ' . $model['BRAND'] . '/' . $model['MODEL'] . '<br />';
		}
		
		
		//print_r($resultArray);
		return true;
		
	}
	
	public function gsmfreedom_sync_country($args)
	{
		$mysql = new mysql();
		
		// Put your API Access key here
		if(!defined("GFM_API_ACCESS_KEY"))
			define('GFM_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("GFM_API_DEBUG"))
			define('GFM_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("GFM_API_URL"))
			define('GFM_API_URL', $args['url']);
		
		// Check if cURL is installed or not
		if (! extension_loaded('curl'))
		{
			trigger_error('cURL extension not installed', E_USER_ERROR);
		}
		
		
		/***********************************
			Brands & Models
		************************************/
		echo '<H2>Sync. Brands</H2>';
		$this->sendCommand('GET_NETWORKS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$sql = 'truncate table ' . IMEI_COUNTRY_MASTER;
		$mysql->query($sql);
		$sql = 'truncate table ' . IMEI_NETWORK_MASTER;
		$mysql->query($sql);
		
		$netowrks = $resultArray['RESULT'];
		$country_id = 1;
		foreach($netowrks as $network)
		{
			$sql_group = 'select * from ' . IMEI_COUNTRY_MASTER . ' where country=' . $mysql->quote($network['COUNTRY']);
			$query_group = $mysql->query($sql_group);
			
			if($mysql->rowCount($query_group) == 0)
			{
				$sql = 'insert into ' . IMEI_COUNTRY_MASTER . ' (country, status)
								values (' . $mysql->quote($network['COUNTRY']) . ', 1)';
				$mysql->query($sql);
				$country_id = $mysql->insert_id();
			}
			
			$sql = 'insert into ' . IMEI_NETWORK_MASTER . ' (country, network, status)
						values(
							' . $mysql->quote($network['NETWORK']) . ',
							' . $country_id . ',
							1
						)';
			$mysql->query($sql);
			echo 'Updateing Brand/Network: ' . $network['COUNTRY'] . '/' . $network['NETWORK'] . '<br />';
		}
		
		
		//print_r($resultArray);
		return true;
		
	}
	
	function getResult()
	{
		//echo htmlentities($this->result);
		//echo '<hr />';
		return $this->result;
	}
		
	function checkError($result)
	{
		if(isset($result['ERR']))
		{
			echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			echo '<h3>' . $result['ERR'] . '</h3>';
			include('layout_footer.php');
			exit();
		}
	}
	
    function sendCommand($command, $params = array())
    {
		if (is_string($command))
		{
			// Change its value to true in case you want to debug the code
			if(!defined("GFM_API_DEBUG"))
				define('GFM_API_DEBUG', false);
			if (is_array($params))
			{
				$params['api_key'] = GFM_API_ACCESS_KEY;
				$params['command'] = $command;
				$this->gf_channel = curl_init( );
				// you might want the headers for http codes
				curl_setopt( $this->gf_channel, CURLOPT_HEADER, false );
				// you may need to set the http useragent for curl to operate as
				curl_setopt( $this->gf_channel, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				// you wanna follow stuff like meta and location headers
				curl_setopt( $this->gf_channel, CURLOPT_FOLLOWLOCATION, true );
				// you want all the data back to test it for errors
				curl_setopt( $this->gf_channel, CURLOPT_RETURNTRANSFER, true );
				// probably unecessary, but cookies may be needed to
				curl_setopt( $this->gf_channel, CURLOPT_COOKIEJAR, 'cookie.txt');
				// as above
				curl_setopt( $this->gf_channel, CURLOPT_COOKIEFILE, 'cookie.txt');
				
				// if the $vars are in an array then turn them into a usable string
				if( is_array( $params ) ):
					$vars = implode( '&', $params);
				endif;
				
				// setup the url to post / get from / to
				curl_setopt( $this->gf_channel, CURLOPT_URL, GFM_API_URL );
				curl_setopt( $this->gf_channel, CURLOPT_POST, true );
				curl_setopt( $this->gf_channel, CURLOPT_POSTFIELDS, $params );
				
				$this->result = curl_exec( $this->gf_channel );
				
				if (GFM_API_DEBUG && curl_errno($this->gf_channel) != CURLE_OK)
				{
					trigger_error(curl_error($this->gf_channel), E_USER_WARNING);
				}
				
				// close session
				curl_close($this->gf_channel);
			}
		}
    }// End sendCommand
	
	function parse2Array($xml)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $xml, $vals, $index);
		xml_parser_free($xml_parser);
		
		$params = array();
		$level = array();
		foreach ($vals as $xml_elem)
		{
			if ($xml_elem['type'] == 'open')
			{
				if (array_key_exists('attributes',$xml_elem)) 
				{
					list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
				}
				else
				{
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			
			if ($xml_elem['type'] == 'complete')
			{
				$start_level = 1;
				$php_stmt = '$params';
				while($start_level < $xml_elem['level'])
				{
					$php_stmt .= '[$level['.$start_level.']]';
					$start_level++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval($php_stmt);
			}
		}
		return $params;
	}// End parse2XML
}