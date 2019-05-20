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

include_once("config.php");
include_once("functions.php");
include_once("paypal.class2.php");

$paypal = new MyPayPal();
if (_GET('token') != '' && _GET('PayerID') != '') {
    $httpParsedResponseAr = $paypal->DoExpressCheckoutPayment();
    //Check if everything went ok..
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

        $pay_pal_trid = urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

        if ('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {

            //  echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
            // add all to sql 
            $httpParsedResponseAr = $paypal->GetTransactionDetails();

            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

                $order_num = $httpParsedResponseAr['L_NUMBER0'];
                $amount = urldecode($httpParsedResponseAr["L_AMT0"]);
                //$order_num = substr($order_num, 3);
                $txn_id = $order_num;

                $sql = 'select amount,user_id from ' . INVOICE_REQUEST . ' where status=0 and id=' . $order_num;
                $resultamnt = $mysql->getResult($sql);
                $amouttobepaid = $resultamnt['RESULT'][0]['amount'];
                $user_id = $resultamnt['RESULT'][0]['user_id'];
                if ($user_id != "") {

                    // now update the data
                    $sql_user = 'select auto_pay,currency_id from ' . USER_MASTER . ' where id=' . $user_id;
                    $query_user = $mysql->query($sql_user);
                    $rows_user = $mysql->fetchArray($query_user);
                    $auto_play = $rows_user[0]['auto_pay'];
                    $currency_id = $rows_user[0]['currency_id'];
                    if ($auto_play == 1) {
                        $user_cr = $objCredits->getUserCredits($user_id);

                        $sql = 'update ' . INVOICE_REQUEST . '
							set
								
								status=1
							where id=' . $order_num;
                        $mysql->query($sql);

                        $sql = 'select * from ' . INVOICE_REQUEST . ' where id=' . $order_num;
                        $query = $mysql->query($sql);
                        if ($mysql->rowCount($query) > 0) {
                            $rows = $mysql->fetchArray($query);
                            $user_id = $rows[0]['user_id'];
                            $credits = $rows[0]['credits'];

                            $sql = 'update ' . USER_MASTER . '
									set credits=credits + ' . $mysql->getFloat($credits) . '
								where id=' . $mysql->getInt($user_id);
                            //error_log("^^" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                            $mysql->query($sql);


                            $ip = $_SERVER['REMOTE_ADDR'];
                            $sql = 'insert into ' . CREDIT_TRANSECTION_MASTER . '
							(user_id, user_id2, date_time, credits,
							credits_acc, credits_acc_process, credits_acc_used,
							credits_after, credits_after_process, credits_after_used,
							info, trans_type,ip)
							values(
								' . $mysql->getInt($user_id) . ',
								0,
								now(),
								' . $mysql->getFloat($credits) . ',

								' . $mysql->getFloat($user_cr['credits']) . ',
								' . $mysql->getFloat($user_cr['process']) . ',
								' . $mysql->getFloat($user_cr['used']) . ',
								' . $mysql->getFloat(($user_cr['credits'] + $credits)) . ',
								' . $mysql->getFloat(($user_cr['process'])) . ',
								' . $mysql->getInt($user_cr['used']) . ',
															
								' . $mysql->quote("Credits Added By PayPal Checkout") . ',
								6,
								' . $mysql->quote($ip) . '
							);';
                            //error_log("##" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                            $mysql->query($sql);
                            //$gateway_id = 7;
                            $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount,credits,gateway_id,currency_id, date_time,date_time_paid,paid_status,status) values(' . $mysql->quote($pay_pal_trid) . ',' . $user_id . ',' . $mysql->getFloat($amount) . ',' . $credits . ',8,' . $currency_id . ',now(),now(),1,0)';
                            //echo $sql_inv;
                            $mysql->query($sql_inv);
                            // error_log("##" . $sql_inv, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                            $last_id = $mysql->insert_id();
                            //entery into invoice log
                            $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values(' . $last_id . ', ' . $mysql->getFloat($credits) . ', ' . $credits . ', "Credits Added By RedSys", now(),' . $user_id . ',1,1)';
                            $query_inv = $mysql->query($inv_log);
                            // error_log("##" . $inv_log, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                        }

                        if ($query_inv) {
                            $msg = 'done';
                            $msg_type = 1;
                            header('location:' . CONFIG_PATH_SITE_USER . 'pay_pal_done.html?msg=' . $msg . '&msg_type=' . $msg_type . '');
                            exit();
                        }
                    } else {
                        $sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($pay_pal_trid) . '
							where id=' . $order_num;
                        // error_log("**" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                        $mysql->query($sql);
                        $msg = 'done';
                        $msg_type = 1;
                        header('location:' . CONFIG_PATH_SITE_USER . 'pay_pal_done.html?msg=' . $msg . '&msg_type=' . $msg_type . '');
                        exit();
                    }
                }
            } else {


                $msg = '<b>Get Transaction Details failed:</b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                $msg_type = 2;
                header('location:' . CONFIG_PATH_SITE_USER . 'pay_pal_done.html?msg=' . $msg . '&msg_type=' . $msg_type . '');
                exit();
            }
        } elseif ('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {

            $msg = 'Transaction Complete but payment may still be pending!<br>If that is the case You can manually authorize this payment in your Paypal Account';
            $msg_type = 2;
            header('location:' . CONFIG_PATH_SITE_USER . 'pay_pal_done.html?msg=' . $msg . '&msg_type=' . $msg_type . '');
            exit();
        }

        // $this->GetTransactionDetails();
    } else {

        $msg = '<b>Error : </b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        $msg_type = 2;
        header('location:' . CONFIG_PATH_SITE_USER . 'pay_pal_done.html?msg=' . $msg . '&msg_type=' . $msg_type . '');
        exit();
    }
}
