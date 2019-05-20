	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
	.select2-container--default .select2-selection--single {
		border:1px solid #ececec;
}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
		background: #f2f2f2;
}
	#demoGsm1 {
		box-shadow: 5px 6px 7px;
		border-top: 2px solid #ccc;
		margin: 4px 0 20px;
		padding: 0 7px;
}
</style>

<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$limit = $request->getInt('limit');
$supplier_id = $request->GetInt('supplier_id');
$order_id = $request->PostInt('order_id');
$search_status = $request->PostInt('search_status');
$no_paging = $request->GetInt('no_paging');
$order_reply="";
$show=0;

if (isset($_POST['type']))
	{
    
    $type = $request->PostStr('type');
    $show=1;
	}
else
    $type = $request->GetStr('type');

if (isset($_POST['imei']))
    $imei = $request->PostStr('imei');
else
    $imei = $request->GetStr('imei');

if (isset($_POST['imei_code'])) {
    $imei_code = $request->PostStr('imei_code');
    //$no_paging = 2;
} else {
    $imei_code = $request->GetStr('imei_code');
}

if (isset($_POST['search_tool_id']))
    $search_tool_id = $request->PostInt('search_tool_id');
else
    $search_tool_id = $request->GetInt('search_tool_id');

if (isset($_POST['search_user_id']))
    $search_user_id = $request->PostInt('search_user_id');
else
    $search_user_id = $request->GetInt('search_user_id');

if (isset($_POST['search_supplier_id']))
    $search_supplier_id = $request->PostInt('search_supplier_id');
else
    $search_supplier_id = $request->GetInt('search_supplier_id');

$ip = $request->GetStr('ip');
$user_id = $request->GetInt('user_id');

$hide_user = $request->GetInt('hide_user');
if (($request->GetStr('order_id')) && $request->GetStr('order_id') != '') {
    $order_id = $request->GetStr('order_id');
}
if (($request->GetStr('search_status')) && $request->GetStr('search_status') != '') {
    $search_status = $request->GetStr('search_status');
}
//split IMEI in new line
if ($imei_code != '') {
    $imei = '';
}
//split IMEI in new line
//$imeis = explode("&#13;&#10;", $imei);
$imeis = explode(PHP_EOL, $imei);
//print_r($imeis);
//$txtImeis = "";

foreach ($imeis as $im) {
    $im=  trim($im);
     if(is_numeric($im))
      {
         $txtImeis .= $im . ',';
      }
      else
      {
          $txtImeis .= '"'.$im . '",';
      }
}
$txtImeis = rtrim($txtImeis, ',');

$delimiter = $request->PostStr('delimiter');

if ($imei_code != '') {
    if ($delimiter == '') {
        $delimiter = ' ';
    }
    //split IMEI in new line
    //$imeis = explode("&#13;&#10;", $imei_code);
    $imeis = explode(PHP_EOL, $imei_code);
    $txtImeis = "";
    foreach ($imeis as $im) {
        $tempIMEI = strstr($im, $delimiter, true);
        if (is_numeric($tempIMEI)) {
            $txtImeis .= $tempIMEI . ',';
            $imeiCodes[$tempIMEI] = trim(strstr($im, $delimiter), $delimiter);
        }
    }
    $txtImeis = rtrim($txtImeis, ',');
}
//echo $txtImeis;
//echo strlen($txtImeis);
$sqlCount = 'select status, count(id) as total from ' . ORDER_IMEI_MASTER . ' where status in (0, 1) and api_auth=0 and api_rej_2=0 group by status';
$queryCount = $mysql->query($sqlCount);
if ($mysql->rowCount($queryCount) > 0) {
    $rows = $mysql->fetchArray($queryCount);
    foreach ($rows as $row) {
        $imeiCount[$row['status']] = $row['total'];
    }
}
$sqlCount = 'select
				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where (im.status=2 and im.verify=1)) as verificationIMEI
				from ' . ADMIN_MASTER . ' am where id=' . $mysql->getInt($admin->getUserId());

$queryCount = $mysql->query($sqlCount);
$rowCount = 0;
if ($mysql->rowCount($queryCount) > 0) {
    $rowsCount = $mysql->fetchArray($queryCount);
    $rowCount = $rowsCount[0];
}

$verifyCount = ($rowCount['verificationIMEI'] > 0) ? (' <span class="label label-rounded label-danger">' . $rowCount['verificationIMEI'] . '</span>') : '';


// api auth count

$sqlCountapiauth = 'select count(*) as apiauth from '.ORDER_IMEI_MASTER.' im
where im.api_auth=1 and im.status=0 and im.api_auth_yn=0';

$queryCountapiauth = $mysql->query($sqlCountapiauth);
$rowCountapiauth = 0;
if ($mysql->rowCount($queryCountapiauth) > 0) {
    $rowCountapiauth = $mysql->fetchArray($queryCountapiauth);
    $rowCountapiauth = $rowCountapiauth[0];
}
//var_dump($rowCountapiauth);
$verifyCountapiauth = ($rowCountapiauth['apiauth'] > 0) ? (' <span class="label label-rounded label-danger">' . $rowCountapiauth['apiauth'] . '</span>') : '';


// api rej count

$sqlCountapiauth = 'select count(*) as apirej from '.ORDER_IMEI_MASTER.' im
where im.api_rej_2=1 and im.status=1';

$queryCountapiauth = $mysql->query($sqlCountapiauth);
$rowCountapiauth = 0;
if ($mysql->rowCount($queryCountapiauth) > 0) {
    $rowCountapirej = $mysql->fetchArray($queryCountapiauth);
    $rowCountapirej = $rowCountapirej[0];
}
//var_dump($rowCountapiauth);
$verifyCountapirej = ($rowCountapirej['apirej'] > 0) ? (' <span class="label label-rounded label-danger">' . $rowCountapirej['apirej'] . '</span>') : '';


$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
if ($limit == '') {
    $limit = 100;
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
if (isset($_POST['req_type']) && $_POST['req_type'] != '' && $_POST['req_type'] == 'P') {
    if (isset($_POST['dlist_page_size']) && $_POST['dlist_page_size'] != 0) {
        $limit = ($_POST['dlist_page_size'] > 0 ? $_POST['dlist_page_size'] : $limit);
    } else {
        $limit = ($_POST['page_size'] > 0 ? $_POST['page_size'] : $limit);
    }
} else if (isset($_GET['limit']) && $_GET['limit'] != '') {
    $limit = ($_GET['limit'] > 0 ? $_GET['limit'] : $limit);
} else {
    $limit = ((defined('CONFIG_ORDER_PAGE_SIZE') && CONFIG_ORDER_PAGE_SIZE > 0) ? CONFIG_ORDER_PAGE_SIZE : 40);
}
//var_dump($_POST);
//echo $no_paging;
//echo $_POST['req_type'];
//echo $limit;
$qLimit = " limit $offset,$limit";
$extraURL = '&type=' . $type . '&limit='.$limit.'&supplier_id=' . $supplier_id .  '&order_id=' . $order_id . '&search_tool_id=' . $search_tool_id . '&search_user_id=' . $search_user_id . '&search_supplier_id=' . $search_supplier_id . '&user_id=' . $user_id . '&ip=' . $ip . '&search_status=' . $search_status;
?>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init_nxt_admin.js" ></script>

<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_orders')); ?></li>
        </ol>
    </div>
</div>

<div class="row" style="display:none" id="loadingPanel">
    <div class="col-md-8 col-md-offset-2">
        <!-- Progress Bar -->
        <h1 class="text-center" id="h1Wait"><i class="fa fa-refresh fa-large fa-spin"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Please_wait...')); ?></h1>
        <!--<div class="progress progress-striped active" id="pBarSubmit">	
             <div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
             <progress class="progress progress-primary sr-only">0% Complete</progress>
                 
             </div>
         </div>-->

        <progress class="progress progress-primary" id="pBarSubmit" value="0" max="100">0% Complete</progress>
        <!-- / Progress Bar -->	

        <!-- Message after successfull submission -->
        <h1 class="text-center" style="display:none" id="h1Done"><i class="fa fa-ok fa-large text-success"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Done')); ?></h1>
        <div class="text-center" style="display:none" id="panelButtons">
            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_pending_final.html" method="post" name="frmDownload" id="frmDownload">
                <div id="tempDownloadIMEIS"></div>
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=<?php echo $type ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Go_Back')); ?></a>
                <button type="submit" name="process" class="btn btn-primary"><i class="fa fa-download"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Preview/Download')); ?></button>
            </form>
        </div>
        <!-- / Message after successfull submission -->

        <!-- Error Message -->
        <div class="alert alert-danger " style="display:none" id="h1Error"><i class="fa fa-remove fa-large"></i> <span id="h1ErrorText"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_There_is_some_unexpected_error!')); ?></span></div>
        <div class="text-center " style="display:none" id="panelButtonsCredits">
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=<?php echo $type ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_There_is_some_error!_Go_Back')); ?></a>
        </div>
        <!-- / Error Message -->
    </div>
</div>

<div class="clear"></div>

<div class="row" id="btn-group-top">
    <div class="col-lg-12">
        <div class="btn-group btn-group-sm extra">
            <div class="dropdown pull-left btn-group-sm"> 
<?php
echo '
					<a class="btn ' . (($type == 'pending') ? 'btn-primary dropdown-toggle' : 'btn-default dropdown-toggle') . ' tab-current" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fa fa-asterisk fa-large"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Orders')) . ' ' . (isset($imeiCount[0]) ? ' <span class="label label-rounded label-danger">' . $imeiCount[0] : '') . '</span>
						<span class="caret"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-scale from-left btn-group-sm">';
echo '<a href="order_imei.html?type=pending" class="dropdown-item tab-current">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_All_New_Orders')) . '</a>';

$sqlPending = 'select tm.id as id, count(im.id) as total,
									tm.tool_name as tool_name
									from ' . ORDER_IMEI_MASTER . ' im
									left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
									where im.status=0  and im.api_auth=0
									group by im.tool_id';
$queryPending = $mysql->query($sqlPending);
if ($mysql->rowCount($queryPending) > 0) {
    $rows = $mysql->fetchArray($queryPending);
    foreach ($rows as $row) {
        echo '<a href="order_imei.html?type=pending&search_tool_id=' . $row['id'] . '" class="dropdown-item tab-current"><span class="m-r-10 label label-rounded label-danger">' . $row['total'] . '</span>' . $row['tool_name'] . '</a>';
    }
}
echo '</div>';
?>
            </div>
            <div class="dropdown pull-left btn-group-sm">
<?php
echo '
					<a class="btn ' . (($type == 'locked') ? 'btn-primary dropdown-toggle' : 'btn-default dropdown-toggle') . ' tab-current" data-toggle="dropdown">
						' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_In-Process')) . ' ' . (isset($imeiCount[1]) ? ' <span class="label label-rounded label-danger">' . $imeiCount[1] : '') . '</span>
						<span class="caret"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-scale from-left">';
echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=locked" class="dropdown-item tab-current">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_In_Process')) . '</a></li>';
echo '<span role="presentation" class="divider" class="dropdown-item"></span>';
$sqlPending = 'select tm.id as id, count(im.id) as total,
									tm.tool_name as tool_name
									from ' . ORDER_IMEI_MASTER . ' im
									left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
									where im.status=1 and im.api_rej_2=0
									group by im.tool_id';
$queryPending = $mysql->query($sqlPending);
if ($mysql->rowCount($queryPending) > 0) {
    $rows = $mysql->fetchArray($queryPending);
    foreach ($rows as $row) {
        echo '<a href="order_imei.html?type=locked&search_tool_id=' . $row['id'] . '" class="dropdown-item tab-current"><span class="m-r-10 label label-rounded label-danger tab-current">' . $row['total'] . '</span>' . $row['tool_name'] . '</a>';
    }
}
echo '</div>';
?>
            </div>

            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=avail<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'avail') ? 'btn-primary' : 'btn-default'; ?> tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Completed')); ?> </a>

            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=rejected<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'rejected') ? 'btn-primary' : 'btn-default'; ?> tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?> </a>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=api_auth<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'api_auth') ? 'btn-primary' : 'btn-default'; ?> tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Pending_API_Authorization')).''.$verifyCountapiauth; ?> </a>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=api_rej<?php echo (($pString != '') ? ('&' . $pString ) : ''); ?>" class="btn <?php echo ($type == 'api_rej') ? 'btn-primary' : 'btn-default'; ?> tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Review_For_Rejected')).''.$verifyCountapirej; ?> </a>

<?php
if ($rowCount['verificationIMEI'] > 0) {
    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=verify' . (($supplier_id != '') ? ('&supplier_id=' . $supplier_id) : '') . '" class="btn ' . (($type == 'verify') ? 'btn-primary' : 'btn-default') . ' tab-current">' . $admin->wordTrans($admin->getUserLang(),'Verification') . ' ' . $verifyCount . '</a>';
}
?>

            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html<?php echo ($pString != '') ? ('?' . $pString) : '' ?>" class="btn <?php echo ($type == '') ? 'btn-primary' : 'btn-default'; ?> tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_orders')); ?> </a>

            <a href="#" id="downloadFile" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>download.do" class="blueButton btn btn-danger" style="display:none"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Download')); ?> </a>
            
			<a href="#" onclick="extract();" class="btn btn-primary tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Extract')); ?> </a>
			
			

        </div>
		
		<br><br>
		
		
		<a class="btn btn-info" id="btn-search" data-toggle="collapse" data-target="#demoGsm"> <!-- <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> --> Filter Orders</a>            			
			  <div id="demoGsm1" class="collapse out" style="">
							  <br>
							  <h4><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
						  							  
			                  <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html" method="post">
                    <fieldset>
                        <div class="form-group">
									  <input type="hidden" value="1" id="sh" />
                            <select name="search_tool_id" style="width:100%;overflow: auto"  id="toolsearch">
                                <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_tools')); ?> </option>
<?php
$sql_tool = 'select itm.id as tool_id, itm.tool_name, itm.group_id, itm.delivery_time, itm.status,igm.group_name from ' . IMEI_TOOL_MASTER . ' itm	left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)	order by igm.sort_order, itm.sort_order, itm.tool_name';
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
                                <select name="search_user_id" style="width:100%;overflow: auto"  id="usersearch">
                                    <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_users')); ?> </option>
<?php
$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';
$query_usr = $mysql->query($sql_usr);
$rows_usr = $mysql->fetchArray($query_usr);
foreach ($rows_usr as $row_usr) {
    echo '<option ' . (($row_usr["id"] == $search_user_id || $row_usr["id"] == $user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
}
?>
                                </select>
								
								 <br><br>
								<select name="search_supplier_id" style="width:100%;overflow: auto"  id="suppsearch">
                                    <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_suppliers')); ?> </option>
<?php
$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
$query_usr = $mysql->query($sql_usr);
$rows_usr = $mysql->fetchArray($query_usr);
foreach ($rows_usr as $row_usr) {
    echo '<option ' . (($row_usr["id"] == $search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
}
?>
                                </select>
								
								<br><br>
								
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), 'Order ID'); ?></label>
                        <input name="order_id" type="text" class="form-control" id="order_id" value="" />
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <?php if($type==""){?>
                         <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_status')); ?> </label>
                        <select name="search_status" class="form-control chosenf-select">
                            <option value="-1"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all')); ?> </option>
                            <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_inprocess')); ?> </option>
                            <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_completed')); ?> </option>
                            <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_rejected')); ?> </option>
                            </select><?php }?>
								
								
                            </div>

                            <div class="form-group col-md-6">
							
							
							
							
							
							
							
							                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </label>
                                    <textarea name="imei" class="form-control" id="imei" rows="5"><?php echo $imei; ?></textarea>
                                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                                    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
                                    <input type="hidden" name="ip" value="<?php echo $ip; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                                </div>
                                <!--                                <div class="col-md-6">
                                                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_with_code')); ?> </label>
                                                                    <textarea name="imei_code" class="form-control" id="imei_code" rows="6"><?php echo $imei_code; ?></textarea>
                                                                    <br />
                                                                    <div class="form-group">
                                                                        <select name="delimiter" class="form-control">
                                                                            <option value="">Space</option>
                                                                            <option value=":">Colon[:]</option>
                                                                            <option value=";">Semicolon[;]</option>
                                                                            <option value=",">Coma [,]</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                            </div>
                        </div>
							
							
							


							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />	
							
<?php
	if (trim($imei) != '' || $search_tool_id != 0 || $search_user_id != 0 || $search_supplier_id != 0) {
		echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html' . (($type != '' ) ? ('?type=' . $type) : '') . '" class="btn btn-danger tab-current"><i class="fa fa-undo"></i></a>';
	}
?>
							
                                
                            </div>
                        </div>



                        

                        
						
						

						
						
						
						
                    </fieldset>
                </form>
			  </div>

		
		
		
		
		
    </div>  

    <div class="text-right" style="margin:12px 10px 3px 0px;">
<?php
if ($type == 'pending' || $type == 'locked') { // || $type == 'avail' || $type == 'verify')
    echo '<form action="" enctype="multipart/form-data" method="post" name="frmAjaxOrderSub" id="frmAjaxOrderExtra">
				<div id="tempFields"></div>
				<input type="submit" name="process" id="process"  class="btn btn-success"  value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" />
			</form>';
}
?>
    </div>
</div>
<div class="clear"></div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frmAjaxOrder" id="frmAjaxOrder">
    <input type="hidden" name="imei" value="<?php echo $imei; ?>">
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <input type="hidden" name="reqeustType" id="reqeustType"  value="<?php echo $type; ?>">
    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
    <input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id; ?>">
    <input type="hidden" name="ip" value="<?php echo $ip; ?>">
    <input type="hidden" name="search_user_id" value="<?php echo $search_user_id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="limit" value="<?php echo $limit; ?>">
      <?php
        
         if ($type == 'avail') {
          //   echo ' <div class="input-group"> <div class="col-xs-4" id="brejct"><label>Bulk Reject Reason</label>';
        echo '  <br><div class="input-group col-xs-6" id="brejct"><input placeholder="Bulk Reject Reason" type="text" name="b_reject_reason" value="" class="form-control" />';

        echo '  <span class="input-group-btn"><input type="submit" name="reject_all" value="' . $lang->get('lbl_bulk_reject') . '" class="btn btn-danger" /></span></div>';
    }
    ?>
<?php
ob_flush();
ob_start();
?>
    <div class="clearfix"></div>
    <div class="row" id="order-panel">
        <h4 class="panel-heading m-b-20  m-t-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_jobs')); ?></h4>
	<div class="table-responsive">
        <table class="table table-bordered table-hover" style="table-layout:fixed;
width:100%;
word-wrap:break-word;">
            <tr>
<?php
if ($type != 'locked' && $type != 'verify' ) {
    ?>
                    <th width="35px"><label class="c-input c-checkbox"><input type="checkbox" class="autoCheckAll" data-class="autoCheckMe" /><span class="c-indicator c-indicator-success"></span></label></th>
                    <?php } echo ($type != '') ? '<th width="100px">#</th>' : '<th width="100px">#</th>';
                ?>
                <th width="150px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?></th>
<!--                <th width="16">API</th>-->
                <?php //echo ($type == '') ? '<th width="16"></th>' : ''; ?>
                <th width="150px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th">
                <?php echo ($type == 'verify') ? '<th width="50px"><label>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Verify')) . ' </label><input type="checkbox" class="autoCheckAll" data-class="autoCheckMe" /></th>' : ''; ?>
                <?php
                echo '<th width="150px">
							<a href="#" class="" id="code"  onclick="showtb();">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unlock_code')) . ' <i class="fa fa-folder-open"></i></a><br />
							<input type="text" name="" id="codeBox" class="form-control autoFillText" style="display:none" />
						</th>';

                if ($type == 'locked' or $type == 'pending' or $type == 'verify') {
                    echo '
					<th width="120px">
						<a href="#" class="toggle" id="unText">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Reject')) . '</a>
						<input type="text" name="" value="" id="unTextBox" class="form-control autoFillText" style="display:none">
						<br />
						<span class="text_11 text_black">
                                                <input type="checkbox" class="autoCheckAllrej" data-class="autoCheckMe" />
							
						</span>
					</th>';
                }
                ?>
                <th width="150px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date_time')); ?> </th>
                <th width="80px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_price')); ?> </th>
                <?php //echo ($type == "avail") ? '<th style="width=150px;">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Reject')) . '</th>' : ''; ?>
<?php //echo ($supplier_id != 0 and $type == 'avail') ? '<th style="text-align:center;width=150px;"><label class="c-input c-checkbox"><input type="checkbox" value="" id="Pay" class="selectAllBoxes" />' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pay')) . '<span class="c-indicator c-indicator-success"></span></label></th>' : ''; ?>
                <th width="100px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Action')); ?> </th>

            </tr>

<?php
$qType = '';

switch ($type) {
    case 'pending':
        $qType = ' im.api_auth=0 and im.status=0 ';
        break;
     case 'api_auth':
        $qType = ' im.api_auth=1 and im.status=0 ';
        break;
    case 'api_rej':
        $qType = ' im.api_rej_2=1 and im.status=1 ';
        break;
    case 'locked':
        $qType = ' im.status=1 and im.api_rej_2=0';
        break;
    case 'avail':
        $qType = ' im.status=2 ';
        break;
    case 'rejected':
        $qType = ' im.status=3 ';
        break;
    case 'verify':
        $qType = ' im.status=2 and im.verify=1 ';
        break;
}

if ($supplier_id != 0) {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $supplier_id;
}
if (strlen($txtImeis) !=2) {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.imei in (' . $txtImeis . ') ';
}
if ($search_tool_id != 0) {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id;
}
if ($search_user_id != 0) {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.user_id = ' . $search_user_id;
}
if ($search_supplier_id != 0) {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $search_supplier_id;
}
if ($ip != '') {
    $qType .= (($qType != '') ? ' and ' : '') . ' im.ip = ' . $mysql->quote($ip);
}
if ($user_id != 0) {
    $qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id);
}
  if ($order_id != '') {
                $qType .= (($qType != '') ? ' and ' : '') . ' im.id = ' . $order_id . ' ';
            }
             if ($search_status != '' && $search_status != 1) {
                if ($search_status == -1) {
                    $qType .= (($qType != '') ? ' and ' : '') . ' im.status in (0,1,2,3) ';
                } else {
                    $qType .= (($qType != '') ? ' and ' : '') . ' im.status = ' . $search_status . ' ';
                }
            }
            if ($search_status != '' && $search_status == 1) {
                $qType .= (($qType != '') ? ' and ' : '') . ' im.status in(0,1) ';
            }
//if($imei != ''){
//$qType .= (($qType != '') ? ' and ' : '') . ' im.imei = ' . $imei;
//}
$qType = ($qType == '') ? '' : ' where ' . $qType;

$strUserFields = $strUserTbl = '';
if ($hide_user == 1) {
    //echo "hideUsers";
}
$sql = 'select im.*, im.id as imeiID,
					tm.api_id,
					DATE_FORMAT(im.date_time, "%d, %b %y %h:%i %p") as dtDateTime,
					DATE_FORMAT(im.date_time, "%h:%i %p") as timeSubmit,
					DATE_FORMAT(im.reply_date_time, "%d, %b %y %h:%i %p") as dtReplyDateTime,
					TIMESTAMPDIFF(SECOND, im.date_time, now()) as timediff,
					um.username as username,
					um.email as email,
					tm.tool_name as tool_name, 
					tm.tool_alias as tool_alias, 
					sm.username as supplier,
					DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier,
					cm.prefix, cm.suffix
					from ' . ORDER_IMEI_MASTER . ' im
					left join ' . USER_MASTER . ' um on(im.user_id = um.id)
					left join ' . CURRENCY_MASTER . ' cm on(cm.id = um.currency_id)
					left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
					left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
					' . $qType . '
					order by im.id DESC';
//echo $sql;

$query = $mysql->query($sql . (($no_paging == '0') ? $qLimit : ''));


$strReturn = "";
$pCode = '';
if ($no_paging == '0') {
    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'order_imei.html', $offset, $limit, $extraURL);
}
$i = $offset;
$totalRows = $mysql->rowCount($query);

if ($totalRows > 0) {
    $rows = $mysql->fetchArray($query);
    $old_tool_group_name = '';
    $a = 1;
    $f=0;
    $extract = array();
    foreach ($rows as $row) {
        $f++;
        echo '<tr>';
        echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
        if ($row['status'] == 0 && $row['api_auth'] == 1 && $row['api_auth_yn']==1) {
            echo '<td>';
//            //echo '<input type="checkbox" class="subSelectLock" name="locked[]" value="' . $row['id'] . '">';
            echo '</td>';
        } else if ($type != 'locked' && $type != 'verify') {
            echo '<td>';
            echo '<div id="apiauth1' . $row['id'] . '"> <label class="c-input c-checkbox"><input type="checkbox" class="autoCheckMe" data-imei="' . $row['imei'] . '" name="locked[]" value="' . $row['id'] . '"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"></div>';
            echo '</td>';
        }
        else {
            
        }
        //if($type == 'locked'){
        //echo '<td>';
        //echo '<input type="checkbox" class="autoCheckMe" data-imei="' . $row['imei'] . '" id="inpro_' . $row['id'] . '" name="inpro_' . $row['id'] . '">';echo '</td>';
        //}
        echo '<td class="small text_center" alt="im-' . $row['id'] . '">'.$f.'<br>Order#' . $row['id'] . '<br>'. (($row['api_id'] != 0) ? '<span class="badge bg-important"><i class="fa fa-link"></i></span>' : '').'</td>';
        echo '<td  class="small">';
        echo '' . $row['imei'] . '<br />';
      //   echo '<td>' . (($row['api_id'] != 0) ? '<span class="badge bg-important"><i class="fa fa-link"></i></span>' : '') . '</td>';
        switch ($row['status']) {
            case 0:
                echo '<span class="text-default"><i class="fa fa-spinner fa-pulse text-default"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')) . '</span>';
                break;
            case 1:
                echo '<span class="text-primary"><i class="fa fa-lock text-primary"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_In-Process')) . '</span>';
                break;
            case 2:
                echo '<span class="text-success"><i class="fa fa-circle text-success"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')) . '</span>';
                break;
            case 3:
                echo '<span class="text-danger"><i class="fa fa-circle text-danger"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span>';
                break;
        }
        echo (($row['extern_id'] != '0') ? '<br /><small class="text-danger">' . $row['extern_id'] . '</small>' : '');
        echo '</td>';
       // echo '<td>' . (($row['api_id'] != 0) ? '<span class="badge bg-important"><i class="fa fa-link"></i></span>' : '') . '</td>';
        echo '<td width=""><div class="">';
        echo '<small  style="">'.$row['tool_name'].'</small><br>';
        //. (($row['supplier'] != '') ? ('<br><small  style="color: red"><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '') . '<br />';
        echo ($row['username'] != '') ? ('<small><a style="font-size:small" href="users_edit.html?id=' . $row['user_id'] . '" class="label label-success">' .$row['username']. '</small></a>') : '';
        echo '</div></td>';
        if ($type == "verify") {
            echo '<td>' . (($row['status'] == "2" && $row['verify'] == "1") ? '<label class="c-input c-checkbox"><input type="checkbox" class="autoCheckMe" data-imei="' . $row['imei'] . '" name="verify_' . $row['id'] . '"><span class="c-indicator c-indicator-success"></span>' : '') . '</td>';
        }
        echo '<td><div class="small">';
        
        $order_reply=base64_decode($row['reply']);
        
         if(strstr($order_reply,"stylesheet") || strstr($order_reply,"script src") ||strstr($order_reply,"img src"))
	 $order_reply= 'Page Not found';
        
        if (defined("DEMO")) {
            echo '*****Demo*****';
        } else {
            if (($type != "pending" and $type == 'locked' and $row['status'] == '1') or $type == 'verify') {
                //echo ($type != "avail") ? '<div class="divCode" id="code_' . $row['id'] . '">' . nl2br(base64_decode($row['reply'])) . '</div>' : '';
                $UnlockCode = '';
                if (isset($imeiCodes[$row['imei']])) {
                    $UnlockCode = $imeiCodes[$row['imei']];
                } else {
                    $UnlockCode = $order_reply;
                }
            echo '<input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control codeBoxFill txtCode" style="display:inline" value="' . $UnlockCode . '" /><br>';
             echo ($row['v_remarks'] != '') ? '<div class="small"><div class="alert alert-success">'.$row["v_remarks"].'</div></div>' : '';
                echo '<input name="unlock_code_' . $row['id'] . '_2" class="form-contol" style="width:100px;display:none" value="' . $UnlockCode . '" />';
            } else {
                echo ($row['reply'] != '') ? nl2br($order_reply) : '';
            }
            echo ($row['message'] != '') ? '<div class="alert alert-danger">' . nl2br(base64_decode($row['message'])) . '</div>' : '';
            echo ($row['remarks'] != '' && $row['status']==0) ? '<div class="alert alert-info">' . $row['remarks'] . '</div>' : '';
        }

        if ($type == "pending") {
            echo ($row['admin_note'] != '') ? '<div class="alert alert-info">' . $row['admin_note'] . '</div>' : '';
        }

        echo '</div></td>';

        if ($type == 'locked' or $type == 'pending' or $type == "verify") {
            echo '<td>';
            echo (($row['status'] == '1' or $row['status'] == '0' or $type == 'verify') ? '<label class="c-input c-checkbox"><input type="checkbox" name="unavailable_' . $row['id'] . '" id="check' . $row['id'] . '" class="subSelectUnavail toggleOnCheck"><span class="c-indicator c-indicator-success"></span></label> <input type="text" name="un_remarks_' . $row['id'] . '" id="check' . $row['id'] . 'Hide" value="" class="form-control unTextBoxFill" style="display:none">' : '');
            echo '</td>';
        }
        echo '<td alt="' . $mysql->prints($row['ip']) . '">';
        echo '<small>';
        //if($row['timediff'] <= 20){
        //echo '<span class="badge bg-important">Just Now</span>';
        //}else if ($row['timediff'] > 20 && $row['timediff'] <= 60){
        //echo '<span class="badge bg-warning">Less than a min</span>';
        //} else if ($row['timediff'] > 60 && $row['timediff'] <= 60 * 60) {
        //echo (int) ($row['timediff'] / 60) . ' min';
        //} else if ($row['timediff'] > 60 * 60 && $row['timediff'] <= 60 * 60 * 24) {
        //echo $row['timeSubmit'];
        //} else {
        //echo ($row['dtDateTime'] != '') ? ($row['dtDateTime'] . '<br />') : '';
        //}
       
        

// time zone logic
          $finaldate = $admin->datecalculate($row['date_time']);
        //

        echo ($finaldate != '') ? ($finaldate) : '';
        echo '</small>';
        //    echo $row['reply_date_time'];
        //    echo $dftimezonewebsite.'--'.$dftimezoneadmin;
        //    exit
        if ($row['reply_date_time'] != '0000-00-00 00:00:00') {
           
             $finaldate2 = $admin->datecalculate($row['reply_date_time']);
            echo ($finaldate2 != '' && $finaldate2 != '29-Nov--0001 19:31') ? ('<br /><small><b>' . $finaldate2 . '</b></small>') : '';
        }
        echo ($row['supplier'] != '') ? ('<span class="label label-info">' . $mysql->prints($row['supplier']) . '</span>') : '';

        echo '</td>';

        echo '<td><div class="hspan"><span class="label label-pill label-primary label-lg">' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</span>';

        if ($row['credits_purchase'] != 0) {

            echo '<br /><small>' . $row['credits_purchase'] . '</small>';
        }

        echo '</div></td>';



        //echo ($type == "avail") ? '<td class="text_center"><label class="c-input c-checkbox"><input type="checkbox" name="return_' . $row['id'] . '" class="subSelectReturn"><span class="c-indicator c-indicator-success"></span></label><br /><input type="text" name="return_remarks_' . $row['id'] . '" value="" class="form-control"></td>' : '';

        if ($supplier_id != 0 and $type == 'avail') {

            echo '<td class="text_center">';

            if ($row['supplier_paid'] == 0) {

                echo '<input type="checkbox" class="subSelectPay" name="pay_' . $row['id'] . '" id="pay_' . $row['id'] . '">';
            } else {

                echo '<small><b>' . $row['dtSupplier'] . '</b></small>';
            }

            echo '</td>';
        }

        //  echo '<td class="text-center"><input type="checkbox" data-imei="' . $row['imei'] . '" class="autoCheckMe" /></td>';

        echo '<td width="">

<div class="btn-group btn-group-sm mybtn">' . (($row['status'] == 3) ? '<a class="prompt btn btn-primary btn-xs " title="Are you sure you want to put IMEI back to LOCKED status?" href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei_relock.do?id=' . urlencode($row['id']) . '" ><i class="fa fa-lock"></i></a>' : '') . '<a class="btn btn-default" data-fancybox-type="iframe" href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei_detail.html?id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" ><i class="fa fa-arrow-right"></i></a><a class="btn btn-danger" data-fancybox-type="iframe" href="' . CONFIG_PATH_SITE_ADMIN . 'order_q_edit.html?status='.urlencode($row['status']).'&id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" ><i class="fa fa-pencil-square-o"></i></a>' . (($row['status'] == 2) ? '<a class="btn btn-inverse btn-xs tab-current prompt" href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei_reinprocess.do?id=' . urlencode($row['id']) . '" title="Are you sure? You want to move the selected IMEI to InProcess." ><i class="fa fa-reply"></i></a>' : '') . '        

</div>';
        
        if($row['status'] == 2 && $row['verify'] == 1)
        {
            if($row['v_check']==0)
            {
            echo '<div class="btn-group btn-group-sm mybtnnn">';
            echo '<a class="btn btn-success btn-xs " title="Are you sure you want to Accept Order For Verification?" href="' . CONFIG_PATH_SITE_ADMIN . 'imei_order_accept_verify.do?id=' . urlencode($row['id']) . '&check=1" ><i class="fa fa-hand-o-up"></i></a>';
            echo '<a class="btn btn-danger btn-xs " title="Are you sure you want to Reject Order For Verification?" href="' . CONFIG_PATH_SITE_ADMIN . 'imei_order_accept_verify.do?id=' . urlencode($row['id']) . '&check=2" ><i class="fa fa-hand-o-down"></i></a>';
            echo '</div>';
            }
            else
            {
                if($row['v_check']==1)
                 //   echo 'Accept For Verification';
                echo '<span class="label label-success"><i class="fa fa-check"></i>Accepted</span>';
            }
        }
        if($row['status'] == 0 && $row['api_auth'] == 1)
        {
            if($row['api_auth_yn']==0)
            {
            echo '<div class="btn-group btn-group-sm mybtnnn" id="authdiv' . $row['id'] . '">';
            echo '<a class="btn btn-success btn-xs " onclick="makeauth(' . urlencode($row['id']) . ',1);" title="Are you sure you want to Accept Order For Verification?" href="javascript:void(0);" >Authrize</a>';
            echo '<a class="btn btn-danger btn-xs " onclick="makeauth(' . urlencode($row['id']) . ',2);" title="Are you sure you want to Reject Order For Verification?" href="javascript:void(0);" >Normal</a>';
            echo '</div>';
            }
            else
            {
                if($row['api_auth_yn']==1)
                 //   echo 'Accept For Verification';
                echo '<span class="label label-success"><i class="fa fa-check"></i>Auth</span>';
            }
        }
          if($row['status'] ==1  && $row['api_rej_2'] == 1)
         {
            echo '<div class="btn-group btn-group-sm mybtnnn" id="setprovider' . $row['id'] . '">';
            echo '<a class="btn btn-success  btn-xs " onclick="setprovider(' . urlencode($row['id']) . ',1);" title="Set Next Provider" href="javascript:void(0);" >Set New<br> Provider</a>';
            echo '<a class="btn btn-danger btn-xs " onclick="setprovider(' . urlencode($row['id']) . ',2);" title="Set Next Provider" href="javascript:void(0);" >Reject It</a>';

            echo '</div>';
          }

	echo '</td>';

        echo '</tr>';

        $extract[$row['tool_id']][] = array($finaldate . '_' . $row['tool_name'] . '_' . $row['imei'].'_'.$row['custom_1'].'_'.$row['custom_2'].'_'.$row['custom_3'].'_'.$row['custom_4'].'_'.$row['custom_5']);

        //extract
//                       $tool_group_name_new=$row['tool_name'];
//                 //       echo $tool_group_name_new;
//               // if($tool_group_name_new == $old_tool_group_name)
//              //  {
//                    
//                    echo '==================================================='.'<br/>';
//                    echo 'Order Date:'.$finaldate.' Service:'.$tool_group_name_new.'<br/>';
//                    echo '---------------------------------'.'<br/>';
//                    echo $row['imei'].'<br/>';
//                    echo '==================================================='.'<br/>';
        //     }
        //exit;
        //end exctract
    }//end foreach
} else {

    echo '<tr><td colspan="20" class="no_record">';

    echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_Order(s)_Not_Found'));

    echo '</td></tr>';
}

if ($totalRows > 0 and $type == 'verify') {

    echo '

				<tr><td colspan="20">

					<input type="submit" name="process" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" class="btn btn-danger" />

					</td></tr>';

   
    //echo ' <input type="submit" name="download" value="Download" class="btn btn-danger" />';
}


?>

        </table>
            <?php
            
            if($row['status'] == 0 && $row['api_auth'] == 1)
       {
    echo '

				

					<input type="submit" name="auth" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Authorize_selected')) . '" class="btn btn-success" />
                                        <input type="submit" name="normal" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Normal_selected')) . '" class="btn btn-primary" />

					';

    }
    if($row['status'] == 1 && $row['api_rej_2'] == 1)
       {
    echo '

				

					<input type="submit" name="new_pro" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Set_New_Provider(s)')) . '" class="btn btn-success" />
                                        <input type="submit" name="rej" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Reject_All')) . '" class="btn btn-danger" />

					';

    }
            ?>
	</div>	
          
             
      

    </div>
    <div id="last_msg_loader"></div>
            <?php //ob_flush(); ?>
</form>

<section class="MT10 panel" id="extract" style="overflow-y: scroll; height:500px; display: none;">
    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_download_extract.do" method="POST" enctype="multipart/form-data">
        <br/>
            <?php
            ksort($extract);
            // echo '<pre>';
            //var_dump($extract);
            $content = '';
            foreach ($extract as $vals) {
                $a = 1;
                foreach ($vals as $val) {
                    $value = explode('_', $val[0]);
                    //  echo '<pre>';
                    //var_dump($value);
                    if ($a == 1) {
                        $content .= CR;
                        $content .='===================================================' . CR;
                        $content .='Order Date:' . $value[0] . ' Service:' . $value[1] . CR;
                        $content .='---------------------------------' . CR;
                        echo '===================================================' . '<br/>';
                        echo 'Order Date:' . $value[0] . ' Service:' . $value[1] . '<br/>';
                        echo '---------------------------------' . '<br/>';
                    }
                    echo $value[2] .' '.$value[3].' '.$value[4].' '.$value[5].' '.$value[6].' '.$value[7]. '<br/>';
                    $content .=$value[2].' '.$value[3] .' '.$value[4] .' '.$value[5] .' '.$value[6] .' '.$value[7] . CR;
                    //  echo $content;//exit;
                    $a++;
                }
            }
            echo '------------------END---------------------';
            ?>
        <input type="hidden" value="<?php echo $content; ?>" name="content" />
        <br/>
        
</section><button  id="btn_extract" type="submit" name="process" class="btn btn-primary"><i class="fa fa-download"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_download')); ?></button>
    </form>

<div class="row m-t-20">
    <div class="col-md-6 p-l-0" id="somediv2">
        <div class="TA_C navigation" id="paging">
<?php if ($imei == '') {
    echo $pCode;
} ?>
        </div>
    </div>
    <div id="downloadContent" class="display: none;"></div>
    <div class="col-md-6" id="somediv">
        <form action="" method="post" name="page_form">
            <input type="hidden" name="req_type" value="P" />
            <input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id;?>"/>
            <input type="hidden" name="search_user_id" value="<?php echo $search_user_id;?>"/>
            <input type="hidden" name="search_supplier_id" value="<?php echo $search_supplier_id;?>"/>
              <input type="hidden" name="order_id" value="<?php echo $order_id;?>"/>
              <input type="hidden" name="imei" value="<?php echo $imei; ?>"/>
              
            
             
            <div class="row">
                <div class="col-md-5">
                    <select name="dlist_page_size" class="c-select">                    	
                        <option value="0">--<?php echo $admin->wordTrans($admin->getUserLang(),'Set'); ?>--</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>&nbsp;
                </div>
                <div class="col-md-5">
                    <input type="number" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),'Custom Page Size'); ?>" class="form-control" name="page_size" value="" />&nbsp;
                </div>
                <div class="col-md-2">
                    <input class="btn btn-success formSubmit" type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),'Set'); ?>"  />
                </div>
            </div>
        </form>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
  $('#suppsearch').select2();
  $('#usersearch').select2();
  $('#toolsearch').select2();
   //$('#child').select2();
</script>
<script type="text/javascript">
    
   
    
    $(document).ready(function () {
		
		
		$("#btn-search").on('click',function(){
			var sh=$("#sh").val();
			
			if(sh=="1")
			{
			$("#demoGsm1").css("display","block");
            $("#sh").val("0");			
			}
			else
				{
				$("#demoGsm1").css("display","none");
			    $("#sh").val("1");
			}
				
			
		})
	
        $("#btn_extract").css("display","none");
        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
        imeiOrders();
        
         $('#brejct').css('display', 'none');
        $('#extract').css('display', 'none');
          $('#downloadContent').css('display', 'none');
        

//
        $('.subSelectUnavail').click(function (event) {
            if ($(this).is(":checked")) {
                $('#reqeustType').val("locked");
            } else {
                
                var temptype="<?php echo $type;?>";
                $('#reqeustType').val(temptype);
            }
        });
        
         $('.autoCheckAllrej').click(function (event) {
            if ($(this).is(":checked")) {
                $('#reqeustType').val("locked");
            } else {
                 var temptype="<?php echo $type;?>";
                $('#reqeustType').val(temptype);
               // $('#reqeustType').val("pending");
            }
        });

        $('.selectAllBoxesLink').click(function (event) {
            $('#reqeustType').val("locked");
        });

        $('.unselectAllBoxesLink').click(function (event) {
            $('#reqeustType').val("pending");
        });
        $('.autoCheckMe').click(function (event) {
           // $('#reqeustType').val("pending");
          
              if ($(this).is(":checked")) {
                 $('#brejct').css('display', '');
            } else {
                if($('input.autoCheckMe').not(':checked').length==$('.autoCheckMe').length)
                 $('#brejct').css('display', 'none');
            }
        });
        $('.autoCheckAll').click(function (event) {
           // $('#reqeustType').val("pending");
          
              if ($(this).is(":checked")) {
                 $('#brejct').css('display', '');
            } else {
                if($('input.autoCheckAll').not(':checked').length==$('.autoCheckAll').length)
                 $('#brejct').css('display', 'none');
            }
        });

        $('#downloadFile').click(function (e) {
            textToWrite = $('#downloadContent').text().replace(/\n/g, "\r\n");
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(textToWrite));
            element.setAttribute('download', 'download.txt');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        });

        $(document).on('click', '', function (e) {

        });

    });
 $('.autoCheckAllrej').change(function(){
     
      $('.subSelectUnavail').prop("checked",$(this).is(":checked"));
      if($(this).is(":checked")) {
         //   alert('checked');
            $('.unTextBoxFill').show();
            $('#unTextBox').show();
        }
        else
        {
            $('.unTextBoxFill').hide();
            $('#unTextBox').hide();
        }
    
               // $(".subSelect" + $(this).attr("value")).prop("checked",true).trigger("change");
		//updateDownload();
	});
    
    function extract() {
        // alert('ok');
        $('#extract').css('display', 'block');
         $('#btn_extract').css('display', 'block');
        
        $('#order-panel').css('display', 'none');
        $('#process').css('display', 'none');
         $('#somediv').css('display', 'none');
        $('#paging').css('display', 'none');
    }

    
    function showtb()
    {
        if($('#codeBox').css('display') == 'none')
{
$('#codeBox').show();
}else
    $('#codeBox').hide();
       // alert('show');
        
    }
    
       function makeauth(a,b)
                {
                    //alert(abc);
                    $.ajax({
			type: "POST",
			url:'<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_order_api_auth.do',
                        data: "&id=" + a + "&check=" + b,			
			error: function () {
				// alert("Some Error Occur");
			},success: function (msg) {
		
              //  alert(msg);
              if(msg!="")
              {
                        $('#apiauth1'+a).css("display", "none");
                        if(msg=="api_auth")
                         $('#authdiv'+a).html('<span class="label label-success"><i class="fa fa-check"></i>Auth</span>');
                     else
                         $('#authdiv'+a).html('<span class="label label-success"><i class="fa fa-check"></i>Normal</span>');
			}}
		});
                }
                  function setprovider(a,b)
                {
                    //alert(abc);
                    $.ajax({
			type: "POST",
			url:'<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_order_set_provider.do',
                        data: "&id=" + a+ "&check=" + b,		
			error: function () {
				// alert("Some Error Occur");
			},success: function (msg) {
		
              //  alert(msg);
              if(msg!="")
              {
                       // $('#setprovider'+a).css("display", "none");
                       
                         $('#setprovider'+a).html('<span class="label label-success"><i class="fa fa-check"></i>ACTION DONE</span>');
                    
			}}
		});
                }
	
</script>
<script src="<?php echo CONFIG_PATH_ASSETS; ?>getfile/jquery.generateFile.js" type="text/javascript"></script>