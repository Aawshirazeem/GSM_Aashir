<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_edit_54964566hh2');


$id = $request->PostInt('id');
$name = $request->PostStr('name');
$email = $request->PostStr('email');
$c_person = $request->PostStr('c_person');
$c_number = $request->PostStr('c_number');
$address = $request->PostStr('address');
$notes = $request->PostStr('notes');
$status = $request->PostInt('status');


if ($name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "vendor.html?reply=" . urlencode('reply_name_missing'));
    exit();
}



$sql = 'update ' . Vendor . '
			set 
			name = ' . $mysql->quote($name) . ',
                        email = ' . $mysql->quote($email) . ',
                         c_person = ' . $mysql->quote($c_person) . ',
                              c_number = ' . $mysql->quote($c_number) . ',
                                   address = ' . $mysql->quote($address) . ',
                                        notes = ' . $mysql->quote($notes) . ',
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);


//$sql='update ' . Category . ' set name='. $mysql->quote($username) .',status= ' . $mysql->getInt($status) . '  where id=' . $mysql->getInt($id);


$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "vendor.html?reply=" . urlencode('reply_edit_success'));
exit();
?>