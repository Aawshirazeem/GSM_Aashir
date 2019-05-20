<!--<meta http-equiv="refresh" content="120">-->
<?php
defined("_VALID_ACCESS") or die("Restricted Access");
//get imei cout
//$sql = 'select count(a.id) imeiorder from  ' . ORDER_IMEI_MASTER . ' a where a.`status` in (0,1)';
$sql = 'select
(select count(a.id) imeiorder from ' . ORDER_IMEI_MASTER . ' a
where a.`status` in (0,1))imeiorder,
(select count(a.id) serverord from  ' . ORDER_SERVER_LOG_MASTER . ' a
where a.`status` in (0,-1)) serverord,
(select count(*) totalfile from  ' . ORDER_FILE_SERVICE_MASTER . ' a
where a.`status` in (0,-1)) fileod';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $ToatlIMEIORDERS = $rows[0]['imeiorder'];
    $ToatlSERVERORDERS = $rows[0]['serverord'];
    $ToatlFILEORDERS = $rows[0]['fileod'];
    //$drate = $rows[0]['rate'];
}

$sql = 'select 
(select count(a.id) imeiorder from ' . ORDER_IMEI_MASTER . ' a
where a.`status` in (0)) imineword,
(select count(a.id) imeiorder from ' . ORDER_IMEI_MASTER . ' a
where a.`status` in (1)) imiinprocess,
(select count(a.id) imeiverifyod from ' . ORDER_IMEI_MASTER . ' a
where a.`status` in (2) and a.verify=1) imiverfify
';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $imineword = $rows[0]['imineword'];
    $imiinprocess = $rows[0]['imiinprocess'];
    $imiverfify = $rows[0]['imiverfify'];
    //$drate = $rows[0]['rate'];
}

$sql = 'select 
(select count(a.id) imeiorder from  ' . ORDER_SERVER_LOG_MASTER . ' a
where a.`status` in (0)) servneword,
(select count(a.id) imeiorder from  ' . ORDER_SERVER_LOG_MASTER . ' a
where a.`status` in (-1)) servinprocess
';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $servneword = $rows[0]['servneword'];
    $servinprocess = $rows[0]['servinprocess'];
    //$drate = $rows[0]['rate'];
}

$sql = 'select 
(select count(a.id) imeiorder from ' . ORDER_FILE_SERVICE_MASTER . '  a
where a.`status` in (0)) fileneword,
(select count(a.id) imeiorder from ' . ORDER_FILE_SERVICE_MASTER . '  a
where a.`status` in (-1)) fileinprocess
';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $fileneword = $rows[0]['fileneword'];
    $fileinprocess = $rows[0]['fileinprocess'];
    //$drate = $rows[0]['rate'];
}

// get the default currency
$sql = 'select a.id,a.currency,a.rate from  ' . CURRENCY_MASTER . ' a where a.is_default=1';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $defcurid = $rows[0]['id'];
    $defcuridpfx = $rows[0]['currency'];
    $drate = $rows[0]['rate'];
}
// now get the income

$sql = 'select ifnull(sum(FINAL),0) as FinalIncome,ifnull(currency,"EUR") currency from(
select 
cm.currency,A,
 ROUND(if(id=' . $mysql->getInt($defcurid) . ',-A,-(A/cm.rate)),2) FINAL
 from (
select sum(a.credits) A,b.currency_id from ' . CREDIT_TRANSECTION_MASTER . ' a
inner join ' . USER_MASTER . ' b
on a.user_id2=b.id
where 
cast(a.date_time as date)=cast(now() as date) 
and a.trans_type=6 
and a.info="Credits Revoked by Admin"
group by b.currency_id
 ) outt
 inner join ' . CURRENCY_MASTER . ' cm
 on outt.currency_id=cm.id
union
select cm.currency,A,
 ROUND(if(id=' . $mysql->getInt($defcurid) . ',A,A/cm.rate),2) FINAL
 from (
 select sum(ok.plus) A,ok.currency_id from(
 select sum(a.credits) as "plus",b.currency_id from ' . CREDIT_TRANSECTION_MASTER . ' a
 inner join ' . USER_MASTER . ' b
 on a.user_id=b.id
where 
cast(a.date_time as date)=cast(now() as date) 
and a.trans_type=6 
 and a.info="Credits Added by Admin"
 group by b.currency_id
 union all
 select sum(a.credits) as plus,a.currency_id 
from ' . INVOICE_REQUEST . ' a
 where cast(a.date_time as date)=cast(now() as date)
group by a.currency_id) ok
group by ok.currency_id) outt
inner join ' . CURRENCY_MASTER . ' cm
on outt.currency_id=cm.id ) incomee';


$sql = 'select sum(a.credits) as incomee,b.currency from ' . INVOICE_MASTER . ' a
inner join ' . CURRENCY_MASTER . ' b
on a.currency_id=b.id and b.is_default=1
where cast(a.date_time as date)=cast(now() as date) and a.paid_status=1';

//echo $sql;exit;
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $TodayIncome = $rows[0]['incomee'];
    $TodayIncomeSign = $rows[0]['currency'];
}

$sqlGetCurrency = 'SELECT * FROM  `nxt_currency_master` WHERE  `is_default` =1';
$qryCurrencydata = $mysql->query($sqlGetCurrency);
if ($mysql->rowCount($qryCurrencydata) > 0) {
    $rows = $mysql->fetchArray($qryCurrencydata);
    $currencySign = $rows[0]['prefix'];
}


// get cur version
$cur_version = 'v1.5';
$sql = 'select * from ' . SMTP_CONFIG . '
limit 1
';

//echo $sql;exit;
$qrydata = $mysql->query($sql);

if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $cur_version = $rows[0]['cur_ver'];
    // $TodayIncomeSign = $rows[0]['currency'];
}


// check is update
$is_update = 0;
$sql = 'select * from ' . ADMIN_MASTER . ' a
where a.is_update=1

limit 1';

//echo $sql;exit;
$qrydata = $mysql->query($sql);

if ($mysql->rowCount($qrydata) > 0) {
    $is_update = 1;
}
?>
<?php if ($is_update == 1) {
    ?>
    <div class="row m-b-40">
        <div class="col-xs-12 col-md-12" >





            <h3 class="list-inline chart-detail-list text-center" style="color: red">

                <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_New_Update_Is_Available._Go_to_Utilities_and_Check_For_Update')); ?>
            </h3>


        </div>
    </div> <?php } ?>
<div class="row m-b-40">
    <div class="col-xs-12 col-md-8 gsmHide">
        <h4 class="m-t-0 header-title gsmHide">
            <b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Available_Orders_Graph')); ?> </b>
        </h4>

        <ul class="list-inline chart-detail-list text-center gsmHide">
            <span class="aligncenter"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Year:')); ?> <?php echo gmdate("Y"); ?></b></span><br>
            <!--<li><h5><i class="fa fa-circle m-r-5" style="color: #5d9cec"></i>IMEI</h5></li>
            <li><h5><i class="fa fa-circle m-r-5" style="color: #5fbeaa"></i>SERVER</h5></li>
            <li><h5><i class="fa fa-circle m-r-5" style="color: #101F1C"></i>FIlE</h5></li>-->
        </ul>
        <canvas id="lineChart" height="300" width="650" class="gsmHide"></canvas>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="row m-b-40">
            <div class="col-xs-12 col-md-12">
                <h4 class="m-t-0 header-title"><b><?php echo $admin->wordTrans($admin->getUserLang(), 'Quick Links'); ?></b></h4>
                <div class="finance-widget-1">
                    <div class="row">
                        <div class="col-xs-12" style="margin-top: 20px">
                            <ul class="list-group">
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_admin_login_log.html" class="btn btn-default btn-sm btn-outline  parentOpen" style="margin-top: -5px;"><span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Login_Log')); ?></span>
                                    </a>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=0" class="btn btn-default btn-sm btn-outline parentOpen" style="margin-top: -5px;"><span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Unpaid_Invoices')); ?></span>
                                    </a>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>mass_email.html" class="btn btn-default btn-sm btn-outline parentOpen" style="margin-top: -5px;"> <span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Mass_Email')); ?></span></a>

                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-default btn-sm btn-outline parentOpen" style="margin-top: -5px;"> <span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_User_Manage')); ?></span></a>

                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html" class="btn btn-default btn-sm btn-outline parentOpen" style="margin-top: -5px;"><span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Api_Setting')); ?></span>
                                    </a>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-default btn-sm btn-outline parentOpen" style="margin-top: -5px;"><span class="target"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_IMEI_Services_List')); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<div class="row" style="padding-bottom:400px">

    <div class="row">
        <div class="col-xs-12 col-md-12">

            <div class="row m-b-10">
                <div class="col-xs-12 col-md-3">
                    <div class="text-widget-2 bg-primary-700">


                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $imineword; ?></span>

                            </div>
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_Accepted")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $imiinprocess; ?></span>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12 text-center">

                                <div class="title color-white"><h3 class="txtShdw"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_IMEI_Orders")); ?></h3></div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px;"> <?php echo $imineword; ?> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New_IMEI_Orders")); ?> </a>
                        <div class="dropdown-menu dropdown-menu-scale from-left">
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=pending"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imineword; ?></span></a>
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=locked"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_In-Process")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imiinprocess; ?></span></a>
                            <?php if ($imiverfify != 0) { ?>
                                <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=verify"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_Verification")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imiverfify; ?></span></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="text-widget-2 bg-default-700">



                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $servneword; ?></span>

                            </div>
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_Accepted")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $servinprocess; ?></span>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12 text-center">

                                <div class="title color-white"><h3 class="txtShdw"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_SERVER_Orders")); ?></h3></div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="btn btn-default dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px;"> <?php echo $servneword; ?> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New_SERVER_Orders")); ?> </a>
                        <div class="dropdown-menu dropdown-menu-scale from-left">
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=pending"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $servneword; ?></span></a>
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=locked"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_In-Process")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $servinprocess; ?></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="text-widget-2" style="background:#101F1C">



                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $fileneword; ?></span>

                            </div>
                            <div class="col-xs-6 text-center">
                                <div class="title color-white"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_Accepted")); ?></div>
                                <span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $fileinprocess; ?></span>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12 text-center">

                                <div class="title color-white"><h3 class="txtShdw"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_File_Orders")); ?></h3></div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px; background:#101F1C !important; opacity:0.85; border:1px solid #34403E !important"> <?php echo $fileneword; ?> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New_FILE_Orders")); ?> </a>
                        <div class="dropdown-menu dropdown-menu-scale from-left">
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=pending"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_New")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $fileneword; ?></span></a>
                            <a class="dropdown-item parentOpen" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=locked"><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_In-Process")); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $fileinprocess; ?></span></a>
                        </div>
                    </div>
                </div><!-- 3rd end -->
                <div class="col-xs-12 col-md-3">
                    <div class="text-widget-2 text-widget-sm bg-success-500" style="padding:68px 20px;">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="left">
                                    <div class="centered">
                                        <div class="circle border-color-white" style="line-height: 35px;font-size: 30px;">
                                            <span class="color-white">
                                                <?php echo $currencySign; ?><?php echo $TodayIncome; ?>
                                            </span>
                                        </div>
                                        <!--<i class="fa fa fa-eur color-white"></i>-->
                                    </div>
                                </div>
                                <div class="center bg-success-500 text-center">
                                    <div class="title color-white"><h3 class="txtShdw"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints("lbl_Today's_Revenue")); ?></h3></div>
                                    <div class="numbers text- color-white">
                                        <span class="amount color-white"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">




        <div class="col-sm-10">

        </div>


    </div>

    <?php
// income calculations
    $TodayIncomedaily = 0;
    $monthIncomedaily = 0;
    $yearIncomedaily = 0;

    $sql = 'select sum(okk.prfit) as profit from (
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and date(a.reply_date_time)=date(now())
union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and date(a.reply_date_time)=date(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1  and date(a.reply_date_time)=date(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from  nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and date(a.reply_date_time)=date(now())
) okk';
    $qrydata = $mysql->query($sql);
    if ($mysql->rowCount($qrydata) > 0) {
        $rows = $mysql->fetchArray($qrydata);
        $TodayIncomedaily = $rows[0]['profit'];
        //$drate = $rows[0]['rate'];
    }
    $sql = 'select sum(okk.prfit) as profit from (
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and month(a.reply_date_time)=month(now()) and year(a.reply_date_time)=year(now())
union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now()) and year(a.reply_date_time)=year(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1  and month(a.reply_date_time)=month(now()) and year(a.reply_date_time)=year(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from  nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now()) and year(a.reply_date_time)=year(now())
) okk';
    $qrydata = $mysql->query($sql);
    if ($mysql->rowCount($qrydata) > 0) {
        $rows = $mysql->fetchArray($qrydata);
        $monthIncomedaily = $rows[0]['profit'];
        //$drate = $rows[0]['rate'];
    }
    $sql = 'select sum(okk.prfit) as profit from (
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.reply_date_time)=year(now())
union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.reply_date_time)=year(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1  and  year(a.reply_date_time)=year(now())

union all
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from  nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.reply_date_time)=year(now())
) okk';
    $qrydata = $mysql->query($sql);
    if ($mysql->rowCount($qrydata) > 0) {
        $rows = $mysql->fetchArray($qrydata);
        $yearIncomedaily = $rows[0]['profit'];
        //$drate = $rows[0]['rate'];
    }
    ?>
    <br><hr>
    <div class="row">
        <div class="col-xs-12 col-md-8 gsmHide"> 
            <div class="row"> 
                <div class="col-xs-7"> 



                </div> 
                <div class="col-xs-5"> 
                    <div class="dropdown pull-right m-0" style="background:#ccc;"> 
                        <a class="btn no-bg dropdown-toggle no-after" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > <i class="fa fa-ellipsis-v"></i> 
                        </a> 
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-scale from-right"> 
                            <a onclick="getgraph(1);" class="dropdown-item">Daily</a> 
                            <a onclick="getgraph(3);" class="dropdown-item">This week</a>  
                            <a onclick="getgraph(2);" class="dropdown-item">This year</a>

                        </div> 
                    </div> 
<!--                    <button class="btn no-bg pull-right m-0"> <i class="fa fa-cog" id="icon-880"></i> </button> -->
                </div> 

            </div><div id="1"> 
                <canvas id="lineChart2" style="width: 444px; height: 211px;" width="444" height="211"></canvas> </div>
            <div id="2" style="display: none"> 
                <canvas  id="lineChart3" style="width: 444px; height: 211px;" width="444" height="211"></canvas> </div>
            <div id="3" style="display: none"> 
                <canvas  id="lineChart4" style="width: 444px; height: 211px;" width="444" height="211"></canvas> </div>
        </div>
        <div class="col-xs-12 col-md-4 text-center">
            <br><br><br>
            <h4>Today's Profit</h4>
            <h1><?php echo $TodayIncomedaily; ?></h1>
            <h5><?php echo $defcuridpfx; ?></h5>

            <div style="clear:both"></div>
            <div class="col-xs-6" style="background:#588EBD; color:#fff; padding-top:10px;">
                <h5>This Month</h5>
                <h2><?php echo $monthIncomedaily; ?></h2>
                <h6><?php echo $defcuridpfx; ?></h6>
            </div>
            <div class="col-xs-6" style="background:#1BBC9B; color:#fff; padding-top:10px;">
                <h5>This Year</h5>
                <h2><?php echo $yearIncomedaily; ?></h2>
                <h6><?php echo $defcuridpfx; ?></h6>
            </div>
        </div>
    </div>











</div>
</div>
<?php
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");



$sql = '

select

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=1 and a.`status`=2) January,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=2 and a.`status`=2) February,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=3 and a.`status`=2) March,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=4 and a.`status`=2) April,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=5 and a.`status`=2)  May,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=6 and a.`status`=2) June,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=7 and a.`status`=2) July,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=8 and a.`status`=2) August,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=9 and a.`status`=2) September,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=10 and a.`status`=2) October,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=11 and a.`status`=2) November,

(select count(*) total from ' . ORDER_IMEI_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=12 and a.`status`=2) December



union all

select

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=1 and a.`status`=1) January,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=2 and a.`status`=1) February,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=3 and a.`status`=1) March,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=4 and a.`status`=1) April,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=5 and a.`status`=1)  May,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=6 and a.`status`=1) June,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=7 and a.`status`=1) July,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=8 and a.`status`=1) August,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=9 and a.`status`=1) September,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=10 and a.`status`=1) October,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=11 and a.`status`=1) November,

(select count(*) total from ' . ORDER_SERVER_LOG_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=12 and a.`status`=1) December

union all



select

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=1 and a.`status`=1) January,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=2 and a.`status`=1) February,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=3 and a.`status`=1) March,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=4 and a.`status`=1) April,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=5 and a.`status`=1)  May,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=6 and a.`status`=1) June,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=7 and a.`status`=1) July,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=8 and a.`status`=1) August,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=9 and a.`status`=1) September,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=10 and a.`status`=1) October,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=11 and a.`status`=1) November,

(select count(*) total from ' . ORDER_FILE_SERVICE_MASTER . ' as a

where year(a.date_time)=year(now()) and month(a.date_time)=12 and a.`status`=1) December



';

//echo $sql;exit;

$qrydata = $mysql->query($sql);

$IMEIvalues = array();

$SERVERvalues = array();

$FILEvalues = array();



if ($mysql->rowCount($qrydata) > 0) {

    $rows = $mysql->fetchArray($qrydata);

    // var_dump($rows);

    foreach ($months as $row) {

        // echo $row;

        $IMEIvalues[$row] = $rows[0][$row];

        $SERVERvalues[$row] = $rows[1][$row];

        $FILEvalues[$row] = $rows[2][$row];
    }
}
?>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>-->
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/Chart.min.js"></script>


<?php
//$SERVERvalues = array('10','15','20','25','30','35','40','45','50','55','60','65');
//$FILEvalues = array('5','10','15','20','25','30','35','40','45','50','55','60');
//$sql = 'select a.currency,a.prefix from  ' . CURRENCY_MASTER . ' a where a.is_default=1';
//$qrydata = $mysql->query($sql);
//if ($mysql->rowCount($qrydata) > 0) {
//    $rows = $mysql->fetchArray($qrydata);
//    $defcuridpfx = $rows[0]['currency'];
//    $defcuridpfx2 = $rows[0]['prefix'];
//}
// weekly rpt
//$months = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
$total_no_days = date('t');
//echo $total_no_days;
$dayss = array();
for ($a = 1; $a <= $total_no_days; $a++) {
    // echo $a;
    array_push($dayss, $a);
}

$sql = "select sum(okk.prfit) Profit,okk.dayy from(

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.reply_date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and month(a.reply_date_time)=month(now()) and year(a.reply_date_time)=year(now())

group by day(a.reply_date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.reply_date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now())
and year(a.reply_date_time)=year(now())
group by day(a.reply_date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.reply_date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now())
and year(a.reply_date_time)=year(now())
group by day(a.reply_date_time)


union all

select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.reply_date_time) as dayy
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_group_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now())
and year(a.reply_date_time)=year(now())
group by day(a.reply_date_time)

) okk

group by okk.dayy";
$qrydata = $mysql->query($sql);
$IMEIvaluessum = array();

$d_Prfit = 0;
$t = date('d-m-Y');
$todaysname = date("l", strtotime($t));

if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    // var_dump($rows);
    foreach ($rows as $rowdata) {
        //     echo $rowdata['dayy'];
        foreach ($dayss as $row) {
            // echo $rows[0][$row]['dayy'];
            //   echo $row;
            // var_dump($rowdata);
            if ($rowdata['dayy'] == $row && $IMEIvaluessum[$row] == "") {
                if ($rowdata['Profit'] < 0)
                    $IMEIvaluessum[$row] = 0;
                else
                    $IMEIvaluessum[$row] = $rowdata['Profit'];
            }
            else {
                if ($IMEIvaluessum[$row] == "")
                    $IMEIvaluessum[$row] = 0;
            }
        }
    }
}
//var_dump($IMEIvaluessum);
//$datetime = DateTime::createFromFormat('YmdHi',getdate());
//echo $datetime->format('D');
//echo 'Todays Profit:' . $d_Prfit . ' ' . $defcuridpfx2;
// for 2nd one
$months2nd = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$sql = "select sum(okk.prfit) Profit,okk.dayy from(

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.reply_date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.reply_date_time)=year(now())

group by month(a.reply_date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.reply_date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.reply_date_time)=month(now())
and year(a.reply_date_time)=year(now())
group by month(a.reply_date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.reply_date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1
and year(a.reply_date_time)=year(now())
group by month(a.reply_date_time)


union all

select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.reply_date_time) as dayy
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_group_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1
and year(a.reply_date_time)=year(now())
group by month(a.reply_date_time)

) okk

group by okk.dayy";
$qrydata = $mysql->query($sql);
$IMEIvaluessum2nd = array();

if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    // var_dump($rows);
    foreach ($rows as $rowdata) {
        //     echo $rowdata['dayy'];
        foreach ($months as $row) {
            // echo $rows[0][$row]['dayy'];
            //   echo $row;
            // var_dump($rowdata);
            if ($rowdata['dayy'] == $row && $IMEIvaluessum2nd[$row] == "") {
                if ($rowdata['Profit'] < 0)
                    $IMEIvaluessum2nd[$row] = 0;
                else
                    $IMEIvaluessum2nd[$row] = $rowdata['Profit'];
            }

            else {
                if ($IMEIvaluessum2nd[$row] == "")
                    $IMEIvaluessum2nd[$row] = 0;
            }
        }
    }
}

// 3rd rpt
$days33 = array("SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY");
$sql = "select sum(okk.prfit) Profit,okk.dayy from(
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.reply_date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and 
YEARWEEK(a.reply_date_time) = YEARWEEK(NOW())

group by DAYNAME(a.reply_date_time)

union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.reply_date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.reply_date_time) = YEARWEEK(NOW())

group by DAYNAME(a.reply_date_time)
union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.reply_date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.reply_date_time) = YEARWEEK(NOW())

group by DAYNAME(a.reply_date_time)

union all
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.reply_date_time) as dayy
from  nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.reply_date_time) = YEARWEEK(NOW())

group by DAYNAME(a.reply_date_time)
) okk

group by okk.dayy
";
$qrydata = $mysql->query($sql);
$IMEIvalues33 = array();

$d_Prfit = 0;
$t = date('d-m-Y');
$todaysname = date("l", strtotime($t));
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    // var_dump($rows);


    foreach ($rows as $rowdata) {
        //     echo $rowdata['dayy'];
        foreach ($days33 as $row) {


            if (strtoupper($rowdata['dayy']) == $row && $IMEIvalues33[$row] == "") {
                if ($rowdata['Profit'] < 0)
                    $IMEIvalues33[$row] = 0;
                else
                    $IMEIvalues33[$row] = $rowdata['Profit'];
            }

            else {
                if ($IMEIvalues33[$row] == "")
                    $IMEIvalues33[$row] = 0;
            }
        }
    }
}
?>
<script>


                                //console.log(LineChart);
                                var ctx = document.getElementById("lineChart").getContext("2d");
                                var data = {
                                    //  labels: ["January", "February", "March", "April", "May", "June", "July"],
                                    labels: <?= json_encode($months); ?>,
                                    datasets: [
                                        {
                                            label: "IMEI",
                                            fill: false,
                                            lineTension: 0.1,
                                            backgroundColor: "#5d9cec", //"rgba(14, 23, 21, 1)",
                                            borderColor: "#5d9cec", //"rgba(14, 23, 21, 1)",
                                            borderCapStyle: 'butt',
                                            borderDash: [],
                                            borderDashOffset: 0.0,
                                            borderJoinStyle: 'miter',
                                            pointBorderColor: "#5d9cec", //"rgba(14, 23, 21, 1)",
                                            pointBackgroundColor: "#fff",
                                            pointBorderWidth: 1,
                                            pointHoverRadius: 5,
                                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                            pointHoverBorderColor: "#5d9cec", //"rgba(14, 23, 21, 1)",
                                            pointHoverBorderWidth: 2,
                                            pointRadius: 1,
                                            pointHitRadius: 10,
                                            data: <?= json_encode(array_values($IMEIvalues)); ?>
                                        },
                                        {
                                            label: "SERVER",
                                            fill: false,
                                            lineTension: 0.1,
                                            backgroundColor: "#5fbeaa", //"rgba(9, 136, 43,1)",
                                            borderColor: "#5fbeaa", //"rgba(9, 136, 43,1)",
                                            borderCapStyle: 'butt',
                                            borderDash: [],
                                            borderDashOffset: 0.0,
                                            borderJoinStyle: 'miter',
                                            pointBorderColor: "#5fbeaa", //"rgba(9, 136, 43,1)",
                                            pointBackgroundColor: "#fff",
                                            pointBorderWidth: 1,
                                            pointHoverRadius: 5,
                                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                            pointHoverBorderColor: "#5fbeaa", //"rgba(9, 136, 43,1)",
                                            pointHoverBorderWidth: 2,
                                            pointRadius: 1,
                                            pointHitRadius: 10,
                                            data: <?= json_encode(array_values($SERVERvalues)); ?>
                                        },
                                        {
                                            label: "FILE",
                                            fill: false,
                                            lineTension: 0.1,
                                            backgroundColor: "#101F1C", //"rgba(10, 4, 128,1)",
                                            borderColor: "#101F1C", //"rgba(10, 4, 128,1)",
                                            borderCapStyle: 'butt',
                                            borderDash: [],
                                            borderDashOffset: 0.0,
                                            borderJoinStyle: 'miter',
                                            pointBorderColor: "#101F1C", //"rgba(10, 4, 128,1)",
                                            pointBackgroundColor: "#fff",
                                            pointBorderWidth: 1,
                                            pointHoverRadius: 5,
                                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                            pointHoverBorderColor: "#101F1C", //"rgba(10, 4, 128,1)",
                                            pointHoverBorderWidth: 2,
                                            pointRadius: 1,
                                            pointHitRadius: 10,
                                            data: <?= json_encode(array_values($FILEvalues)); ?>
                                        }
                                    ]
                                };


                                var cur_graph = 1;
                                function getgraph(a)
                                {
                                    if (a != cur_graph)
                                    {

                                        cur_graph = a;
                                        if (a == 1)
                                        {
                                            $("#2").hide();
                                            $("#3").hide();
                                        }
                                        if (a == 2)
                                        {
                                            $("#1").hide();
                                            $("#3").hide();
                                        }
                                        if (a == 3)
                                        {
                                            $("#1").hide();
                                            $("#2").hide();
                                        }
                                        // get the new graph here
                                        $("#" + a).show();

                                    }
                                }

// for first chart
                                function drawLineChart1() {
                                    // Create the chart.js data structure using 'labels' and 'data'
                                    var data2 = {
                                        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
                                        labels: <?= json_encode($dayss); ?>,
                                        datasets: [
                                            {
                                                label: "<?php echo $defcuridpfx; ?>",
                                                fill: true,
                                                lineTension: 0.5,
                                                backgroundColor: "#D9534F",
                                                borderColor: "#D9534F",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#D9534F",
                                                pointBackgroundColor: "#D9534F",
                                                pointBorderWidth: 2,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#D9534F",
                                                pointHoverBorderColor: "#D9534F",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($IMEIvaluessum)); ?>
                                            }

                                        ]
                                    };


                                    // Get the context of the canvas element we want to select
                                    var ctx2 = document.getElementById("lineChart2").getContext("2d");
                                    var myLineChart2 = new Chart(ctx2, {
                                        type: 'line',
                                        data: data2,
                                        options: {
                                            legend: {display: true, },
                                            title: {
                                                display: true,
                                                text: 'This Month Report',
                                                fontSize: 18
                                            }
                                        }
                                    });

                                }
                                drawLineChart1();

                                // for second chart
                                function drawLineChart2() {
                                    // Create the chart.js data structure using 'labels' and 'data'
                                    var data2 = {
                                        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
                                        labels: <?= json_encode($months2nd); ?>,
                                        datasets: [
                                            {
                                                label: "<?php echo $defcuridpfx; ?>",
                                                fill: true,
                                                lineTension: 0.5,
                                                backgroundColor: "#D9534F",
                                                borderColor: "#D9534F",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#D9534F",
                                                pointBackgroundColor: "#D9534F",
                                                pointBorderWidth: 2,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#D9534F",
                                                pointHoverBorderColor: "#D9534F",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($IMEIvaluessum2nd)); ?>
                                            }

                                        ]
                                    };


                                    // Get the context of the canvas element we want to select
                                    var ctx3 = document.getElementById("lineChart3").getContext("2d");
                                    var myLineChart3 = new Chart(ctx3, {
                                        type: 'line',
                                        data: data2,
                                        options: {
                                            legend: {display: true, },
                                            title: {
                                                display: true,
                                                text: 'This Year Report',
                                                fontSize: 18
                                            }
                                        }
                                    });

                                }
                                drawLineChart2();


                                // for second chart
                                function drawLineChart3() {
                                    // Create the chart.js data structure using 'labels' and 'data'
                                    var data2 = {
                                        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
                                        labels: <?= json_encode($days33); ?>,
                                        datasets: [
                                            {
                                                label: "<?php echo $defcuridpfx; ?>",
                                                fill: true,
                                                lineTension: 0.5,
                                                backgroundColor: "#D9534F",
                                                borderColor: "#D9534F",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#D9534F",
                                                pointBackgroundColor: "#D9534F",
                                                pointBorderWidth: 2,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#D9534F",
                                                pointHoverBorderColor: "#D9534F",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($IMEIvalues33)); ?>
                                            }

                                        ]
                                    };


                                    // Get the context of the canvas element we want to select
                                    var ctx4 = document.getElementById("lineChart4").getContext("2d");
                                    var myLineChart4 = new Chart(ctx4, {
                                        type: 'line',
                                        data: data2,
                                        options: {
                                            legend: {display: true, },
                                            title: {
                                                display: true,
                                                text: 'This Week Report',
                                                fontSize: 18
                                            }

                                        }
                                    });

                                }
                                drawLineChart3();




                                //for available order graph
                                // for second chart
                                function drawLineChart5() {
                                    // Create the chart.js data structure using 'labels' and 'data'
                                    var data2 = {
                                        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
                                        labels: <?= json_encode($months); ?>,
                                        datasets: [
                                          
                                            {
                                                label: "SERVER",
                                                fill: 'origin',
                                                lineTension: 0.5,
                                                backgroundColor: "#101F1C",
                                                borderColor: "#101F1C",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#101F1C",
                                                pointBackgroundColor: "#fff",
                                                pointBorderWidth: 1,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#rgba(75,192,192,1)",
                                                pointHoverBorderColor: "#101F1C",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($SERVERvalues)); ?>
                                            },
                                                     {
                                                label: "FILE",
                                                fill: 'origin',
                                                lineTension: 0.5,
                                                backgroundColor: "#5fbeaa",
                                                borderColor: "#5fbeaa",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#5fbeaa",
                                                pointBackgroundColor: "#fff",
                                                pointBorderWidth: 1,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#rgba(75,192,192,1)",
                                                pointHoverBorderColor: "#5fbeaa",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($FILEvalues)); ?>
                                            },
                                                      {
                                                label: "IMEI",
                                                fill: 'origin',
                                                lineTension: 0.5,
                                                backgroundColor: "#D9534F",
                                                borderColor: "#D9534F",
                                                borderCapStyle: 'butt',
                                                borderDash: [],
                                                borderDashOffset: 0.0,
                                                borderJoinStyle: 'miter',
                                                pointBorderColor: "#D9534F",
                                                pointBackgroundColor: "#D9534F",
                                                pointBorderWidth: 2,
                                                pointHoverRadius: 5,
                                                pointHoverBackgroundColor: "#D9534F",
                                                pointHoverBorderColor: "#D9534F",
                                                pointHoverBorderWidth: 2,
                                                pointRadius: 5,
                                                pointHitRadius: 10,
                                                data: <?= json_encode(array_values($IMEIvalues)); ?>
                                            }
                                           

                                        ]
                                    };


                                    // Get the context of the canvas element we want to select
                                    var ctx5 = document.getElementById("lineChart").getContext("2d");
                                    var myLineChart5 = new Chart(ctx5, {
                                        type: 'line',
                                        data: data2,
                                        options: {
                                            legend: {display: true, },
                                            title: {
                                                display: true,
                                                fontSize: 18
                                            },
                                            plugins: {
                                                filler: {
                                                    propagate: false
                                                }
                                            }


                                        }
                                    });

                                }
                                drawLineChart5();
</script>