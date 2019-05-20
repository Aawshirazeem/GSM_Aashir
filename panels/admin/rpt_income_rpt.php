<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
$sql = 'select a.currency,a.prefix from  ' . CURRENCY_MASTER . ' a where a.is_default=1';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $defcuridpfx = $rows[0]['currency'];
    $defcuridpfx2 = $rows[0]['prefix'];
}
// weekly rpt
$days33 = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
$sql = "select sum(MONDAY) MONDAY,sum(TUESDAY) TUESDAY,
sum(WEDNESDAY) WEDNESDAY,sum(THURSDAY) THURSDAY,
sum(FRIDAY) FRIDAY,sum(SATURDAY) SATURDAY,sum(SUNDAY) SUNDAY

 from(
select 

(select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='MONDAY') as MONDAY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='TUESDAY') as TUESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='wednesday') as WEDNESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='thursday') as THURSDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='friday') as FRIDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='saturday') as SATURDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='sunday') as SUNDAY


union

select 

(select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='MONDAY') as MONDAY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='TUESDAY') as TUESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='wednesday') as WEDNESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='thursday') as THURSDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='friday') as FRIDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='saturday') as SATURDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='sunday') as SUNDAY
union

select 

(select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='MONDAY') as MONDAY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='TUESDAY') as TUESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='wednesday') as WEDNESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='thursday') as THURSDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='friday') as FRIDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='saturday') as SATURDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='sunday') as SUNDAY
union

select 

(select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_file_service_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='MONDAY') as MONDAY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='TUESDAY') as TUESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='wednesday') as WEDNESDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='thursday') as THURSDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='friday') as FRIDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='saturday') as SATURDAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and  cast(a.date_time as date) > Subdate(Curdate(), INTERVAL 1 week)
and DAYNAME(a.date_time)='sunday') as SUNDAY)
ok";
$sql = "select sum(okk.prfit) Profit,okk.dayy from(
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and 
YEARWEEK(a.date_time) = YEARWEEK(NOW())

group by DAYNAME(a.date_time)

union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.date_time) = YEARWEEK(NOW())

group by DAYNAME(a.date_time)
union all
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.date_time) = YEARWEEK(NOW())

group by DAYNAME(a.date_time)

union all
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
DAYNAME(a.date_time) as dayy
from  nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and 
YEARWEEK(a.date_time) = YEARWEEK(NOW())

group by DAYNAME(a.date_time)
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
//$datetime = DateTime::createFromFormat('YmdHi',getdate());
//echo $datetime->format('D');
//echo 'Todays Profit:' . $d_Prfit . ' ' . $defcuridpfx2;
?>
<div class="row">

    <div class="col-sm-10">
        <label>  Weekly Profit Report:: <?php echo date('F Y');?></label>
        <canvas id="lineChart" height="300" width="650"></canvas>
    </div>

</div> 

<!--<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/Chart.js/Chart.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.js"></script>
<script>
  
    //console.log(LineChart);
    var ctx = document.getElementById("lineChart").getContext("2d");
    var data = {
        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
        labels: <?= json_encode($days33); ?>,
        datasets: [
            {
                label: "<?php echo $defcuridpfx; ?>",
                fill: true,
                lineTension: 0.1,
                backgroundColor: "rgba(14,50, 21, 1)",
                borderColor: "rgba(14, 23, 21, 1)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(14, 23, 21, 1)",
                pointBackgroundColor: "#0275d8",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(14, 23, 21, 1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: <?= json_encode(array_values($IMEIvalues33)); ?>
            }

        ]
    };
//        var myLineChart = Chart.Line(ctx, {
//            data: data,
//            options: {
//                responsive: true
//            }
//        });
    // var myLineChart = new Chart(ctx).Line(LineChart);
//        var lineDemo = new Chart(ctx).Line(LineChart, {
//            responsive: true,
//            // pointDotRadius: 3,
//            // bezierCurve: false,
//            scaleShowVerticalLines: false,
//            //scaleGridLineColor: 'black'
//        });
    //var myNewChart = new Chart(ctx).PolarArea(data);


    $(document).ready(function (e) {
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                legend: {display: true, }
            }
        });
    });


</script>
