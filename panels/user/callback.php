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
/* * *****************************************************
  Start Bitcoin API
 * ***************************************************** */
//var_dump($_GET);
//exit;
$sql_gateway = 'select * from ' . GATEWAY_MASTER . ' where id =7';
$query_gateway = $mysql->query($sql_gateway);
if ($mysql->rowCount($query_gateway) > 0) {
    $secret = CONFIG_SALT;
    if ($_GET['secret'] != $secret && $_GET['invoice'] == "" && $_GET['value'] == "") {
        die('Stop doing that');
    } else {

//update DB
        $order_num = $_GET['invoice'];
        $txn_id=$order_num;
        $amount = $_GET['value']; //default value is in satoshis
        $confirmations = $_GET['confirmations']; //- The number of confirmations for this transaction
        $address = $_GET['address']; //The address that was generated to receive the payment
        $tr_hash = $_GET['transaction_hash'];  //The transaction hash for this transaction
        $amountCalc = $amount / 100000000; //optional convert to bitcoins
        //Check amount of the invoice
        $bitcoinamunt = round($amountCalc, 4);
        $sql = 'select bitamount,user_id from ' . INVOICE_REQUEST . ' where status=0 and id=' . $order_num;
        $resultamnt = $mysql->getResult($sql);
        $amouttobepaid = $resultamnt['RESULT'][0]['bitamount'];
        $user_id = $resultamnt['RESULT'][0]['user_id'];

        if ($amouttobepaid <= $bitcoinamunt) {
            // now update the data
            $sql_user = 'select auto_pay,currency_id from ' . USER_MASTER . ' where id=' . $user_id;
            $query_user = $mysql->query($sql_user);
            $rows_user = $mysql->fetchArray($query_user);
            $auto_play = $rows_user[0]['auto_pay'];
            $currency_id = $rows_user[0]['currency_id'];
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
															
								' . $mysql->quote("Credits Added Bitcoin") . ',
								7,
								' . $mysql->quote($ip) . '
							);';
                //error_log("##" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                $mysql->query($sql);
                //$gateway_id = 7;
                $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount, bitcoinamount,bitcoinconfirmations,bitcoinaddress,bitcoinhash,credits,gateway_id,currency_id, date_time,date_time_paid,paid_status,status) values(' . $mysql->quote($txn_id) . ',' . $user_id . ',' . $mysql->getFloat($credits) . ',' . $mysql->getFloat($amountCalc) . ',' . $confirmations . ',' . $mysql->quote($address) . ',' . $mysql->quote($tr_hash) . ',' . $credits . ',7,' . $currency_id . ',now(),now(),1,0)';
                //echo $sql_inv;
                $mysql->query($sql_inv);
                // error_log("##" . $sql_inv, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                $last_id = $mysql->insert_id();
                //entery into invoice log
                $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values(' . $last_id . ', ' . $mysql->getFloat($credits) . ', ' . $credits . ', "Credits Added Bitcoin", now(),' . $user_id . ',1,1)';
                $query_inv = $mysql->query($inv_log);
                // error_log("##" . $inv_log, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
            }

            if ($query_inv) {
                echo "*ok*"; // you must echo *ok* on the page or blockchain will keep sending callback requests every block up to 1,000 times!
            }
        } else {
            echo "Amount not ok";
            exit;
        }
    }
}