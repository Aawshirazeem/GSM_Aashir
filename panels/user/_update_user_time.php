<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

//$admin->checkLogin();
//$admin->reject();
$member->checkLogin();
$member->reject();
//$admin_id = $request->PostStr('a_id');
//$admin_id=1;
//$user_id = $request->PostStr('u_id');
$now = new DateTime();
$now=$now->format('Y-m-d H:i:s'); 
$sql="update ".USER_MASTER."  set last_active_time='".$now."' where id=" . $member->getUserId();
//echo $sql;exit;
$mysql->query($sql);
//echo 'Chat Deleted Succesfully';
?>