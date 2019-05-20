<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$type = '';
if (isset($_POST['type'])) {
    $type = $request->PostStr('type');
}
if (isset($_GET['type'])) {
    $type = $request->GetStr('type');
}
$imei = $request->PostStr('imei');
$order_id = $request->PostInt('order_id');
$search_tool_id = $request->PostInt('search_tool_id');
$search_status = $request->PostInt('search_status');
$from_date = $_POST["from_date"];
$to_date = $_POST["to_date"];

$ss = 0;
if (isset($_GET["ss"]))
    $ss = 1;

//var_dump($_POST);
$show = 0;

if (isset($_POST['type'])) {

    $type = $request->PostStr('type');
    $show = 1;
}
$imeis = explode(PHP_EOL, $imei);
$txtImeis = "";
//foreach ($imeis as $im) {
// //   if (is_numeric(trim($im))) {
//        $txtImeis .= $im . ',';
////    } else {
////        if ($im != '') {
////            $graphics->messagebox($im . ': Not a valid IMEI Number!');
////        }
////    }
//}



foreach ($imeis as $im) {
    $im = trim($im);
    if (is_numeric($im)) {
        // $txtImeis .= $im . ',';
        //  $txtImeis .= ''.$im . '",';
        $txtImeis .= '"' . $im . '",';
    } else {
        $txtImeis .= '"' . $im . '",';
    }
}
$txtImeis = rtrim($txtImeis, ',');
$txtImeis = rtrim($txtImeis, '"');
$txtImeis = ltrim($txtImeis, '"');



if (($request->GetStr('imei'))) {
    $txtImeis = $request->GetStr('imei');
}
if (($request->GetStr('order_id')) && $request->GetStr('order_id') != '') {
    $order_id = $request->GetStr('order_id');
}
if (($request->GetStr('search_tool_id')) && $request->GetStr('search_tool_id') != '') {
    $search_tool_id = $request->GetStr('search_tool_id');
}
if (($request->GetStr('search_status')) && $request->GetStr('search_status') != '') {
    $search_status = $request->GetStr('search_status');
}

if ($service_imei == "0") {
    echo "<h1>" . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_you_are_authorize_to_view_this_page!')) . "</h1>";
    return;
}

//Pagination
$page_size = 0;

$paging = new paging();
$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
$limit = 40; // Default
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


/// FIND MY IPHONE  Service //
//$objHelper->find_my_iphone_check(NULL,$member->getUserId());
?>





<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_panel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Verfiy_Order')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>imei_verify.html" method="post">
                    <div class="form-group">
                        <input type="hidden" name="t_order" id="t_order">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_reason')); ?></label>
<!--                        <input type="text" name="v_remarks" id="v_remarks" class="form-control">-->
                        <textarea rows="10" name="v_remarks" id="v_remarks" class="form-control"></textarea>

                    </div>
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_submit')); ?>" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #gsmDetails:hover { cursor:pointer; }
    .gsmDetailsInner span { font-weight:bold; min-width:115px; float:left !important; }
	.card-box { margin-bottom:0; }
	.clear { clear:both; }
	.modal { width:90%; left:5%; }
	.modal .modal-dialog .modal-content .modal-header { margin:0; padding:0; }
	.modal-header .close { font-size:35px; }
	.modal-body span { font-weight:bold; }
	.modal-body strong { padding-right:30px; }
	#demoGsm1 .modal-dialog { width:100%; }
	h5.modal-title { font-size:25px; color:#ccc; }
	h5.modal-title span { color:#000; }
</style>


<div class="row">

    <div class="card-box col-lg-12 ">

        <div class="btn-group">
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?type=pending&offset=0&limit=<?= $limit ?>" class="btn <?php echo ($type == 'pending') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_In_Process')); ?></a>
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?type=avail&offset=0&limit=<?= $limit ?>" class="btn <?php echo ($type == 'avail') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Completed')); ?></a>
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?type=rejected&offset=0&limit=<?= $limit ?>" class="btn <?php echo ($type == 'rejected') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_rejected')); ?></a>
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?offset=0&limit=<?= $limit ?>" class="btn <?php echo ($type == '') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_all_orders')); ?></a>
<!--                <a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-custom waves-effect waves-light"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_search')); ?> </a>-->
           

        </div>
        <div class="btn-group" style="float: right">
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit.html" class="btn btn-success"><i class="icon-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_order_new_IMEI_service')); ?></a>


        </div>
	</div>
	<div class="row">
		<a style="margin:9px 36px" class="btn btn-info" id="btn-search" data-toggle="collapse" data-target="#demoGsm"> <!-- <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_search')); ?> --> <strong>Filter Orders</strong></a>
    </div>
</div>
<div id="demoGsm1" class="collapse out" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button> -->
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_search')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?ss=1&offset=0&limit=<?= $limit ?>" method="post">
				
				
				 <div class="col-md-6">
				
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_imei')); ?></label>
                        <textarea name="imei" type="text" class="form-control" id="imei" rows="5"><?php echo $imei; ?></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(), 'From Date'); ?></label>
<!--										<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>-->
                                <input class="datepicker" id="fdt" name="from_date" data-date-format="mm/dd/yyyy" value="<?php echo $from_date ?>">
                            </div>
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(), 'To Date'); ?></label>
<!--										<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>-->
                                <input class="datepicker" id="fdt" name="to_date" data-date-format="mm/dd/yyyy" value="<?php echo $to_date ?>">
                            </div>
							
						</div>		
							
                    </div>
                 </div>
				 
				 <div class="col-md-6">
				 
				 
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_unlocking_tool')); ?> </label>
                        <select name="search_tool_id" data-style="btn-white" class="selectpicker" data-live-search="true">
                            <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all_tools')); ?> </option>
                            <?php
                            $sqlTools = 'select 
													itm.id as tool_id, itm.tool_name, itm.group_id, itm.delivery_time, itm.status,
													igm.group_name
											from ' . IMEI_TOOL_MASTER . ' itm
											left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
                                                                                 where itm.id in (select distinct(a.tool_id) from ' . ORDER_IMEI_MASTER . ' a where a.user_id=' . $member->getUserId() . ')		           
											order by igm.sort_order, itm.sort_order, itm.tool_name';
                            $tools = $mysql->getResult($sqlTools);
                            $myTools = array();
                            foreach ($tools['RESULT'] as $tool) {
                                $myTools[$tool['group_name']][] = $tool;
                            }
                            foreach ($myTools as $group => $tools) {
                                echo '<optgroup label="' . $group . '">';
                                foreach ($tools as $tool) {
                                    echo '<option ' . (($tool['tool_id'] == $search_tool_id) ? 'selected="selected"' : '') . ' value="' . $tool['tool_id'] . '">' . $mysql->prints($tool['tool_name']) . '</option>';
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), 'Order ID'); ?></label>
                        <input name="order_id" type="text" class="form-control" id="order_id" value="" />
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_status')); ?> </label>
                        <select name="search_status" class="form-control chosenf-select">
                            <option value="-1"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all')); ?> </option>
                            <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_inprocess')); ?> </option>
                            <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_completed')); ?> </option>
                            <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_rejected')); ?> </option>
                        </select>
                    </div>
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_search')); ?>" class="btn btn-success" />
                    <?php
                    if ($imei != '') {
                        echo '<a href="' . CONFIG_PATH_SITE_USER . 'imei.html?type=' . $type . '" class="btn btn-danger"><i class="fa fa-undo"></i></a>';
                    }
                    ?>

                </form>
			</div>	
				
				<br class="clear">
				
            </div>
			
					<br class="clear">
        </div>
    </div>
</div>
<div class="container">
    <form class="form-inline" id="new1" action="<?php echo CONFIG_PATH_SITE_USER; ?>imei.html?offset=0&limit=<?= $limit ?>" method="post">
        <div class="form-group">

            <select name="search_status" class="form-control chosenf-select" onchange="getdata();">
                <option value="-1"<?php echo ($search_status == -1 ? 'selected=""' : '') ?>><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all')); ?> </option>
                <option value="1"<?php echo ($search_status == 1 ? 'selected=""' : '') ?>><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_inprocess')); ?> </option>
                <option value="2"<?php echo ($search_status == 2 ? 'selected=""' : '') ?>><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_completed')); ?> </option>
                <option value="3"<?php echo ($search_status == 3 ? 'selected=""' : '') ?>><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_rejected')); ?> </option>
            </select>
        </div>
        <div class="form-group">
            <select name="search_tool_id" class="form-control chosenf-select">
                <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all_tools')); ?> </option>
                <?php
                $sqlTools = 'select 
													itm.id as tool_id, itm.tool_name, itm.group_id, itm.delivery_time, itm.status,
													igm.group_name
											from ' . IMEI_TOOL_MASTER . ' itm
											left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
                                                                                 where itm.id in (select distinct(a.tool_id) from ' . ORDER_IMEI_MASTER . ' a where a.user_id=' . $member->getUserId() . ')		           
											order by igm.sort_order, itm.sort_order, itm.tool_name';
                //echo $sqlTools;exit;
                $tools = $mysql->getResult($sqlTools);
                $myTools = array();
                foreach ($tools['RESULT'] as $tool) {
                    $myTools[$tool['group_name']][] = $tool;
                }
                foreach ($myTools as $group => $tools) {
                    echo '<optgroup label="' . $group . '">';
                    foreach ($tools as $tool) {
                        echo '<option ' . (($tool['tool_id'] == $search_tool_id) ? 'selected="selected"' : '') . ' value="' . $tool['tool_id'] . '">' . $mysql->prints($tool['tool_name']) . '</option>';
                    }
                    echo '</optgroup>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">

            <input type="number" name="imei" class="form-control" id="imeiinline" placeholder="Search IMEI">
        </div>

    </form>
</div>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>imei_download_2.do" enctype="multipart/form-data" method="post">

    <div class="panel-heading">
        <?php
        switch ($type) {
            case 'pending':
                echo '<b>' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl__New_IMEI_orders')) . '</b>';
                break;
            case 'locked':
                echo '<b>' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_Inprocess_IMEI_orders')) . '</b>';
                break;
            case 'avail':
                echo '<b>' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_Completed_IMEI_orders')) . '</b>';
                break;
            case 'rejected':
                echo '<b>' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_rejected_IMEI_orders')) . '</b>';
                break;
            default:
                echo '<b>' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_all_IMEI_orders')) . '</b>';
                break;
        }
        ?>
    </div>

    <div class="panel MT10 table-responsive">
        <table class="table table-hover table-bordered table-striped">
            <tr>
               
                <th><input type="checkbox" value="" id="Download" class="selectAllBoxes CopyAllIMEIs" /></th>
                 <th></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Order')); ?>#</th>
                <th width="25%"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_service')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_imei')); ?></th>
                
                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_unlock_code')); ?></th>
<!--                 <th width="30%" class=""><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Notes')); ?></th>-->

                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_time')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_credits')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Action')); ?></th>
                <!--th width="5%" class="text-center">Status</th-->
            </tr>
            <?php
            //$limit = 4;  // Default
            //get ccode from ip
            //
            
            
            $qLimit = " limit $offset,$limit";
            $extraURL = '&type=' . $type . '&limit=' . $limit . '&imei=' . $txtImeis . '&order_id=' . $order_id . '&search_tool_id=' . $search_tool_id . '&search_status=' . $search_status;

            $cr = $crProcess = $crUsed = 0;


            $qType = '';

            switch ($type) {
                case 'pending':
                case 'locked':
                    $qType = ' (im.status=0 or im.status=1) ';
                    break;
                case 'avail':
                    $qType = ' im.status=2 ';
                    break;
                case 'rejected':
                    $qType = ' im.status=3 ';
                    break;
            }

            if (trim($txtImeis) != '') {
                $qType .= (($qType != '') ? ' and ' : '') . ' im.imei in ("' . $txtImeis . '") ';
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
            if ($search_tool_id != '') {
                $qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id . ' ';
            }

            if ($from_date != '' && $from_date != $to_date) {
                $dateInput = explode('/', $from_date);
                $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
                $dateInput = explode('/', $to_date);
                $to_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
                $qType .= (($qType != '') ? ' and ' : '') . ' im.date_time between "' . $from_date . '" and "' . $to_date . '" ';
            } else if ($from_date != '') {
                $dateInput = explode('/', $from_date);
                $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
                $qType .= (($qType != '') ? ' and ' : '') . ' date(im.date_time) = "' . $from_date . '" ';
            }

            $qType = ($qType == '') ? '' : ' and ' . $qType;



            $sql = 'select im.*, im.id as imeiID,
							DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
							DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
							tm.tool_name as tool_name,
                                                        if(now()< im.reply_date_time + INTERVAL tm.veri_days DAY=1,1,0) tool_verify,						
							tm.cancel as tool_cancel,
							nm.network as network_name,
							mm.model as model_name, 
							bm.brand as brand_name,
							cm.prefix, cm.suffix
						from ' . ORDER_IMEI_MASTER . ' im
						left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
						left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
						left join ' . IMEI_NETWORK_MASTER . ' nm on(im.network_id = nm.id)
						left join ' . IMEI_MODEL_MASTER . ' mm on(im.model_id = mm.id)
						left join ' . IMEI_BRAND_MASTER . ' bm on(im.brand_id = bm.id)
						where im.user_id=' . $member->getUserId() . '
						' . $qType . '
						order by im.id DESC';

            //    echo $sql;
            $query = $mysql->query($sql . $qLimit);
            $strReturn = "";

            $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'imei.html', $offset, $limit, $extraURL);

            $i = $offset;

            // time zone logic
            //get defaul timezone of admin





            if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                $obj = new country();
                foreach ($rows as $row) {

                    $ccode = $obj->getccode($row['ip']);
                    // echo $ccode;
                    //  echo  CONFIG_PATH_SITE . 'images/flags/' . strtolower($ccode) . '.png';
                    //  exit;
                    $order_reply = base64_decode($row['reply']);

                    if (strstr($order_reply, "stylesheet") || strstr($order_reply, "script src") || strstr($order_reply, "img src"))
                        $order_reply = 'Page Not found';

                    if ($row['message'] != '' && $row['status']!=1)
                        $order_reply = base64_decode($row['message']);

                    if ($order_reply == "")
                        $order_reply = "NA";

                    $dtDateTime = $member->datecalculate($row['dtDateTime']);

                    if ($row['reply_date_time'] != '0000-00-00 00:00:00' && $row['reply_date_time'] != NULL && $row['status']!=1) {

                        $finaldate2 = $member->datecalculate($row['reply_date_time']);
                    }

                    if ($finaldate2 == "")
                        $finaldate2 = "NA";


                    switch ($row['status']) {
                        case 0:
                            $tempstatus = $admin->wordTrans($admin->getUserLang(), $lang->get('com_New_Order'));
                            //   $cr += $row['credits'];
                            break;
                        case 1:
                            $tempstatus = $admin->wordTrans($admin->getUserLang(), $lang->get('com_In_Process'));
                            //   $crProcess += $row['credits'];
                            break;
                        case 2:
                            $tempstatus = $admin->wordTrans($admin->getUserLang(), $lang->get('com_Completed'));
                            //    $crUsed += $row['credits'];
                            break;
                        case 3:
                            $tempstatus = $admin->wordTrans($admin->getUserLang(), $lang->get('com_rejected'));
                            //    $cr += $row['credits'];
                            break;
                    }

                    $user_notesss = "NA";
                    if ($row['remarks'] != "")
                        $user_notesss = $row['remarks'];
                    echo '<tr>';
                                        echo '<td><input type="checkbox" class="subSelectDownload copySelected checkbox-inline" name="chk[]" id="chk" value="' . $row['id'] . '" /></td>';

                    echo '<td>
								<a id="gsmDetails" data-toggle="modal" data-target="#myModal-' . $row['id'] . '" aria-expanded="true"><i class="fa fa-plus" style="margin:8px 0 0 12px"></i></a>
								
								
								
								

								
								
								
								
								<div id="myModal-' . $row['id'] . '" class="modal fade" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel" aria-hidden = "true">
								   <div class = "modal-dialog  modal-md">
									  <div class = "modal-content">
										 
										 <div class = "modal-header">
											<button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
												  &times;
											</button>
											<h5 class="modal-title"><span>Code : </span>' . $order_reply .' </h5>
										 </div>
         
										 <div class = "modal-body">

																	<div class="card card-block row"> 
																		<div><p><span class="col-md-7">Order </span>' . '<strong>:</strong>'.$row['id'] . '</p></div>
																		<div><p><span class="col-md-7">IMEI</span>' . '<strong>:</strong>'. $row['imei'] . '</p></div>
																		<div><p><span class="col-md-7">Code</span>' . '<strong>:</strong>'. $order_reply .' </p></div>
																		<div><p><span class="col-md-7">Submitted On </span>' . '<strong>:</strong>'. $dtDateTime . '</p></div>
																		<div><p><span class="col-md-7">Replied On </span> ' . '<strong>:</strong>'. $finaldate2 . '</p></div>
																		<div><p><span class="col-md-7">Price </span> ' . '<strong>:</strong>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</p></div>
																		<div><p><span class="col-md-7">Status</span>' .  '<strong>:</strong>' . $tempstatus . '</p></div>
																		<div><p><span class="col-md-7">User Notes</span>' . '<strong>:</strong>' . $row['remarks'] . '</p></div>
																		<br style="clear:both">
																													
																	</div>
																	
																	
																	
																	
										 </div>

         
									  </div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->							
									
									
									
									
									
									
									
								</div>
						  </td>';
                    echo '<td class="text_center" alt="im-' . $row['id'] . '">' . $row['id'] . '</td>';
                     echo '<td>' . $row['tool_name'] . '</td>';
                    echo '<td><b>' . $row['imei'] . '</b><br /><small class="text-danger">' . $row['ip'] . ' <img src="' . CONFIG_PATH_SITE . 'images/flags/' . strtolower($ccode) . '.png" /> </small></td>';
                   
                    if (defined("DEMO")) {
                        echo '<td>*****Demo*****</td>';
                    } else {

                        echo '<td class="">';
                        echo ($row['reply'] != '') ? '<div class="breaking">' . $order_reply . '</div>' : '';
                        echo ($row['message'] != '' && $row['status']!=1 && $row['status']!=0 ) ? '<div style="color:red">' . base64_decode($row['message']) . '</div>' : '';
                        //echo ($row['remarks'] != '') ? '<div class="alert alert-info"><b>'.$lang->get('com_customer_note').'</b>: ' . $row['remarks'] . '</div>' : '';
                        echo '</td>';
                    }

                    //echo '<td class="text-center">' . $row['remarks'] . '</td>';
//                                         //end
                    //    echo $dtDateTime;exit;

                    echo '<td><small>' . $dtDateTime . '<br /><b>' . $finaldate2 . '</b></small></td>';
                    echo '<td>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</td>';
                    echo '<td>';

                    // echo '</td>';
                    //  echo '<td class="text-center">';
                    switch ($row['status']) {
                        case 0:
                            echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_New_Order')) . '</span>';
                            $cr += $row['credits'];
                            break;
                        case 1:
                            echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_In_Process')) . '</span>';
                            $crProcess += $row['credits'];
                            break;
                        case 2:
                            echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_Completed')) . '</span>';
                            $crUsed += $row['credits'];
                            break;
                        case 3:
                            echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_rejected')) . '</span>';
                            $cr += $row['credits'];
                            break;
                    }


                    echo '<br><div class="btn-group">';
                    if ($row['status'] == "0" && $row['tool_cancel'] == "1") {
                        echo '<a href="' . CONFIG_PATH_SITE_USER . 'imei_cancel.do?id=' . $row['id'] . '"  data-toggle="tooltip" data-placement="bottom" title="Click To Cancel that order"><h5><i class="glyphicon glyphicon-remove" style="color:red;"></i><h5></a>';
                    } elseif ($row['status'] == "3" || $row['status'] == "1" || $row['status'] == "0"/* $row['status'] == "2" && $row['verify'] == "0" && $row['tool_verify'] == "1" */) {
                        //echo '<a href="' . CONFIG_PATH_SITE_USER . 'imei_verify.do?id=' . $row['id'] . '" class="btn btn-default" >'.$lang->get('lbl_verify').'</a>';
                    } elseif ($row['status'] == "2" && $row['verify'] == "1" && $row['v_check'] == 0) {
                        echo '<span class="label label-warning"><i class="fa fa-warning-sign"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_requested_verification')) . '</span>';
                    } elseif ($row['verify'] == "2") {
                        echo '<span class="label label-success"><i class="fa fa-check"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_verified')) . '</span>';
                    } elseif ($row['verify'] == "1" && $row['v_check'] == 1) {
                        echo '<span class="label label-primary"><i class="fa fa-warning-sign"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_under_verification')) . '</span>';
                    } elseif ($row['verify'] == "0" && $row['v_check'] == 2) {
                        echo '<span class="label label-danger"><i class="fa fa-warning-sign"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_verification_denied')) . '</span>';
                    } elseif ($row['status'] == "2" && $row['tool_verify'] == "1") {
                        //  echo '<a href="#"  data-toggle="tooltip" data-placement="bottom" title="Click To Verify that order"><h5><i class="glyphicon glyphicon-warning-sign" style="color:black;"></i><h5></a>';
                     //  echo '<div id="for_verfi">';
                        echo ' <a href="javascript:void(0);" onclick="verify2(' . $row['id'] . ')" data-toggle="modal" data-target="#v_panel" title="Click To Verify that order"><i class="glyphicon glyphicon-warning-sign"></i></a>';

                      //  echo '<textarea rows="3" name="v_remarks" id="v_remarks2" class="form-control"></textarea>';
                     //   echo '</div>';
                    }
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
            }
            ?>
        </table>
        <?php //echo $cr . '/' . $crUsed . '/' . $crProcess; ?>
    </div>
    <div class="col-lg-12">

        <div class="">
            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_export_data')); ?>" class="btn btn-success" />
        </div>
</form> 
<div align="right">
    <form action="" method="post" name="page_form">
        <input type="hidden" name="req_type" value="P" />

        <select name="dlist_page_size">
            <option value="0">--<?php echo $admin->wordTrans($admin->getUserLang(), 'Set Page Size'); ?>--</option>
            <option value="50"><?php echo $admin->wordTrans($admin->getUserLang(), '50'); ?></option>
            <option value="100"><?php echo $admin->wordTrans($admin->getUserLang(), '100'); ?></option>
            <option value="250"><?php echo $admin->wordTrans($admin->getUserLang(), '250'); ?></option>
            <option value="500"><?php echo $admin->wordTrans($admin->getUserLang(), '500'); ?></option>
            <option value="1000"><?php echo $admin->wordTrans($admin->getUserLang(), '1000'); ?></option>
        </select>&nbsp;
        <input type="number" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(), 'Custom Page Size'); ?>" name="page_size" value="" />&nbsp;<input class="btn btn-success formSubmit" type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), 'Set'); ?>"  />
    </form>
</div>
</div> <input type="hidden" value="1" id="sh" />
<?php echo $pCode; ?>

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>

<script type="text/javascript">
                $("#Download").change(function () {
                    $("input:checkbox").prop('checked', $(this).prop("checked"));
                });
</script>
<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/css/datepicker.css" />
<script type="text/javascript" src="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
                $(document).ready(function ()
                {
                    var ok =<?php echo $ss; ?>;
                    if (ok == 1)
                        $('body').scrollTo(240);
                    $("#btn-search").on('click', function () {
                        var sh = $("#sh").val();

                        if (sh == "1")
                        {

                            $("#demoGsm1").css("display", "block");
                            $("#sh").val("0");
                            $('body').scrollTo(240);
                        }
                        else
                        {

                            $("#demoGsm1").css("display", "none");
                            $("#sh").val("1");
                        }


                    })

                    $('.datepicker').datepicker({
                        startDate: '-3d'
                    });
                    $('#from_date').datepicker({format: 'yyyy-mm-dd'});
                    $('#to_date').datepicker({format: 'yyyy-mm-dd'});
                });

                function verify(a)
                {
                    //alert(a);
                    $("#t_order").val(a);
                    //  $("#v_panel").show();
                }
                function verify2(a)
                {
                   // alert(a);
                    $("#t_order").val(a);
                    $("#v_panel").show();
//                    var o_id = a;
//                    var reason = $("#v_remarks2").val();
//                    if (reason != "")
//                    {
//                      //  alert(o_id + reason);
//                        $.ajax({
//                            type: "POST",
//                            url: '<?php echo CONFIG_PATH_SITE_USER; ?>' + "ajax_imei_verify.do",
//                            data: "&t_order=" + o_id + "&v_remarks=" + reason,
//                            error: function () {
//                                //  alert("Some Error Occur");
//                            },
//                            success: function (msg) {
//                                // you are now offline
//                                //$.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
//                                // alert(msg);
//                                if (msg == '1')
//                                  //  alert('done');
//                                      //$("p").html("Hello <b>world!</b>");
//                                $('#for_verfi').html('<span class="label label-warning"><i class="fa fa-warning-sign"></i> Requested Verification</span>');
//                                else
//                                    alert(msg);
//                            }
//                        });
//                    }
                   //$("#v_panel").show();
                }
                $("#imeiinline").blur(function () {
                    if ($("#imeiinline").val() != "")
                        $("#new1").submit();
                });

                function getdata()
                {
                    $("#new1").submit();
                }
</script>

