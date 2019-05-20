<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_edit_54964566hh2');


$id = $request->PostInt('id');
$username = $request->PostStr('username');
$username=  strtoupper($username);

//$sql = 'select name from ' . Category . ' where name=' . $mysql->quote($username);
//$query = $mysql->query($sql);
//if ($mysql->rowCount($query) > 0) {
//    header("location:" . CONFIG_PATH_SITE_ADMIN . "category.html?reply=" . urlencode('reply_name_duplicate'));
//    exit();
//}

//$password = $request->PostStr('password');
$status = $request->PostInt('status');

$sql = 'update ' . ADMIN_MASTER . '
			set 
			' . $qPassword . '
			email = ' . $mysql->quote($email) . ',
                        timezone_id = ' . $mysql->quote($tz) . ',
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);


$sql='update ' . Category . ' set name='. $mysql->quote($username) .',status= ' . $mysql->getInt($status) . '  where id=' . $mysql->getInt($id);
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "category.html?reply=" . urlencode('reply_edit_success'));
exit();
?>