<?php
defined("_VALID_ACCESS") or die("Restricted Access");



$type = $request->getStr('type');

$ip = $request->GetStr('ip');

$user_id = $request->GetStr('user_id');
?>



<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Prepaid_Logs_Orders')); ?></li>

        </ol>

    </div>

</div>



<div class="row">

    <div class="col-lg-12">

        <div class="">

            <h4 class="panel-heading m-b-20">

                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Prepaid_Logs_Orders')); ?>

            </h4>
			
		<div class="table-responsive">
		
            <table class="table table-striped table-hover">

                <tr>

                    <th width="60"></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log')); ?> </th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username/password')); ?> </th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?> </th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(),'Create Date'); ?>&<?php echo $admin->wordTrans($admin->getUserLang(),'Time'); ?></th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(),'Order Date'); ?>&<?php echo $admin->wordTrans($admin->getUserLang(),'Time'); ?></th>

                </tr>

                <?php
                $paging = new paging();

                $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

                $limit = 20;

                $qLimit = " limit $offset,$limit";

                $extraURL = '&type=' . $type . '&user_id=' . $user_id . '&ip=' . $ip;

                $qType = '';

                if ($ip != '') {

                    $qType .= (($qType == '') ? ' and ' : '') . ' plum.ip = ' . $mysql->quote($ip);
                }

                if ($user_id != '') {

                    $qType .= (($qType == '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id);
                }



                $sql = 'select plum.*, um.username as uname, plm.prepaid_log_name

							from ' . PREPAID_LOG_UN_MASTER . ' plum

							left join ' . PREPAID_LOG_MASTER . ' plm on (plum.prepaid_log_id = plm.id)

							left join ' . USER_MASTER . ' um on (plum.user_id = um.id)

							where plum.status!=0 ' . $qType . '

							order by plum.id DESC';

//echo $sql;



                $query = $mysql->query($sql . $qLimit);

                $strReturn = "";



                $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'order_imei.html', $offset, $limit, $extraURL);



                $i = $offset;

                $totalRows = $mysql->rowCount($query);



                if ($totalRows > 0) {

                    $rows = $mysql->fetchArray($query);

                    foreach ($rows as $row) {

                        $i++;

                        echo '<tr>';

                        echo '<td class="text_center">' . $i . '<br /><small>pc-' . $row['id'] . '</small></td>';

                        echo '<td>' . $mysql->prints($row['prepaid_log_name']) . '</td>';

                        echo '<td>' . $mysql->prints($row['username']) . '</td>';

                        echo '<td>' . $mysql->prints($row['uname']) . '</td>';
                        
                         $finaldate2 = $admin->datecalculate($row['date_created']);
                        echo '<td>' . $finaldate2. '</td>';
                        
                        
                        $finaldate = $admin->datecalculate($row['reply_date_time']);
                        echo '<td>' . $finaldate. '</td>';


                        echo '</tr>';
                    }
                } else {

                    echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>

            </table>

        </div>
		
		</div>

    </div>

</div>

<div class="row m-t-20">
    <div class="col-md-6 p-l-0">
        <div class="TA_C navigation" id="paging">
            <?php echo $pCode; ?>
        </div>
    </div>
    <div class="col-md-6">

    </div>
</div>