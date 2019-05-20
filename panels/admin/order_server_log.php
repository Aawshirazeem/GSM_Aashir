<?php

defined("_VALID_ACCESS") or die("Restricted Access");



$type = $request->getStr('type');

$supplier_id = $request->getInt('supplier_id');

$server_log_id = $request->getInt('server_log_id');

$ip = $request->GetStr('ip');

$user_id = $request->GetInt('user_id');



$pString = '';

if ($supplier_id != 0) {

    $pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;

}

if ($ip != '') {

    $pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;

}

if ($user_id != 0) {

    $pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;

}

if ($server_log_id != 0) {

    $pString .= (($pString != '') ? '&' : '') . 'server_log_id=' . $server_log_id;

}

$pString = trim($pString, '&');

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Server_Logs_Orders')); ?></li>

        </ol>

    </div>

</div>

<div class="row" id="btn-group-top">

    <div class="col-lg-12">

        <div class="btn-group btn-group-sm extra">

          

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=pending<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'pending') ? 'btn-primary' : 'btn-default'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_New_Order')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=locked<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'locked') ? 'btn-primary' : 'btn-default'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_In_Process')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=accepted<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'accepted') ? 'btn-primary' : 'btn-default'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Completed')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=rejected<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'rejected') ? 'btn-primary' : 'btn-default'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html<?php echo (($pString != '') ? ('?' . $pString) : ''); ?>" class="btn <?php echo ($type == '') ? 'btn-primary' : 'btn-default'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_orders')); ?> </a>

            </div>

        

    </div>

</div>

<div class="clear"></div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log_<?php echo $type; ?>_process.do" enctype="multipart/form-data" method="post" name="frm_inquiry" id="frm_inquiry" class="formSkin noWidth">

    <input type="hidden" name="type" value="<?php echo $type; ?>">

    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">

    <input type="hidden" name="ip" value="<?php echo $ip; ?>">

    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <input type="hidden" name="server_log_id" value="<?php echo $server_log_id; ?>">



    <div class="row">

        <div class="col-lg-12">

            <div class="panel">

                <h4 class="panel-heading m-t-20 m-b-20">

                   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Server_Logs_Orders')); ?>

                </h4>	
			<div class="table-responsive">
               <table class="table table-bordered table-hover" style="table-layout:fixed;
width:100%;
word-wrap:break-word;">

                    <tr>

                        <th width="100"></th>

                       

                      

                        <?php echo ($type == "pending") ? '<th width="" style="text-align:center; color:#000077">' . $lang->get('com_lock') . '<br><label class="c-input c-checkbox"><input type="checkbox" id="mine" value="1" onclick="ok()" class="" /><span class="c-indicator c-indicator-success"></span></th>' : ''; ?>

                        <?php echo ($type == "locked") ? '<th width="width="100"" style="text-align:center">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_accept')) . '</th>' : ''; ?>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_name')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_information')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date_time')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>

                        <?php echo ($type == "locked") ? '<th width="100" style="text-align:center;color:#FF0000">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_Reject')) . '</th>' : ''; ?>

                        <?php echo ($supplier_id != 0 and $type == 'accepted') ? '<th style="text-align:center">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_pay')) . '</th>' : ''; ?>

                        <th width="100"></th>

                    </tr>



                    <?php

                    $paging = new paging();

                    $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

                    $limit = 20;

                    $qLimit = " limit $offset,$limit";

                    $extraURL = '&type=' . $type . '&supplier_id=' . $supplier_id . '&user_id=' . $user_id . '&ip=' . $ip;





                    $qType = '';



                    switch ($type) {

                        case 'pending':

                            $qType = ' ofsm.status=0 ';

                            break;

                        case 'locked':

                            $qType = ' ofsm.status=-1 ';

                            break;

                        case 'accepted':

                            $qType = ' ofsm.status=1 ';

                            break;

                        case 'rejected':

                            $qType = ' ofsm.status=2 ';

                            break;

                    }



                    if ($supplier_id != 0) {

                        $qType .= (($qType != '') ? ' and ' : '') . ' ofsm.supplier_id=' . $mysql->getInt($supplier_id) . ' ';

                    }

                    if ($server_log_id != 0) {

                        $qType .= (($qType != '') ? ' and ' : '') . ' ofsm.server_log_id=' . $mysql->getInt($server_log_id) . ' ';

                    }

                    if ($ip != '') {

                        $qType .= (($qType != '') ? ' and ' : '') . ' ofsm.ip = ' . $mysql->quote($ip);

                    }

                    if ($user_id != '') {

                        $qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id);

                    }





                    $qType = ($qType == '') ? '' : ' where ' . $qType;





                    $sql = 'select ofsm.*,

								DATE_FORMAT(ofsm.date_time, "%d-%b-%Y %k:%i") as dtDateTime,

								DATE_FORMAT(ofsm.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,

								um.username,um.slog_suc_noti,um.slog_rej_noti,

								um.email,

								slm.server_log_name,

								sm.username as supplier,cm.prefix, cm.suffix,

								DATE_FORMAT(ofsm.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier

								from ' . ORDER_SERVER_LOG_MASTER . ' ofsm

								left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)

								left join ' . SERVER_LOG_MASTER . ' slm on (slm.id = ofsm.server_log_id)

                                                                left join ' . CURRENCY_MASTER . ' cm on(cm.id = um.currency_id)

								left join ' . SUPPLIER_MASTER . ' sm on(ofsm.supplier_id = sm.id)

								' . $qType . '

								order by ofsm.id DESC';

                    //echo $sql;



                    $query = $mysql->query($sql . $qLimit);

                    $strReturn = "";



                    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'order_server_log.html', $offset, $limit, $extraURL);



                    $i = $offset;

                    $totalRows = $mysql->rowCount($query);



                    if ($totalRows > 0) {

                        $rows = $mysql->fetchArray($query);

                        foreach ($rows as $row) {

                            $i++;

                            echo '<input type="hidden" name="Ids[]" value=' . $row['id'] . '>';

                            echo '<input type="hidden" name=username_' . $row['id'] . ' value=' . $row['username'] . '>';

                            echo '<input type="hidden" name=server_log_name_' . $row['id'] . ' value=' . $row['server_log_name'] . '>';

                            echo '<input type="hidden" name=user_id_' . $row['id'] . ' value=' . $row['user_id'] . '>';

                            echo '<input type="hidden" name=email_' . $row['id'] . ' value=' . $row['email'] . '>';

                            echo '<input type="hidden" name=sucmail_' . $row['id'] . ' value=' . $row['slog_suc_noti'] . '>';

                            echo '<input type="hidden" name=rejmail_' . $row['id'] . ' value=' . $row['slog_rej_noti'] . '>';

                            echo '<input type="hidden" name=credits_' . $row['id'] . ' value=' . $row['credits'] . '>';

                            

                            echo '<tr>';

                            echo '<td class="text_center">' . $i . '<br /><small>sc-' . $row['id'] . '</small>';

                           // echo '<td>';

                            switch ($row['status']) {

                                case -1:

                                    echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_In_Process')) . '</span>';

                                    break;

                                case 0:

                                    echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')) . '</span>';

                                    break;

                                case 1:

                                    echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')) . '</span>';

                                    break;

                                case 2:

                                    echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span>';

                                    break;

                            }

                            echo '</td>';

                            echo ($type == "pending") ? '<td class="text-center">' . (($row['status'] == '0') ? '<input type="checkbox" class="subSelect" name="locked_' . $row['id'] . '">' : '') . '</td>' : '';

                            echo ($type == "locked") ? '<td class="text-center">' . (($row['status'] == '-1') ? '<input type="checkbox" name="accept_' . $row['id'] . '">' : '') . '<input type="text" name="acc_remarks_' . $row['id'] . '" value="" class="form-control"></td>' : '';

                            echo '<td>' . $mysql->prints($row['server_log_name']) . (($row['supplier'] != '') ? ('<br><small><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '') . '</td>';

                            echo '<td>';

                            echo '<b>' . $mysql->prints($row['username']) . '</b>';

                            echo '<br><span class="text-danger">' . $mysql->prints($row['ip']) . '</span>';

                            echo '</td>';

                            echo '<td>' . $row['custom_value'] . '</td>';
                            
                            
                             $finaldate = $admin->datecalculate($row['dtDateTime']);
                            if ($row['reply_date_time'] != '0000-00-00 00:00:00') {
                                $finaldate2 = $admin->datecalculate($row['dtReplyDateTime']);
                            }
 else {
     $finaldate2="";
 }
                            

                            echo '<td><small>' . $finaldate . '<br /><b>' . $finaldate2 . '</b></small></td>';

                            //echo '<td>' . $row['credits'] . '</td>';

                            echo '<td>'.$objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']).'</td>';

                            echo ($type == "locked") ? '<td class="text-center">' . (($row['status'] == '-1') ? '<input type="checkbox" name="reject_' . $row['id'] . '"><br /><input type="text" name="un_remarks_' . $row['id'] . '" value="" class="form-control"></td>' : '') . '' : '';

                            if ($supplier_id != 0 and $type == 'accepted') {

                                echo '<td class="text_center">';

                                if ($row['supplier_paid'] == 0) {

                                    echo '<input type="checkbox" name="pay_' . $row['id'] . '">';

                                } else {

                                    echo '<small><b>' . $row['dtSupplier'] . '</b></small>';

                                }

                                echo '</td>';

                            }

                            echo '<td><a class="btn btn-primary btn-sm various" data-fancybox-type="iframe" href="order_server_log_detail.html?id=' . urlencode($row['id']) . (($type != '') ? ('&type=' . $type . '&' . $pString) : ('&' . $pString)) . '" > ' . $admin->wordTrans($admin->getUserLang(),'Details') . '</a> </td>';

                            echo '</tr>';

                        }

                    } else {

                        echo '<tr><td colspan="9" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

                    }

                    ?>

                </table>
			</div>	

<div class="row m-t-20">
	<div class="col-md-6 p-l-0">
    	<div class="TA_C navigation" id="paging">
			<?php  echo $pCode;  ?>
        </div>
    </div>
    <div class="col-md-6">
    		<div class="panel-body">

                    <?php // $graphics->messagebox('Note: In case if you check Accept and Reject options both for the same server log order, then the order will be considered as <b>' . $lang->get('com_accepted') . '</b>.'); ?>

                    <?php

                    if ($totalRows > 0 and ( $type == 'pending' || $type == 'locked' || $type == 'accepted')) {

                        echo '<input type="submit" value="' . $admin->wordTrans($admin->getUserLang(),'Process Selected') . '" class="btn btn-success btn-sm" />';

                    }

                    ?>

           </div>
    </div>
</div>

                

            </div>

        </div>

</form>





<script>

    function ok() {



        if ($('#mine').is(":checked"))

        {

            $('.subSelect').each(function () { //loop through each checkbox



                this.checked = true;  //select all checkboxes with class "checkbox1"               

            });

        }

        else

        {

            $('.subSelect').each(function () { //loop through each checkbox

                this.checked = false; //deselect all checkboxes with class "checkbox1"                       

            });

        }





    }

</script>