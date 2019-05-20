<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$tool_id = $_REQUEST['id'];
if ($tool_id != "") {
    $sqldel = 'delete from ' . IMEI_SPL_CREDITS
            . ' 
where tool_id=' . $tool_id;

    $query = $mysql->query($sqldel);

    $sqldel = 'delete from ' . PACKAGE_IMEI_DETAILS
            . ' 
where tool_id=' . $tool_id;

    $query = $mysql->query($sqldel);
}