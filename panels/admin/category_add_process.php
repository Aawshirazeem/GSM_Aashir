<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_add_549883whh2');
$name = $request->PostStr('name');
$name=  strtoupper($name);
$status = $request->PostInt('status');

if ($name == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "category_add.html?reply=" . urlencode('reply_name_missing'));
    exit();
}


$sql = 'select name from ' . Category . ' where name=' . $mysql->quote($name);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "category_add.html?reply=" . urlencode('reply_name_duplicate'));
    exit();
}


$sql = 'insert into ' . Category . '
			(name, status)
			values(
			' . $mysql->quote($name) . ',
			' . $mysql->getInt($status) . ')';

$mysql->query($sql);
$id = $mysql->insert_id();
header("location:" . CONFIG_PATH_SITE_ADMIN . "category.html?reply=" . urlencode('reply_success'));
?>