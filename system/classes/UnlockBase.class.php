<?php

/*
  _/    _/            _/                      _/        _/_/_/
  _/    _/  _/_/_/    _/    _/_/      _/_/_/  _/  _/    _/    _/    _/_/_/    _/_/_/    _/_/
  _/    _/  _/    _/  _/  _/    _/  _/        _/_/      _/_/_/    _/    _/  _/_/      _/_/_/_/
  _/    _/  _/    _/  _/  _/    _/  _/        _/  _/    _/    _/  _/    _/      _/_/  _/
  _/_/    _/    _/  _/    _/_/      _/_/_/  _/    _/  _/_/_/      _/_/_/  _/_/_/      _/_/_/



  _|_|     _|_|_|     _|_|_|                    _|_|_|
  _|    _|   _|    _|     _|         _|      _|         _|
  _|_|_|_|   _|_|_|       _|         _|      _|     _|_|
  _|    _|   _|           _|           _|  _|           _|
  _|    _|   _|         _|_|_|           _|       _|_|_|



  This is a PHP 4/5 library for connecting the UnlockBase API (v3)

  It supposes that you have cURL installed.
  If it is not the case, please ask your system administrator.
  More information : http://www.php.net/curl

  If you need assistance, please contact tech@unlockbase.com
 */



/* Here comes the main class */

class UnlockBase {

    public function api_get_credits($args) {

        /* Enter your API key here */
        if (!defined("UNLOCKBASE_API_KEY"))
            define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');

        /* Set this value to true if something goes wrong and you want to display error messages */
        if (!defined("UNLOCKBASE_API_DEBUG"))
            define('UNLOCKBASE_API_DEBUG', true);

        /* This is the url of the api, don't change it */
        if (!defined("UNLOCKBASE_API_URL"))
            define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');

        if (!defined("UNLOCKBASE_VARIABLE_ERROR"))
            define('UNLOCKBASE_VARIABLE_ERROR', '_UnlockBaseError');
        if (!defined("UNLOCKBASE_VARIABLE_ARRAY"))
            define('UNLOCKBASE_VARIABLE_ARRAY', '_UnlockBaseArray');
        if (!defined("UNLOCKBASE_VARIABLE_POINTERS"))
            define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers');

        /* Check that cURL is installed */
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        /*         * **************************************************** */


        $XML = $this->CallAPI('AccountInfo');

        $credits = 0;
        if (is_string($XML)) {
            $Data = $this->ParseXML($XML);
            if (is_array($Data)) {
                if (isset($Data['Error'])) {
                    /* The API has returned an error */
                    return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . htmlspecialchars($Data['Error']));
                } else {
                    /* Everything works fine */
                    $credits = $Data['Credits'];
                }
            } else {
                return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not parse the XML stream.');
            }
        } else {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
        }
        return array('credits' => $credits, 'msg' => '');
    }

    public function api_get_code($args) {
        /* Enter your API key here */
        if (!defined("UNLOCKBASE_API_KEY"))
            define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');

        /* Set this value to true if something goes wrong and you want to display error messages */
        if (!defined("UNLOCKBASE_API_DEBUG"))
            define('UNLOCKBASE_API_DEBUG', true);

        /* This is the url of the api, don't change it */
        if (!defined("UNLOCKBASE_API_URL"))
            define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');

        if (!defined("UNLOCKBASE_VARIABLE_ERROR"))
            define('UNLOCKBASE_VARIABLE_ERROR', '_UnlockBaseError');
        if (!defined("UNLOCKBASE_VARIABLE_ARRAY"))
            define('UNLOCKBASE_VARIABLE_ARRAY', '_UnlockBaseArray');
        if (!defined("UNLOCKBASE_VARIABLE_POINTERS"))
            define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers');

        /* Check that cURL is installed */
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        /*         * **************************************************** */

        $XML = $this->CallAPI('GetOrders', array('ID' => $args['extern_id']));

        if (is_string($XML)) {
            $Data = $this->ParseXML($XML);
            if (is_array($Data)) {
                if (isset($Data['Error'])) {
                    /* The API has returned an error */
                    echo 'Can\'t fetch credits now! Contact site admin for more assistance. ' . htmlspecialchars($Data['Error']);
                    return;
                } else {
                    /* Everything works fine */
                    foreach ($Data['Order'] as $Order) {
                        $status = '';
                        switch ($Order['Status']) {
                            case 'Waiting':
                                //echo 'Still Waiting';
                                return;
                                break;
                            case 'Delivered':
                                switch ($Order['Available']) {
                                    case 'True':
                                        $status = 'Avail';
                                        break;
                                    case 'False':
                                        $status = 'Unavil';
                                        break;
                                    default:
                                        //echo 'Unexpected Error!';
                                        return;
                                        break;
                                }
                                break;
                            case 'Canceled':
                                $status = 'Unavil';
                                break;
                        }


                        switch ($status) {

                            // if unlock code is available
                            case 'Avail':
                                $mysql = new mysql();
                                $sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($args['order_id']);
                                $query = $mysql->query($sql);
                                $rows = $mysql->fetchArray($query);

                                $sql = 'update 
												' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
												set
												im.status=2,
												reply_by=3,
												reply=' . $mysql->quote(base64_encode($Order['Codes'])) . ',
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
                                ///////////////////////////////////Email sending code **************************************
                                $sql = 'select
									um.username, um.email,um.imei_suc_noti,um.imei_rej_noti,
									oim.id as oid, oim.user_id uid, oim.reply,
									oim.imei, itm.tool_name, oim.credits
								from ' . ORDER_IMEI_MASTER . ' oim 
								left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
								left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
							where oim.id in (' . $mysql->getInt($args['order_id']) . ')';

                                $query = $mysql->query($sql);
                                if ($mysql->rowCount($query) > 0) {
                                    $rows = $mysql->fetchArray($query);
                                    //  $argsAll = array();
                                    foreach ($rows as $row) {

                                        $imei_suc_noti = $row['imei_suc_noti'];

                                        //send maill code if unlock code available
                                        $objEmail = new email();
                                        $email_config = $objEmail->getEmailSettings();
                                        $admin_email = $email_config['admin_email'];
                                        $system_from = $email_config['system_email'];
                                        $from_display = $email_config['system_from'];


                                        $args = array(
                                            'to' => $row['email'],
                                            'from' => $system_from,
                                            'fromDisplay' => $from_display,
                                            'username' => $row['username'],
                                            'order_id' => $row['oid'],
                                            'imei' => $row['imei'],
                                            'tool_name' => $row['tool_name'],
                                            'credits' => $row['credits'],
                                            'unlock_code' => base64_decode($row['reply']),
                                            'send_mail' => true
                                        );

                                        if ($imei_suc_noti == 1)
                                            $objEmail->sendEmailTemplate('admin_user_imei_avail', $args);
                                    }
                                    //   $objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
                                }

                                ////.//////////////////////////////Email sending code*************************************
                                break;

                            // if imei is rejected
                            case 'Unavil':
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
                                                                                               
                                                                                                message=' . $mysql->quote(base64_encode("Unavailable")) . ',
												im.reply_date_time=now(),
												um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
											where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                    $mysql->query($sql);
                                    if ($mysql->rowCount($query) > 0) {
                                        $objCredits = new credits();
                                        $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);

                                        //	echo "IMEI Return";
                                        ///////////////////////////////////Email sending code **************************************
                                        $sql = 'select
											um.username, um.email,um.imei_rej_noti,
											oim.id as oid, oim.user_id uid, oim.reply,
											oim.imei, itm.tool_name, oim.credits
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id in (' . $mysql->getInt($args['order_id']) . ')';
                                        $query = $mysql->query($sql);

                                        if ($mysql->rowCount($query) > 0) {
                                            $rows = $mysql->fetchArray($query);

                                            foreach ($rows as $row) {

                                                //send maill code if unlock code unavailable
                                                ////.//////////////////////////////Email sending code*************************************
                                                $objEmail = new email();
                                                $email_config = $objEmail->getEmailSettings();
                                                $from_admin = $email_config['system_email'];
                                                $admin_from_disp = $email_config['system_from'];
                                                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                                $simple_email_body = '';
                                                //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                                $simple_email_body .= '
                                        <p><b>Service Name:</b>' . $row['tool_name'] . '<p>
                                        <p><b>IMEI:</b>' . $row['imei'] . '<p>
                                        <p><b>Reson:</b>Unavailable<p>
                                        <p><b>Credits:</b>' . $row['credits'] . '<p>
                                        <p><b>Order ID :</b>' . $row['oid'] . '<p>
                                        <p>
                                        <p>Complete Credits of This Order are Refunded & Added Back in Your User!
                                        ';
                                                $objEmail->setTo($row['email']);
                                                $objEmail->setFrom($from_admin);
                                                $objEmail->setFromDisplay($admin_from_disp);
                                                $objEmail->setSubject("Unlock Code Not Found");
                                                $objEmail->setBody($simple_email_body);
                                                // $sent = $objEmail->sendMail();
                                                if ($row['imei_rej_noti'] == 1)
                                                    $objEmail->queue();
                                            }
                                        }
                                    }
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
                                        } else {
                                            // reject orders like normal


                                            $sql = 'update 
												' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
												set
												im.status=3,
												reply_by=3,
                                                                                               
                                                                                                message=' . $mysql->quote(base64_encode("Unavailable")) . ',
												im.reply_date_time=now(),
												um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
											where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                            $mysql->query($sql);
                                            if ($mysql->rowCount($query) > 0) {
                                                $objCredits = new credits();
                                                $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);

                                                //	echo "IMEI Return";
                                                ///////////////////////////////////Email sending code **************************************
                                                $sql = 'select
											um.username, um.email,um.imei_rej_noti,
											oim.id as oid, oim.user_id uid, oim.reply,
											oim.imei, itm.tool_name, oim.credits
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id in (' . $mysql->getInt($args['order_id']) . ')';
                                                $query = $mysql->query($sql);

                                                if ($mysql->rowCount($query) > 0) {
                                                    $rows = $mysql->fetchArray($query);

                                                    foreach ($rows as $row) {

                                                        //send maill code if unlock code unavailable
                                                        ////.//////////////////////////////Email sending code*************************************
                                                        $objEmail = new email();
                                                        $email_config = $objEmail->getEmailSettings();
                                                        $from_admin = $email_config['system_email'];
                                                        $admin_from_disp = $email_config['system_from'];
                                                        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                                        $simple_email_body = '';
                                                        //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                                        $simple_email_body .= '
                                        <p><b>Service Name:</b>' . $row['tool_name'] . '<p>
                                        <p><b>IMEI:</b>' . $row['imei'] . '<p>
                                        <p><b>Reson:</b>Unavailable<p>
                                        <p><b>Credits:</b>' . $row['credits'] . '<p>
                                        <p><b>Order ID :</b>' . $row['oid'] . '<p>
                                        <p>
                                        <p>Complete Credits of This Order are Refunded & Added Back in Your User!
                                        ';
                                                        $objEmail->setTo($row['email']);
                                                        $objEmail->setFrom($from_admin);
                                                        $objEmail->setFromDisplay($admin_from_disp);
                                                        $objEmail->setSubject("Unlock Code Not Found");
                                                        $objEmail->setBody($simple_email_body);
                                                        // $sent = $objEmail->sendMail();
                                                        if ($row['imei_rej_noti'] == 1)
                                                            $objEmail->queue();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else {
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
								 message=' . $mysql->quote(base64_encode("Unavailable")) . ',
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
                                                                                               
                                                                                                message=' . $mysql->quote(base64_encode("Unavailable")) . ',
												im.reply_date_time=now(),
												um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
											where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($args['order_id']);
                                            $mysql->query($sql);
                                            if ($mysql->rowCount($query) > 0) {
                                                $objCredits = new credits();
                                                $objCredits->returnIMEI($mysql->getInt($args['order_id']), $rows[0]['user_id'], $rows[0]['credits']);

                                                //	echo "IMEI Return";
                                                ///////////////////////////////////Email sending code **************************************
                                                $sql = 'select
											um.username, um.email,um.imei_rej_noti,
											oim.id as oid, oim.user_id uid, oim.reply,
											oim.imei, itm.tool_name, oim.credits
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id in (' . $mysql->getInt($args['order_id']) . ')';
                                                $query = $mysql->query($sql);

                                                if ($mysql->rowCount($query) > 0) {
                                                    $rows = $mysql->fetchArray($query);

                                                    foreach ($rows as $row) {

                                                        //send maill code if unlock code unavailable
                                                        ////.//////////////////////////////Email sending code*************************************
                                                        $objEmail = new email();
                                                        $email_config = $objEmail->getEmailSettings();
                                                        $from_admin = $email_config['system_email'];
                                                        $admin_from_disp = $email_config['system_from'];
                                                        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                                        $simple_email_body = '';
                                                        //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
                                                        $simple_email_body .= '
                                        <p><b>Service Name:</b>' . $row['tool_name'] . '<p>
                                        <p><b>IMEI:</b>' . $row['imei'] . '<p>
                                        <p><b>Reson:</b>Unavailable<p>
                                        <p><b>Credits:</b>' . $row['credits'] . '<p>
                                        <p><b>Order ID :</b>' . $row['oid'] . '<p>
                                        <p>
                                        <p>Complete Credits of This Order are Refunded & Added Back in Your User!
                                        ';
                                                        $objEmail->setTo($row['email']);
                                                        $objEmail->setFrom($from_admin);
                                                        $objEmail->setFromDisplay($admin_from_disp);
                                                        $objEmail->setSubject("Unlock Code Not Found");
                                                        $objEmail->setBody($simple_email_body);
                                                        // $sent = $objEmail->sendMail();
                                                        if ($row['imei_rej_noti'] == 1)
                                                            $objEmail->queue();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                break;

                            default:
                                return;
                                break;
                        }
                    }
                    //End All Orders
                }
            } else {
                return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not parse the XML stream.');
            }
        } else {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
        }


        /*         * ************************************************************ */
    }

    public function api_place_order($args) {
        /* Enter your API key here */
        if (!defined("UNLOCKBASE_API_KEY"))
            define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');

        /* Set this value to true if something goes wrong and you want to display error messages */
        if (!defined("UNLOCKBASE_API_DEBUG"))
            define('UNLOCKBASE_API_DEBUG', true);

        /* This is the url of the api, don't change it */
        if (!defined("UNLOCKBASE_API_URL"))
            define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');

        if (!defined("UNLOCKBASE_VARIABLE_ERROR"))
            define('UNLOCKBASE_VARIABLE_ERROR', '_UnlockBaseError');
        if (!defined("UNLOCKBASE_VARIABLE_ARRAY"))
            define('UNLOCKBASE_VARIABLE_ARRAY', '_UnlockBaseArray');
        if (!defined("UNLOCKBASE_VARIABLE_POINTERS"))
            define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers');

        /* Check that cURL is installed */
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        /*         * **************************************************** */

        $XML = $this->CallAPI('PlaceOrder', array('Tool' => $args['service_id'], 'IMEI' => $args['imei']));

        if (is_string($XML)) {
            $Data = $this->ParseXML($XML);
            if (is_array($Data)) {
                if (isset($Data['Error'])) {
                    /* The API has returned an error */
                    return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.' . htmlspecialchars($Data['Error']));
                } else {
                    /* Everything works fine */
                    return array('id' => $Data['ID'], 'msg' => $Data['Success']);
                }
            } else {
                return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance. Could not parse the XML stream');
            }
        } else {
            return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
        }


        /*         * ************************************************************ */
    }

    public function api_sync_tools($args) {
        /* Enter your API key here */
        if (!defined("UNLOCKBASE_API_KEY"))
            define('UNLOCKBASE_API_KEY', '(' . $args['key'] . ')');

        /* Set this value to true if something goes wrong and you want to display error messages */
        if (!defined("UNLOCKBASE_API_DEBUG"))
            define('UNLOCKBASE_API_DEBUG', true);

        /* This is the url of the api, don't change it */
        if (!defined("UNLOCKBASE_API_URL"))
            define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');

        if (!defined("UNLOCKBASE_VARIABLE_ERROR"))
            define('UNLOCKBASE_VARIABLE_ERROR', '_UnlockBaseError');
        if (!defined("UNLOCKBASE_VARIABLE_ARRAY"))
            define('UNLOCKBASE_VARIABLE_ARRAY', '_UnlockBaseArray');
        if (!defined("UNLOCKBASE_VARIABLE_POINTERS"))
            define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers');

        /* Check that cURL is installed */
        if (!extension_loaded('curl')) {
            trigger_error('cURL extension not installed', E_USER_ERROR);
        }

        /*         * **************************************************** */

        $mysql = new mysql();

        /* Call the API */
        $XML = $this->CallAPI('GetTools');

        if (is_string($XML)) {
            /* Parse the XML stream */
            $Data = $this->ParseXML($XML);

            if (is_array($Data)) {
                if (isset($Data['Error'])) {
                    /* The API has returned an error */
                    return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. ' . htmlspecialchars($Data['Error']));
                } else {
                    $sql = 'update ' . API_MASTER . ' set sync_datetime=now() where id=' . $args['id'];
                    $mysql->query($sql);

                    $sql = 'delete from ' . API_DETAILS . ' where api_id=' . $args['id'];
                    $mysql->query($sql);

                    /* Everything works fine */
                    foreach ($Data['Group'] as $Group) {
                        $tempgname = $Group['Name'];
                        echo '<b>--------' . $tempgname . '--------</b><br>';
                        foreach ($Group['Tool'] as $Tool) {
                            $sql = '
										insert into ' . API_DETAILS . '
											(api_id, group_name, service_id, service_name, credits, type,delivery_time) 
											VALUES (
												' . $args['id'] . ',
                                                                                                ' . $mysql->quote($tempgname) . ', 
												' . $Tool['ID'] . ',
												' . $mysql->quote($Tool['Name']) . ', 
												' . $Tool['Credits'] . ',
                                                                                                    1,
												' . $mysql->quote('') . ')';
                            $mysql->query($sql);
                            echo 'Updateing Tool: ' . $Tool['Name'] . '(' . $Tool['Credits'] . ')<br />';
                            ob_flush();
                        }
                    }
                }
            } else {
                /* Parsing error */
                return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not parse the XML stream.');
            }
        } else {
            /* Communication error */
            return array('credits' => '-1', 'msg' => 'Can\'t fetch credits now! Contact site admin for more assistance. Could not communicate with the api.');
        }


        //print_r($resultArray);
        return true;
    }

    /*
      mixed UnlockBase::CallAPI (string $Action, array $Parameters)
      Call the UnlockBase API.
      Returns the xml stream sent by the UnlockBase server
      Or false if an error occurs
     */

    public function CallAPI($Action, $Parameters = array()) {
        if (is_string($Action)) {
            if (is_array($Parameters)) {
                /* Add the API Key and the Action to the parameters */
                $Parameters['Key'] = UNLOCKBASE_API_KEY;
                $Parameters['Action'] = $Action;

                /* Prepare the cURL session */
                $Ch = curl_init(UNLOCKBASE_API_URL);
                curl_setopt($Ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($Ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($Ch, CURLOPT_HEADER, false);
                curl_setopt($Ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($Ch, CURLOPT_ENCODING, '');
                curl_setopt($Ch, CURLOPT_POST, true);
                curl_setopt($Ch, CURLOPT_POSTFIELDS, $this->BuildQuery($Parameters));
                /* Perform the session */
                $Data = curl_exec($Ch);

                if (UNLOCKBASE_API_DEBUG && curl_errno($Ch) != CURLE_OK) {
                    /* If an error occurred, report it in debug mode */
                    trigger_error(curl_error($Ch), E_USER_WARNING);
                }

                /* Close the session */
                curl_close($Ch);

                /* Return the data, or false if an error occurred */
                return $Data;
            } else
                trigger_error('Parameters must be an array', E_USER_WARNING);
        } else
            trigger_error('Action must be a string', E_USER_WARNING);

        return false;
    }

    /*
      mixed UnlockBase::ParseXML (string $XML)
      Parse an XML stream from the UnlockBase API.
      Returns an associative array of the parsed XML string
      Or false if an error occurs
     */

    public function ParseXML($XML) {
        if (!is_string($XML)) {
            /* If the argument is not a string, report the error in debug mode & stop here */
            if (UNLOCKBASE_API_DEBUG)
                trigger_error('Invalid argument supplied for UnlockBase::ParseXML()', E_USER_WARNING);
            return false;
        }

        /* Globalize variables */
        global ${UNLOCKBASE_VARIABLE_ERROR};
        global ${UNLOCKBASE_VARIABLE_ARRAY};
        global ${UNLOCKBASE_VARIABLE_POINTERS};
        /* Initialize variables */
        ${UNLOCKBASE_VARIABLE_ERROR} = false;
        ${UNLOCKBASE_VARIABLE_ARRAY} = array();
        ${UNLOCKBASE_VARIABLE_POINTERS} = array();

        /* Configure the parser */
        $Parser = xml_parser_create('UTF-8');
        xml_set_element_handler($Parser, array('UnlockBase', 'XML_Start'), array('UnlockBase', 'XML_End'));
        xml_set_character_data_handler($Parser, array('UnlockBase', 'XML_CData'));
        xml_parser_set_option($Parser, XML_OPTION_CASE_FOLDING, 0);

        /* Start parsing, check the success of both parsing and analyzing */
        $Success = xml_parse($Parser, $XML, true) && !${UNLOCKBASE_VARIABLE_ERROR};

        /* Report errors in debug mode */
        if (UNLOCKBASE_API_DEBUG) {
            if (${UNLOCKBASE_VARIABLE_ERROR}) {
                /* The XML stream has not been recognized */
                trigger_error('Unrecognized XML format', E_USER_WARNING);
            } elseif (xml_get_error_code($Parser) != XML_ERROR_NONE) {
                /* A parser error occurred */
                trigger_error(xml_error_string(xml_get_error_code($Parser)), E_USER_WARNING);
            }
        }

        /* Free the parser */
        xml_parser_free($Parser);

        /* Get a reference to the result */
        $Array = & ${UNLOCKBASE_VARIABLE_ARRAY};

        /* Unset global variables */
        unset($GLOBALS[UNLOCKBASE_VARIABLE_ERROR]);
        unset($GLOBALS[UNLOCKBASE_VARIABLE_ARRAY]);
        unset($GLOBALS[UNLOCKBASE_VARIABLE_POINTERS]);

        /* Return the result */
        return ($Success ? $Array : false);
    }

    /*
      bool UnlockBase::CheckEmail (string $Email)
      Check the validity of an email address
      This function is *not* RFC 2822 compliant, but instead reflects today's email reality
      Returns true if the email address seems correct, false otherwise
     */

    public function CheckEmail($Email) {
        return (bool) preg_match('/^[0-9a-z_\\-\\.]+@([0-9a-z][0-9a-z\\-]*[0-9a-z]\\.)+[a-z]{2,}$/i', $Email);
    }

    /*
      bool UnlockBase::CheckIMEI (string $IMEI, bool $Checksum)
      Check a 15-digit IMEI serial number.
      You are free to verify the checksum, or not;
      Bad checksums are 99% likely to provide unavailable unlock codes (exceptions exist, however)
      Returns true if the IMEI seems correct, false otherwise
     */

    public function CheckIMEI($IMEI, $Checksum = true) {
        if (is_string($IMEI)) {
            if (ereg('^[0-9]{15}$', $IMEI)) {
                if (!$Checksum)
                    return true;

                for ($i = 0, $Sum = 0; $i < 14; $i++) {
                    $Tmp = $IMEI[$i] * ( ($i % 2) + 1 );
                    $Sum += ($Tmp % 10) + intval($Tmp / 10);
                }

                return ( ( ( 10 - ( $Sum % 10 ) ) % 10 ) == $IMEI[14] );
            }
        }

        return false;
    }

    /*
      bool UnlockBase::CheckProviderID (string $ProviderID)
      Verify an Alcatel Provider ID
      Returns true if the Provider ID seems correct, false otherwise
     */

    public function CheckProviderID($ProviderID) {
        return (is_string($ProviderID) && eregi('^[0-9a-z]{4,5}\\-[0-9a-z]{7}$', $ProviderID));
    }

    /*
      bool UnlockBase::CheckMEP_PRD (string $Type, string $String)
      Check a MEP/PRD number before submitting it to the API
      $Type is either 'MEP' or 'PRD'
      Returns true if the MEP/PRD seems correct, false otherwise
     */

    public function CheckMEP_PRD($Type, $String) {
        return ereg('^' . $Type . '\\-[0-9]{5}\\-[0-9]{3}$', $String);
    }

    /* Internal functions - do not care */

    function BuildQuery($Parameters) {
        if (function_exists('http_build_query')) {
            /* PHP 5 */
            return http_build_query($Parameters);
        } else {
            /* PHP 4 */
            $Data = array();
            foreach ($Parameters as $Name => $Value)
                array_push($Data, urlencode($Name) . '=' . urlencode($Value));
            return implode('&', $Data);
        }
    }

    function XML_Start($Parser, $Name, $Attributes) {
        /* Globalize variables */
        global ${UNLOCKBASE_VARIABLE_ERROR};
        global ${UNLOCKBASE_VARIABLE_ARRAY};
        global ${UNLOCKBASE_VARIABLE_POINTERS};

        /* Do nothing if an error occurred previously */
        if (${UNLOCKBASE_VARIABLE_ERROR})
            return;

        if (count(${UNLOCKBASE_VARIABLE_POINTERS}) == 0) {
            /* Root Element : create the first pointer to the array */
            ${UNLOCKBASE_VARIABLE_POINTERS}[] = & ${UNLOCKBASE_VARIABLE_ARRAY};
        } else {
            /* Get the latest pointer */
            $Pointer = & ${UNLOCKBASE_VARIABLE_POINTERS} [count(${UNLOCKBASE_VARIABLE_POINTERS}) - 1];

            if (is_null($Pointer)) {
                /* This is the first sub-tag with that name, create the new container array for it */
                $Pointer[] = array();

                /* Replace the latest pointer, point to the first item of the new container */
                ${UNLOCKBASE_VARIABLE_POINTERS}[count(${UNLOCKBASE_VARIABLE_POINTERS}) - 1] = & $Pointer[0];
                $Pointer = & $Pointer[0];
            } elseif (is_array($Pointer)) {
                if (isset($Pointer[$Name])) {
                    if (!is_array($Pointer[$Name])) {
                        /* Unrecognized XML stream */
                        ${UNLOCKBASE_VARIABLE_ERROR} = true;
                        return;
                    }

                    /* The tag is already known, add an item to the array and create a pointer to it */
                    $Pointer[$Name][] = array();
                    ${UNLOCKBASE_VARIABLE_POINTERS}[] = & $Pointer[$Name][count($Pointer[$Name]) - 1];
                    return;
                }
            } else {
                /* Unrecognized XML stream */
                ${UNLOCKBASE_VARIABLE_ERROR} = true;
                return;
            }

            /* Set the default value and create a pointer to it */
            $Pointer[$Name] = NULL;
            ${UNLOCKBASE_VARIABLE_POINTERS}[] = & $Pointer[$Name];
        }
    }

    function XML_End($Parser, $Name) {
        /* Globalize variables */
        global ${UNLOCKBASE_VARIABLE_ERROR};
        global ${UNLOCKBASE_VARIABLE_POINTERS};

        /* Do nothing if an error occurred previously */
        if (${UNLOCKBASE_VARIABLE_ERROR})
            return;

        /* Remove the latest pointer */
        array_pop(${UNLOCKBASE_VARIABLE_POINTERS});
    }

    function XML_CData($Parser, $Data) {
        /* Ignore whitespaces */
        if (rtrim($Data) == '')
            return;

        /* Globalize variables */
        global ${UNLOCKBASE_VARIABLE_ERROR};
        global ${UNLOCKBASE_VARIABLE_POINTERS};

        /* Do nothing if an error occurred previously */
        if (${UNLOCKBASE_VARIABLE_ERROR})
            return;

        /* Get the latest pointer */
        $Pointer = & ${UNLOCKBASE_VARIABLE_POINTERS} [count(${UNLOCKBASE_VARIABLE_POINTERS}) - 1];

        if (is_array($Pointer)) {
            /* Unrecognized XML stream, should be null or string here */
            ${UNLOCKBASE_VARIABLE_ERROR} = true;
            return;
        }

        /* Append the character data */
        $Pointer .= $Data;
    }

}

?>