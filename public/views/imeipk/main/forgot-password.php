<div class="bg_blue">
	<div class="container">
	
		<h3 class="wow fadeInDown" data-wow-delay="0.2s"><?php $lang->prints('lbl_recover_password')?></h3>
        <div class="thumb-pad9 wow fadeInUp" data-wow-delay="0.1s">
            <div class="row contact-wrap"> 
                <div class="status alert alert-success" style="display: none"></div>
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>password_recover_process.do" method="post" class="noAutoLoad">
                    <div class="col-sm-4 col-sm-offset-4">
                    <?php
                        $reply = $request->getStr('reply');
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
                        }
                        include("_message.php");
                    ?>
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" class="form-control" required="required" autofocus>
                            <p class="help-bock"><?php $lang->prints('lbl_enter_your_username_to_recover_password_via_email'); ?></p>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input name="email" type="text" class="form-control" id="email" />
                            <p class="help-bock"><?php $lang->prints('lbl_email_registered_with_the_above_username'); ?></p>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="<?php $lang->prints('lbl_get_password_via_e-mail'); ?>" class="btn btn-success"/>
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
