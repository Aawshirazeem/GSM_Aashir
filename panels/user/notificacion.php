<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
include_once "SermepaException.php";
include_once "SermepaInterface.php";
include_once "Sermepa.php";

use CommerceRedsys\Payment\Sermepa;

//file_put_contents("filename.txt", "");
$sql_gateway = 'select * from ' . GATEWAY_MASTER . ' where id =6';
$query_gateway = $mysql->query($sql_gateway);
if ($mysql->rowCount($query_gateway) > 0) {
    $rows_gateway = $mysql->fetchArray($query_gateway);
    $row_gateway = $rows_gateway[0];
    $fuc = $key = '';
    if (sizeof(explode(':', $row_gateway['gateway_id'])) == 4) {
        list($fuc, $key, $company, $user) = explode(':', $row_gateway['gateway_id']);
    }
    $settings = array(
        'name' => $user,
        'merchantCode' => $fuc,
        'merchantPassword' => $key,
        'terminal' => '001',
        'environment' => 'test',
    );
    try {
        // Create a new instance and initialize it.
        $gateway = new Sermepa($settings['name'], $settings['merchantCode'], $settings['terminal'], $settings['merchantPassword'], $settings['environment']);

        // Get response data.
        if (!$feedback = $gateway->getFeedback()) {
            // No feedback response.
            return;
        }

        $encoded_parameters = $feedback['Ds_MerchantParameters'];
        $decoded_parameters = $gateway->decodeMerchantParameters($encoded_parameters);

        $feedback_signature = $feedback['Ds_Signature'];
        $composed_signature = $gateway->composeMerchantSignatureFromFeedback($encoded_parameters);

        // Check if the signatures are valid.
        if ($feedback_signature != $composed_signature) {
            // Or...
            //if (!$gateway->areValidSignatures($feedback)) {
            echo "Signatures don't match";
            exit;
        }

        // Load the payment from ???? and store the necessary values.
        $payment_id = $decoded_parameters['Ds_MerchantData'];

        $response_code = (int) $decoded_parameters['Ds_Response'];
        if ($response_code <= 99) {
            // Transaction valid. Save your data here.
            $transaction_remote_id = $decoded_parameters['Ds_AuthorisationCode'];
            $transaction_message = $gateway->handleResponse($response_code);

            $order_num = $payment_id;
            $order_num = substr($order_num, 3);
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
															
								' . $mysql->quote("Credits Added By RedSys") . ',
								6,
								' . $mysql->quote($ip) . '
							);';
                        //error_log("##" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                        $mysql->query($sql);
                        //$gateway_id = 7;
                        $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount,credits,gateway_id,currency_id, date_time,date_time_paid,paid_status,status) values(' . $mysql->quote($txn_id) . ',' . $user_id . ',' . $mysql->getFloat($amount) . ',' . $credits . ',6,' . $currency_id . ',now(),now(),1,0)';
                        //echo $sql_inv;
                        $mysql->query($sql_inv);
                        // error_log("##" . $sql_inv, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                        $last_id = $mysql->insert_id();
                        //entery into invoice log
                        $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values(' . $last_id . ', ' . $mysql->getFloat($amount) . ', ' . $credits . ', "Credits Added By RedSys", now(),' . $user_id . ',1,1)';
                        $query_inv = $mysql->query($inv_log);
                        // error_log("##" . $inv_log, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                    }

                    if ($query_inv) {
                        echo "its done";
                        exit;
                    }
                } else {
                    $sql = 'update ' . INVOICE_REQUEST . '
							set
								txn_id=' . $mysql->quote($transaction_remote_id) . '
							where id=' . $order_num;
                    // error_log("**" . $sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log");
                    $mysql->query($sql);
                    exit;
                }
            }
        } else {
            // Transaction no valid. Save your data here.
            $transaction_message = $gateway->handleResponse($response_code);
            echo $transaction_message;
            exit;
        }
    } catch (SermepaException $e) {
        echo $e;
    }

    exit;
}    