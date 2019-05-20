<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),'Manage Currency'); ?></h1>
	<div class="btn-group">
		<a href="<?php echo CONFIG_PATH_SITE_USER;?>currency_add.html"><img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/add.png" width="10" height="10" alt="" /><?php echo $admin->wordTrans($admin->getUserLang(),'Currency Add'); ?></a>
	</div>
</div>
<!--
<div class="toolbarSkin text_right float_right">
	<a href="<?php echo CONFIG_PATH_SITE_USER;?>currency_add.html"><img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/add.png" width="10" height="10" alt="" />Currency Add</a>
</div> -->
<div class="clear"></div>
	<table class="MT5 table table-striped table-hover panel">
	<tr>
		<th width="45"></th>
		<th width="16"></th>
		<th width="16"></th>
		<th width="15%"><?php echo $admin->wordTrans($admin->getUserLang(),'currency'); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'prefix'); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'prefix_code'); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'suffix'); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'rate'); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'default'); ?></th>
		<th width="100" style="text-align:center"></th>
	</tr>
	<?php
		$sql= 'select cm.*
					from ' . CURRENCY_MASTER . ' cm 
				    order by currency';
		$query = $mysql->query($sql);
		$strReturn = "";
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				echo '<tr>';
				echo '<td>' . $row['id'] . '</td>';
				echo '<td>';
				echo '</td>';
				echo '<td>';
				echo '<td><h2 style="font-size:24px;">' . $row['currency'] . '</h2>';
				echo '<td>' . $row['prefix'] . '</td>';
				echo '<td>' . $row['prefix_code'] . '</td>';
				echo '<td>' . $row['suffix'] . '</td>';
				echo '<td>' . $row['rate'] . '</td>';
				echo '<td>' . $row['default'] . '</td>';
				echo '<td class="toolbarSkin text_center" style="text-align:center">
						<a href="' . CONFIG_PATH_SITE_USER . 'currency_edit.html?id=' . $row['id'] . '"><img src="' . CONFIG_PATH_IMAGES . 'skin/edit.png" wdith="10" height="10" alt="">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_edit')) . '</a>
					  </td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="8" class="no_record">No record found!</td></tr>';
		}
	?>
	</table>