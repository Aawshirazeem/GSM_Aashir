<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_network_add_5445622h2');
	$country_id = $request->GetInt('country_id');
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			     <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_countries.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_country_manager')); ?></a></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_networks.html?country_id=<?php echo $country_id; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Networks')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_network')); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_networks_add_process.do" method="post">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_network')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_network_name')); ?> </label>
						<input name="network" type="text" class="form-control" id="network" value="" />
						<input name="country_id" type="hidden" id="country_id" value="<?php echo $country_id;?>" />
						<input name="filetype" type="hidden" id="filetype" value="html" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </label>
						<label class="checkbox-inline"><input type="radio" name="status" value="1" checked="checked"/><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="0" /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
					</div>
				<div class="form-group">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_networks.html?country_id=<?php echo $country_id; ?>" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
					<input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_network')); ?>" />
				</div>
				</div> <!-- / panel-body -->
				 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
</form>
