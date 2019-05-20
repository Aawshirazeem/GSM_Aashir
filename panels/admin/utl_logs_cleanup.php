<?php
defined("_VALID_ACCESS") or die("Restricted Access");
error_reporting
?>
<div class="lock-to-top">
    <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Logs_CleanUp')); ?></h4>
</div>

<div class="clear"></div>
<div class="table-responsive">
<table class="MT5 table table-striped table-hover panel">
    <tr>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Log')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_size')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
    </tr>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Sql Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_LOGS_ABSOLUTE . "/sql_error.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=1 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Email Queue').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_LOGS_ABSOLUTE . "/email_queue.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=2 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Timeout Error Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_SITE_ABSOLUTE . "TimeoutErrorLog.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=4 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Sql Delete Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_SITE_ABSOLUTE . "sql_del.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=5 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Paypal Result Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_SITE_ABSOLUTE . "paypal_result.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=6 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
    <?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Paypal Query Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_SITE_ABSOLUTE . "paypal_query.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=7 class="btn btn-success btn-sm">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
	<?php
    echo '<tr>';
    echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Paypal Return Data Log').'</td>';
    echo '<td>' . filesize(CONFIG_PATH_SITE_ABSOLUTE . "paypal.log") / 1000 . ' Kbytes</td>';
    echo '<td><a href='.CONFIG_PATH_SITE_ADMIN.'utl_logs_cleanup_process.html?id=8 class="btn btn-success btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Clear_Log')).'</a></td>';
    echo '</tr>';
    ?>
</table>
</div>