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
//$months = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
$total_no_days = date('t');
//echo $total_no_days;
$months = array();
for ($a = 1; $a <= $total_no_days; $a++) {
    // echo $a;
    array_push($months, $a);
}

$sql = "select sum(okk.prfit) Profit,okk.dayy from(

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and month(a.date_time)=month(now()) and year(a.date_time)=year(now())

group by day(a.date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.date_time)=month(now())
and year(a.date_time)=year(now())
group by day(a.date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.date_time)=month(now())
and year(a.date_time)=year(now())
group by day(a.date_time)


union all

select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
day(a.date_time) as dayy
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_group_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.date_time)=month(now())
and year(a.date_time)=year(now())
group by day(a.date_time)

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
        foreach ($months as $row) {
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
?>

<div class="row">

    <div class="col-sm-10">
        <label>  Daily Profit Report</label>
        <canvas id="lineChart2" height="300" width="650"></canvas>
    </div>

</div>   

<!--<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/Chart.js/Chart.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.js"></script>
<script>

    //console.log(LineChart);
    var ctx2 = document.getElementById("lineChart2").getContext("2d");
    var data2 = {
        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
        labels: <?= json_encode($months); ?>,
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
                data: <?= json_encode(array_values($IMEIvaluessum)); ?>
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
        var myLineChart = new Chart(ctx2, {
            type: 'line',
            data: data2,
            options: {
                legend: {display: true, }
            }
        });
    });


</script>
