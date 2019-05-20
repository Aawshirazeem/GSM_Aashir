<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$admin_time = $admin->timezone();
$member_time = $member->timezone();
?>

<div class="col-md-12">
    <div class="panel table-responsive">
        <div class="panel-heading"><h4><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_history')); ?></h4></div>

        <?php
        $paging = new paging();
        $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
        $limit = 40;
        $qLimit = " limit $offset,$limit";
        $extraURL = '&type=' . $type;
        $sql = 'select stu.username,stu.success,stu.b_info,stu.p_info, stu.ip, stu.date_time
							from ' . STATS_USER_LOGIN_MASTER . ' stu 
							left join ' . USER_MASTER . ' um on (um.username=stu.username)
							where  um.id=' . $member->getUserId() . ' order by stu.id DESC';
        $result = $mysql->getResult($sql, false, 20, $offset, CONFIG_PATH_SITE_USER . 'login_history.html', array());
        ?>
        <table class="table table-striped table-hover table-striped table-bordered">
            <tr>
                <th class="TA_R">#</th>
                <th class="TA_R"><?php echo $admin->wordTrans($admin->getUserLang(),'Date & Time'); ?></th>
                <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_name')); ?></th>
                
                  <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Browser_info')); ?></th>
                <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_ip')); ?> </th>
                <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),'Status'); ?></th>
            </tr>
            <?php
            $strReturn = "";

            $i = $offset;
            $obj = new country();
            if ($result['COUNT']) {
                foreach ($result['RESULT'] as $row_lf) {
                    $ccode = $obj->getccode($row_lf['ip']);
                    //timezone 
                    $i++;
                    $dtDateTime = new DateTime($row_lf['date_time'], new DateTimeZone($admin_time));
                    $dtDateTime->setTimezone(new DateTimeZone($member_time));
                    $dtDateTime = $dtDateTime->format('d-M-Y H:i');
                      $finaldate2 = $member->datecalculate($row_lf['date_time']);
                    //echo $dtDateTime;exit
                    //end
                    ?>
                    <tr <?php echo (($row_lf['success'] == 0) ? 'style=color:red;' : ''); ?>>
                        <td class="TA_R"><?php echo $i; ?></td>
                        <td><?php echo $finaldate2; ?></td>
                         <td><?php echo $row_lf['username'];?></td>
                        
                         <td><?php echo $row_lf['b_info'];?></td>
                        <td><?php echo $row_lf['ip'] . '<img src="' . CONFIG_PATH_SITE . 'images/flags/' . strtolower($ccode) . '.png" />' ?></td>
                        <td><?php echo (($row_lf['success'] == 0) ? '<b>unsuccess</b>' : 'success'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
    <?php echo $result['PAGINATION']; ?>
</div>