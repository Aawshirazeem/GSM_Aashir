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

/* Count number of credit request */
$sql_cr = 'select count(id) as totalRequests from ' . INVOICE_REQUEST . ' tm where status=0';
$query_cr = $mysql->query($sql_cr);
$rows_cr = $mysql->fetchArray($query_cr);
$crRequest = $rows_cr[0]['totalRequests'];
/* Get list of credit request */
$crRequestArr = array();
$IS_NOTI = FALSE;
if ($crRequest > 0) {
    $sql_cr = 'select im.*,um.username, cm.prefix, gm.gateway
					from ' . INVOICE_REQUEST . ' im
					left join ' . USER_MASTER . ' um on (im.user_id = um.id)
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					where im.status=0
				order by im.id DESC limit 10';
    $query_cr = $mysql->query($sql_cr);
    if ($mysql->rowCount($query_cr) > 0) {

        $IS_NOTI = TRUE;
        $newcredreq = array();
        $newcredreq = $mysql->fetchArray($query_cr);
    }
    // $crRequestArr = $mysql->fetchArray($query_cr);
}


$datta = "";
if ($IS_NOTI) {

    $datta .= '
    <li class="nav-item dropdown dropdown-menu-right">
        	<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
                <i class="zmdi zmdi-money"></i>
                <span class="label label-rounded label-danger label-xs">'.$crRequest.'</span>
            </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right" style="min-width: 222px">
            	<a class="dropdown-item" href='.CONFIG_PATH_SITE_ADMIN.'users_credit_invoices.html?status=0>
                	<span class="label label-default pull-right">' . $admin->wordTrans($admin->getUserLang(), 'New') . $crRequest . '</span>' . $admin->wordTrans($admin->getUserLang(), 'Pending Credits') . '(s)
                </a>';
    if ($IS_NOTI) {

        foreach ($newcredreq as $crReq) {
            $datta.= '<a class="dropdown-item" href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html">
								<i class="zmdi zmdi-account"></i>  
								<span class="subject">
									<span class="from">' . $crReq['username'] . '</span>
									<span class="time">' . $crReq['prefix'] . ' ' . $crReq['credits'] . '</span>
								</span>
								<span class="message">' . date("d-M Y H:i", strtotime($crReq['date_time'])) . '</span></a>';
        }



        $datta.=' </div>';
    }
}
if ($datta != "") {
    echo json_encode(array($datta, $crRequest));
}


exit;
