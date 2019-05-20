<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_add_549883whh2');

$name = $request->PostStr('name');
$email = $request->PostStr('email');
$c_person = $request->PostStr('c_person');
$c_number = $request->PostStr('c_number');
$address = $request->PostStr('address');
$notes = $request->PostStr('notes');
$status = $request->PostInt('status');

if ($name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "vendor_add.html?reply=" . urlencode('reply_name_missing'));
    exit();
}

//
//$sql = 'select name from ' . Category . ' where name=' . $mysql->quote($name);
//$query = $mysql->query($sql);
//if ($mysql->rowCount($query) > 0) {
//    header("location:" . CONFIG_PATH_SITE_ADMIN . "category_add.html?reply=" . urlencode('reply_name_duplicate'));
//    exit();
//}


$sql = 'insert into ' . Vendor . '
			(name,email,c_person,c_number,address,notes,status)
			values(
			' . $mysql->quote($name) . ',
                            	' . $mysql->quote($email) . ',
                                    	' . $mysql->quote($c_person) . ',
                                            	' . $mysql->quote($c_number) . ',
                                                    	' . $mysql->quote($address) . ',
                                                            	' . $mysql->quote($notes) . ',
			' . $mysql->getInt($status) . ')';

$mysql->query($sql);
$id = $mysql->insert_id();
header("location:" . CONFIG_PATH_SITE_ADMIN . "vendor.html?reply=" . urlencode('reply_success'));
?>