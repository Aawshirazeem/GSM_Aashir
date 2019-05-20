<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();

if(isset($_POST['logoremove']) && $_POST['logoremove'] == 1){
	$sql= 'UPDATE '.CMS_MENU_MASTER.' SET logo = ""';
	$query = $mysql->query($sql);
	echo json_encode(array('success'=>true));
	exit;
}

if(isset($_POST['setSocial']) && $_POST['setSocial'] == 1){
	$id = $_POST['id'];
	$sql = 'select * from '.CMS_SOCIAL.' where id = '.$id;
	$query = $mysql->query($sql);
	
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	if($row['is_active'] == 1){
		$sql = 'update '.CMS_SOCIAL.' SET `is_active` = 0 where `id` = "'.$id.'"';
		$mysql->query($sql);
	}else{
		$sql = 'update '.CMS_SOCIAL.' SET `is_active` = 1 where `id` = "'.$id.'"';
		$mysql->query($sql);
	}
	
	$resp = array('status'=>1);
	echo json_encode($resp);
	die;
}

if(isset($_POST['isSocial']) && $_POST['isSocial'] == 1){
	$id = $_POST['id'];
	$sql = 'select * from '.CMS_SOCIAL.' where id = '.$id;
	$query = $mysql->query($sql);
	
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	$resp = array('status'=>1,'url'=>$row['url'],'id'=>$id);
	echo json_encode($resp);
	die;
}

if(isset($_POST['isSocialUpdate']) && $_POST['isSocialUpdate'] == 1){
	$socialArray = array();
	parse_str($_POST['formData'],$socialArray);
	$id = $socialArray['hdnSocialId'];
	$url = $socialArray['socialUrl'];
	
	$sql = 'update '.CMS_SOCIAL.' SET `url` = "'.$url.'" where `id` = "'.$id.'"';
	$mysql->query($sql);
	
	$resp = array('status'=>1);
	echo json_encode($resp);
	die;
}



foreach($_POST as $k=>$v){
	$sql = 'update '.CMS_SETTINGS.' SET `value` = "'.$v.'" where `config` = "'.$k.'"';
	$mysql->query($sql);
}
$resp = array('status'=>1);
	echo json_encode($resp);
	die;
/*
if(isset($_POST['isDelete'])){
	$id = $_POST['id'];
	$sql = 'delete from '.CMS_PAGE_MASTER.' where id = '.$id;
	$mysql->query($sql);
	
	$resp = array('status'=>1,'msg'=>'page successfully deleted.');
	echo json_encode($resp);
	die;
}

if(isset($_POST['isUpdate'])){
	$postParam = array();
	parse_str($_POST['formstring'],$postParam);
	
	$id = $postParam['hdnId'];
	$title = $postParam['pageTitle'];
	$metaKeywords = $postParam['pageMetaKeyword'];
	$isHome = (isset($postParam['is_home']) ? $postParam['is_home'] : 0);
	
	$sql = 'update ' . CMS_PAGE_MASTER . '	set title = ' . $mysql->quote($title) . ', meta = '.$mysql->quote($metaKeywords).', is_home_page = '.$isHome.' where id = ' . $id;
	$mysql->query($sql);
	
	$resp = array('status'=>1,'msg'=>'data successfully updated.');
	echo json_encode($resp);
	die;
}


$sql = 'update ' . CMS_PAGE_MASTER . '	set page_content = ' . $mysql->quote($content) . '	where id = ' . $id;
$mysql->query($sql);

$sql= 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id ;
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

$resp = array('status'=>1,'url'=>$row['url'].'.html');
echo json_encode($resp);
die;*/

?>