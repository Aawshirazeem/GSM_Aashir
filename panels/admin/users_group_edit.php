<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_group_edit_54964566h34');
    
	$group_id = $request->GetInt('group_id');
	$sql ='select * from ' . USER_GROUP_MASTER . ' where id=' . $group_id	;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	
	if($mysql->rowCount($query)>0)
	{
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
	
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_group_edit_process.do" method="post">
	<div class="lock-to-top">
		<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group_manager')); ?> </h1>
		<div class="btn-group">
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users_group.html" class="btn btn-default"><?php echo '<i class="icon-arrow-left"></i>'; ?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>
			<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success">
		</div>
	</div>
	  
	  
	  <div class="clear"></div>

			<input name="group_id" type="hidden" class="textbox_fix" id="id" value="<?php echo $group_id?>" />
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel">
						<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group_manager')); ?> </div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_group_name')); ?></label>
								<input type="text" name="group_name" class="form-control" value="<?php echo $row['group_name']?>">
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></label>
								<input type="checkbox" name="status" class="checkbox-inline" <?php echo (($row['status']==1) ? 'checked="checked"' : '')?>>
							</div>
						</div> <!-- / panel-body -->
						<div class="panel-footer">
							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success">
						</div> <!-- / panel-footer -->
					</div> <!-- / panel -->
				</div> <!-- / col-lg-6 -->
			</div> <!-- / row -->
			
<?php
	}
	else
	{
		echo '<tr><td colspan="6" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
	}
	?>	

</form>
