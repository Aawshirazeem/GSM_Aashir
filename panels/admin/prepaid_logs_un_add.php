<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('con_pre_log_log_un_148837312');
	
	$id = $request->getInt('id');
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_un.html?id=<?php echo $id;?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_manager')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_prepaid_username_password')); ?></li>
	</ul>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_un_add_process.do" method="post">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),'Update Currency'); ?></div>
				<div class="panel-body">
					
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username/password')); ?></label>
						<textarea name="prepaid_log_un" class="form-control" id="prepaid_log_un" rows="8"></textarea>
						<input type="hidden" name="prepaid_log_id" id="prepaid_log_id" value="<?php echo $id;?>" />
						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_eg.:')); ?><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_uname1:pass1')); ?><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_uname2:pass2')); ?><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_uname3:pass3')); ?><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_please_put_each_username/password_pair_in_single_line_and_put_line_break_after_each_pair')); ?></p>
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs_un.html?id=<?php echo $id; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_now')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->



</form>
