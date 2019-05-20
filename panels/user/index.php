<?php
defined("_VALID_ACCESS") or die("Restricted Access");
if ($member->isLogedIn()) {
    ob_clean();
    header('location:' . CONFIG_PATH_SITE_USER . 'dashboard.html');
    exit();
}
?>
<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
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
    case 'reply_inactive_login':
        $msg = 'User Blocked - Contact With Admin';
        break;

    // register page error

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
         case 'country_missing':
        $msg = 'County is Missing, Select A Your Country';
        break;
    case 'thanks':
        $msg = 'You are registered successfully';
        break;



    //forgot password 

    case 'reply_user_miss':
        $msg = 'Username Missing';
        break;
    case 'link_broken':
        $msg = 'Activation Link is Broken';
        break;
    case 'Invalid_Approach':
        $msg = 'Activation Link is Broken';
        break;
    case 'reply_account_blocked':
        $msg = 'Due to wrong password entries your account has been blocked, Contact To administrator for unblock your account';
        break;
    case 'reply_email_miss':
        $msg = 'Email Missing';
        break;
    case 'reply_pass_reset':
        $msg = 'Password Reset Successfully';
        break;
        break;
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
include("_message.php");
?>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="icon-remove"></i></button>
                <h4 class="modal-title"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_forgot_password')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>password_recover_process.do" method="post" class="noAutoLoad">

                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></label>
                        <input name="username" type="text" class="form-control" id="username" required="" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_your_username_to_recover_password_via_email')); ?>"  />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')); ?></label>
                        <input name="email" type="text" class="form-control" id="email" required="" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_registered_with_the_above_username')); ?>" />
                    </div>
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_get_password_via_e-mail')); ?>" class="btn btn-success"/>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-page">    



    <div class=" card-box" id="loginbox2">
        <div class="panel-heading"> 
            <h3 class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Sign_In_to')); ?> <strong class="text-custom"><?php echo CONFIG_SITE_NAME; ?></strong> </h3>
        </div> 
        <div class="panel-heading"> 
            <h4 class="text-center"><strong class="text-custom"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_Panel')); ?></strong> </h4>

        </div> 


        <div class="panel-body">
            <form class="form-horizontal m-t-20" id="loginform" role="form" action="<?php echo CONFIG_PATH_SITE_USER; ?>login_process.do" method="post">


                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" name="username" type="text" required="" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_your_username')); ?>" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" name="password" required="" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_your_account_password')); ?>">
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox-signup" type="checkbox" name="stay_signed_in" value="1">
                            <label for="checkbox-signup">
                                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Remember_me')); ?>
                            </label>
                        </div>
                        <div style="float:right; font-size: 80%; position: relative; top:-20px"><a href="#searchPanel" data-toggle="modal"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_forgot_password')); ?></a>

                        </div>
                    </div>

                    <div class="form-group text-center m-t-40">
                        <div class="col-xs-12">
                            <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Log_In')); ?></button>
                            <a class="btn btn-inverse btn-block text-uppercase waves-effect waves-light" href="<?php echo CONFIG_PATH_SITE_USER; ?>sign-up.html">
                                 <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Sign_Up_Here')); ?>
                            </a>
                        </div>
                    </div>
            </form> 

        </div>   
    </div>   
</div>

<!--login box ends here -->

<div id="signupbox" style="display:none;" class="card-box">
    <div class="panel-heading"> 
        <h3 class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Sign_Up_To')); ?> <strong class="text-custom"><?php echo CONFIG_SITE_NAME; ?></strong> </h3>
    </div> 
    <div class="panel-heading"> 
        <h4 class="text-center"><strong class="text-custom"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_Panel')); ?></strong> </h4>

    </div>  
    <div class="panel-body" >
        <form id="signupform" class="form-horizontal" role="form" action="<?php echo CONFIG_PATH_SITE_USER; ?>register_process.do" method="post">

            <div id="signupalert" style="display:none" class="alert alert-danger">
                <p><?php echo $admin->wordTrans($admin->getUserLang(),'Error'); ?>:</p>
                <span></span>
            </div>




            <div class="form-group">

                <div class="col-xs-12">
                    <input name="first_name" type="text" class="form-control" required id="first_name" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_first_name')) ?>*"/>
                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="last_name" type="text" class="form-control" id="last_name" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_last_name')) ?>*" />
                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="username" type="text" class="form-control" id="username" required  placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')) ?>*"/>
                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="email" type="email" class="form-control" id="email" required placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')) ?>*" />
                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="password_new" type="password" class="form-control required" id="password_new" required  placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password')) ?>*"/>

                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="password_confim" type="password" class="form-control required" id="password_confim" required placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_confirm_password')) ?>*" />

                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">

                    <select name="country_id" class="form-control" id="country_id">
                        <?PHP
                       // echo $objHelper->getCountries(0);
                        ?>
                        <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_country')); ?></option>
                            <?php
                            $sql_language = 'select * from ' . COUNTRY_MASTER;
                            $query_language = $mysql->query($sql_language);
                            $rows_language = $mysql->fetchArray($query_language);
                            foreach ($rows_language as $row_language) {
                                echo '<option value="' . $row_language['id'] . '">' . $row_language['countries_name'] . '</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            
              <div class="form-group">
                        <div class="col-xs-12">
                        <select name="language" class="form-control" id="language">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>
                            <?php
                            $sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';
                            $query_language = $mysql->query($sql_language);
                            $rows_language = $mysql->fetchArray($query_language);
                            foreach ($rows_language as $row_language) {
                                echo '<option  value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';
                            }
                            ?>
                        </select>
                        </div> </div>
            <div class="form-group">

                <div class="col-xs-12">

                    <select name="currency" class="form-control" id="currency" required>
                        <?php
                        $sql_currency = 'select * from ' . CURRENCY_MASTER . ' where `status`!=0 order by currency';
                        $query_currency = $mysql->query($sql_currency);
                        $rows_currency = $mysql->fetchArray($query_currency);
                        foreach ($rows_currency as $row_currency) {
                            echo '<option  value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="form-group">

                <div class="col-xs-12">
                    <select name="timezone" class="form-control" id="timezone">
                        <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                        <?php
                        $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
                        $query_timezone = $mysql->query($sql_timezone);
                        $rows_timezone = $mysql->fetchArray($query_timezone);
                        foreach ($rows_timezone as $row_timezone) {
                            echo '<option value="' . $row_timezone['id'] . '">' . $row_timezone['timezone'] . '</option>';
                            // echo '<option ' . (($row_timezone['id'] == $time_zone) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';
                        }
                        ?>
                    </select>
                </div></div>
            <div class="form-group">

                <div class="col-xs-12">
                    <input name="city" type="text" class="form-control required" id="city" required  placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')) ?>*"/>
                </div>
            </div>

            <div class="form-group">

                <div class="col-xs-12">
                    <input name="phone" type="text" class="form-control" id="phone"  placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Phone')) ?>*"/>
                </div>
            </div>
            <div class="form-group">

                <div class="col-xs-12">
                    <img src="<?php echo CONFIG_PATH_SITE; ?>captcha_image.do?rand=<?php echo rand(); ?>" id='captchaimg'>
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_code')) ?>*<br />
                        <u><a href='javascript: refreshCaptcha();'><?php $lang->prints('lbl_Cannot_read_the_image_click_here_to_refresh') ?></a></u></label>
                    <input name="captchaCode" type="text" class="form-control required" id="captchaCode" required placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Captcha')) ?>*" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-xs-12">
                    <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')) ?></button>
                    <a class="btn btn-inverse btn-block text-uppercase waves-effect waves-light"  id="signinlink" href="#" onclick="$('#signupbox').hide();
                            $('#loginbox2').show()"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Go_To_Login_Page')) ?></a>
                </div>

            </div>





        </form>
    </div>
</div></div>

<div class="CL"></div>
<div id="wrapper">
    <div class="text-center text-muted MT10">

                                        <p class="wow fadeInUp" style=" color: black"><?php echo "Copyright  &#169; ".date('Y')." GSM UNION";  ?>. <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights_reserved')); ?>.</p>
    </div>
</div>

<script type='text/javascript'>
    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    }
</script> 