<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" type="text/css">


<?php
defined("_VALID_ACCESS") or die("Restricted Access");



$search_user = $request->GetStr('search_user');

$search_ip = $request->GetStr('search_ip');

//$rpt_type = $request->GetStr('rpt_type');

$rpt_type = $_REQUEST['rpt_type'];
if ($rpt_type == "")
    $rpt_type = 1;

$clearSearch = false;

$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$i = $offset;


$i++;

$limit = CONFIG_ORDER_PAGE_SIZE;

$qLimit = " limit $offset,$limit";

$extraURL = '&search_user=' . $search_user;

$from_date = $request->PostStr("from_date");

$to_date = $request->PostStr("to_date");
$order_id = $request->PostInt('order_id');
$search_tool_id = $request->PostStr('search_tool_id');
$qType = '';
//if ($search_user != '') {
//
//    $qType .= (($qType == '') ? ' where ' : ' and ') . ' username like "%' . $search_user . '%"';
//
//    $clearSearch = true;
//}

$txtImeis = $request->PostStr('imei');
if ($from_date != '' && $from_date != $to_date) {

    $dateInput = explode('/', $from_date);
    $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $dateInput = explode('/', $to_date);
    $to_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $qType .= (($qType != '') ? ' and ' : '') . ' a.reply_date_time between "' . $from_date . '" and "' . $to_date . '" ';
} else if ($from_date != '') {

    $dateInput = explode('/', $from_date);
    $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $qType .= (($qType != '') ? ' and ' : '') . ' date(a.reply_date_time) = "' . $from_date . '" ';
} else {
    if ($rpt_type == 1)
        $qType = 'cast(a.reply_date_time as date)=cast(now() as date)';
    else if ($rpt_type == 2)
        $qType = 'cast(a.reply_date_time as date) > Subdate(Curdate(), INTERVAL 1 week) ';
    elseif ($rpt_type == 3)
        $qType = 'cast(a.reply_date_time as date) > Subdate(Curdate(), INTERVAL 1 MONTH) ';
    else
        $qType = 'cast(a.reply_date_time as date)=cast(now() as date)';
}
if ($order_id != '') {
    $qType .= (($qType != '') ? ' and ' : '') . ' a.id = ' . $order_id . ' ';
}
if (trim($txtImeis) != '') {
    $qType .= (($qType != '') ? ' and ' : '') . ' a.imei in ("' . $txtImeis . '") ';
}
if ($search_tool_id != '') {
    $qType2 = "";
    $qType3 = "";
     $qType4 = "";
      $qType5 = "";
    if (strstr($search_tool_id, 'im')) {
        $toool = substr($search_tool_id, 3);
        $qType2 = ' and  a.tool_id = ' . $toool . ' ';
    }
    else if(strstr($search_tool_id, 'fs'))
    {
        $toool = substr($search_tool_id, 3);
        $qType3 = ' and  a.file_service_id = ' . $toool . ' ';
    }
    else if(strstr($search_tool_id, 'sl'))
    {
        $toool = substr($search_tool_id, 3);
        $qType4 = ' and  a.server_log_id = ' . $toool . ' ';
    }
     else if(strstr($search_tool_id, 'pl'))
    {
        $toool = substr($search_tool_id, 3);
        $qType5 = ' and  a.prepaid_log_id = ' . $toool . ' ';
    }
    else
    {
         
    }
}



//echo $order_id;
?>



<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>

                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_search')); ?></h4>

            </div>

            <div class="modal-body">

                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>rpt_new_profit.html" method="post">
                    <input type="hidden" name="rpt_type" value="<?php echo $rpt_type; ?>">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(), 'From Date'); ?></label>
<!--										<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>-->
                                <input class="datepicker" id="fdt" name="from_date" data-date-format="mm/dd/yyyy" value="">
                            </div>
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(), 'To Date'); ?></label>
<!--										<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>-->
                                <input class="datepicker" id="tdt" name="to_date" data-date-format="mm/dd/yyyy" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_unlocking_tool')); ?> </label>
                        <select name="search_tool_id" data-style="btn-white" class="form-control">
                            <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_all_tools')); ?> </option>
                            <?php
                            $sqlTools = 'select 
												concat("im-",itm.id) as tool_id, itm.tool_name, itm.group_id, itm.delivery_time, itm.status,
													igm.group_name
											from nxt_imei_tool_master itm
											left join nxt_imei_group_master igm on(itm.group_id = igm.id)
                                           union                                  		           
											select 
													concat("fs-",fsm.id)as tool_id, fsm.service_name,1  group_id, 0 delivery_time, fsm.`status`,
													"MAIN" group_name
											from nxt_file_service_master fsm
											union
											
										select 
					concat("sl-",slog.id) as tool_id, slog.server_log_name as tool_name, igm.id, slog.delivery_time, slog.status,
												
													igm.group_name
											from nxt_server_log_master slog
											left join nxt_server_log_group_master igm on(slog.group_id = igm.id)
											union
											select 
					concat("pl-",plog.id) as tool_id, plog.prepaid_log_name as tool_name, igm.id, plog.delivery_time, plog.status,
												
													igm.group_name
											from nxt_prepaid_log_master plog
											left join nxt_prepaid_log_group_master igm on(plog.group_id = igm.id)
											
							
                                                                            		           
											
										       
											';
                            echo $sqlTools;
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

                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_search')); ?>" class="btn btn-success" />

                    </div>

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

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Profit_Report')); ?></li>

        </ol>

    </div>

</div>





<div class="row">

    <div class="col-sm-12">

        <div class="m-t-10">



            <h4 class="panel-heading m-b-20">

                <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Profit_Report')); ?>

                <div class="btn-group btn-group-sm pull-right">	

                    <a href="rpt_new_profit.html" class="btn <?php echo ($rpt_type == 1) ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Today')); ?></a>
                    <a href="rpt_new_profit.html?rpt_type=2" class="btn <?php echo ($rpt_type == 2) ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Weekly')); ?></a>
                    <a href="rpt_new_profit.html?rpt_type=3" class="btn <?php echo ($rpt_type == 3) ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_monthly')); ?></a>
                    <a href="#searchPanel" data-toggle="modal" class="btn btn-warning"> <i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_search')); ?> </a>

                    <?php if ($clearSearch == true) { ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>report_admin_login_log.html" data-toggle="modal" class="btn btn-danger btn-xs pull-right"><i class="fa fa-undo"></i></a> <?php } ?>

                </div>

            </h4>
            <style>
					table.dataTable.no-footer {border-bottom:1px solid #ECECEC !important;}
			</style>
            <div class="table-responsive">

                <table  class="display table-stripped table-bordered table-hover" cellspacing="0" width="100%" id="myTable">

                    <thead>
                        <tr>
                            <th> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_ID')); ?> </th>

                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Service')); ?> </th>

                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_IMEI')); ?> </th>

                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Code')); ?> </th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Buy')); ?> </th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Sell')); ?> </th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Profit')); ?> </th>
                        </tr>
                    </thead> <tbody>

                        <?php
//$qType = ($qType == '') ? '' : ' where ' . $qType;
                        //       echo $sql;
                        $sql = 'select a.id,tm.tool_name,a.imei,FROM_BASE64(a.reply) reply,a.b_rate,
 a.credits,round((a.credits- a.b_rate),2) as profit,
  a.user_id,cm.prefix,cm.id as curid 
  from nxt_order_imei_master a 
  inner join nxt_imei_tool_master tm on tm.id=a.tool_id 
  inner join nxt_user_master um on um.id=a.user_id 
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=2 
  and ' . $qType . ' ' . $qType2 . '
union 
select a.id,tm.service_name as tool_name,"NA" as imei,a.unlock_code as reply,a.b_rate,
 a.credits,round((a.credits- a.b_rate),2) as profit,
  a.user_id,cm.prefix,cm.id as curid 
  from nxt_order_file_service_master a 
  inner join nxt_file_service_master tm on tm.id=a.file_service_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType3 . '
  union 
  select a.id,tm.server_log_name as tool_name,"NA" as imei,a.reply,a.b_rate,
 a.credits,round((a.credits- a.b_rate),2) as profit,
  a.user_id,cm.prefix,cm.id as curid 
  from nxt_order_server_log_master a 
  inner join nxt_server_log_master tm on tm.id=a.server_log_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType4 . '
  
union 

select a.id,tm.prepaid_log_name as tool_name,"NA" as imei,a.username as reply,a.b_rate,
 a.credit,round((a.credit- a.b_rate),2) as profit,
  a.user_id,cm.prefix,cm.id as curid 
  from nxt_prepaid_log_un_master a 
  inner join nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType5 . '
  
';
                      //    echo $sql;
                        $query = $mysql->query($sql);



                        //$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'report_admin_login_log.html', $offset, $limit, $extraURL);



                        if ($mysql->rowCount($query) > 0) {

                            $logins = $mysql->fetchArray($query);

                            foreach ($logins as $login) {
                                ?>

                                <tr>

                                    <td><?php echo $login['id']; ?></td>
                                    <td><?php echo $login['tool_name']; ?></td>
                                    <td><?php echo $login['imei']; ?></td>
                                    <td><?php echo  $login['reply']; ?></td>
                                    <td><?php echo $login['b_rate'] . ' ' . $login['prefix']; ?></td>
                                    <td><?php echo $login['credits'] . ' ' . $login['prefix']; ?></td>
                                    <?php if($login['profit']>0)
                                    {
                                        ?>
                                    <td><?php echo $login['profit'] . ' ' . $login['prefix']; ?></td>
                                    <?php }else{?>
                                    <td class="text-danger"><?php echo $login['profit'] . ' ' . $login['prefix']; ?></td>
                                    <?php }?>
                                </tr>

        <?php
    }
} else {


    echo ' <tr>

                                    <td>No Data Found</td>
                                      <td></td>
                                        <td></td>
                                          <td></td>
                                            <td></td>
                                              <td></td>
                                                <td></td>
                                    


                                </tr>';
}
?>
                    </tbody>

                </table>
            </div>
            <div id="sumdata" style="display: none;float: right">
                <h5 class="text-danger" style="margin:20px 0 0; padding:0;"> Profit Sum</h5> <br><hr>
<?php
$sql_sum = 'select sum(round(ok.profit,2)) profit,ok.currency,ok.prefix from (

select sum(round((a.credits- a.b_rate),2)) as profit, cm.currency,cm.prefix 
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and ' . $qType . ' ' . $qType2 . '
group by cm.id
union
select sum(round((a.credits- a.b_rate),2)) as profit, cm.currency,cm.prefix 
  from nxt_order_file_service_master a 
  inner join nxt_file_service_master tm on tm.id=a.file_service_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType3 . '
  
  group by cm.id
  union
   select sum(round((a.credits- a.b_rate),2)) as profit, cm.currency,cm.prefix 
  from nxt_order_server_log_master a 
  inner join nxt_server_log_master tm on tm.id=a.server_log_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType4 . '
   group by cm.id
   union
   select sum(round((a.credit- a.b_rate),2)) as profit, cm.currency,cm.prefix 
  from nxt_prepaid_log_un_master a 
  inner join nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
  inner join nxt_user_master um on um.id=a.user_id
  inner join nxt_currency_master cm on cm.id=um.currency_id 
  where a.`status`=1
  and ' . $qType . ' ' . $qType5 . '
   group by cm.id
   ) ok
   
   group by ok.prefix 
   ';
$query_sum = $mysql->query($sql_sum);



//$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'report_admin_login_log.html', $offset, $limit, $extraURL);



if ($mysql->rowCount($query_sum) > 0) {

    $profit_sum = $mysql->fetchArray($query_sum);
    foreach ($profit_sum as $sum) {
        echo $sum['currency'] . '=' . $sum['profit'] . ' ' . $sum['prefix'] . '<br>';
    }
}
?>

            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#myTable').dataTable({
            "pageLength": <?php echo $limit; ?>,
            "ordering": false,
            "lengthChange": false,
            "fnDrawCallback": function (oSettings) {
                if ($('#myTable tr').length < <?php echo $limit; ?>) {
                    $('.dataTables_paginate').hide();
                    $('#sumdata').show();
                }
            }

        });
        $("#fdt").datepicker();
        $("#tdt").datepicker();
    });
    $('#myTable').on('page.dt', function () {
        var table = $('#myTable').DataTable();
        var info = table.page.info();
        if (info.page + 1 == info.pages)
        {
            // alert('last page');
            //  $('#sumdata').css('display':'block');
            $('#sumdata').show();
        }
        else
            $('#sumdata').hide();
    });

</script>
