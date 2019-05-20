<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('con_pre_log_edit_14553737312');
//echo '<pre>';
$f_count = count($_POST['f_name']);
//var_dump($_POST);
//echo $f_count;
//exit;

$id = $request->PostInt('id');
$server_log_name = $request->PostStr('server_log_name');
$group_id = $request->PostInt('group_id');
$delivery_time = $request->PostStr('delivery_time');
$verification = $request->PostInt('verification');
$status = $request->PostInt('status');
$email_notify = $request->PostCheck('e_notify');
$chimera = $request->PostInt('chimera');
$user_id = $request->PostStr('user_id');
$chimera_api_key = $request->PostStr('api_key');
//  echo $user_id;
//  echo $api_key;
//  exit;

$custom_field_name = $request->PostStr('custom_field_name');
$custom_field_message = $request->PostStr('custom_field_message');
$custom_field_value = $request->PostStr('custom_field_value');

$api_id = $request->PostInt('api_id');
$api_service_id = $request->PostInt('api_service_id');

$download_link = $request->PostStr('download_link');
$faq_id = $request->PostInt('faq_id');
$info = $request->PostStr('info');


if ($server_log_name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_edit.html?id=" . $mysql->getInt($id) . "&reply=" . urlencode('reply_server_logs_missing'));
    exit();
}

$sql_chk = 'select * from ' . SERVER_LOG_MASTER . ' where server_log_name=' . $mysql->quote($server_log_name) . ' and group_id=' . $mysql->getInt($group_id) . ' and id!=' . $mysql->getInt($id);
$query_chk = $mysql->query($sql_chk);

if ($mysql->rowCount($query_chk) == 0) {
    $sql = 'update ' . SERVER_LOG_MASTER . '
				set
				server_log_name = ' . $mysql->quote($server_log_name) . ',
				delivery_time = ' . $mysql->quote($delivery_time) . ',
				group_id = ' . $mysql->getInt($group_id) . ',
				api_id = ' . $mysql->getInt($api_id) . ',
				api_service_id = ' . $mysql->getInt($api_service_id) . ',
				custom_field_name = ' . $mysql->quote($custom_field_name) . ',
				custom_field_message = ' . $mysql->quote($custom_field_message) . ',
				custom_field_value = ' . $mysql->quote($custom_field_value) . ',
				info = ' . $mysql->quote($info) . ',
                                     is_send_noti = ' . $email_notify . ',

				download_link = ' . $mysql->quote($download_link) . ',
				faq_id = ' . $mysql->getInt($faq_id) . ',
				verification = ' . $mysql->getInt($verification) . ',
				status = ' . $mysql->getInt($status) . ',chimera = ' . $mysql->getInt($chimera) . ',chimera_user_id = ' . $mysql->quote($user_id) . ',chimera_api_key = "' . $chimera_api_key . '"  where id=' . $mysql->getInt($id);
    //echo $sql;exit;
    $mysql->query($sql);


    /*     * **************************************************
      UPDATE AMOUNT
     * *************************************************** */
    if (isset($_POST['currency_id'])) {
        $currencies = $_POST['currency_id'];

        $sql = 'delete from ' . SERVER_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
        $mysql->query($sql);
        // delete old notifications of that tool
        $sql = 'delete from nxt_price_notify where tool_id = ' . $id;
        $mysql->query($sql);
        foreach ($currencies as $key => $currency_id) {

            $amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
            $amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

            $sql = 'insert into ' . SERVER_LOG_AMOUNT_DETAILS . '
						(log_id, currency_id, amount , amount_purchase)
						values(
						' . $id . ',
						' . $mysql->getInt($currency_id) . ',
						' . $amount . ',
						' . $amount_purchase . ')';
            $mysql->query($sql);


            // add all user price notification
            // get all the users
            $sql_all_users = 'select a.id,a.currency_id from ' . USER_MASTER . ' a';
            $all_users = $mysql->getResult($sql_all_users);
            foreach ($all_users['RESULT'] as $a_user) {

                if ($a_user['currency_id'] == $currency_id) {
                    // add notification
                    $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $id . ',
						3,
						' . $amount . ',
						' . $a_user['id'] . ')';
                    $mysql->query($sql_a);
                }
            }
        }
    }
    /*     * ************************************************** */

    /*     * **************************************************
      UPDATE CUSTOME FIELDS
     * *************************************************** */
    if ($f_count > 0) {
//			$currencies = $_POST['currency_id'];
        //$sql = 'delete from ' . SERVER_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
        // delete old custome fields
        $sql = 'delete from nxt_custom_fields  where s_type=2 and s_id=' . $id;
        $mysql->query($sql);

        for ($f = 0; $f < $f_count; $f++) {

            if ($_POST['f_name'][$f] != "") {


                $sql2 = 'update ' . SERVER_LOG_MASTER . ' set is_custom=1 where id=' . $id;
                ;
                $mysql->query($sql2);
                //$amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
                //$amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));
                if (!isset($_POST['f_opt'][$f]))
                    $opt = "";
                else {
                    $opt = $_POST['f_opt'][$f];
                }
                $sql = 'insert into nxt_custom_fields
						(s_type,s_id,f_type,f_name,f_desc,f_opt,f_valid,f_req)
						values(
                                                2,
						' . $id . ',
						' . $_POST['f_type'][$f] . ',
                                                ' . $mysql->quote($_POST['f_name'][$f]) . ',
                                                ' . $mysql->quote($_POST['f_desc'][$f]) . ',
                                                ' . $mysql->quote($opt) . ',
                                                ' . $mysql->quote($_POST['f_valid'][$f]) . ',
						' . $_POST['f_req2'][$f] . ')';

                // echo $sql;exit;
                $mysql->query($sql);
            } else {
                $sql3 = 'update ' . SERVER_LOG_MASTER . ' set is_custom=0 where id=' . $id;
                ;
                $mysql->query($sql3);
            }
        }
    }
    /*     * ************************************************** */
    /*     * **************************************************
      SPECIAL CREDITS
     * *************************************************** */
    $id = $request->PostInt('id');
    $user_ids = $_POST['user_ids'];

    $check_user = (isset($_POST['check_user'])) ? $_POST['check_user'] : array();

    $sql = 'delete from ' . SERVER_LOG_SPL_CREDITS . ' where log_id=' . $id;

    $mysql->query($sql);

    $sqlInsert = $sqlDel = '';
    foreach ($user_ids as $user_id) {
        $splCr = $_POST['spl_' . $user_id];

        if ($splCr != '' && !in_array($user_id, $check_user)) {
            $sqlInsert .= 'insert into ' . SERVER_LOG_SPL_CREDITS . '
						(amount, log_id, user_id)
						values(' . $mysql->getfloat($splCr) . ', ' . $id . ', ' . $user_id . ');';
            //$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id=' . $user_id . ';';
            
               $sql = 'delete from nxt_price_notify where user = ' . $user_id.' and tool_id='.$id;
                         $mysql->query($sql);
                            
                            $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $id . ',
						3,
						' . $mysql->getFloat($splCr) . ',
						' . $user_id . ')';
                    $mysql->query($sql_a);
        }
    }

    if ($sqlInsert != '') {
        $mysql->multi_query($sqlInsert);
    }
    if ($sqlDel != '') {
        $mysql->multi_query($sqlDel);
    }
    /**     * ************************************************* */
    /*     * **************************************************
      VISIBLE TO USER
     * *************************************************** */
    $log_id = $request->PostInt('log_id');
    $check_user = $_POST['check_user1'];

    if ($check_user != NULL) {
        $sql = 'delete from ' . SERVER_LOG_USERS . ' where log_id=' . $log_id;
        $mysql->query($sql);


        foreach ($check_user as $user_id) {
            $sql = 'insert into ' . SERVER_LOG_USERS . ' (log_id, user_id) values(' . $log_id . ', ' . $user_id . ')';
            $mysql->query($sql);
        }
    }
    /**     * ************************************************* */
    $args = array(
        'to' => CONFIG_EMAIL,
        'from' => CONFIG_EMAIL,
        'fromDisplay' => CONFIG_SITE_NAME,
        'server_log_name' => $server_log_name,
        'credits' => $credits,
        'site_admin' => CONFIG_SITE_NAME
    );
    $objEmail->sendEmailTemplate('admin_edit_server_log', $args);

    header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs.html?reply=" . urlencode('reply_update_success'));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_edit.html?id=" . $id . "&reply=" . urlencode('reply_server_logs_duplicate'));
    exit();
}
?>