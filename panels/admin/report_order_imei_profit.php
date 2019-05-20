<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$search_user_id = $request->GetInt('search_user_id');
$search_supplier_id = $request->GetInt('search_supplier_id');
$from_date = $request->getstr("from_date");
$to_date = $request->getstr("to_date");
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reports')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IMEI_PROFIT')); ?></li>
        </ol>
    </div>
</div>

<div class="row">
    <h4 class="panel-heading">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_SAME_DAY_PROFIT_IMEI')); ?>
        <div class="btn-group btn-group-sm  pull-right">
            <a href="report_order_imei_profit.html" class="btn btn-primary btn-sm tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Today')); ?></a>
            <a href="report_order_imei_profit_7.html" class="btn btn-default btn-sm tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_LAST_7_DAYS')); ?></a>
            <a href="report_order_imei_profit_30.html" class="btn btn-default btn-sm tab-current"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_LAST_30_DAYS')); ?></a>
        </div>
    </h4>
    
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
                    <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_imei.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),'From Date'); ?></label>
                                    <input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>
                                </div>
                                <div class="col-sm-6" data-date-format="dd-mm-yyyy">
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),'To Date'); ?></label>
                                    <input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>
                            <select name="search_user_id" class="form-control">
                                <option value="0"><?php $lang->prints('lbl_all_users'); ?> </option>
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
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier')); ?> </label>
                            <select name="search_supplier_id" class="form-control">
                                <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_suppliers')); ?> </option>
                                <?php
                                $sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
                                $query_usr = $mysql->query($sql_usr);
                                $rows_usr = $mysql->fetchArray($query_usr);
                                foreach ($rows_usr as $row_usr) {
                                    echo '<option ' . (($row_usr["id"] == $search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div> 
    <!-- Chart -->
    <div class="col-sm-12">
        <div id="report_order_imei_profit" class="graph" ></div><br>
    </div>
    <!-- End Chart -->
	
	<div class="table-responsive">

    <table class="MT5 table table-striped table-hover panel">
        <tr>
            <th width="16"></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_tool')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_No.Tr')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_profit')); ?></th>
        </tr>
        <?php
        $i = 0;
        $group_name = '';
        $qType = 'where  oim.`status`=2 and year(oim.date_time)=year(now()) ';

        if ($from_date != '' && $to_date != '') {
            $from_date_search = date('Ymd', strtotime($from_date));
            $to_date_search = date('Ymd', strtotime($to_date));
            $qType = ' where date(oim.date_time) between ' . $from_date_search . ' and ' . $to_date_search;
        }
        if ($search_user_id != 0) {
            $qType .= (($qType == '') ? ' where ' : ' and ') . '  oim.user_id = ' . $search_user_id;
        }
        if ($search_supplier_id != 0) {
            $qType .= (($qType == '') ? ' where ' : ' and ') . ' oim.supplier_id = ' . $search_supplier_id;
        }
        $sql = '
select * from(
select igm.group_name  ,tm.tool_name,ok.formul,ok.sumcredits,ko.profit,(ko.profit*ok.formul) profit2 
 from(
select count(a.id) formul,round(sum(a.credits),2) sumcredits, a.tool_id,a.user_id from ' . ORDER_IMEI_MASTER . ' a
where
cast(a.date_time as date)=cast(now() as date) and a.`status`=2
and a.credits!=0
 group by a.tool_id) ok
 inner join 
 (select v.tool_id, round((v.amount- v.amount_purchase),2) profit
  from ' . IMEI_TOOL_AMOUNT_DETAILS . ' v
-- where v.tool_id in (49,67) 
where v.currency_id=(select k.id from nxt_currency_master k where k.is_default=1)) ko
on ok.tool_id=ko.tool_id
inner join ' . IMEI_TOOL_MASTER . ' tm on tm.id=ok.tool_id
left join ' . IMEI_GROUP_MASTER . ' igm on tm.group_id=igm.id
)done
where done.profit2!=0
';
        
        
        
        $sql2='select igm.group_name,tm.tool_name,count(a.id) as formul, (
    CASE 
        WHEN sum(a.credits- a.b_rate) >=0 THEN sum(a.credits- a.b_rate)
    
        ELSE 0
    END) AS profit2
from ' . ORDER_IMEI_MASTER . ' a 

inner join ' . IMEI_TOOL_MASTER . ' tm on tm.id=a.tool_id
left join ' . IMEI_GROUP_MASTER . ' igm on tm.group_id=igm.id 


where cast(a.date_time as date)=cast(now() as date)
and a.`status`=2 and a.credits!=0 

group by a.tool_id

';
        
        $result = $mysql->getResult($sql2);
        if ($result['COUNT'] > 0) {
            $tempsum=0;
            foreach ($result['RESULT'] as $row) {
                $i++;
                if ($group_name != $row['group_name']) {
                    echo '<tr><th colspan="5">' . $row['group_name'] . '</th></tr>';
                    $group_name = $row['group_name'];
                }
                ?>
                <tr>
                    <td></td>
                    <td><?php echo $row['tool_name']; ?></td>
                    <td><?php echo $row['formul']; ?></td>
                    <td><b><?php echo $objCredits->printCredits($row['profit2'], $row['prefix'], $row['suffix']); ?></b></td>
                </tr>
                <?php
                $tempsum=$tempsum+ $row['profit2'];
            }
            ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><label><?php echo $admin->wordTrans($admin->getUserLang(),'Total Profit'); ?>: </label></td>
                    <td><?php echo $tempsum; ?></td>
                </tr>
                <?php
        } else {
            echo '<tr><td class="text-center" colspan="3">'. $admin->wordTrans($admin->getUserLang(),'No Record Found').'</td></tr>';
        }
        ?>
    </table>
	</div>
</div>

<script type="text/javascript">
	var _sfunc = function(){
		Morris.Donut({
			element: 'report_order_imei_profit',
			data: [
				<?php
					$str = '';
					foreach ($result['RESULT'] as $row) {
						$str .= '{label: \'' . $row['tool_name'] . '\', value: ' . $row['profit2'] . '},';
					}
					echo trim($str, ',');
				?>
			]
		});
	}

	$(window).load(function(){
		_sfunc();
	});
	
	if(_winLoad == true) _sfunc();
</script>