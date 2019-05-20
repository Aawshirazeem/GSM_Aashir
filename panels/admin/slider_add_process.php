<?php
if(!defined("_VALID_ACCESS")){
	define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin = new admin();
$request = new request();
$mysql = new mysql();


if(isset($_POST['getHW'])){
	$sql = 'select * from ' . SLIDER_MASTER.' ORDER BY id DESC' ;
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$resp = array('status'=>1,'height'=>$row['s_height'],'width'=>$row['s_width']);
	echo json_encode($resp);
	die;
}

if(isset($_POST['id'])){
	$id = $_POST['id'];
	
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

/*
$postParam = array();
parse_str($_POST['formstring'],$postParam);

$title = $postParam['pageTitle'];
$url = $postParam['pageUrl'];
$metaKeywords = $postParam['pageMetaKeyword'];
$pageContent = '';
$isHome = (isset($postParam['is_home']) ? $postParam['is_home'] : 0);

$sql = 'insert into '. CMS_PAGE_MASTER.' (title,url,meta,page_content, is_home_page,added_by,added_on) values('.$mysql->quote($title).','.$mysql->quote($url).','.$mysql->quote($metaKeywords).','.$mysql->quote($pageContent).','.$isHome.','.$admin->getUserId().',"'.date('Y-m-d H:i:s').'")';

$mysql->query($sql);

if($mysql->insert_id() != "" && $mysql->insert_id() != 0){
	$resp = array('status'=>1,'inserted_id'=>$mysql->insert_id());
}else{
	$resp = array('status'=>0);
}

echo json_encode($resp);
die;*/

//if(isset($_POST['btnSavePage'])){
	$title = $_POST['sliderTitle'];
	$dispOrder = $_POST['sliderDispOrder'];
	$sliderWidth = $_POST['sliderWidth'];
	$sliderHeight = $_POST['sliderHeight'];
	$notes = $_POST['notes'];
	
	$image = $_FILES['sliderImage']['name'];
	
	$upPath = CONFIG_PATH_ROOT . '/public/views/cms/slider_upload/';
	
	$extractFile = explode('.',$_FILES['sliderImage']['name']);	
	$randName = mt_rand(100000, 999999).".".end($extractFile);

	move_uploaded_file($_FILES['sliderImage']['tmp_name'],$upPath.$randName);
	
	$sql = 'insert into '. SLIDER_MASTER.' (slider_title,image,notes,added_by,disp_order) values("'.$title.'","'.$randName.'","'.addcslashes($notes).'",'.$admin->getUserId().','.$dispOrder.')';
	echo $sql;
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "slider.html?msg=" . urlencode("Slider Added Successfully"));
//}
?>
