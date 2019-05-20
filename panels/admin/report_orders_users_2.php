<?php

defined("_VALID_ACCESS") or die("Restricted Access");



$from = $request->GetStr('from');



$to = $request->GetStr('to');



$search_user_id = $request->GetInt('search_user_id');



$user_id = $request->GetInt('user');







$qstr = '';





$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$i = $offset;

$qType = '';

$i++;

$limit = 50;

$qLimit = " limit $offset,$limit";

$extraURL = '&user=' . $user_id;







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



$sql = 'select a.id,c.tool_name,b.username,a.credits,a.date_time,a.ip,a.imei,a.`status`,



            round(if(a.`status`=2,a.credits,0),2) earn,a.reply,a.reply_by,a.reply_date_time,a.remarks

            from ' . ORDER_IMEI_MASTER . ' a

            inner join ' . USER_MASTER . ' b on a.user_id=b.id

            inner join ' . IMEI_TOOL_MASTER . ' c on a.tool_id=c.id

            where a.user_id=' . $user_id . '

            order by a.date_time desc';

//  echo $sql;exit;

//$query = $mysql->query($sql);

$query = $mysql->query($sql . $qLimit);

$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'report_orders_users_2.html', $offset, $limit, $extraURL);

?>



<div class="row">

    <div class="col-lg-12">

        <ul class="breadcrumb">

            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html"><i class="fa fa-angle-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Main_report')); ?></a></li>

            <li class="active"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Order_IMEI_Detail')); ?></li>

        </ul>

    </div>

</div>	



<?php

if ($from != '') {

    ?>

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'From :'); ?> </label> <?php echo $from; ?>

            <label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'To :'); ?> </label> <?php echo $to; ?><br/>

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

<div class="row">

    <div class="col-lg-12">

        <div class="">

            <h4 class="panel-heading m-b-20">

<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IMEI_order_detail_report')); ?>

                <div class=" btn-group pull-right">

                        <!--a href="report_order_file.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_file')); ?></a>

                        <a href="report_order_imei.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_imei')); ?></a>

                        <a href="report_order_server_log.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_server_log')); ?></a>

                        <a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php $lang->prints('lbl_filter'); ?> </a-->	

<?php if ($clearSearch == true) { ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>report_order_imei.html" data-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-undo"></i></a> <?php } ?>

                </div>

            </h4>



            <table class="table table-striped table-hover">

                <tr>

                    <th>#</th>

                      <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_no.')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tool')); ?></th>

                      <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>

                      <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date_time')); ?></th>

                       <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IMEI')); ?></th>

                             <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></th>

                    <!--th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Available_credits')); ?></th-->

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_earn')); ?></th>

                </tr>

<?php





$sql11 = 'select a.timezone from ' . TIMEZONE_MASTER . ' as a where a.is_default=1';

                    $query111 = $mysql->query($sql11);

                    $rowCount = $mysql->rowCount($query111);

                    if ($rowCount != 0) {

                        $rows = $mysql->fetchArray($query111);

                        $row11 = $rows[0];

                        $dftimezonewebsite = $row11['timezone'];

                    }



                    //get defaul timezone of admin

                    $sql22 = 'select am.*,tz.timezone from ' . ADMIN_MASTER . ' as am

                                                                    inner join ' . TIMEZONE_MASTER . ' as tz

                                                                    on am.timezone_id=tz.id

                                                                    where am.id=' . $admin->getUserId();

                    $query222 = $mysql->query($sql22);

                    $rowCount = $mysql->rowCount($query222);

                    if ($rowCount != 0) {

                        $rows = $mysql->fetchArray($query222);

                        $row22 = $rows[0];

                        $dftimezoneadmin = $row22['timezone'];

                    }













if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    //$i=1;

    $date_time = '';

    foreach ($rows as $row) {

        //$sql_order = 'select count(id) as total, sum(credits) as credits from ' . ORDER_IMEI_MASTER .' where tool_id=' . $row['id'];

        //$query_order = $mysql->query($sql_order);

        echo '<tr>';

        echo '<td>' . $i++ . ' </td>';

         echo '<td>' . $row['id'] . ' </td>';

        echo '<td>' . $row['tool_name'] . ' </td>';

          echo '<td>' . $row['username'] . ' </td>';

        

           $date = new DateTime($row['date_time'], new DateTimeZone($dftimezonewebsite));

                    $date->setTimezone(new DateTimeZone($dftimezoneadmin));

                    $finaldate = $date->format('d-M-Y H:i');

          

          

        //if($mysql->rowCount($query_order)>0)

        //{ 

        //$rows_order = $mysql->fetchArray($query_order);

        echo '<td>' . $row['credits']  . ' </td>';

           echo '<td>' . $finaldate . ' </td>';

             echo '<td>' . $row['imei'] . ' </td>';

             

               switch ($row['status']) {

                        case 0:

                            echo '<td><div class="complatedur"><span class="text-default"><i class="fa fa-spinner fa-pulse text-default"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')) . '</span></div></td>';

                            break;

                        case 1:

                            echo '<td><div class="complatedur"><span class="text-primary"><i class="fa fa-lock text-primary"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_In-Process')). '</span></div></td>';

                            break;

                        case 2:

                            echo '<td><div class="complatedur"><span class="text-success"><i class="fa fa-circle text-success"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')) . '</span></div></td>';

                            break;

                        case 3:

                            echo '<td><div class="complatedur"><span class="text-danger"><i class="fa fa-circle text-danger"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')) . '</span></div></td>';

                            break;

                        

                    }

             

             

             

               // echo '<td>' . $row['status'] . ' </td>';

                 

                 

                 

                 

                 

                 

        echo '<td>' . $row['earn'] . ' </td>';

       // echo '<td>' . $row['orders'] . ' </td>';



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