<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_tools_users_1812jdf18196');
	
	$tool_id=$request->getInt('id');
	
	$sql = 'select um.id,itu.tool_id, um.username
			from ' . USER_MASTER . ' um 
			left join ' . IMEI_TOOL_USERS . ' itu on(um.id=itu.user_id and itu.tool_id=' . $tool_id. ')
			left join ' . IMEI_TOOL_MASTER . ' tm on(itu.tool_id=tm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
			<li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tool_visible_users')); ?></li>
		</ul>
	</div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_users_process.do" enctype="multipart/form-data" method="post">

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tool_visible_users')); ?></div>



					<input type="hidden" name="tool_id" value="<?php echo $tool_id;?>" >
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
									echo '<tr class="' . (($row['tool_id'] != '') ? 'success' : '') . '">';
									echo '<td>' . $i++ . '</td>';
									echo '<td>' . $row['username'] . '</td>';
									
									echo '<td class="text_right">
											<input type="checkbox"  name="check_user[]" ' . (($row['tool_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>
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
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN .'services_imei_tools.html'; ?>" class="btn btn-danger btn-sm"> <i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
	
</form>

