<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

//start session in all pages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} //PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above
//
$sql = 'select * from ' . CMS_MENU_MASTER . ' where id = 1';
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

if ($row['logo'] != "") {

    $logo = CONFIG_PATH_THEME_NEW . 'site_logo/' . $row['logo'];
} else
    $logo = CONFIG_SITE_TITLE;
$sql_gw = 'select gm.gateway_id,gm.demo_mode, cm.currency
								from ' . GATEWAY_MASTER . ' gm
								left join ' . GATEWAY_DETAILS . '  gd on (gm.id = gd.gateway_id)
								left join ' . USER_MASTER . ' um on (um.id = gd.user_id)
								left join ' . CURRENCY_MASTER . ' cm on um.currency_id=cm.id
					where gm.m_id=8 and um.id=' . $member->getUserId();
$query_gw = $mysql->query($sql_gw);
$rows_gw = $mysql->fetchArray($query_gw);
$row_gw = $rows_gw[0];

$g_id = $row_gw['gateway_id'];
$g_demo = $row_gw['demo_mode'];
$g_curr = $row_gw['currency'];


$user = $pass = $sign = "";
//$redsys_cur = $redsys_ter = 0;
if (sizeof(explode(':', $g_id)) == 3) {
    list($user, $pass, $sign) = explode(':', $g_id);
}

// sandbox or live
if ($g_demo == 1)
    define('PPL_MODE', 'sandbox');

if (PPL_MODE == 'sandbox') {

    define('PPL_API_USER', $user);
    define('PPL_API_PASSWORD', $pass);
    define('PPL_API_SIGNATURE', $sign);
} else {

    define('PPL_API_USER', $user);
    define('PPL_API_PASSWORD', $pass);
    define('PPL_API_SIGNATURE', $sign);
}

define('PPL_LOGO_IMG', $logo);
define('PPL_LANG', 'EN');
define('PPL_RETURN_URL', 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . 'paypal_ch_return.do');
define('PPL_CANCEL_URL', 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . 'cancel_url.html');
define('PPL_CURRENCY_CODE', $g_curr);
