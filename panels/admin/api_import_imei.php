<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$id = $request->getInt('id'); 

	$validator->formSetAdmin('service_imei_api_import_2561561');

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_masters')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_master')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_services')); ?></li>

        </ol>

    </div>

</div>

<div class="row m-b-20">

	<div class="col-xs-12 col-lg-12">

    	<div class="bs-nav-tabs nav-tabs-warning">

        	<ul class="nav nav-tabs nav-animated-border-from-left">

            	<li class="nav-item"> 

                	<a class="nav-link active" data-toggle="tab" data-target="#nav-tabs-0-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-3"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?></a> 

                </li>

            </ul>

            <div class="tab-content">

            	<div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">

                	<div class="p-t-20">

                    	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_import_imei_process.do" method="post">

                            <input type="hidden" name="id" value="<?php echo $id;?>" />

                            <table class="MT5 table table-striped table-hover panel">

                            <tr>

                                <th width="16"></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool_name')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase_credits')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_selling_credits')); ?></th>

                            </tr>

                            <tr>

                                <th width="16"></th>

                                <th><h2><?php echo $admin->wordTrans($admin->getUserLang(),'ADD credits to each service'); ?> </h2> <?php echo $admin->wordTrans($admin->getUserLang(),'In case the purchase creidts are same as the selling credits'); ?></th>

                                <th colspan="2" class="TA_C"><input type="text" value="1" name="credits_add" class="noEffect spinner" style="width:60px" /></th>

                            </tr>

                            <?php

                                $sql= 'select

                                                ad.id, ad.group_name, ad.service_name, ad.credits, ad.delivery_time,

                                                itm.tool_name

                                            from ' . API_DETAILS . ' ad

                                            left join ' . IMEI_TOOL_MASTER . ' itm on (itm.tool_name = ad.service_name)

                                            where ad.type=1 and ad.api_id=' . $id . ' order by ad.group_name, ad.service_name';
//echo $sql;
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

                                            echo '<tr><td colspan="4">' . $groupName . '</td></tr>';

                                        }

                                        echo '<tr ' . (($row['tool_name'] != '') ? 'class="TC_R"' : '') . '>';

                                        echo '<td>' . (($row['tool_name'] == '') ? '<label class="c-input c-checkbox"><input type="checkbox" class="" name="ids[]" value="' . $row['id'] . '"><span class="c-indicator c-indicator-default"></span></label>' : '') . '</td>';

                                        echo '<td>' . $mysql->prints($row['service_name']) . '' . $mysql->prints($row['delivery_time']) . '</td>';

                                        echo '<td>' . $row['credits'] . '</td>';

                                        echo '<td>

                                                    <input type="hidden" name="credits_org_' . $row['id'] . '" value="' . $row['credits'] . '" />

                                                    <input type="text" name="credits_' . $row['id'] . '" value="' . $row['credits'] . '" class="noEffect spinner ' . (($row['tool_name'] == '') ? '' : 'hidden') . '" style="width:60px;" />

                                                    ' . (($row['tool_name'] == '') ? '' : '<b>Already in list!</b>') . '</td>';

                                        echo '</tr>';

                                    }

                                }

                                else

                                {

                                    echo $graphics->messagebox_warning( $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));

                                }

                            ?>

                            <tr>

                                <th colspan="4">

                                    <select name="clear" id="clearAllItems" class="form-control">

                                        <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Append services to my existing services list.'); ?></option>

                                        <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Clear my existing list and import selected/all services.'); ?></option>

                                    </select>

                                </th>

                            </tr>

                            </table>

                            <p class="TA_C">

                                <span id="butAll" class="hiddefn" ><input type="submit" class="btn btn-danger" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_all_services')); ?>" ></span>

                                <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_services')); ?>" >

                            </p>

                        </form>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-2">

                	<div class="p-t-20">

                    	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_import_file_process.do" method="post">

                            <input type="hidden" name="id" value="<?php echo $id;?>" />

                            

                            <table class="MT5 table table-striped table-hover panel">

                            <tr>

                                <th width="16"></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_name')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase_credits')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_selling_credits')); ?></th>

                            </tr>

                            <tr>

                                <th width="16"></th>

                                <th> <h2><?php echo $admin->wordTrans($admin->getUserLang(),'ADD credits to each service'); ?></h2><?php echo $admin->wordTrans($admin->getUserLang(),'In case the purchase creidts are same as the selling credits'); ?></th>

                                <th colspan="2" class="TA_C"><input type="text" value="1" name="credits_add" class="noEffect spinner" style="width:60px" /></th>

                            </tr>

                            <?php

                                $sql= 'select

                                            ad.id, ad.group_name, ad.service_name, ad.credits, ad.delivery_time,

                                            fsm.service_name as file_service_name

                                            from ' . API_DETAILS . ' ad

                                            left join ' . FILE_SERVICE_MASTER . ' fsm on (fsm.service_name = ad.service_name)

                                            where ad.type=2 and ad.api_id=' . $id . ' order by ad.group_name, ad.service_name'; 

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

                                            echo '<tr><td colspan="4">' . $groupName . '</td></tr>';

                                        }

                                        echo '<tr ' . (($row['file_service_name'] != '') ? 'class="TC_R"' : '') . '>';

                                        echo '<td>' . (($row['file_service_name'] == '') ? '<label class="c-input c-checkbox"><input type="checkbox" class="" name="ids[]" value="' . $row['id'] . '"><span class="c-indicator c-indicator-default"></span></label>' : '') . '</td>';

                                        echo '<td>' . $mysql->prints($row['service_name']) . '' . $mysql->prints($row['delivery_time']) . '</td>';

                                        echo '<td>' . $row['credits'] . '</td>';

                                        echo '<td>

                                                <input type="hidden" name="credits_org_' . $row['id'] . '" value="' . $row['credits'] . '" />

                                                <input type="text" name="credits_' . $row['id'] . '" value="' . $row['credits'] . '" class="noEffect spinner ' . (($row['file_service_name'] == '') ? '' : 'hidden') . '" style="width:60px;" />

                                                ' . (($row['file_service_name'] == '') ? '' : '<b>Already in list!</b>') . '</td>';

                                        echo '</tr>';

                                    }

                                }

                                else

                                {

                                    echo $graphics->messagebox_warning( $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));

                                }

                            ?>

                            <tr>

                                <th colspan="4">

                                    <select name="clear" id="clearAllItems" class="form-control">

                                        <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Append services to my existing services list.'); ?></option>

                                        <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Clear my existing list and import selected/all services.'); ?></option>

                                    </select>

                                </th>

                            </tr>

                            </table>

                            <p class="TA_C">

                                <span id="butAll"><input type="submit" class="btn btn-danger" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_all_services')); ?>" ></span>

                                <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_services')); ?>" >

                            </p>

                        </form>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-3">

                	<div class="p-t-20">

                    	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_import_server_log_process.do" method="post">

                            <input type="hidden" name="id" value="<?php echo $id;?>" />

                            

                            <table class="MT5 table table-striped table-hover panel">

                            <tr>

                                <th width="16"></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase_credits')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_selling_credits')); ?></th>

                            </tr>

                            <tr>

                                <th width="16"></th>

                                <th><h2><?php echo $admin->wordTrans($admin->getUserLang(),'ADD credits to each service'); ?></h2><?php echo $admin->wordTrans($admin->getUserLang(),'In case the purchase creidts are same as the selling credits'); ?></th>

                                <th colspan="2" class="TA_C"><input type="text" value="1" name="credits_add" class="noEffect spinner" style="width:60px" /></th>

                            </tr>

                            <?php

                                $sql= 'select

                                            ad.id, ad.group_name, ad.service_name, ad.credits, ad.delivery_time,

                                            fsm.server_log_name

                                            from ' . API_DETAILS . ' ad

                                            left join ' . SERVER_LOG_MASTER . ' fsm on (fsm.server_log_name = ad.service_name)

                                            where ad.type=3 and ad.api_id=' . $id . ' order by ad.group_name, ad.service_name'; 

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

                                            echo '<tr><td colspan="4">' . $groupName . '</td></tr>';

                                        }

                                        echo '<tr ' . (($row['server_log_name'] != '') ? 'class="TC_R"' : '') . '>';

                                        echo '<td>' . (($row['server_log_name'] == '') ? '<label class="c-input c-checkbox"><input type="checkbox" class="" name="ids[]" value="' . $row['id'] . '"><span class="c-indicator c-indicator-default"></span></label>' : '') . '</td>';

                                        echo '<td>' . $mysql->prints($row['service_name']) . '' . $mysql->prints($row['delivery_time']) . '</td>';

                                        echo '<td>' . $row['credits'] . '</td>';

                                        echo '<td>

                                                    <input type="hidden" name="credits_org_' . $row['id'] . '" value="' . $row['credits'] . '" />

                                                    <input type="text" name="credits_' . $row['id'] . '" value="' . $row['credits'] . '" class="noEffect spinner ' . (($row['server_log_name'] == '') ? '' : 'hidden') . '" style="width:60px;" />

                                                    ' . (($row['server_log_name'] == '') ? '' : '<b>Already in list!</b>') . '</td>';

                                        echo '</tr>';

                                    }

                                }

                                else

                                {

                                    echo $graphics->messagebox_warning($admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));

                                }

                            ?>

                            <tr>

                                <th colspan="4">

                                    <select name="clear" id="clearAllItems" class="form-control">

                                        <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Append services to my existing services list.'); ?></option>

                                        <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Clear my existing list and import selected/all services.'); ?></option>

                                    </select>

                                </th>

                            </tr>

                            </table>

                            <p class="TA_C">

                                <span id="butAll" ><input type="submit" class="btn btn-danger" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_all_services')); ?>" ></span>

                                <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_import_services')); ?>" >

                            </p>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

