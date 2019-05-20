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

$request = new request();
$mysql = new mysql();
//echo  $_SERVER['HTTP_HOST'];

$con = mysqli_connect("185.27.133.16", "gsmunion_upuser", "S+OXupg8lqaW", "gsmunion_upload");

//$qry_check = 'select * from tbl_users where  domain="' . $_SERVER['HTTP_HOST'] . '" and reseller_panel=0';
$input = $_SERVER['HTTP_HOST'];
$input = trim($input, '/');
if (!preg_match('#^http(s)?://#', $input)) {
    $input = 'http://' . $input;
}
$urlParts = parse_url($input);
$domain = preg_replace('/^www\./', '', $urlParts['host']);

$qry_check = 'select * from tbl_users a

where a.domain LIKE "%'.$domain.'%"  and a.is_update=1';



$result = $con->query($qry_check);


if ($result->num_rows > 0) {

    // is updaye is yes update the local chk as well
    $qry = 'update ' . ADMIN_MASTER . ' set is_update=1';
    $mysql->query($qry);
}

$con->close();
