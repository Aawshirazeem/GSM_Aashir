<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_manager')); ?></li>
        </ol>
    </div>
</div>

<div class="">
	<h4 class="m-b-20">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_manager')); ?>
		<div class="btn-group pull-right">
                    	<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_file_arrange.html" class="btn btn-danger btn-sm"><i class="fa fa-sort-amount-asc"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_arrange')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_file_add.html" class="btn btn-danger btn-sm"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_file_service')); ?> </a>
                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_quick_edit.html" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Quick_Edit')); ?></a>

		</div>
	</h4>
	<div class="table-responsive">
	<table class="table table-striped table-hover">
	<tr>
		<th width="16"></th>
		<th width="16"></th>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_name')); ?> </th>
		<th width="100"></th>
		<th width="300"></th>
	</tr>
	<?php
		$sql= 'select
						fsm.*, am.api_server, fsad.amount,
						count(fsu.service_id) as user_count,
						cm.prefix, cm.suffix
					from ' . FILE_SERVICE_MASTER . ' fsm
					left join ' . API_MASTER . ' am on (fsm.api_id = am.id)
					left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)
					left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsm.id = fsad.service_id and cm.id = fsad.currency_id)
					left join ' . FILE_SERVICE_USERS . ' fsu on(fsu.service_id=fsm.id)
					group by fsm.id
					order by fsm.sort_order,fsm.service_name';
		$query = $mysql->query($sql);
		$strReturn = "";
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$i = 0;
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				echo '<td>' . $i . '</td>';
				echo '<td>' . $graphics->status($row['status']) . '</td>';
				echo '<td>' . (($row['api_id'] != '0') ? '<span class="badge bg-important tooltips" data-placement="top" data-toggle="tooltip" data-original-title="' . $mysql->prints($row['api_server']) . '"><i class="fa fa-link"></i></span>' : '') . '</td>';
				echo '<td>' . $mysql->prints($row['service_name']) . '</td>';
				echo '<td class="text-center"><span class="m-r-10 label label-pill label-primary label-lg">' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</span></td>';
				echo '<td class="text-right">
						<div class="btn-group">
                                                	<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_white_list.html?s_id=' . $row['id'] . '" class="btn btn-success btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_manage_extentions')) . '</a>
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_service_remove.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm" title="This will delte the Selected Service."><i class="fa fa-times"></i></a>
    
</div>
					  </td>';
				echo '</tr>';
			}
		}
                //removed menu from above foreach
              //  <a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_spl_cr_user_list.html?id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $lang->get('lbl_spl_cr_users') . '</a>
	     //<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_users.html?id=' . $row['id'] . '" class="btn ' . (($row['user_count'] > 0) ? 'btn-warning' : 'btn-default') . ' btn-sm">' . $lang->get('lbl_visible_to') . (($row['user_count'] > 0) ? ' <b>[' . $row['user_count'] . ']</b>' : '') . '</a>
                                                        
                //
		else
		{
			echo '<tr><td colspan="5" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
		}
	?>
	</table>
	</div>
</div>

