<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$type = $request->getStr('type');
	
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_inbox')); ?></h1>
</div>

	<div class="clear"></div>
	<table class="MT5 table table-striped table-hover panel">
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type;
		
		
		$qType = '';
		
		if($type == "unread")
		{
			$qType = ' status=0 ';
		}
		else if($type == "read")
		{
			$qType = ' status=1 ';
		}
		else
		{
			$qType = '';			
		}
		
		
		$qType = ($qType == '') ? '' : ' and ' . $mysql->getStr($qType);

		
		$sql = 'select * from ' . MAIL_HISTORY . ' where user_id=' . $mysql->getInt($member->getUserId()) . $qType . ' order by id DESC';
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_USER . 'inbox.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
					echo '<td width="15%"><a href="' . CONFIG_PATH_SITE_USER . 'email.html?id=' . $row['id'] . '">' . $row['subject'] . '</a></td>';
					echo '<td width="15%">' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';
					
					$strlen = 120;
					$content = strip_tags($row['plain_mail']);
					$content = substr($content,0, $strlen);
					echo '<td>' . $content . '...</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="7" class="no_record">No record found!</td></tr>';
		}
	?>
	</table>
	<?php echo $pCode;?>