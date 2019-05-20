<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

//$admin->checkLogin();
//$admin->reject();

$admin_id = $request->PostStr('a_id');
//$admin_id=1;
$user_id = $request->PostStr('u_id');
$sql="delete from ".Chat_Box."  where entry_type='user' and admin_id=" . $mysql->getInt($admin_id) . " and user_id=" . $mysql->getInt($user_id);
$mysql->query($sql);
echo 'Chat Deleted Succesfully';
?>