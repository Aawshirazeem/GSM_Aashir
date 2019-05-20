<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('config_reseller_edit_1488855448');

	$id = $request->GetInt('id');

	
	$sql ='select * from ' . RESELLER_MASTER . ' where id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_reseller.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li><?php $lang->prints('lbl_masters'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_reseller.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_resellers')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_reseller')); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_reseller_edit_process.do" method="post">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_reseller')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> </label>
						<input name="reseller" type="text" class="form-control" id="reseller" value="<?php echo $row['reseller']?>" />
						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />
					</div>
					<div class="form-group">
						<label class="checkbox-inline"><input type="radio" name="type" value="0" <?php echo (($row['type'] == '0') ? 'checked="checked"' : '');?> /> <i class="icon-globe"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_worldwide_distributer')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="type" value="1" <?php echo (($row['type'] == '1') ? 'checked="checked"' : '');?> /> <i class="icon-user"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_distributer')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="type" value="2" <?php echo (($row['type'] == '2') ? 'checked="checked"' : '');?> /> <i class="icon-group"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?></label>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?> </label>
						<select name="country" class="form-control" id="country">
						<option value=""> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?> </option>
						<?php
							$sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
							$query_country = $mysql->query($sql_country);
							$rows_country = $mysql->fetchArray($query_country);
							foreach($rows_country as $row_country)
							{
								echo '<option ' . (($row_country['id'] == $row['country']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?> </label>
						<input name="address" type="text" class="form-control" id="address" value="<?php echo $row['address']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?> </label>
						<input name="email" type="text" class="form-control" id="email" value="<?php echo $row['email']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?> </label>
						<input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $row['mobile']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?> </label>
						<input name="phone" type="text" class="form-control" id="phone" value="<?php echo $row['phone']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website')); ?> </label>
						<input name="website" type="text" class="form-control" id="website" value="<?php echo $row['website']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_yahoo')); ?> </label>
						<input name="yahoo" type="text" class="form-control" id="yahoo" value="<?php echo $row['yahoo']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_msn')); ?> </label>
						<input name="msn" type="text" class="form-control" id="msn" value="<?php echo $row['msn']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_skype')); ?> </label>
						<input name="skype" type="text" class="form-control" id="skype" value="<?php echo $row['skype']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_icq')); ?> </label>
						<input name="icq" type="text" class="form-control" id="icq" value="<?php echo $row['icq']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_sonork')); ?> </label>
						<input name="sonork" type="text" class="form-control" id="sonork" value="<?php echo $row['sonork']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_account_status')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> > <i class="icon-ok"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> > <i class="icon-remove"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_reseller.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_reseller')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
  </form>
