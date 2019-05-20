<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('prepaid_logs_un_edit_14887312');

	$id = $request->GetInt('id');
	$prepaid_log_id = $request->GetInt('prepaid_log_id');

	
	$sql ='select * from ' . PREPAID_LOG_UN_MASTER . ' where id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_un.html?id=<?php echo $prepaid_log_id;?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_manager')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_prepaid_username_password')); ?></li>
	</ul>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_un_edit_process.do" method="post">

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_setting_prepaid_log')); ?></div>
				<div class="panel-body">
					
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
						<input name="username" type="text" class="form-control" id="username" value="<?php echo $row['username'];?>" />
						<input name="id" type="hidden" id="id" value="<?php echo $row['id'];?>" />
						<input name="prepaid_log_id" type="hidden" id="prepaid_log_id" value="<?php echo $prepaid_log_id;?>" />
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs_un.html?id=<?php echo $prepaid_log_id; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />
					</div>
					
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->


</form>
