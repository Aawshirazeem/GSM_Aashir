<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$group_id = $request->getInt('group_id');
	
	$sql='select * from ' . IMEI_FAQ_MASTER;
	$query = $mysql->query($sql);
	$i = 1;
	$count = $mysql->rowCount($query);
?>

<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active">Master</li>
			<li class="active">FAQ Master</li>
		</ul>
	</div>
</div>
	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_hfaq_manager')); ?>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_faq_add.html" class="btn btn-danger btn-xs pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_faq')); ?> </a>
		</div>

		<table class="table table-hover table-striped">
		<tr>
			<th width="16"> </th>
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_faqs')); ?> </th>
			<th width="75"></th>
		</tr>
	    <?php
			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				$i = 0;
				foreach($rows as $row)
				{
					$i++;
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>' . $graphics->status($row['status']) . '</td>';
					echo '<td>' . $row['question'] . '?</td>';
					echo '<td class="text-right">
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_faq_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
						  </td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="8" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
			}
	    ?>
	</table>
