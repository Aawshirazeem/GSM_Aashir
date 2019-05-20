<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$id = $request->PostInt('id');


$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('services_gateway_edit_54434ghh2');



$sql = 'select * from ' . GATEWAY_MASTER . ' where id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_add.html?reply=" . urlencode('reply_success_setting_gateway'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
//$fuc = $key = $user = '';
$new_id = $row['m_id'];
//echo $id;exit;




if ($new_id == 6)
    $gateway_id = $request->PostStr('m_code') . ':' . $request->PostStr('m_password') . ':' . $request->PostStr('m_user') . ':' . $request->PostStr('m_terminal') . ':' . $request->PostStr('m_currency');
else if ($new_id == 7)
    $gateway_id = $request->PostStr('api_key') . ':' . $request->PostStr('x_key');
else if ($new_id == 8)
    $gateway_id = $request->PostStr('user_name_paypal') . ':' . $request->PostStr('password_paypal') . ':' . $request->PostStr('sign_paypal');

else {
    $gateway_id = $request->PostStr('gateway_id');
}
$charges = $request->PostFloat('charges');
$details = $request->PostStr('details');
$status = $request->PostCheck('status');
$min = $request->PostInt('min');
$max = $request->PostInt('max');
$demo = $request->PostCheck('demo_mode');


$sql = 'update ' . GATEWAY_MASTER . '
			set 
			gateway_id = ' . $mysql->quote($gateway_id) . ',
			charges = ' . $mysql->getInt($charges) . ',
			details = ' . $mysql->quote($details) . ',
			min = ' . $mysql->getInt($min) . ',
			max = ' . $mysql->getInt($max) . ',
			status = ' . $mysql->getInt($status) . ',
                            demo_mode = ' . $mysql->getInt($demo) . '
			where id = ' . $mysql->getInt($id);
$mysql->query($sql);

$temp_g_id = $id;
if ($status == 1) {
    $sql_del = 'delete from ' . GATEWAY_DETAILS . ' where gateway_id = ' . $mysql->getInt($temp_g_id);
    $mysql->query($sql_del);

    $sql_gw = 'select id from ' . USER_MASTER;
    $query_gw = $mysql->query($sql_gw);
    if ($mysql->rowCount($query_gw) > 0) {
        $rows_gw = $mysql->fetchArray($query_gw);
        foreach ($rows_gw as $row_gw) {

            $temp_user_id = $row_gw['id'];
            $sql = 'insert into ' . GATEWAY_DETAILS . ' (user_id, gateway_id) values(' . $temp_user_id . ',' . $temp_g_id . ')';
            $query = $mysql->query($sql);
        }
    }
    // add it to all users profile
} else {
    $sql_del = 'delete from ' . GATEWAY_DETAILS . ' where gateway_id = ' . $mysql->getInt($temp_g_id);
    $mysql->query($sql_del);
    // remove  it to all users profile
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "settings_gateway.html?reply=" . urlencode('reply_success'));
?>