<?php

defined("_VALID_ACCESS") or die("Restricted Access");

if ($admin->isLogedin()) {

    header('location:' . CONFIG_PATH_SITE_ADMIN . 'dashboard.html');

    exit();

}

$objPass = new password();

//echo $objPass->generate("admin");

$reply = $request->getStr('reply');

//  echo $reply;

$msg = '';

switch($reply){

	case 'reply_invalid_google_auth_code':

		$msg = 'Authenticator code is incorrect. please enter it again Correctly';

		break;

	case 'reply_invalid_google_auth_code':

        $msg = 'Authenticator code is incorrect. please enter it again Correctly';

        break;

    case 'reply_code_empty':

        $msg = 'Authenticator code cannot be empty';

        break;

}

if($msg != ""){

?>

<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

    	<div class="modal-content">

        	<div class="modal-header">

            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title"><?php echo CONFIG_SITE_NAME?></h4>

            </div>

            <div class="modal-body">

				<?php echo $msg;?>

            </div>

            <div class="modal-footer">

            	<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>

            </div>

        </div>

    </div>

</div>

<?php } ?>

<?php /*?><div class="fullsize-background-image-1">

	<img src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/login_bg.png">

</div><?php */?>

<style>
html [data-palette="palette-5"][data-layout="fullsize-background-image"]{
    background: #fff !important;
}
.loginFrm {
	border: 1px solid #dddddd;
	margin-bottom: 9px;
	border-radius: 7px;
	}
body .login-page .form-group.floating-labels input[type="text"], body .login-page .form-group.floating-labels input[type="password"], body .login-page .form-group.floating-labels input[type="email"]{
    border-bottom: 1px solid #ddd !important;
    color: #6B6B6B !important;
	line-height:0.1 !important;
}
.c-input-text {
	color: #888 !important;
	}
#email {
	background: white url("<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/login_username.png") no-repeat scroll right bottom;
	}
#password {
	background: white url("<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/login_password.png") no-repeat scroll right bottom;
	padding-top: 10px;
	}
.form-group.floating-labels {
	margin-bottom: 25px;
	}
</style>

<div class="container-fluid loginBackColor">

	<div class="row">

    	<div class="col-xs-12">

        	<div class="login-page text-center animated fadeIn delay-2000">

            	<?php /*?><h1> Sign In to <strong class="text-custom"><?php echo CONFIG_SITE_NAME; ?></strong> </h1><?php */?>
				
				<div class="col-lg-6 col-lg-offset-3">
					<div style="margin-bottom:225px" class="visible-lg"></div>
					<h1> <img class="" src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/gsmFusion-logo.jpg"> </h1>
				</div>
                <div class="col-lg-8">

                	<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-7 col-lg-4">

                        <form class="form" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>login_process.do" method="post">

                        	<div class="row">

                            	<div class="col-xs-12 loginFrm">

                                	<div class="form-group floating-labels">

                                    	<label for="email">Username</label>

                                        <input id="email" name="username" type="text" required="" autofocus>

                                        <p class="error-block"></p>

                                    </div>


									<div class="form-group floating-labels">

										<!-- <label for="password">Password</label> -->

										<input id="password" type="password" name="password" required="">

										<p class="error-block"></p>

									</div>

								</div>

							</div>

                            

							<div class="row buttons">

                            <div class="col-xs-12 col-md-6">

                             <label class="c-input c-checkbox">

                                 <input type="checkbox" id="checkbox-signup" value="remember-me" name="stay_signed_in"><span class="c-indicator c-indicator-warning"></span> <span class="c-input-text">  Remember me</span> 

                              </label>           

                            </div>

                            

								<div class="col-xs-12 col-md-6">

									<input type="submit" class="btn-login btn btn-md btn-success btn-block m-b-20" value="Login">

								</div>

							</div>

						</form>

					</div>

				</div>

			</div>

		</div>

	</div>

</div><!-- global scripts -->





<script>

jQuery(document).ready(function($){

	$("#myModal").modal();

});

</script>