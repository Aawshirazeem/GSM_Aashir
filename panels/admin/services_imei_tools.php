<?php

defined("_VALID_ACCESS") or die("Restricted Access");

?>


<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_imei_unlocking_tool')); ?></li>

        </ol>

    </div>

</div>

<div class="row">

	<div class="col-xs-12">

    	<h4 class="m-b-20">

        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?>

            <div class="btn-group btn-group-sm pull-right">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_group.html" class="btn btn-xs btn-default"><i class="fa fa-block"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_groups')); ?></a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_add.html" class="btn btn-xs btn-danger"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_imei_tool')); ?></a>
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_quick_edit.html" class="btn btn-xs btn-success"> <i class="fa fa-edit"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Quick_Edit')); ?></a>

            </div>

        </h4>
	<div class="table-responsive">
	
        <table class="table table-striped table-hover">

            <tr>

                <th width="16"></th>

                <th width="16"></th>

                <th width="16"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_api')); ?> </th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_tool_name')); ?> </th>

                <!--th width="80"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand')); ?> </th>

                <th width="80"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?> </th-->

                <th width="60" class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?> </th>

                <th width="130"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?> </th>

                <th colspan="4"></th>

            </tr>

            <?php

            $sql = 'select itm.*, ibm.brand as BrandName, icm.countries_name as CountryName,

                            igm.id as gid, igm.group_name, igm.status as groupStatus,

                            am.api_server, itad.amount,

                            count(itu.tool_id) as user_count,

                            cm.prefix, cm.suffix,pkg.amount as discount,spl.amount as spl_dis

                    from ' . IMEI_TOOL_MASTER . ' itm

                    left join ' . IMEI_BRAND_MASTER . ' ibm on (itm.brand_id = ibm.id)

                    left join ' . API_MASTER . ' am on (itm.api_id = am.id)

                    left join ' . COUNTRY_MASTER . ' icm on (itm.country_id = icm.id)

                    left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)

                    left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)

                    left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itm.id = itad.tool_id and cm.id = itad.currency_id)

                    left join ' . IMEI_TOOL_USERS . ' itu on(itu.tool_id=itm.id)

                                    left join ' . PACKAGE_IMEI_DETAILS . ' pkg on (itm.id=pkg.tool_id) 

                                    left join ' . IMEI_SPL_CREDITS . ' spl on (itm.id=spl.tool_id)    

                    group by itm.id

                    order by igm.sort_order, igm.group_name, itm.sort_order, itm.tool_name';
       //     echo $sql;
            $mysql->query("SET SQL_BIG_SELECTS=1");
                $sql='select distinct(itm.tool_name), itm.*, ibm.brand as BrandName, icm.countries_name as CountryName, igm.id as gid, igm.group_name, 
igm.status as groupStatus, am.api_server, itad.amount,
 cm.prefix, 
 cm.suffix
 from ' . IMEI_TOOL_MASTER . '  itm 
inner join nxt_grp_det b on itm.id=b.ser
inner join ' . IMEI_GROUP_MASTER . '  igm on igm.id=b.grp
 left join ' . IMEI_BRAND_MASTER . '  ibm on (itm.brand_id = ibm.id) 
 left join ' . API_MASTER . '  am on (itm.api_id = am.id) 
 left join ' . COUNTRY_MASTER . '  icm on (itm.country_id = icm.id) 
 left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1) 
 left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itm.id = itad.tool_id and cm.id = itad.currency_id) 
 left join ' . IMEI_TOOL_USERS . ' itu on(itu.tool_id=itm.id) 
order by igm.sort_order, igm.group_name, itm.sort_order, itm.tool_name
';
            $query = $mysql->query($sql);

            $strReturn = "";

            if ($mysql->rowCount($query) > 0) {

                $rows = $mysql->fetchArray($query);

                $i = 0;

                $groupName = "";

                foreach ($rows as $row) {

                    $i++;

                    if ($groupName != $row['group_name']) {

                        $groupName = $row['group_name'];

                        echo '<tr>

                                    <th colspan="9"><a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_arrange.html?id=' . $row['gid'] . '"><i class="fa fa-sort-amount-asc"></i></a> ' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '</th>

                                    <th class="text-right"><a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_group_edit.html?id=' . $row['gid'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a></th>

                                </tr>';

                    }

                    echo '<tr>';

                    echo '<td></td>';

                    echo '<td>' . $graphics->status($row['visible']) . '</td>';

                    echo '<td>' . (($row['api_id'] != '0') ? '<span class="badge bg-important tooltips" data-placement="top" data-toggle="tooltip" data-original-title="' . $mysql->prints($row['api_server']) . '"><i class="fa fa-link"></i></span>' : '') . '</td>';

                    echo '<td>' . $mysql->prints($row['tool_name']) . '</td>';

    

                    /*

                      echo '<td>';

                      switch($row['brand_id'])

                      {

                      case '-1':

                      echo '*All Brands';

                      break;

                      case '0':

                      echo '--';

                      break;

                      default:

                      echo $mysql->prints($row['BrandName']);

                      break;

                      }

                      echo '</td>';

                      echo '<td>';

                      switch($row['country_id'])

                      {

                      case '-1':

                      echo '*All Countries';

                      break;

                      case '0':

                      echo '--';

                      break;

                      default:

                      echo $mysql->prints($row['CountryName']);

                      break;

                      }

                      echo '</td>'; */

    

                    echo '<td class="text-center"><span class="label label-pill label-default">' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</span></td>';

                    echo '<td>' . $mysql->prints($row['delivery_time']) . '</td>';

                    echo '<td>' . $graphics->visible($row['visible']) . '</td>';

                    echo '<td class="text-center" width="40%" colspan="3">

                            <div class="btn-group">';

    

    

                    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

                                

                                <a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_remove.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm prompt" title="Are you sure? You want to remove the selected service."><i class="fa fa-times"></i></a>

                            </div>

                          </td>';
                    echo '</tr>';

                }

            } else {

                echo '<tr><td colspan="11">';
				$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found'));
                echo $graphics->messagebox_warning($msg);

                echo '</td></tr>';

            }

            ?>

        </table>
	</div>
    </div>

</div>