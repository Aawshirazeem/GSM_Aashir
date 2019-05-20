<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	
	$request = new request();
	$mysql = new mysql();
	$api = new api();
	
	
	$api_id = $request->getInt('api_id');
$strApi="";
	//$strApi = ' and am.is_special=0 ';
	if($api_id > 0){
		$strApi = ' and tm.api_id = ' . $api_id;
	}
	
	/*
		
		Algorithem 
		
	*/
	
	$mysql->query('SET SQL_BIG_SELECTS=1');
	/*
		Get total number of orders per api */
	$sql = 'select tm.api_id as id, tm.api_service_id as service_id, tm.api_priority as priority, count(oim.id) as total
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' tm on (tm.id = oim.tool_id)
					left join ' . API_MASTER . ' am on (am.id = tm.api_id)
					where am.`status`=1 and am.is_visible=1 and oim.status=1 and oim.extern_id!="" ' . $strApi . '
					group by tm.api_id, tm.api_service_id';
	$query = $mysql->query($sql);
	$arrOrders = $mysql->fetchArray($query);
	
	/*
		Calculate total number of orders in list */
	$TPre = $TOrders = 0;
	foreach($arrOrders as $item)
	{
		$tempPriority = ($item['priority'] == 0) ? 1 : $item['priority'];
		$TPre += $tempPriority;
		$TOrders += $item['total'];
	}
	
	
	/*
		Set lot size */
	$toSend = 40;
	if($TOrders > 200 && $TOrders <= 500)
	{
		$toSend = 50;
	}
	if($TOrders > 500 && $TOrders <= 1000)
	{
		$toSend = 80;
	}
	if($TOrders > 1000)
	{
		$toSend = 100;
	}
	$toSend = 1000;
	
	//echo "Priority: " . $TPre . '<br />';
	//echo "Total: " . $TOrders . '<br />';
	//echo "Send: " . $toSend . '<br />';
	
	
	$percent = ($TPre > 0) ? (int)(100 / $TPre) : 0;
	$total = $bonus = $itemID = 0;
	$services = array();
	foreach($arrOrders as $item)
	{
		$tempPriority = ($item['priority'] == 0) ? 1 : $item['priority'];
		/*
			if we have extra orders in our list than the lot capacity*/
		if($toSend < $TOrders)
		{
			$temp = ((int)(($tempPriority * $percent * $toSend) / 100));
			$temp += $bonus;
			$bonus = 0;
		}
		else
		{
			$temp = $item['total'];
		}
	
		/*
			if the seleted list have less orders then the alloted to it */
		if($temp > $item['total'])
		{
			$bonus = $temp - $item['total'];
			$temp = $item['total'];
		}
		
		/*
			Create service list */
		$itemID++;
		$services[$itemID] = array('api_id' => $item['id'], 'service_id' => $item['service_id'], 'total' => $temp);
		
		/*
			Calculate Total */
		$total += $temp;
	}
	/*
		Add orders that are left after allocation to all services */
	if(!empty($services))
	{
		if($total < $toSend)
		{
			$total = $services[$itemID]['total'] + $toSend - $total;
			$services[$itemID]['total'] = $total;
			$total += $toSend - $total;
		}
	}
	else
	{
		die("No imei in Queue...");
	}
	// print_r($services);die();
	
	
	
	/*
		
		Algorithem --- END
		
	*/
	
	
	foreach($services as $service)
	{
            $mysql->query('SET SQL_BIG_SELECTS=1');
		$sql = 'select
						oim.id as order_id, oim.extern_id, oim.imei, itm.tool_name,
						itm.api_id as api_id,
						am.server_id, am.url, am.username, am.password, am.key
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					left join ' . API_MASTER . ' am on (itm.api_id = am.id)
					where am.`status`=1 and am.is_visible=1 and oim.status=1 and oim.extern_id!=""
					and itm.api_id =' . $service['api_id'] . ' and itm.api_service_id=' . $service['service_id'] . '
					order by api_tries_done
					limit ' . $service['total'];
		$query = $mysql->query($sql);

		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$strIds = '';
			foreach($rows as $row)
			{
				$strIds .= $row['order_id'] . ',';
			}
			$strIds = trim($strIds, ',');
			
			if($strIds != '')
			{
				$sql = 'update ' . ORDER_IMEI_MASTER . ' set api_tries_done = api_tries_done + 1 where id in (' . $strIds . ')';
				$mysql->query($sql);
			}
			
			$extern_id = '';
			foreach($rows as $row)
			{
				$api_id = $row['server_id'];
				$args['imei'] = $row['imei'];
				$args['order_id'] = $row['order_id'];
				$args['extern_id'] = $row['extern_id'];
				$args['username'] = $row['username'];
				$args['password'] = $row['password'];
				$args['key'] = $row['key'];
				$args['url'] = $row['url'];
				//$args['model'] = $rows_api[0]['model'];
				//$args['provider'] = $rows_api[0]['provider'];
				//$args['network'] = $rows_api[0]['network'];
				//$args['custom_value'] = $custom_value;
				//print_r($args);
				if (defined("debug"))
				{
					print_r($row);
				}
				else
				{
					//echo "DONE";
				}
				

				$api->get($api_id, $args);
			}
		}
	}
	
?>