<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('prepaid_logs_users_98349t57hjng94750');
	
	$prepaid_log_id=$request->getInt('id');
	
	$sql = 'select um.id,plu.prepaid_log_id,
			um.username
			from ' . USER_MASTER . ' um 
			left join ' . PREPAID_LOG_USERS . ' plu on(um.id=plu.user_id and plu.prepaid_log_id=' . $prepaid_log_id. ')
			left join ' . PREPAID_LOG_MASTER . ' plm on(plu.prepaid_log_id=plm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visible_to')); ?></li>
	</ul>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_users_process.do" enctype="multipart/form-data" method="post" class="formSkin noWidth">

		<input type="hidden" name="prepaid_log_id" value="<?php echo $prepaid_log_id;?>" >
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<section class="panel MT10">
				<div class="panel-heading">
					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_visible_users')); ?>
				</div>
				<table class="table table-hover table-striped">	
					<tr>
					  <th width="16"></th>
					  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>
					  <th width="16"></th>
				</tr>
			<?php
				if($mysql->rowCount($query) > 0)
				{
					$rows = $mysql->fetchArray($query);
					foreach($rows as $row)
					{
						echo '<tr class="' . (($row['prepaid_log_id'] != '') ? 'success' : '') . '">';
						echo '<td>' . $i++ . '</td>';
						echo '<td>' . $row['username'] . '</td>';
					
						echo '<td class="text_right">
								<input type="checkbox"  name="check_user[]" ' . (($row['prepaid_log_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>
								<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />
							</td>';
						echo '</tr>';
					}
				}
				else
				{
					echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
				}
			?>
				</table>
			</section>
				<div class="form-group">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
					<input type="submit" name="update" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />
				</div>
		</div>
	</div>
	
</form>

