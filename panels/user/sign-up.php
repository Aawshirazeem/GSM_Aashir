<?php
defined("_VALID_ACCESS") or die("Restricted Access");
if ($member->isLogedIn()) {
    ob_clean();
    header('location:' . CONFIG_PATH_SITE_USER . 'dashboard.html');
    exit();
}




$sql_2 = 'select a.value,a.field,a.value_int finfo from '.CONFIG_MASTER.' a
where a.field in ("USER_NOTES","ADMIN_NOTES","TER_CON")
order by a.id';
//echo $sql_2;
$query_2 = $mysql->query($sql_2);
$rows_2 = $mysql->fetchArray($query_2);
$ter_con_of=$rows_2[2]['finfo'];
$urll=$rows_2[2]['value'];
//echo $urll;exit;
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
<div class="wrapper-page">
    <div id="signupbox" style="" class="card-box">
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
                
                 <?php
        $sql_timezone1 = 'select * from ' . SMTP_CONFIG;
$query_timezone1 = $mysql->query($sql_timezone1);
$rows_timezone1 = $mysql->fetchArray($query_timezone1);
//echo '<pre>';
//var_dump($rows_timezone1);exit;
//echo $rows_timezone1[0]["show_price"];exit;


if ($rows_timezone1[0]['is_custom'] == 1) {
      
            
            echo '<div id="c_fields">';
            // show some custom fields now
            $sql1 = 'select * from nxt_custom_fields a where a.s_type=3 and a.s_id=3';
            $result = $mysql->getResult($sql1);
            $f_temp = 1;
            foreach ($result['RESULT'] as $row2) {

                if ($row2['f_type'] == 1) {
                    // text box
                   // echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo ' <input type="text" name="custom_' . $f_temp . '"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="' . $row2['f_desc'] . '' . (($row2['f_req'] == 1) ? '*' : '') . '"/><br>';
                }
                if ($row2['f_type'] == 2) {

                    $ops = $row2['f_opt'];
                    $ops = explode(',', $ops);
                    // text box
                   // echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<select ' . (($row2['f_req'] == 1) ? 'required' : '') . ' class="form-control"  name="custom_' . $f_temp . '">';
                    echo ' <option value="">' . $row2['f_desc'] . '' . (($row2['f_req'] == 1) ? '*' : '') . '</option>';
                    for ($a = 0; $a < count($ops); $a++) {
                        echo ' <option value="' . $ops[$a] . '">' . $ops[$a] . '</option>';
                    }

                    echo '</select><br>';
                    //echo  ' <input type="text" name="custom_1"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="'.$row2['f_desc'].'"/>';
                }
                
                if($row2['f_type'] == 3)
                {
                    echo '<br><label>' . $row2['f_name'] . '' . (($row2['f_req'] == 1) ? '*' : '') . '</label><br>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<label>' . $row2['f_desc'] . '</label>';
                    echo ' <input type="checkbox" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' name="custom_' . $f_temp . '"/><br>';
                }
                $f_temp++;
            }
            echo '</div>';
        }
        
        ?>

                
                
                
                
                <div class="form-group">

                    <div class="col-xs-12">
                        <img src="<?php echo CONFIG_PATH_SITE; ?>captcha_image.do?rand=<?php echo rand(); ?>" id='captchaimg'>
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_code')) ?>*<br />
                            <u><a href='javascript: refreshCaptcha();'><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Cannot_read_the_image_click_here_to_refresh')) ?></a></u></label>
                        <input name="captchaCode" type="text" class="form-control required" id="captchaCode" required placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Captcha')) ?>*" />
                    
                    
                    <?php if($ter_con_of==1){?>
                        
                        <div class="checkbox checkbox-primary">
                            <input id="termcon" name="termcon" value="1" type="checkbox" required="">
                            <label for="termcon">
                            <?php echo $admin->wordTrans($admin->getUserLang(),'I have read and agreed to the'); ?> <a target="_blank" href="<?php echo $urll;?>"><?php echo $admin->wordTrans($admin->getUserLang(),'Terms of Service'); ?></a></label>
                        </div>
                        
                    <?php } ?>
                    
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-xs-12">
                        <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')) ?></button>
                        <a class="btn btn-inverse btn-block text-uppercase waves-effect waves-light"  id="signinlink" href="<?php echo CONFIG_PATH_SITE_USER; ?>index.html" ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Go_To_Login_Page')); ?></a>
                    </div>

                </div>

       


            </form>
        </div>
    </div></div>

<div class="CL"></div>
<div id="wrapper">
    <div class="text-center text-muted MT10">

        <p class="wow fadeInUp" style=" color: black"><?php echo "Copyright  &#169; " . date('Y') . " GSM UNION"; ?>. <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights_reserved')); ?>.</p>
    </div>
</div>

<script type='text/javascript'>
    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    }
</script> 