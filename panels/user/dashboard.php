<?php
defined("_VALID_ACCESS") or die("Restricted Access");
//$crM = $objCredits->getMemberCredits();

$pendingIMEIS = $pendingFiles = $pendingServerLogs = '-';
if ($service_imei == "1") {
    $sqlCount = 'select count(id) as total from ' . ORDER_IMEI_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and (status=0 or status=1)';
    $queryCount = $mysql->query($sqlCount);
    $rowsCount = $mysql->fetchArray($queryCount);
    $pendingIMEIS = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
}
if ($service_file == "1") {
    $sqlCount = 'select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and status=0';
    $queryCount = $mysql->query($sqlCount);
    $rowsCount = $mysql->fetchArray($queryCount);
    $pendingFiles = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
}
if ($service_logs == "1") {
    $sqlCount = 'select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and status=0';
    $queryCount = $mysql->query($sqlCount);
    $rowsCount = $mysql->fetchArray($queryCount);
    $pendingServerLogs = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
}


// calculate imeis for chart
//echo 'aaaaaaaa';
$totalorders = 0;
$sql_lf = 'select count(*) as tor from nxt_order_imei_master oim where oim.user_id=' . $mysql->getInt($member->getUserId()) . ' and status in (0,1,2,3)';
//echo $sql_lf;
$query_lf = $mysql->query($sql_lf);
$rows_lf = $mysql->fetchArray($query_lf);
//echo $rows_lf[0]['tor'];
if ($rows_lf[0]['tor'] != 0)
    $totalorders = $rows_lf[0]['tor'];

$porders = 0;
$pendingpercantage = 0;
$aorders = 0;
$avapercantage = 0;
$rorders = 0;
$rejpercantage = 0;


if ($totalorders != 0) {
    $sql_lf = 'select count(*) as total,if(oim.status=0 or oim.status=1,"Pending",if(oim.status=2,"Available",if(oim.status=3,"Reject","Locked"))) as orderstatus from '.ORDER_IMEI_MASTER.' oim 
        
    where oim.user_id= ' . $mysql->getInt($member->getUserId()) . ' and oim.status!=1 group by oim.status';
    $sql_lf='select count(*) as total,
if(oim.status=0,"new",
if(oim.status=1 ,"inpro",
if(oim.status=2,"Available",
if(oim.status=3,"Reject","Locked")))) 
as orderstatus from ' . ORDER_IMEI_MASTER . ' oim
where oim.user_id= ' . $mysql->getInt($member->getUserId()) . '
group by oim.status
';
    
//echo $sql_lf;
    //$temp=0;
    $query_lf = $mysql->query($sql_lf);
    if ($mysql->rowCount($query_lf) > 0) {
        $rows_lf = $mysql->fetchArray($query_lf);
        foreach ($rows_lf as $row_lf) {
            if ($row_lf['orderstatus'] == 'new') {
                $pordersnew = $row_lf['total'];
                //$temp=$pordersnew;
                //  echo $porders;
              //  $pendingpercantage = $mysql->getInt(($porders / $totalorders) * 100);
                //  echo $pendingpercantage;
            }
              if ($row_lf['orderstatus'] == 'inpro') {
                $pordersinpro = $row_lf['total'];
                //  echo $porders;
              //  $pendingpercantage = $mysql->getInt(($porders / $totalorders) * 100);
                //  echo $pendingpercantage;
            }

            if ($row_lf['orderstatus'] == 'Available') {
                $aorders = $row_lf['total'];
                //  echo $porders;
                $avapercantage = $mysql->getInt(($aorders / $totalorders) * 100);
                //  echo $pendingpercantage;
            }

            if ($row_lf['orderstatus'] == 'Reject') {
                $rorders = $row_lf['total'];
                //  echo $porders;
                $rejpercantage = $mysql->getInt(($rorders / $totalorders) * 100);
                //  echo $pendingpercantage;
            }
        }
        $templus=$pordersnew+$pordersinpro;
         $pendingpercantage = $mysql->getInt(($templus / $totalorders) * 100);
    }
}


if ((CONFIG_PANEL == 'Dark/') || (CONFIG_PANEL == 'Light/')) {
    ?>

<?php
        $sqlnews='select a.news from '.NEWS_MASTER.' a where a.publish=1';
    $query_lf = $mysql->query($sqlnews);
    if ($mysql->rowCount($query_lf) > 0) {
        $rows_lf = $mysql->fetchArray($query_lf);
        $newss=$rows_lf[0]['news'];
    }
    if($newss!='')
    {
?>
<!-- Marquee Start/Stop by Way2Tutorial.com -->


<marquee  style="color: red;font-size: 15px" hspace="10" vspace="5" scrolldelay="110" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"><?php echo $newss;?></marquee>

    <?php }?>
	
	<div id="gsmUnion">

    <div class="col-md-4">
	<div class="card-box">
            <div class="bar-widget">
                <div class="table-box">
                    

                    <div class="table-detail">
                        <h4 class="m-t-0 m-b-5"><b><i class="fa fa-arrow-right"></i> <?php echo $member->getUserName(); ?></b></h4>
						<h4 class="m-t-0 m-b-5"><b><i class="fa fa-arrow-right"></i> <?php echo $member->getFullName(); ?></b></h4>
						<h4 class="m-t-0 m-b-5"><b><i class="fa fa-arrow-right"></i> <?php echo $member->getEmail(); ?></b></h4>
                    </div>

                </div>
            </div>
        </div>
        		
        
    </div>

    <div class="col-md-3">
            <div class="panel panel-color panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-credit-card"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_details')); ?></h3>
                </div>

                <!-- Table -->
                <table class="table gsmTable">
                    <tbody>
                        <tr>
                            <th scope="row" style="background:#ffffff"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_available')); ?></th>
                            <td class="text-success"><b><?php echo $credits; ?></b></td>

                        </tr>
                        <tr>
                            <th scope="row" style="background:#ffffff"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_used')); ?></th>
                            <td class="text-dark"><b><?php echo $creditsUsed; ?></b></td>

                        </tr>
                        <tr>
                            <th scope="row" style="background:#ffffff"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_in_process')); ?></th>
                            <td class="text-primary"><b><?php echo $creditsProcess; ?></b></td>

                        </tr>
                        <?php 
                       // echo $ovd_c_limit;
                        if ($ovd_c_limit!="")
                        {?>
                         <tr>
                            <th scope="row" style="background:#ffffff"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Over_Drive_Limit')); ?></th>
                            <td class="text-danger"><b><?php echo $ovd_c_limit; ?></b></td>

                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
    </div>


    <div class="col-md-5">
        <div class="card-box" style="padding-top:3px; padding-bottom:0px;">
<div class="row">		
			<div class="col-md-6">
				<p class="font-600 text-primary "><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_in_process')); ?><span class="text-dark pull-right"><?php echo $templus . '(' . $totalorders . ')'; ?></span></p>
			</div>
			<div class="col-md-6">    
				<div class="progress progress-striped m-b-30">
					<div class="progress-bar progress-bar-primary progress-animated wow animated animated" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pendingpercantage . '%'; ?>; visibility: visible; animation-name: animationProgress;">
					</div><!-- /.progress-bar .progress-bar-danger -->
				</div><!-- /.progress .no-rounded -->
			</div>
</div>
<div class="row">
			 <div class="col-md-6">   
				<p class="font-600 text-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Rejected')); ?> <span class="text-dark pull-right"><?php echo $rorders . '(' . $totalorders . ')'; ?></span></p>
			</div>
			<div class="col-md-6">   
				<div class="progress progress-striped m-b-30">
					<div class="progress-bar progress-bar-danger progress-animated wow animated animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rejpercantage . '%'; ?>; visibility: visible; animation-name: animationProgress;">
					</div><!-- /.progress-bar .progress-bar-pink -->
				</div><!-- /.progress .no-rounded -->
			</div>
</div>	
<div class="row">		
			<div class="col-md-6"> 
				<p class="font-600 text-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Completed')); ?><span class="text-dark pull-right"><?php echo $aorders . '(' . $totalorders . ')'; ?></span></p>
			</div>
			<div class="col-md-6">		
				<div class="progress progress-striped m-b-30">
					<div class="progress-bar progress-bar-success progress-animated wow animated animated" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $avapercantage . '%'; ?>; visibility: visible; animation-name: animationProgress;">
					</div><!-- /.progress-bar .progress-bar-info -->
				</div><!-- /.progress .no-rounded -->
			</div>
</div>
        </div>		
    </div>
    <div class="col-lg-12">

        <div class="portlet panel-info"><!-- /primary heading -->
            <div class="portlet-heading panel-heading">
                <h2 class="portlet-title text-white">
                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Last_20_IMEI_Orders')); ?>
                </h2>
                <div class="portlet-widgets">

                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                    <span class="divider"></span>
                    <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="portlet2" class="panel-collapse collapse in">
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered  table-fixed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Service')); ?></th>
                                    <th width="350"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Unlock_Code')); ?></th>
                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Order_Date')); ?></th>
                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Price')); ?></th>
                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Status')); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = 'select im.*, im.id as imeiID,
									DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
									DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
									tm.tool_name as tool_name, 
									tm.verification as tool_verify,
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
								order by im.id DESC
								limit 20';
                              //  echo $sql;
                                $imeiResult = $mysql->getResult($sql);
                                if ($imeiResult['COUNT']) {
                                    $i = 0;
                                    foreach ($imeiResult['RESULT'] as $row) {
                                        echo '<tr>';
                                        echo '<td class="" alt="im-' . $row['id'] . '">' . $row['id'] . '</td>';
                                        echo '<td>
										<b>' . $row['imei'] . '</b><br />
										
										<small class="text-muted">' . $row['tool_name'] . '</small>
									</td>';
                                            if($row['status']==2)
                                              {
                                                ?>
                            <td><small><?php 
                            
                                     $order_reply=base64_decode($row['reply']);
        
         if(strstr($order_reply,"stylesheet") || strstr($order_reply,"script src") ||strstr($order_reply,"img src"))
	 $order_reply= 'Page Not found';
                            
                            echo nl2br($order_reply); 
                            
                            
                            ?> </small></td>
                                <?php
                                              }
                                            else 
                                              {
                                                              ?>
                            <td><small id="unlockCode">---</small></td>
                                <?php 
                                                
                                              }
                                              
                                              if ($row['dtReplyDateTime'] != '0000-00-00 00:00:00' && $row['dtReplyDateTime']!=null) {
                        
                          $finaldate2 = $member->datecalculate($row['dtReplyDateTime']);
                       
                    }
                                              $finaldate1 = $member->datecalculate($row['dtDateTime'] );
                                        echo '<td><small>' . $finaldate1 . '<br /><b>' . $finaldate2. '</b></small></td>';
                                        echo '<td>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</td>';
                                        echo '<td class="">';
                                        switch ($row['status']) {
                                            case 0:
                                                echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')) . '</span>';
                                                break;
                                            case 1:
                                                echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_In_Process')) . '</span>';
                                                break;
                                            case 2:
                                                echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')) . '</span>';
                                                break;
                                            case 3:
                                                echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Rejected')) . '</span>';
                                                break;
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="TA_R">--</td>
                                    </tr>
                                    <?php
                                }
                                // Update Last viewed credtis
                                $sql = 'update ' . CREDIT_TRANSECTION_MASTER . ' set views=(views+1)
							where 
									user_id=' . $member->getUserId() . ' or user_id2=' . $member->getUserId() . '
									and views < 5';
                                $query = $mysql->query($sql);
                                ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
    <?php
} else {
    ?>

    <div class="row state-overview">
        <div class="col-sm-3 dashboard_stats">
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-lock"></i>
                </div>
                <div class="value">
                    <h1><?php echo $pendingIMEIS; ?></h1>
                    <p><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pending_imei_jobs')); ?></p>
                </div>
            </section>
        </div>



        <div class="col-sm-3 dashboard_stats">
            <section class="panel">
                <div class="symbol red">
                    <i class="fa fa-file"></i>
                </div>
                <div class="value">
                    <h1><?php echo $pendingFiles; ?></h1>
                    <p><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pending_file_service_jobs')); ?></p>
                </div>
            </section>
        </div>


        <div class="col-sm-6">
            <div class="panel" style="height:100px;">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4 text-center">Available<h2><?php echo $credits; ?></h2></div>
                        <div class="col-sm-4 text-center">Used<h2><?php echo $creditsUsed; ?></h2></div>
                        <div class="col-sm-4 text-center">In process<h2><?php echo $creditsProcess; ?></h2></div>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <div class="clear"></div>


    <div class="row">
        <div class="col-md-6">
            <?php
            $sql_total = 'select count(id) as total from ' . USER_MASTER;
            $query_total = $mysql->query($sql_total);
            $rows_total = $mysql->fetchArray($query_total);
            $totalUsers = $rows_total[0]['total'];
            ?>
            <div class="panel">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_latest_imei_orders')); ?></div>
                <div style="overflow-x:scroll; max-height:315px;">
                    <table class="table table-striped table-hover">
                        <?php
                        $sql = 'select im.*, im.id as imeiID,
									DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
									DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
									tm.tool_name as tool_name, 
									tm.verification as tool_verify,
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
								order by im.id DESC
								limit 25';
                        $imeiResult = $mysql->getResult($sql);
                        if ($imeiResult['COUNT']) {
                            $i = 0;
                            foreach ($imeiResult['RESULT'] as $row) {
                                echo '<tr>';
                                echo '<td class="text_center" alt="im-' . $row['id'] . '">' . ++$i . '</td>';
                                echo '<td>
										<b>' . $row['imei'] . '</b><br />
										<small class="text-danger">' . $row['ip'] . '</small><br />
										<small class="text-muted">' . $row['tool_name'] . '</small>
									</td>';
                                echo '<td><small>' . $row['dtDateTime'] . '<br /><b>' . $row['dtReplyDateTime'] . '</b></small></td>';
                                echo '<td>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</td>';
                                echo '<td class="text-right">';
                                switch ($row['status']) {
                                    case 0:
                                        echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')) . '</span>';
                                        break;
                                    case 1:
                                        echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')) . '</span>';
                                        break;
                                    case 2:
                                        echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_available')) . '</span>';
                                        break;
                                    case 3:
                                        echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable')) . '</span>';
                                        break;
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3" class="TA_R">--</td>
                            </tr>
                            <?php
                        }
                        // Update Last viewed credtis
                        $sql = 'update ' . CREDIT_TRANSECTION_MASTER . ' set views=(views+1)
							where 
									user_id=' . $member->getUserId() . ' or user_id2=' . $member->getUserId() . '
									and views < 5';
                        $query = $mysql->query($sql);
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading"><?php $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_history')); ?></div>

                <?php
                $sql_lf = 'select stu.username,stu.success, stu.ip, stu.date_time
							from ' . STATS_USER_LOGIN_MASTER . ' stu 
							left join ' . USER_MASTER . ' um on (um.username=stu.username)
							where  um.id=' . $member->getUserId() . ' order by stu.id DESC limit 10';
                $query_lf = $mysql->query($sql_lf);
                if ($mysql->rowCount($query_lf) > 0) {
                    ?>
                    <table class="table table-striped table-hover">
                        <tr>
                            <th class="TA_R" width="16"></th>
                            <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>
                            <th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_ip')); ?> </th>
                            <th class="TA_L"></th>
                        </tr>
                        <?php
                        $rows_lf = $mysql->fetchArray($query_lf);
                        $i = 1;
                        foreach ($rows_lf as $row_lf) {
                            ?>
                            <tr <?php echo (($row_lf['success'] == 0) ? 'style=color:red;' : ''); ?>>
                                <td class="TA_R"><?php echo $i++; ?></td>
                                <td><?php echo $mysql->prints($row_lf['date_time']); ?></td>
                                <td><?php echo $row_lf['ip']; ?></td>
                                <td><?php echo (($row_lf['success'] == 0) ? '<b>unsuccess</b>' : 'success'); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
	</div><!-- gsmUnion -->





<?php } ?>
