<?php

defined("_VALID_ACCESS") or die("Restricted Access");



$from = $request->GetStr('from');



$to = $request->GetStr('to');



$search_user_id = $request->GetInt('search_user_id');



$qstr = '';





$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$i = $offset;

$qType = '';

$i++;

$limit = 50;

$qLimit = " limit $offset,$limit";

$extraURL = "";







$clearSearch = false;



if ($from != '' && $to == '') {

    $from = date('Y-m-d', strtotime($from));

    $qstr = ' where a.date_time = ' . $mysql->quote($from);

    $clearSearch = true;

} else if ($from != '' && $to != '') {

    $from = date('Y-m-d', strtotime($from));

    $to = date('Y-m-d', strtotime($to));

    $qstr = ' where a.date_time>= ' . $mysql->quote($from) . ' and a.date_time<=' . $mysql->quote($to);

    $clearSearch = true;

}

if ($search_user_id != 0) {

    $qstr.=(($qstr == '') ? ' where ' : ' and ') . ' a.user_id=' . $search_user_id;

    $clearSearch = true;

}

$sql = 'select itm.id,itm.tool_name 

			from ' . IMEI_TOOL_MASTER . ' itm

			left join ' . ORDER_IMEI_MASTER . ' a on(a.tool_id=itm.id)

			 ' . $qstr . ' group by a.tool_id';

$sql = 'select count(a.id) orders,b.tool_name,round(sum(a.credits),2) Csum 

                    from ' . ORDER_IMEI_MASTER . ' a

                    inner join ' . IMEI_TOOL_MASTER . ' b

                    on a.tool_id=b.id ' . $qstr . '

                    group by a.tool_id';



$sql = 'select a.id, a.tool_name,a.`status` ,count(b.id) orders,ifnull(round(sum(if(b.`status`=2,b.credits,0)),2),0) orignalsum,ifnull(round(sum(b.credits),2),0) Csum 

                    from ' . IMEI_TOOL_MASTER . ' a

                    left join ' . ORDER_IMEI_MASTER . ' b



                    on a.id=b.tool_id

                    

                     group by a.id order by orders desc';



$sql = 'select b.id,b.username ,(select count(a.id) imeiorders FROM ' . ORDER_IMEI_MASTER . ' a

                    where a.user_id=b.id) as imei,

                    (select count(a.id) fileorders FROM ' . ORDER_FILE_SERVICE_MASTER . ' a

                        where a.user_id=b.id) fileorders,

                    (select count(a.id) server FROM ' . ORDER_SERVER_LOG_MASTER . ' a

                    where a.user_id=b.id) server

                        from nxt_user_master b';



//  echo $sql;exit;

//$query = $mysql->query($sql);

$query = $mysql->query($sql . $qLimit);

$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'report_order_imei.html', $offset, $limit, $extraURL);

?>









<?php

if ($from != '') {

    ?>

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'From '); ?>: </label> <?php echo $from; ?>

            <label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'To '); ?>: </label> <?php echo $to; ?><br/>

        </div>

    </div>

    <?php

}

?>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>

                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>

            </div>

            <div class="modal-body">

                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html" method="get">

                    <fieldset>

                        <div class="form-group">

                            <div class="row">

                                <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_from')); ?> </label>

                                    <input type="text" name="from"  class="form-control" value="<?php echo $from; ?>"> 

                                </div>

                                <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?> </label>

                                    <input type="text" name="to"  class="form-control" value="<?php echo $to; ?>"> 

                                </div>

                            </div>

                        </div>

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>

                            <select name="search_user_id" class="form-control">

                                <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>

<?php

$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';

$query_usr = $mysql->query($sql_usr);

$rows_usr = $mysql->fetchArray($query_usr);

foreach ($rows_usr as $row_usr) {

    echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';

}

?>

                            </select>

                        </div>



                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />

                    </fieldset>

                </form>

            </div>

        </div>

    </div>

</div>


<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Reports')); ?></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Orders_Users')); ?></li>

        </ol>

    </div>

</div>
<div class="btn-group btn-group-sm extra">
    <div class="dropdown pull-left btn-group-sm"> 
    </div>


    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>combine_reports.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Top_IMEI_Services')); ?> </a>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_daywise.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Daywise')); ?> </a>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_userwise.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Userwise')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_FILE_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Server_log_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Users_Orders')); ?> </a>

</div>
<link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" />

<hr>

<div class="row">

    <div class="col-md-8">

        <div class="row">

            <h4 class="m-b-20">

<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users_order_report')); ?>

                <div class=" btn-group pull-right">

                        <!--a href="report_order_file.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_file')); ?></a>

                        <a href="report_order_imei.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_imei')); ?></a>

                        <a href="report_order_server_log.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_server_log')); ?></a-->

                    <a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-sm"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_filter')); ?> </a>	

<?php if ($clearSearch == true) { ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>report_order_imei.html" data-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-undo"></i></a> <?php } ?>

                </div>

            </h4>

		<div class="table-responsive">

            <table class="table table-striped table-hover">

                <tr>

                    <th width="16">#</th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_name')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IMEI')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_File')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Server')); ?></th>



                </tr>

<?php

if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    //$i=1;

    $date_time = '';

    foreach ($rows as $row) {

        //$sql_order = 'select count(id) as total, sum(credits) as credits from ' . ORDER_IMEI_MASTER .' where tool_id=' . $row['id'];

        //$query_order = $mysql->query($sql_order);

        echo '<tr>';

        echo '<td>' . $i++ . ' </td>';

      //  echo '<td>' . $row['id'] . ' </td>';

         echo '<td>' . $row['username'] . ' </td>';

         echo '<td><a href="' . CONFIG_PATH_SITE_ADMIN . 'report_orders_users_2.html?user=' . $row['id'] . '">' . $row['imei'] . '</a></td>';

        //if($mysql->rowCount($query_order)>0)

        //{ 

        //$rows_order = $mysql->fetchArray($query_order);

        //     echo $row['id'];

        //echo '<td>' . $row['Csum']  . ' </td>';

        echo '<td>' . $row['fileorders'] . ' </td>';

        echo '<td>' . $row['server'] . ' </td>';

       // echo '<td><a href="' . CONFIG_PATH_SITE_ADMIN . 'report_order_imei_2.html?tool=' . $row['id'] . '">Detail Report</a></td>';





        //}

        //else

        //{

        //echo '<td></td>';

        //echo '<td></td>';

        //echo '<td></td>';

        //}

        echo '</tr>';

    }

}

?>

            </table>

		</div>	
			
        </div>
		
        <div class="row m-t-20">
            <div class="col-md-6 p-l-0">
                <div class="TA_C navigation" id="paging">
                    <?php  echo $pCode;  ?>
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>

    </div>

</div>

