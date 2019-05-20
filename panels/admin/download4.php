<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$request = new request();
$mysql = new mysql();
if (isset($_GET['id'])) {
// if id is set then get the file with the id from database
    $id = $_GET['id'];
    $sql = 'select *,LENGTH(a.f_content) as f_size2 from ' . ORDER_FILE_SERVICE_MASTER . ' a where a.id=' . $id;
    $result_cr = $mysql->getResult($sql);
    $row1 = $result_cr['RESULT'][0];
    $name = $row1['f_name'];
    $type = substr($name, strpos($name, ".") + 1);    
    $content = $row1['f_content'];
    $size = $row1['f_size2'];
    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Disposition: attachment; filename=$name");
    echo $content;
    exit;
}