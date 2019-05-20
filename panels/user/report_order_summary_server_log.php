<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_server_log_id = $request->GetInt('search_server_log_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_report')); ?></h1>
</div>

<div class="btn-group">
	<a href="report_order_summary_imei.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?></a>
	<a href="report_order_summary_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></a>
	<a href="report_order_summary_server_log.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
</div>
<div class="btn-group">
	<a href="#" class="showDialog btn btn-default"><?php echo $graphics->icon('search')?> <?php echo $admin->wordTrans($admin->getUserLang(),'Filter'); ?></a><?php
	if($search_server_log_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
	{
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_summary_imei.html" class="btn btn-default">Show All</a>';
	}
	?>
</div>
	<div id="showDialogPanel">
		<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_server_log.html" method="get">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel">
						<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_report')); ?></div>
						<div class="panel-body">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_logs')); ?> </label>
								<select name="search_server_log_id"  class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_logs')); ?> </option>
									<?php
										$sql_log = 'select 
															slg.id as server_log_id, slg.server_log_name, slg.group_id,slgm.group_name
															from ' . SERVER_LOG_MASTER . ' slg
															left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slg.group_id = slgm.id)
															order by slg.group_id, slg.server_log_name';
										$query_log = $mysql->query($sql_log);
										$rows_log = $mysql->fetchArray($query_log);
										$groupName = $groupName2 = '';
										foreach($rows_log as $row_log)
										{
											if($groupName != $row_log['group_name'])
											{
												echo ($groupName != '') ? '<optgroup>' : '';
												echo '<optgroup label="' . $row_log['group_name'] . '" style="font-weight:bold;">';
												$groupName = $row_log['group_name'];
											}
											echo '<option ' . (($row_log['server_log_id'] == $search_server_log_id) ? 'selected="selected"' : '') . ' value="' . $row_log['server_log_id'] . '">' . $mysql->prints($row_log['server_log_name']) . '</option>';
										}
										echo ($groupName != '') ? '</optgroup>' : '';
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
								<select name="search_user_id"  class="form-control">
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
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?> </label>
								<select name="search_supplier_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_suppliers')); ?></option>
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
							<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" />
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
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?> </th>
		</tr>
	<?php
		$i=0;
		$qType = '';
		if($search_server_log_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' oslm.server_log_id = ' . $search_server_log_id;
		}
		
		if($search_user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . '  oslm.user_id = ' . $search_user_id;
		}
		
		if($search_supplier_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' oslm.supplier_id = ' . $search_supplier_id;
		}
		
		$qType = ($qType == '') ? '' :' and' . $qType;
		$group_name='';
		$sql_1 = 'select 
						count(oslm.id) as count, slm.server_log_name, slm.group_id,slgm.group_name
						from ' . ORDER_SERVER_LOG_MASTER . ' oslm
						left join '	. SERVER_LOG_MASTER . ' slm on(oslm.server_log_id=slm.id)
						left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
						where year(oslm.date_time)=2012 '. $qType .' and user_id='.$member->getUserId().'
						group by slm.group_id, oslm.server_log_id';
		$query_1 = $mysql->query($sql_1);
		if($mysql->rowCount($query_1))
		{
			$rows_1=$mysql->fetchArray($query_1);
			foreach($rows_1 as $row_1)
			{
				$i++;
				if($group_name != $row_1['group_name'])
					{
						echo '<tr><td></td><td colspan="5"><h2>' . $row_1['group_name'] . '</h2></td></tr>';
						$group_name = $row_1['group_name'];
					}
		?>		
		<tr>
			<td class="TA_R"><?php echo $i; ?></td>
			<td><?php echo $row_1['server_log_name']?></td>
			<td><?php echo $row_1['count']?></td>
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
                categories: [ 'Server Log', ]
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
					if($mysql->rowCount($query_1))
					{
					foreach($rows_1 as $row_1)
					{
						$c++;
						if($c<=5)
						{
				?>
							{
							<?php echo $admin->wordTrans($admin->getUserLang(),'name'); ?>: <?php echo $mysql->quote($row_1['server_log_name']);?>,
							<?php echo $admin->wordTrans($admin->getUserLang(),'data'); ?>: [<?php echo $row_1['count'];?> ]
				
							},
				<?php
						}
					}
					}
					
				?>
			
			]
        });
    });
    
		</script> 