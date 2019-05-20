<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$objCredits = new credits();


$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('user_add_59905855d2');

//$request->test();

$package_id = $request->PostInt('package_id');
$check = 0;
if ($package_id == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "package_file_credit.html?reply=" . urlencode('reply_credit_mising'));
    exit();
}


$sql_tools = 'select id from ' . SERVER_LOG_MASTER;
$tools = $mysql->getResult($sql_tools);

$sql_curr = 'select id from ' . CURRENCY_MASTER;
$currencies = $mysql->getResult($sql_curr);


$sql_1 = 'delete from  ' . PACKAGE_SERVER_LOG_DETAILS . ' where package_id=' . $package_id;
$mysql->query($sql_1);

$sql = 'insert into ' . PACKAGE_SERVER_LOG_DETAILS . '
			(package_id,currency_id,log_id,amount)
			values';
foreach ($tools['RESULT'] as $tool) {
    foreach ($currencies['RESULT'] as $currency) {
        $spl_credit = $request->PostFloat('amount_' . $tool['id'] . '_' . $currency['id']);
        if ($spl_credit > 0) {
            $sql.='(
						' . $package_id . ',
						' . $currency['id'] . ', 
						' . $tool['id'] . ', 
						' . $spl_credit . '),';

            $check = 1;



            $sql_all_users = 'select a.user_id,b.currency_id from nxt_package_users a
inner join nxt_user_master b
on a.user_id=b.id
where a.package_id=' . $package_id;
            $all_users = $mysql->getResult($sql_all_users);
            foreach ($all_users['RESULT'] as $a_user) {

                if ($currency['id'] == $a_user['currency_id']) {
                    $sql12 = 'delete from nxt_price_notify where user = ' . $a_user['user_id'] . ' and tool_id=' . $tool['id'];
                    $mysql->query($sql12);

                    $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $tool['id'] . ',
						3,
						' . $spl_credit . ',
						' . $a_user['user_id'] . ')';
                    $mysql->query($sql_a);
                }
            }
        }
    }
}
$sql = trim($sql, ',') . ';';
if ($check != 0)
    $mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_ADMIN . "package.html?reply=" . urlencode('reply_credit_success'));
exit();
?>