<?php

if (!extension_loaded('curl')) {
    trigger_error('cURL extension not installed', E_USER_ERROR);
}

class api_codedesk {

    var $xmlData;
    var $xmlResult;
    var $debug;
    var $action;

    function __construct() {
        $this->xmlData = new DOMDocument();
    }

    function getResult() {
        return $this->xmlResult;
    }

    function action($action, $arr = array()) {
        if (is_string($action)) {
            if (is_array($arr)) {
                if (count($arr)) {
                    $request = $this->xmlData->createElement("PARAMS");
                    $this->xmlData->appendChild($request);
                    foreach ($arr as $key => $val) {
                        $key = strtoupper($key);
                        $request->appendChild($this->xmlData->createElement($key, $val));
                    }
                }
                $posted = array(
                    'login' => USERNAME,
                    'apikey' => API_ACCESS_KEY,
                    'cmd' => $action,
                    'format' => REQUESTFORMAT,
                    'params' => $this->xmlData->saveHTML());
                $crul1 = curl_init();
                curl_setopt($crul1, CURLOPT_HEADER, false);
                curl_setopt($crul1, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                //curl_setopt($crul1, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($crul1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($crul1, CURLOPT_URL, CODESK_URL . '/api/api.php');
                curl_setopt($crul1, CURLOPT_POST, true);
                curl_setopt($crul1, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($crul1, CURLOPT_POSTFIELDS, $posted);
                $response = curl_exec($crul1);
                if (curl_errno($crul1) != CURLE_OK) {
                    echo curl_error($crul1);
                    curl_close($crul1);
                } else {
                    curl_close($crul1);
                    // $response = XMLtoARRAY2(trim($response));
                    if ($this->debug) {
                        echo "<textarea rows='20' cols='200'> ";
                        print_r($response);
                        echo "</textarea>";
                    }
                    return (json_decode($response, true));
                }
            }
        }
        return false;
    }

    public function api_get_credits($args) {
        if (!defined("REQUESTFORMAT"))
            define("REQUESTFORMAT", "JSON");
        if (!defined("CODESK_URL"))
            define('CODESK_URL', $args['url']);
        if (!defined("USERNAME"))
            define("USERNAME", $args['username']);
        if (!defined("API_ACCESS_KEY"))
            define("API_ACCESS_KEY", $args['key']);


        $this->debug = false; // Debug on

        $request = $this->action('ACCOUNTINFO');

        if ($request['RET'] != 0) {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ERROR CODE:' . $request['RET']);
        }

        return array('credits' => $request['USERCRED'], 'msg' => '');
        exit();
    }

    public function api_sync_tools($args) {
        $mysql = new mysql();

        if (!defined("REQUESTFORMAT"))
            define("REQUESTFORMAT", "JSON");
        if (!defined("CODESK_URL"))
            define('CODESK_URL', $args['url']);
        if (!defined("USERNAME"))
            define("USERNAME", $args['username']);
        if (!defined("API_ACCESS_KEY"))
            define("API_ACCESS_KEY", $args['key']);


        $request = $this->action('SERVICELIST');
        if ($request['RET'] != 0) {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch service list now! Contact site admin for more assistance. ' . $request['RET']);
        }

        $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
        $mysql->query($sql);

        $sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
        $mysql->query($sql);


        foreach ($request['PRODUCTS'] as $key => $tool) {
            $sql = '
                    insert into ' . API_DETAILS . '
                        (api_id, service_id, service_name, credits, delivery_time) 
                        VALUES (
                            ' . $args['id'] . ',
                            ' . $tool['IDPROD'] . ',
                            ' . $mysql->quote($tool['PRODNAME']) . ', 
                            ' . $tool['PRODPRICE'] . ',
                            ' . $mysql->quote($tool['TIMEMINTYPE']) . ')';
            $mysql->query($sql);
            echo 'Updateing Tool: ' . $tool['PRODNAME'] . '(' . $tool['PRODPRICE'] . ')<br />';
            ob_flush();
        }
        return true;
    }

    public function api_get_code($args) {
        if (!defined("REQUESTFORMAT"))
            define("REQUESTFORMAT", "JSON");
        if (!defined("CODESK_URL"))
            define('CODESK_URL', $args['url']);
        if (!defined("USERNAME"))
            define("USERNAME", $args['username']);
        if (!defined("API_ACCESS_KEY"))
            define("API_ACCESS_KEY", $args['key']);

        $request = $this->action('ORDERINFO', Array('ORDERID' => $args['extern_id']));
        // print_r($request);

        if ($request['RET'] != 0) {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch service list now! Contact site admin for more assistance. ' . $request['RET']);
        }

        $objEmail = new email();
        $email_config = $objEmail->getEmailSettings();
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];

        $status_flag = -1;
        foreach ($request as $key => $result) {
            if (isset($result['ORDSTATUS'])) {
                switch ($result['ORDSTATUS']) {
                    // if unlock code is available
                    case '1':
                        //echo 'Pending';
                        // echo '<pre>' . print_r($result, true) . '</pre>';
                        break;
                    // if unlock code is available
                    case '2':
                        $mysql = new mysql();
                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                        $query = $mysql->query($sql);
                        $rows = $mysql->fetchArray($query);

                        $sql = 'update 
                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
                                    set
                                    im.status=2,
                                    reply_by=3,
                                    reply=' . $mysql->quote(base64_encode($result['RESULTTXT'])) . ',
                                    im.reply_date_time=now(),
                                    um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
                                    where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
                        $mysql->query($sql);
                        //if($mysql->rowCount($query) > 0)
                        {
                            $objCredits = new credits();
                            $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                        }
                        //  echo "IMEI Processed";
                        $status_flag = 1;
                        break;

                    // if imei is rejected
                    case '3':
                        $mysql = new mysql();
                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim   left join ' . IMEI_TOOL_MASTER . ' as tm
					on oim.tool_id=tm.id 
                                        where oim.status=1 and oim.id=' . $mysql->getInt($args['order_id']);
                        $query = $mysql->query($sql);
                        $rows = $mysql->fetchArray($query);

                        $api_rej = 0;
                        $api_rej_auto_man = 0;
                        $api_rej = $rows[0]['api_rej'];
                        $api_rej_auto_man = $rows[0]['api_rej_man_auto'];
                        if ($api_rej == 0) {
                            //reject as usual
                            $sql = 'update 
                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
                                    set
                                    im.status=3,
                                    reply_by=3,
                                    reply=' . $mysql->quote(base64_encode($result['REASON'])) . ',
                                    im.reply_date_time=now(),
                                    um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
                                where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                            $mysql->query($sql);
                            if ($mysql->rowCount($query) > 0) {
                                $objCredits = new credits();
                                $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                                $status_flag = 0;
                            }
                        } else {
                            // if api rejection chk is enable and also on auto process
                            if ($api_rej_auto_man == 0) {

                                // get the priority of that service
                                $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority,b_price_adj,om.b_rate_main from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($args['order_id']);

                                $query1 = $mysql->query($sql);

                                $rowCount = $mysql->rowCount($query1);

                                if ($rowCount != 0) {
                                    $rowss = $mysql->fetchArray($query1);
                                    $row_api_pri = $rowss[0];

                                    $new_api_id = $row_api_pri['api_id'];
                                    $new_api_name = $row_api_pri['api_name'];
                                    $new_api_service_id = $row_api_pri['api_service_id'];
                                    $temp_priority = $row_api_pri['s_priority'];
                                    $temp_priority = $temp_priority + 1;
                                    //buy price adj
                                    $order_main_b_price = $row_api_pri['b_rate_main'];
                                    $api_price_adj = $row_api_pri['b_price_adj'];

                                    if ($api_price_adj != "") {
                                        $api_price_adj = trim($api_price_adj);
                                        $action_on_price = substr($api_price_adj, 0, 1);
                                        if ($action_on_price == "+" || $action_on_price == "-") {
                                            $price_percent = substr($api_price_adj, 1);

                                            $calculate1 = $order_main_b_price / 100;
                                            $calculate2 = $calculate1 * $price_percent;
                                            if ($action_on_price == "+")
                                                $new_b_price = $order_main_b_price + $calculate2;
                                            else if ($action_on_price == "-")
                                                $new_b_price = $order_main_b_price - $calculate2;
                                            else
                                                $new_b_price = $order_main_b_price + $calculate2;
                                        } else
                                            $new_b_price = $order_main_b_price;
                                    } else
                                        $new_b_price = $order_main_b_price;
                                    //update order api creds
                                    //update order api creds
                                    $nothing = "";

                                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' 
								set
                                                                status=0,
                                                                main_api=0,
                                                                extern_id=' . $mysql->quote($nothing) . ',
								api_id=' . $new_api_id . ',
                                                                b_rate=    ' . $mysql->getFloat($new_b_price) . ',
                                                                api_name=' . $mysql->quote($new_api_name) . ',
                                                                api_service_id=' . $new_api_service_id . ',
                                                                api_rej_2_prio=' . $temp_priority . '
							where status=1 and id=' . $mysql->getInt($args['order_id']);
                                    $mysql->query($sql);
                                }
                                else {
                                    // reject orders like normal
                                    $sql = 'update 
                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
                                    set
                                    im.status=3,
                                    reply_by=3,
                                    reply=' . $mysql->quote(base64_encode($result['REASON'])) . ',
                                    im.reply_date_time=now(),
                                    um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
                                where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                    $mysql->query($sql);

                                    if ($mysql->rowCount($query) > 0) {
                                        $objCredits = new credits();
                                        $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                                        $status_flag = 0;
                                    }
                                }
                            } else {
                                // if api rejection chk is enable and also on manual process
                                // if api rejection chk is enable and also on manual process
                                $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($args['order_id']);

                                $query1 = $mysql->query($sql);

                                $rowCount = $mysql->rowCount($query1);

                                if ($rowCount != 0) {
                                    // provider aval in list

                                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' 
								set
								api_rej_2=1,
								reply_by=3,
								message=' . $mysql->quote(base64_encode($result['REASON'])) . ',
								reply_date_time=now()
							where status=1 and id=' . $mysql->getInt($args['order_id']);
                                    $mysql->query($sql);
                                } else {
                                    // reject order now
                                    // reject orders like normal
                                    $sql = 'update 
                                    ' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
                                    set
                                    im.status=3,
                                    reply_by=3,
                                    reply=' . $mysql->quote(base64_encode($result['REASON'])) . ',
                                    im.reply_date_time=now(),
                                    um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
                                where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                    $mysql->query($sql);

                                    if ($mysql->rowCount($query) > 0) {
                                        $objCredits = new credits();
                                        $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                                        $status_flag = 0;
                                    }
                                }
                            }
                        }
                        //echo "IMEI Return";		

                        break;
                    default:
                        print_r($result);
                        break;
                }
                if ($status_flag == 0) {
                    ///////////////////////////////////Email sending code **************************************
                    $sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $mysql->getInt($args['order_id']) . ')';
                    $query = $mysql->query($sql);

                    if ($mysql->rowCount($query) > 0) {
                        $rows = $mysql->fetchArray($query);
                        $argsAll = array();
                        foreach ($rows as $row) {
                            $args = array(
                                'to' => $row['email'],
                                'from' => $from_admin,
                                'fromDisplay' => $admin_from_disp,
                                'user_id' => $row['uid'],
                                'save' => '1',
                                'username' => $row['username'],
                                'imei' => $row['imei'],
                                'order_id' => $row['oid'],
                                'tool_name' => $row['tool_name'],
                                'credits' => $row['credits'],
                                'site_admin' => $admin_from_disp,
                                'send_mail' => true
                            );
                            array_push($argsAll, $args);
                        }
                        $objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $argsAll);
                    }
                } else if ($status_flag == 1) {
                    ///////////////////////////////////Email sending code **************************************
                    $sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $mysql->getInt($args['order_id']) . ')';

                    $query = $mysql->query($sql);
                    if ($mysql->rowCount($query) > 0) {
                        $rows = $mysql->fetchArray($query);
                        $argsAll = array();
                        foreach ($rows as $row) {
                            $args = array(
                                'to' => $row['email'],
                                'from' => $from_admin,
                                'fromDisplay' => $admin_from_disp,
                                'user_id' => $row['uid'],
                                'save' => '1',
                                'username' => $row['username'],
                                'imei' => $row['imei'],
                                'unlock_code' => base64_decode($row['reply']),
                                'order_id' => $row['oid'],
                                'tool_name' => $row['tool_name'],
                                'credits' => $row['credits'],
                                'site_admin' => $admin_from_disp,
                                'send_mail' => true
                            );
                            array_push($argsAll, $args);
                        }

                        $objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
                    }

                    ////.//////////////////////////////Email sending code*************************************
                }
            }
        }
    }

    public function api_place_order($args) {

        if (!defined("REQUESTFORMAT"))
            define("REQUESTFORMAT", "JSON");
        if (!defined("CODESK_URL"))
            define('CODESK_URL', $args['url']);
        if (!defined("USERNAME"))
            define("USERNAME", $args['username']);
        if (!defined("API_ACCESS_KEY"))
            define("API_ACCESS_KEY", $args['key']);


        $para = array();
        $para['IMEI'] = $args['imei'];
        $para['SERVICEID'] = $args['service_id'];
        $para['MEP'] = $args['mep'];
        $para['MODEL'] = $args['model'];
        $para['NETWORK'] = $args['network'];
        $para['PROVIDER'] = $args['network'];
        $para['POLARPRODUCT'] = "";
        $para['DATAFIELD'] = "";
        $para['FILEFIELD'] = "";

        $request = $this->action('PLACEORDER', $para);
        // print_r($request);

        if ($request['RET'] != 0) {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch service list now! Contact site admin for more assistance. ' . $request['RET']);
        }
        //print_r($resultArray);
        return array('id' => $request['IDORDER'], 'msg' => '');
    }

}

function XMLtoARRAY2($rawxml) {
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
    xml_parser_free($xml_parser);
    $params = array();
    $level = array();
    $alreadyused = array();
    $x = 0;
    foreach ($vals as $xml_elem) {
        if ($xml_elem['type'] == 'open') {
            if (in_array($xml_elem['tag'], $alreadyused)) {
                ++$x;
                $xml_elem['tag'] = $xml_elem['tag'] . $x;
            }
            $level[$xml_elem['level']] = $xml_elem['tag'];
            $alreadyused[] = $xml_elem['tag'];
        }
        if ($xml_elem['type'] == 'complete') {
            $start_level = 1;
            $php_stmt = '$params';
            while ($start_level < $xml_elem['level']) {
                $php_stmt .= '[$level[' . $start_level . ']]';
                ++$start_level;
            }
            $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
            eval($php_stmt);
            continue;
        }
    }
    return $params;
}
?>



