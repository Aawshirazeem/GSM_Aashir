<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_tool_id = $request->GetInt('search_tool_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tools_order_report_summary')); ?></h1>
</div>	

<div class="btn-group">
	<a href="report_order_summary_imei.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?></a>
	<a href="report_order_summary_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></a>
	<a href="report_order_summary_server_log.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
</div>
<div class="btn-group">
	<a href="#" class="showDialog btn btn-default"><?php echo $graphics->icon('search')?><?php echo $admin->wordTrans($admin->getUserLang(),' Filter'); ?></a><?php
	if($search_tool_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
	{
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_summary_imei.html" class="btn btn-default">Show All</a>';
	}
	?>
</div>
<div id="showDialogPanel">
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_imei.html" method="get">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tools_order_report_summary')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?> </label>
						<select name="search_tool_id" class="form-control">
							<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_tools')); ?> </option>
							<?php
								$sql_tool = 'select 
													itm.id as tool_id, itm.tool_name, itm.group_id, itm.credits, itm.delivery_time, itm.status,
													igm.group_name
											from ' . IMEI_TOOL_MASTER . ' itm
											left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
											order by itm.group_id, itm.tool_name';
								$query_tool = $mysql->query($sql_tool);
								$rows_tool = $mysql->fetchArray($query_tool);
								$groupName = $groupName2 = '';
								foreach($rows_tool as $row_tool)
								{
									if($groupName != $row_tool['group_name'])
									{
										echo ($groupName != '') ? '</optgroup>' : '';
										echo '<optgroup label="' . $row_tool['group_name'] . '" style="font-weight:bold;">';
										$groupName = $row_tool['group_name'];
									}
									echo '<option ' . (($row_tool['tool_id'] == $search_tool_id) ? 'selected="selected"' : '') . ' value="' . $row_tool['tool_id'] . '">' . $mysql->prints($row_tool['tool_name']) . '</option>';
								}
								echo ($groupName != '') ? '</optgroup>' : '';
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
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?> </label>
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
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />
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
			<td width="16"></td>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?></th>
		</tr>
	<?php
		$i=0;
		$group_name='';
		$qType = '';
		if($search_tool_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' oim.tool_id = ' . $search_tool_id;
		}
		
		if($search_user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . '  oim.user_id = ' . $search_user_id;
		}
		
		if($search_supplier_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' oim.supplier_id = ' . $search_supplier_id;
		}
		
		$qType = ($qType == '') ? '' :' and' . $qType;
		
		$sql='select 
				igm.group_name,itm.tool_name,count(oim.id) as count
				from ' . ORDER_IMEI_MASTER . ' oim
				left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
				left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
				where year(oim.date_time)=2012 '. $qType .' and user_id='.$member->getUserId().'
				group by oim.tool_id
				order by itm.group_id';
		$query=$mysql->query($sql);
		if($mysql->rowCount($query))
		{
			$rows=$mysql->fetchArray($query);
			foreach($rows as $row)
			{ 
				$i++;
				if($group_name != $row['group_name'])
				{
					echo '<tr><td colspan="5"><h2>' . $row['group_name'] . '</h2></td></tr>';
					$group_name = $row['group_name'];
				} 
	?>	
		<tr>
			<td></td>
			<td><?php echo $row['tool_name']?></td>
			<td><b><?php echo $row['count']?></b></td>
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
                categories: [ 'imei', ]
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
								<?php echo $admin->wordTrans($admin->getUserLang(),'name'); ?>: <?php echo $mysql->quote($row['tool_name']);?>,
								<?php echo $admin->wordTrans($admin->getUserLang(),'data'); ?>: [<?php echo $row['count'];?> ]
				
							},
				<?php
						}
					}
					
				?>
			
			]
        });
    });
    
		</script> 