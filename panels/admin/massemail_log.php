<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
?>

<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_user_list.html"><i class="fa fa-mail-forward"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mass_Mail')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mail_History')); ?></li>
        </ol>
    </div>
</div>

<div class="col-lg-12">
    <div class="">
        <h4 class="panel-heading m-b-20">
          
            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mass_Email_Log')); ?>
        </h4>
        <div class="card-box">
            <table class=" table table-striped table-hover">
                <tr>
                    <th>   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_subject')); ?></th>
                    <th>   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_body')); ?></th>
                    <th>   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?></th>
                    <th>   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
                </tr>

                <?php
                $sql = 'select * from ' . MAIL_HISTORY . ' a where a.user_id=777777 order by a.date_time desc';
                $result = $mysql->getResult($sql);
                if ($result['COUNT']) {
                    foreach ($result['RESULT'] as $row) {
                        echo '<tr>';
                        echo '<td>' . $mysql->prints($row['subject']) . '</td>';
                        echo '<td>' . $mysql->prints($row['content']) . '</td>';
                        echo '<td>' . $mysql->prints($row['date_time']) . '</td>';
                        echo '<td>
								<a href="' . CONFIG_PATH_SITE_ADMIN . 'email_user_list.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Reuse')) . '</a>
						  </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>
            </table></div>
    </div>

    <!-- <script>
        $(document).ready(function ()
        {
            $(".various").fancybox({
                maxWidth: 900,
                maxHeight: 400,
                fitToView: false,
                width: '90%',
                height: '90%',
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                'afterClose': function () {
                    window.location.reload();
                }
            });
        });
    </script>-->
