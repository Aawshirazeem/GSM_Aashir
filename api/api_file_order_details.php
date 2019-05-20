<?php
	require_once("_init.php");
	$request = new request();
	$xml = new xml();
	$mysql = new mysql();
	$api = new api();
	$objImei = new imei();
	$objCredits = new credits();
	
	
	
	$parameters = html_entity_decode($_POST['parameters']);
	$params = $xml->XMLtoARRAY(trim($parameters));
	$ids = $params['PARAMETERS']['ID'];
	 
	if($ids != '')
	{
		$strQ = ' and oim.id in (' . $ids . ')';
	}
	//$member = new member_api($api_key);
//	  $result = Array('MESSAGE' => "No IMEI Found");
//			$result2[] = $result;
//			$result = Array('ID' => $ids, 'ERROR' => $result2, 'apiversion' => '2.0.0');
//	
//	
//	//error_log(print_r($result, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruStatus.log");
//	echo json_encode($result);exit;
	$body = "";

                
//                $result = Array('MESSAGE' => "No IMEI Found");
//			$result2[] = $result;
//			$result = Array('ID' => $ids, 'ERROR' => $result2, 'apiversion' => '2.0.0');
//	
//	
//	//error_log(print_r($result, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruStatus.log");
//	echo json_encode($result);
//	exit;
//	
	$code="NO CODE";
	if($ids != '')
	{
		$sql = 'select
						id, imei, reply, message, remarks, status
					from ' . ORDER_IMEI_MASTER . ' oim
					where 1 ' . $strQ . ' and 
						oim.user_id=' . $member->getUserId() . '
					order by id DESC';
                $sql='select
						id, unlock_code, reply, message, remarks, status
					from ' . ORDER_FILE_SERVICE_MASTER . ' oim
					where 1 ' . $strQ . ' and 
						oim.user_id=' . $member->getUserId() . ' and status in (1,2)
					order by id DESC';
                
                
		$query = $mysql->query($sql);
		$status = 0;
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$code = $row['unlock_code'];
				switch($row['status'])
				{
					case 2:
						$status = 3;
                                                $code = $row['reply'];
						break;
					case 1:
						$status = 4;						
						break;
					default:
						$status = -1;
						break;
				}
				$services = Array(
									'IMEI' => $row['id'],
									'STATUS' => $status,
									'CODE' => $code,
									'COMMENTS' => $row['remarks']
								);
				$result[0] = $services;
			}
			$result = Array('ID' => $ids, 'SUCCESS' => $result, 'apiversion' => '2.0.0');
		}
		else
		{
			$result = Array('MESSAGE' => "Code Not Ready");
			$result2[] = $result;
			$result = Array('ID' => $ids, 'ERROR' => $result2, 'apiversion' => '2.0.0');
		}
	}
	else
	{
		$result = Array('MESSAGE' => "Parameter 'ID' Required", 'ID' => $ids);
		$result[] = $result;
		$result = Array('ERROR' => $result, 'apiversion' => '2.0.0');
	}
	
	//error_log(print_r($result, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruStatus.log");
	echo json_encode($result);
?>