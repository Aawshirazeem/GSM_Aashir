<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$id = $request->getInt('id');
	
?>

	<div class="clear"></div>
	<div style="width:90%; margin:0px auto">
	<?php
		$sql = 'select * from ' . MAIL_HISTORY . ' where id=' . $mysql->getInt($id) . ' and user_id=' . $mysql->getInt($member->getUserId()) . ' order by id DESC';
		$query = $mysql->query($sql);
		$strReturn = "";
		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$row = $rows[0];
			echo '<h1>' . $row['subject'] . '</h1>';
			echo '<h2>' . date("d-M Y H:i", strtotime($row['date_time'])) . '</h2>';
			echo '<div class="text_12 text_black">' . $row['content'] . '</div>';
		}
		else
		{
			echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_you_are_not_authorized_to_view_the_mail!'));
		}
	?>

	<br /><br />
	<div class="butSkin">
		<a href="<?php echo CONFIG_PATH_SITE_USER;?>inbox.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl__back_to_inbox')); ?></a>
	</div>

	</div>
