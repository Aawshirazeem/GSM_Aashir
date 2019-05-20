<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$sqlCount = 'select
				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where (status=0 or status=1)) as pendingIMEI,
				(select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' fsm where (status=0 or status=1)) as pendingFiles,
				(select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' slm where (status=0 or status=1)) as pendingServerLogs,							
				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where status=2) as availIMEI,
				(select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' fsm where status=2) as availFiles,
				(select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' slm where status=2) as availServerLogs,				
				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where status=3) as rejectedIMEI,
				(select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' fsm where status=3) as rejectedFiles,
				(select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' slm where status=3) as rejectedServerLogs				
				from ' . SUPPLIER_MASTER . ' am where id=' . $supplier->getUserId();
//im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
$supplier->checkLogin();
$supplier->reject();
//$queryCount = $mysql->query($sqlCount);
//$rowsCount = $mysql->fetchArray($queryCount);
//$rowCount = $rowsCount[0];
?>
<div class="row m-b-20">
	<div class="col-lg-12">
    	<div class="panel panel-color">
        	<div class="panel-heading bg-default-700 p-10 color-white">
            	<h3 class="panel-title m-0"><?php echo $admin->wordTrans($admin->getUserLang(),'Pending Order(S) List'); ?></h3>
            </div>
            <div class="panel-body">
			<?php
            if ($service_imei == "1") {
				$sqlCount = 'select count(id) as total from ' . ORDER_IMEI_MASTER . ' where status=0 and tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')';
				$queryCount = $mysql->query($sqlCount);
				$rowsCount = $mysql->fetchArray($queryCount);
				$total = $rowsCount[0]['total'];
				
				$sql = 'select tool_name,count(tool_id) as count,tool_id
						from ' . ORDER_IMEI_MASTER . ' im
						left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
						where im.status=0 and
						im.tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
						group by tool_id order by im.id DESC';
				$query = $mysql->query($sql);
				if ($mysql->rowCount($query) > 0) {
					if ($total > 0) {
			?>
            	<div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <?php
                        $rows = $mysql->fetchArray($query);
                        $i = 1;
                        foreach ($rows as $row) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html?<?php echo 'search_tool_id=' . $row['tool_id'] . '&type=pending' ?>"><?php echo $mysql->prints($row['tool_name']); ?></a>
                            </td>
                            <td><?php echo $row['count']; ?></td>
                            <td width="16"></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td class="TA_R"><?php echo $admin->wordTrans($admin->getUserLang(),'Total Pending'); ?>:</td>
                            <td class="TA_R"><h2><?php echo $total; ?></h2></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
			<?php
					} else {
						echo '<h2 class="p-10 text-center">'.$admin->wordTrans($admin->getUserLang(),'No Pending IMEI').'</h2>';
					}
				}else {
					echo '<h2 class="p-10 text-center">'.$admin->wordTrans($admin->getUserLang(),'No Pending IMEI').'</h2>';
				}
			}
			?>
          	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-color">
            <div class="panel-heading bg-primary-200 p-10 color-white">
                <h3 class="panel-title m-0"><?php echo $admin->wordTrans($admin->getUserLang(),'Work Statistics'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <tr>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'Pending'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'Locked'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'Available'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'Unavailable'); ?></th>
                        </tr>
                        <?php
    //$sql = 'select *, DATE_FORMAT(date_time, "%d %b, %Y") as date_time from ' . SUPPLIER_PAYMENT . ' where supplier_id=' . $supplier->getUserId() . ' order by id desc';
                        $sql = '
    select 
    (select count(id) as Pending 
    from ' . ORDER_IMEI_MASTER . ' 
    
     where status=0 and tool_id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=oim.supplier_id)) Pending,
    (select (count(a.id)) as locked from ' . ORDER_IMEI_MASTER . '  a
    
    inner join ' . IMEI_SUPPLIER_DETAILS . ' b on a.tool_id=b.tool
    
     where a.supplier_id=oim.supplier_id and a.`status`=1) locked
     ,
     
     (select (count(a.id)) as Available from ' . ORDER_IMEI_MASTER . '  a
    
    inner join ' . IMEI_SUPPLIER_DETAILS . ' b on a.tool_id=b.tool
    
     where a.supplier_id=oim.supplier_id and a.`status`=2) Available,
     
      
     (select (count(a.id)) as Unavailable from ' . ORDER_IMEI_MASTER . '  a
    
    inner join ' . IMEI_SUPPLIER_DETAILS . ' b on a.tool_id=b.tool
    
     where a.supplier_id=oim.supplier_id and a.`status`=3) Unavailable
     
     
     from ' . ORDER_IMEI_MASTER . '  oim where oim.supplier_id=' . $supplier->getUserId() . '
     
     group by oim.supplier_id
     
     ';
                        $query = $mysql->query($sql);
                        $i = 1;
                        if ($mysql->rowCount($query) > 0) {
                            $rows = $mysql->fetchArray($query);
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $mysql->prints($row['Pending']) . '</td>';
                                echo '<td>' . $mysql->prints($row['locked']) . '</td>';
                                echo '<td>' . $mysql->prints($row['Available']) . '</td>';
                                echo '<td>' . $mysql->prints($row['Unavailable']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel panel-color">
            <div class="panel-heading bg-primary-200 p-10 color-white">
                <h3 class="panel-title m-0"><?php echo $admin->wordTrans($admin->getUserLang(),'Earn Statistics'); ?></h3>
            </div>
            <div class="panel-body">
            	<div class="table-responsive">
                    <table class="table table-condensed">
                        <tr>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI Service'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'FILE Service'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'LOG Service'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'TOTAL PAID'); ?></th>
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),'REMAINING'); ?></th>
                        </tr>
                        <?php
    //$sql = 'select *, DATE_FORMAT(date_time, "%d %b, %Y") as date_time from ' . SUPPLIER_PAYMENT . ' where supplier_id=' . $supplier->getUserId() . ' order by id desc';
                        $sql = '
    select ok.totalIMEIs,ok.totalFiles,ok.totalLogs,ok.totalPayment,
    (ok.totalIMEIs+ok.totalFiles+ok.totalLogs-ok.totalPayment) as Bakaya from (
    select  ifnull((select round(sum(isd.credits_purchase),2) credits_purchase
    
    
     from ' . ORDER_IMEI_MASTER . '  oim inner join ' . IMEI_SUPPLIER_DETAILS . ' isd on isd.tool=oim.tool_id 
     
     and isd.supplier_id=oim.supplier_id where oim.supplier_id=sm.id and oim.status=2),0) as totalIMEIs, 
     
     ifnull((select round(sum(fsd.credits_purchase),2) credits_purchase 
     
     from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
     inner join ' . FILE_SUPPLIER_DETAILS . ' fsd on fsd.service_id=oim.file_service_id 
     where oim.supplier_id=sm.id and oim.status=1),0) as totalFiles, 
     ifnull((select round(sum(slsd.credits_purchase),2) credits_purchase from ' . ORDER_SERVER_LOG_MASTER . ' oim 
     inner join ' . SERVER_LOG_SUPPLIER_DETAILS . ' slsd on slsd.log_id=oim.server_log_id
      where oim.supplier_id=sm.id and oim.status=1),0) as totalLogs, ifnull(round((select sum(credits_paid)
    from ' . SUPPLIER_PAYMENT . ' sp where sp.supplier_id=sm.id and sp.supplier_id=3 ),2),0)as totalPayment
        
    from ' . SUPPLIER_MASTER . ' sm where sm.id =' . $supplier->getUserId() . '
    ) as ok
     ';
                        $query = $mysql->query($sql);
                        $i = 1;
                        if ($mysql->rowCount($query) > 0) {
                            $rows = $mysql->fetchArray($query);
                            $row = $rows[0];
                            echo '<tr>';
                            echo '<td>' . $mysql->prints($row['totalIMEIs']) . '</td>';
                            echo '<td>' . $mysql->prints($row['totalFiles']) . '</td>';
                            echo '<td>' . $mysql->prints($row['totalLogs']) . '</td>';
                            echo '<td style="color:green;">' . $mysql->prints($row['totalPayment']) . '</td>';
                            echo '<td style="color:red;">' . $mysql->prints($row['Bakaya']) . '</td>';
                            echo '</tr>';
                        } else {
                            echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
