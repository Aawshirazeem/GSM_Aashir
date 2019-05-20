<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('con_services_file_edit_166h3412');

$id = $request->PostInt('id');
$service_name = $request->PostStr('service_name');
$group_id = $request->PostInt('group_id');
$delivery_time = $request->PostStr('delivery_time');
$reply_type = $request->PostInt('reply_type');
$verification = $request->PostInt('verification');
$status = $request->PostInt('status');
$email_notify = $request->PostCheck('e_notify');
$download_link = $request->PostStr('download_link');
$faq_id = $request->PostInt('faq_id');
$info = $request->PostStr('info');

$api_id = $request->PostInt('api_id');
$api_service_id = base64_decode($request->PostStr('api_service_id'));

if ($service_name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_edit.html?id=" . $id . "&reply=" . urlencode('reply_file_service_missing'));
    exit();
}

$sql_chk = 'select * from ' . FILE_SERVICE_MASTER . ' where service_name=' . $mysql->quote($service_name) . ' and id!=' . $mysql->getInt($id);
$query_chk = $mysql->query($sql_chk);

if ($mysql->rowCount($query_chk) == 0) {
    $sql = 'update ' . FILE_SERVICE_MASTER . '
						set
						service_name = ' . $mysql->quote($service_name) . ',
						api_id = ' . $mysql->getInt($api_id) . ',
						api_service_id = ' . $mysql->getInt($api_service_id) . ',
						delivery_time = ' . $mysql->quote($delivery_time) . ',
						reply_type = ' . $mysql->getInt($reply_type) . ',
						info = ' . $mysql->quote($info) . ',
						download_link = ' . $mysql->quote($download_link) . ',
						faq_id = ' . $mysql->getInt($faq_id) . ',
                                                    is_send_noti = ' . $email_notify . ',
						verification = ' . $mysql->getInt($verification) . ',
						status = ' . $mysql->getInt($status) . '
					where id=' . $mysql->getInt($id);
    $mysql->query($sql);





    /*     * **************************************************
      UPDATE AMOUNT
     * *************************************************** */
    if (isset($_POST['currency_id'])) {
        $currencies = $_POST['currency_id'];

        $sql = 'delete from ' . FILE_SERVICE_AMOUNT_DETAILS . ' where service_id = ' . $id;
        $mysql->query($sql);

        // delete old notifications of that tool
        $sql = 'delete from nxt_price_notify where tool_id = ' . $id;
        $mysql->query($sql);


        foreach ($currencies as $key => $currency_id) {

            $amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
            $amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));

            $sql = 'insert into ' . FILE_SERVICE_AMOUNT_DETAILS . '
						(service_id, currency_id, amount , amount_purchase)
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
						2,
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
    $service_id = $request->PostInt('service_id');
    $user_ids = $_POST['user_ids'];

    $check_user = (isset($_POST['check_user'])) ? $_POST['check_user'] : array();

    $sql = 'delete from ' . FILE_SPL_CREDITS . ' where service_id=' . $service_id;
    $mysql->query($sql);

    $sqlInsert = $sqlDel = '';
    foreach ($user_ids as $user_id) {
        $splCr = $request->PostFloat('spl_' . $user_id);
        if ($splCr != '' && !in_array($user_id, $check_user)) {
            $sqlInsert .= 'insert into ' . FILE_SPL_CREDITS . '
						(amount, service_id, user_id)
						values(' . $splCr . ', ' . $service_id . ', ' . $user_id . ');';

            //$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id=' . $user_id . ';';
            
             $sql = 'delete from nxt_price_notify where user = ' . $user_id.' and tool_id='.$service_id;
                         $mysql->query($sql);
                            
                            $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $service_id . ',
						2,
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
      Visible To Users
     * *************************************************** */

    $service_id = $request->PostInt('service_id');
    $check_user = $_POST['check_user1'];
    if ($check_user != NULL) {
        $sql = 'delete from ' . FILE_SERVICE_USERS . ' where service_id=' . $service_id;
        $mysql->query($sql);


        foreach ($check_user as $user_id) {
            $sql = 'insert into ' . FILE_SERVICE_USERS . ' (service_id, user_id) values(' . $service_id . ', ' . $user_id . ')';
            $mysql->query($sql);
        }
    }
    /*     * ************************************************** */

    $file_service = $service_name;
    $args = array(
        'to' => CONFIG_EMAIL,
        'from' => CONFIG_EMAIL,
        'fromDisplay' => CONFIG_SITE_NAME,
        'file_service' => $file_service,
        'site_admin' => CONFIG_SITE_NAME
    );
    $objEmail->sendEmailTemplate('admin_edit_file_service', $args);
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file.html?reply=" . urlencode('reply_update_success'));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_edit.html?id=" . $id . "&reply=" . urlencode("reply_service_file_duplicate"));
    exit();
}
?>