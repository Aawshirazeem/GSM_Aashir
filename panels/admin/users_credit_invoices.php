<?php
defined("_VALID_ACCESS") or die("Restricted Access");



if (isset($_GET['status']))
    $status = $request->GetInt('status');
else
    $status = -1;
?>

<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_option')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Unpaid_Invoice')); ?></li>

        </ol>

        <h4 class="m-t-10"> 

<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_All User Invoices')); ?> 

            <div class="btn-group btn-group-sm pull-right">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=0" class="btn btn-xs <?php echo ($status == 0) ? 'btn-primary' : 'btn-default' ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unpaid')); ?></a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=1" class="btn btn-xs <?php echo ($status == 1) ? 'btn-primary' : 'btn-default' ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_paid')); ?></a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=2" class="btn btn-xs <?php echo ($status == 2) ? 'btn-primary' : 'btn-default' ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?></a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=3" class="btn btn-xs <?php echo ($status == 3) ? 'btn-primary' : 'btn-default' ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Refunded')); ?></a>

            </div>

        </h4>

        <div class="table-responsive">

            <table class="table table-striped table-hover "id ="mytbl">

                <tr>

                        <?php if ($status != 2) { ?>

                        <th>

                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_TRA#')); ?>

                        </th>

                        <?php } else { ?>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),'INV#'); ?></th>

                    <?php } ?>

                    <th>

                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?>

                    </th>

                    <th>

<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?>

                    </th>

                        <?php if ($status != 2) { ?>

                        <th width="180">

                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin Note')); ?>

                        </th>

                            <?php
                        }

                        if ($status == 0) {
                            ?>

                        <th width="100">



                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?>



                        </th>

                        <th width="40">

    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit')); ?>

                        </th>

<?php } ?>

                    <th>

<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?>

                    </th>

<?php if ($status == 0 || $status == 1) { ?>

                        <th width="">

    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?>

                        </th>

<?php } ?>

                </tr>

<?php
$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$limit = CONFIG_ORDER_PAGE_SIZE;

$qLimit = " limit $offset,$limit";

//$extraURL = '&status=1';

if ($status == 0) {

    $sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . '

						order by im.id DESC';
    //echo $sql;

    $extraURL = '&status=0';

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html', $offset, $limit, $extraURL);
} elseif ($status == 1) {

    $sql = 'select 

                              a.id, a.`txn_id`,um.`username`, a.`date_time_paid`,a.date_time,c.`prefix`,sum(b.`amount`) as amount,

      b.`credits`,a.`paid_status`,b.`gateway_id`

      from ' . INVOICE_MASTER . ' as a

      left join ' . INVOICE_LOG . ' as b

      on a.id=b.`inv_id`

      left join ' . CURRENCY_MASTER . ' as c

      on a.`currency_id`=c.`id`

      left join ' . USER_MASTER . ' um on (a.user_id = um.id)



                                       where a.`paid_status` =1

                                       group by b.`inv_id` order by a.id DESC' ;

    $extraURL = '&status=1';

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html', $offset, $limit, $extraURL);
} elseif ($status == 2) {

    $sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . '

						order by im.id DESC';

    $extraURL = '&status=2';

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html', $offset, $limit, $extraURL);
}

if ($status == 3) {

    $sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . '

						order by im.id DESC';

    $extraURL = '&status=3';

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html', $offset, $limit, $extraURL);
}

$query = $mysql->query($sql . $qLimit);

$strReturn = "";

//$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html',$offset,$limit,$extraURL);



$i = $offset;

//get gateway

$get_gateway = 'select id,gateway from ' . GATEWAY_MASTER . ' where status =1';



$gateway = $mysql->query($get_gateway);

$gateways = $mysql->fetchArray($gateway);

//

if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    foreach ($rows as $row) {

        $i++;
        ?>

                        <tr>
                          
                            
                                <?php
                                if ($row['paid_status'] != 2) {
                                    ?>
                                <td width="15%"><?php echo $row['txn_id']; ?>
                                    <br>
                                    <?php
                        switch ($row['paid_status']) {
                            case '0':
                                echo '<span class="label label-info">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unpaid')) . '</span>';
                                break;
                            case '1':
                                echo '<span class="label label-success">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_paid')) . '</span>';
                                break;
                            case '2':
                                echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span>';
                                break;
                            case '3':
                                echo '<span class="label label-danger">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Refunded')) . '</span>';
                                break;
                        }
                        ?>
                                </td>
                                    <?php
                                } else {
                                    ?>
                                <td width="15%"><?php echo $row['id']; ?>
                                <?php
                        switch ($row['paid_status']) {
                            case '0':
                                echo '<span class="label label-info">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unpaid')) . '</span>';
                                break;
                            case '1':
                                echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_paid')) . '</span>';
                                break;
                            case '2':
                                echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span>';
                                break;
                            case '3':
                                echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Refunded')) . '</span>';
                                break;
                        }
                        ?>
                                
                                </td>
                                    <?php
                                }
                                ?>
                            <td><?php echo $row['username']; ?></td>
                            <td width="24%"><?php echo  $finaldate = $admin->datecalculate($row['date_time']); ?><br>
                            <?php 
                            if($row['date_time_paid']!="" && $row['date_time_paid']!="0000-00-00 00:00:00")
                            echo  $finaldate = $admin->datecalculate($row['date_time_paid']); ?>
                            </td>
                         
                            <?php
                            if ($row['paid_status'] == 0) {
                                ?>
                                <td><div class="w-100"><input  id="gt<?php echo $row["id"]; ?>" name="gateway" class="form-control"></div></td> 
                                <?php
                            } elseif ($row['paid_status'] == 1) {
                                ?>
                                <td><?php echo $row["gateway_id"] ?></td>
                                <?php
                            }
                            if ($row['paid_status'] == 0) {
                                ?>
                                <td width="23%"><lable id="lbl<?php echo $row['id']; ?>"><?php echo $row["amount"]; ?></lable> - <input name="amount" id="amnt_inv_vo<?php echo $row["id"]; ?>" maxlength="6" size="6"  value="" class="form-control" style="display:inline-block; width:100px" /></td>
                            <td id="<?php echo $row['id']; ?>">
                                <a href="javascript:void(0)" id="refresh_inv_vo<?php echo $row["id"]; ?>" class="zmdi zmdi-refresh zmdi-hc-2x" onclick="edit_proccess(<?php echo $row["id"]; ?>)"></a>
                            </td>
                                <?php
                            }
                            if ($row['paid_status'] == 0 || $row['paid_status'] == 1) {
                                ?>
                            <td>
                                <input type="hidden" name="credit" id="credit_inv_vo<?php echo $row["id"]; ?>"  value="<?php echo $row['credits']; ?>" /><?php echo $row["credits"]; ?><?php echo $row['prefix']; ?></td>
                                <?php
                            } else {
                                ?>
                            <td><?php echo $row["credits"]; ?><?php echo $row['prefix']; ?></td>
            <?php
        }
        if ($row['paid_status'] != 2) {
            if ($row['paid_status'] == 0) {
                ?>
                                <td width="50%">
                                    <div class="btn-group btn-group-sm" id="somediv<?php echo $row["id"]; ?>">
                                        <a href="#" onclick="edit_proccess_accept(<?php echo $row["id"]; ?>)" class="btn btn-primary btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Accept')); ?></a>
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_unpaid_reject_process.do?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Reject')); ?></a>
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_detail.html?id=<?php echo $row['id']; ?>&type=0" class="btn btn-primary btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_View')); ?></a>
                                    </div>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_detail.html?id=<?php echo $row['id']; ?>&type=1" class="btn btn-primary btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_View')); ?></a>
                                                                           <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_inv_mark_unpaid.html?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_mark_unpaid')); ?></a>

                                    </div>
                                </td>
                <?php
            }
        }
        ?>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="no_record"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>

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

<?php $url1 = CONFIG_PATH_ADMIN . 'user_credit_edit_proccess.php' ?>

<script>

    function edit() {

        $("#amnt").css("background-color", "green");

        $("#amnt").prop("readonly", false);

    }



    function edit_proccess(r) {

        //var trid = $("#r1").

        //alert(r);



        var amount = $("#amnt_inv_vo" + r).val();

        var credit = $("#credit_inv_vo" + r).val();

        var gateway = $("#gt" + r).val();



        //  alert(gateway);

        // var id=



        amount = parseFloat(amount);

        if (amount > 0) {

            $("#refresh_inv_vo" + r).attr('class', 'fa fa-spinner');

            $.ajax({
                url: '<?php echo $url1; ?>',
                data: {id: r, amnt: amount, cr: credit, gt: gateway, type: 0},
                success: function (data) {

                    if (data == 'done') {

                      //  window.location = "users_credit_invoices.html?status=0"
                          $("#amnt_inv_vo" + r).hide();

                        $("#gt" + r).hide();
                         $("#somediv" + r).hide();
                         $("#lbl" + r).text('');
                        $("#lbl" + r).text('Paid Done');
                         $("#refresh_inv_vo" + r).attr('class', 'fa fa-check');

                    } else {

                     //   $("#refresh_inv_vo" + r).attr('class', 'zmdi zmdi-refresh zmdi-hc-2x');

                        $("#amnt_inv_vo" + r).val('');

                        $("#gt" + r).val('');

                        $("#lbl" + r).text(data);
                          $("#refresh_inv_vo" + r).attr('class', 'zmdi zmdi-refresh zmdi-hc-2x');

                    }

                }

                //change_log_id();

            });

        }

    }



    function edit_proccess_accept(r) {

        //var trid = $("#r1").

        //alert(r);



        var amount = $("#lbl" + r).text();

        var credit = $("#credit_inv_vo" + r).val();

        var gateway = $("#gt" + r).val();



        //  alert(gateway);

        // var id=



        amount = parseFloat(amount);

        if (amount > 0) {

            $("#refresh_inv_vo" + r).attr('class', 'throbber-loader');

            $.ajax({
                url: '<?php echo $url1; ?>',
                data: {id: r, amnt: amount, cr: credit, gt: gateway, type: 1},
                success: function (data) {

                    window.location = "users_credit_invoices.html?status=0";

                }

            });

        }

    }

</script>