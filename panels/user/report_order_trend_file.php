<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_file_service_id = $request->GetInt('search_file_service_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	
	//to find generate array of tools orders
	$sql='select 
				count(ofsm.id) as count,ofsm.file_service_id,month(ofsm.date_time) as month
				from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
				left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
				where year(ofsm.date_time)=year(now())
				group by ofsm.file_service_id,month(ofsm.date_time)';
	$query=$mysql->query($sql);
	if($mysql->rowCount($query))
	{
		$id = 0;
		$name = '';
		$tempOrder=Array();
		$orders=Array();
		$rows=$mysql->fetchArray($query);
		foreach($rows as $row)
		{
			if($id != $row['file_service_id'])
			{
				$tempTool = array('order' => $tempOrder);
				$orders[$id] = $tempTool;
				$tempOrder=Array();
				$tempOrder[$row['month']]=$row['count'];
				$id = $row['file_service_id'];
			}
			else
			{
				$tempOrder[$row['month']]=$row['count'];
			}
			
		}
		$tempTool = array('order' => $tempOrder);
		$orders[$id] = $tempTool;
		$tempOrder=Array();
	}
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_order_trend')); ?> </h1>
</div>


	<div class="btn-group">
		<a href="report_order_trend_imei.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?> </a>
		<a href="report_order_trend_file.html" class="active btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?> </a>
		<a href="report_order_trend_server_log.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
	</div>
	<div class="btn-group">
		<a href="#" class="showDialog btn btn-default"><?php echo $graphics->icon('search')?><?php echo $admin->wordTrans($admin->getUserLang(),' Filter'); ?></a><?php
		if($search_file_service_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
		{
			echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_trend_file.html" class="btn btn-default">Show All</a>';
		}
		?>
	</div>

	<div id="showDialogPanel">
		<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_trend_file.html" method="get">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel">
						<div class="panel-heading">##########</div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></label>
								<select name="search_file_service_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_services')); ?></option>
									<?php
										$sql_service='select id,service_name from ' . FILE_SERVICE_MASTER . ' order by service_name';
										$query_service=$mysql->query($sql_service);
										$rows_service = $mysql->fetchArray($query_service);
										foreach($rows_service as $row_service)
										{
											echo '<option ' . (($row_service['id'] == $search_file_service_id) ? 'selected="selected"' : '') . ' value="' . $row_service['id'] . '">' . $mysql->prints($row_service['service_name']) . '</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
								<select name="search_user_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_users')); ?> </option>
									<?php
										$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';
										$query_usr = $mysql->query($sql_usr);
										$rows_usr = $mysql->fetchArray($query_usr);
										foreach($rows_usr as $row_usr)
										{
											echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> </label>
								<select name="search_supplier_id" class="textbox_fix combo">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_suppliers')); ?> </option>
									<?php
										$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
										$query_usr = $mysql->query($sql_usr);
										$rows_usr = $mysql->fetchArray($query_usr);
										foreach($rows_usr as $row_usr)
										{
											echo '<option ' . (($row_usr["id"] == $search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
										}
									?>
								</select>
							</div>
						</div> <!-- / panel-body -->
						<div class="panel-footer">
							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success"/>
						</div> <!-- / panel-footer -->
					</div> <!-- / panel -->
				</div> <!-- / col-lg-6 -->
			</div> <!-- / row -->
		</form>
	</div>
	<div class="CL"></div>
	
<!-- Chart -->
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"><?php echo $admin->wordTrans($admin->getUserLang(),'f'); ?></div>
<!-- End Chart -->
	
	<table class="MT5 table table-striped table-hover panel">
		<tr>
			<td width="10"></td>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_services')); ?></th>
			<?php
			$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			for($i=0;$i<12;$i++)
			{
				echo '<th width="5%">' . $months[$i] . '</th>';
			}
			?>
		</tr>
		<?php
			$qType = '';
			if($search_file_service_id != 0)
			{
				$qType .= (($qType != '') ? ' and ' : '') . ' ofsm.file_service_id = ' . $search_file_service_id;
			}
			
			if($search_user_id != 0)
			{
				$qType .= (($qType != '') ? ' and ' : '') . '  ofsm.user_id = ' . $search_user_id;
			}
			
			if($search_supplier_id != 0)
			{
				$qType .= (($qType != '') ? ' and ' : '') . ' ofsm.supplier_id = ' . $search_supplier_id;
			}
			
			$qType = ($qType == '') ? '' :' and' . $qType;
			$sql_1='select 
						count(ofsm.id) as count,ofsm.file_service_id,month(ofsm.date_time) as month,fsm.service_name
						from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
						left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
						where year(ofsm.date_time)=year(now()) '. $qType . ' and user_id='.$member->getUserId().'
						group by ofsm.file_service_id';
			$query_1=$mysql->query($sql_1);
			if($mysql->rowCount($query_1))
			{
				$i=0;
				$rows_1=$mysql->fetchArray($query_1);
				foreach($rows_1 as $row_1)
				{
					$i++;
		?>	
					<tr>
						<td class="TA_R"><?php echo $i; ?></td>
						<td><?php echo $row_1['service_name']?></td>
		<?php	
						for($k=1;$k<=12;$k++)
						{
							$order=$orders[$row_1['file_service_id']]['order'];
							if(isset($order[$k]))
							{
								echo '<td>' . $order[$k] . '</td>' ; 
							}
							else
							{
								echo '<td>' . '</td>';
							}
							
						}
					echo '</tr>';
				}	
			}
			else
			{
				echo '<tr><td colspan="14" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
			}
	?>
</table>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'IMEI Order Trend',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [
			<?php
				$c=0;
				foreach($rows_1 as $row_1)
				{
					$c++;
					if($c<=5)
					{
			?>
						{
						
						   name: <?php echo $mysql->quote($row_1['service_name']); ?>,
							data: [
								<?php
									for($m=1;$m<=12;$m++)
									{
										
										$order=$orders[$row_1['file_service_id']]['order'];
										if(isset($order[$m]))
										{
											echo  $order[$m].',' ; 
										}
										else
										{
											echo '0,';
										}
										
									}
								?>
							] 
						},
			<?php
					}
				}
			?>
			]
        });
    });
   </script>