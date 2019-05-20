<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$sql = 'select a.id,a.username,a.imei_suc_noti,a.imei_rej_noti,a.file_suc_noti,a.file_rej_noti,a.slog_suc_noti
,a.slog_rej_noti

 from nxt_user_master a  where id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];


$imei_suc_noti = $rows[0]['imei_suc_noti'];
if ($imei_suc_noti == 1)
    $imei_suc_noti = 'checked';
else
    $imei_suc_noti = '';

$imei_rej_noti = $rows[0]['imei_rej_noti'];
if ($imei_rej_noti == 1)
    $imei_rej_noti = 'checked';
else
    $imei_rej_noti = '';

$file_suc_noti = $rows[0]['file_suc_noti'];
if ($file_suc_noti == 1)
    $file_suc_noti = 'checked';
else
    $file_suc_noti = '';

$file_rej_noti = $rows[0]['file_rej_noti'];
if ($file_rej_noti == 1)
    $file_rej_noti = 'checked';
else
    $file_rej_noti = '';

$slog_suc_noti = $rows[0]['slog_suc_noti'];
if ($slog_suc_noti == 1)
    $slog_suc_noti = 'checked';
else
    $slog_suc_noti = '';

$slog_rej_noti = $rows[0]['slog_rej_noti'];
if ($slog_rej_noti == 1)
    $slog_rej_noti = 'checked';
else
    $slog_rej_noti = '';
?>

<div class="col-md-10">
    <div class="panel panel-default">

        <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_Email_Notifications')); ?></div>
        <form action="<?php echo CONFIG_PATH_SITE_USER; ?>email_notify_process.do" method="post">
            <div class="panel-body">

                <table class="table table-hover table-responsive table-striped">

                    <tr>
                        <th><h4 class="text-white"><?php echo $admin->wordTrans($admin->getUserLang(),'Email Type'); ?></h4></th>
                        <th><h4 class="text-white"><?php echo $admin->wordTrans($admin->getUserLang(),'Enable/Disable'); ?></h4></th>
                    </tr>
                    <tr>
                        <td> <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI ORDER SUCCESS NOTIFICATION'); ?></label></td>
                        <td>
                            <input name="imei_suc_noti" type="checkbox" <?php echo $imei_suc_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>
                        </td>
                    </tr>
                    <tr>
                        <td> <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI ORDER REJECT NOTIFICATION'); ?></label></td>
                        <td>                        
                            <input name="imei_rej_noti" type="checkbox" <?php echo $imei_rej_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>
                        </td>
                    </tr>
                    <tr>
                        <td>  <label><?php echo $admin->wordTrans($admin->getUserLang(),'FILE ORDER SUCCESS NOTIFICATION'); ?></label></td>
                        <td>
                            <input name="file_suc_noti" type="checkbox" <?php echo $file_suc_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>

                        </td>
                    </tr>
                    <tr>
                        <td><label><?php echo $admin->wordTrans($admin->getUserLang(),'FILE ORDER REJECT NOTIFICATION<br>'); ?></label>
                        </td>
                        <td>
                            <input name="file_rej_noti" type="checkbox" <?php echo $file_rej_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>

                        </td>
                    </tr>
                    <tr>
                        <td>                    <label><?php echo $admin->wordTrans($admin->getUserLang(),'Log Server ORDER SUCCESS NOTIFICATION'); ?></label>
                        </td>
                        <td>
                            <input name="slog_suc_noti" type="checkbox" <?php echo $slog_suc_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>

                        </td>
                    </tr>
                    <tr>
                        <td>                    <label><?php echo $admin->wordTrans($admin->getUserLang(),'Log Server ORDER REJECT NOTIFICATION'); ?></label>
                        </td>
                        <td>
                            <input name="slog_rej_noti" type="checkbox" <?php echo $slog_rej_noti; ?> data-plugin="switchery" data-color="#495C74" data-size="big"/>

                        </td>
                    </tr>
                </table>




<!--                        <p class="help-block">Make Sure You Have the pin before turning on this option</p>-->
            </div>

            <div class="panel-footer">
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-primary"/>
            </div> <!-- / panel-footer -->          
    </div>
</form>

</div>
</div>