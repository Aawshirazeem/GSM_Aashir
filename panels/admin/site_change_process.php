<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();



if(isset($_POST['isEdit'])){
	$id = $_POST['id'];
	$sql = 'select * from '.CMS_MENU_MASTER.' where id = '.$id;
	$query = $mysql->query($sql);
	
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$nId = $row['id'];
	$logo = $row['logo'];
	
	$resp = array('status'=>1,'id'=>$nId,'image'=>$logo);
	
	echo json_encode($resp);
	die;
}

$id = $_POST['hdnUpdateId'];

$upPath = CONFIG_PATH_ROOT . '/public/views/cms/site_logo/';

$extractFile = explode('.',$_FILES['logoImage']['name']);	
$randName = mt_rand(100000, 999999).".".end($extractFile);

move_uploaded_file($_FILES['logoImage']['tmp_name'],$upPath.$randName);

$sql = 'update ' . CMS_MENU_MASTER . ' set logo = '.$mysql->quote($randName).' where id = ' . $id;
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_settings.html?msg=" . urlencode("Logo Updated Successfully"));

?>