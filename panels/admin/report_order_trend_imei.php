<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$year = $request->GetStr('year');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	$clearSearch = false;

	$arrMonths=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	$qType =' where year(oim.date_time)=year(now()) ';
	if($year != '')
	{
		$qType = ' where year(oim.date_time) = ' . $year;
		$clearSearch = true;
	}
	if($search_user_id != 0)
	{
		$qType .= (($qType == '') ? ' where ' : ' and ') . '  oim.user_id = ' . $search_user_id;
		$clearSearch = true;
	}
	
	if($search_supplier_id != 0)
	{
		$qType .= (($qType == '') ? ' where ' : ' and ') . ' oim.supplier_id = ' . $search_supplier_id;
		$clearSearch = true;
	}


	//to find generate array of tools orders
	$sql='select 
				count(oim.id) as count,
				oim.tool_id,
				itm.tool_name,
				igm.group_name,
				month(oim.date_time) as month,
				itm.group_id
			from ' . ORDER_IMEI_MASTER . ' oim
			left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
			left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
			where year(oim.date_time)=year(now())
			group by oim.tool_id,month(oim.date_time)';
	$result=$mysql->getResult($sql);
//echo '<pre>';
	$months = array();
	$tools = array();
	if($result['COUNT'] > 0)
	{
		foreach($result['RESULT'] as $item)
		{
                    //var_dump($item);
			$months[$item['month']][$item['tool_id']] = $item;
			$tools[$item['group_name']][$item['tool_id']] = $item;
		}
	}
	//var_dump($tools);
        //exit;
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Reports'); ?></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Order Trend'); ?></li>
		</ul>
	</div>
</div>

<link href="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/morris.css" rel="stylesheet" />
<script src="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/morris.min.js" type="text/javascript"></script>
<script src="<?php echo CONFIG_PATH_ASSETS; ?>morris.js-0.4.3/raphael-min.js" type="text/javascript"></script>


	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tools_order_trend')); ?>
			<div class="btn-group pull-right">
				<a href="report_order_trend_imei.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service')); ?> </a>
				<a href="report_order_trend_file.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?> </a>
				<a href="report_order_trend_server_log.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?> </a>
				<a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></a>
				<?php if($clearSearch == true){ ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN?>report_order_trend_imei.html" data-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-undo"></i></a> <?php } ?>
			</div>
		</div>


	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
				</div>
				<div class="modal-body">
					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_trend_imei.html" method="get">
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
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>
							<select name="search_user_id" class="form-control">
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
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier')); ?> </label>
							<select name="search_supplier_id" class="form-control">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_suppliers')); ?> </option>
								<?php
									$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
									$query_usr = $mysql->query($sql_usr);
									$rows_usr = $mysql->fetchArray($query_usr);
									foreach($rows_usr as $row_usr)
									{
										echo '<option ' . (($row_usr["id"] ==$search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />
						</div>
					</fieldset>
				</form>
				</div>
			</div>
		</div>
	</div>
	
            
            
            <table class="table table-striped table-hover">
		<?php $i = 1; if(sizeof($tools) > 0) { ?>
			<?php foreach($tools as $group_name => $items) { ?>
				<tr class="success">
					<th colspan="2"><?php echo $group_name; ?></th>
					<?php foreach($arrMonths as $month){ ?>
						<th><?php echo $month; ?></th>
					<?php } ?>
				</tr>
				<?php foreach($items as $item){ ?>
					<tr>
						<td class="text-right"><?php echo $i++; ?></td>
						<td><?php echo $item['tool_name']?></td>
						
                                                
                                                
                                                
                                                
                                                
                                                <?php 
                                                
                                                $monthID = 0; 
                                                
                                                foreach($arrMonths as $month){ ?>
							<td>
                                                            
								<?php 
                                                               
                                                                $monthID++; 
                                                                 echo $monthID.'-';
                                                                 echo $item['tool_id'].'-';
                                                                // print_r($months[$monthID][$item['tool_id']]);
                                                                if(isset($months[$monthID][$item['tool_id']]))
                                                                    
                                                                    { ?>
									<span class="badge"><?php echo $months[$monthID][$item['tool_id']]['count']; ?></span>
								<?php 
                                                                
                                                                } else 
                                                                    { ?>
									-
								<?php 
                                                                
                                                                    } ?>
							</td>
						<?php 
                                                
                                                
                                                                } 
                                                
                                                ?>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
					</tr>
				<?php }	?>
				<tr>
					<td colspan="14">
						<div id="hero-area<?php echo $item['tool_id']; ?>" class="graph"></div>
						<script>
						$(document).ready(function(){

					      Morris.Area({
					        element: 'hero-area<?php echo $item['tool_id']; ?>',
					        data: [
					        	<?php
					        		$months = '';
					        		foreach($arrMonths as $month){
					        			$monthID++;
					        			$strTools = '';
					        			foreach($items as $item){
					        				$strTools .= '\'' . $item['tool_name'] . '\':' . $item['count'] . ',';
					        			}
					        			$strTools = trim($strTools, ',');
					        			$months .= '{period: \'2015 ' . $month . '\', ' . $strTools . '},' . PHP_EOL;
					        		}
					        		echo trim($months, ',' . PHP_EOL);
					        	?>
					        ],
					        		<?php
										$strTools = '';
					        			foreach($items as $item){
					        				$strTools .= '\'' . $item['tool_name'] . '\',';
					        			}
					        		?>
					          xkey: 'period',
					          ykeys: [<?php echo $strTools; ?>],
					          labels: [<?php echo $strTools; ?>],
					          hideHover: 'auto',
					          lineWidth: 1,
					          pointSize: 5,
					          
					          fillOpacity: 0.5,
					          smooth: true
					      });
						});
					      </script>
					</td>
				</tr>
			<?php } ?>
		<?php } else {
				echo '<tr><td colspan="14" class="text-center">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '</td></tr>';
			}
		?>
</table>
</section>

