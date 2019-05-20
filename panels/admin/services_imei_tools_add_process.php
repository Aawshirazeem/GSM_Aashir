<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('services_imei_tools_add_53938484h2');

//var_dump($_POST['group_id']);
if (!isset($_POST['group_id'])) {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_add.html?reply=' . urlencode('repl_service_group_not_selected'));
    exit();
}
$tool_name = $request->PostStr('tool_name');
$tool_alias = $request->PostStr('tool_alias');
// $group_id = $request->PostInt('group_id');
$delivery_time = $request->PostStr('delivery_time');
//$verification = $request->PostInt('verification');
//$status = $request->PostInt('status');
$verification = 0;
$status = 0;

$brand_id = $request->PostInt('brand_id');
$country_id = $request->PostInt('country_id');
$mep_group_id = $request->PostInt('mep_group_id');
$field_pin = $request->PostInt('field_pin');
$field_kbh = $request->PostInt('field_kbh');
$field_prd = $request->PostInt('field_prd');
$field_type = $request->PostInt('field_type');
$imei_type = $request->PostInt('imei_type');
$custom_field_name = $request->PostStr('custom_field_name');
$custom_field_message = $request->PostStr('custom_field_message');
$custom_field_value = $request->PostStr('custom_field_value');

$api_id = $request->PostInt('api_id');
$api_service_id = $request->PostInt('api_service_id');

$download_link = $request->PostStr('download_link');
$faq_id = $request->PostInt('faq_id');
$info = $request->PostStr('info');


if ($tool_name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_add.html?reply=" . urlencode('reply_service_imei_tools_missing'));
    exit();
}

$sql_chk = 'select * from ' . IMEI_TOOL_MASTER . ' where tool_name=' . $mysql->quote($tool_name);
$query_chk = $mysql->query($sql_chk);

if ($mysql->rowCount($query_chk) == 0) {

    $sql = 'insert into ' . IMEI_TOOL_MASTER . '
				(tool_name, tool_alias, delivery_time , api_id , api_service_id , brand_id , country_id , 
					custom_field_name , custom_field_message , custom_field_value , info , 
					download_link , faq_id , mep_group_id , field_pin , field_kbh , field_prd , 
					field_type , imei_type , verification ,status)
				values(
				' . $mysql->quote($tool_name) . ',
				' . $mysql->quote($tool_alias) . ',
				' . $mysql->quote($delivery_time) . ',
				
				' . $mysql->getInt($api_id) . ',
				' . $mysql->getInt($api_service_id) . ',
				' . $mysql->getInt($brand_id) . ',
				' . $mysql->getInt($country_id) . ',
				' . $mysql->quote($custom_field_name) . ',
				' . $mysql->quote($custom_field_message) . ',
				' . $mysql->quote($custom_field_value) . ',
				' . $mysql->quote($info) . ',
				' . $mysql->quote($download_link) . ',
				' . $mysql->getInt($faq_id) . ',
				' . $mysql->getInt($mep_group_id) . ',
				' . $mysql->getInt($field_pin) . ',
				' . $mysql->getInt($field_kbh) . ',
				' . $mysql->getInt($field_prd) . ',
				' . $mysql->getInt($field_type) . ',
				' . $mysql->getInt($imei_type) . ',
				' . $mysql->getInt($verification) . ',
				' . $mysql->getInt($status) . ')';
    $mysql->query($sql);
    $tool_id = $mysql->insert_id();

    // add grp
    foreach ($_POST['group_id'] as $selected) {
        //  echo $selected . "<br>";
        $sql_a = 'insert into nxt_grp_det
						(ser,grp)
						values(
						' . $tool_id . ',
				
						' . $selected . ')';
        $mysql->query($sql_a);
    }


    if (isset($_POST['currency_id'])) {
        $currencies = $_POST['currency_id'];
        foreach ($currencies as $key => $currency_id) {

            $amount = $request->PostFloat('amount_' . $currency_id);
            $amount_purchase = $request->PostFloat('amount_purchase_' . $currency_id);

            $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . '
						(tool_id, currency_id, amount , amount_purchase)
						values(
						' . $tool_id . ',
						' . $currency_id . ',
						' . $amount . ',
						' . $amount_purchase . ')';
            $mysql->query($sql);
        }
    }





    $imei_tool = $tool_name;
    $args = array(
        'to' => CONFIG_EMAIL,
        'from' => CONFIG_EMAIL,
        'fromDisplay' => CONFIG_SITE_NAME,
        'imei_tool' => $imei_tool,
        'site_admin' => CONFIG_SITE_NAME
    );
    $objEmail->sendEmailTemplate('admin_add_imei_tool', $args);
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools.html?reply=" . urlencode('reply_success'));
    exit();
} else {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_add.html?reply=' . urlencode('reply_service_imei_tools_duplicate'));
    exit();
}
?>