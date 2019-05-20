<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_faq_edit_45609809');

	$id = $request->GetInt('id');
	
	$sql ='select * from ' . IMEI_FAQ_MASTER . ' where id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_faq.html?reply=" . urlencode('reply_success'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_faq.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_FAQ_master')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Setting'); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_faq_edit_process.do" method="post">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_faq_details')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_question')); ?> </label>
						<div class="input-group">
							<input name="question" type="text" class="form-control" id="question" value="<?php echo $row['question']?>" />
							<span class="input-group-addon">?</span>
						</div>

						<input name="id" type="hidden" class="form-control" id="id" value="<?php echo $row['id']?>" />
						<input name="filetype" type="hidden" class="form-control" id="filetype" value="html" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_answer')); ?> </label>
						<textarea class="form-control" name="answer" id="answer" rows="3"><?php echo $row['answer']?></textarea>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> </label>
						<label class="checkbox-inline"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
						<label class="checkbox-inline"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
					</div>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_mep.html?group_id=<?php echo $group_id;?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_mep')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
	
</form>
