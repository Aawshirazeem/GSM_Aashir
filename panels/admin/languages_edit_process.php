<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();

if(isset($_POST['isDelete'])){
	$id = $_POST['id'];
	$sqlFetchLang = 'select * from '.LANG_MASTER.' where where id = '.$id;
	$query = $mysql->query($sqlFetchLang);
	$rows = $mysql->fetchArray($query);
		
	$sql = 'delete from '.LANG_MASTER.' where id = '.$id;
	$mysql->query($sql);
	
	$sqlDeleteTrans = 'delete from '.TRANSLATION_MASTER.' where lang_code = "'.$rows[0]['language_code'].'"';
	$mysql->query($sqlDeleteTrans);
	
	$resp = array('status'=>1,'msg'=>'language successfully deleted.');
	echo json_encode($resp);
	die;
}

if(isset($_POST['isChange'])){
	$id = $_POST['id'];
	
	if($_POST['status'] == 1){
		$sql = 'select * from '.LANG_MASTER.' where lang_status = 1';
		$query = $mysql->query($sql);
		
		$rowCount = $mysql->rowCount($query);
		if($rowCount > 1){
			$sql = 'update ' . LANG_MASTER . ' set lang_status = 0 where id = ' . $id;
		}else{
			$resp = array('status'=>0,'msg'=>'please select at least one language.');
			echo json_encode($resp);
			die;
		}
	}else{
		$sql = 'update ' . LANG_MASTER . ' set lang_status = 1 where id = ' . $id;
	}
	
	$mysql->query($sql);
	$resp = array('status'=>1);
	
	echo json_encode($resp);
	die;
}

if(isset($_POST['isEdit'])){
	$id = $_POST['id'];
	$sql = 'select * from '.LANG_MASTER.' where id = '.$id;
	$query = $mysql->query($sql);
	
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$nId = $row['id'];
	$name = $row['language'];
	$code = $row['language_code'];
	$lFlag = CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$row['language_flag'];

	$resp = array('status'=>1,'id'=>$nId,'name'=>$name,'code'=>$code,'langFlag'=>$lFlag);
	
	echo json_encode($resp);
	die;
}

if(isset($_GET['setLang']) && $_GET['setLang'] != ""){
	$sql = 'update ' . ADMIN_MASTER . ' set user_lang = "' . $_GET['setLang'] . '" where id = ' . $admin->getUserId();
	
	$mysql->query($sql);
	
	$admin->setUserLang($_GET['setLang']);
	$resp = array('status'=>1);
	
	echo json_encode($resp);
	die;
	//header("location:" . CONFIG_PATH_SITE_ADMIN . "log_out.do?msg=" . urlencode("reply_session_expired"));
}

$id = $_POST['hdnUpdateId'];
$name = $_POST['languageName'];
$code = $_POST['languageCode'];

if(isset($_FILES['languageFlag']['name']) && $_FILES['languageFlag']['name'] != ""){
	$upTemp = '';
	$fileName = explode('.',$_FILES['languageFlag']['name']);
	$ext = end($fileName);
	$upFileName = $code.'.'.$ext;
	
	$upPath = CONFIG_PATH_ROOT . '/panel_themes/Dark/assets_1/language_flag/';
	move_uploaded_file($_FILES['languageFlag']["tmp_name"],$upPath.$upFileName);
	
	$sql = 'update ' . LANG_MASTER . ' set language = ' . $mysql->quote(addslashes($name)) . ', language_code = '.$mysql->quote(addslashes($code)).', language_flag = "'.$upFileName.'" where id = ' . $id;
	
	$mysql->query($sql);
}else{
	$sql = 'update ' . LANG_MASTER . ' set language = ' . $mysql->quote(addslashes($name)) . ', language_code = '.$mysql->quote(addslashes($code)).' where id = ' . $id;
	
	$mysql->query($sql);
}


header("location:" . CONFIG_PATH_SITE_ADMIN . "languages.html?msg=" . urlencode("Language Updated Successfully"));
?>