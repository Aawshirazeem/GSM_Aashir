<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$group_id = $request->getInt('group_id');
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_API_Master')); ?></li>
        </ol>
    </div>
</div>
<div class="row m-b-20">
    <div class="col-lg-12 gsmUnion" style=" background-color: rgba(0, 188, 212, 0.18); min-height: 300px; margin-bottom: 15px;">
        <br>
        <label> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_To_Enable_API_Automation_feature_to_run,_make_sure_you_setup_a_cron_job_to_run_Every_Minute._Create_the_following_Cron_Jobs_Using_Cpanel_of_Your_site')); ?><br> 

            <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Setup_cron_job_as_listed_below')); ?>


        </label>
        <label><?php echo $admin->wordTrans($admin->getUserLang(), 'For Fast Proccess IMEI Orders by API Set Up This Cron Job'); ?></label>
        <?php
        //echo "<br><be>";
        $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/cron_imei.php >/dev/null 2>&1</pre>';
        // $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php</pre>';
        echo $link;
        ?>


        <label><?php echo $admin->wordTrans($admin->getUserLang(), 'For Bulk IMEI Orders by API Also Run This Cron Job Separately'); ?></label>
        <?php
        //echo "<br><be>";
        // $link2 .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php</pre>';
        $link2 .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/get_imei_orders.php >/dev/null 2>&1</pre>';
        echo $link2
        ?>

        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_For_File_Service_API_set_up_these_cron_jobs_separately')); ?>
        </label>
        <?php
        //echo "<br><be>";
        $link1 .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/get_file_orders.php >/dev/null 2>&1</pre>';
        $link1 .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_file_orders.php >/dev/null 2>&1</pre>';
        echo $link1;
        ?>

        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_For_Check_API_Rates_&_Update')); ?>
        </label>
        <?php
        //echo "<br><be>";
        //  $link1 .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/get_file_orders.php >/dev/null 2>&1</pre>';
        $link4.= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/update_api_rates3.php >/dev/null 2>&1</pre>';
        echo $link4;
        ?>
    </div>
</div>
<div class="row m-b-20">
    <div class="col-md-12">
        <div class="col-md-8">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_API_Servers')); ?>
            </h4>
        </div>
        <div class="col-md-2">	
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_add.html" class="btn btn-danger btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), 'Add New Api Server') ?></a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html" class="btn btn-danger btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), 'Setup Custom Api') ?></a>
        </div>

        <hr>
        <div class="table-responsive">		
            <table class="table table-striped table-hover">
                <tr>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_api')); ?> </th>
                    <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_command')); ?> </th>
                    <th width="150"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Sync_Datetime')); ?></th>
                    <th width="270"></th>
                </tr>
                <?php
                $sql = 'select *, DATE_FORMAT(sync_datetime,"%d %b %Y %h:%i %p") dts from ' . API_MASTER . ' where is_visible=1 and api_server!="Custom API" and status=1  order by api_server';
                $query = $mysql->query($sql);
                if ($mysql->rowCount($query) > 0) {
                    $rows = $mysql->fetchArray($query);
                    $i = 0;
                    foreach ($rows as $row) {

                        $server = '';
                        switch ($row['server_id']) {
                            case '1':
                                $server = 'GSMUnion Fusion API';
                                break;
                            case '8':
                                $server = 'Dhru Fusion API';
                                break;
                            case '12':
                                $server = 'Code Desk API';
                                break;
                        }
                        //    echo '<td>' . (($row['is_special']) ? '<i class="fa fa-link"></i>' : '') . '</td>';
                        echo '<td>
								<b>' . $row['api_server'] . '</b>
								<br /><small class="text-danger">' . $server . '</small>
                                                                    <br /><small class="text-danger">' . (($row['is_special']) ? '<i class="fa fa-link"></i>' : '') . '</small>
							</td>';
                        $finaldate = $admin->datecalculate($row['dts']);
                        $link = '<ul style="background-color: #d47572;
    ">';
                        $link .= '/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/get_imei_orders.php?api_id=' . $row['id'] . ' >/dev/null 2>&1';
                        $link .= '<br>----------------------------------------------<br>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php?api_id=' . $row['id'] . ' >/dev/null 2>&1';
                        $link .= '<ul>';
                        echo '<td style="">' . (($row['is_special']) ? $link : '') . '</td>';
                        if ($row['requires_sync'] == 1) {
                            echo '<td>' . (($row['dts'] != '') ? $finaldate : '<b class="TC_R">REQUIRE SYNC</b>') . '</td>';
                        } else {
                            echo '<td>--</td>';
                        }
                        echo '<td class="text-right">';
                        echo '
								<div class="btn-group">
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm"> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_setting')) . '</a>
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $row['id'] . '" class="btn btn-warning btn-sm"> ' . $admin->wordTrans($admin->getUserLang(), 'Import') . '</a>
                                                                            <a title="Show Connected Services" onclick="showdata(' . $row['id'] . ');" href="javascript:void(0);" class="btn btn-danger btn-sm"><i class="fa fa-bolt" aria-hidden="true"></i></a>
								</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<h4><?php echo $admin->wordTrans($admin->getUserLang(), 'Inactive API Servers'); ?></h4>
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tr>
                    <th width="16">#</th>
                    <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_api_name')); ?> </th>
                    <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(), 'Action'); ?></th>
                </tr>
                <?php
                $sql = 'select * from ' . API_MASTER . ' where is_visible=1 and status=0  order by api_server';
                $query = $mysql->query($sql);
                if ($mysql->rowCount($query) > 0) {
                    $rows = $mysql->fetchArray($query);
                    $i = 0;
                    foreach ($rows as $row) {
                        $i++;
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td class="text-center">' . $row['api_server'] . '</td>';
                        //echo '<td></td>';
                        //echo '<td></td>';

                        if ($row['status'] == '0') {
                            echo '<td class="text-center">';
                            echo ' <div class="btn-group">';
                            echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_activate.html?id=' . $row['id'] . '&type=1" class="btn btn-success btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_activate')) . '</a>';
                            if ($row['server_id'] == 1 || $row['server_id'] == 8 || $row['server_id'] == 12 || $row['server_id'] == 13 || $row['server_id'] == 9) {
                                echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_activate.html?id=' . $row['id'] . '&type=2" class="btn btn-danger btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_remove')) . '</a>';
                            }

                            echo '</div>';
                            echo '</td>';
                        } else {
                            echo '	<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_edit.html?id=' . $row['id'] . '" class="btn btn-primary">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_setting')) . '</a>
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_check_credits.html?id=' . $row['id'] . '" class="btn btn-default">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_check_credits')) . '</a>';
                        }
                        //echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
       
          <h4 class="modal-title">Services Connected</h4>
        </div>
        <div class="modal-body" id="myy">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<script type="text/javascript">
    function showdata(a)
    {

        $.ajax({
            type: "POST",
            url: "<?php echo CONFIG_PATH_SITE_ADMIN; ?>get_api_servc.do",
            //data: 'page='+url,

            data: "&api_id=" + a,
            //dataType: "html",

            success: function (msg) {

                if (msg != "")
                {
                     $('#myy').html(msg);
                  //  alert(msg);
                $('#myModal').modal('show');
            }
                else
                    alert("Some error");
            }
        });
    }
</script>