<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$type = $request->getStr('type');
$search_status = $request->PostInt('search_status');
$paging = new paging();
$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
$limit = 40;
$qLimit = " limit $offset,$limit";

$from_date=$_REQUEST["from_date"];
$to_date=$_REQUEST["to_date"];
//echo $from_date;
$extraURL = '&from_date=' . $from_date . '&to_date=' . $to_date . '&search_status=' . $search_status;
$qType="";
//echo $from_date.$to_date;
if ($from_date != '' && $from_date != $to_date) {
    $dateInput = explode('/', $from_date);
    $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $dateInput = explode('/', $to_date);
    $to_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $qType .=' ctm.date_time between "' . $from_date . '" and "' . $to_date . '" ';
} else if ($from_date != '') {
    $dateInput = explode('/', $from_date);
    $from_date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
    $qType =' date(ctm.date_time) = "' . $from_date . '" ';
}
     //$qType =$qType. ($qType == '') ? '' : ' and ';
if($qType!="")
    $qType.=' and';
   //  echo $qType;
if ($search_status == -1) {
    $sql = 'select ctm.* , um.username as username1, um2.username as username2
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					where   ' . $qType . ' (user_id=' . $member->getUserID() . ' or user_id2=' . $member->getUserID() . ')
                                          
					order by ctm.id DESC';
} else if ($search_status == 1) {
    $sql = 'select ctm.* , um.username as username1, um2.username as username2
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					where   ' . $qType . ' (user_id=' . $member->getUserID() . ' or user_id2=' . $member->getUserID() . ') and (ctm.info="Credits Added by Admin" or ctm.info="Credits Added by [Auto Pay]") 
                                          
					order by ctm.id DESC';
} else if ($search_status == 2) {
    $sql = 'select ctm.* , um.username as username1, um2.username as username2
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					where  ' . $qType . ' (user_id=' . $member->getUserID() . ' or user_id2=' . $member->getUserID() . ') and (ctm.info="Credits Revoked by Admin" or ctm.info="Credits Revoked by [Auto Pay]") 
                                           
					order by ctm.id DESC';
} else {
  $sql = 'select ctm.* , um.username as username1, um2.username as username2,oim.imei
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
                                            left join nxt_order_imei_master oim
                                        on ctm.order_id_imei=oim.id and ctm.info="IMEI Order"
					where   ' . $qType . ' (ctm.user_id=' . $member->getUserID() . ' or ctm.user_id2=' . $member->getUserID() . ')
                                          
					order by ctm.id DESC';
}
?>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html?offset=0&limit=<?= $limit ?>" method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'From Date'); ?></label>
<!--										<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>-->
                                <input class="datepicker" id="fdt" name="from_date" data-date-format="mm/dd/yyyy" value="">
                            </div>
                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'To Date'); ?></label>
<!--										<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>-->
                                <input class="datepicker" id="fdt" name="to_date" data-date-format="mm/dd/yyyy" value="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Type')); ?> </label>
                        <select name="search_status" class="form-control chosenf-select">
                            <option value="-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all')); ?> </option>
                            <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Added')); ?> </option>
                            <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Revoked')); ?> </option>

                        </select>
                    </div>
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info table-responsive">
    <div class="panel-heading">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_statements')); ?></label>  
        <a href="#searchPanel" style="padding-bottom: 9px" data-toggle="modal" class="btn btn-default pull-right"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>
	<br style="margin-bottom: 15px">
    </div>

    <div class="col-sm-4" style="float: right">

    </div>
    <table class="table table-striped table-hover">
        <tr>
            <th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_trans_id')); ?></th>
            <th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_id')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_transaction_info')); ?></th>
                  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note')); ?></th>
             <th style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IMEI')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time')); ?></th>
            <th width="16" style="text-align:center"></th>
            <th width="100" style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>
            <th width="100" style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_net')); ?></th>
        </tr>
        <tr class="searchPanel hidden">
            <td></td>
            <td></td>
            <td><input type="text" class="textbox_small" name="username" id="username" value="" /></td>
            <td class="toolbarSkin text_center" style="text-align:center">
                <input type="submit" value="Search" /><input type="button" class="showHideSearch" value="Cancel" />
            </td>
        </tr>
<?php
//$sql = 'select ctm.* , um.username as username1, um2.username as username2
//					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
//					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
//					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
//					where user_id=' . $member->getUserID() . ' or user_id2=' . $member->getUserID() . '
//					order by ctm.id DESC';
//echo $sql;
$query = $mysql->query($sql . $qLimit);
$result = $mysql->getResult($sql, false, 20, $offset, CONFIG_PATH_SITE_USER . 'credits_history.html', array());
 $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'credits_history.html', $offset, $limit, $extraURL);
$strReturn = "";

$i = $offset;
  if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                //$obj=new country();
                foreach ($rows as $row) {
        $i++;
        echo '<tr>';
        echo '<td align="center">' . $row['id'] . '<br /></td>';
        echo '<td align="center">';
        echo ($row['order_id_imei'] != '0') ? $row['order_id_imei'] : '';
        
        echo ($row['order_id_file'] != '0') ? $row['order_id_file'] : '';
        echo ($row['order_id_server'] != '0') ? $row['order_id_server'] : '';
        echo '</td>';
      
        echo '<td><b>' . $row['info'] . '</b><br />';
        switch ($row['trans_type']) {
            case 6:
                echo (($member->getUserID() == $row['user_id']) ? $row['username2'] : $row['username1']);
                break;
        }
        echo '</td>';
           echo '<td><b>';
         echo ($row['admin_note'] != '') ? $row['admin_note'] : $row['user_note'];     echo '</b>';
        echo '</td>';
           echo '<td style="text-align:center"><b>';
         echo ($row['imei'] != '') ? $row['imei'] : '';     echo '</b></td>';

         $finaldate2 = $member->datecalculate($row['date_time']);
        echo '<td>' . $finaldate2. '</td>';
        //echo '<td align="center"><u>' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc'] : $row['credits_acc_2']) . '</u></td>';
        //echo '<td align="center">'. (($member->getUserID() == $row['user_id']) ? $row['credits_acc_process'] : $row['credits_acc_process_2']) . '</td>';
        //echo '<td align="center">' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc_used'] : $row['credits_acc_used_2']) . '</td>';

        echo '<td align="center">';
        switch ($row['trans_type']) {
            case 0:
                echo '<i class="fa fa-plus-circle text-success"></i>';
                break;
            case 1:
                echo '<i class="fa fa-plus-circle text-success"></i>';
                break;
            case 2:
                echo '<i class="fa fa-minus-circle text-danger"></i>';
                break;
            case 3:
                echo '<i class="fa fa-minus-circle text-danger"></i>';
                break;
            case 6:
                if ($member->getUserID() == $row['user_id']) {
                    echo '<i class="fa fa-arrow-up text-success"></i>';
                } else {
                    echo '<i class="fa fa-arrow-down text-danger"></i>';
                }
                break;
        }
        echo '</td>';

        echo '<td align="center"><span class="badge bg-inverse">' . round($row['credits'],2) . '</span></td>';
        echo '<td align="center">
								<b>' . (($member->getUserID() == $row['user_id']) ? round($row['credits_after'],2) : round($row['credits_after_2'],2)) . '</b>
							</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="11" class="no_record">No record found!</td></tr>';
}
?>
    </table>
        <?php echo $pCode; ?>
</div>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/css/datepicker.css" />
<script type="text/javascript" src="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
    $(document).ready(function ()
    {

        $('.datepicker').datepicker({
            startDate: '-3d'
        });
        $('#from_date').datepicker({format: 'yyyy-mm-dd'});
        $('#to_date').datepicker({format: 'yyyy-mm-dd'});
    });
</script>