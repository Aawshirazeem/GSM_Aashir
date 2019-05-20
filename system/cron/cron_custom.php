<?php

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
    $strApi = ' and if(oim.main_api=1,tm.api_id,oim.api_id) = ' . $api_id;
}

//echo "Start";
/**
  Algorithem
 */
$mysql->query('SET SQL_BIG_SELECTS=1');

/** Get total number of orders per api */
$sql = 'select if(oim.main_api=1,tm.api_id,oim.api_id) as id, if(oim.main_api=1,tm.api_service_id,oim.api_service_id) as service_id, tm.api_priority as priority, count(oim.id) as total
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' tm on (tm.id = oim.tool_id)
					left join ' . API_MASTER . ' am on (am.id =if(oim.main_api=1,tm.api_id,oim.api_id))
					where am.server_id=15 and oim.status=0 and if(oim.main_api=1,tm.api_id!=0,oim.api_id!=0) ' . $strApi . '
					group by if(oim.main_api=1,tm.api_id,oim.api_id),if(oim.main_api=1,tm.api_service_id,oim.api_service_id)
					limit 20';
$query = $mysql->query($sql);
   //    echo $sql;exit;
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
    $itemID++;
    $services[$itemID] = array('api_id' => $item['id'], 'service_id' => $item['service_id'], 'total' => $temp);

    /** Calculate Total  */
    $total += $temp;
}
/** Add orders that are left after allocation to all services */
if (!empty($services)) {
    if ($total < $toSend) {
        $total = $services[$itemID]['total'] + $toSend - $total;
        $services[$itemID]['total'] = $total;
        $total += $toSend - $total;
    }
} else {
    //die("No imei in Queue...");
    echo "No imei in Queue...";
}
//print_r($services);die();



/** Algorithem --- END */
if (!empty($services)) {
    foreach ($services as $service) {
        $mysql->query('SET SQL_BIG_SELECTS=1');
        $sql = 'select
						oim.api_tries, oim.id as oid,tm.api_rej,tm.api_rej_man_auto,oim.imei, oim.model_id as model, oim.custom_value,
						oim.network_id as network, oim.prd, oim.pin,
						if(oim.main_api=1,tm.api_id,oim.api_id) api_id,if(oim.main_api=1,tm.api_service_id,oim.api_service_id) api_service_id,
						am.server_id, am.api_server, am.username, am.password, am.key, am.url,
						 ad.provider as provider, ad.service_name,ad.id as cust_id,ad.info,
						imm.mep
						from ' . ORDER_IMEI_MASTER . ' oim
						left join ' . IMEI_TOOL_MASTER . ' tm on (tm.id = oim.tool_id)
						left join ' . API_MASTER . ' am on (am.id = if(oim.main_api=1,tm.api_id,oim.api_id))
						left join ' . API_DETAILS . ' ad on (ad.service_id = if(oim.main_api=1,tm.api_service_id,oim.api_service_id) and ad.api_id = if(oim.main_api=1,tm.api_id,oim.api_id))
						left join ' . IMEI_MEP_MASTER . ' imm on(oim.mep_id = imm.id)
						where oim.status =0 and if(oim.main_api=1,tm.api_id!=0,oim.api_id!=0)
						and if(oim.main_api=1,tm.api_id,oim.api_id) =' . $service['api_id'] . ' and if(oim.main_api=1,tm.api_service_id,oim.api_service_id)=' . $service['service_id'] . '
						order by api_tries
						limit ' . $service['total'];
        $query = $mysql->query($sql);

        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            $strIds = '';
            foreach ($rows as $row) {
                $strIds .= $row['oid'] . ',';
            }
            $strIds = trim($strIds, ',');

            $sql = 'update ' . ORDER_IMEI_MASTER . ' set api_tries = api_tries + 1 where id in (' . $strIds . ')';
            $mysql->query($sql);

            //	echo '<pre>';
            $i = 0;
            foreach ($rows as $row) {
                //	echo ($i == 0) ? ($row['api_server'] . ':' . $row['service_name'] . "\n") : '';
                $i++;
                /* echo  $row['imei'] . "\n";
                  $args['imei'] = $row['imei'];
                  $order_id_sys = $row['oid'];
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
                  $args['custom_value'] = $row['custom_value']; */

                if (defined("debug")) {
                    print_r($args);
                }
                //exit();
                if ($row['server_id'] == 15) {
                    // custom API

                    $api_rej = 0;
                    $api_rej_auto_man = 0;
                    $api_rej = $row['api_rej'];
                    $api_rej_auto_man = $row['api_rej_man_auto'];


                    $imei_to_send = $row['imei'];
                    $url_to_send = $row['info'];
                    $order_id_sys = $row['oid'];
                    $tempurl = $url_to_send . $imei_to_send;
                    if ($imei_to_send != "" && $url_to_send != "") {

                        // send 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $tempurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                        $rezultat = curl_exec($ch);

                        if (trim($rezultat) != "") {

                            if (trim($rezultat) != "")
                                $errorcheck = 0;
                            else
                                $errorcheck = 2;
                            if (curl_errno($ch) != CURLE_OK) {
                                //echo curl_error($ch);
                                $msg = curl_error($ch);
                                echo 'INTERNAL ERROR....<br>Try Again after sometime';
                                $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set
						im.remarks = ' . $mysql->quote($msg) . '
					where im.status=0 and im.id=' . $mysql->getInt($order_id_sys);
                                $mysql->query($sql);
                                $errorcheck = 2;
                                curl_close($ch);
                                // exit;
                            } else {
                                curl_close($ch);
                                // now check the result and compare it with internal custom API errors

                                $sqlerr = 'select * from nxt_api_errors';
                                $sqlerr = 'select * from nxt_api_errors a where a.api_id=' . $row['cust_id'];
                                $queryerr = $mysql->query($sqlerr);

                                $reply = "";

                                if ($mysql->rowCount($queryerr) > 0) {
                                    $rowserr = $mysql->fetchArray($queryerr);

                                    foreach ($rowserr as $apierror) {
                                        // check for error one by one
                                        if (strstr(trim($rezultat), $apierror['reason'])) {
                                            if ($apierror['action'] == 1) {
                                                //if error matches.... take action
                                                $errorcheck = 1;
                                                $reply = $apierror['reply'];
                                            } else {
                                                //if error matches....take no action
                                                $errorcheck = 2;
                                            }
                                        }
                                    }
                                }
                                //     echo 'errrr:'.$errorcheck;exit;
                                // now decide order status with $errorcheck

                                $sql1 = 'select tm.tool_name,um.username,um.email,oim.credits,oim.imei
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . USER_MASTER . ' as um
					on oim.user_id=um.id
				        left join ' . IMEI_TOOL_MASTER . ' as tm
					on oim.tool_id=tm.id
					where 
					oim.id=' . $mysql->getInt($order_id_sys);
                                //echo $sql1;
                                $query1 = $mysql->query($sql1);
                                $rows1 = $mysql->fetchArray($query1);

                                $tool_name = $rows1[0]['tool_name'];
                                $user_name = $rows1[0]['username'];
                                $u_email = $rows1[0]['email'];
                                $price = $rows1[0]['credits'];
                                $o_imei = $rows1[0]['imei'];
                                //   $accept_flag = false;
                                // $code = $request['SUCCESS']['0']['CODE'];
                                $code = base64_encode($rezultat);
                                $reply = base64_encode($reply);

                                if ($errorcheck == 1) {
                                    // order rejecct
                                    if ($api_rej == 0) {

                                        // if api rejection chk is disable then

                                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($order_id_sys);
                                        $query = $mysql->query($sql);
                                        $rows = $mysql->fetchArray($query);

                                        $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($reply) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id_sys);
                                        $mysql->query($sql);
                                        // send credit back to user
                                        $objCredits = new credits();
                                        $objCredits->returnIMEI($mysql->getInt($order_id_sys), $rows[0]['user_id'], $rows[0]['credits']);
                                        // send email to user

                                        $objEmail = new email();
                                        $email_config = $objEmail->getEmailSettings();
                                        $from_admin = $email_config['system_email'];
                                        $admin_from_disp = $email_config['system_from'];
                                        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                        $simple_email_body = '';
                                        //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                        $simple_email_body .= '
         <p><b>Service Name:</b>' . $tool_name . '<p>
         <p><b>IMEI:</b>' . $o_imei . '<p>
         <p><b>Reson:</b>' . base64_decode($reply) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $order_id_sys . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                                        $objEmail->setTo($u_email);
                                        $objEmail->setFrom($from_admin);
                                        $objEmail->setFromDisplay($admin_from_disp);
                                        $objEmail->setSubject("Unlock Code Not Found");
                                        $objEmail->setBody($simple_email_body);
                                        // $sent = $objEmail->sendMail();
                                        $objEmail->queue();
                                    } else {
                                        // if enable then what
                                        if ($api_rej_auto_man == 0) {

                                            // get the priority of that service
                                            $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority,b_price_adj,om.b_rate_main from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($order_id_sys);

                                            $query = $mysql->query($sql);

                                            $rowCount = $mysql->rowCount($query);

                                            if ($rowCount != 0) {
                                                $rows = $mysql->fetchArray($query);
                                                $row_api_pri = $rows[0];

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
							where status=0 and id=' . $mysql->getInt($order_id_sys);
                                                $mysql->query($sql);
                                            } else {
                                                // reject orders like normal

                                                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($order_id_sys);
                                                $query = $mysql->query($sql);
                                                $rows = $mysql->fetchArray($query);

                                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($reply) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id_sys);
                                                $mysql->query($sql);
                                                // send credit back to user
                                                $objCredits = new credits();
                                                $objCredits->returnIMEI($mysql->getInt($order_id_sys), $rows[0]['user_id'], $rows[0]['credits']);
                                                // send email to user

                                                $objEmail = new email();
                                                $email_config = $objEmail->getEmailSettings();
                                                $from_admin = $email_config['system_email'];
                                                $admin_from_disp = $email_config['system_from'];
                                                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                                $simple_email_body = '';
                                                //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                                $simple_email_body .= '
         <p><b>Service Name:</b>' . $tool_name . '<p>
         <p><b>IMEI:</b>' . $o_imei . '<p>
         <p><b>Reson:</b>' . base64_decode($reply) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $order_id_sys . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                                                $objEmail->setTo($u_email);
                                                $objEmail->setFrom($from_admin);
                                                $objEmail->setFromDisplay($admin_from_disp);
                                                $objEmail->setSubject("Unlock Code Not Found");
                                                $objEmail->setBody($simple_email_body);
                                                // $sent = $objEmail->sendMail();
                                                $objEmail->queue();
                                            }
                                        } else {
                                            // if api rejection chk is enable and also on manual process
                                            $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($order_id_sys);

                                            $query = $mysql->query($sql);

                                            $rowCount = $mysql->rowCount($query);

                                            if ($rowCount != 0) {
                                                // provider aval in list

                                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' 
								set
                                                                status=1,
								api_rej_2=1,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								reply_date_time=now()
							where status=0 and id=' . $mysql->getInt($order_id_sys);
                                                $mysql->query($sql);
                                            } else {
                                                // reject order now
                                                // reject orders like normal

                                                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($order_id_sys);
                                                $query = $mysql->query($sql);
                                                $rows = $mysql->fetchArray($query);

                                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($reply) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id_sys);
                                                $mysql->query($sql);
                                                // send credit back to user
                                                $objCredits = new credits();
                                                $objCredits->returnIMEI($mysql->getInt($order_id_sys), $rows[0]['user_id'], $rows[0]['credits']);
                                                // send email to user

                                                $objEmail = new email();
                                                $email_config = $objEmail->getEmailSettings();
                                                $from_admin = $email_config['system_email'];
                                                $admin_from_disp = $email_config['system_from'];
                                                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                                $simple_email_body = '';
                                                //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                                $simple_email_body .= '
         <p><b>Service Name:</b>' . $tool_name . '<p>
         <p><b>IMEI:</b>' . $o_imei . '<p>
         <p><b>Reson:</b>' . base64_decode($reply) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $order_id_sys . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                                                $objEmail->setTo($u_email);
                                                $objEmail->setFrom($from_admin);
                                                $objEmail->setFromDisplay($admin_from_disp);
                                                $objEmail->setSubject("Unlock Code Not Found");
                                                $objEmail->setBody($simple_email_body);
                                                // $sent = $objEmail->sendMail();
                                                $objEmail->queue();
                                            }
                                        }
                                    }

                                    // end
                                } else if ($errorcheck == 0) {
                                    // order accept

                                    $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($order_id_sys);
                                    $query = $mysql->query($sql);
                                    $rows = $mysql->fetchArray($query);

                                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=2, reply_by=3,
								reply=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
								where im.status=0 and um.id = im.user_id and im.id=' . $order_id_sys;
                                    $mysql->query($sql);

                                    //send maill code if unlock code available
                                    $objEmail = new email();
                                    $email_config = $objEmail->getEmailSettings();
                                    $admin_email = $email_config['admin_email'];
                                    $system_from = $email_config['system_email'];
                                    $from_display = $email_config['system_from'];


                                    $args = array(
                                        'to' => $u_email,
                                        'from' => $system_from,
                                        'fromDisplay' => $from_display,
                                        'username' => $user_name,
                                        'order_id' => $order_id_sys,
                                        'imei' => $o_imei,
                                        'tool_name' => $tool_name,
                                        'credits' => $price,
                                        'unlock_code' => base64_decode($code),
                                        'send_mail' => true
                                    );
// send mail done
                                    $objEmail->sendEmailTemplate('admin_user_imei_avail', $args);
                                    //get reseller profit

                                    $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits  from nxt_order_imei_master as a
                                       left join nxt_imei_tool_amount_details as b
                                       on a.tool_id=b.tool_id
                                       left join nxt_imei_spl_credits_reseller as c
                                       on a.tool_id=c.tool_id
                                       left join nxt_user_master as d
                                       on a.user_id=d.id
                                       where a.id=' . $mysql->getInt($order_id_sys) . '
                                       and b.currency_id=d.currency_id';
                                    $query = $mysql->query($sql);
                                    $rows1 = $mysql->fetchArray($query);
                                    $profit = $rows1[0]["profit"];
                                    $reseller_id = $rows1[0]["reseller_id"];
                                    $credits_after = $rows1[0]["credits"];
                                    $credits_after = $credits_after + $profit;
                                    if ($reseller_id != 0) {
                                        $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + ' . $profit . '
									where um.id = ' . $reseller_id . ';
                                                                            ';

                                        $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              ' . $reseller_id . ',
											now(),
											' . $profit . ',
                                                                                        ' . $credits_after . ',
                                                                                        ' . $id . ',    
											' . $mysql->quote("Reseller Profit From IMEI Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                                    }
                                    if ($sqlAvail != '') {
                                        $mysql->multi_query($sqlAvail);
                                        $sqlAvail = '';
                                    }
                                } else {
                                    //no work
                                    echo 'No Action';
                                    // exit;
                                }
                            }
                        } else {
                            echo 'Result Is Empty';
                        }
                    }
// var_dump($rezultat);
//exit;
                }
            }
//echo '</pre>';
        }
    }
}
exit;
