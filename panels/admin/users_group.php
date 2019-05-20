<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$mysql = new mysql();
	
	$getString = $request->getStr('getString');
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group_manager')); ?></h1>
	<div class="btn-group">
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users_group_add.html" class="btn btn-success"> <i class="icon-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_group')); ?></a>
	</div>
</div>
	<div class="btn-group">
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users.html?<?php echo $getString;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users_group.html?<?php echo $getString;?>" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_groups')); ?></a>
		<!-- <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users_manage_creidts.html?<?php echo $getString;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mass_credit_trans.')); ?></a> -->
	</div>

<div class="clearfix"></div>

	<div class="row MT10">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group_manager')); ?></div>
					<table class="table table-striped table-hover">
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group')); ?></th>
						<th width="140"></th>
					</tr>
					<?php
					$sql = 'select * from ' . USER_GROUP_MASTER ;
					$query = $mysql->query($sql);
						$i = 1;
						if($mysql->rowCount($query) > 0)
						{
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row)
							{
								echo '<tr>';
								echo '<td>' . $i++ . '</td>';
								echo '<td>' . $graphics->status($row['status']) . '</td>';
								echo '<td><a href="' . CONFIG_PATH_SITE_ADMIN . 'users.html?group_id=' . $row['id'] . '">' . $row['group_name'] . '</a></td>';
								echo '<td>
										<div class="btn-group">
											<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_group_edit.html?group_id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
											<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_group_allot.html?group_id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_users')).  '</a>
										</div>
									</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr><td colspan="7" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
						}
					?>
					</table>
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

