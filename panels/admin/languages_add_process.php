<?php
if(!defined("_VALID_ACCESS")){
	define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();

if(isset($_POST['hdnUpdateId']) && $_POST['hdnUpdateId'] != 0){
	$id = $_POST['hdnUpdateId'];
	
	$sql= 'select * from ' . SLIDER_MASTER.' where id = '.$id ;
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	if($row['is_active'] == 1){
		$sql = 'update ' . SLIDER_MASTER . ' set is_active = 0 where id = ' . $id;
	}else{
		$sql = 'update ' . SLIDER_MASTER . ' set is_active = 1 where id = ' . $id;
	}
	
	$mysql->query($sql);
	
	$resp = array('status'=>1);
	echo json_encode($resp);
	die;
}

/******** insert new language *********/
$name = $_POST['languageName'];
$code = $_POST['languageCode'];


if(isset($_FILES['languageFlag']['name']) && $_FILES['languageFlag']['name'] != ""){
	$upTemp = '';
	$fileName = explode('.',$_FILES['languageFlag']['name']);
	$ext = end($fileName);
	$upFileName = $code.'.'.$ext;
	
	$upPath = CONFIG_PATH_ROOT . '/panel_themes/Dark/assets_1/language_flag/';
	move_uploaded_file($_FILES['languageFlag']["tmp_name"],$upPath.$upFileName);
}else{
	$upFileName = 'default-flag.jpg';
}

$sql = 'insert into '. LANG_MASTER.' (language,language_code,added_by,language_flag) values("'.$name.'","'.$code.'",'.$admin->getUserId().',"'.$upFileName.'")';
$mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_ADMIN . "languages.html?msg=" . urlencode("Language Added Successfully"));

?>
