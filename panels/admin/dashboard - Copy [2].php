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
$sql = 'select a.id,a.rate from  ' . CURRENCY_MASTER . ' a where a.is_default=1';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $defcurid = $rows[0]['id'];
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


$sql='select sum(a.credits) as incomee,b.currency from ' . INVOICE_MASTER . ' a
inner join ' . CURRENCY_MASTER . ' b
on a.currency_id=b.id and b.is_default=1
where cast(a.date_time as date)=cast(now() as date) and a.paid_status=1
';

//echo $sql;exit;
$qrydata = $mysql->query($sql);

if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $TodayIncome = $rows[0]['incomee'];
    $TodayIncomeSign = $rows[0]['currency'];
}
?>
<div class="row m-b-40">
	<div class="col-xs-12 col-md-12">
        <h4 class="m-t-0 header-title"><b><?php $lang->prints('lbl_Available_Orders_Graph'); ?> </b></h4>

        <ul class="list-inline chart-detail-list text-center">
            <span class="aligncenter"><b><?php $lang->prints('lbl_Year:'); ?> <?php echo date("Y"); ?></b></span><br>
            <!--<li><h5><i class="fa fa-circle m-r-5" style="color: #5d9cec"></i>IMEI</h5></li>
            <li><h5><i class="fa fa-circle m-r-5" style="color: #5fbeaa"></i>SERVER</h5></li>
            <li><h5><i class="fa fa-circle m-r-5" style="color: #101F1C"></i>FIlE</h5></li>-->
        </ul>
        <canvas id="lineChart" height="300" width="650"></canvas>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-5">
        <div class="row m-b-40">
        	<div class="col-xs-12 col-md-12">
                <h4 class="m-t-0 header-title"><b><?php $lang->prints('lbl_Quick_Links'); ?></b></h4>
                <div class="finance-widget-1">
                	<div class="row">
                    	<div class="col-xs-12">
                        	<ul class="list-group">
                            	<li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_admin_login_log.html" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_Login_Log'); ?></span>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=0" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_Unpaid_Invoices'); ?></span>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_user_list.html" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_Mass_Email'); ?></span>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_User_Manage'); ?></span>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_Api_Setting'); ?></span>
                                </li>
                                <li class="list-group-item success">
                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-default btn-outline pull-right btn-sm" style="margin-top: -5px;"><i class="fa fa-long-arrow-right"></i></a>
                                	<span class="text-uppercase text-bold"><?php $lang->prints('lbl_IMEI_Services_List'); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        	</div>
        </div>        
    </div>
    <div class="col-xs-12 col-md-7">
    	<div class="row m-b-10">
        	<div class="col-xs-12 col-md-12">
                <div class="text-widget-10 text-widget-sm bg-success-500">
                	<div class="row">
                    	<div class="col-xs-12">
                        	<div class="left bg-success-700">
                            	<div class="centered">
                                	<div class="circle border-color-white"></div>
                                    <i class="fa fa fa-eur color-white"></i>
                                </div>
                            </div>
                            <div class="right bg-success-500">
                            	<div class="title text- color-white"><?php $lang->prints("lbl_Today's_Revenue"); ?></div>
                                <div class="numbers text- color-white">
                                	<span class="amount color-white"><?php echo $TodayIncome; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row m-b-10">
        	<div class="col-xs-12 col-md-4">
            	<div class="text-widget-2 bg-warning-700">
                	<div class="row">
                    	<div class="col-xs-12 text-center">
                        	<span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $ToatlIMEIORDERS; ?></span>
                            <div class="title color-white"><?php $lang->prints("lbl_IMEI_Orders"); ?></div>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                	<a class="btn btn-warning dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px;"> <?php echo $imineword; ?> <?php $lang->prints("lbl_New_IMEI_Orders"); ?> </a>
                    <div class="dropdown-menu dropdown-menu-scale from-left">
                    	<a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=pending"><b><?php $lang->prints("lbl_New"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imineword; ?></span></a>
                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=locked"><b><?php $lang->prints("lbl_In-Process"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imiinprocess; ?></span></a>
                        <?php if ($imiverfify != 0) { ?>
                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=verify"><b><?php $lang->prints("lbl_Verification"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $imiverfify; ?></span></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
            	<div class="text-widget-2 bg-default-700">
                	<div class="row">
                    	<div class="col-xs-12 text-center">
                        	<span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $ToatlSERVERORDERS; ?></span>
                            <div class="title color-white"><?php $lang->prints("lbl_SERVER_Orders"); ?></div>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                	<a class="btn btn-default dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px;"> <?php echo $servneword; ?> <?php $lang->prints("lbl_New_SERVER_Orders"); ?> </a>
                    <div class="dropdown-menu dropdown-menu-scale from-left">
                    	<a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=pending"><b><?php $lang->prints("lbl_New"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $servneword; ?></span></a>
                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=locked"><b><?php $lang->prints("lbl_In-Process"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $servinprocess; ?></span></a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
            	<div class="text-widget-2 bg-primary-700">
                	<div class="row">
                    	<div class="col-xs-12 text-center">
                        	<span class="amount color-white" count-to="105" value="0" duration="1"><?php echo $ToatlFILEORDERS; ?></span>
                            <div class="title color-white"><?php $lang->prints("lbl_File_Orders"); ?></div>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                	<a class="btn btn-primary dropdown-toggle btn-block btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:14px;"> <?php echo $fileneword; ?> <?php $lang->prints("lbl_New_FILE_Orders"); ?> </a>
                    <div class="dropdown-menu dropdown-menu-scale from-left">
                    	<a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=pending"><b><?php $lang->prints("lbl_New"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $fileneword; ?></span></a>
                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=locked"><b><?php $lang->prints("lbl_In-Process"); ?></b> <span class="m-r-10 label label-rounded label-primary-outline"><?php echo $fileinprocess; ?></span></a>
                    </div>
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
?>
<script>

        var LineChart = {
            //labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
            labels: <?= json_encode($months); ?>,
            datasets: [
                {
					label: "IMEI",
                    fillColor: "rgba(93, 156, 236,0.5)",
                    strokeColor: "rgba(93, 156, 236,1)",
                    pointColor: "rgba(93, 156, 236,1)",
                    pointStrokeColor: "#fff",
                    data: <?= json_encode(array_values($IMEIvalues)); ?>
                },
                {
					label: "SERVER",
                    fillColor: "rgba(95, 190, 170, 0.5)",
                    strokeColor: "rgba(95, 190, 170, 1)",
                    pointColor: "rgba(95, 190, 170, 1)",
                    pointStrokeColor: "#fff",
                    data: <?= json_encode(array_values($SERVERvalues)); ?>
                },
                {
					label: "FILE",
                    fillColor: "rgba(14, 23, 21, 0.5)",
                    strokeColor: "rgba(14, 23, 21, 1)",
                    pointColor: "rgba(14, 23, 21, 1)",
                    pointStrokeColor: "#fff",
                    data: <?= json_encode(array_values($FILEvalues)); ?>
                }
            ]
        };
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
                    backgroundColor: "#5d9cec",//"rgba(14, 23, 21, 1)",
                    borderColor: "#5d9cec",//"rgba(14, 23, 21, 1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "#5d9cec",//"rgba(14, 23, 21, 1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "#5d9cec",//"rgba(14, 23, 21, 1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: <?= json_encode(array_values($IMEIvalues)); ?>
                },
                {
                    label: "SERVER",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "#5fbeaa",//"rgba(9, 136, 43,1)",
                    borderColor: "#5fbeaa",//"rgba(9, 136, 43,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "#5fbeaa",//"rgba(9, 136, 43,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "#5fbeaa",//"rgba(9, 136, 43,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: <?= json_encode(array_values($SERVERvalues)); ?>
                },
                {
                    label: "FILE",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "#101F1C",//"rgba(10, 4, 128,1)",
                    borderColor: "#101F1C",//"rgba(10, 4, 128,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "#101F1C",//"rgba(10, 4, 128,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "#101F1C",//"rgba(10, 4, 128,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: <?= json_encode(array_values($FILEvalues)); ?>
                }
            ]
        };
//        var myLineChart = Chart.Line(ctx, {
//            data: data,
//            options: {
//                responsive: true
//            }
//        });

        //var myLineChart = new Chart(ctx).Line(LineChart);
		/*var myLineChart = new Chart(ctx, {
			type: "line",
			data: LineChart,
			options: {
				legend: { display: false,}
			}
		});*/	
		
		$(document).ready(function(e) {
            	var myLineChart = new Chart(ctx, {
			type: 'bar',
			data: data,
			options: {
				legend: { display: true,}
			}
		});		
        });	
		

//        var lineDemo = new Chart(ctx).Line(LineChart, {
//            responsive: true,
//            // pointDotRadius: 3,
//            // bezierCurve: false,
//            scaleShowVerticalLines: false,
//            //scaleGridLineColor: 'black'
//        });
        //var myNewChart = new Chart(ctx).PolarArea(data);
    </script>