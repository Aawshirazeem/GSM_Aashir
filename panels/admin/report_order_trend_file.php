<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$year = $request->GetStr('year');
	$search_file_service_id = $request->GetInt('search_file_service_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	$clearSearch = false;
	
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
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php $lang->prints('lbl_dashboard'); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Reports'); ?></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Order Trend'); ?></li>
		</ul>
	</div>
</div>
	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_order_trend_file')); ?>
			<div class="btn-group MB10 pull-right">
				<a href="report_order_trend_imei.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service')); ?> </a>
				<a href="report_order_trend_file.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?> </a>
				<a href="report_order_trend_server_log.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?> </a>
				<a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></a>
<?php if($clearSearch == true){ ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN?>report_order_trend_file.html" data-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-undo"></i></a> <?php } ?>
			</div>
		</div>

	<div class="toolbarSkin toolbarBig FL">
		<?php
		if($search_file_service_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
		{
			echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_trend_file.html">Show All</a>';
		}
		?>
	</div>

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
				</div>
				<div class="modal-body">
					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_trend_file.html" method="get">
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_year')); ?> </label>
							<select name="year" class="form-control">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_year')); ?> </option>
								<?php
									$sql_year = 'select distinct(year(date_time)) as year from ' . ORDER_IMEI_MASTER. ' order by year(date_time)';
									$query_year = $mysql->query($sql_year);
									$rows_year = $mysql->fetchArray($query_year);
									$groupName = $groupName2 = '';
									foreach($rows_year as $row_year)
									{
										echo '<option ' . (($row_year['year'] == $year) ? 'selected="selected"' : '') . ' value="' . $mysql->prints($row_year['year']) . '">' . $mysql->prints($row_year['year']) . '</option>';
									}
									echo ($groupName != '') ? '</optgroup>' : '';
								?>
							</select>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></label>
							<select name="search_user_id" class="form-control combo">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>
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
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reseller')); ?> </label>
							<select name="search_supplier_id" class="form-control combo">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_suppliers')); ?> </option>
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
						<div class="form-group">
							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
<!-- Chart -->
<div id="chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<!-- End Chart -->
		<table class="table table-striped table-hover">
			<tr>
				<td width="10"></td>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></th>
				<?php
				$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				for($i=0;$i<12;$i++)
				{
					echo '<th width="5%">' . $months[$i] . '</th>';
				}
				?>
			</tr>
				<?php
					$qType = ' where year(ofsm.date_time)=year(now()) ';
					if($year != '')
					{
						$qType = ' where year(ofsm.date_time) = ' . $year;
					$clearSearch = true;
					}
			
					if($search_user_id != 0)
					{
						$qType .= (($qType == '') ? ' where ' : ' and ') . '  ofsm.user_id = ' . $search_user_id;
					$clearSearch = true;
					}
			
					if($search_supplier_id != 0)
					{
						$qType .= (($qType == '') ? ' where ' : ' and ') . ' ofsm.supplier_id = ' . $search_supplier_id;
					$clearSearch = true;
					}
			
					//$qType = ($qType == '') ? '' :' and' . $qType;
					$sql_1='select 
								count(ofsm.id) as count,ofsm.file_service_id,month(ofsm.date_time) as month,fsm.service_name
								from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
								left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
								'. $qType . '
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
									<td class="text-right"><?php echo $i; ?></td>
									<td><?php echo $row_1['service_name']?></td>
						<?php	
								for($k=1;$k<=12;$k++)
								{
									
									if(isset($order[$k]))
									{
										$order=$orders[$row_1['file_service_id']]['order'];
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
						echo '<tr><td colspan="14" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '</td></tr>';
					}
				?>
			</table>
		</section>
		<script src="<?php echo CONFIG_PATH_EXTRA; ?>chart/highcharts.js"></script>
<script type="text/javascript">
$(function () {
        $('#chart').highcharts({
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
