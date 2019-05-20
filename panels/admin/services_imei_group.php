<?php  defined("_VALID_ACCESS") or die("Restricted Access"); ?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group_manager')); ?></li>
        </ol>
    </div>
</div>

<div class="row">
	<div class="col-xs-8">
    	<h4 class="m-b-20">
        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group_manager')); ?>
            <div class="btn-group btn-group-sm pull-right">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_group_arrange.html" class="btn btn-warning btn-xs"> <i class="fa fa-sort-amount-asc"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_arrange')); ?></a>
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_group_add.html" class="btn btn-danger btn-xs"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_group')); ?></a>
            </div>
        </h4>
        <table class="table table-striped table-hover">
            <tr>
                <th width="16"> </th>
                <th width="16"></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_name')); ?> </th>
                <th width="150"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_total_tools')); ?> </th>
                <th width="80" style="text-align:center"></th>
            </tr>
            <?php
                $sql= 'select count(itm.id) as total, igm.*
                                from ' . IMEI_TOOL_MASTER . ' itm
                                    inner join nxt_grp_det b on itm.id=b.ser
right join ' . IMEI_GROUP_MASTER . ' igm on igm.id=b.grp 
                            --    right join ' . IMEI_GROUP_MASTER . ' igm on (itm.group_id = igm.id)
                        group by igm.id
                        order by sort_order';
              //  echo $sql;
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
                        echo '<td>' . $mysql->prints($row['group_name']) . '</td>';
                        echo '<td><span class="label label-rounded label-info">' . $row['total'] . '</small></td>';
                        echo '<td class="text-right">
                                <a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_group_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
                              </td>';
                        echo '</tr>';
                    }
                }
                else
                {
                    echo '<tr><td colspan="4" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
                }
            ?>
            </table>
    </div>
</div>