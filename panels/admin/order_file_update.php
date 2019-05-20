<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
    
    $id = $request->GetInt('id');
	$type=$request->GetStr('type');
	$supplier_id=$request->GetInt('supplier_id');
	$limit=$request->GetInt('limit');
	$user_id=$request->GetInt('user_id');
	$ip=$request->GetStr('ip');
	
	$pString='';
	if($supplier_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($ip != '')
	{
		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	$pString = trim($pString, '&');
	
	
	$sql = 'select
					slm.reply_type
				from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
				left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
				where ofsm.id=' . $id;
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$reply_type = $rows[0]['reply_type'];
?>
	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Orders'); ?></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=<?php echo $type; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_orders')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Update'); ?></li>
			</ul>
		</div>
	</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file_update_process.do" enctype="multipart/form-data" method="post">
    <input name="id" type="hidden" id="id" value="<?php echo $id;?>" />
    <input type="hidden" name="type" value="<?php echo $type;?>">
	<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
	<input type="hidden" name="ip" value="<?php echo $ip;?>">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
	<fieldset>
    <legend></legend>
	
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_file_service_order')); ?></div>
				<div class="panel-body">
					
					<?php if($reply_type == 1) {?>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_file')); ?> </label>
						<input name="file" type="file" id="file" value="" />
					</div>
					<?php } else {?>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlock_code')); ?> </label>
						<textarea name="code" class="form-control" rows="5" id="code"></textarea>
					</div>
					<?php }?>
					<div class="form-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN?>order_file.html?type=<?php echo $type; ?>" class=" btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success btn-sm" />
					</div>
				</div> <!-- / panel-body -->
 <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

 </form>
