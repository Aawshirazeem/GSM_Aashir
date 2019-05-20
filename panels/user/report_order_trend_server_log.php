<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_server_log_id = $request->GetInt('search_server_log_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	
	
	$sql = 'select 
						count(oslm.id) as count, slm.server_log_name,oslm.server_log_id,month(oslm.date_time) as month
						from ' . ORDER_SERVER_LOG_MASTER . ' oslm
						left join '	. SERVER_LOG_MASTER . ' slm on(oslm.server_log_id=slm.id)
						where year(oslm.date_time)=year(now())
						group by month(oslm.date_time), oslm.server_log_id';
	$query = $mysql->query($sql);
	if($mysql->rowCount($query))
	{
		$id = 0;
		$name = '';
		$tempOrder=Array();
		$orders=Array();
		$rows=$mysql->fetchArray($query);
		foreach($rows as $row)
		{
			if($id != $row['server_log_id'])
			{
				$tempTool = array('order' => $tempOrder);
				$orders[$id] = $tempTool;
				$tempOrder=Array();
				$tempOrder[$row['month']]=$row['count'];
				$id = $row['server_log_id'];
			}
			else
			{
				$tempOrder[$row['month']]=$row['count'];
			}
			
		}
		$tempTool = array('order' => $tempOrder);
		$orders[$id] = $tempTool;
		$tempOrder=Array();
		//echo '<pre>' .	print_r($orders,true)  .	'</pre>';
	}
	
?>

<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_report')); ?> </h1>

<div class="toolbarSkin toolbarBig FR TA_R">
	<a href="report_order_trend_imei.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?> </a><a
	href="report_order_trend_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?> </a><a
	href="report_order_trend_server_log.html" class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
</div>
<div class="toolbarSkin toolbarBig FL">
	<a href="#" class="showDialog"><?php echo $graphics->icon('search')?> <?php echo $admin->wordTrans($admin->getUserLang(),'Filter'); ?></a><?php
	if($search_server_log_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
	{
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_trend_server_log.html">Show All</a>';
	}
	?>
</div>
<div id="showDialogPanel">

	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_trend_server_log.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
		<fieldset>
		<legend></legend>
		<p class="field">
			<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_logs')); ?> </label>
					<select name="search_server_log_id" class="textbox_fix combo">
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
									echo ($groupName != '') ? '</optgroup>' : '';
									echo '<optgroup label="' . $row_log['group_name'] . '" style="font-weight:bold;">';
									$groupName = $row_log['group_name'];
								}
								echo '<option ' . (($row_log['server_log_id'] == $search_server_log_id) ? 'selected="selected"' : '') . ' value="' . $row_log['server_log_id'] . '">' . $mysql->prints($row_log['server_log_name']) . '</option>';
							}
							echo ($groupName != '') ? '</optgroup>' : '';
						?>
					</select>

			</p>
			<p class="field">
				<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
				<select name="search_user_id" class="textbox_fix combo">
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
			</p>
			<p class="field">
				<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?> </label>
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
			</p>
			<p class="butSkin" style="text-align:center">
				<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" />
			</p>
		</fieldset>
	</form>
</div>
<div class="CL"></div>
<!-- Chart -->
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<!-- End Chart -->


	<table class="MT5 details text_12 ui-corner-all ui-widget-content WD80 FC">
		<tr>
			<td width="16"></td>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </th>
			<?php
			$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			for($i=0;$i<12;$i++)
			{
				echo '<th width="5%">' . $months[$i] . '</th>';
			}
			?>
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
						count(oslm.id) as count, slm.server_log_name, slm.group_id,slgm.group_name,oslm.server_log_id
						from ' . ORDER_SERVER_LOG_MASTER . ' oslm
						left join '	. SERVER_LOG_MASTER . ' slm on(oslm.server_log_id=slm.id)
						left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
						where year(oslm.date_time)=year(now()) '. $qType .'and user_id='.$member->getUserId().'
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
					echo '<tr><td><h2>' . $row_1['group_name'] . '</h2></td></tr>';
					$group_name = $row_1['group_name'];
				}
	?>		
				<tr>
					<td class="TA_R"><?php echo $i; ?></td>
					<td><?php echo $row_1['server_log_name']?></td>
	<?php
				for($k=1;$k<=12;$k++)
				{
					$order=$orders[$row_1['server_log_id']]['order'];
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
				if($mysql->rowCount($query_1))
				{
					foreach($rows_1 as $row_1)
					{
						$c++;
						if($c<=5)
						{
			?>
							{
							
							   name: <?php echo $mysql->quote($row_1['server_log_name']); ?>,
								data: [
									<?php
										for($m=1;$m<=12;$m++)
										{
											
											$order=$orders[$row_1['server_log_id']]['order'];
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
				}
			?>
			]
        });
    });
   </script>