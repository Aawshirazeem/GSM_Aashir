<?php
class api_ubox{

	
	public function api_credits($args)
	{

		// Put your API Access key here
		if(!defined("UBOX_API_USER"))
			define('UBOX_API_USER', $args['username']);

		// Put your API Access key here
		if(!defined("UBOX_API_PASSWORD"))
			define('UBOX_API_PASSWORD', $args['password']);

		// Put your API Access key here
		if(!defined("UBOX_API_KEY"))
			define('UBOX_API_KEY', $args['key']);
		

		
		/* CHANGE TO true FOR DEBUG */
		define('UBOX_API_DEBUG', false);

		require('ubox/APIUBOX.php');

		//GET THE API SIGN
		$Sign = APIUBOX::GetApiSign(UBOX_API_USER, UBOX_API_PASSWORD, UBOX_API_KEY);
		
		//FILL THE API PARAMS
		$params = array(
				'user' => UBOX_API_USER,
				'sign' => $Sign
		);
		
		//DO THE API CALL
		$XML = APIUBOX::CallMethod('GetUserInfo', $params);	
		
		//PARSE RESULT
		if (is_string($XML))
		{
			echo $XML;
			if(UBOX_API_DEBUG)
			{
				echo 'Response XML:<br>', htmlspecialchars($XML), '<br><br>', PHP_EOL ;
			}
			
			$isError = APIUBOX::HasError($XML);
			if($isError)
			{
				trigger_error(APIUBOX::GetErrorDescription($XML), E_USER_ERROR);
			}
			else 
			{
				//PROCESS GETAPIVERSION XML RETURNED
				$doc = new DOMDocument();
				$loaded = $doc->loadXML($XML);
				
				$nodes = $doc->getElementsByTagName('credits');
				foreach ($nodes as $nod)
		        	return array('credits' => $nod->nodeValue, 'msg' => '');
				
			}		
		}
		else 
		{
			return array('credits' => '-1', 'msg' => 'Could not parse the XML stream');
		}

	}





    public function api_sync_tools($args)
    {
        $mysql = new mysql();


		// Put your API Access key here
		if(!defined("UBOX_API_USER"))
			define('UBOX_API_USER', $args['username']);

		// Put your API Access key here
		if(!defined("UBOX_API_PASSWORD"))
			define('UBOX_API_PASSWORD', $args['password']);

		// Put your API Access key here
		if(!defined("UBOX_API_KEY"))
			define('UBOX_API_KEY', $args['key']);
		

		
		/* CHANGE TO true FOR DEBUG */
		define('UBOX_API_DEBUG', false);

		require('ubox/APIUBOX.php');

		//GET THE API SIGN
		$Sign = APIUBOX::GetApiSign(UBOX_API_USER, UBOX_API_PASSWORD, UBOX_API_KEY);
		
		//FILL THE API PARAMS
		$params = array(
				'user' => UBOX_API_USER,
				'sign' => $Sign
		);
		
		//DO THE API CALL
		$XML = APIUBOX::CallMethod('GetOperations', $params);	
		
		//PARSE RESULT
		if (is_string($XML))
		{
			if(UBOX_API_DEBUG)
			{
				echo 'Response XML:<br>', htmlspecialchars($XML), '<br><br>', PHP_EOL ;
			}
			
			$isError = APIUBOX::HasError($XML);
			if($isError)
			{
				trigger_error(APIUBOX::GetErrorDescription($XML), E_USER_ERROR);
			}
			else 
			{
				//PROCESS GETAPIVERSION XML RETURNED
				$doc = new DOMDocument();
				$loaded = $doc->loadXML($XML);

		        $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
		        $mysql->query($sql);
		        
		        $sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
		        $mysql->query($sql);

				
				$nodesNames = $doc->getElementsByTagName('name');
				$nodesIDs = $doc->getElementsByTagName('id');
				$nodesCredits = $doc->getElementsByTagName('credits');
				$nodesResolutionTime = $doc->getElementsByTagName('resolutionTime');
				$i=0;
				foreach ($nodesIDs as $nod)
				{
					$myID = $nod->nodeValue;
					$myName = $nodesNames->item($i)->nodeValue;
					$myCredits = $nodesCredits->item($i)->nodeValue;
					$myTime = $nodesResolutionTime->item($i)->nodeValue;
					$i++;
					//<id>5311</id><name>APPLE CARRIER NETWORK Full info</name><modelId>102</modelId><credits>1</credits><description>WARNING : Th
					//echo 'Operation: ', $nod->nodeValue, '<br>', PHP_EOL;

					$sql = '
						insert into ' . API_DETAILS . '
							(api_id, service_id, service_name, credits, delivery_time) 
							VALUES (
								' . $args['id'] . ',
								' . $myID . ',
								' . $mysql->quote($myName) . ', 
								' . $myCredits . ',
								' . $mysql->quote($myTime) . ')';
					$mysql->query($sql);
					echo 'Updateing Tool: ' . $myName . '('. round($myCredits,2). ')<br />';
					ob_flush();
				}
				
			}		
		}
		else 
		{
			return array('credits' => '-1', 'msg' => 'Could not parse the XML stream');
		}
        return true;
    }






    public function api_get_code($args)
    {
		// Put your API Access key here
		if(!defined("UBOX_API_USER"))
			define('UBOX_API_USER', $args['username']);

		// Put your API Access key here
		if(!defined("UBOX_API_PASSWORD"))
			define('UBOX_API_PASSWORD', $args['password']);

		// Put your API Access key here
		if(!defined("UBOX_API_KEY"))
			define('UBOX_API_KEY', $args['key']);
		

		
		/* CHANGE TO true FOR DEBUG */
		define('UBOX_API_DEBUG', false);

		require('ubox/APIUBOX.php');

		//GET THE API SIGN
		$Sign = APIUBOX::GetApiSign(UBOX_API_USER, UBOX_API_PASSWORD, UBOX_API_KEY);
		
		//FILL THE API PARAMS
		$params = array(
				'user' => UBOX_API_USER,
				'requestID' => $args['extern_id'],
				'sign' => $Sign
		);

		
		//DO THE API CALL
		$XML = APIUBOX::CallMethod('CheckRequest', $params);	
		
		//PARSE RESULT
		if (is_string($XML))
		{
			if(UBOX_API_DEBUG)
			{
				echo 'Response XML:<br>', htmlspecialchars($XML), '<br><br>', PHP_EOL ;
			}
			
			$isError = APIUBOX::HasError($XML);
			if($isError)
			{
				trigger_error(APIUBOX::GetErrorDescription($XML), E_USER_ERROR);
			}
			else 
			{
				//PROCESS GETAPIVERSION XML RETURNED
				$doc = new DOMDocument();
				$loaded = $doc->loadXML($XML);
				echo $XML;
				$nodeState = $doc->getElementsByTagName('state');
				$nodeResult = $doc->getElementsByTagName('result');



				foreach ($nodeState as $nod)
	        	{
	        		$myState = $nod->nodeValue;
	        		$myResult = $nodeResult->item(0)->nodeValue;

					$nodeResult = $doc->getElementsByTagName('result');
					foreach ($nodeResult as $node)
						$myResult = $node->nodeValue;

	        		//echo '<h1>' . $myState . '</h1>';
					switch($myState)
	                {
	                    // if unlock code is available
	                    case 'Pending':
	                       // echo 'Pending';
	                        //echo '<pre>' . print_r($myResult, true) . '</pre>';
	                        break;
	                    // if unlock code is available
	                    case 'Resolved':
	                        $mysql = new mysql();
	                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
	                        $query = $mysql->query($sql);
	                        $rows = $mysql->fetchArray($query);
	                        
	                        $sql = 'update 
	                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
	                                    set
	                                    im.status=2,
	                                    reply_by=3,
	                                    reply=' . $mysql->quote(base64_encode($myResult)) . ',
	                                    im.reply_date_time=now(),
	                                    um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
	                                    where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
	                        $mysql->query($sql);
	                        //if($mysql->rowCount($query) > 0)
	                        {
	                            $objCredits = new credits();
	                            $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
	                        }
	                    //    echo "IMEI Processed";
	                        break;

	                    // if imei is rejected
	                    case 'Erroneous':
	                        $mysql = new mysql();
	                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
	                        $query = $mysql->query($sql);
	                        $rows = $mysql->fetchArray($query);
	                        
	                        $sql = 'update 
	                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
	                                    set
	                                    im.status=3,
	                                    reply_by=3,
	                                    reply=' . $mysql->quote($myResult) . ',
	                                    im.reply_date_time=now(),
	                                    um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
	                                where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
	                        $mysql->query($sql);
	                        //if($mysql->rowCount($query) > 0)
	                        {
	                            $objCredits = new credits();
	                            $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
	                        }
	                     //   echo "IMEI Return";
	                        break;
	                    
	                    default:
	                        print_r($myResult);
	                        break;
	                }
	        	}
				
			}		
		}
		else 
		{
			return array('credits' => '-1', 'msg' => 'Could not parse the XML stream');
		}
    }




    public function api_place_order($args)
    {

		// Put your API Access key here
		if(!defined("UBOX_API_USER"))
			define('UBOX_API_USER', $args['username']);

		// Put your API Access key here
		if(!defined("UBOX_API_PASSWORD"))
			define('UBOX_API_PASSWORD', $args['password']);

		// Put your API Access key here
		if(!defined("UBOX_API_KEY"))
			define('UBOX_API_KEY', $args['key']);
		

		
		/* CHANGE TO true FOR DEBUG */
		define('UBOX_API_DEBUG', false);

		require('ubox/APIUBOX.php');

		//GET THE API SIGN
		$Sign = APIUBOX::GetApiSign(UBOX_API_USER, UBOX_API_PASSWORD, UBOX_API_KEY);
		
		//FILL THE API PARAMS
		$params = array(
				'user' => UBOX_API_USER,
				'operationId' => $args['service_id'],
				'imei' => $args['imei'],
				'infoextra' => '',
				'notes' => '',
				'sign' => $Sign,
				'netId' => '-1',
		);

		
		//DO THE API CALL
		$XML = APIUBOX::CallMethod('AddNewRequest', $params);	
		
		//PARSE RESULT
		if (is_string($XML))
		{
			if(UBOX_API_DEBUG)
			{
				echo 'Response XML:<br>', htmlspecialchars($XML), '<br><br>', PHP_EOL ;
			}
			
			$isError = APIUBOX::HasError($XML);
			if($isError)
			{
				trigger_error(APIUBOX::GetErrorDescription($XML), E_USER_ERROR);
			}
			else 
			{
				//PROCESS GETAPIVERSION XML RETURNED
				$doc = new DOMDocument();
				$loaded = $doc->loadXML($XML);
					
				$nodes = $doc->getElementsByTagName('id');
				$ExternalId='';
				foreach ($nodes as $nod)			
					$ExternalId = $nod->nodeValue;

		        return array('id' => $ExternalId, 'msg' => '');
				
			}		
		}
		else 
		{
			return array('credits' => '-1', 'msg' => 'Could not parse the XML stream');
		}
        
    }




}
