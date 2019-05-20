<?php
if(!defined("_VALID_ACCESS")){
	define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();

$json = addslashes($_POST['menu']);
$sql = 'update ' . CMS_MENU_MASTER . '	set `json` = "'.$json.'" WHERE id = 1';
$mysql->query($sql);

$resp = array('status'=>1);
echo json_encode($resp);
die;
?>
