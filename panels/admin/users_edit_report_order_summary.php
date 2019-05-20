<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
            <canvas id="lineChart" height="300" width="650"></canvas>
<canvas id="myChart" width="600" height="400"></canvas>

            
            
            
<section class="panel MT10">
	<div class="panel-heading">
		<?php $lang->prints('lbl_order_summary'); ?>
	</div>
	<div class="panel-body morris">
		<div class="row">
			<div class="col-sm-6"><div id="hero-bar-imei" class="graph"></div></div>
			<div class="col-sm-6"><div id="hero-bar-file" class="graph"></div></div>
		</div>
	</div>
</section>

<script src="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/morris.min.js" type="text/javascript"></script>
<script src="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/raphael-min.js" type="text/javascript"></script>
<link href="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/morris.css" rel="stylesheet" />

 
<script>
	if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }

	Morris.Bar({
		element: 'hero-bar-imei',
		data: [
			<?php
				$sql_imei ='select 
									itm.tool_name,count(oim.id) as count
								from ' . ORDER_IMEI_MASTER . ' oim
								left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
									where oim.user_id = ' . $id . '
									group by oim.tool_id
									order by count(oim.id) DESC
									limit 8';
				$imeiResult = $mysql->getResult($sql_imei);

				$str = '';
				foreach($imeiResult['RESULT'] as $imei){
					$str .= '{device: "' . $imei['tool_name'] . '", geekbench: ' . $imei['count'] . '},';
				}
				echo trim($str, ',');
			?>
		],
		xkey: 'device',
		ykeys: ['geekbench'],
		labels: ['Geekbench'],
		barRatio: 0.4,
		xLabelAngle: 35,
		hideHover: 'auto',
		barColors: ['#6883a3']
	});


	Morris.Bar({
		element: 'hero-bar-file',
		data: [
			<?php
				$str = '';
				$sql_file ='select 
							fsm.service_name,count(ofsm.id) as count,ofsm.supplier_id
							from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
							left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
							where ofsm.user_id = ' . $id . '
							group by ofsm.file_service_id
							order by count(ofsm.id) DESC
							limit 5';
				$fileResult = $mysql->getResult($sql_file);

				foreach($fileResult['RESULT'] as $file){
					$str .= '{device: "' . $file['service_name'] . '", geekbench: ' . $file['count'] . '},';
				}
				echo trim($str, ',');
			?>
		],
		xkey: 'device',
		ykeys: ['geekbench'],
		labels: ['Geekbench'],
		barRatio: 0.4,
		xLabelAngle: 35,
		hideHover: 'auto',
		barColors: ['#6883a3']
	});

     Morris.Donut({
        element: 'hero-donut',
        data: [
          {label: 'Jam', value: 25 },
          {label: 'Frosted', value: 40 },
          {label: 'Custard', value: 25 },
          {label: 'Sugar', value: 10 }
        ],
          colors: ['#41cac0', '#49e2d7', '#34a39b'],
        formatter: function (y) { return y + "%" }
      });
  </script>
