<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$group_id = $request->getInt('group_id');
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php  echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_master')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_API_Master')); ?></li>
        </ol>
    </div>
</div>
<div class="row m-b-20">
	<div class="col-lg-12" style=" background-color: rgba(0, 188, 212, 0.18);height: 150px">
    	<br>
        <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_To_Enable_Custom_API_Automation_Feature_To_Run,_Please_make_sure_you_setup_a_cron_job_to_run_Every_Minute._Create_the_following_Cron_Jobs_Using_Cpanel_of_Your_site')); ?><br> 
            
        
            
            <hr>
        </label>
        
        <?php
            //echo "<br><be>";
            $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/cron_custom.php >/dev/null 2>&1</pre>';
           // $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php</pre>';
            echo $link;
        ?>
    </div>
</div>
<div class="row m-b-20">
    <div class="col-md-12">
        <h4 class="m-b-20">
            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_API')); ?>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_add_cust.html" class="btn btn-success btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_customized_api')) ?></a>

        </h4>
        <hr>
        <table class="table table-striped table-hover">
            <tr>
                                <th width="16">#</th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Name')); ?> </th>
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_URL')); ?> </th>
                <th width="250"></th>
            </tr>
            <?php
            $sql = 'select a.id,a.service_name,a.info from ' . API_DETAILS . '  a where a.group_name="Custom"';
            $query = $mysql->query($sql);
            $i=0;
            if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                $i = 0;
                foreach ($rows as $row) {


$i++;
                    echo '<tr>';
                                        echo '<td>' . $i. '</td>';

                    echo '<td class="">' . $row['service_name'] . '</td>';
                    echo '<td class="text-center">' . $row['info'] . '</td>';
                    echo '<td class="text-right">';
                    echo '
								<div class="btn-group">
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_edit_cust.html?id=' . $row['id'] . '" class="btn btn-success btn-sm"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_edit')) . '</a>
                                                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'api_errors_add.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_add_error')) . '</a>
                                                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'api_del_cust.html?id=' . $row['id'] . '" class="btn btn-danger btn-sm"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_delete')) . '</a>

								</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
            }
            ?>
        </table>
    </div>
</div>



