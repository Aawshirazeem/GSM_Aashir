<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$id = $_POST['id'];
$content = $_POST['content'];

//if ($id=="") {
//    header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html");
//    exit();
//}
$admin = new admin();
$request = new request();
$mysql = new mysql();
if(isset($_POST['pChange'])){
	$mTitle = $_POST['mtitle'];
	$pLang = $_POST['plang'];
	$sql = 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$mTitle.'" AND page_lang = "'.$pLang.'"' ;
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount > 0){
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
		$id = $row['id'];
		$title = $row['title'];
		$meta = $row['meta'];
	}else{
		$id = 0;
		$title = "";
		$meta = "";
	}
	$respArray = array('id'=>$id,'title'=>$title,'meta'=>$meta);
	echo json_encode($respArray);
	die;
}

if(isset($_POST['isDelete'])){
	$id = $_POST['id'];
	$title= $_POST['title'];
	$lang_code = $_POST['lang_code'];
	if($id != 0){
		/*** fetch data ****/
		$sql = 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id;
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
		/***** end *****/
	
		$sql = 'delete from '.CMS_PAGE_MASTER.' where m_title = "'.$row['m_title'].'"';
		$mysql->query($sql);
	
		$resp = array('status'=>1,'msg'=>'page successfully deleted.');
		echo json_encode($resp);
		die;
	}else{
		$sql = 'delete from '.CMS_PAGE_MASTER.' where m_title = "'.$title.'" and page_lang = "'.$lang_code.'"';
		$mysql->query($sql);
	
		$resp = array('status'=>1,'msg'=>'page successfully deleted.');
		echo json_encode($resp);
		die;
	}
}

if(isset($_POST['changeTitle'])){
	$mTitle = $_POST['title'];
	$sql = 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$mTitle.'"' ;
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$result = array('status'=>1,'title'=>$row['m_title'],'mUrl'=>$row['url']);
	echo json_encode($result);
	die;
}

if(isset($_POST['hdnMasterTitle'])){
	$oldTitle = $_POST['hdnMasterTitle'];
	$newTitle = $_POST['pageMasterTitle'];
	$pageUrl = $_POST['pageUrl'];
	  if (strstr(strtoupper($newTitle), "HOME") == "" && strstr(strtoupper($newTitle), "FOOTER") == "") {
	$sql = 'update ' . CMS_PAGE_MASTER . '	set m_title = ' . $mysql->quote($newTitle) . ',url = ' . $mysql->quote($pageUrl) . ' where m_title = ' . $mysql->quote($oldTitle);
	$mysql->query($sql);

	header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Page Update Successfully"));
          }
 else {
     	header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Page Cant Update With this Title"));

 }
}

if(isset($_POST['hdnUpdateId'])){
	$id = $_POST['hdnUpdateId'];
	$pageTitle = $_POST['pageTitle'];
	$pageMetaKeyword = $_POST['pageMetaKeyword'];
	$hdnLangCode = $_POST['hdnLangCode'];
	$hdnmTitle = $_POST['hdnmTitle'];
  if (strstr(strtoupper($pageTitle), "HOME") == "" && strstr(strtoupper($pageTitle), "FOOTER") == "") {
	$sql = 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$hdnmTitle.'" AND page_lang = "en"' ;
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];

	if($id != 0){
		$sql = 'update ' . CMS_PAGE_MASTER . '	set m_title = ' . $mysql->quote($hdnmTitle) . ',url = ' . $mysql->quote($row['url']) . ',title = ' . $mysql->quote($pageTitle) . ', meta = '.$mysql->quote($pageMetaKeyword). ', page_content = '.$mysql->quote($pageMetaKeyword).', page_lang = '.$mysql->quote($hdnLangCode).' where id = ' . $id;
		/*echo $sql;
		die;*/
		$mysql->query($sql);
	}else{
		$sql = 'insert into '. CMS_PAGE_MASTER.' (m_title,url,title,meta,page_content,page_lang,added_by,added_on) values('.$mysql->quote($hdnmTitle).','.$mysql->quote($row['url']).','.$mysql->quote($pageTitle).','.$mysql->quote($pageMetaKeyword).','.$mysql->quote($row['page_content']).', "'.$hdnLangCode.'",'.$admin->getUserId().',"'.date('Y-m-d H:i:s').'")';
		/*echo $sql;
		die;*/
		$mysql->query($sql);
	}
	header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Page Added Successfully"));
	die;
  }
  else
  {
      header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Page Cant Update With This Title"));
	die;
  }
}

if(isset($_POST['isUpdate'])){
	$postParam = array();
	parse_str($_POST['formstring'],$postParam);
	$id = $postParam['hdnId'];
	$title = $postParam['pageTitle'];
	$metaKeywords = $postParam['pageMetaKeyword'];
	$isHome = (isset($postParam['is_home']) ? $postParam['is_home'] : 0);

        if (strstr(strtoupper($title), "HOME") == "" && strstr(strtoupper($title), "FOOTER") == "") {
	$sql = 'update ' . CMS_PAGE_MASTER . '	set title = ' . $mysql->quote($title) . ', meta = '.$mysql->quote($metaKeywords).', is_home_page = '.$isHome.' where id = ' . $id;
	$mysql->query($sql);

	$resp = array('status'=>1,'msg'=>'data successfully updated.');
	echo json_encode($resp);
	die;
        }
        else
        {
            $resp = array('status'=>1,'msg'=>'Update Failed');
	echo json_encode($resp);
	die;
        }
}

if(isset($_POST['changePage'])){
	$mTitle = $_POST['mtitle'];
	$plang = $_POST['plang'];
	
	$sql = 'select * from ' . CMS_PAGE_MASTER.' where m_title = '.$mysql->quote($mTitle).' AND page_lang = '.$mysql->quote($plang);
	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];

	$url = '../cms.html?id='.$row['id'].'&lang='.$row['page_lang'];
	$resp = array('status'=>1,'rUrl'=>$url);
	echo json_encode($resp);
	die;
}

$sql = 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id ;
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

if(isset($_POST['vPreview']) && $_POST['vPreview'] == 1){
	$sql = 'update ' . CMS_PAGE_MASTER . '	set preview_content = ' . $mysql->quote($content) . '	where id = ' . $id;
	$mysql->query($sql);
	$isPrev = 1;
}else{
	$pEmpty = "";
	$sql = 'update ' . CMS_PAGE_MASTER . '	set page_content = ' . $mysql->quote($content) . ', preview_content = ""	where id = ' . $id;
	$mysql->query($sql);
	$isPrev = 0;
}

$resp = array('status'=>1,'url'=>$row['url'].'.html','isPrev'=>$isPrev);
echo json_encode($resp);
die;
?>