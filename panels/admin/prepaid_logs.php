<?php
defined("_VALID_ACCESS") or die("Restricted Access");
?>

<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></li>
        </ol>
    </div>
</div>
<div class="row">
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <h4 class="panel-heading">
            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_group_manager')); ?>
            <div class="btn-group pull-right m-b-20">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_group.html" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_groups')); ?></a>
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_add.html" class="btn btn-danger btn-sm"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_prepaid_logs')); ?></a>
            </div>
        </h4>
        <tr>
            <th width="16"></th>
            <th width="16"></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_name')); ?> </th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?> </th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_in_account')); ?> </th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
            <th width="380"></th>
        </tr>
        <?php
        $sql = 'select plm.*, igm.id as gid, igm.group_name, igm.status as groupStatus,igm.sort_order,
					   	count(plum.id) inacc, count(plu.prepaid_log_id) as user_count,
					   	plad.amount, cm.prefix, cm.suffix
					   	from ' . PREPAID_LOG_MASTER . ' plm
					   	left join ' . PREPAID_LOG_UN_MASTER . ' plum on (plum.prepaid_log_id= plm.id and plum.status=0)
					   	left join ' . PREPAID_LOG_GROUP_MASTER . ' igm on(plm.group_id = igm.id)
						left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)
						left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on(plm.id = plad.log_id and cm.id = plad.currency_id)
						left join ' . PREPAID_LOG_USERS . ' plu on(plu.prepaid_log_id=plm.id)
					   	group by plm.id
						order by igm.sort_order,igm.status DESC, igm.group_name,plm.sort_order, plm.prepaid_log_name';
        $query = $mysql->query($sql);
        $strReturn = "";
        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            $groupName = '';
            $i = 0;
            foreach ($rows as $row) {
                $i++;
                if ($groupName != $row['group_name']) {
                    $groupName = $row['group_name'];


                    echo '<tr>

                                    <th colspan="6"><a href="' . CONFIG_PATH_SITE_ADMIN . 'services_plog_tools_arrange.html?id=' . $row['gid'] . '"><i class="fa fa-sort-amount-asc"></i></a> ' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '</th>

                                    <th class="text-right"><a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_group_edit.html?id=' . $row['gid'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a></th>

                                </tr>';
                }
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                echo '<td>' . $graphics->status($row['status']) . '</td>';
                echo '<td>' . $mysql->prints($row['prepaid_log_name']) . '</td>';
                echo '<td>' . $mysql->prints($row['delivery_time']) . '</td>';
                echo '<td class="text-center">' . (($row['inacc'] == "0") ? '-' : ('<span class="badge bg-important">' . $row['inacc'] . '</span>')) . '</td>';
                echo '<td class="text-center"><span class="label label-pill label-primary label-lg">' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</span></td>';
                echo '<td>
								<div class="btn-group pull-right">
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_un.html?id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_view_u/p')) . '</a>
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_delete.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm" title="This will delte the Selected Service."><i class="fa fa-times"></i></a>
								
</div>
							  </td>';
                echo '</tr>';
            }
        }
        //menu hide
        //<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_spl_cr_user_list.html?id=' . $row['id'] .'" class="btn btn-default btn-sm">' . $lang->get('lbl_spl_cr_users') . '</a>
        //<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_users.html?id=' . $row['id'] .'" class="btn ' . (($row['user_count'] > 0) ? 'btn-warning' : 'btn-default') . ' btn-sm">' . $lang->get('lbl_visible_to') . '</a>
        //                                        
        else {
            echo '<tr><td colspan="4" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
        }
        ?>
    </table>
</div>
</div>
