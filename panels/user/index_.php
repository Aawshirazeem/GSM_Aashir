<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	if($member->isLogedIn())
	{
		ob_clean();
		header('location:' . CONFIG_PATH_SITE_USER . 'dashboard.html');
		exit();
	}
?>
	<form action="<?php echo CONFIG_PATH_SITE_USER; ?>login_process.do" method="post" class="login-form">
        <h2 class="form-signin-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_login')); ?></h2>
        <div class="login-wrap">
			<input type="text" class="form-control input-lg" name="username" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?>" autofocus>
			<input type="password" class="form-control input-lg" name="password" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?>">
            <label class="checkbox">
                <input type="checkbox" value="1" name="stay_signed_in"> <?php echo $admin->wordTrans($admin->getUserLang(),'Remember me'); ?>
				<span class="pull-right"><a href="#" id="forgot_password"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_forgot_password')); ?></a></span>
            </label>
            <button class="btn btn-login btn-block" type="submit"><?php echo $admin->wordTrans($admin->getUserLang(),'Sign in'); ?></button>
        </div>
	</form>
	

	
<div class="CL"></div>
<div id="wrapper">
	<div class="text-center text-muted MT10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_copyright')); ?> <?php echo CONFIG_SITE_TITLE;?>. <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights_reserved')); ?>.</div>
</div>

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
	  <div class="modal-dialog">
		  <div class="modal-content">
			  <div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="icon-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
			  </div>
			  <div class="modal-body">
				<form action="<?php echo CONFIG_PATH_SITE_USER; ?>password_recover_process.do" method="post" class="noAutoLoad">
				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_forgot_password')); ?>
				<div class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
					<input name="username" type="text" class="form-control" id="username" />
					<p class="help-bock"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_your_username_to_recover_password_via_email')); ?></p>
				</div>
				<div class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>
					<input name="email" type="text" class="form-control" id="email" />
					<p class="help-bock"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_registered_with_the_above_username')); ?></p>
				</div>
				<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_get_password_via_e-mail')); ?>" class="btn btn-success"/>
			  </div>
		  </div>
	  </div>
	</div>
</form>

</div>