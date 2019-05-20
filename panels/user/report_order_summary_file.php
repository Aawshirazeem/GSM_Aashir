<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$search_file_service_id = $request->GetInt('search_file_service_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_order_report')); ?> </h1>
</div>


<div class="btn-group">
	<a href="report_order_summary_imei.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?></a>
	<a href="report_order_summary_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></a>
	<a href="report_order_summary_server_log.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
</div>
<div class="btn-group">
	<a href="#" class="showDialog btn btn-default"><?php echo $graphics->icon('search')?> <?php echo $admin->wordTrans($admin->getUserLang(),'Filter'); ?></a>
	<?php
	if($search_file_service_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
	{
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_summary_imei.html" class="btn btn-default">Show All</a>';
	}
	?>
</div>
	<div id="showDialogPanel">
		<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_file.html" method="get">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel">
						<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_order_report')); ?></div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?> </label>
								<select name="search_file_service_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_services')); ?> </option>
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
									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
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
									<select name="search_supplier_id" class="form-control">
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
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<!-- End Chart -->
<table class="MT5 table table-striped table-hover panel">
	<tr>
			<td width="10"></td>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?> </th>
		</tr>
	<?php
		$i=0;
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
		$sql='select 
					fsm.service_name,count(ofsm.id) as count,ofsm.supplier_id
					from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
					left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
					where year(ofsm.date_time)=2012 '. $qType. ' and user_id='.$member->getUserId().'
					group by ofsm.file_service_id';
		$query=$mysql->query($sql);
		if($mysql->rowCount($query))
		{
			$rows=$mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
	?>	
		<tr>
			<td class="TA_R"><?php echo $i; ?></td>
			<td><h2><?php echo $row['service_name']?></h2></td>
			<td><?php echo $row['count']?></td>
		</tr>
	<?php
			}
		}
	?>	
	</table>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'IMEI Service'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [ 'File Service', ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Order'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.1,
                    borderWidth: 0
                }
            },
            series: [
				<?php 
					$c=0;
					foreach($rows as $row)
					{
						$c++;
						if($c<=5)
						{
				?>
							{
							name: <?php echo $mysql->quote($row['service_name']);?>,
							data: [<?php echo $row['count'];?> ]
				
							},
				<?php
						}
					}
					
				?>
			
			]
        });
    });
    
		</script> 