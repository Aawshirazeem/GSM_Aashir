<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$request = new request();
$mysql = new mysql();
$api = new api();

// -----------------------------------------------------------send orderss-------------------------------------------------

$api_id = $request->getInt('api_id');

$strApi = "";
//$strApi = ' and am.is_special=0 ';
if ($api_id > 0) {
    $strApi = ' and tm.api_id = ' . $api_id;
}

//echo "Start";
/**
  Algorithem
 */
$mysql->query('SET SQL_BIG_SELECTS=1');

/** Get total number of orders per api */
$sql = 'select tm.api_id as id, tm.api_service_id as service_id, tm.api_priority as priority, count(oim.id) as total
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' tm on (tm.id = oim.tool_id)
					left join ' . API_MASTER . ' am on (am.id = tm.api_id)
					where am.`status`=1 and am.is_visible=1 and oim.status=0 and tm.api_id!=0 ' . $strApi . '
					group by tm.api_id, tm.api_service_id
					limit 20';
$query = $mysql->query($sql);
//      echo $sql;exit;
$arrOrders = $mysql->fetchArray($query);


/** Calculate total number of orders in list */
$TPre = $TOrders = 0;
foreach ($arrOrders as $item) {
    $tempPriority = ($item['priority'] == 0) ? 1 : $item['priority'];
    $TPre += $tempPriority;
    $TOrders += $item['total'];
}


/** Set lot size */
$toSend = 40;
if ($TOrders > 200 && $TOrders <= 500) {
    $toSend = 50;
}
if ($TOrders > 500 && $TOrders <= 1000) {
    $toSend = 80;
}
if ($TOrders > 1000) {
    $toSend = 100;
}

$toSend = 1000;
//echo "Priority: " . $TPre . '<br />';
//echo "Total: " . $TOrders . '<br />';
//echo "Send: " . $toSend . '<br />';


$percent = ($TPre > 0) ? (int) (100 / $TPre) : 0;
$total = $bonus = $itemID = 0;
$services = array();
foreach ($arrOrders as $item) {
    $tempPriority = ($item['priority'] == 0) ? 1 : $item['priority'];
    /*
      if we have extra orders in our list than the lot capacity */
    if ($toSend < $TOrders) {
        $temp = ((int) (($tempPriority * $percent * $toSend) / 100));
        $temp += $bonus;
        $bonus = 0;
    } else {
        $temp = $item['total'];
    }

    /** if the seleted list have less orders then the alloted to it */
    if ($temp > $item['total']) {
        $bonus = $temp - $item['total'];
        $temp = $item['total'];
    }

    /** Create service list */
    $services[$itemID] = array('api_id' => $item['id'], 'service_id' => $item['service_id'], 'total' => $temp);
    $itemID++;
    /** Calculate Total  */
    $total += $temp;
}
/** Add orders that are left after allocation to all services */
//echo '<pre>';
//print_r($services);
//die();
if (empty($services)) {
    echo 'No Imei In Que';
    exit;
}


/** Algorithem --- END */
if (!empty($services)) {
    foreach ($services as $service) {
        $mysql->query('SET SQL_BIG_SELECTS=1');
        $sql = 'select
						oim.api_tries, oim.id as oid, oim.imei, oim.model_id as model, oim.custom_value,
						oim.network_id as network, oim.prd, oim.pin,
						tm.api_id, tm.api_service_id,
						am.server_id, am.api_server, am.username, am.password, am.key, am.url,
						 ad.provider as provider, ad.service_name,ad.id as cust_id,ad.info,
						imm.mep
						from ' . ORDER_IMEI_MASTER . ' oim
						left join ' . IMEI_TOOL_MASTER . ' tm on (tm.id = oim.tool_id)
						left join ' . API_MASTER . ' am on (am.id = tm.api_id)
						left join ' . API_DETAILS . ' ad on (ad.service_id = tm.api_service_id and ad.api_id = tm.api_id)
						left join ' . IMEI_MEP_MASTER . ' imm on(oim.mep_id = imm.id)
						where am.`status`=1 and am.is_visible=1 and oim.status =0 and tm.api_id!=0
						and tm.api_id =' . $service['api_id'] . ' and tm.api_service_id=' . $service['service_id'] . '
						order by api_tries
						limit ' . $service['total'];
        // echo $sql;
        $query = $mysql->query($sql);

        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            $strIds = '';
            foreach ($rows as $row) {
                $strIds .= $row['oid'] . ',';
            }
            $strIds = trim($strIds, ',');

            $sql = 'update ' . ORDER_IMEI_MASTER . ' set api_tries = api_tries + 1 where id in (' . $strIds . ')';
            //   echo $sql;
            $mysql->query($sql);

            // 	echo '<pre>';
            // var_dump($rows);
            //exit;
            $i = 0;
            foreach ($rows as $row) {
                //	echo ($i == 0) ? ($row['api_server'] . ':' . $row['service_name'] . "\n") : '';
                $i++;
                //    echo $i . '---' . $row['imei'] . "<br>";
                $args['imei'] = $row['imei'];
                $args['order_id'] = $row['oid'];
                $args['service_id'] = $row['api_service_id'];
                $args['username'] = $row['username'];
                $args['password'] = $row['password'];
                $args['key'] = $row['key'];
                $args['url'] = $row['url'];
                $args['model'] = $row['model'];
                $args['provider'] = $row['network'];
                $args['network'] = $row['network'];
                $args['prd'] = $row['prd'];
                $args['kbh'] = $row['pin'];
                $args['mep'] = $row['mep'];
                $args['custom_value'] = $row['custom_value'];

                if (defined("debug")) {
                    print_r($args);
                }
                //exit();

                $api = new api();
                $extern_id = 0;
                 $response = $api->send($row['server_id'], $args);
                if (defined("debug")) {
                    echo $row['server_id'];
                    print_r($response);
                }

                if (isset($response['id'])) {
                    $extern_id = $response['id'];
                    if ($extern_id == "-1") {
                        //	echo $response['msg'];
                    } else {
                        if ($extern_id != "0") {
                            $sql = 'update ' . ORDER_IMEI_MASTER . '
										set 
											extern_id = ' . $mysql->quote($extern_id) . ',
											status = 1
										where id=' . $row['oid'];
                            $mysql->query($sql);
                        }
                    }
                } else {
                    if (isset($response['msg'])) {
                        echo $response['msg'];
                    }
                }
            }
            //echo '</pre>';
        }
    }
}
//echo "end"
//echo '<br><br>.................ORDER SEND DONE ..............<br><br>';