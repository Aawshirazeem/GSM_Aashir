<?php

class api_dlgsm{

	var $gf_channel;
	
	var $result = array();
	
	
	public function api_credits($args)
	{



		/* the action can be 1 of below actions
		1- 'getimeiservicedetails'  : you can send this action to get response about the specific service ,and you must send service id as ['ID']
		2- 'servicelist'			: you can send this action to get all service list and details as response
		3- 'getcredit'              : you can send this action to get your credit as response
		4- 'placeimeiorder'         : you can send this action to submit new order ,you must send ['IMEI'] and ['ID'](serviceid) too
										you will get order_status = 0:order reject ,1: order received ;
										order_code : details of order reception (error or success)
										you will get response_status and response_code
										response_status = 0: waiting action ,1:in proccess , 2:(rejected and refunded credit) ,3: rejected , 4: success ;
										response code = details about response (in gsx instant service you will get imei report as response_code in 'placeimeiorder')
		4- 'orderstatus'			: you can send this action to check your order status,you must send ['ORDERID'] that returned in 'placeimeiorder' action
		*/
		
		// Put your API Access key here
		if(!defined("API_USERNAME"))
			define('API_USERNAME', $args['username']);
		
		if(!defined("API_ACCESS_KEY"))
			define('API_ACCESS_KEY', $args['key']);
		
		if(!defined("API_URL"))
			define('API_URL', "http://www.dlgsm.com/gsx/api_index.php");
				
		$resultArray = $this->sendCommand('getcreditd', array());
		echo "<pre>";
		print_r($resultArray);
		echo "</pre>";

		if(isset($resultArray['ERROR']))
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

				$params['USERNAME'] = API_USERNAME;
				$params['API_ACCESS_KEY'] = API_ACCESS_KEY;//
				$params['action'] = $command;
				// if the $vars are in an array then turn them into a usable string
				if( is_array( $params ) ):
					$vars = implode( '&', $params);
				endif;
				$ch = curl_init();
				$timeout = 60;
				curl_setopt($ch,CURLOPT_URL,API_URL);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
				curl_setopt($ch,CURLOPT_POST , 1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$params);


				$data = curl_exec($ch);
				//curl_close($ch);
				return json_decode($data);
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