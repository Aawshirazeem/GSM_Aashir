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

// get all notification
$sql_notif = 'select a.id, "IMEI" as type, b.tool_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_imei_tool_master b
on a.service_id=b.id

where a.display=1 and a.order_type=1

union
select a.id, "FILE" as type,  c.service_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_file_service_master c
on a.service_id=c.id

where a.display=1 and a.order_type=2
union
select a.id, "SERVER" as type,  d.server_log_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_server_log_master d
on a.service_id=d.id

where a.display=1 and a.order_type=3';

$query_noti = $mysql->query($sql_notif);
//$IS_NOTI = FALSE;
if ($mysql->rowCount($query_noti) > 0) {
    $total_new_notifi = $mysql->rowCount($query_noti);
}
$sql_notif = 'select a.id, "IMEI" as type, b.tool_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_imei_tool_master b
on a.service_id=b.id

where a.display=1 and a.order_type=1

union
select a.id, "FILE" as type,  c.service_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_file_service_master c
on a.service_id=c.id

where a.display=1 and a.order_type=2
union
select a.id, "SERVER" as type,  d.server_log_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_server_log_master d
on a.service_id=d.id

where a.display=1 and a.order_type=3 order by id desc limit 10';

$query_noti = $mysql->query($sql_notif);
$IS_NOTI = FALSE;
if ($mysql->rowCount($query_noti) > 0) {
  
    $IS_NOTI = TRUE;
    $newnotifications = array();
    $newnotifications = $mysql->fetchArray($query_noti);
}
$datta = "";
if ($IS_NOTI) {

    $datta = '
    <li class="nav-item dropdown dropdown-menu-right">
        <a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
            <span class="label label-rounded label-danger label-xs">'.$total_new_notifi.'</span>
        </a>
        <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right" style="min-width: 222px">';
    if ($IS_NOTI) {
              //  if($total_new_notifi>10)
            $datta.='<a class="dropdown-item" href="#" onclick="offnotificatios(-1);return false;"><i class="fa fa-bell-slash"></i>  Clear All Notifications</a>';

        foreach ($newnotifications as $noti) {
            $datta.= '<a style="font-size:10px;" class="dropdown-item" onclick="offnotificatios(' . $noti['id'] . ');return false;" href="#"><i class="fa fa-bell"></i>  ' . $noti['type'] . ' = ' . $noti['torders'] . ' NEW ORDER(S) ' . $noti['tool'] . '&nbsp<button style="font-size:10px;" type="button" class="btn btn-success btn-sm">Readed</button></a>';
        }
        if($total_new_notifi>10)
            $datta.='<a class="dropdown-item" href="'.CONFIG_PATH_SITE_ADMIN.'all_notifications.html"><i class="fa fa-bell"></i>Show More Notifications</a>';
    }


    $datta.=' </div>';
}

if ($datta != "")
{
    echo json_encode(array($datta, $total_new_notifi));
}


exit;
