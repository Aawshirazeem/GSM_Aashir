<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
		

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_key_33455gkgk5d2');
    $id = $request->GetInt('id');
    $user_id = $request->GetInt('user_id');
	
	
	$sql = 'select
					slm.reply_type
				from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
				left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
				where ofsm.id=' . $id;
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$reply_type = $rows[0]['reply_type'];
?>
<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_file_update_process.do" method="post" enctype="multipart/form-data">
	<div class="lock-to-top">
		<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_file_service_order')); ?></h1>
		<div class="btn-group">
			<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success btn-lg"/>
		</div>
	</div>
	
    <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $id;?>" />
    <input name="user_id" type="hidden" class="textbox_fix" id="user_id" value="<?php echo $user_id;?>" />
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_file_service_order')); ?></div>
				<div class="panel-body">
					<?php if($reply_type == 1) {?>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_file')); ?></label>
						<input name="file" type="file" class="form-control" id="file" value="" />
					</div>
					<?php } else {?>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unlock_code')); ?></label>
						<textarea name="code" class="form-control" rows="5" id="code"></textarea>
					</div>
					<?php }?>
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
					<button type="submit" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?></button>
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
	

  </form>
