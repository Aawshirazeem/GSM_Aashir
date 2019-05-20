<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_countries_edit_509996512');

	$id = $request->GetInt('id');

	$sql ='select * from ' . IMEI_COUNTRY_MASTER . ' where id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_countries.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_countries_edit_process.do" method="post">
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_country_edit')); ?> </h1>
	<div class="btn-group">
		
	</div>
</div>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_country_details')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_country_name')); ?></label>
						<input name="country" type="text" class="textbox_fix" id="country" value="<?php echo $row['country']?>" />
						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> </label>
						<input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active_user')); ?>
						<input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?>
					</div>				
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_country')); ?>" />
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

</form>