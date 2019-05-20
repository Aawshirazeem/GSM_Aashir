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
$admin->checkLogin();
$admin->reject();

$sql_reg = 'select count(id) as total from ' . USER_REGISTER_MASTER;
$query_reg = $mysql->query($sql_reg);
$rows_reg = $mysql->fetchArray($query_reg);
$newReg = $rows_reg[0]['total'];
/* Get list of new registration */
$newRegArr = array();
$IS_NOTI = FALSE;
if ($newReg > 0) {
    $sql_reg = $sql = 'select id, username from ' . USER_REGISTER_MASTER . ' limit 5';
    $query_reg = $mysql->query($sql_reg);
    $newRegArr = $mysql->fetchArray($query_reg);
    $IS_NOTI = TRUE;
    $newusers = array();
    $newusers = $mysql->fetchArray($query_reg);
}
$datta = "";
if ($IS_NOTI) {

    $datta.='<li class="nav-item dropdown dropdown-menu-right">
            <a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
            	<i class="zmdi zmdi-accounts"></i>
                <span class="label label-rounded label-danger label-xs">' . $newReg . '</span>
            </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right" style="min-width: 222px">
            	<a class="dropdown-item">
                	<span class="label label-default pull-right">' . $admin->wordTrans($admin->getUserLang(), 'New') . $newReg . '</span>' . $admin->wordTrans($admin->getUserLang(), 'Pending User') . '(s)
                </a>';

    if ($newReg > 0) {
        foreach ($newRegArr as $user) {
            $datta.='<a class="dropdown-item" href="' . CONFIG_PATH_SITE_ADMIN . 'users_add.html?id=' . $user['id'] . '"><i class="zmdi zmdi-account"></i>  ' . $user['username'] . '</a>';
        }
    }
    $datta.='	
                <a class="dropdown-item" href="'.CONFIG_PATH_SITE_ADMIN.'users_register.html">
                	<i class="zmdi zmdi-accounts-list"></i>' . $admin->wordTrans($admin->getUserLang(), 'Pending User List') . '
                </a>
            </div>
        </li>';
}
//$datta.=' </div>';

if ($datta != "") {
    echo json_encode(array($datta, $newReg));
}


exit;
