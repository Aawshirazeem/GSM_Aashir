<?php
	//Top News
	$sql = 'select * from ' . NEWS_MASTER . ' where publish=1 order by id DESC';
	$arrayTicker = $mysql->getResult($sql);
?>