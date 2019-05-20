<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_pass_chan_148148548');

$id = $request->GetInt('id');

$sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];
$adminnotify = $rows[0]['master_pin'];
$adminnotify2 = $rows[0]['master_pin'];

if ($adminnotify == 1)
    $adminnotify = 'checked';
else
    $adminnotify = '';
?>


<div class="lock-to-top">
    <h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_details')); ?></h3>
</div>



<div class="row">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_password')); ?></div>
            <form action="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_password_process.do" method="post">
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></label>
                        <input name="username" type="text" class="form-control checkUserName" id="username" value="<?php echo $row['username']; ?>" readonly />
                        <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_old_password')); ?></label>
                        <input name="password_old" type="password" class="form-control checkUserName" id="password_old" required />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_you_existing_password')); ?></p>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_new_password')); ?></label>
                        <input name="password" type="password" class="form-control checkUserName" id="password" required />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_enter_you_new_password')); ?></p>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reenter_new_password')); ?></label>
                        <input name="password2" type="password" class="form-control checkUserName" id="password2" required />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reenter_and_confirm_you_new_password')); ?></p>
                    </div>
                </div> <!-- / panel-body -->
                <div class="panel-footer">
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_password')); ?>" class="btn btn-primary"/>
                </div> <!-- / panel-footer -->            
            </form>
        </div> <!-- / panel -->
    </div> <!-- / col-lg-6 -->
    <div class="col-md-6">
        <div class="panel panel-default">

            <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_Master_Pin')); ?></div>
            <form action="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_master_pin.do" method="post">
                <div class="panel-body">
                    <p class="help-block">
					<?php echo $admin->wordTrans($admin->getUserLang(),'Master Pin is  best way to make your device recognizable for the system.');?>
                    <?php echo $admin->wordTrans($admin->getUserLang(),'When your turn on this option then everytime when you log in form a different device or browser system will ask you to enter that master pin.');?>
                    <?php echo $admin->wordTrans($admin->getUserLang(),'You can also tell the system to remember your device so it will never ask you for the Master Pin on that device or browser.');?>
                    <?php echo $admin->wordTrans($admin->getUserLang(),'So in case someone knows your username and password and wants to login with that then system will ask him/her for Master Pin that he/she dont have.');?>
                    <?php echo $admin->wordTrans($admin->getUserLang(),'So he/she cant login and also system will send you an Email about this login from other system.');?>
                    </p>
                    <br>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Turn On Mater Pin'); ?></label>
                        <input onchange="change(this)" name="m_pin" type="checkbox" <?php echo $adminnotify; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>

                        <div id="m_pin_div">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Provide Mater Pin To Disable it'); ?></label>
                            <input name="m_pin_txt" type="text" class="form-control" id="m_pin_txt"/>
                        </div>



                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Make Sure You Have the pin before turning on this option'); ?></p>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Renew Master Pin'); ?></label>
                            <input name="m_pin_renew" type="checkbox" data-plugin="switchery" data-color="#495C74" data-size="big"/>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Turn On this option if you want to New/Reset Master Pin. You will receive an email from admin with new Master Pin'); ?></p>
                            <label class="help-info"></label>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_Master_Pin')); ?>" class="btn btn-primary"/>
                    </div> <!-- / panel-footer -->          
                </div>
            </form>

        </div>
    </div> <!-- / row --></div>
<script>

    var m_pin_div =<?php echo $adminnotify2; ?>;
    //alert(m_pin_div);
   // if (m_pin_div == '1')
        //$("#m_pin_div").hide();
    document.getElementById("m_pin_div").style.display = "none";

    function change(p) {

        if ($(p).prop("checked") == true) {

            $("#m_pin_div").hide();
        } else {


            $("#m_pin_div").show();
        }
    }
</script>