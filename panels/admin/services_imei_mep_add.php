<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_mep_add_56556632');
	$group_id = $request->GetInt('group_id');
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_mep_groups.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_MEP_master')); ?></a></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_mep.html?group_id=<?php echo $group_id; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_MEP')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Add New MEP'); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_mep_add_process.do" method="post">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_mep_details')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_mep')); ?></label>
						<input name="mep" type="text" class="form-control" id="mep" value="" />
						<input name="group_id" type="hidden" id="group_id" value="<?php echo $group_id;?>" />
						<input name="filetype" type="hidden" id="filetype" value="html" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> </label>
						<label class="checkbox-inline"><input type="radio" name="status" value="1" checked="checked"/><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="0" /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_mep.html?group_id=<?php echo $group_id;?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_mep')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
	
</form>
