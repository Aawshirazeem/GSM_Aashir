<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('config_reseller_add_148148548');
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_reseller.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_resellers')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Add New Resellers'); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_reseller_add_process.do" method="post">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_reseller')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> </label>
						<input name="reseller" type="text" class="form-control" id="reseller" value="" />
					</div>
					<div class="form-group">
						<label class="checkbox-inline"><input type="radio" name="type" value="0" checked="checked" /> <i class="icon-globe"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_worldwide_distributer')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="type" value="1" /> <i class="icon-user"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_distributer')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="type" value="2" /> <i class="icon-group"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?></label>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?> </label>
						<select name="country" class="form-control" id="country">
							<option value=""> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?>  </option>
							<?php
								$sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
								$query_country = $mysql->query($sql_country);
								$rows_country = $mysql->fetchArray($query_country);
								foreach($rows_country as $row_country)
								{
									echo '<option value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?> </label>
						<input name="address" type="text" class="form-control" id="address" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?> </label>
						<input name="email" type="text" class="form-control" id="email" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?> </label>
						<input name="mobile" type="text" class="form-control" id="mobile" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?> </label>
						<input name="phone" type="text" class="form-control" id="phone" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website')); ?> </label>
						<input name="website" type="text" class="form-control" id="website" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_yahoo')); ?> </label>
						<input name="yahoo" type="text" class="form-control" id="yahoo" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_msn')); ?> </label>
						<input name="msn" type="text" class="form-control" id="msn" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_skype')); ?> </label>
						<input name="skype" type="text" class="form-control" id="skype" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_icq')); ?> </label>
						<input name="icq" type="text" class="form-control" id="icq" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_sonork')); ?> </label>
						<input name="sonork" type="text" class="form-control" id="sonork" value="" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_account_status')); ?> </label>
						<label class="checkbox-inline"><input type="radio" name="status" value="1" checked="checked"> <i class="icon-ok"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="0"> <i class="icon-remove"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_reseller.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_reseller')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
  </form>
