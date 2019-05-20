<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	if($supplier->isLogedin())
	{
		header('location:' . CONFIG_PATH_SITE_SUPPLIER . 'dashboard.html');
		exit();
	}
?>

<br /><br />
<div style="width:500px; text-align:center; margin:10px auto; padding:0px 0px 0px 0px;">
	<img src="<?php echo CONFIG_PATH_SITE;?>images/logo_panel.png" align="middle" ><br /><br />
	<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>login_process.do" method="post" class="form-signin">
		<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_login')); ?></h1>
		<div class="form-group">
			<input placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?>" name="username" type="text" class="form-control" id="username" style="border-bottom:0px;" />
		</div>
		<div class="form-group">
			<input placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password')); ?>" name="password" type="password" class="form-control" id="password" />
		</div>
		<input type="checkbox" value="1" name="stay_signed_in" class="checkbox-inline"/> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sign_in')); ?>
		<div class="panel-footer">
			<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login')); ?>" class="btn btn-lg btn-primary btn-block" />
		</div>
	</form>
</div>
<div class="CL"></div>
<div class="text_11 text_gray TA_C PT5"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_copyright')); ?> <?php echo CONFIG_SITE_TITLE;?>. <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights_reserved')); ?>.</div>