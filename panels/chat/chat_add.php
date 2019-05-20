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

$username = $request->PostStr('u_name');
$useremail = $request->PostStr('u_email');
$userphone = $request->PostStr('u_phone');

$msg = $request->PostStr('msg');
$sql = 'insert into '.Chat_Box_NEW.' (admin_id,user_id,msg,isadmin,isview,entry_type,uname,uemail,uphone)
values (' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',1,1,' . $mysql->quote(user) . ',' . $mysql->quote($username) . ',' . $mysql->quote($useremail) . ',' . $mysql->quote($userphone) . '),(' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',1,0,' . $mysql->quote(admin) . ',' . $mysql->quote($username) . ',' . $mysql->quote($useremail) . ',' . $mysql->quote($userphone) . ')';
//echo $sql;
$mysql->query($sql);
?>