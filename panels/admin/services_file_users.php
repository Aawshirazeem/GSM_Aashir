<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_file_users_185158&9jnf8');
	
	$service_id=$request->getInt('id');
	
	$sql = 'select um.id,fsu.service_id,
			um.username
			from ' . USER_MASTER . ' um 
			left join ' . FILE_SERVICE_USERS . ' fsu on(um.id=fsu.user_id and fsu.service_id=' . $service_id. ')
			left join ' . FILE_SERVICE_MASTER . ' tm on(fsu.service_id=tm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>

<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_manager')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_service_visible_to')); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_users_process.do" enctype="multipart/form-data" method="post" class="formSkin noWidth">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_service_visible_to')); ?></div>
					<input type="hidden" name="service_id" value="<?php echo $service_id;?>" >
					<table class="table table-striped table-hover">
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
									echo '<tr class="' . (($row['service_id'] != '') ? 'success' : '') . '">';
									echo '<td>' . $i++ . '</td>';
									echo '<td>' . $row['username'] . '</td>';
									
									echo '<td class="text_right">
											<input type="checkbox"  name="check_user[]" ' . (($row['service_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>
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
				
				
				
				<div class="panel-footer">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html" type="submit" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
					<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" />
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

	</form>

