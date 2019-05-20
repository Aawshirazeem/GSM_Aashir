<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$group_id = $request->getInt('group_id');
	
	$sql='select * from ' . IMEI_MEP_MASTER . ' where mep_group_id=' . $mysql->getInt($group_id);
	$query = $mysql->query($sql);
	$i = 1;
	$count = $mysql->rowCount($query);
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_mep_groups.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_MEP_master')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'MEP'); ?></li>
		</ul>
	</div>
</div>

<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mep_manager')); ?>
			<div class="btn-group pull-right">
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_mep_groups.html" class="btn btn-default btn-xs"> <i class="fa fa-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_mep_add.html?group_id=<?php echo $group_id;?>" class="btn btn-danger btn-xs"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_mep')); ?></a>
			</div>
		</div>

		<table class="table table-hover table-striped">
		<tr>
			<th width="16"> </th>
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mep')); ?></th>
			<th width="70"></th>
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
					echo '<td>' . $row['mep'] . '</td>';
					echo '<td class="text-right">
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_edit.html?id=' . $row['id'] . '&group_id=' . $group_id . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
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
</section>
