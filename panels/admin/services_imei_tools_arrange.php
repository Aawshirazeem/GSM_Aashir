<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$id = $request->getInt('id');
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_sort_service_tool')); ?></li>
	</ul>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading">
					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_sort_service_tool')); ?>
					<div class="btn-group pull-right">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-xs btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>
					</div>
				</div>
				<div class="panel-body">
					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_arrange_process.do" method="post">
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
				 		<div class="dd nestable_list" data-output="nestable_list_1_output">
							<textarea id="nestable_list_1_output" name="list" class="hidden col-lg-12 form-control"></textarea>
							<ol class="dd-list">
								<?php
									$sql= 'select itm.*, ibm.brand as BrandName, icm.countries_name as CountryName,
													igm.id as gid, igm.group_name, igm.status as groupStatus
											from ' . IMEI_TOOL_MASTER . ' itm
											left join ' . IMEI_BRAND_MASTER . ' ibm on (itm.brand_id = ibm.id)
											left join ' . COUNTRY_MASTER . ' icm on (itm.country_id = icm.id)
											inner join nxt_grp_det b on itm.id=b.ser
                                                                                        inner join ' . IMEI_GROUP_MASTER . ' igm on igm.id=b.grp 
											where b.grp=' . $id . '
											order by itm.sort_order, igm.group_name, itm.tool_name';
									$query = $mysql->query($sql);
									$strReturn = "";
									if($mysql->rowCount($query) > 0)
									{
										$rows = $mysql->fetchArray($query);
										$i = 0;
										$groupName = "";
										foreach($rows as $row)
										{
											$i++;
											if($groupName != $row['group_name'])
											{
												$groupName = $row['group_name'];
												//echo '<tr><td colspan="9"><h2>' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_group_edit.html?id=' . $row['gid'] . '"> <i class="icon-pencil"></i></a></h2></td></tr>';
											}
											echo '<li class="dd-item" data-id="' . $mysql->prints($row['id']) . '"><div class="dd-handle">' . $mysql->prints($row['tool_name']) . '</div></li>';
										}
									}
									else
									{
										echo $graphics->messagebox_warning($admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));
									}
								?>
							</ol>
							<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>
							<input type="submit" value="submit" class="btn btn-success" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.css" />
	<script src="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.js" type="text/javascript"></script>
    <script class="include" type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.nicescroll.js" type="text/javascript"></script>
    
	<script>
	var Nestable = function () {
		
	    var updateOutput = function (e) {
	        var list = e.length ? e : $(e.target),
	            output = list.data('output');
	            console.log(output);
	        if (window.JSON) {
	            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
	        } else {
	            output.val('JSON browser support required for this demo.');
	        }
	    };

	    // activate Nestable for list 1
	    $('.nestable_list').nestable({
	        group: 1
	    }).on('change', updateOutput);

	    // output initial serialised data
	    updateOutput($('.nestable_list').data('output', $('#' + $('.nestable_list').data('output'))));
	    
	}();
	</script>
	
