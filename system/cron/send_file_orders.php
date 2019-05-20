<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$request = new request();
$mysql = new mysql();

$api_id = $request->getInt('api_id');

$strApi = ' and 1';
if ($api_id > 0) {
    //$strApi = ' and tm.api_id = ' . $api_id;
    $strApi = 'and fsm.api_id=' . $api_id;
}

//echo "Start";
/**
  Algorithem
 */
/** Get total number of orders per api */

$sql = 'select count(ofm.id) total,fsm.api_id,fsm.api_service_id 
from ' . ORDER_FILE_SERVICE_MASTER . ' ofm 
left join ' . FILE_SERVICE_MASTER . ' fsm on ofm.file_service_id=fsm.id
left join ' . API_MASTER . ' am on  fsm.api_id=ofm.api_id
where ofm.`status`=0 
and fsm.api_id!=0 ' . $strApi . '
group by ofm.api_id,ofm.api_service_id';

//  echo $sql;exit;
$query = $mysql->query($sql);
$arrOrders = $mysql->fetchArray($query);


$total = $bonus = $itemID = 0;

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
//  var_dump($services);exit;
/** Add orders that are left after allocation to all services */
if (!empty($services)) {
//		if($total < $toSend)
//		{
//			$total = $services[$itemID]['total'] + $toSend - $total;
//			$services[$itemID]['total'] = $total;
//			$total += $toSend - $total;
//		}
} else {
    die("No File Order in Queue...");
}
//print_r($services);die();


foreach ($services as $service) {
    //$mysql->query('SET SQL_BIG_SELECTS=1');

    $sql = 'select a.fileask,a.f_name,a.f_content,f_size,f_type,a.id oid,a.mobile,a.remarks,a.message,b.id api_id,b.api_server,b.url,b.username,b.password,b.`key`,b.server_id
,a.api_service_id,a.api_tries
 from ' . ORDER_FILE_SERVICE_MASTER . ' a
 left join  ' . FILE_SERVICE_MASTER . '  j
 on a.file_service_id=j.id
left join ' . API_MASTER . ' b
on b.id=j.api_id
where a.status=0 and  j.api_id= ' . $service['api_id'] . '  and j.api_service_id=' . $service['service_id'];
 //   echo $sql;
  //  exit;
    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        $strIds = '';
        foreach ($rows as $row) {
            $strIds .= $row['oid'] . ',';
        }
        $strIds = trim($strIds, ',');

        $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set api_tries = api_tries + 1 where id in (' . $strIds . ')';
        $mysql->query($sql);

        // echo '<pre>';
        $i = 0;
        foreach ($rows as $row) {
            //echo ($i == 0) ? ($row['api_server'] . ':' . $row['api_service_id'] . "\n\n\n") : '';
            $i++;
            //echo 'Order# ' . $row['oid'] . ' Sending.....' . "\n";
            //$args['imei'] = $row['imei'];
            $args['order_id'] = $row['oid'];
            $args['service_id'] = $row['api_service_id'];
            $args['username'] = $row['username'];
            $args['password'] = $row['password'];
            $args['key'] = $row['key'];
            $args['url'] = $row['url'];
            $args['mobile'] = $row['mobile'];
            $args['remarks'] = $row['remarks'];
            $args['message'] = $row['message'];
            $args['fileask'] = $row['f_name'];
            $args['fpath'] = $row['f_content'];
            $args['fsize'] = $row['f_size'];
            $args['ftype'] = $row['f_type'];

            //echo '<pre>';
            //var_dump($args);exit;

            if (defined("debug")) {
                print_r($args);
            }
            //exit();

            $api = new api();
            $extern_id = 0;
            $response = $api->sendfileorder($row['server_id'], $args);
            if (defined("debug")) {
                echo $row['server_id'];
                print_r($response);
            }

            if (isset($response['id'])) {
                $extern_id = $response['id'];
                if ($extern_id == "-1") {
                    //echo $response['msg'];
                } else {
                    if ($extern_id != "0") {
                        $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . '
										set 
											extern_id = ' . $mysql->quote($extern_id) . ',
											status = -1
										where id=' . $row['oid'];
                        $mysql->query($sql);
                        //echo 'Order #' . $row['oid'] . ' Sent Done' . "\n";
                    }
                }
            } else {
                if (isset($response['msg'])) {
                    echo $response['msg'] . '<br>';
                }
            }
        }
        //echo '</pre>';
    }
}
//echo "end";
exit();
