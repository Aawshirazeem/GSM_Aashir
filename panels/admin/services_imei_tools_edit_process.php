<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

//echo '<pre>';
//var_dump($_POST);exit;
$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('services_imei_model_edit_5434553hh2');
$id = $request->PostInt('id');
if (!isset($_POST['group_id'])) {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_edit.html?id='.$id.'&reply=' . urlencode('repl_service_group_not_selected'));
    exit();
}
$f_count = count($_POST['f_name']);
$one = count($_POST['one']);

$tool_name = $request->PostStr('tool_name');
$succ_time = $request->PostStr('succ_time');
if($succ_time=="")
    $succ_time=0;
$veri_days = $request->PostStr('veri_days');
if($veri_days=="")
    $veri_days=0;
$tool_alias = $request->PostStr('tool_alias');
//$group_id = $request->PostInt('group_id');
$delivery_time = $request->PostStr('delivery_time');
$api_c_limit = $request->PostStr('api_c_limit');
$verification = $request->PostInt('verification');
$cancel = $request->PostInt('cancel');
$accept_duplicate = $request->PostInt('accept_duplicate');
$check_duplicate = $request->PostInt('check_duplicate');
$verify_checksum = $request->PostInt('verify_checksum');
$visible = $request->PostCheck('visible');
$email_notify = $request->PostCheck('e_notify');
$price_notify = $request->PostCheck('p_notify');
$status = $request->PostCheck('status');
$api_auth = $request->PostCheck('api_auth');
$api_rej=$request->PostCheck('api_rej');
$api_rej_custom_act = $request->PostInt('api_rej_custom_act');
$auto_success = $request->PostCheck('auto_success');
$api_r_sync = $request->PostCheck('api_r_sync');
$brand_id = $request->PostInt('brand_id');
$country_id = $request->PostInt('country_id');
$mep_group_id = $request->PostInt('mep_group_id');
$field_pin = $request->PostInt('field_pin');
$field_kbh = $request->PostInt('field_kbh');
$field_prd = $request->PostInt('field_prd');
$field_type = $request->PostInt('field_type');
$imei_type = $request->PostInt('imei_type');
$imei_field_name = $request->PostStr('imei_field_name');
$imei_field_info = $request->PostStr('imei_field_info');
$imei_field_length = $request->PostStr('imei_field_length');
$imei_field_alpha = $request->PostInt('imei_field_alpha');
$custom_field_name = $request->PostStr('custom_field_name');
$custom_field_message = $request->PostStr('custom_field_message');
$custom_field_value = $request->PostStr('custom_field_value');

$custom_required = $request->PostStr('custom_required');
$custom_required = ($custom_required == '' ? 0 : $custom_required);
$custom_range = $request->PostStr('custom_range');

$custom_range = preg_split('[-]', $custom_range);
if (sizeof($custom_range) > 1) {
    //print_r($custom_range);
    $custom_range = $custom_range[0] . '-' . $custom_range[1];
} else {
    $custom_range = $custom_range[0];
}


$api_id = $request->PostInt('api_id');
$api_service_id = $request->PostStr('api_service_id');
if($api_service_id=="")
    $api_id=0;

$download_link = $request->PostStr('download_link');
$faq_id = $request->PostInt('faq_id');
// $info = $request->PostStr('info');
$info = $_POST['info'];


if ($tool_name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_add.html?reply=" . urlencode("reply_service_imei_tools_missing"));
    exit();
}

$sql_chk = 'select * from ' . IMEI_TOOL_MASTER . ' where tool_name=' . $mysql->quote($tool_name) . ' and id!=' . $mysql->getInt($id);
$query_chk = $mysql->query($sql_chk);

if ($mysql->rowCount($query_chk) == 0) {
    $sql = 'update ' . IMEI_TOOL_MASTER . '
				set
				tool_name = ' . $mysql->quote($tool_name) . ',
				tool_alias = ' . $mysql->quote($tool_alias) . ',
				delivery_time = ' . $mysql->quote($delivery_time) . ',
                                    api_rate_sync = ' . $mysql->getFloat($api_c_limit) . ',
				
				api_id = ' . $mysql->getInt($api_id) . ',
				api_service_id = ' . $mysql->getInt($api_service_id) . ',
				brand_id = ' . $mysql->getInt($brand_id) . ',
				country_id = ' . $mysql->getInt($country_id) . ',
				custom_field_name = ' . $mysql->quote($custom_field_name) . ',
				custom_field_message = ' . $mysql->quote($custom_field_message) . ',
				custom_field_value = ' . $mysql->quote($custom_field_value) . ',
				info = ' . $mysql->quote($info) . ',
				download_link = ' . $mysql->quote($download_link) . ',
				faq_id = ' . $mysql->getInt($faq_id) . ',
				mep_group_id = ' . $mysql->getInt($mep_group_id) . ',
				field_pin = ' . $mysql->getInt($field_pin) . ',
                                succ_time = ' . $mysql->getInt($succ_time) . ',
                                     veri_days = ' . $mysql->getInt($veri_days) . ',
				field_kbh = ' . $mysql->getInt($field_kbh) . ',
				field_prd = ' . $mysql->getInt($field_prd) . ',
				field_type = ' . $mysql->getInt($field_type) . ',
				imei_type = ' . $imei_type . ',
				imei_field_name = ' . $mysql->quote($imei_field_name) . ',
				imei_field_info = ' . $mysql->quote($imei_field_info) . ',
				imei_field_length = "' . $imei_field_length . '",
				imei_field_alpha = ' . $imei_field_alpha . ',
				verification = ' . $mysql->getInt($verification) . ',
				cancel = ' . $mysql->getInt($cancel) . ',
				accept_duplicate = ' . $mysql->getInt($accept_duplicate) . ',
                                    duplicate_yn = ' . $mysql->getInt($check_duplicate) . ',
				verify_checksum = ' . $mysql->getInt($verify_checksum) . ',
				is_send_noti = ' . $email_notify . ',
                                    price_update = ' . $price_notify . ',
                                    visible = ' . $visible . ',
				status = ' . $status . ',
                                    api_auth = ' . $api_auth . ',
                                           api_rej = ' . $api_rej . ',
                                                 api_rej_man_auto = ' . $api_rej_custom_act . ',
                                    is_check_rate = ' . $api_r_sync . ',
                                    auto_success = ' . $auto_success . ',
				custom_required = ' . $custom_required . ',
				custom_range    = "' . $custom_range . '"  
				where id=' . $mysql->getInt($id);

    $mysql->query($sql);



    /*     * **************************************************
      UPDATE AMOUNT
     * *************************************************** */
    if (isset($_POST['currency_id'])) {
        $currencies = $_POST['currency_id'];

        $sql = 'delete from ' . IMEI_TOOL_AMOUNT_DETAILS . ' where tool_id = ' . $id;
        $mysql->query($sql);

        // delete old notifications of that tool
        // $sql = 'delete from nxt_price_notify where tool_id = ' . $id;
        //  $mysql->query($sql);

        foreach ($currencies as $key => $currency_id) {

            $amount = $request->PostFloat('amount_' . $currency_id);
            $amount_purchase = $request->PostFloat('amount_purchase_' . $currency_id);
            $amount = round($amount, 2);
            $amount_purchase = round($amount_purchase, 2);

            $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . '
						(tool_id, currency_id, amount , amount_purchase)
						values(
						' . $id . ',
						' . $currency_id . ',
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
//                    $sql_a = 'insert into nxt_price_notify
//						(tool_id,type, price,user)
//						values(
//						' . $id . ',
//						1,
//						' . $amount . ',
//						' . $a_user['id'] . ')';


                    $sql_del = 'delete from nxt_price_notify  WHERE tool_id = ' . $id . ' and price!=' . $amount . ' and user=' . $a_user['id'];
                    //echo $sql_del.'<br>';
                    $mysql->query($sql_del);

                    $sql_a = 'INSERT INTO nxt_price_notify (tool_id,type, price,user)
SELECT * FROM (SELECT ' . $id . ' as tooll,1 as type,' . $amount . ' as amountt,' . $a_user['id'] . ' as userr) AS tmp
WHERE NOT EXISTS (
    SELECT tool_id FROM nxt_price_notify b WHERE b.tool_id = ' . $id . ' and b.price=' . $amount . ' and b.user=' . $a_user['id'] . '
) LIMIT 1;';

                    $mysql->query($sql_a);
                }
            }
        }
    }
    /*     * ************************************************** */
//exit;


    /*     * **************************************************
      UPDATE SPECIAL GROUPS PRICE
     * *************************************************** */

    $sql_packg = 'select * from ' . PACKAGE_MASTER . ' where status=1 order by id';
    $packgs = $mysql->getResult($sql_packg);


    $sql_curr = 'select * from ' . CURRENCY_MASTER . ' where status=1 order by is_default DESC';
    $currenciessg = $mysql->getResult($sql_curr);
    $check = 0;
    foreach ($packgs['RESULT'] as $packg) {

        $pkg_id = $packg['id'];
        $tool_id = $id;
        // echo $pkg_id.$tool_id;exit;

        $sql_1 = 'delete from  ' . PACKAGE_IMEI_DETAILS . ' where package_id=' . $pkg_id . ' and tool_id=' . $tool_id;
        $mysql->query($sql_1);


        foreach ($currenciessg['RESULT'] as $currency) {
            $spl_credit = $request->PostFloat('amountsg_' . $pkg_id . '_' . $currency['id']);

            if ($spl_credit != "" && $spl_credit != 0) {
                $sql = 'insert into ' . PACKAGE_IMEI_DETAILS . '
			(package_id,currency_id,tool_id,amount)
			values';



                $sql.='(
						' . $pkg_id . ',
						' . $currency['id'] . ', 
						' . $tool_id . ', 
						' . $spl_credit . ')';

                $mysql->query($sql);



                $sql_all_users = 'select a.user_id,b.currency_id from nxt_package_users a
inner join nxt_user_master b
on a.user_id=b.id
where a.package_id=' . $pkg_id;
                $all_users = $mysql->getResult($sql_all_users);
                foreach ($all_users['RESULT'] as $a_user) {

                    if ($currency['id'] == $a_user['currency_id']) {
                       // $sql12 = 'delete from nxt_price_notify where user = ' . $a_user['user_id'] . ' and tool_id=' . $tool_id;
                       // $mysql->query($sql12);

                        $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $tool_id . ',
						1,
						' . $spl_credit . ',
						' . $a_user['user_id'] . ')';
                       // $mysql->query($sql_a);
                        
                        
                           $sql_del = 'delete from nxt_price_notify  WHERE tool_id = ' . $tool_id . ' and price!=' . $spl_credit . ' and user=' . $a_user['user_id'];
                            $mysql->query($sql_del);

                    $sql_a = 'INSERT INTO nxt_price_notify (tool_id,type, price,user)
SELECT * FROM (SELECT ' . $tool_id . ' as tooll,1 as type,' . $spl_credit . ' as amountt,' . $a_user['user_id'] . ' as userr) AS tmp
WHERE NOT EXISTS (
    SELECT tool_id FROM nxt_price_notify b WHERE b.tool_id = ' . $tool_id . ' and b.price=' . $spl_credit . ' and b.user=' . $a_user['user_id'] . '
) LIMIT 1;';

                    $mysql->query($sql_a);
                              
                    }
                }
            }
        }
    }

 

    /*     * ************************************************** */

    
     /*     * **************************************************
      UPDATE API PRIORITY FIELDS
     * *************************************************** */
    if ($one > 0) {
        $sql = 'delete from nxt_api_priority  where  s_id=' . $id;
        $mysql->query($sql);
$priority=1;
        for ($o = 0; $o < $one; $o++) {
             if ($_POST['one'][$o] != "") {
                  $sql = 'insert into nxt_api_priority
						(s_id,api_id,api_name,api_service_id,api_service_name,b_price_adj,s_priority)
						values(
                                               
						' . $id . ',
						' . $_POST['one'][$o] . ',
                                                ' . $mysql->quote($_POST['two'][$o]) . ',
                                                
                                                ' . $_POST['three'][$o] . ',
                                                    ' . $mysql->quote($_POST['four'][$o]) . ',
                                                             ' . $mysql->quote($_POST['five'][$o]) . ',
						' .$priority  . ')';

                // echo $sql;exit;
                $mysql->query($sql);
                $priority++;
             }
        }
    }
 else {
      $sql = 'delete from nxt_api_priority  where  s_id=' . $id;
        $mysql->query($sql);    
    }

    /*     * ************************************************** */
    
    /*     * **************************************************
      UPDATE CUSTOME FIELDS
     * *************************************************** */
    if ($f_count > 0) {
//			$currencies = $_POST['currency_id'];
        //$sql = 'delete from ' . SERVER_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
        // delete old custome fields
        $sql = 'delete from nxt_custom_fields  where s_type=1 and s_id=' . $id;
        $mysql->query($sql);

        for ($f = 0; $f < $f_count; $f++) {

            if ($_POST['f_name'][$f] != "") {


                $sql2 = 'update ' . IMEI_TOOL_MASTER . ' set is_custom=1 where id=' . $id;
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
                                                1,
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
                $sql3 = 'update ' . IMEI_TOOL_MASTER . ' set is_custom=0 where id=' . $id;
                ;
                $mysql->query($sql3);
            }
        }
    }
    /*     * ************************************************** */



    /*     * **************************************************
      UPDATE Modelss
     * *************************************************** */
    if (isset($_POST['models'])) {
        $currencies = $_POST['models'];

        // var_dump($currencies);exit;
        $sql = 'delete from nxt_imei_model_master_2 where imei_id = ' . $id . ' and brand_id=' . $brand_id;
        $mysql->query($sql);

        foreach ($currencies as $key => $currency_id) {

            //$amount = $request->PostFloat('amount_' . $currency_id);
            // $amount_purchase = $request->PostFloat('amount_purchase_' . $currency_id);

            $sql = 'insert into nxt_imei_model_master_2
						(imei_id, model_id,brand_id)
						values(
						' . $id . ',
						' . $currency_id . ',
						' . $brand_id . ')';
            //echo $sql;exit;
            $mysql->query($sql);
        }
    }


    if (!isset($_POST['models']) && ($brand_id != 0)) {
        $sql = 'delete from nxt_imei_model_master_2 where imei_id = ' . $id . ' and brand_id=' . $brand_id;
        $mysql->query($sql);
    }
    /*     * ************************************************** */


    /*     * **************************************************
      UPDATE Visible Users
     * *************************************************** */

    if (isset($_POST['check_user'])) {
        $check_user = $_POST['check_user'];
    } else {
        $check_user = array();
    }


    $sql = 'delete from ' . IMEI_TOOL_USERS . ' where tool_id=' . $id;
    $mysql->query($sql);

    if (is_array($check_user)) {
        foreach ($check_user as $user_id) {
            $sql = 'insert into ' . IMEI_TOOL_USERS . ' (tool_id, user_id) values(' . $id . ', ' . $user_id . ')';
            $mysql->query($sql);
        }
    }



    /*     * ************************************************** end */
    /*     * **************************************************
      UPDATE Spl Credits Users
     * *************************************************** */
    $user_ids2 = $_POST['user_ids2'];

    $check_user2 = (isset($_POST['check_user2'])) ? $_POST['check_user2'] : array();

    $sql = 'delete from ' . IMEI_SPL_CREDITS . ' where tool_id=' . $id;
    $mysql->query($sql);

    $sqlInsert = $sqlDel = '';
    foreach ($user_ids2 as $user_id) {
        $splCr = $request->PostFloat('spl_' . $user_id);
        if ($splCr != '' && !in_array($user_id, $check_user2)) {
            $sqlInsert .= 'insert into ' . IMEI_SPL_CREDITS . '
						(amount, tool_id, user_id)
						values(' . $splCr . ', ' . $id . ', ' . $user_id . ');';

            //$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id='. $user_id  . ';';

           // $sql = 'delete from nxt_price_notify where user = ' . $user_id . ' and tool_id=' . $id;
           // $mysql->query($sql);

//            $sql_a = 'insert into nxt_price_notify
//						(tool_id,type, price,user)
//						values(
//						' . $id . ',
//						1,
//						' . $mysql->getFloat($splCr) . ',
//						' . $user_id . ')';
          //  $mysql->query($sql_a);
            
            
            
            
             $sql_del = 'delete from nxt_price_notify  WHERE tool_id = ' . $id . ' and price!=' . $mysql->getFloat($splCr) . ' and user=' . $user_id;
                    $mysql->query($sql_del);

                    $sql_a = 'INSERT INTO nxt_price_notify (tool_id,type, price,user)
SELECT * FROM (SELECT ' . $id . ' as tooll,1 as type,' . $mysql->getFloat($splCr) . ' as amountt ,' . $user_id . ' as userr) AS tmp
WHERE NOT EXISTS (
    SELECT tool_id FROM nxt_price_notify b WHERE b.tool_id = ' . $id . ' and b.price=' . $mysql->getFloat($splCr) . '  and b.user=' . $user_id. '
) LIMIT 1;';

                    $mysql->query($sql_a);
            
            
            
            
            
            
            
            
            
        }
    }
    if ($sqlInsert != '') {
        $mysql->multi_query($sqlInsert);
    }
    if ($sqlDel != '') {
        $mysql->multi_query($sqlDel);
    }

    /*     * ************************************************** end */
    $imei_tool = $tool_name;
    /* $args = array(
      'to' => CONFIG_EMAIL,
      'from' => CONFIG_EMAIL,
      'fromDisplay' => CONFIG_SITE_NAME,
      'imei_tool'=>$imei_tool,
      'site_admin' => CONFIG_SITE_NAME
      );
      $objEmail->sendEmailTemplate('admin_edit_imei_tool', $args); */

    //---------------------------------update group-----------------------
    // first delte old entries of that service

    $sql = 'delete from nxt_grp_det where ser=' . $id;
    $mysql->query($sql);

    foreach ($_POST['group_id'] as $selected) {
        echo $selected . "<br>";
        $sql_a = 'insert into nxt_grp_det
						(ser,grp)
						values(
						' . $id . ',
				
						' . $selected . ')';
        $mysql->query($sql_a);
    }

    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools.html?reply=" . urlencode("reply_update_success"));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_edit.html?id=" . $id . "&reply=" . urlencode("reply_service_imei_tools_duplicate"));
    exit();
}
?>