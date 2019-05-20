<?php
defined("_VALID_ACCESS") or die("Restricted Access");

//$validator->formSetAdmin('user_add_59905855d2');



$id = $request->GetInt("id");



$admin_email = '';

$support_email = '';

$system_email = '';

$system_from = '';

$admin_signature = '';





$type = '';
$enc_type='';

$smtp_port = '';

$smtp_host = '';

$username = '';

$password = '';

$test_email = '';





$success_flag = false;



if (count($_REQUEST) > 0) {

    $admin_email = $request->PostStr('admin_email');

    $support_email = $request->PostStr('support_email');

    $system_email = $request->PostStr('system_email');

    $system_from = $request->PostStr('system_from');

    $admin_signature = $request->PostStr('admin_signature');



    $type = $request->PostStr('type');
    $enc_type=$request->PostInt('enc_type');;

    $smtp_port = $request->PostStr('smtp_port');

    $smtp_host = $request->PostStr('smtp_host');

    $username = $request->PostStr('username');

    $password = $request->PostStr('password');

    $test_email = $request->GetStr('test_email');
}



$smtp_id = $request->PostInt('reg_id');

if (isset($smtp_id) && $smtp_id != '') {

    $sql = 'UPDATE ' . SMTP_CONFIG . '  SET

		admin_email = ' . stripslashes($mysql->quote($admin_email)) . ', 

		support_email = ' . stripslashes($mysql->quote($support_email)) . ',

		system_email = ' . stripslashes($mysql->quote($system_email)) . ',

		system_from = ' . stripslashes($mysql->quote($system_from)) . ',

		admin_signature = "' . (($admin_signature)) . '",

		

		type = ' . stripslashes($mysql->quote($type)) . ',
                    enc_type = ' . $mysql->getInt($enc_type) . ',

		smtp_port = ' . $mysql->getInt($smtp_port) . ',

		smtp_host = ' . stripslashes($mysql->quote($smtp_host)) . ',

		username = ' . stripslashes($mysql->quote($username)) . ',

		password = ' . stripslashes($mysql->quote($password)) . ',

		last_updated_by = ' . $admin->getUserId() . ' 

		WHERE

		id = ' . $smtp_id;



    $success_flag = $mysql->query($sql);
} else if (count($_POST) > 0) {

    $sql = 'insert into ' . SMTP_CONFIG . '

					(entry_date,admin_email,support_email,system_email,system_from, admin_signature,type,enc_type, smtp_port, smtp_host, username, password, created_by,last_updated_by)

					values(

					 now(),

					 ' . stripslashes($mysql->quote($admin_email)) . ',

					 ' . stripslashes($mysql->quote($support_email)) . ',

					 ' . stripslashes($mysql->quote($system_email)) . ', 

					 ' . stripslashes($mysql->quote($system_from)) . ', 

					 "' . (($admin_signature)) . '", 

					 

					' . stripslashes($mysql->quote($type)) . ',
                                            ' . $mysql->getInt($enc_type) . ',

					' . $mysql->getInt($smtp_port) . ',

					' . stripslashes($mysql->quote($smtp_host)) . ', 

					' . stripslashes($mysql->quote($username)) . ',

					' . stripslashes($mysql->quote($password)) . ',

					' . $admin->getUserId() . ',' . $admin->getUserId() . ')';



    $success_flag = $mysql->query($sql);



    if ($success_flag) {

        $id = $mysql->insert_id();
    }
}

if ($test_email == 'Y') {

    if ($success_flag) {

        $objEmail = new email();

        $email_config = $objEmail->getEmailSettings();

        $objEmail->setTo($email_config['admin_email']);

        $objEmail->setFrom($email_config['system_email']);

        $objEmail->setFromDisplay($email_config['system_from']);

        $objEmail->setSubject($email_config['system_from'] . " - TEST EMAIL ");



        $signatures = "<br /><br /><br /><br />" . nl2br($admin_signature);



        $objEmail->setBody('This is a test email from ' . $email_config['system_from'] . $signatures);

        if ($objEmail->sendMail()) {

            header("location:" . CONFIG_PATH_SITE_ADMIN . "smtp_settings.html?reply=" . urlencode('reply_email_sent'));

            exit();
        } else {

            header("location:" . CONFIG_PATH_SITE_ADMIN . "smtp_settings.html?reply=" . urlencode('reply_email_not_sent'));

            exit();
        }
    }
} else if ($success_flag && !$test_email) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "smtp_settings.html?reply=" . urlencode('reply_data_saved'));

    exit();
}

//if($id != 0)
//{
//$sql = 'select * from ' . SMTP_CONFIG . ' where id=' . $mysql->getInt($id);

$sql = 'select * from ' . SMTP_CONFIG . ' limit 1';

$query = $mysql->query($sql);

if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    $id = $rows[0]['id'];

    $admin_email = $rows[0]['admin_email'];

    $support_email = $rows[0]['support_email'];

    $system_email = $rows[0]['system_email'];

    $system_from = $rows[0]['system_from'];

    $admin_signature = $rows[0]['admin_signature'];



    $type = $rows[0]['type'];
    $enc_type=$rows[0]['enc_type'];

    $smtp_port = $rows[0]['smtp_port'];

    $smtp_host = $rows[0]['smtp_host'];

    $username = $rows[0]['username'];

    $password = $rows[0]['password'];
}

//}
?>
<div class="row m-b-20">
    <div class="col-lg-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SMTP_Settings')); ?></li>
        </ol>
    </div>
</div>
<div class="row m-b-20">
	<div class="col-lg-12" style=" background-color: rgba(0, 188, 212, 0.18);height: 150px">
    	<br>
        <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_To_Enable_Automated_Email_Notifications,_Please_make_sure_you_setup_a_cron_job_to_run_Every_Minute._Create_the_following_Cron_Jobs_Using_Cpanel_of_Your_site')); ?><br> 
            
        
            
            <hr>
        </label>
        
        <?php
            //echo "<br><be>";
            $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/mail_que.php >/dev/null 2>&1</pre>';
           // $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php</pre>';
            echo $link;
        ?>
    </div>
</div>
<form action="" method="post" class="frmValidate" id="smtp_form">
	<input name="reg_id" type="hidden" id="reg_id" value="<?php echo $id; ?>" />
    <input name="test_email" type="hidden" id="test_email" value="N" />
    <div class="row">
	    <div class="col-lg-12"><h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_smtp_details')); ?></h4></div>
    	<div class="col-lg-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ADMIN_EMAIL')); ?></label>
                        <input name="admin_email" type="email" class="form-control checkUserName required" data-msg-required="Please enter admin email" id="admin_email" value="<?php echo $admin_email; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SUPPORT_EMAIL')); ?></label>
                        <input name="support_email" type="email" class="form-control checkUserName required" data-msg-required="Please enter support email" id="support_email" value="<?php echo $support_email; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SYSTEM_EMAIL')); ?></label>
                        <input name="system_email" type="email" class="form-control checkUserName required" data-msg-required="Please enter system email" id="system_email" value="<?php echo $system_email; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SYSTEM_FROM')); ?></label>
                        <input name="system_from" type="text" class="form-control checkUserName required" data-msg-required="Please enter system from" id="system_from" value="<?php echo $system_from; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ADMIN_SIGNATURE')); ?></label>
                        <textarea name="admin_signature" type="text" class="form-control checkUserName required" data-msg-required="Please enter admin signature" id="admin_signature" required ><?php echo $admin_signature; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mail_Type')); ?></label>
                        <select name="type" class="form-control" id="type">
                            <option value="S">SMTP</option>
                            <!-- <option value="G">GMAIL</option> -->
                        </select>
                    </div>
                </div>	
            </div>
			
			
		</div>
		<div class="col-lg-6">
			
			
			
			
			
                 <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_encryption_Type')); ?></label>
                        <select name="enc_type" class="form-control" id="type">
                            <?php if($enc_type==1)
                                echo ' <option value="1" selected>SSL</option>';
                                else
                              echo ' <option value="1">SSL</option>';
                                
                                if($enc_type==2)
                                    echo '<option value="2" selected>TLS</option>';
                                else
                                    echo '<option value="2">TLS</option>';
                           
                             
                           ?>
                        </select>
                    </div>
                </div>	
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SMTP_PORT')); ?></label>
                        <input name="smtp_port" type="text" class="form-control checkUserName required" data-msg-required="Please enter smtp port" id="smtp_port" value="<?php echo $smtp_port; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SMTP_HOST')); ?></label>
                        <input name="smtp_host" type="text" class="form-control checkUserName required" data-msg-required="Please enter smtp host" id="smtp_host" value="<?php echo $smtp_host; ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="username"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
                        <input name="username" type="text" class="form-control checkUserName required" data-msg-required="Please enter username" id="username" value="<?php echo $mysql->prints($username); ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="password"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>
                        <input name="password" type="password" class="form-control required" id="password" value="<?php echo $password; ?>" required />
                    </div>
                    <!-- 
                    <div class="col-sm-6">
                            <label for="password"><?php //$lang->prints('lbl_password_confirm');  ?></label>
                            <input name="password2" equalto="#password" type="password" class="form-control required" id="password2" value="<?php //echo $password; ?>" required />
                            <p class="help-block"><?php //$lang->prints('lbl_reenter_password');  ?></p>
                    </div>
                    -->
                </div>
            </div>
            <div class="form-group">
                 <!-- <a href="<?php //echo CONFIG_PATH_SITE_ADMIN;  ?>users.html" class="btn btn-danger">Cancel</a> -->
                <button type="submit" formaction="" class="btn btn-success formSubmit"><i class="fa fa-check"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_save')); ?></button>
                <button type="submit" formaction="?test_email=Y" class="btn btn-success formSubmit"><i class="fa fa-check"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Save_and_Test_Email')); ?></button>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">

    function smtpValidate()

    {

        //alert(obj)

        /*$("#test_email").val(test_email);
         
         if(confirm("Do you want to submit this form?"))
         
         {
         
         $('#smtp_form').submit();
         
         return true;
         
         }*/

        //return false;

    }

</script>