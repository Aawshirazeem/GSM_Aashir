<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();


$imei_suc_noti = $request->PostCheck('imei_suc_noti');
$imei_rej_noti = $request->PostCheck('imei_rej_noti');
$file_suc_noti = $request->PostCheck('file_suc_noti');
$file_rej_noti = $request->PostCheck('file_rej_noti');
$slog_suc_noti = $request->PostCheck('slog_suc_noti');
$slog_rej_noti = $request->PostCheck('slog_rej_noti');

$sql = 'update ' . USER_MASTER . '
					set
				
                                            imei_suc_noti = ' . $imei_suc_noti . ',
                                                imei_rej_noti = ' . $imei_rej_noti . ',
                                                    file_suc_noti = ' . $file_suc_noti . ',
                                                        file_rej_noti = ' . $file_rej_noti . ',
                                                            slog_suc_noti = ' . $slog_suc_noti . ',
                                                                slog_rej_noti = ' . $slog_rej_noti . '



					where id=' . $mysql->getInt($member->getUserId());
$mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_USER . "email_notify.html?reply=" . urlencode('reply_pass_update'));
exit();
