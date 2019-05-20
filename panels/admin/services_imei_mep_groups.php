<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$group_id = $request->getInt('group_id');
	
	$sql='select img.*,(select count(id) from ' . IMEI_MEP_MASTER . ' imm
			where imm.mep_group_id = img.id) as totalMeps from ' . IMEI_MEP_GROUP_MASTER . ' img';
	$query = $mysql->query($sql);
	$i = 1;
	$count = $mysql->rowCount($query);
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li class="active"<?php echo $admin->wordTrans($admin->getUserLang(),'>MEP Master'); ?></li>
		</ul>
	</div>
</div>
	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mep_group_master')); ?>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_mep_groups_add.html" class="btn btn-danger btn-xs pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_mep_group')); ?></a>
		</div>

		<table class="table table-hover table-striped">
		<tr>
			<th width="16"> </th>
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mep_groups')); ?></th>
			<th width="150"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_total_meps')); ?> </th>
			<th width="180"></th>
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
					echo '<td>' . $row['mep_group'] . '</td>';
					echo '<td>' . (($row['totalMeps'] == "0") ? '-' : $row['totalMeps']) . '</td>';
					echo '<td class="text-right">
						<div class="btn-group">
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep_groups_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_mep.html?group_id=' . $row['id'] . '" class="btn btn-default btn-sm">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_meps')).'</a>
						</div>
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
