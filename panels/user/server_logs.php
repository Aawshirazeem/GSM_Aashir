<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$type = $request->getStr('type');

if ($service_logs == "0") {
    echo "<h1>You are authorize to view this page!</h1>";
    return;
}
if(isset($_GET['reply']))
{
    $code=$_GET['code'];
    if($code == "0")
    {
      $reply='Error:'.$_GET['reply'];  
      $color="red";
    }
    else
    {
        $reply=$_GET['reply']; 
        $color="green";
    }    
        
   
}
            
$crM = $objCredits->getMemberCredits();
$prefix = $crM['prefix'];
$suffix = $crM['suffix'];
$rate = $crM['rate'];
?>
<style>
  #gsmDetails:hover { cursor:pointer; }
  .gsmDetailsInner span { font-weight:bold; min-width:115px; float:left !important; }
</style>
<div class="row">
    <label style="color: <?php echo $color; ?>"><?php echo $reply; ?></label>
	<div class=" card-box col-sm-12">
		<div class="btn-group">
			<h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_log_orders')); ?></h3>

                </div>
           
            <div class="btn-group" style="float: right">
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs_submit.html" class="btn btn-success"><i class="icon-plus"></i>  <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_server_log')); ?></a>
		</div>
	
	</div>
	
</div>
<div class="clear"></div>
<div class="panel MT10 table-responsive">
<table class="table table-hover table-striped table-bordered">
    <tr>
        <th></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Order')); ?>#</th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Status')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_log')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_more_information')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_notes')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Date')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Result')); ?></th>

    </tr>
    <?php
    $paging = new paging();
    $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
    $limit = 40;
    $qLimit = " limit $offset,$limit";
    $extraURL = '&type=' . $type;


    $qType = '';

    switch ($type) {
        case 'pending':
            $qType = ' (im.status=0 or im.status=-1) ';
            break;
        case 'avail':
            $qType = ' im.status=1 ';
            break;
        case 'rejected':
            $qType = ' im.status=2 ';
            break;
    }


    $qType = ($qType == '') ? '' : ' and ' . $mysql->getStr($qType);


    $sq1 = 'select im.id, slm.server_log_name as server_log_name,im.custom_value,im.credits,im.message,im.reply, im.date_time as datee, im.`status`,"Server Log" typee,im.remarks as unotes
from ' . ORDER_SERVER_LOG_MASTER . ' im 
left join ' . SERVER_LOG_MASTER . ' slm 
on (slm.id = im.server_log_id) where im.user_id=' . $mysql->getInt($member->getUserId()) . '
					' . $qType . '

union all 

select plum.id,plm.prepaid_log_name as server_log_name,plum.username as custom_value, plum.credit as credits,plum.remarks as message,

                    "" as reply,plum.reply_date_time as datee,plum.`status`,"Prepaid Log" typee,"" as unotes
 from ' . PREPAID_LOG_UN_MASTER . ' plum 
 left join ' . PREPAID_LOG_MASTER . ' plm on (plum.prepaid_log_id = plm.id)
  left join ' . USER_MASTER . ' um on (plum.user_id = um.id)
      inner join ' . PREPAID_LOG_AMOUNT_DETAILS . ' as amd
on plum.prepaid_log_id=amd.log_id
 where plum.status!=0 and plum.user_id=' . $member->getUserId() . ' and amd.currency_id=' . $member->getCurrencyId() . '
 
  order by datee DESC';

    $sql = $sq1 . $qLimit;
   //    echo $sq1;exit;

    /* 	$sql = 'select im.`status`,slm.server_log_name,im.custom_value,im.credits,im.id,im.message,im.reply
      from ' . ORDER_SERVER_LOG_MASTER . ' im
      left join ' . SERVER_LOG_MASTER . ' slm on (slm.id = im.server_log_id)
      where
      im.user_id=' . $mysql->getInt($member->getUserId()) . '
      ' . $qType . ' order by im.id DESC'; */
    $query = $mysql->query($sq1);
    // echo $mysql->rowCount($query);exit;
    $strReturn = "";

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'server_logs.html', $offset, $limit, $extraURL);

    $i = $offset;
$a=0;
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        foreach ($rows as $row) {
            $i++;
            $a++;
             switch ($row['status']) {
                case -1:
                case 0:
                    $tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order'));
                    break;
                case 1:
                    $tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed'));
                    break;
                case 2:
                    $tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected'));
                    break;
            }
 $finaldate2 = $member->datecalculate($row['datee']);
            echo '<tr>';
            
            
            
            
            
            
            $order_reply=$row['reply'];
            if($order_reply!="")
                $order_reply=$row['message'];
            
            if($order_reply=="")
                $order_reply="NA";
            
              echo '<td>
								<a id="gsmDetails" data-toggle="collapse" data-target="#collapse-example-' .$a . '" aria-expanded="true" aria-controls="collapse-example-1"><i class="fa fa-plus" style="margin:8px 0 0 12px"></i></a>
								<div id="collapse-example-' . $a . '" class="collapse out gsmDetailsInner" aria-expanded="true">
									<div class="card card-block"> 
										<p><span>Order :</span>' . $row['id'] . '</p>
									
										<p><span>Code :</span>' .$order_reply. '</p>
										
										<p><span>Replied On :</span> '.$finaldate2.'</p>
                                                                                    <p><span>Price :</span> ' . $objCredits->printCredits($row['credits'], $prefix, $suffix). '</p>
                                                                                        <p><span>Status:</span>'.$tempstatus.'</p>
                                                                                            <p><span>User Notes:</span>'.$row['unotes'].'</p>
                                                                                    
									</div>
								</div>
						  </td>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>';
            switch ($row['status']) {
                case -1:
                case 0:
                    echo '<span class="label label-default">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')).'</span>';
                    break;
                case 1:
                    echo '<span class="label label-primary">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')).'</span>';
                    break;
                case 2:
                    echo '<span class="label label-danger">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')).'</span>';
                    break;
            }

            echo '</td>';
            echo '<td><b>' . $row['server_log_name'] . '<br><span class="label label-inverse">' . $row['typee'] . '</span></b></td>';
            echo '<td>' . $row['custom_value'] . '</td>';
            
            // change date time acc to user time zone
         
                        
                                 
           
                                   
            
            echo '<td class="text-center">' . $row['unotes'] . '</td>';
            echo '<td class="text-center">' . $finaldate2 . '</td>';
            echo '<td>' . $objCredits->printCredits($row['credits'], $prefix, $suffix) . '</td>';
            echo '<td class="text-right">';
            if ($row['status'] == "0") {
                echo '<a href="' . CONFIG_PATH_SITE_USER . 'server_logs_cancel.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm"> <i class="icon-remove"></i> '.$admin->wordTrans($admin->getUserLang(),'Cancel').'</a>';
            } else if ($row['status'] == "1") {
                echo '<small>' . $row['reply'] . '</small>';
            } else if ($row['status'] == "-1") {
                echo '<small>'.$admin->wordTrans($admin->getUserLang(),'Pending').'</small>';
            } else {
                echo '<small>' . $row['message'] . '</small>';
            }
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '!</td></tr>';
    }
    ?>
</table></div>
    <?php echo $pCode; ?>