<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
//        echo "<pre/>";
//        var_dump($_POST);
//        exit;
$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('con_pre_log_edit_14856467312');

$id = $request->PostInt('id');
$prepaid_log_name = $request->PostStr('prepaid_log_name');
$group_id = $request->PostInt('group_id');
$status = $request->PostInt('status');
$email_notify = $request->PostCheck('e_notify');
$delivery_time = $request->PostStr('delivery_time');

$info = $request->PostStr('info');


if ($prepaid_log_name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_edit.html?group_id=" . $group_id . "&reply=" . urlencode('reply_prepaid_logs_missing'));
    exit();
}

$sql_chk = 'select * from ' . PREPAID_LOG_MASTER . ' where prepaid_log_name=' . $mysql->quote($prepaid_log_name) . ' and group_id=' . $group_id . ' and id!=' . $id;
$query_chk = $mysql->query($sql_chk);

if ($mysql->rowCount($query_chk) == 0) {
    $sql = 'update ' . PREPAID_LOG_MASTER . '
						set
						prepaid_log_name = ' . $mysql->quote($prepaid_log_name) . ',
                                                    delivery_time = ' . $mysql->quote($delivery_time) . ',
						group_id = ' . $mysql->getInt($group_id) . ',
						info = ' . $mysql->quote($info) . ',
                                                       is_send_noti = ' . $email_notify . ',
						status = ' . $mysql->getInt($status) . '
					where id=' . $mysql->getInt($id);
    $mysql->query($sql);


    /*     * **************************************************
      UPDATE AMOUNT
     * *************************************************** */
    if (isset($_POST['currency_id'])) {
        $currencies = $_POST['currency_id'];

        $sql = 'delete from ' . PREPAID_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
        $mysql->query($sql);
        // delete old notifications of that tool
        $sql = 'delete from nxt_price_notify where tool_id = ' . $id;
        $mysql->query($sql);

        foreach ($currencies as $key => $currency_id) {

            $amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
            $amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

            $sql = 'insert into ' . PREPAID_LOG_AMOUNT_DETAILS . '
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
						4,
						' . $amount . ',
						' . $a_user['id'] . ')';
                    $mysql->query($sql_a);
                }
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

    $sql = 'delete from ' . PREPAID_LOG_SPL_CREDITS . ' where log_id=' . $id;
    $mysql->query($sql);

    $sqlInsert = $sqlDel = '';
    foreach ($user_ids as $user_id) {
        $splCr = $_POST['spl_' . $user_id];

        if ($splCr != '' && !in_array($user_id, $check_user)) {

            $sqlInsert .= 'insert into ' . PREPAID_LOG_SPL_CREDITS . '
						(amount, log_id, user_id)
						values(' . $mysql->getfloat($splCr) . ', ' . $id . ', ' . $user_id . ');';
            //$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id=' . $user_id . ';';
              $sql = 'delete from nxt_price_notify where user = ' . $user_id.' and tool_id='.$id;
                         $mysql->query($sql);
                            
                            $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $id . ',
						4,
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
    /*     * ************************************************** */
    /*     * **************************************************
      VISIBLE TO USERS
     * *************************************************** */

    $prepaid_log_id = $request->PostInt('prepaid_log_id');
    $check_user1 = $_POST['check_user1'];



    if ($check_user1 != NULL) {
        $sql = 'delete from ' . PREPAID_LOG_USERS . ' where prepaid_log_id=' . $prepaid_log_id;
        $mysql->query($sql);


        foreach ($check_user1 as $user_id) {
            $sql = 'insert into ' . PREPAID_LOG_USERS . ' (prepaid_log_id, user_id) values(' . $prepaid_log_id . ', ' . $user_id . ')';
            $mysql->query($sql);
        }
    }

    /*     * ************************************************** */
    $prepaid = $prepaid_log_name;
    $args = array(
        'to' => CONFIG_EMAIL,
        'from' => CONFIG_EMAIL,
        'fromDisplay' => CONFIG_SITE_NAME,
        'prepaid_log' => $prepaid_log,
        'site_admin' => CONFIG_SITE_NAME
    );
    $objEmail->sendEmailTemplate('admin_edit_prepaid_log', $args);
    header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs.html?reply=" . urlencode('reply_group_update_success'));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_edit.html?id=" . $id . "&reply=" . urlencode('reply_prepaid_logs_duplicate'));
    exit();
}
?>