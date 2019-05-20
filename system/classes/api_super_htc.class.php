<?php

class api_super_htc{

	var $gf_channel;
	
	var $result = array();
	
	
	public function superhtc_credits($args)
	{
		
		// Put your API Access key here
		if(!defined("SHTC_API_ACCESS_KEY"))
			define('SHTC_API_ACCESS_KEY', $args['key']);
		
		// Change its value to true in case you want to debug the code
		if(!defined("SHTC_API_DEBUG"))
			define('SHTC_API_DEBUG', false);
		
		// Link to the web api
		if(!defined("SHTC_API_URL"))
			define('SHTC_API_URL', $args['url']);
		
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
	
	public function superhtc_get($args)
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
	
	public function superhtc_send($args)
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
		
		$api_super_htc->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei']));
		$resultArray = $api_super_htc->parse2Array($api_super_htc->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
		}
		//print_r($resultArray);
		return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
		
	}
	
	public function superhtc_sync_tools($args)
	{
		$mysql = new mysql();
		
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
		
		$this->sendCommand('GET_TOOLS', array());
		
		$resultArray = $this->parse2Array($this->getResult());
		if(isset($resultArray['RESULT']['ERR']))
		{
			//echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			//echo '<h3>' . $result['ERR'] . '</h3>';
			return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . $resultArray['RESULT']['ERR']);
		}
		
		$sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
		return array('credits' => '-1', 'msg' => $sql);
		$mysql->query($sql);
		
		$sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
		$mysql->query($sql);
		
		$total = count($resultArray['RESULT']);
		for($count=1;$count<=$total;$count++)
		{
			$tool = $resultArray['RESULT']['TOOL' . $count];
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
						(api_id, service_id, service_name, credits, delivery_time) 
						VALUES (
							' . $args['id'] . ',
							' . $tool['ID'] . ',
							' . $mysql->quote($tool['TOOL_NAME']) . ', 
							' . $tool['CREDITS'] . ',
							' . $mysql->quote($tool['DELIVERY_TIME']) . ')';
			$mysql->query($sql);
			echo 'Updateing Tool: ' . $tool['TOOL_NAME'] . '<br />';
			ob_flush();
		}
		//print_r($resultArray);
		return true;
		
	}
	
	function getResult()
	{
		//echo htmlentities($this->result);
		return $this->result;
	}
		
	function checkError($result)
	{
		if(isset($result['ERR']))
		{
			echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			echo '<h3>' . $result['ERR'] . '</h3>';
			exit();
		}
	}
	
    function sendCommand($command, $params = array())
    {
		if (is_string($command))
		{
			if (is_array($params))
			{
				$params['api_key'] = SHTC_API_ACCESS_KEY;
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
				
				curl_setopt( $this->gf_channel, CURLOPT_URL, SHTC_API_URL );
				curl_setopt( $this->gf_channel, CURLOPT_POST, true );
				curl_setopt( $this->gf_channel, CURLOPT_POSTFIELDS, $params );
				
				$this->result = curl_exec( $this->gf_channel );
				
				if (SHTC_API_DEBUG && curl_errno($this->gf_channel) != CURLE_OK)
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