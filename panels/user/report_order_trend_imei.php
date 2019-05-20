<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_tool_id = $request->GetInt('search_tool_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	
	//to find generate array of tools orders
	$sql='select 
			count(oim.id) as count,oim.tool_id,month(oim.date_time) as month,itm.group_id
			from ' . ORDER_IMEI_MASTER . ' oim
			left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
			where year(oim.date_time)=year(now())
			group by oim.tool_id,month(oim.date_time)';
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
			if($id != $row['tool_id'])
			{
				$tempTool = array('order' => $tempOrder);
				$orders[$id] = $tempTool;
				$tempOrder=Array();
				$tempOrder[$row['month']]=$row['count'];
				$id = $row['tool_id'];
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
<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tools_order_trend')); ?> </h1>

<div class="toolbarSkin toolbarBig FR TA_R">
	<a href="report_order_trend_imei.html" class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?> </a><a
	href="report_order_trend_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?> </a><a
	href="report_order_trend_server_log.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?> </a>
</div>
<div class="toolbarSkin toolbarBig FL">
	<a href="#" class="showDialog"><?php echo $graphics->icon('search')?> Filter</a><?php
	if($search_tool_id != 0 or $search_user_id != 0 or $search_supplier_id != 0)
	{
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_trend_imei.html">Show All</a>';
	}
	?>
</div>
<div id="showDialogPanel">
	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_trend_imei.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
	<fieldset>
		<legend></legend>
			<p class="field">
				<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?> </label>
				<select name="search_tool_id" class="textbox_fix combo">
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
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tools')); ?> </th>
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
			$group_name='';
			$sql_tool='select 
							igm.group_name,itm.tool_name,count(oim.id) as count,oim.tool_id,month(oim.date_time) as month
							from ' . ORDER_IMEI_MASTER . ' oim
							left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
							left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
							where year(oim.date_time)=year(now()) '. $qType .' and user_id='.$member->getUserId().'
							group by itm.group_id,oim.tool_id';
			$query_tool=$mysql->query($sql_tool);
			if($mysql->rowCount($query_tool))
			{
				$i=0;
				$rows_tool=$mysql->fetchArray($query_tool);
				//echo $rows_tool[0]['month'];
				foreach($rows_tool as $row_tool)
				{ 
					$i++;
					if($group_name != $row_tool['group_name'])
					{
						echo '<tr><td colspan="14"><h2>' . $row_tool['group_name'] . '</h2></td></tr>';
						$group_name = $row_tool['group_name'];
					}
		?>	
					<tr>
						<td class="TA_R"><?php echo $i; ?></td>
						<td><?php echo $row_tool['tool_name']?></td>
		<?php	
						for($k=1;$k<=12;$k++)
						{
							$order=$orders[$row_tool['tool_id']]['order'];
							if(isset($order[$k]))
							{
								echo '<td>' . $order[$k] . '</td>' ; 
							}
							else
							{
								echo '<td>-</td>';
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
				foreach($rows_tool as $row_tool)
				{
					$c++;
					if($c<=5)
					{
			?>
						{
						
						   name: <?php echo $mysql->quote($row_tool['tool_name']); ?>,
							data: [
								<?php
									for($m=1;$m<=12;$m++)
									{
										
										$order=$orders[$row_tool['tool_id']]['order'];
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