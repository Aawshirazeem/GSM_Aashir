<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('config_settting_15821611');



$sql_timezone1 = 'select * from ' . SMTP_CONFIG;

$query_timezone1 = $mysql->query($sql_timezone1);

$rows_timezone1 = $mysql->fetchArray($query_timezone1);

//echo '<pre>';

//var_dump($rows_timezone1);exit;

//echo $rows_timezone1[0]["show_price"];exit;





?>



<!--<div class="row">

    <div class="col-lg-12">

        <ul class="breadcrumb">

            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="active">Master</li>

            <li class="active">Website Configuration</li>

        </ul>

    </div>

</div>-->

<?php

if (!is_writable(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php")) {

    echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> Configuration file is not writable</div>';

}

?>

 

 

 <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings_process.do" method="post" enctype="multipart/form-data">

    <div class="row">

        <div class="col-md-8">

            <div class="">

                <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_theme_setting')); ?></h4>

                <div class="panel-body">

<!--                    <div class="form-group">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_status')); ?> </label>

                        <select class="form-control" name="CONFIG_REPAIR_MODE" id="CONFIG_REPAIR_MODE">

                            <option value="0" <?php echo (CONFIG_REPAIR_MODE == '0') ? 'selected="selected"' : ''; ?> >Online</option>

                            <option value="1" <?php echo (CONFIG_REPAIR_MODE == '1') ? 'selected="selected"' : ''; ?> >!!!Under Construction Mode!!!!</option>

                        </select>

                    </div>-->



<!--                    <div class="form-group">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_theme')); ?> </label>

                        <select class="form-control" name="CONFIG_THEME" id="CONFIG_THEME">

                            <?php

                            $dir = CONFIG_PATH_SITE_ABSOLUTE . 'public/views/';

                            if (defined("DEMO")) {

                                echo '<option value="theme1" selected="selected">Theme 1</option>';

                            } else {

                                if (is_dir($dir)) {

                                    if ($dh = opendir($dir)) {

                                        while (($file = readdir($dh)) !== false) {

                                            if ($file != "." && $file != '..' && $file != 'index.html' && $file != '.DS_Store' && $file != 'offline') {

                                                echo '<option value="' . $file . '" ' . ((trim(CONFIG_THEME, '/') == $file) ? 'selected="selected"' : '') . '>' . $file . '</option>';

                                            }

                                        }

                                        closedir($dh);

                                    }

                                }

                            }

                            ?>

                        </select>

                    </div>-->



                    <div class="form-group">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_theme')); ?> </label>

                        <select class="form-control" name="CONFIG_PANEL" id="CONFIG_PANEL">

                            

                            <option value="Dark" <?php echo ((CONFIG_PANEL == 'Dark/') ? 'selected="selected"' : ''); ?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Dark')); ?></option>

                            <option value="Light" <?php echo ((CONFIG_PANEL == 'Light/') ? 'selected="selected"' : ''); ?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Light')); ?></option>

                        </select>

                    </div>



<!--                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-4">

                                <label>Admin Panel</label>

                                <input type="text" class="form-control" name="CONFIG_PATH_SITE_ADMIN" id="CONFIG_PATH_SITE_ADMIN" value="<?php echo trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_ADMIN), '//') ?>" />

                            </div>

                            <div class="col-sm-4">

                                <label>Supplier Panel</label>

                                <input type="text" class="form-control" name="CONFIG_PATH_SITE_SUPPLIER" id="CONFIG_PATH_SITE_SUPPLIER" value="<?php echo trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_SUPPLIER), '//') ?>" />

                            </div>

                            <div class="col-sm-4">

                                <label>User Panel</label>

                                <input type="text" class="form-control" name="CONFIG_PATH_SITE_USER" id="CONFIG_PATH_SITE_USER" value="<?php echo trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_USER), '//') ?>" />

                            </div>

                        </div>

                    </div>-->





<!--                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-4">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_domain_name')); ?> </label>

                                <input type="text" class="form-control" name="CONFIG_DOMAIN" id="CONFIG_DOMAIN" value="<?php echo CONFIG_DOMAIN ?>" />

                            </div>

                            <div class="col-sm-4">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_title')); ?> </label>

                                <input type="text" class="form-control" name="CONFIG_SITE_TITLE" id="CONFIG_SITE_TITLE" value="<?php echo CONFIG_SITE_TITLE ?>" />

                            </div>

                            <div class="col-sm-4">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_name')); ?> </label>

                                <input type="text" class="form-control" name="CONFIG_SITE_NAME" id="CONFIG_SITE_NAME" value="<?php echo CONFIG_SITE_NAME ?>" />

                            </div>

                        </div>

                    </div>-->





<!--                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-4">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_administrator_email')); ?> </label>

                                <input type="text" class="form-control" name="CONFIG_EMAIL_ADMIN" id="CONFIG_EMAIL_ADMIN" value="<?php echo CONFIG_EMAIL_ADMIN ?>" />

                            </div>

                            <div class="col-sm-4">

                                    <label><?php //$lang->prints('lbl_notification_email');  ?> </label>

                                    <input type="text" class="form-control" name="CONFIG_EMAIL" id="CONFIG_EMAIL" value="<?php //echo CONFIG_EMAIL ?>" />

                            </div>

                            <div class="col-sm-4">

                                    <label><?php //$lang->prints('lbl_contact_email');  ?> </label>

                                    <input type="text" class="form-control" name="CONFIG_EMAIL_CONTACT" id="CONFIG_EMAIL_CONTACT" value="<?php echo CONFIG_EMAIL_CONTACT ?>" />

                            </div>

                        </div>

                    </div>-->



                    <!--<div class="form-group">

                            <div class="row">

                                    <div class="col-sm-4">

                                            <div class="alert alert-info">

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_username');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_USERNAME_ADMIN" id="GMAIL_USERNAME_ADMIN" value="<?php //echo GMAIL_USERNAME_ADMIN ?>" />

                                                    </div>

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_password');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_PASSWORD_ADMIN" id="GMAIL_PASSWORD_ADMIN" value="<?php //echo GMAIL_PASSWORD_ADMIN ?>" />

                                                    </div>

                                            </div>

                                    </div>

                                    <div class="col-sm-4">

                                            <div class="alert alert-info">

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_username');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_USERNAME" id="GMAIL_USERNAME" value="<?php //echo GMAIL_USERNAME ?>" />

                                                    </div>

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_password');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_PASSWORD" id="GMAIL_PASSWORD" value="<?php //echo GMAIL_PASSWORD ?>" />

                                                    </div>

                                            </div>

                                    </div>

                                    <div class="col-sm-4">

                                            <div class="alert alert-info">

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_username');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_USERNAME_CONTACT" id="GMAIL_USERNAME_CONTACT" value="<?php //echo GMAIL_USERNAME_CONTACT ?>" />

                                                    </div>

                                                    <div class="form-group">

                                                            <label><?php //$lang->prints('lbl_gmail_password');  ?> </label>

                                                            <input type="text" class="form-control" name="GMAIL_PASSWORD_CONTACT" id="GMAIL_PASSWORD_CONTACT" value="<?php //echo GMAIL_PASSWORD_CONTACT ?>" />

                                                    </div>

                                            </div>

                                    </div>

                            </div>

                    </div>-->







                  

                  



<!--                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-6">

                                <label>Order History Page Size<?php //$lang->prints('lbl_currency'); ?></label>

                                <input name="CONFIG_ORDER_PAGE_SIZE" type="text" class="form-control checkUserName required" data-msg-required="Please enter order history page size" id="smtp_port" value="<?php echo (defined('CONFIG_ORDER_PAGE_SIZE') ? CONFIG_ORDER_PAGE_SIZE : ''); ?>" required />

                            </div>

                        </div>

                    </div>-->



                    <div class="form-group hidden">

                        <div class="pull-right well"><img src="<?php echo CONFIG_PATH_IMAGES; ?>logo.png" /></div>

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company_logo')); ?> </label>

                        <input type="file" name="logo" id="logo" />

                    </div>

                    <div class="form-group">

                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />

                    </div>

                </div> <!-- / panel-body -->

                <!-- / panel-footer -->

            </div> <!-- / panel -->

        </div> <!-- / col-lg-6 -->

    </div> <!-- / row -->



</form>

