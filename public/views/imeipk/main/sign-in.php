<div class="bg_blue">
	<div class="container">
	
		<h3 class="wow fadeInDown" data-wow-delay="0.2s"><?php $lang->prints('lbl_login')?></h3>
        <div class="thumb-pad9 wow fadeInUp" data-wow-delay="0.1s">
            <div class="row contact-wrap"> 
                <div class="status alert alert-success" style="display: none"></div>
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>login_process.do" method="post" class="form-signin">
                    <div class="col-sm-4 col-sm-offset-4">
                    <?php
                        $reply = $request->getStr('reply');
                      //  echo $reply;
                        $msg = '';
                        switch($reply){
                            case 'reply_invalid_login':
                                $msg = 'Invalid login details';
                                break;
                            case 'reply_logut_success':
                                $msg = 'Logout successfull.';
                                break;
                            case 'reply_invalid_lp':
                                $msg = 'Invalid IP';
                                break;
                             case 'reply_admin_accept_wait':
                                $msg = 'Contact To Admin For Your Account Activation!';
                                break;
                        }
                        include("_message.php");
                    ?>
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" class="form-control" required="required" autofocus>
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-danger btn-lg" required="required">Log in</button>
                            <span class="help-block"><a href="<?php echo CONFIG_PATH_SITE;?>forgot-password.html" id="forgot_password"><?php $lang->prints('lbl_forgot_password'); ?></a></span>
                        </div>

                    </div>
                    
                </form>
            </div><!--/.row-->
        </div>
	</div>
</div>

<script type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script> 
