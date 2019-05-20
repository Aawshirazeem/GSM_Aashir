<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('config_order_14345356148');



$type = $request->getStr('type');

$supplier_id = $request->GetInt('supplier_id');

$file_service_id = $request->GetInt('file_service_id');

$file_name = $request->getStr('file_name');

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

if ($file_service_id != 0) {

    $pString .= (($pString != '') ? '&' : '') . 'file_service_id=' . $file_service_id;
}



$pString = trim($pString, '&');

$sqlCount = 'select status, count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' where status in (0, -1) group by status';
$queryCount = $mysql->query($sqlCount);
if ($mysql->rowCount($queryCount) > 0) {
    $rows = $mysql->fetchArray($queryCount);
    foreach ($rows as $row) {
        $imeiCount[$row['status']] = $row['total'];
    }
}
$new_orders=$imeiCount['0'];
$inpr_orders=$imeiCount['-1'];
//echo $new_orders.$inpr_orders;
?>



<div class="row">

    <div class="col-lg-12">

        <ul class="breadcrumb">

            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Orders')); ?></li>

            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_File_Orders')); ?></li>

        </ul>

    </div>

</div>

<div class="clearfix"></div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>

                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>

            </div>

            <div class="modal-body">

                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html<?php echo (($pString != '') ? ('?' . $pString) : ''); ?>" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">

                    <input type="hidden" name="type" value="<?php echo $type; ?>">

                    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">

                    <input type="hidden" name="ip" value="<?php echo $ip; ?>">

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <input type="hidden" name="file_service_id" value="<?php echo $file_service_id; ?>">

                    <fieldset>

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_names')); ?> </label>

                            <input type="text" name="file_name" class="form-control" id="file_name" value="<?php echo stripslashes($file_name); ?>" />

                            <input type="hidden" name="type" value="<?php echo $type; ?>">

                            <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">

                        </div>

                        <div class="form-group">

                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />

                        </div>

                    </fieldset>

                </form>

            </div>

        </div>

    </div>

</div>

<div class="row" id="btn-group-top">

    <div class="col-lg-12">

        <div class="btn-group extra pull-right">

            <div class="btn-group btn-group-sm extra">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=pending<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'pending') ? 'btn-primary' : 'btn-default'; ?>" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_New_Order')); ?><span class="label label-rounded label-danger"><?php echo $new_orders;?></span> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=locked<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'locked') ? 'btn-primary' : 'btn-default'; ?>" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_In-Process')); ?> <span class="label label-rounded label-danger"><?php echo $inpr_orders;?></span> </a>



                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=accepted<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'accepted') ? 'btn-primary' : 'btn-default'; ?>" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Completed')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=rejected<?php echo (($pString != '') ? ('&' . $pString) : ''); ?>" class="btn <?php echo ($type == 'rejected') ? 'btn-primary' : 'btn-default'; ?>" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?> </a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html<?php echo (($pString != '') ? ('?' . $pString) : ''); ?>" class="btn <?php echo ($type == '') ? 'btn-primary' : 'btn-default'; ?>" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_orders')); ?> </a>

                <a href="#searchPanel" data-toggle="modal" class="btn btn-warning"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>

            </div>

        </div>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file_<?php echo $type; ?>_process.do" enctype="multipart/form-data" method="post" name="frm_inquiry" id="frm_inquiry" class="formSkin noWidth">

    <input type="hidden" name="type" value="<?php echo $type; ?>">

    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">

    <input type="hidden" name="ip" value="<?php echo $ip; ?>">

    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <input type="hidden" name="file_service_id" value="<?php echo $file_service_id; ?>">

    <div class="row">

        <div class="col-sm-12 col-sm-offset-0">

            <div class="panel">

                <h4 class="panel-heading m-b-20">

                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_orders')); ?>



                </h4>
				
			<div class="table-responsive">

                <table class="table table-striped table-hover">

                    <tr>

                        <th></th>

                        <?php echo ($type == "pending") ? '<th class=""> <label class="c-input c-checkbox"> <input type="checkbox" id="mine" value="1" onclick="ok()" class="" /><span class="c-indicator c-indicator-success"></span>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_lock')) . '</th>' : ''; ?>

                        <?php echo ($type == "locked") ? '<th class="text-center">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_accept')) . '</th>' : ''; ?>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_name')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_names')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlock_code')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date_time')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>

                        <?php echo ($type == "locked") ? '<th width="100" style="text-align:center;color:#FF0000">Reject </th>' : ''; ?>

                        <th style="text-align:right;"><?php echo $admin->wordTrans($admin->getUserLang(),'Actions'); ?></th>

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

                    if ($file_service_id != 0) {

                        $qType .= (($qType != '') ? ' and ' : '') . ' ofsm.file_service_id=' . $mysql->getInt($file_service_id) . ' ';
                    }

                    if (trim($file_name) != '') {

                        $qType .= (($qType != '') ? ' and ' : '') . ' ofsm.fileask LIKE \'' . ('%' . $mysql->getStr($file_name) . '%') . '\' ';
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

									ofsm.reply_date_time as dtReplyDateTime,

									um.username,um.email,um.file_suc_noti,um.file_rej_noti,

									slm.service_name,

									sm.username as supplier,cm.prefix, cm.suffix,

									DATE_FORMAT(ofsm.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier

									from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm

									left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)

                                                                        left join ' . CURRENCY_MASTER . ' cm on(cm.id = um.currency_id)

									left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)

									left join ' . SUPPLIER_MASTER . ' sm on(ofsm.supplier_id = sm.id)

									' . $qType . '

									order by ofsm.id DESC';

                    $query = $mysql->query($sql . $qLimit);

                    $strReturn = "";



                    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'order_file.html', $offset, $limit, $extraURL);



                    $i = $offset;

                    $totalRows = $mysql->rowCount($query);



                    if ($totalRows > 0) {

                        $rows = $mysql->fetchArray($query);

                        foreach ($rows as $row) {

                            $i++;



                            echo '<input type="hidden" name="Ids[]" value=' . $row['id'] . '>';

                            echo '<input type="hidden" name=user_id_' . $row['id'] . ' value=' . $row['user_id'] . '>';

                            echo '<input type="hidden" name=username_' . $row['id'] . ' value=' . $row['username'] . '>';

                            echo '<input type="hidden" name=file_service_' . $row['id'] . ' value=' . $row['service_name'] . '>';

                            echo '<input type="hidden" name=credits_' . $row['id'] . ' value=' . $row['credits'] . '>';

                            echo '<input type="hidden" name=email_' . $row['id'] . ' value=' . $row['email'] . '>';

                            echo '<input type="hidden" name=sucmail_' . $row['id'] . ' value=' . $row['file_suc_noti'] . '>';

                            echo '<input type="hidden" name=rejmail_' . $row['id'] . ' value=' . $row['file_rej_noti'] . '>';



                            echo '<tr>';

                            /* Serial Number * Order ID */

                            echo '<td class="text_center">' . $i . '<br /><small>fl-' . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . '</small></td>';



                            /* Show Lock check box for pending Jobs */

                            echo ($type == "pending") ? '<td>' . (($row['status'] == '0') ? '<input type="checkbox" class="subSelect" name="locked_' . $row['id'] . '">' : '') . '</td>' : '';



                            /* Show Accept Job for the locked Jobs */

                            if ($type == "locked") {

                                echo ($type == "locked") ? '<td>' . (($row['status'] == '-1') ? '<label class="c-input c-checkbox"><input type="checkbox" name="accept_' . $row['id'] . '"><span class="c-indicator c-indicator-success"></span></label><input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control codeBoxFill txtCode" style="display:inline" value="' . $UnlockCode . '" />' : '') . '</td>' : '';

                                echo '';
                            }

                            /* File Service * Service Name * Supplier */

                            echo '<td>';

                            echo '' . $mysql->prints($row['service_name']) . '<br>';

                            switch ($row['status']) {

                                case 0:

                                    //	echo '<span class="label label-default">' . $lang->get('com_pending'). '</span>';

                                    echo '<span class="text-default"><i class="fa fa-spinner fa-pulse"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')). '</span>';



                                    break;

                                case -1:

                                    //echo '<span class="label label-primary">' . $lang->get('com_locked'). '</span>';

                                    echo '<span class="text-primary"><i class="fa fa-lock"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_In-Process')) . '</span>';

                                    break;

                                case 1:

                                    //echo '<span class="label label-success">' . $lang->get('com_available'). '</span>';

                                    echo '<span class="text-success"><i class="fa fa-circle"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')) . '</span>';



                                    break;

                                case 2:

                                    //echo '<span class="label label-danger">' . $lang->get('com_unavailable'). '</span>';

                                    echo '<span class="text-danger"><i class="fa fa-circle"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span>';



                                    break;
                            }



                            echo (($row['supplier'] != '') ? ('<br><small><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '');

                            echo '</td>';



                            /* File Name */

                            echo '<td>';
                            $file_name_temp=array_pop(explode('_', $row['fileask']));

                            echo (($row['fileask'] != '') ? ('<small>' . $mysql->prints($file_name_temp) . '</small>') : '');

                            echo (($row['filerpl'] != '') ? ('<br /><small>' . $mysql->prints($row['filerpl']) . '</small>') : '');

                            echo '<br /><b>' . $mysql->prints($row['username']) . '</b>';

                            echo '<br /><small class="text-danger">' . $mysql->prints($row['ip']) . '</small>';

                            echo '</td>';



                            /* Unlock Code */

                            echo '<td>';

                            if (defined("DEMO")) {

                                echo '*****Demo*****';
                            } else {

                                if ($row['status'] == 1) {

                                    echo (($row['unlock_code'] != '' and $row['unlock_code'] != '0') ? nl2br($mysql->prints($row['unlock_code'])) : '');
                                } else {

                                    //echo (($row['unlock_code'] !='' and $row['unlock_code'] != '0') ? nl2br($mysql->prints($row['unlock_code'])) : '') ;

                                    echo (($row['reply'] != '') ? $mysql->prints($row['reply']) : '');
                                }
                            }

                            echo '</td>';



                            /* Date Time */

                            $finaldate = $admin->datecalculate($row['dtDateTime']);
                            if ($row['dtReplyDateTime'] != '0000-00-00 00:00:00') {
                                $finaldate2 = $admin->datecalculate($row['dtReplyDateTime']);
                            }
                             else {
     $finaldate2="";
 }
       
                            echo '<td>

											<small>' . $finaldate . '<br />

											<b>' . $finaldate2 . '</b></small>

									</td>';



                            /* Credits */

                            //  echo '<td>' . $row['credits'] . '</td>';

                            echo '<td>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</td>';



                            /* Return Job */

                            echo ($type == "locked") ? '<td>' . (($row['status'] == '-1') ? '<input type="checkbox" name="reject_' . $row['id'] . '"><br /><input type="text" name="un_remarks_' . $row['id'] . '" value="" class="form-control">' : '') . '</td>' : '';



                            echo '<td class="text-right">

										<div class="btn-group btn-group-sm">';

                            $ask = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['fileask']);

                            $rpl = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['filerpl']);



                            if ($row['f_content'] != "") {

                                echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'download4.do?id=' .$row['id']. '" title="Click To Download File" class="btn btn-default btn-sm"><i class="fa fa-download"></i></a>  ';
                            }



                            if (file_exists($rpl) and $row['filerpl'] != "") {

                                echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'download3.do?type=askrpl&file_name=' . stripslashes($row['filerpl']) . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_RPL')) . '</a>';
                            }



                            if ($row['status'] == '-1') {

                                //echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_file_update.html?id=' . $row['id'] . (($type != '') ? ('&type=' . $type . '&' . $pString) : ('&' . $pString)) . '" class="btn btn-primary btn-sm" >' . $lang->get('com_update'). '</a>';
                            }



                            echo '<a data-fancybox-type="iframe" title="Click To Find Order Details" href="order_file_detail.html?id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" class="btn btn-primary btn-sm" ><i class="fa fa-arrow-right"></i></a>';
                             echo '<a data-fancybox-type="iframe" title="Click To Edit Order" href="order_q_edit_2.html?status='.urlencode($row['status']).'&id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" class="btn btn-danger btn-sm" ><i class="fa fa-pencil-square-o"></i></a>';

                            echo '

										</div></td>';

                            echo '</tr>';
                        }
                    } else {

                        echo '<tr><td colspan="20" class="no_record">'.$admin->wordTrans($admin->getUserLang(),'No record found!').'</td></tr>';
                    }
                    ?>

                </table>

                <?php
                if ($totalRows > 0 and ( $type == 'pending' || $type == 'locked')) {

                    echo '<div class="panel-body"';

                    echo '<div class="form-group">';

                    echo '<input type="submit" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_selected')) . '" class="btn btn-success btn-sm" />';

                    echo '</div>';

                    echo '</div>';
                }
                ?>

            </div>
		</div>	

        </div>

    </div>

</form>

<div class="row m-t-20">
    <div class="col-md-6 p-l-0">
        <div class="TA_C navigation" id="paging">
            <?php echo $pCode; ?>
        </div>
    </div>
    <div class="col-md-6">

    </div>
</div>







<!--script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.js" ></script-->

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