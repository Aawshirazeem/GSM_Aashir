<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('server_logs_users_jfiu47$9trj494r');
	
	$log_id=$request->getInt('id');
	
	$sql = 'select um.id,slu.log_id, um.username
			from ' . USER_MASTER . ' um 
			left join ' . SERVER_LOG_USERS . ' slu on(um.id=slu.user_id and slu.log_id=' . $log_id. ')
			left join ' . SERVER_LOG_MASTER . ' slm on(slu.log_id=slm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visible_to_users')); ?></li>
	</ul>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_users_process.do" enctype="multipart/form-data" method="post" class="formSkin noWidth">

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_visible_users')); ?></div>
						<input type="hidden" name="log_id" value="<?php echo $log_id;?>" >
						<table class="table table-striped table-hover">
						<?php
							if($mysql->rowCount($query) > 0)
							{
								$rows = $mysql->fetchArray($query);
								foreach($rows as $row)
								{
									echo '<tr class="' . (($row['log_id'] != '') ? 'success' : '') . '">';
									echo '<td>' . $i++ . '</td>';
									echo '<td>' . $row['username'] . '</td>';
									
									echo '<td class="text_right">
											<input type="checkbox"  name="check_user[]" ' . (($row['log_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>
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
 <!-- / panel-footer -->
			</div> <!-- / panel -->
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs.html" class="btn btn-danger btn-sm"> <i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
						<input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" />
					</div>
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

	</form>

