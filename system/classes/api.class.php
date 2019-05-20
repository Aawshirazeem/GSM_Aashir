<?php

/*



 */

class api {

    public function get($api_id, $args) {

        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_get($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_get($args);
                break;
            case '3':
                return $this->lgtool_net_get($args);
                break;
            case '4':
                return $this->infinity_online_service_get($args);
                break;
            case '5':
                return $this->imeicheck_get($args);
                break;
            case '6':
                return $this->blockcheck_get($args);
                break;
            case '7':
                return $this->bruteforcemarket_get($args);
                break;
            case '8':
                return $this->dhrufusionapi_get($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_get_code($args);
                break;
            case '10':
                $api_hotunlockcode = new api_hotunlockcode();
                return $api_hotunlockcode->api_get_code($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_get_code($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_get_code($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_get_code($args);
                break;
            case '14':
                $api_dlgsm = new api_dlgsm();
                return $api_dlgsm->api_get_code($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function get_file($api_id, $args) {

        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_get_file_new($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_get($args);
                break;
            case '3':
                return $this->lgtool_net_get($args);
                break;
            case '4':
                return $this->infinity_online_service_get($args);
                break;
            case '5':
                return $this->imeicheck_get($args);
                break;
            case '6':
                return $this->blockcheck_get($args);
                break;
            case '7':
                return $this->bruteforcemarket_get($args);
                break;
            case '8':
                return $this->dhrufusionapi_get_file_new($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_get_code($args);
                break;
            case '10':
                $api_hotunlockcode = new api_hotunlockcode();
                return $api_hotunlockcode->api_get_code($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_get_code($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_get_code($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_get_code($args);
                break;
            case '14':
                $api_dlgsm = new api_dlgsm();
                return $api_dlgsm->api_get_code($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function send($api_id, $args) {
        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_send($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_send($args);
                break;
            case '3':
                return $this->lgtool_net_send($args);
                break;
            case '4':
                return $this->infinity_online_service_send($args);
                break;
            case '5':
                return $this->imeicheck_send($args);
                break;
            case '6':
                return $this->blockcheck_send($args);
                break;
            case '7':
                return $this->bruteforcemarket_send($args);
                break;
            case '8':
                return $this->dhrufusionapi_send($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_place_order($args);
                break;
            case '10':
                $api_hotunlockcode = new api_hotunlockcode();
                return $api_hotunlockcode->api_place_order($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_place_order($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_place_order($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_place_order($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function sendfileorder($api_id, $args) {
        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_send_file_new($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_send($args);
                break;
            case '3':
                return $this->lgtool_net_send($args);
                break;
            case '4':
                return $this->infinity_online_service_send($args);
                break;
            case '5':
                return $this->imeicheck_send($args);
                break;
            case '6':
                return $this->blockcheck_send($args);
                break;
            case '7':
                return $this->bruteforcemarket_send($args);
                break;
            case '8':
                return $this->dhrufusionapi_send_file_new($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_place_order($args);
                break;
            case '10':
                $api_hotunlockcode = new api_hotunlockcode();
                return $api_hotunlockcode->api_place_order($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_place_order($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_place_order($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_place_order($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function credits($api_id, $args) {
        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_credits($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_credits($args);
                break;
            case '3':
                return $this->lgtool_net_credits($args);
                break;
            case '4':
                return $this->infinity_online_service_credits($args);
                break;
            case '5':
                //return $this->imeicheck_credits($args);
                break;
            case '6':
                return $this->blockcheck_credits($args);
                break;
            case '7':
                return $this->bruteforcemarket_credits($args);
                break;
            case '8':
                return $this->dhrufusionapi_credits($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_get_credits($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_get_credits($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_get_credits($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_credits($args);
                break;
            case '14':
                $api_dlgsm = new api_dlgsm();
                return $api_dlgsm->api_credits($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function sync_tools($api_id, $args) {
        switch ($api_id) {
            case '1':
                return $this->dhrufusionapi_sync_tools($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                return $api_super_htc->superhtc_sync_tools($args);
                break;
            case '3':
                return $this->lgtool_net_sync_tools($args);
                break;
            case '4':
                return $this->infinity_online_service_sync_tools($args);
                break;
            case '5':
                return $this->imeicheck_sync_tools($args);
                break;
            case '6':
                return $this->bruteforcemarket_sync_tools($args);
                break;
            case '7':
                return $this->blockcheck_sync_tools($args);
                break;
            case '8':
                return $this->dhrufusionapi_sync_tools($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                return $UnlockBase->api_sync_tools($args);
                break;
            case '11':
                $api_eu_unlock = new api_eu_unlock();
                return $api_eu_unlock->api_sync_tools($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                return $api_codedesk->api_sync_tools($args);
                break;
            case '13':
                $api_ubox = new api_ubox();
                return $api_ubox->api_sync_tools($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function sync_brands($api_id, $args) {
        switch ($api_id) {
            case '1':
                $api_gsmfreedom = new api_gsmfreedom();
                //return $api_gsmfreedom->gsmfreedom_sync_brands($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                //return $api_super_htc->superhtc_sync_brands($args);
                break;
            case '3':
                //return $this->lgtool_net_sync_brands($args);
                break;
            case '4':
                //return $this->infinity_online_service_sync_brands($args);
                break;
            case '5':
                //return $this->imeicheck_sync_brands($args);
                break;
            case '6':
                //return $this->bruteforcemarket_sync_brands($args);
                break;
            case '7':
                //return $this->blockcheck_sync_brands($args);
                break;
            case '8':
                //return $this->dhrufusionapi_sync_brands($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                //return $UnlockBase->api_sync_brands($args);
                break;
            case '12':
                $api_codedesk = new api_codedesk();
                //return $UnlockBase->api_sync_brands($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    public function sync_country($api_id, $args) {
        switch ($api_id) {
            case '1':
                $api_gsmfreedom = new api_gsmfreedom();
                //return $api_gsmfreedom->gsmfreedom_sync_country($args);
                break;
            case '2':
                $api_super_htc = new api_super_htc();
                //return $api_super_htc->superhtc_sync_country($args);
                break;
            case '3':
                //return $this->lgtool_net_sync_country($args);
                break;
            case '4':
                //return $this->infinity_online_service_sync_country($args);
                break;
            case '5':
                //return $this->imeicheck_sync_country($args);
                break;
            case '6':
                //return $this->bruteforcemarket_sync_country($args);
                break;
            case '7':
                //return $this->blockcheck_sync_country($args);
                break;
            case '8':
                //return $this->dhrufusionapi_sync_country($args);
                break;
            case '9':
                $UnlockBase = new UnlockBase();
                //return $UnlockBase->api_sync_country($args);
                break;
            default:
                break;
        }
        return array('id' => '-1', 'msg' => 'API not configured to send IMEI!');
    }

    /*     * ***************************************
     * *****************************************
      Bruteforce Market
     * *****************************************
     * **************************************** */

    public function bruteforcemarket_send($args) {
        $api = new api_bruteforcemarket($url, $username, $key);
        $response = $api->upload($imei, $custom);
        if (isset($response['error'])) {
            return array('id' => '-1', 'msg' => $response['error_msg']);
        }
        if ($response['success']) {
            return array('id' => 0, 'msg' => "ok");
        } else {
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.');
        }
    }

    /*     * ***************************************
     * *****************************************
      Dhru Fusion API
     * *****************************************
     * **************************************** */

    public function dhrufusionapi_credits($args) {

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
        $request = $api->action('accountinfo');
        if (isset($request['ERROR'])) {

            if ($request['ERROR'][0]['MESSAGE'] == "Invalid IP Request")
                $eroris = "Your Server IP is not allowed...";
            else
                $eroris = $request['ERROR'][0]['MESSAGE'];
            //var_dump($request);
            return array('credits' => '-1', 'msg' => 'Cant SYNC API.' . $eroris);
        }
        return array('credits' => $request['SUCCESS'][0]['AccoutInfo']['credit'], 'msg' => '', 'currency' => $request['SUCCESS'][0]['AccoutInfo']['currency'], 'email' => $request['SUCCESS'][0]['AccoutInfo']['mail']);
    }

    public function dhrufusionapi_get($args) {
        //echo 'file api';	
        $status_flag = -1;

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
        $mysql = new mysql();

        $para['ID'] = $args['extern_id'];
        $request = $api->action('getimeiorder', $para);

        if (defined("debug")) {
            error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruCheck.log");
            print_r($request);
        }

        if (isset($request['SUCCESS'])) {
//			$objEmail = new email();
//			$email_config 		= $objEmail->getEmailSettings();
//			$from_admin 		= $email_config['system_email'];
//			$admin_from_disp	= $email_config['system_from'];
            $sql1 = 'select tm.tool_name, tm.api_rej,tm.api_rej_man_auto, um.username,um.email,oim.credits,oim.imei,um.imei_suc_noti,um.imei_rej_noti
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . USER_MASTER . ' as um
					on oim.user_id=um.id
				        left join ' . IMEI_TOOL_MASTER . ' as tm
					on oim.tool_id=tm.id
					where 
					oim.id=' . $mysql->getInt($args['order_id']);
            //echo $sql1;
            $query1 = $mysql->query($sql1);
            $rows1 = $mysql->fetchArray($query1);

            $tool_name = $rows1[0]['tool_name'];
            $api_rej = 0;
            $api_rej_auto_man = 0;
            $api_rej = $rows1[0]['api_rej'];
            $api_rej_auto_man = $rows1[0]['api_rej_man_auto'];
            $imei_suc_noti = $rows1[0]['imei_suc_noti'];
            $imei_rej_noti = $rows1[0]['imei_rej_noti'];
            $user_name = $rows1[0]['username'];
            $u_email = $rows1[0]['email'];
            $price = $rows1[0]['credits'];
            $o_imei = $rows1[0]['imei'];
            $accept_flag = false;

            $code = $request['SUCCESS']['0']['CODE'];
            $code = base64_encode($code);
            switch ($request['SUCCESS']['0']['STATUS']) {
                //get data from user_table,service_table
                //end
                case '3':

                    if ($api_rej == 0) {

                        // if api rejection chk is disable then
                        //reject normal
                        $mysql = new mysql();
                        $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where status=1 and id=' . $mysql->getInt($args['order_id']);
                        $query = $mysql->query($sql);
                        $rows = $mysql->fetchArray($query);


                        $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                        $mysql->query($sql);
                        if ($mysql->rowCount($query) > 0) {
                            $objCredits = new credits();
                            $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                            //echo "IMEI Return : " . $args['imei'] . PHP_EOL;		
                            $status_flag = 0;

                            //send maill code if unlock code unavailable

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
         <p><b>Reson:</b>' . base64_decode($code) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $args['order_id'] . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                            $objEmail->setTo($u_email);
                            $objEmail->setFrom($from_admin);
                            $objEmail->setFromDisplay($admin_from_disp);
                            $objEmail->setSubject("Unlock Code Not Found");
                            $objEmail->setBody($simple_email_body);
                            // $sent = $objEmail->sendMail();
                            if ($imei_rej_noti == 1)
                                $objEmail->queue();
                            // SEND Simple Email
                            ////end
                        }

                        break;
                    }
                    else {
                        // if api rejection chk is enable and also on auto process
                        if ($api_rej_auto_man == 0) {

                            // get the priority of that service
                            $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority,b_price_adj,om.b_rate_main from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($args['order_id']);

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
                                break;
                            } else {
                                // reject orders like normal
                                //reject normal
                                $mysql = new mysql();
                                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where status=1 and id=' . $mysql->getInt($args['order_id']);
                                $query = $mysql->query($sql);
                                $rows = $mysql->fetchArray($query);


                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                $mysql->query($sql);
                                if ($mysql->rowCount($query) > 0) {
                                    $objCredits = new credits();
                                    $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                                    //echo "IMEI Return : " . $args['imei'] . PHP_EOL;		
                                    $status_flag = 0;

                                    //send maill code if unlock code unavailable

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
         <p><b>Reson:</b>' . base64_decode($code) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $args['order_id'] . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                                    $objEmail->setTo($u_email);
                                    $objEmail->setFrom($from_admin);
                                    $objEmail->setFromDisplay($admin_from_disp);
                                    $objEmail->setSubject("Unlock Code Not Found");
                                    $objEmail->setBody($simple_email_body);
                                    // $sent = $objEmail->sendMail();
                                    if ($imei_rej_noti == 1)
                                        $objEmail->queue();
                                    // SEND Simple Email
                                    ////end
                                }

                                break;
                            }


                            //   break;
                        } else {
                            // if api rejection chk is enable and also on manual process
                            $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $mysql->getInt($args['order_id']);

                            $query = $mysql->query($sql);

                            $rowCount = $mysql->rowCount($query);

                            if ($rowCount != 0) {
                                // provider aval in list

                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' 
								set
								api_rej_2=1,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								reply_date_time=now()
							where status=1 and id=' . $mysql->getInt($args['order_id']);
                                $mysql->query($sql);
                            } else {
                                // reject order now
                                // reject orders like normal
                                //reject normal
                                $mysql = new mysql();
                                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where status=1 and id=' . $mysql->getInt($args['order_id']);
                                $query = $mysql->query($sql);
                                $rows = $mysql->fetchArray($query);


                                $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                $mysql->query($sql);
                                if ($mysql->rowCount($query) > 0) {
                                    $objCredits = new credits();
                                    $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                                    //echo "IMEI Return : " . $args['imei'] . PHP_EOL;		
                                    $status_flag = 0;

                                    //send maill code if unlock code unavailable

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
         <p><b>Reson:</b>' . base64_decode($code) . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $args['order_id'] . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                                    $objEmail->setTo($u_email);
                                    $objEmail->setFrom($from_admin);
                                    $objEmail->setFromDisplay($admin_from_disp);
                                    $objEmail->setSubject("Unlock Code Not Found");
                                    $objEmail->setBody($simple_email_body);
                                    // $sent = $objEmail->sendMail();
                                    if ($imei_rej_noti == 1)
                                        $objEmail->queue();
                                    // SEND Simple Email
                                    ////end
                                }

                                break;
                            }


                            break;
                        }
                        break;
                    }
                // if unlock code is available
                case '4':
                    $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                    $query = $mysql->query($sql);
                    $rows = $mysql->fetchArray($query);

                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=2, reply_by=3,
								reply=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
								where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
                    $mysql->query($sql);

                    $objCredits = new credits();
                    $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);

                    //echo "IMEI Processed" . $args['imei'] . PHP_EOL;
                    $status_flag = 1;

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
                        'order_id' => $args['order_id'],
                        'imei' => $o_imei,
                        'tool_name' => $tool_name,
                        'credits' => $price,
                        'unlock_code' => base64_decode($code),
                        'send_mail' => true
                    );

                    if ($imei_suc_noti == 1)
                        $objEmail->sendEmailTemplate('admin_user_imei_avail', $args);
                    ////end
                    //get reseller profit

                    $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits  from nxt_order_imei_master as a
                                       left join nxt_imei_tool_amount_details as b
                                       on a.tool_id=b.tool_id
                                       left join nxt_imei_spl_credits_reseller as c
                                       on a.tool_id=c.tool_id
                                       left join nxt_user_master as d
                                       on a.user_id=d.id
                                       where a.id=' . $mysql->getInt($args['order_id']) . '
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
                    //
                    break;
                default:
                    if (defined("debug")) {
                        print_r($request);
                    }
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
                    //$objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $argsAll);
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

                    //$objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
                }
            }
        }
        if (isset($request['ERROR'])) {
            $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);

            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set
						remarks=' . $mysql->quote($request['ERROR'][0]['MESSAGE']) . '
					where im.id=' . $mysql->getInt($args['order_id']);
            $mysql->query($sql);
            if ($mysql->rowCount($query) > 0) {
                //$objCredits = new credits();
                //$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
            }
            //echo "IMEI Return : " . $args['imei'] . PHP_EOL;
        }
    }

    public function dhrufusionapi_get_file($args) {
        //echo 'file api';	
        $status_flag = -1;

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
        $mysql = new mysql();
        //var_dump($args);exit;
        $para['ID'] = $args['extern_id'];

        $request = $api->action('getfileorder', $para);
        // print_r($request);exit;
        if (defined("debug")) {
            error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruCheck.log");
            print_r($request);
        }

        if (isset($request['SUCCESS'])) {
//			$objEmail = new email();
//			$email_config 		= $objEmail->getEmailSettings();
//			$from_admin 		= $email_config['system_email'];
//			$admin_from_disp	= $email_config['system_from'];
//			         $sql1 ='select tm.tool_name,um.username,um.email,oim.credits,oim.imei
//					from ' . ORDER_IMEI_MASTER . ' oim
//					left join ' . USER_MASTER . ' as um
//					on oim.user_id=um.id
//				        left join ' . IMEI_TOOL_MASTER . ' as tm
//					on oim.tool_id=tm.id
//					where 
//					oim.id=' .$mysql->getInt($args['order_id']);

            $sql1 = 'select tm.service_name,um.username,um.email,oim.credits,oim.id
					from ' . ORDER_FILE_SERVICE_MASTER . ' oim
					left join ' . USER_MASTER . ' as um
					on oim.user_id=um.id
				        left join ' . FILE_SERVICE_MASTER . ' as tm
					on oim.api_service_id=tm.id
					where 
					oim.id=' . $mysql->getInt($args['order_id']);
            //echo $sql1;exit;
            $query1 = $mysql->query($sql1);
            $rows1 = $mysql->fetchArray($query1);

            $tool_name = $rows1[0]['service_name'];
            $user_name = $rows1[0]['username'];
            $u_email = $rows1[0]['email'];
            $price = $rows1[0]['credits'];
            $o_imei = $rows1[0]['id'];
            $accept_flag = false;

            $code = $request['SUCCESS']['0']['CODE'];
            switch ($request['SUCCESS']['0']['STATUS']) {
                //get data from user_table,service_table
                //end
                case '2':
                    $mysql = new mysql();
                    $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                    //  echo $sql;exit;
                    $query = $mysql->query($sql);
                    $rows = $mysql->fetchArray($query);

                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								message=' . $mysql->quote($code) . ',
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                    //$mysql->query($sql);


                    $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $mysql->quote('API') . ',
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($code) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                    //echo $sql;exit;
                    $mysql->query($sql);



                    //if($mysql->rowCount($query) > 0)
                    {
                        $objCredits = new credits();
                        $objCredits->returnFile($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                        //$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                    }
                    //echo "FILE ORDER Return : " . $args['order_id'] . PHP_EOL;		
                    $status_flag = 0;

                    //send maill code if unlock code unavailable

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
         <p><b>Reson:</b>' . $code . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $args['order_id'] . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                    $objEmail->setTo($u_email);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Unlock Code Not Found");
                    $objEmail->setBody($simple_email_body);
                    //   $sent = $objEmail->sendMail();
                    $objEmail->queue();
                    // SEND Simple Email
                    ////end
                    break;
                // if unlock code is available
                case '1':
//					$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
//					$query = $mysql->query($sql);
//					$rows = $mysql->fetchArray($query);

                    $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=2, reply_by=3,
								reply=' . $mysql->quote(base64_encode($code)) . ',
								im.reply_date_time=now(),
								um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
								where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
                    //$mysql->query($sql);



                    $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($code) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $args['order_id'];

                    $mysql->query($sql);



                    //$objCredits = new credits();
                    //$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                    //echo "IMEI Processed" . $args['order_id'] . PHP_EOL;
                    //$status_flag = 1;
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
                        'order_id' => $args['order_id'],
                        'imei' => $o_imei,
                        'tool_name' => $tool_name,
                        'credits' => $price,
                        'unlock_code' => $code,
                        'send_mail' => true
                    );

                    $args2 = array(
                        'to' => $u_email,
                        'from' => $system_from,
                        'fromDisplay' => $from_display,
                        'user_id' => $user_id,
                        'save' => '1',
                        'username' => $username,
                        'order_id' => $args['order_id'],
                        'file_service' => $tool_name,
                        'credits' => $price,
                        'site_admin' => CONFIG_SITE_NAME
                    );

                    //$objEmail->sendEmailTemplate('admin_user_imei_avail', $args);
                    $objEmail->sendEmailTemplate('admin_user_order_file_accept', $args2, TRUE);
                    //echo 'File Done';
                    ////end
                    //get reseller profit
//                                 $sql='select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits  from nxt_order_imei_master as a
//                                       left join nxt_imei_tool_amount_details as b
//                                       on a.tool_id=b.tool_id
//                                       left join nxt_imei_spl_credits_reseller as c
//                                       on a.tool_id=c.tool_id
//                                       left join nxt_user_master as d
//                                       on a.user_id=d.id
//                                       where a.id='.$mysql->getInt($args['order_id']).'
//                                       and b.currency_id=d.currency_id';
//	                       //  $query=$mysql->query($sql);
//                                 $rows1=$mysql->fetchArray($query);
//                                 $profit=$rows1[0]["profit"];  
//                                 $reseller_id=$rows1[0]["reseller_id"];
//                                 $credits_after=$rows1[0]["credits"];
//                                 $credits_after=$credits_after + $profit;
//                                    if($reseller_id!=0)
//                                        {
//                                            $sqlAvail .= 'update ' . USER_MASTER . ' um
//									set um.credits =um.credits + '.$profit.'
//									where um.id = '.$reseller_id.';
//                                                                            ';
//                                                                    
//                                            $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
//									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
//								         values(
//									              '.$reseller_id.',
//											now(),
//											'.$profit.',
//                                                                                        '.$credits_after.',
//                                                                                        '.$id.',    
//											' . $mysql->quote("Reseller Profit From IMEI Order") . ',
//											1,
//											' . $mysql->quote($ip) . '
//										);
//                                                                                ';
//                                            
//                                        }
//                                if($sqlAvail != '')
//			{
//				$mysql->multi_query($sqlAvail);
//				$sqlAvail = '';
//				
//			}
                    //
                                break;
                default:
                    if (defined("debug")) {
                        print_r($request);
                    }
                    break;
            }
//			if ($status_flag == 0)
//			{
//				///////////////////////////////////Email sending code **************************************
//					$sql = 'select
//							um.username, um.email,
//							oim.id as oid, oim.user_id uid, oim.reply,
//							oim.imei, itm.tool_name, oim.credits
//						from ' . ORDER_IMEI_MASTER . ' oim 
//						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
//						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
//					where oim.id in (' .  $mysql->getInt($args['order_id']) . ')';
//					$query = $mysql->query($sql);
//					
//					if($mysql->rowCount($query) > 0)
//					{
//						$rows = $mysql->fetchArray($query);
//						$argsAll = array();
//						foreach($rows as $row)
//						{
//							$args = array(
//											'to' => $row['email'],
//											'from' => $from_admin,
//											'fromDisplay' => $admin_from_disp,
//											'user_id' => $row['uid'],
//											'save' => '1',
//											'username' => $row['username'],
//											'imei' => $row['imei'],
//											'order_id' => $row['oid'],
//											'tool_name' => $row['tool_name'],
//											'credits'=>	$row['credits'],
//											'site_admin' => $admin_from_disp,
//											'send_mail' => true
//										);
//							array_push($argsAll, $args);
//						}
//						//$objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $argsAll);
//					}
//			}
//			else if ($status_flag == 1)
//			{
//				///////////////////////////////////Email sending code **************************************
//					$sql = 'select
//							um.username, um.email,
//							oim.id as oid, oim.user_id uid, oim.reply,
//							oim.imei, itm.tool_name, oim.credits
//						from ' . ORDER_IMEI_MASTER . ' oim 
//						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
//						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
//					where oim.id in (' . $mysql->getInt($args['order_id']). ')';
//					
//					$query = $mysql->query($sql);
//					if($mysql->rowCount($query) > 0)
//					{
//						$rows = $mysql->fetchArray($query);
//						$argsAll = array();
//						foreach($rows as $row)
//						{
//							$args = array(
//											'to' => $row['email'],
//											'from' => $from_admin,
//											'fromDisplay' => $admin_from_disp,
//											'user_id' => $row['uid'],
//											'save' => '1',
//											'username' => $row['username'],
//											'imei' => $row['imei'],
//											'unlock_code' => base64_decode($row['reply']),
//											'order_id' => $row['oid'],
//											'tool_name' => $row['tool_name'],
//											'credits'=>	$row['credits'],
//											'site_admin' => $admin_from_disp,
//											'send_mail' => true
//										);
//							array_push($argsAll, $args);
//						}
//						
//						//$objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
//					}
//			}
        }
        if (isset($request['ERROR'])) {
//			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
//			$query = $mysql->query($sql);
//			$rows = $mysql->fetchArray($query);

            $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im
						set
						remarks=' . $mysql->quote($request['ERROR'][0]['MESSAGE']) . '
					where im.id=' . $mysql->getInt($args['order_id']);
            $mysql->query($sql);
            if ($mysql->rowCount($query) > 0) {
                //$objCredits = new credits();
                //$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
            }
            //echo "File Return : " . $args['order_id'] . PHP_EOL;
        }
    }

    public function dhrufusionapi_get_file_new($args) {
        //echo 'file api';	
        $status_flag = -1;

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
        $mysql = new mysql();
        //var_dump($args);exit;
        $para['ID'] = $args['extern_id'];

        $request = $api->action('getfileorder', $para);
        //    print_r($request);exit;
        if (defined("debug")) {
            error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruCheck.log");
            print_r($request);
        }

        if (isset($request['SUCCESS'])) {

            $sql1 = 'select tm.service_name,um.username,um.email,um.file_suc_noti,um.file_rej_noti,oim.credits,oim.id
					from ' . ORDER_FILE_SERVICE_MASTER . ' oim
					left join ' . USER_MASTER . ' as um
					on oim.user_id=um.id
				        left join ' . FILE_SERVICE_MASTER . ' as tm
					on oim.api_service_id=tm.id
					where 
					oim.id=' . $mysql->getInt($args['order_id']);
            //echo $sql1;exit;
            $query1 = $mysql->query($sql1);
            $rows1 = $mysql->fetchArray($query1);

            $tool_name = $rows1[0]['service_name'];
            $user_name = $rows1[0]['username'];
            $u_email = $rows1[0]['email'];
            $price = $rows1[0]['credits'];
            $o_imei = $rows1[0]['id'];
            $successmailyn = $rows1[0]['file_suc_noti'];
            $rejectmailyn = $rows1[0]['file_rej_noti'];
            $accept_flag = false;

            $code = $request['SUCCESS']['0']['CODE'];
            switch ($request['SUCCESS']['0']['STATUS']) {
                //get data from user_table,service_table
                //end // if unlock code is not available
                case '3':
                    $mysql = new mysql();
                    $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                    //  echo $sql;exit;
                    $query = $mysql->query($sql);
                    $rows = $mysql->fetchArray($query);

                    $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $mysql->quote('API') . ',
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($code) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                    //echo $sql;exit;
                    $mysql->query($sql);



                    //if($mysql->rowCount($query) > 0)
                    {
                        $objCredits = new credits();
                        $objCredits->returnFile($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                        //$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                    }
                    //echo "FILE ORDER Return : " . $args['order_id'] . PHP_EOL;		
                    $status_flag = 0;

                    //send maill code if unlock code unavailable

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
         <p><b>Reson:</b>' . $code . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $args['order_id'] . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

                    $objEmail->setTo($u_email);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Unlock Code Not Found");
                    $objEmail->setBody($simple_email_body);
                    //   $sent = $objEmail->sendMail();
                    if ($rejectmailyn == 1)
                        $objEmail->queue();
                    // SEND Simple Email
                    ////end
                    break;
                // if unlock code is available
                case '4':
                    $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($code) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $args['order_id'];

                    $mysql->query($sql);



                    //$objCredits = new credits();
                    //$objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                    //echo "IMEI Processed" . $args['order_id'] . PHP_EOL;
                    //$status_flag = 1;
                    //send maill code if unlock code available
                    $objEmail = new email();
                    $email_config = $objEmail->getEmailSettings();
                    $admin_email = $email_config['admin_email'];
                    $system_from = $email_config['system_email'];
                    $from_display = $email_config['system_from'];


                    $args2 = array(
                        'to' => $u_email,
                        'from' => $system_from,
                        'fromDisplay' => $from_display,
                        'user_id' => $user_id,
                        'save' => '1',
                        'username' => $username,
                        'order_id' => $args['order_id'],
                        'file_service' => $tool_name,
                        'codee' => $code,
                        'credits' => $price,
                        'site_admin' => CONFIG_SITE_NAME
                    );

                    //$objEmail->sendEmailTemplate('admin_user_imei_avail', $args);
                    if ($successmailyn == 1)
                        $objEmail->sendEmailTemplate('admin_user_order_file_update', $args2, TRUE);
                    //echo 'File Done';
                    break;
                default:
                    if (defined("debug")) {
                        print_r($request);
                    }
                    break;
            }
        }
        if (isset($request['ERROR'])) {
//			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
//			$query = $mysql->query($sql);
//			$rows = $mysql->fetchArray($query);

            $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im
						set
						remarks=' . $mysql->quote($request['ERROR'][0]['MESSAGE']) . '
					where im.id=' . $mysql->getInt($args['order_id']);
            $mysql->query($sql);
            if ($mysql->rowCount($query) > 0) {
                //$objCredits = new credits();
                //$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
            }
            //echo "File Return : " . $args['order_id'] . PHP_EOL;
        }
    }

    public function dhrufusionapi_send($args) {

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }


        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);

        $para['IMEI'] = $args['imei'];
        $para['ID'] = $args['service_id'];
        $para['MEP'] = $args['mep'];
        $para['MODELID'] = $args['model'];
        $para['PROVIDERID'] = $args['network'];
        $para['PIN'] = $args['mep'];
        $para['KBH'] = $args['kbh'];
        $para['PRD'] = $args['prd'];
        $para['TYPE'] = $args['mep'];
        $para['REFERENCE'] = $args['mep'];

        $request = $api->action('placeimeiorder', $para);
        echo '<HR />';
        //print_r($request);
        echo '<HR />';
        error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruR.log");

        if (isset($request['ERROR'])) {

            /*
              REPLY FROM SERVER
              Array
              (
              [ID] => 1382
              [IMEI] => 111111111111116
              [ERROR] => Array
              (
              [0] => Array
              (
              [MESSAGE] => ValidationError123139847
              [FULL_DESCRIPTION] => error: This IMEI is already available or pending in your Account
              )
              )
              [apiversion] => 2.0.0
              )
             */



            $mysql = new mysql();
            $msg = (isset($request['ERROR'][0]['MESSAGE'])) ? strip_tags($request['ERROR'][0]['MESSAGE']) : '';
            $msg .= (isset($request['ERROR'][0]['FULL_DESCRIPTION'][1])) ? strip_tags($request['ERROR'][0]['FULL_DESCRIPTION'][1]) : '';
            $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);

            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=3,
						reply_by=3,
						im.reply_date_time=now(),
						im.message = ' . $mysql->quote(base64_encode($msg)) . ',
						um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
					where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set
						im.remarks = ' . $mysql->quote($msg) . '
					where im.status=0 and im.id=' . $mysql->getInt($args['order_id']);
            $mysql->query($sql);
            /* if($mysql->rowCount($query) > 0)
              {
              $objCredits = new credits();
              $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
              } */
            return array('credits' => '-1', 'msg' => $msg);
        }

        return array('id' => $request['SUCCESS'][0]['REFERENCEID'], 'msg' => '');
    }

    public function dhrufusionapi_send_file_new($args) {

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        //   echo 'so far so good';

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);

        $para['IMEI'] = $args['oid'];
        $para['ID'] = $args['service_id'];
        $para['MOBILE'] = $args['mobile'];
        $para['MESSAGE'] = $args['message'];
        $para['REMARKS'] = $args['remarks'];
        $para['FILENAME'] = $args['fileask'];
        $para['FILEDATA'] = base64_encode($args['fpath']);
        //$para['KBH'] = $args['kbh'];
        //$para['PRD'] = $args['prd'];
        $para['TYPE'] = $args['ftype'];
        $para['SIZE'] = $args['fsize'];
        //$para['REFERENCE'] = $args['mep'];
        //   echo '<pre>';
        //var_dump($para);
        // exit;
        $request = $api->action('placefileorder', $para);
        echo '<HR />';
        //print_r($request);
        echo '<HR />';
        error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruR.log");

        if (isset($request['ERROR'])) {

            /*
              REPLY FROM SERVER
              Array
              (
              [ID] => 1382
              [IMEI] => 111111111111116
              [ERROR] => Array
              (
              [0] => Array
              (
              [MESSAGE] => ValidationError123139847
              [FULL_DESCRIPTION] => error: This IMEI is already available or pending in your Account
              )
              )
              [apiversion] => 2.0.0
              )
             */



            $mysql = new mysql();
            $msg = (isset($request['ERROR'][0]['MESSAGE'])) ? strip_tags($request['ERROR'][0]['MESSAGE']) : '';
            $msg .= (isset($request['ERROR'][0]['FULL_DESCRIPTION'][1])) ? strip_tags($request['ERROR'][0]['FULL_DESCRIPTION'][1]) : '';
//			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
//			$query = $mysql->query($sql);
//			$rows = $mysql->fetchArray($query);
//			
//			$sql = 'update 
//						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
//						set
//						im.status=3,
//						reply_by=3,
//						im.reply_date_time=now(),
//						im.message = ' . $mysql->quote($msg) . ',
//						um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
//					where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
            $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im
						set
						im.remarks = ' . $mysql->quote($msg) . '
					where im.status=0 and im.id=' . $mysql->getInt($args['oid']);
            $mysql->query($sql);
//			/*if($mysql->rowCount($query) > 0)
//			{
//				$objCredits = new credits();
//				$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
//			}*/
            return array('credits' => '-1', 'msg' => $msg);
        }

        return array('id' => $request['SUCCESS'][0]['REFERENCEID'], 'msg' => '');
    }

    public function dhrufusionapi_send_file($args) {

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        //   echo 'so far so good';

        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);

        $para['IMEI'] = $args['oid'];
        $para['ID'] = $args['service_id'];
        $para['MOBILE'] = $args['mobile'];
        $para['MESSAGE'] = $args['message'];
        $para['REMARKS'] = $args['remarks'];
        $para['FILEASK'] = $args['fileask'];
        $para['FILEPATH'] = $args['fpath'];
        //$para['KBH'] = $args['kbh'];
        //$para['PRD'] = $args['prd'];
        //$para['TYPE'] = $args['mep'];
        //$para['REFERENCE'] = $args['mep'];
        //var_dump($para);
        //exit;
        $request = $api->action('placefileorder', $para);
        echo '<HR />';
        //print_r($request);
        echo '<HR />';
        error_log(print_r($request, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhruR.log");

        if (isset($request['ERROR'])) {

            /*
              REPLY FROM SERVER
              Array
              (
              [ID] => 1382
              [IMEI] => 111111111111116
              [ERROR] => Array
              (
              [0] => Array
              (
              [MESSAGE] => ValidationError123139847
              [FULL_DESCRIPTION] => error: This IMEI is already available or pending in your Account
              )
              )
              [apiversion] => 2.0.0
              )
             */



            $mysql = new mysql();
            $msg = (isset($request['ERROR'][0]['MESSAGE'])) ? strip_tags($request['ERROR'][0]['MESSAGE']) : '';
            $msg .= (isset($request['ERROR'][0]['FULL_DESCRIPTION'][1])) ? strip_tags($request['ERROR'][0]['FULL_DESCRIPTION'][1]) : '';
//			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
//			$query = $mysql->query($sql);
//			$rows = $mysql->fetchArray($query);
//			
//			$sql = 'update 
//						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
//						set
//						im.status=3,
//						reply_by=3,
//						im.reply_date_time=now(),
//						im.message = ' . $mysql->quote($msg) . ',
//						um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
//					where im.status=0 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
            $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im
						set
						im.remarks = ' . $mysql->quote($msg) . '
					where im.status=0 and im.id=' . $mysql->getInt($args['oid']);
            $mysql->query($sql);
//			/*if($mysql->rowCount($query) > 0)
//			{
//				$objCredits = new credits();
//				$objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
//			}*/
            return array('credits' => '-1', 'msg' => $msg);
        }

        return array('id' => $request['SUCCESS'][0]['REFERENCEID'], 'msg' => '');
    }

    public function dhrufusionapi_sync_tools($args) {


        $mysql = new mysql();

        $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
        $mysql->query($sql);
        /*
          File Service
         * ********************************** */
        /* echo '<H2>Brands</H2>';
          $api = new api_dhrufusionapi();
          $request = $api->action('modellist');


          $sql = 'TRUNCATE TABLE ' . IMEI_BRAND_MASTER;
          $mysql->query($sql);
          $sql = 'TRUNCATE TABLE ' . IMEI_MODEL_MASTER;
          $mysql->query($sql);
          if(isset($request['SUCCESS']))
          {
          $groups = $request['SUCCESS'][0]['LIST'];
          if(is_array($groups))
          {
          foreach($groups as $group)
          {
          $brands = $group['MODELS'];
          $brandName = $group['BRAND'];

          $sql = 'insert into ' . IMEI_BRAND_MASTER . ' (id, brand, status)
          values(
          '. $group['ID'] . ',
          '. $mysql->quote($brandName) . ',
          1)';
          $mysql->query($sql);
          $brand_id = $group['ID'];

          if(is_array($brands))
          {
          foreach($brands as $model)
          {
          $sql = 'insert into ' . IMEI_MODEL_MASTER . ' (id, model, brand, status)
          values(
          '. $model['ID'] . ',
          '. $mysql->quote($model['NAME']) . ',
          '. $mysql->getInt($brand_id) . ',
          1)';
          $mysql->query($sql);
          echo 'Brand: ' . $model['NAME'] . '<br />';
          //ob_flush();
          }
          }
          }
          }
          } */

        /*
          IMEI
         * ********************************** */
        echo '<H2>IMEI Services</H2>';
        $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
        $request = $api->action('imeiservicelist');
        if (isset($request['ERROR'])) {

            return array('credits' => '-1', 'msg' => 'Can\'t sync. services! ' . $request['ERROR']);
        }

        $sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
        $mysql->query($sql);

        $groups = $request['SUCCESS'][0]['LIST'];
        if (is_array($groups)) {
            foreach ($groups as $group) {
                $tools = $group['SERVICES'];
                echo $groupName = '<b>--------' . $group['GROUPNAME'] . '--------</b><br>';
                ;
                foreach ($tools as $tool) {
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
								(api_id, group_name, service_id, service_name, credits, delivery_time, type, info) 
								VALUES (
									' . $args['id'] . ',
									' . $mysql->quote($group['GROUPNAME']) . ', 
									' . $tool['SERVICEID'] . ',
									' . $mysql->quote($tool['SERVICENAME']) . ', 
									' . $mysql->getFloat($tool['CREDIT']) . ',
									' . $mysql->quote(addslashes($tool['TIME'])) . ',
									1,
									' . $mysql->quote(addslashes($tool['INFO'])) . '
									)';
                    $mysql->query($sql);
                    echo $tool['SERVICENAME'] . ' (' . round($tool['CREDIT'], 2) . ') <br />';
                    //ob_flush();
                }
            }
        }

        if ($args['chk_services'] == 1) {
            /*
              File Service
             * ********************************** */
            echo '<H2>File Services</H2>';
            $api = new api_dhrufusionapi('JSON', $args['key'], $args['username'], $args['url']);
            $request = $api->action('fileservicelist');
            if (isset($request['ERROR'])) {
                $msg = isset($request['ERROR'][0]['MESSAGE']) ? $request['ERROR'][0]['MESSAGE'] : '';
                return array('credits' => '-1', 'msg' => 'Can\'t sync. services! ' . $msg);
            }



            $groups = $request['SUCCESS'][0]['LIST'];
            if (is_array($groups)) {
                foreach ($groups as $group) {
                    $tools = $group['SERVICES'];
                    $groupName = $group['GROUPNAME'];
                    if (is_array($tools)) {
                        foreach ($tools as $tool) {
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
									(api_id, group_name, service_id, service_name, credits, delivery_time, type) 
									VALUES (
										' . $args['id'] . ',
										' . $mysql->quote($groupName) . ', 
										' . $tool['SERVICEID'] . ',
										' . $mysql->quote($tool['SERVICENAME']) . ', 
										' . $tool['CREDIT'] . ',
										' . $mysql->quote($tool['TIME']) . ', 2)';
                            $mysql->query($sql);
                            echo 'File Service: ' . $tool['SERVICENAME'] . ' (' . $tool['CREDIT'] . ')<br />';
                            //ob_flush();
                        }
                    }
                }
            }
        }






        $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
        $mysql->query($sql);

        //print_r($resultArray);
        return true;
    }

    /*     * ***************************************
     * *****************************************
      BlockCheck.net
     * *****************************************
     * **************************************** */

    public function blockcheck_get($args) {
        $status_flag = -1;
        $objEmail = new email();
        $email_config = $objEmail->getEmailSettings();
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];

        // Put your API Access key here
        if (!defined("SHTC_API_ACCESS_KEY"))
            define('SHTC_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        if (!defined("SHTC_API_DEBUG"))
            define('SHTC_API_DEBUG', true);

        // Link to the web api
        if (!defined("SHTC_API_URL"))
            define('SHTC_API_URL', $args['url']);

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_blockcheck = new api_blockcheck();

        $api_blockcheck->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));

        $resultArray = $api_blockcheck->parse2Array($api_blockcheck->getResult());
        if (isset($resultArray['RESULT']['ERR'])) {
            echo $resultArray['RESULT']['ERR'];
            return;
        }
        $resultArray = $resultArray['RESULT']['IMEI1'];

        switch ($resultArray['STATUS']) {
            // if unlock code is available
            case '0':
                echo 'Pending';
                echo '<pre>' . print_r($resultArray, true) . '</pre>';
                break;
            // if unlock code is available
            case '1':
            case '2':
                $mysql = new mysql();
                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                $query = $mysql->query($sql);
                $rows = $mysql->fetchArray($query);

                $result = (($resultArray['STATUS'] == 1) ? 'Locked ' : 'Unlocked ') . '<br />';
                $result .= 'SN: ' . $resultArray['NETWORK'] . '<br />';
                $result .= 'Network: ' . $resultArray['NETWORK'] . '<br />';
                $result .= 'Model: ' . $resultArray['NETWORK'] . '<br />';

                $sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2,
							reply_by=3,
							reply=' . $mysql->quote(base64_encode($result)) . ',
							im.reply_date_time=now(),
							um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
                $mysql->query($sql);
                //if($mysql->rowCount($query) > 0)
                {
                    $objCredits = new credits();
                    $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                }
                //echo "IMEI Processed";
                break;

            // if imei is rejected
            case '3':
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
                //echo "IMEI Return";
                break;

            default:
                if (defined("debug")) {
                    print_r($resultArray);
                }
                break;
        }
    }

    public function blockcheck_send($args) {
        // Put your API Access key here
        define('IMCK_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        define('IMCK_API_DEBUG', false);

        // Link to the web api
        define('IMCK_API_URL', $args['url']);


        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_blockcheck = new api_blockcheck();

        $api_blockcheck->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei'], 'country_id' => $args['model']));
        $resultArray = $api_blockcheck->parse2Array($api_blockcheck->getResult());
        if (isset($resultArray['RESULT']['ERR'])) {
            //echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
            //echo '<h3>' . $result['ERR'] . '</h3>';
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
        }
        //print_r($resultArray);
        return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
    }

    /*     * ***************************************
     * *****************************************
      IMEICheck.net
     * *****************************************
     * **************************************** */

    public function imeicheck_get($args) {
        $objEmail = new email();
        $email_config = $objEmail->getEmailSettings();
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];

        // Put your API Access key here
        if (!defined("IMCK_API_ACCESS_KEY"))
            define('IMCK_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        if (!defined("IMCK_API_DEBUG"))
            define('IMCK_API_DEBUG', true);

        // Link to the web api
        if (!defined("IMCK_API_URL"))
            define('IMCK_API_URL', $args['url']);

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_imeicheck = new api_imeicheck();

        $api_imeicheck->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));

        $resultArray = $api_imeicheck->parse2Array($api_imeicheck->getResult());
        if (isset($resultArray['RESULT']['ERR'])) {
            echo $resultArray['RESULT']['ERR'];
            return;
        }
        $resultArray = $resultArray['RESULT']['IMEI1'];

        switch ($resultArray['STATUS']) {
            // if unlock code is available
            case '0':
                echo 'Pending';
                echo '<pre>' . print_r($resultArray, true) . '</pre>';
                break;
            // if unlock code is available
            case '1':
            case '2':
                $mysql = new mysql();
                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                $query = $mysql->query($sql);
                $rows = $mysql->fetchArray($query);

                $result = (($resultArray['STATUS'] == 1) ? 'Locked ' : 'Unlocked ') . '<br />';
                //$result .= 'SN: ' . $resultArray['NETWORK'] . '<br />';
                $result .= 'Network: ' . $resultArray['NETWORK'] . '<br />';
                //$result .= 'Model: ' . $resultArray['NETWORK'] . '<br />';

                $sql = 'update 
							' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2,
							reply_by=3,
							reply=' . $mysql->quote(base64_encode($result)) . ',
							im.reply_date_time=now(),
							um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
                $mysql->query($sql);
                //if($mysql->rowCount($query) > 0)
                {
                    $objCredits = new credits();
                    $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
                }
                //echo "IMEI Processed";
                break;

            // if imei is rejected
            case '3':
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
                //echo "IMEI Return";
                break;

            default:
                print_r($resultArray);
                break;
        }
    }

    public function imeicheck_send($args) {

        // Put your API Access key here
        if (!defined("IMCK_API_ACCESS_KEY"))
            define('IMCK_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        if (!defined("IMCK_API_DEBUG"))
            define('IMCK_API_DEBUG', true);

        // Link to the web api
        if (!defined("IMCK_API_URL"))
            define('IMCK_API_URL', $args['url']);

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_imeicheck = new api_imeicheck();

        $api_imeicheck->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei']));
        $resultArray = $api_imeicheck->parse2Array($api_imeicheck->getResult());
        if (isset($resultArray['RESULT']['ERR'])) {
            //echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
            //echo '<h3>' . $result['ERR'] . '</h3>';
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
        }
        //print_r($resultArray);
        return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
    }

    /*     * ***************************************
     * *****************************************
      Infinity Onlnie service
     * *****************************************
     * **************************************** */

    public function infinity_online_service_get($args) {

        //import infinity api
        require_once(CONFIG_PATH_EXTERNAL_ABSOLUTE . 'infinity/iosapi.php');


        $reply = $Api->SL3JobCheck($Imei);
        if ($reply['Code'] != '') {
            $mysql = new mysql();
            $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);

            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=2,
						reply_by=3,
						reply=' . $mysql->quote(base64_encode($reply['Code'])) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $args['order_id'];
            $mysql->query($sql);
            //if($mysql->rowCount($query) > 0)
            {
                $objCredits = new credits();
                $objCredits->processIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);
            }
            //echo "IMEI Processed";
            // break;
        } elseif ($reply['Result'] != '0') {
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
            //echo "IMEI Return";
        } else {
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $reply['Comment']);
        }
    }

    public function infinity_online_service_send($args) {

        //import infinity api
        require_once(CONFIG_PATH_EXTERNAL_ABSOLUTE . 'infinity/iosapi.php');

        //15 chars
        $Imei = $args['imei'];

        //40 chars (20 bytes hash as Hex string)
        $Hash = trim($args['custom_value']);

        $reply = $Api->SL3JobAdd($Imei, $Hash);
        if (isset($reply['jobId'])) {
            return array('id' => $reply['jobId'], 'msg' => '');
        } else {
            $comment = isset($reply['Comment']) ? $reply['Comment'] : '';
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.:' . $comment);
        }
    }

    /*     * ***************************************
     * *****************************************
      LGtool.net
     * *****************************************
     * **************************************** */

    public function lgtool_net_get($args) {
        // Put your API Access key here
        if (!defined("SHTC_API_ACCESS_KEY"))
            define('SHTC_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        if (!defined("SHTC_API_DEBUG"))
            define('SHTC_API_DEBUG', true);

        // Link to the web api
        if (!defined("SHTC_API_URL"))
            define('SHTC_API_URL', $args['url']);

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_super_htc = new api_super_htc();

        $api_super_htc->sendCommand('IMEI_STATUS', array('id' => $args['extern_id']));

        $resultArray = $api_super_htc->parse2Array($api_super_htc->getResult());
        if (isset($resultArray['RESULT']['ERR'])) {
            echo $resultArray['RESULT']['ERR'];
            return;
        }
        $resultArray = $resultArray['RESULT']['IMEI1'];

        switch ($resultArray['STATUS']) {
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
                //echo "IMEI Processed";
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
                //echo "IMEI Return";
                break;

            default:
                print_r($resultArray);
                break;
        }
    }

    public function lgtool_net_send($args) {

        // Put your API Access key here
        define('LGTN_API_ACCESS_KEY', $args['key']);

        // Change its value to true in case you want to debug the code
        define('LGTN_API_DEBUG', false);

        // Link to the web api
        define('LGTN_API_URL', $args['url']);

        // Check if cURL is installed or not
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        $api_lgtool_net = new api_lgtool_net();

        $api_lgtool_net->sendCommand('IMEI_SUBMIT', array('imei' => $args['imei']));
        $resultArray = $api_lgtool_net->parse2Array($api_lgtool_net->getResult());

        if (isset($resultArray['RESULT']['ERR'])) {
            //echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
            //echo '<h3>' . $result['ERR'] . '</h3>';
            return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . $resultArray['RESULT']['ERR']);
        }
        //print_r($resultArray);
        return array('id' => $resultArray['RESULT']['IMEI1']['ID'], 'msg' => '');
    }

}

?>