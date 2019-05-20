<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$id = $_POST['id'];
$content = $_POST['content'];

/*if (defined("DEMO")) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_edit.html?id=" . $id . "&reply=" . urlencode('reply_com_demo'));
    exit();
}*/
$admin = new admin();
$request = new request();
$mysql = new mysql();

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

$sql = 'update ' . CMS_PAGE_MASTER . '	set page_content = ' . $mysql->quote($content) . ' where id = ' . $id;
$mysql->query($sql);

$sql = 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id ;
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

$resp = array('status'=>1,'url'=>$row['url'].'.html');
echo json_encode($resp);
die;

?>