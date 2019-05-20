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
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Balance_Report')); ?></li>
        </ol>
    </div>
</div>

<div class="row">
    <h4 class="m-b-20">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Balance')); ?>
        <!--        <div class="btn-group pull-right">

                    <a href="report_order_imei_profit.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Today')); ?></a>

                    <a href="report_order_imei_profit_7.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_LAST_7_DAYS')); ?></a>

                    <a href="report_order_imei_profit_30.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_LAST_30_DAYS')); ?></a>

                </div-->
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
        <div id="report_balance_unpaid" class="graph" ></div><br>
        <!-- End Chart -->
        <?php
        $sql = 'select b.currency,b.prefix, round(sum(a.credits),2) bsum,
"User Balance" as Typeb
from ' . USER_MASTER . ' a
inner join ' . CURRENCY_MASTER . ' b on a.currency_id=b.id
-- inner join ' . INVOICE_MASTER . ' c on c.user_id=a.id
where b.is_default=1
-- group by a.currency_id;
union all 
select b.currency,b.prefix,round(sum(c.amount),2) bsum,"Unpaid Balance" as Typeb
from ' . INVOICE_MASTER . ' c
inner join ' . CURRENCY_MASTER . ' b on c.currency_id=b.id
where c.status=0 and c.paid_status=0 and b.is_default=1
-- group by c.currency_id

union all

select b.currency,b.prefix, round(sum(a.ovd_c_limit),2) bsum, "Overdrive Limit" as Typeb 
from nxt_user_master a inner join nxt_currency_master b on a.currency_id=b.id

;';     
        $result = $mysql->getResult($sql);
        ?>
    </div>
    <div class="col-sm-12">
	<div class="table-responsive">
        <table class="MT5 table table-striped table-hover panel">
            <tr>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Id')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Available_balance')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('Unpaid Balance')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_overdrive_limit')); ?></th>
            </tr>
            <?php
            $sql2 = 'select a.id,a.username,round(a.credits,2) credits,a.ovd_c_limit,round(ifnull(ok.unpainamount,0),2) unpainamount
from ' . USER_MASTER . ' a
left join (
select c.user_id, sum(c.amount) unpainamount from nxt_invoice_master c
inner join ' . CURRENCY_MASTER . ' b on c.currency_id=b.id
where c.status=0 and c.paid_status=0 and b.is_default=1
group by c.user_id
) ok
on ok.user_id=a.id
where a.credits!=0 and a.currency_id=(select id from ' . CURRENCY_MASTER . '  where is_default=1)';
            $result2 = $mysql->getResult($sql2);
            if ($result2['COUNT'] > 0) {
                $tempsum1 = 0;
                $tempsum2 = 0;
                $tempsum3 = 0;
                foreach ($result2['RESULT'] as $row2) {
                    ?>
                    <tr>
                        <td><?php echo $row2['id']; ?></td>
                        <td><?php echo $row2['username']; ?></td>
                        <td><?php echo $row2['credits']; ?></td>
                        <td><?php echo $row2['unpainamount']; ?></td>
                         <td><?php echo $row2['ovd_c_limit']; ?></td>
                    </tr>
                    <?php
                    $tempsum1 = $tempsum1 + $row2['credits'];
                    $tempsum2 = $tempsum2 + $row2['unpainamount'];
                    $tempsum3 = $tempsum3 + $row2['ovd_c_limit'];
                }
                ?>
                <tr>
                    <td><hr></td>
                    <td><hr></td>
                    <td><label><?php echo $admin->wordTrans($admin->getUserLang(),'Total Balance');?>: </label></td>
                    <td><label><?php echo $admin->wordTrans($admin->getUserLang(),'Total Unpaid'); ?>:</label></td>
                    <td><label><?php echo $admin->wordTrans($admin->getUserLang(),'Total Overdrive') ?>:</label></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?php echo $tempsum1; ?></td>
                    <td><?php echo $tempsum2; ?></td>
                     <td><?php echo $tempsum3; ?></td>
                </tr>
                <?php
            } else {
                echo '<tr><td class="text-center" colspan="3">No Record Found</td></tr>';
            }
            ?>
        </table>
    </div>
	</div>
</div>

<script type="text/javascript">
	var _sfunc = function(){
		Morris.Donut({
			element: 'report_balance_unpaid',
			data: [
				<?php
					$str = '';
					foreach($result['RESULT'] as $row){
						if($row['bsum'] == '')
							$row['bsum'] = 0.00;
						$str .= '{label: \'' . $row['Typeb'] . '\', value: ' . $row['bsum'] . '},';
					}
					echo trim($str, ',');
				?>
			]
		});
	}
	$(window).load(function(e){
		_sfunc();
	});
	if(_winLoad == true) _sfunc();
</script>