<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();
	
	$id = $request->getInt('id');
	
?>

<h1><?php $lang->prints('lbl_latest_news');?></h1>
<div class="newsDetails">
	<?php
		$QStr = "";
		if($id != 0)
		{
			$QStr = " and id=" . $mysql->getInt($id);
		}
		$sql = 'select * from ' . NEWS_MASTER . ' where publish=1' . $QStr . " order by id DESC";
		$query = $mysql->query($sql);
		
		$i = 0;
		$groupName = "";
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				echo '<h2><a href="' . CONFIG_PATH_SITE . 'news.html?id=' . $row['id'] . '">' . $row['title'] . '</a></h2>';
				echo '<h3>' . $row['date_creation'] . '</h3>';
				echo '<div class="details">' . ($row['news']) . '</div>';
			}
		}
	?>
</div>