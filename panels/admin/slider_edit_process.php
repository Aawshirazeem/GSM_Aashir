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
	$sql = 'delete from '.SLIDER_MASTER.' where id = '.$id;
	$mysql->query($sql);
	
	$resp = array('status'=>1,'msg'=>'page successfully deleted.');
	echo json_encode($resp);
	die;
}

if(isset($_POST['isEdit'])){
	$id = $_POST['id'];
	$sql = 'select * from '.SLIDER_MASTER.' where id = '.$id;
	$query = $mysql->query($sql);
	
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$nId = $row['id'];
	$title = $row['slider_title'];
	$dispOrder = $row['disp_order'];
	$image = $row['image'];
	$notes = $row['notes'];

	$resp = array('status'=>1,'id'=>$nId,'title'=>stripslashes($title),'dispOrder'=>$dispOrder,'image'=>$image,'notes'=>stripslashes($notes));
	
	echo json_encode($resp);
	die;
}

if(isset($_POST['sHW'])){
	if(isset($_POST['setFullWidth']) && $_POST['setFullWidth'] != 1){
		$sliderWidth = $_POST['sliderWidth'];
		$sliderHeight = $_POST['sliderHeight'];
	}else{
		$sliderWidth = 0;
		$sliderHeight = 0;
	}
	
	$sql = 'update ' . SLIDER_MASTER . ' set s_width = '.$sliderWidth .', s_height = '.$sliderHeight;
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_settings.html?msg=" . urlencode("Slider dimensions Successfully"));
}

$id = $_POST['hdnUpdateId'];
$title = $_POST['sliderTitle'];
$dispOrder = $_POST['sliderDispOrder'];
if($dispOrder != "" ? $dispOrder : 0)
if($dispOrder != ""){
	$dispOrder = $_POST['sliderDispOrder'];
}else{
	$dispOrder = 0;
}
$notes = $_POST['notes'];

if(isset($_FILES['sliderImage']['name']) && $_FILES['sliderImage']['name'] != ""){
	$upPath = CONFIG_PATH_ROOT . '/public/views/cms/slider_upload/';
	
	$extractFile = explode('.',$_FILES['sliderImage']['name']);	
	$randName = mt_rand(100000, 999999).".".end($extractFile);

	move_uploaded_file($_FILES['sliderImage']['tmp_name'],$upPath.$randName);
	
	$sql = 'update ' . SLIDER_MASTER . ' set slider_title = ' . $mysql->quote($title) . ', image = '.$mysql->quote($randName).', notes = '.$mysql->quote(addslashes($notes)).', disp_order = '.$dispOrder.' where id = ' . $id;
	
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "slider.html?msg=" . urlencode("Slider Updated Successfully"));
	
}else{
	$sql = 'update ' . SLIDER_MASTER . ' set slider_title = ' . $mysql->quote(addslashes($title)) . ', notes = '.$mysql->quote(addslashes($notes)).', disp_order = '.$dispOrder.' where id = ' . $id;
	
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "slider.html?msg=" . urlencode("Slider Updated Successfully"));
}
?>