<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
<!-- order trend IMEI-->

	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_trend_IMEI')); ?>
		</div>
		<table class="table table-striped table-hover">
			<tr>
				<th width="16"></th>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tools')); ?> </th>
				<?php
				$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				for($i=0;$i<12;$i++)
				{
					echo '<th width="5%">' . $months[$i] . '</th>';
				}
				?>
			</tr>
			<?php
				$qType ='';
				if($id != 0)
				{
					$qType = ' where  oim.user_id = ' . $id;
				}
				$group_name='';
				$sql_tool = 'select 
								igm.group_name,itm.tool_name,count(oim.id) as count,oim.tool_id,month(oim.date_time) as month
								from ' . ORDER_IMEI_MASTER . ' oim
								left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
								left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
								'. $qType .'
								group by itm.group_id,oim.tool_id';
				$query_tool = $mysql->query($sql_tool);
				if($mysql->rowCount($query_tool))
				{
					$i=0;
					$rows_tool=$mysql->fetchArray($query_tool);
					//echo $rows_tool[0]['month'];
					foreach($rows_tool as $tool)
					{ 
						$i++;
						if($group_name != $tool['group_name'])
						{
							echo '<tr><td colspan="14"><b>' . $tool['group_name'] . '</b></td></tr>';
							$group_name = $tool['group_name'];
						}
			?>	
						<tr>
							<td class="text-right"><?php echo $i; ?></td>
							<td><?php echo $tool['tool_name']?></td>
			<?php
							for($k=1;$k<=12;$k++)
							{
								if(isset($order[$k]))
								{
									$order=$orders[$tool['tool_id']]['order'];
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
					echo '<tr><td colspan="14" class="text-center">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '</td></tr>';
				}
			?>
		</table>
	</section>

<!-- order trend file-->

	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_trend_file')); ?>
		</div>
		<table class="table table-striped table-hover">
			<tr>
				<td width="10"></td>
				<th><?php $lang->prints('lbl_services'); ?></th>
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
		
				if($id != 0)
				{
					$qType = ' where  ofsm.user_id = ' . $id;
				}
		
				$sql_file = 'select 
							count(ofsm.id) as count,ofsm.file_service_id,month(ofsm.date_time) as month,fsm.service_name
							from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
							left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
							'. $qType . '
							group by ofsm.file_service_id';
				$query_file = $mysql->query($sql_file);
				if($mysql->rowCount($query_file))
				{
					$i=0;
					$files=$mysql->fetchArray($query_file);
					foreach($files as $file)
					{
							$i++;
					?>	
							<tr>
								<td class="text-right"><?php echo $i; ?></td>
								<td><?php echo $file['service_name']?></td>
					<?php	
							for($k=1;$k<=12;$k++)
							{
								
								if(isset($order[$k]))
								{
									$order=$orders[$file['file_service_id']]['order'];
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

<!-- order trend server log-->

	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_trend_server_log')); ?>
		</div>
		<table class="table table-striped table-hover">
			<tr>
				<td width="16"></td>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?> </th>
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
			if($id != 0)
			{
				$qType = ' where  oslm.user_id = ' . $id;
			}
		
			//$qType = ($qType == '') ? '' :' and' . $qType;

			$group_name='';
			$sql_server_log = 'select 
						count(oslm.id) as count, slm.server_log_name, slm.group_id,slgm.group_name,oslm.server_log_id
						from ' . ORDER_SERVER_LOG_MASTER . ' oslm
						left join '	. SERVER_LOG_MASTER . ' slm on(oslm.server_log_id=slm.id)
						left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
						'. $qType .'
						group by slm.group_id, oslm.server_log_id';

			$query_server_log = $mysql->query($sql_server_log);
			if($mysql->rowCount($query_server_log))
			{
				$rows_server_log = $mysql->fetchArray($query_server_log);
				foreach($rows_server_log as $server_log)
				{
					$i++;
					if($group_name != $server_log['group_name'])
					{
						echo '<tr><td>' . $server_log['group_name'] . '</td></tr>';
						$group_name = $server_log['group_name'];
					}
		?>		
					<tr>
						<td class="text-right"><?php echo $i; ?></td>
						<td><?php echo $server_log['server_log_name']?></td>
		<?php
					for($k=1;$k<=12;$k++)
					{
					
						if(isset($order[$k]))
						{
							$order=$orders[$server_log['server_log_id']]['order'];
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


