<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$id = $request->PostInt('id');
if (defined("DEMO")) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_edit.html?id=" . $id . "&reply=" . urlencode('reply_com_demo'));
    exit();
}
$admin = new admin();
$request = new request();
$mysql = new mysql();


$title = $request->PostStr('title');
$publish = $request->PostCheck('publish');
$news = $request->PostStr('news');


// in active old published new first

if($publish==1){

$sql = 'update ' . NEWS_MASTER . '
			set 
			
			publish = 0';
$mysql->query($sql);
}

$sql = 'update ' . NEWS_MASTER . '
			set 
			title = ' . $mysql->quote($title) . ',
			publish = ' . $publish . ',
                            date_update = now(),
                            admin_update_id = ' . $admin->getUserId() . ',
			news = ' . $mysql->quote($news) . '			
			where id = ' . $id;
$mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news.html?msg=" . urlencode("News Updated Successfully"));
?>