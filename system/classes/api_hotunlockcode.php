<?php
	
	class UnlockBase
	{
	
		public function api_get_credits($args)
		{
			
			// /* Enter your API key here */
			// if(!defined("UNLOCKBASE_API_KEY"))
				// define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');
			
			// /* Set this value to true if something goes wrong and you want to display error messages */
			// if(!defined("UNLOCKBASE_API_DEBUG"))
				// define('UNLOCKBASE_API_DEBUG', true);
			
			// /* This is the url of the api, don't change it */
			// if(!defined("UNLOCKBASE_API_URL"))
				// define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');
			
			// if(!defined("UNLOCKBASE_VARIABLE_ERROR"))
				// define('UNLOCKBASE_VARIABLE_ERROR',    '_UnlockBaseError'    );
			// if(!defined("UNLOCKBASE_VARIABLE_ARRAY"))
				// define('UNLOCKBASE_VARIABLE_ARRAY',    '_UnlockBaseArray'    );
			// if(!defined("UNLOCKBASE_VARIABLE_POINTERS"))
				// define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers' );
			
			// /* Check that cURL is installed */
			// if (! extension_loaded('curl'))
			// {
				// trigger_error('cURL extension not installed', E_USER_ERROR);
			// }
			
			// /*******************************************************/
			
			
			// $XML = $this->CallAPI('AccountInfo');
			
			// $credits = 0;
			// if (is_string($XML))
			// {
				// $Data = $this->ParseXML($XML);
				// if (is_array($Data))
				// {
					// if (isset($Data['Error']))
					// {
						// /* The API has returned an error */
						// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . htmlspecialchars($Data['Error']));
					// }
					// else
					// {
						// /* Everything works fine */
						// $credits = $Data['Credits'];
					// }
				// }
				// else
				// {
					// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not parse the XML stream.');
				// }
			// }
			// else
			// {
				// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
			// }
			// return array('credits' => $credits, 'msg' => '');
		
		}
		
		public function api_get_code($args)
		{
			echo "asdf";
			$url = $args['url'];   // API SUBMIT URL
			$user_name = $args['username'];  // Your Login user name
			$api_access_key = $args['key'];   // Your Api Access Key 

			$data = '
					<REQUEST>
						<ACTION>GET_IMEI_ORDER</ACTION>  
						<IMEI>'.$args['imei'].'</IMEI>
					</REQUEST>   
					';
			
			// UPADTE WITH YOUR POST FIELD NAME     
			$fields = array(    	 	 
									'USERNAME'=>$user_name,
									'API_KEY'=>$api_access_key,
									'DATA'=>$data
							);
			$fields_string = '';
			// do not touch anything if your are not know what your ar doing //
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');
			
			$api=new XMLHttpRequest();
			$api->open("POST","$url" );
			$api->send("langpair=pt|en&".$fields_string);
			if($api->status==200){
				//echo $api->responseText;
				$result =  base64_decode($api->responseText);
				//echo $result;die;
				$response = new SimpleXMLElement($result);  
				var_dump((array)$response);
				echo $response_status = $response->status;  // status=ok Order Submit Success ,  status=err Order Submit Fail
				echo '<br />';
				echo $response_statusmessage = $response->statusmessage;  // if status=err how Why it's fail, 
				echo '<br />';
				echo $response_order_status = $response->order_status; // IF $response_status=ok then you will got order_status (available,pending,rejected)
				echo '<br />';
				
				if($response_order_status=="available")
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
								reply=' . $mysql->quote(base64_encode($response->code)) . ',
								im.reply_date_time=now(),
								um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
								where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
					$mysql->query($sql);
					//if($mysql->rowCount($query) > 0)
					{
						$objCredits = new credits();
						$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
					}
				}


			}else{
				echo "Error in connection to server!<br />";
				echo $api->error; 
			}


		}
		
		public function api_place_order($args)
		{
			// /* Enter your API key here */
			// if(!defined("UNLOCKBASE_API_KEY"))
				// define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');
			
			// /* Set this value to true if something goes wrong and you want to display error messages */
			// if(!defined("UNLOCKBASE_API_DEBUG"))
				// define('UNLOCKBASE_API_DEBUG', true);
			
			// /* This is the url of the api, don't change it */
			// if(!defined("UNLOCKBASE_API_URL"))
				// define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');
			
			// if(!defined("UNLOCKBASE_VARIABLE_ERROR"))
				// define('UNLOCKBASE_VARIABLE_ERROR',    '_UnlockBaseError'    );
			// if(!defined("UNLOCKBASE_VARIABLE_ARRAY"))
				// define('UNLOCKBASE_VARIABLE_ARRAY',    '_UnlockBaseArray'    );
			// if(!defined("UNLOCKBASE_VARIABLE_POINTERS"))
				// define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers' );
			
			// /* Check that cURL is installed */
			// if (! extension_loaded('curl'))
			// {
				// trigger_error('cURL extension not installed', E_USER_ERROR);
			// }
			
			// /*******************************************************/
			
			// $XML = $this->CallAPI('PlaceOrder', array('Tool' => $args['service_id'], 'IMEI' => $args['imei']));
			
			// if (is_string($XML))
			// {
				// $Data = $this->ParseXML($XML);
				// if (is_array($Data))
				// {
					// if (isset($Data['Error']))
					// {
						// /* The API has returned an error */
						// return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . htmlspecialchars($Data['Error']));
					// }
					// else
					// {
						// /* Everything works fine */
						// return array('id' => $Data['ID'], 'msg' => $Data['Success']);
					// }
				// }
				// else
				// {
					// return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance. Could not parse the XML stream');
				// }
			// }
			// else
			// {
				// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
			// }
			
			
			// /***************************************************************/
			
			
			
		}
		
		public function api_sync_tools($args)
		{
			// /* Enter your API key here */
			// if(!defined("UNLOCKBASE_API_KEY"))
				// define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');
			
			// /* Set this value to true if something goes wrong and you want to display error messages */
			// if(!defined("UNLOCKBASE_API_DEBUG"))
				// define('UNLOCKBASE_API_DEBUG', true);
			
			// /* This is the url of the api, don't change it */
			// if(!defined("UNLOCKBASE_API_URL"))
				// define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');
			
			// if(!defined("UNLOCKBASE_VARIABLE_ERROR"))
				// define('UNLOCKBASE_VARIABLE_ERROR',    '_UnlockBaseError'    );
			// if(!defined("UNLOCKBASE_VARIABLE_ARRAY"))
				// define('UNLOCKBASE_VARIABLE_ARRAY',    '_UnlockBaseArray'    );
			// if(!defined("UNLOCKBASE_VARIABLE_POINTERS"))
				// define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers' );
			
			// /* Check that cURL is installed */
			// if (! extension_loaded('curl'))
			// {
				// trigger_error('cURL extension not installed', E_USER_ERROR);
			// }
			
			// /*******************************************************/
			
			// $mysql = new mysql();
			
			// /* Call the API */
			// $XML = $this->CallAPI('GetTools');
			
			// if (is_string($XML))
			// {
				// /* Parse the XML stream */
				// $Data = $this->ParseXML($XML);
				
				// if (is_array($Data))
				// {
					// if (isset($Data['Error']))
					// {
						// /* The API has returned an error */
						// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . htmlspecialchars($Data['Error']));
					// }
					// else
					// {
						// $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
						// $mysql->query($sql);
						
						// $sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
						// $mysql->query($sql);

						// /* Everything works fine */
						// foreach ($Data['Group'] as $Group)
						// {
							// foreach ($Group['Tool'] as $Tool)
							// {
								// $sql = '
										// insert into ' . API_DETAILS . '
											// (api_id, service_id, service_name, credits, delivery_time) 
											// VALUES (
												// ' . $args['id'] . ',
												// ' . $Tool['ID'] . ',
												// ' . $mysql->quote($Tool['Name']) . ', 
												// ' . $Tool['Credits'] . ',
												// ' . $mysql->quote('') . ')';
								// $mysql->query($sql);
								// echo 'Updateing Tool: ' . $Tool['Name'] . '<br />';
								// ob_flush();
							// }
						// }
					// }
				// }
				// else
				// {
					// /* Parsing error */
					// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not parse the XML stream.');
				// }
			// }
			// else
			// {
				// /* Communication error */
				// return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
			// }
			
			
			// //print_r($resultArray);
			// return true;
		}
	
	
		/*
			mixed UnlockBase::CallAPI (string $Action, array $Parameters)
			Call the UnlockBase API.
			Returns the xml stream sent by the UnlockBase server
			Or false if an error occurs
		*/
	}
?>