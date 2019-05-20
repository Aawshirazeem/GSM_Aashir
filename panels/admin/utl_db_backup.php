<link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$tbls = array(
    'nxt_api_error_log' => 'API error log',
    'nxt_stats_admin_login_master' => 'Admin login logs',
    'nxt_stats_user_login_master' => 'User login logs',
    'nxt_user_register_master' => 'New registrations'
);
?>
<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_done':
        $msg = 'DB Backup Successfully';
        break;
    case 'reply_delete_done':
        $msg = 'DB Backup Deleted';
        break;
        break;
    case 'reply_name_duplicate':
        $msg = 'Name Already Exist';
        break;
}
include("_message.php");
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_db_backup_process.do" method="post">
    <div class="card-box col-sm-8">
        <div class="lock-to-top">
            <h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('doc_Db_Backup')); ?> </h1>
            <div class="btn-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_db_backup_process.do" class="btn btn-success btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_take_backup')); ?></a>
            </div>
        </div><br>
        <div class="clear"></div>
		<div class="table-responsive">
        <table class="MT5 table table-striped table-hover panel">
            <tr>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_backup_file_name')); ?></th>
                <th width="200"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
            </tr>
            <?php
            $dir = CONFIG_PATH_SITE_ABSOLUTE . 'assets/db_backup/';
            //echo $dir;
            if (defined("DEMO")) {
                echo '<tr><td colspan="3">*** '.$admin->wordTrans($admin->getUserLang(),'Demo Mode').' ***</td></tr>';
            } else {
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            if ($file != "." && $file != '..' && $file != 'index.html') {
                                echo '<tr>';
                                echo '<td>' . $file . '</td>';
                                echo '<td>
										<div class="btn-group">';
                                $download = CONFIG_PATH_EXTRA_ABSOLUTE . "db_backup/" . $mysql->prints($file);
                                if (file_exists($download)) {
                                    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'download_db.do?file_name=' . stripslashes($file) . '" class="btn btn-primary btn-sm"> '. $admin->wordTrans($admin->getUserLang(),$lang->get('com_Download')).'</a>';
                                }
                                echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'utl_db_backup_delete_process.do?file_name=' . stripslashes($file) . '"  class="btn btn-danger btn-sm" id="sa-warning">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_delete')).'</a>';
                                echo '</div>
									  </td>';
                                echo '</tr>';
                            }
                        }
                        closedir($dh);
                    }
                }
            }
            ?>
        </table>
		</div>
    </div>
</form>