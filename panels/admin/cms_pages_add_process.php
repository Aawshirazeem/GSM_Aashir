<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
if (defined("DEMO")) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_add.html?reply=" . urlencode('reply_com_demo'));
    exit();
}

$admin = new admin();
$request = new request();
$mysql = new mysql();


if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $mtitle = $_POST['mtitle'];
    $sql = 'update ' . CMS_PAGE_MASTER . '	set is_home_page = 0';
    $mysql->query($sql);

    $sql = 'update ' . CMS_PAGE_MASTER . '	set is_home_page = 1 where m_title = "' . $mtitle . '"';
    $mysql->query($sql);

    $resp = array('status' => 1);
    echo json_encode($resp);
    die;
}


/* $postParam = array();
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
  die; */

$title = $_POST['pageMasterTitle'];
$url = $_POST['pageUrl'];
$pLang = 'en';
if (strstr(strtoupper($title), "HOME") == "" && strstr(strtoupper($title), "FOOTER") == "") {
    $sql = 'insert into ' . CMS_PAGE_MASTER . ' (m_title,title,url,page_lang,added_by,added_on) values(' . $mysql->quote($title) . ',' . $mysql->quote($title) . ',' . $mysql->quote($url) . ',' . $mysql->quote($pLang) . ',' . $admin->getUserId() . ',"' . date('Y-m-d H:i:s') . '")';

    $mysql->query($sql);

    header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Page Added Successfully"));
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "cms_pages.html?msg=" . urlencode("Cant_add_page_with_that_title"));
}
?>
