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

$title = $request->PostStr('title');
$publish = $request->PostCheck('publish');
$news = $request->PostStr('news');


if ($title == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_add.html?msg=" . urlencode("Please enter news title"));
    exit();
}

// in active old published new first

if($publish==1){

$sql = 'update ' . NEWS_MASTER . '
			set 
			
			publish = 0';
$mysql->query($sql);
}

$sql = 'insert into ' . NEWS_MASTER . '
			(title, news,date_creation,admin_id, publish)
			values(
			' . $mysql->quote($title) . ',
			' . $mysql->quote($news) . ',
                            now(),
                                ' . $admin->getUserId() . ',
			' . $publish . ')';

$mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news.html?msg=" . urlencode("News Added Successfully"));
?>
