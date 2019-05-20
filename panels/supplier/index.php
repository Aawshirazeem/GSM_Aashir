<?php
defined("_VALID_ACCESS") or die("Restricted Access");
if ($supplier->isLogedin()) {
    header('location:' . CONFIG_PATH_SITE_SUPPLIER . 'dashboard.html');
    exit();
}
?>
<?php /*?><div class="fullsize-background-image-1">
	<img src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/login_bg.png">
</div><?php */?>
<div class="container-fluid loginBackColor">
	<div class="row">
    	<div class="col-xs-12">
        	<div class="login-page text-center animated fadeIn delay-2000">
				<?php /*?><h1> Sign In to <strong class="text-custom"><?php echo CONFIG_SITE_NAME; ?></strong> </h1><?php */?>
                <h1> <img class="" src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/logo_gsm.png" style="width:300px;"> </h1> 
                <h4> <?php echo $admin->wordTrans($admin->getUserLang(),'Please enter your email address and password to login');?> </h4>
                <div class="row">
                	<div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                        <form class="form" action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>login_process.do" method="post" novalidate>
                        	<div class="row">
                            	<div class="col-xs-12">
                                	<div class="form-group floating-labels">
                                    	<label for="email"><?php  echo $admin->wordTrans($admin->getUserLang(),'Username'); ?></label>
                                        <input id="email" name="username" type="text" required="" autofocus>
                                        <p class="error-block"></p>
                                    </div>
                                </div>
							</div>
							<div class="row m-b-40">
								<div class="col-xs-12">
									<div class="form-group floating-labels">
										<label for="password"><?php  echo $admin->wordTrans($admin->getUserLang(),'Password'); ?></label>
										<input id="password" type="password" name="password" required="">
										<p class="error-block"></p>
									</div>
								</div>
							</div>                           

							<div class="row buttons">
                            	<div class="col-xs-12 col-md-6">
                                	<label class="c-input c-checkbox">
                                    	<input type="checkbox" id="checkbox-signup" value="remember-me">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),'Remember me'); ?></span>
                                    </label>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                	<input type="submit" class="btn-login btn btn-lg btn-info btn-block m-b-20" value="<?php echo $admin->wordTrans($admin->getUserLang(),'Login'); ?>">
                                </div>
							</div>
						</form>
					</div>
				</div>
				<p class="copyright text-sm">&copy; <?php echo $admin->wordTrans($admin->getUserLang(),'Copyright'); ?> 2016</p>
			</div>
		</div>
	</div>
</div><!-- global scripts -->

<div class="text-center MT10 site-oPartal">
	<a class="" href="<?php echo CONFIG_PATH_SITE; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'Online Portal'); ?></a>
</div>

<div class="text-center MT10 site-name">
	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_copyright')); ?> <?php echo CONFIG_SITE_TITLE; ?>. <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights')); ?>.
</div>