<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$request = new request();
$mysql = new mysql();
$api = new api();


$api_id = $request->getInt('api_id');

$strApi = ' and 1';
if ($api_id > 0) {
    //$strApi = ' and tm.api_id = ' . $api_id;
    $strApi = 'and ofm.api_id=' . $api_id;
}

//echo "Start";
/**
  Algorithem
 */
/** Get total number of orders per api */
/*
  Get total number of orders per api */
$sql = 'select count(ofm.id) total,ofm.api_id,ofm.api_service_id from ' . ORDER_FILE_SERVICE_MASTER . ' ofm 
            where ofm.`status`=-1 and ofm.api_id!=0 ' . $strApi . '

                    group by ofm.api_id,ofm.api_service_id';
//  echo $sql;exit;
$query = $mysql->query($sql);
$arrOrders = $mysql->fetchArray($query);


$total = $bonus = $itemID = 0;
// Calculate total number of orders in list */
foreach ($arrOrders as $item) {
//		$tempPriority = ($item['priority'] == 0) ? 1 : $item['priority'];
//		/*
//			if we have extra orders in our list than the lot capacity*/
//		if($toSend < $TOrders)
//		{
//			$temp = ((int)(($tempPriority * $percent * $toSend) / 100));
//			$temp += $bonus;
//			$bonus = 0;
//		}
//		else
//		{
//			$temp = $item['total'];
//		}
//		
//		/** if the seleted list have less orders then the alloted to it */
//		if($temp > $item['total'])
//		{
//			$bonus = $temp - $item['total'];
//			$temp = $item['total'];
//		}

    /** Create service list */
    $itemID++;
    $services[$itemID] = array('api_id' => $item['api_id'], 'service_id' => $item['api_service_id'], 'total' => $temp);

    /** Calculate Total  */
    //$total += $temp;
}
//echo '<pre>';
//Add orders that are left after allocation to all services */
if (!empty($services)) {
//    if ($total < $toSend) {
//        $total = $services[$itemID]['total'] + $toSend - $total;
//        $services[$itemID]['total'] = $total;
//        $total += $toSend - $total;
//    }
} else {
    die("No FILE ORDER TO GET Queue Empty...");
}
// print_r($services);die();



/*

  Algorithem --- END

 */


foreach ($services as $service) {
$mysql->query('SET SQL_BIG_SELECTS=1');

    $sql = 'select oim.id as order_id, oim.extern_id,
itm.service_name, itm.api_id as api_id, am.server_id, am.url, am.username, 
am.password, am.key 
from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id) 
left join ' . API_MASTER . ' am on (itm.api_id = am.id) 
where oim.status=-1 and oim.extern_id!="" 
and itm.api_id =' . $service['api_id'] . '
and itm.api_service_id=' . $service['service_id'] . ' 
order by api_tries_done 
limit 1000';
//echo $sql;exit;

    $query = $mysql->query($sql);


    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        $strIds = '';
        foreach ($rows as $row) {
            $strIds .= $row['order_id'] . ',';
        }
        $strIds = trim($strIds, ',');

        if ($strIds != '') {
            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set api_tries_done = api_tries_done + 1 where id in (' . $strIds . ')';
            $mysql->query($sql);
        }

        $extern_id = '';
        foreach ($rows as $row) {
            $api_id = $row['server_id'];
            // $args['imei'] = $row['imei'];
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
            if (defined("debug")) {
                print_r($row);
            } else {
               // echo "DONE";
            }


           // echo ;
            $api->get_file($api_id, $args);
        }
    }
}
?>