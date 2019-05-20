<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();

$admin_id = $request->PostStr('a_id');
$user_id = $request->PostStr('u_id');
$msg = $request->PostStr('msg');


// first check user if online still

$sql = "select a.a_id from nxt_chat_pool_new a where a.entry_type='user' and a.admin_id=".$admin_id." and a.user_id=".$user_id;
$result = $mysql->getResult($sql);
$msgid = '';
if ($result['COUNT']) {
    $sql = 'insert into '.Chat_Box_NEW.' (admin_id,user_id,msg,isadmin,isview,entry_type)
values (' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',0,1,' . $mysql->quote(admin) . '),(' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',0,0,' . $mysql->quote(user) . ')';
//echo $sql;
$mysql->query($sql);
echo 'add';exit;
}
 else {
    echo 'notadd';exit;
}



?>