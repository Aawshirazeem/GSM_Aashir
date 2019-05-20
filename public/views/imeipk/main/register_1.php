<div class="bg_blue">
    <div class="container">

        <h3 class="wow fadeInDown" data-wow-delay="0.2s"><?php $lang->prints('lbl_create_account') ?></h3>
        <div class="thumb-pad9 wow fadeInUp" data-wow-delay="0.1s">
            <?php
            $reply = $request->getStr('reply');
            $msg = '';
            switch ($reply) {
                case 'invalid_verification_code':
                    $msg = 'Invalid verification code!';
                    break;
                case 'name_missing':
                    $msg = 'Your name is missing.';
                    break;
                case 'password_missing':
                    $msg = 'Password can\'t be blank';
                    break;
                case 'username_missing':
                    $msg = 'Username can\'t be blank';
                    break;
                case 'email_missing':
                    $msg = 'Email can\'t be blank';
                    break;
                case 'duplicate_username':
                    $msg = 'Usename is already registered with us. Please try again!';
                    break;
                case 'duplicate_email':
                    $msg = 'Email is already registered with us. Please try again!';
                    break;
                case 'duplicate_email_username':
                    $msg = 'Email and Username is already registered with us. Please try again!';
                    break;
                case 'thanks':
                    $msg = 'You are registered successfully';
                    break;
            }
            include("_message.php");
            ?>
            <form action="<?php echo CONFIG_PATH_SITE; ?>register_process.do" method="post" name="frm_reg" id="frm_reg" class="formSkin frmValidate">
                <p class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_first_name') ?>*</label>
                        <input name="first_name" type="text" class="form-control required" required id="first_name" />
                    </div>
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_last_name') ?></label>
                        <input name="last_name" type="text" class="form-control" id="last_name"  />
                    </div>
                </div>
                </p>
                <p class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_username') ?>*</label>
                        <input name="username" type="text" class="form-control checkUserName {required:true, messages:{required:'Please enter username'}}" id="username" required />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="usernameWait" />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/ok_16.png" alt="Available" height="16" width="16" class="hidden" id="usernameAvail" />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/cross_16.png" alt="Not Available" height="16" width="16" class="hidden" id="usernameNotAvail" />
                    </div>
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_email') ?>*</label>
                        <input name="email" type="text" class="form-control checkEmail {required:true, email:true, messages:{required:'Please enter your email', email:'Please enter a valid email'}}" id="email" required="email" />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="emailWait" />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/ok_16.png" alt="Available" height="16" width="16" class="hidden" id="emailAvail" />
                        <img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/cross_16.png" alt="Not Available" height="16" width="16" class="hidden" id="emailNotAvail" />
                    </div>
                </div>
                </p>
                <p class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_password') ?>*</label>
                        <input name="password_new" type="password" class="form-control required" id="password_new" required />
                    </div>
                    <div class="col-sm-6">
                        <label><?php $lang->prints('lbl_confirm_password') ?>*</label>
                        <input name="password_confim" type="password" class="form-control required" id="password_confim" required />
                    </div>
                </div>
                </p>

                <p class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Country</label>
                        <select name="country_id" class="form-control" id="country_id">
                            <?PHP
                            echo $objHelper->getCountries(0);
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>City</label>
                        <input name="city" type="text" class="form-control required" id="city" required />
                    </div>
                </div>
                </p>

                <p class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Phone</label>
                        <input name="phone" type="text" class="form-control" id="phone" />
                    </div>

                </div>
                </p>


                <p class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="<?php echo CONFIG_PATH_SITE; ?>captcha_image.do?rand=<?php echo rand(); ?>" id='captchaimg'>
                        <label><?php $lang->prints('lbl_code') ?>*<br />
                            <u><a href='javascript: refreshCaptcha();'>Can't read the image? click here to refresh</a></u></label>
                        <input name="captchaCode" type="text" class="form-control required" id="captchaCode" required />
                    </div>
                    <div class="col-sm-8 text-right">
                        <br /><br /><br />
                        <input type="submit" value="<?php $lang->prints('lbl_create_account') ?>" class="btn btn-danger btn-lg" />
                        <span class="help-block">Already has a account <a href="<?php echo CONFIG_PATH_SITE; ?>sign-in.html">click here to login</a></span>
                    </div>
                </div>
                </p>
                <p class="form-group">
                </p>
                <p class="butSkin">

                </p>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript'>
    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    }
</script> 
