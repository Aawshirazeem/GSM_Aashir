<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>

<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></li>
        </ol>
    </div>
</div>

	<div class="row">
			<h4 class="heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_manager')); ?>
		<div class="btn-group pull-right m-b-20">
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs_group.html" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_groups')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs_add.html" class="btn btn-danger btn-sm"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_server_log')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs_quick_edit.html" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_quick_edit')); ?></a>

                </div>
			</h4>
	<div class="table-responsive">		
		<table class="table table-hover table-striped">
		<tr>
			<th width="16"></th>
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_name')); ?> </th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?> </th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
			<th width="300"></th>
		</tr>
		<?php
			$sql= 'select
						slm.*,igm.id as gid, igm.group_name, igm.status as groupStatus, slad.amount,
						count(slu.log_id) as user_count,
						cm.prefix, cm.suffix
					from ' . SERVER_LOG_MASTER . ' slm
					left join ' . SERVER_LOG_GROUP_MASTER . ' igm on(slm.group_id = igm.id)
					left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)
					left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slm.id = slad.log_id and cm.id = slad.currency_id)
					left join ' . SERVER_LOG_USERS . ' slu on(slu.log_id=slm.id)
					group by slm.id
					order by igm.sort_order,igm.group_name,slm.sort_order,slm.server_log_name';
			
			$query = $mysql->query($sql);
			$strReturn = "";
			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				$groupName='';
				$i = 0;
				foreach($rows as $row)
				{
					$i++;
					if($groupName != $row['group_name'])
					{
						$groupName = $row['group_name'];
						//echo '<tr><td colspan="9">' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '<a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_group_edit.html?id=' . $row['gid'] . '"> <i class=" fa fa-pencil"></i></a></td></tr>';
					
                                                echo '<tr>

                                    <th colspan="5"><a href="' . CONFIG_PATH_SITE_ADMIN . 'services_server_tools_arrange.html?id=' . $row['gid'] . '"><i class="fa fa-sort-amount-asc"></i></a> ' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '</th>

                                    <th class="text-right"><a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_group_edit.html?id=' . $row['gid'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a></th>

                                </tr>';

                                        }
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>' . $graphics->status($row['status']) . '</td>';
					echo '<td>' . $mysql->prints($row['server_log_name']) . '</td>';
					echo '<td>' . $mysql->prints($row['delivery_time']) . '</td>';
					echo '<td class="text-center"><span class="label-pill label-primary label-sm">' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</span></td>';
					echo '<td class="text-right">
								<div class="btn-group">
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) .  '</a>
								         <a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_delete.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm" title="This will delte the Selected Service."><i class="fa fa-times"></i></a>
								
</div>
						  </td>';
					echo '</tr>';
				}
			}
                        //hided menus 
                        	//<a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_spl_cr_user_list.html?id=' . $row['id'] .'" class="btn btn-default btn-sm">' . $lang->get('lbl_spl_cr_users') . '</a>
				//<a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_users.html?id=' . $row['id'] .'" class="btn ' . (($row['user_count'] > 0) ? 'btn-warning' : 'btn-default') . ' btn-sm">' . $lang->get('lbl_visible_to') . (($row['user_count'] > 0) ? ' <b>[' . $row['user_count'] . ']</b>' : '') . '</a>
                                                                
			else
			{
				echo '<tr><td colspan="4" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
			}
		?>
		</table>

	</div>
	</div>
