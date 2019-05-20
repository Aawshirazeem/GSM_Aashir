<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$supplier->checkLogin();
$supplier->reject();
$limit = $request->getInt('limit');

$grandTotal = 0;

$no_paging = $request->GetInt('no_paging');
if (isset($_POST['supplier_id']))
    $supplier_id = $request->PostStr('supplier_id');
else
    $supplier_id = $request->GetStr('supplier_id');

if (isset($_POST['imei_prefix']))
    $imei_prefix = $request->PostCheck('imei_prefix');
else
    $imei_prefix = $request->GetCheck('imei_prefix');

if (isset($_POST['type']))
    $type = $request->PostStr('type');
else
    $type = $request->GetStr('type');

if (isset($_POST['imei']))
    $imei = $request->PostStr('imei');
else
    $imei = $request->GetStr('imei');

if (isset($_POST['imei_code'])) {
    $imei_code = $request->PostStr('imei_code');
    $no_paging = 2;
} else {
    $imei_code = $request->GetStr('imei_code');
}

$search_tool_id = $request->GetInt('search_tool_id');
$ip = $request->GetStr('ip');
$user_id = $supplier->getUserId();

$hide_user = $request->GetInt('hide_user');
//echo $user_id;exit;
//split IMEI in new line
if ($imei_code != '') {
    $imei = '';
}
//split IMEI in new line
//$imeis = explode("&#13;&#10;", $imei);
$imeis = explode(PHP_EOL, $imei);
$txtImeis = "";
foreach ($imeis as $im) {
    if (is_numeric($im)) {
        $txtImeis .= $im . ',';
    } else {
        if ($im != $grandTotal) {
            $graphics->messagebox($im . ': Not a valid IMEI Number!');
        }
    }
}
$txtImeis = rtrim($txtImeis, ',');


$delimiter = $request->GetStr('delimiter');

if ($imei_code != '') {
    //split IMEI in new line
    //$imeis = explode("&#13;&#10;", $imei_code);
    $imeis = explode(PHP_EOL, $imei_code);
    $txtImeis = "";
    if ($delimiter == '') {
        $delimiter = ' ';
    }
    foreach ($imeis as $im) {
        if ($imei_prefix == 1) {
            $imItems = explode($delimiter, $im);
            $chkIMEI = false;
            foreach ($imItems as $item) {
                if (preg_match('/^\d{15}$/', $item) && $chkIMEI == false) {
                    $im = substr($im, strpos($im, $item));
                    $chkIMEI = true;
                }
            }
        }
        $tempIMEI = strstr($im, $delimiter, true);
        if (is_numeric($tempIMEI)) {
            $txtImeis .= $tempIMEI . ',';
            //$imeiCodes[strstr($im, ' ', true)] = trim(strstr($im, ' '));
            $imeiCodes[$tempIMEI] = trim(strstr($im, $delimiter), $delimiter);
        }
    }
    $txtImeis = rtrim($txtImeis, ',');
}


$showUser = $showCredits = 0;
$sql = 'select * from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $showUser = $rows[0]['show_user'];
    $showCredits = $rows[0]['show_credits'];
}

$newTotal = 0;
$sqlCount = 'select im.status, count(im.id) as total
					from ' . ORDER_IMEI_MASTER . ' im
					where im.status = 0 and 
					(im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
						or im.tool_id = 19)
					group by im.status';
$queryCount = $mysql->query($sqlCount);
if ($mysql->rowCount($queryCount) > 0) {
    $rows = $mysql->fetchArray($queryCount);
    foreach ($rows as $row) {
        $newTotal = $row['total'];
    }
}
$pendingTotal = 0;
$sqlCount = 'select im.status, count(im.id) as total
					from ' . ORDER_IMEI_MASTER . ' im
					where im.status = 1 and 
					im.supplier_id=' . $supplier->getUserId() . ' and
					(im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
						or im.tool_id = 19)
					group by im.status';
$queryCount = $mysql->query($sqlCount);
if ($mysql->rowCount($queryCount) > 0) {
    $rows = $mysql->fetchArray($queryCount);
    foreach ($rows as $row) {
        $pendingTotal = $row['total'];
    }
}

$sqlCount = 'select count(id) as verificationIMEI
						from ' . ORDER_IMEI_MASTER . ' im
						where (im.status=2 and im.verify=1) and
							(im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
								or im.tool_id = 19)';

$queryCount = $mysql->query($sqlCount);
$rowCount = 0;
if ($mysql->rowCount($queryCount) > 0) {
    $rowsCount = $mysql->fetchArray($queryCount);
    $rowCount = $rowsCount[0];
}

$verifyCount = ($rowCount['verificationIMEI'] > 0) ? (' [' . $rowCount['verificationIMEI'] . ']') : '';

$paging = new paging();
$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
if ($limit == '') {
    $limit = 10;
}

$pStringLimit = '';
$pString = '';
if ($type != '') {
    $pStringLimit .= (($pStringLimit != '') ? '&' : '' ) . 'type=' . $type;
}
if ($supplier_id != 0) {
    $pStringLimit .= (($pStringLimit != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
    $pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
}
if ($ip != '') {
    $pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'ip=' . $ip;
}
if ($user_id != 0) {
    $pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'user_id=' . $user_id;
}
if ($search_tool_id != 0) {
    $pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;
}
$pStringLimit = trim($pStringLimit, '&');


if ($limit != 0 && $no_paging != 0) {
    $pString .= (($pString != '') ? '&' : '' ) . 'limit=' . $limit;
}
if ($ip != '') {
    $pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
}
if ($user_id != 0) {
    $pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
}
if ($search_tool_id != 0) {
    $pString .= (($pString != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;
}
$pString = trim($pString, '&');

$qLimit = " limit $offset,$limit";
$extraURL = '&type=' . $type . '&supplier_id=' . $supplier_id . '&search_tool_id=' . $search_tool_id . '&ip=' . $ip;
?>
<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_orders')); ?></h1>
<div class="row hidden" id="loadingPanel">
    <div class="col-md-8 col-md-offset-2" style="margin-top:15%;margin-bottom:15%;">

        <!-- Progress Bar -->
        <h1 class="text-center" id="h1Wait"><i class="fa fa-refresh fa fa-large fa fa-spin"></i><?php echo $admin->wordTrans($admin->getUserLang(),' Please wait...'); ?></h1>
        <div class="progress progress-striped active hiddenf" id="pBarSubmit">	
            <div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                <span class="sr-only"><?php echo $admin->wordTrans($admin->getUserLang(),'0% Complete'); ?></span>
            </div>
        </div>
        <!-- / Progress Bar -->	


        <!-- Message after successfull submission -->
        <h1 class="text-center hidden" id="h1Done"><i class="fa fa-check fa fa-large text-success"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Done'); ?></h1>
        <div class="text-center hidden" id="panelButtons">
            <form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_pending_final.html" method="post" name="frmDownload" id="frmDownload">
                <div id="tempDownloadIMEIS"></div>
                <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER ?>order_imei.html?type=<?php echo $type ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Go Back'); ?></a>
                <button type="submit" name="process" class="btn btn-primary btn-sm"><i class="fa fa-download-alt"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_download')); ?></button>
            </form>
        </div>
        <!-- / Message after successfull submission -->


        <!-- Error Message -->
        <div class="alert alert-danger hidden" id="h1Error"><i class="fa fa-times fa fa-large"></i> <span id="h1ErrorText"><?php echo $admin->wordTrans($admin->getUserLang(),'There is some unexpected error!'); ?></span></div>
        <div class="text-center hidden" id="panelButtonsCredits">
            <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER ?>order_imei.html?type=<?php echo $type ?>" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'There is some error! Go Back'); ?></a>
        </div>
        <!-- / Error Message -->



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

                <form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html" method="get">
                    <fieldset>
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
                        <input type="hidden" name="ip" value="<?php echo $ip; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unlocking_tool')); ?> </label>
                            <select name="search_tool_id" class="form-control">
                                <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_tools')); ?> </option>
                                <?php
                                $sql_tool = 'select 
										itm.id as tool_id, itm.tool_name, itm.group_id, itm.credits, itm.delivery_time, itm.status,
										igm.group_name
										from ' . IMEI_TOOL_MASTER . ' itm
										left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
										where  (itm.id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
												or itm.id = 19)
										order by itm.group_id, itm.tool_name';

                                $sql_tool = 'select
											tm.*,
											itad.amount, itad.amount_purchase,
											sd.tool as splTool,
											sd.credits_purchase as credits_purchase_tool, 
											igm.group_name,
											cm.prefix, cm.suffix
										from ' . IMEI_TOOL_MASTER . ' tm
										left join ' . IMEI_SUPPLIER_DETAILS . ' sd on (tm.id = sd.tool and sd.supplier_id = ' . $supplier->getUserId() . ')
										left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
										left join ' . CURRENCY_MASTER . ' cm on(cm.is_default=1)
										left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = cm.id)
										where tm.visible=1
										order by igm.sort_order, tm.sort_order, tm.tool_name';



                                $query_tool = $mysql->query($sql_tool);
                                $rows_tool = $mysql->fetchArray($query_tool);
                                $groupName = $groupName2 = '';
                                foreach ($rows_tool as $row_tool) {
                                    if ($groupName != $row_tool['group_name']) {
                                        echo ($groupName != '') ? '</optgroup>' : '';
                                        echo '<optgroup label="' . $row_tool['group_name'] . '" style="font-weight:bold;">';
                                        $groupName = $row_tool['group_name'];
                                    }
                                    echo '<option ' . (($row_tool['tool_id'] == $search_tool_id) ? 'selected="selected"' : '') . ' value="' . $row_tool['tool_id'] . '">' . $mysql->prints($row_tool['tool_name']) . '</option>';
                                }
                                echo ($groupName != '') ? '</optgroup>' : '';
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei')); ?> </label>
                                <textarea name="imei" class="form-control" id="imei" rows="6"><?php echo $imei; ?></textarea>
                                <input type="hidden" name="type" value="<?php echo $type; ?>">
                                <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
                                <input type="hidden" name="ip" value="<?php echo $ip; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_with_code')); ?></label>
                                <textarea name="imei_code" class="form-control" id="imei_code" rows="6"><?php echo $imei_code; ?></textarea>
                                <br />
                                <div class="form-group">
                                    <select name="delimiter" class="form-control">
                                        <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'[Space]'); ?></option>
                                        <option value=":">:</option>
                                        <option value=";">;</option>
                                        <option value=",">,</option>
                                    </select>
                                </div>
                                <label><input type="checkbox" name="imei_prefix" /><?php echo $admin->wordTrans($admin->getUserLang(),'Remove everything before IMEI number'); ?></label>
                            </div>
                        </div>
                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success btn-sm" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>







<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frmAjaxOrder" id="frmAjaxOrder">





    <input type="hidden" name="imei" value="<?php echo $imei; ?>">
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <input type="hidden" name="reqeustType" value="<?php echo $type; ?>">
    <input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id; ?>">
    <input type="hidden" name="ip" value="<?php echo $ip; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="limit" value="<?php echo $limit; ?>">

    <?php
    ob_flush();
    ob_start();
    ?>

    <div class="row">
    	<div class="col-sm-12">
        	<div class="btn-group">
            	<div class="btn-group extra">
                	<div class="btn-group extra">
					<?php
                    	echo '<a class="btn ' . (($type == 'pending') ? 'btn-primary btn-sm dropdown-toggle' : 'btn-default btn-sm dropdown-toggle') . '" data-toggle="dropdown">
								<i class="fa fa-asterisk fa fa-large"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending')) . ' ' . (($newTotal != '0') ? ' <span class="badge">' . $newTotal : '') . '</span>
								<span class="caret"></span>
							</a>
							<div class="dropdown-menu dropdown-menu-scale from-left">';
                        echo '<a href="order_imei.html?type=pending" class="dropdown-item">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_new_orders')) . '</a>';
                        $sqlPending = 'select tm.id as id, count(im.id) as total,
													tm.tool_name as tool_name
													from ' . ORDER_IMEI_MASTER . ' im
													left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
													where im.status=0 and
													(im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
														or im.tool_id = 19)
													group by im.tool_id';
                        $queryPending = $mysql->query($sqlPending);
                        if ($mysql->rowCount($queryPending) > 0) {
                            $rows = $mysql->fetchArray($queryPending);
                            foreach ($rows as $row) {
                                echo '<a href="order_imei.html?type=pending&search_tool_id=' . $row['id'] . '" class="dropdown-item"><span class="badge bg-danger pull-right">' . $row['total'] . '</span>' . $row['tool_name'] . '</a>';
                            }
                        }
                        echo '</div>';
                        ?>
                    </div>

                    <div class="btn-group extra">
						<?php
                            echo '<a class="btn ' . (($type == 'locked') ? 'btn-primary btn-sm dropdown-toggle' : 'btn-default btn-sm dropdown-toggle') . '" data-toggle="dropdown">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_locked')) . ' ' . (($pendingTotal != '0') ? ' <span class="badge">' . $pendingTotal : '') . '</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-scale from-left">';
                                echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html?type=locked"  class="dropdown-item">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_pending_orders')) . '</a>';
                                $sqlPending = 'select tm.id as id, count(im.id) as total,
                                                    tm.tool_name as tool_name
                                                    from ' . ORDER_IMEI_MASTER . ' im
                                                    left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
                                                    where im.status=1 and 
                                                    im.supplier_id=' . $supplier->getUserId() . ' and
                                                    (im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
                                                        or im.tool_id = 19)
                                                    group by im.tool_id';
                                $queryPending = $mysql->query($sqlPending);
                                if ($mysql->rowCount($queryPending) > 0) {
                                    $rows = $mysql->fetchArray($queryPending);
                                    foreach ($rows as $row) {
                                        echo '<a href="order_imei.html?type=locked&search_tool_id=' . $row['id'] . '"  class="dropdown-item"><span class="badge bg-danger pull-right">' . $row['total'] . '</span>' . $row['tool_name'] . '</a>';
                                    }
                                }
                            echo '</div>';
                        ?>
                    </div>
                    <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html?type=avail<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'avail') ? 'btn-primary btn-sm' : 'btn-default btn-sm'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_available')); ?> </a>
                    <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html?type=rejected<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'rejected') ? 'btn-primary btn-sm' : 'btn-default btn-sm'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rejected')); ?> </a>
                    <?php
                    if ($rowCount['verificationIMEI'] > 0) {
                        echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html?type=verify' . (($supplier_id != '') ? ('&supplier_id=' . $supplier_id) : '') . '" class="btn ' . (($type == 'verify') ? 'btn-primary btn-sm' : 'btn-default btn-sm') . '">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_verification')) . ' ' . $verifyCount . '</a>';
                    }
                    ?>
                    <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html<?php echo ($pString != '') ? ('?' . $pString) : '' ?>" class="btn <?php echo ($type == '') ? 'btn-primary btn-sm' : 'btn-default btn-sm'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_orders')); ?> </a>
                    <a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-sm"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?> </a>
                    <?php
                    if (trim($imei) != '' || $search_tool_id != 0) {
                        echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html' . (($type != '' ) ? ('?type=' . $type) : '') . '" class="btn btn-danger btn-sm"><i class="fa fa-undo"></i></a>';
                    }
                    ?>
                </div>
            </div>
            <div class="panel m-t-10">
                <!-- Panel Heading -->
                <div class="panel-heading p-10 bg-success-100 color-white">
                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_jobs')); ?>
                </div>
                <!-- Panel Heading -->
                <table class="table table-striped table-hover">
                    <tr>
                        <th width="16">
                        	<input type="checkbox" class="autoCheckAll"  id="kaka" data-class="checkAllIMEIS" />
                        </th>
                        <th width="16"></th>
                        <th width="16"></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tool')); ?></th>
                        <?php echo $admin->wordTrans($admin->getUserLang(),($type == 'verify')) ? '<th width="60"><label>Veify<input type="checkbox" value="" id="Verify" class="selectAllBoxes" /></label></th>' : ''; ?>
                        <?php
                        if ($type != 'pending') {
                            echo '<th>
								<a href="#" class="toggle" id="code">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_unlock_code')) . ' <i class="fa fa-info-sign"></i></a><br />
								<input type="text" name="" id="codeBox" class="form-control hidden autoFillText" />
								</th>';
                        }
                        if ($type == 'locked' or $type == 'verify') {
                            echo '
								<th width="100">
									<a href="#" class="toggle" id="unText">
										<input type="checkbox" class="autoCheckAll" data-class="toggleOnCheck" />
										Unavail <i class="fa fa-info-sign"></i>
									</a>
									<input type="text" name="" value="" id="unTextBox" class="form-control hidden autoFillText">
								</th>';
                        }
                        ?>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date_time')); ?> </th>
                        <th colspan="15"></th>
                    </tr>
                    <?php
                    $qType = '';

                    switch ($type) {
                        case 'pending':
                            $qType = ' im.status=0 ';
                            break;
                        case 'locked':
                            $qType = ' im.status=1 ';
                            $qType .= ' and im.supplier_id=' . $supplier->getUserId();
                            break;
                        case 'avail':
                            $qType = ' im.status=2 ';
                            $qType .= ' and im.supplier_id=' . $supplier->getUserId();
                            break;
                        case 'rejected':
                            $qType = ' im.status=3 ';
                            $qType .= ' and im.supplier_id=' . $supplier->getUserId();
                            break;
                        case 'verify':
                            $qType = ' im.status=2 and im.verify=1 ';
                            $qType .= ' and im.supplier_id=' . $supplier->getUserId();
                            break;
                        default:
                            $qType = (($qType != '') ? ' and ' : '') . ' im.supplier_id=' . $supplier->getUserId();
                            break;
                    }

                    if ($supplier_id != 0) {
                        $qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $supplier_id;
                    }
                    if (trim($txtImeis) != '') {
                        $qType .= (($qType != '') ? ' and ' : '') . ' im.imei in (' . $txtImeis . ') ';
                    }
                    if ($search_tool_id != 0) {
                        $qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id;
                    }
                    if ($ip != '') {
                        $qType .= (($qType != '') ? ' and ' : '') . ' im.ip = ' . $mysql->quote($ip);
                    }
                    if ($user_id != 0) {
                      //  $qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id);
                    }

                    $qType = ($qType == '') ? '' : ' and ' . $qType;

                    $strUserFields = $strUserTbl = '';

                    $sql = 'select im.*, im.id as imeiID,
									im.api_name, im.message,
									DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
									DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
									um.username as username,
									um.email as email,
									tm.tool_name as tool_name, 
									tm.tool_alias as tool_alias, 
									sm.username as supplier,isd.credits_purchase,
									DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier,isd.credits_purchase
									from ' . ORDER_IMEI_MASTER . ' im
									left join ' . USER_MASTER . ' um on(im.user_id = um.id)
									left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
									left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
                                                                            inner join ' . IMEI_SUPPLIER_DETAILS . ' isd on isd.tool=im.tool_id
									where (im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
										or im.tool_id = 19)
									' . $qType . '
									order by im.id DESC';
                    //          echo $sql . (($no_paging == '0') ? $qLimit : '');exit;
                 //class="dropdown-menu"   echo $sql;exit;
                    $query = $mysql->query($sql . (($no_paging == '0') ? $qLimit : ''));
                    $strReturn = "";

                    $pCode = '';
                    if ($no_paging == '0') {
                        $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html', $offset, $limit, $extraURL);
                    }

                    $i = $offset;
                    $totalRows = $mysql->rowCount($query);

                    if ($totalRows > 0) {
                        $subtotal = $grandTotal = 0;

                        $rows = $mysql->fetchArray($query);
                        foreach ($rows as $row) {
                            $i++;
                            echo '<tr>';

                            //echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
                            //if($type != 'locked')
                            {
                                echo '<td>';
                                echo '<input type="hidden" name="Ids[]" value="' . $row['id'] . '">';
                                echo '<input type="checkbox" class="subSelectLock checkAllIMEIS" name="locked_' . $row['id'] . '" value="' . $row['id'] . '">';
                                echo '</td>';

                                echo '<td>';
                                echo ($row['api_id'] != 0) ? '<span class="label label-danger"><i class="fa fa-link"></i></span>' : '';
                                echo '</td>';
                            }
                            echo '<td class="text_center">
										' . $i . '<br />
										<small>im-' . $row['id'] . '</small><br />';
                            switch ($row['status']) {
                                case 0:
                                    echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending')) . '</span>';
                                    break;
                                case 1:
                                    echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_locked')) . '</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_available')) . '</span>';
                                    echo '<span class="label label-info">' . $row['credits_purchase'] . '</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_unavailable')) . '</span>';
                                    break;
                            }
                            //echo (($row['extern_id'] != '0') ? '<br /><small style="color:#DD0000">' . $row['extern_id'] . ': ' . $row['api_name'] . '</small>' : '');
                            echo '</td>';
                            echo '<td>';
                            echo '' . $row['imei'] . '';
                            echo ($showUser == 1) ? '<span class="text-warning">' . $row['username'] . '</span>' : '';
                            echo '</td>';

                            echo '<td>' . $row['tool_name'];
                            //echo ($showCredits == 1) ? '<br /><span class="label label-info">' . $row['credits'] . '</span>' : '';
                            echo '</td>';
                            if ($type == "verify") {
                                echo '<td>' . (($row['status'] == "2" && $row['verify'] == "1") ? '<input type="checkbox" class="subSelectVerify " name="verify_' . $row['id'] . '">' : '') . '</td>';
                            }
                            if ($type != "pending") {
                                echo '<td>
											';
                                if (defined("DEMO")) {
                                    echo '*****Demo*****';
                                } else {
                                    if (($type == 'locked' and $row['status'] == '1') or $type == 'avail' or $type == 'verify') {
                                        //echo ($type != "avail") ? '<div class="divCode" id="code_' . $row['id'] . '">' . nl2br(base64_decode($row['reply'])) . '</div>' : '';
                                        $UnlockCode = '';
                                        if (isset($imeiCodes[$row['imei']])) {
                                            $UnlockCode = $imeiCodes[$row['imei']];
                                        } else {
                                            $UnlockCode = base64_decode($row['reply']);
                                        }
                                        echo '<input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control codeBoxFill txtCode" style="display:inline" value="' . $UnlockCode . '" />';
                                        echo '<input name="unlock_code_' . $row['id'] . '_2" class="form-contol" style="width:100px;display:none" value="' . $UnlockCode . '" />';
                                    } else {
                                        echo ($row['reply'] != '') ? nl2br(base64_decode($row['reply'])) : nl2br(base64_decode($row['message']));
                                    }
                                }
                                echo '<br />';

                                echo '&nbsp;</td>';
                            }
                            if ($type == 'locked' or $type == "verify") {
                                echo '<td>';
                                echo (($row['status'] == '1' or $type == 'verify') ? '<input type="checkbox" name="unavailable_' . $row['id'] . '" id="check' . $row['id'] . '" class="subSelectUnavail toggleOnCheck"> <input type="text" name="un_remarks_' . $row['id'] . '" id="check' . $row['id'] . 'Hide" value="" class="form-control unTextBoxFill hidden">' : '');
                                echo '</td>';
                            }
                            echo '<td>';
                            echo ($row['dtDateTime'] != '') ? ('<small>' . $row['dtDateTime'] . '</small>') : '';
                            echo ($row['dtReplyDateTime'] != '') ? ('<br /><small><b>' . $row['dtReplyDateTime'] . '</b></small>') : '';
                            if (($type == 'avail' || $type == '') && $row['status'] == 2) {
                                $grandTotal += $row['credits_purchase'];
                                //echo '<br /><span class="label label-danger">' . $row['credits_purchase'] . '</span>';
                            }
                            echo '</td><th colspan="15"></th>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="20" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '</td><td></td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="20">
                            <?php
                            if ($type == 'pending') {
                                //echo '<i class="fa fa-level-up fa-flip-horizontal"></i> ';
                                //echo '<a href="#" value="Lock" class="selectAllBoxesLink">' . $lang->get('lbl_check_all') . '</a> / ';
                                //echo '<a href="#" value="Lock" class="unselectAllBoxesLink">' . $lang->get('lbl_uncheck_all') . '</a> ';
                            }
                            if ($totalRows > 0 and ( $type == 'pending' || $type == 'locked' || $type == 'avail' || $type == 'verify')) {
                                //echo '<input type="submit" name="process" value="' . $lang->get('lbl_process_imeis') . '" class="btn btn-success" />';
                                if ($type == 'pending') {
                                    //echo ' <input type="submit" name="process_all" value="' . $lang->get('lbl_process_imeis_all') . '" class="btn btn-danger" />';
                                }
                            }
                            //if($type != 'locked')
                            {
                                echo ' <input type="submit" name="download" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_download')) . '" class="btn btn-default btn-sm" />';
                                //	echo '<label class="checkbox-inline">' . $lang->get('lbl_tool') . '<input type="checkbox" value="1" id="copyTool" name="copyTool" class="copyTool CopyAllIMEIs" /></label>';
                                //	echo '<label class="checkbox-inline">' . $lang->get('lbl_alias') . '<input type="checkbox" value="1" id="copyAlias" name="copyAlias" class="copyAlias CopyAllIMEIs" /></label>';
                                //	echo '<label class="checkbox-inline">' . $lang->get('lbl_username') . '<input type="checkbox" value="1" id="copyUsername" name="copyUsername" class="copyUsername CopyAllIMEIs" /></label>';
                                //	echo '<label class="checkbox-inline">.csv<input type="checkbox" value="1" id="file_ext" name="file_ext" /></label>';
                            }
                            ?>
                        </td>
                        <td><?php
                            if ($type == 'avail' || $type == '') {
                                echo $admin->wordTrans($admin->getUserLang(),'Total Profit').':  <b>' . $grandTotal . '</b>';
                            }
                            ?></td>
                    </tr>
                </table>
                <div class="TA_C navigation"><?php
                    if ($imei == '') {
                        echo $pCode;
                    }
                    ?></div>
            </div>
            <?php
            if ($type == 'pending' || $type == 'locked' || $type == 'verify') { // || $type == 'avail' || $type == 'verify')
                echo '
							<form action="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei_' . (($type != '') ? $type : 'all') . '_process_ajax.do" enctype="multipart/form-data" method="post" name="frmAjaxOrderSub" id="frmAjaxOrderExtra">
								<div id="tempFields"></div>
								<input type="submit" name="process" class="btn btn-success btn-sm" value="' . $admin->wordTrans($admin->getUserLang(),'Process Imeis'). '" />
							</form>';
            }
            ?>
        </div>
    </div>

    <div id="last_msg_loader"></div>


    <?php ob_flush(); ?>

</form>
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_nxt_admin.js" ></script>
<script>
    $(document).ready(function ()
    {
        imeiOrders();

        $('#kaka').click(function (event) {
            // alert('aaaaaaaaaa');
            //on click 
            if (this.checked) { // check select status


                $('.subSelectLock').each(function () { //loop through each checkbox

                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.subSelectLock').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });
    });
</script>