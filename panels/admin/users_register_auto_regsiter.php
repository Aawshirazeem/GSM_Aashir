<?php
if(!defined("_VALID_ACCESS")){
	define("_VALID_ACCESS",1);
	require_once("../../_init.php");
}
	
$admin->checkLogin();
$admin->reject();

    
$enable = $request->GetInt('enable');
    
$sql = 'update  ' . CONFIG_MASTER . ' set value_int=' . $mysql->getInt($enable) . ' WHERE field=\'AUTO_REGISTRATION\'';
$query = $mysql->query($sql);
	
header("location:" . CONFIG_PATH_SITE_ADMIN . "users_register.html?&reply=" . urlencode('reply_auto_ragister'));
exit();
?>