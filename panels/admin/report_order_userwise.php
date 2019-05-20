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

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Reports')); ?></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_IMEI_Userwise')); ?></li>

        </ol>

    </div>

</div>
<div class="btn-group btn-group-sm extra">
    <div class="dropdown pull-left btn-group-sm"> 
    </div>


    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>combine_reports.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Top_IMEI_Services')); ?> </a>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_daywise.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Daywise')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_userwise.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Userwise')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_FILE_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Server_log_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Users_Orders')); ?> </a>


</div>
<link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" />

<hr>

<div class="row">
    <h4 class="m-b-20">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Top_Users_of_IMEI_Service')); ?> <?php echo date('Y'); ?>
        <!--div class="btn-group pull-right">

                <a href="report_order_summary_imei.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service')); ?></a>

                <a href="report_order_summary_file.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?></a>

                <a href="report_order_summary_server_log.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?> </a>

                <a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></a>

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
        <div id="hero-bar-userwise" class="graph" ></div><br>
    </div>
    <!-- End Chart -->
	<div class="table-responsive">
    <table class="MT5 table table-striped table-hover panel">
        <tr>
            <th width="">#</th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Available_orders')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_use')); ?></th>
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
		$sql = 'select 
					igm.group_name,itm.tool_name,count(oim.id) as count
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)
					left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
					' . $qType . '
					group by oim.tool_id
					order by count(oim.id) DESC
					limit 10';
		$sql = 'select a.id,a.username,concat(a.first_name," ",a.last_name) name ,
                                count(b.id) orders,
                                            ifnull(round(sum(b.credits),2),0) Csum 
                                    from ' . USER_MASTER . ' a
                                        left join 
                                        (select * from ' . ORDER_IMEI_MASTER . ' c
                                        where year(c.date_time)=year(now()) and c.status=2) b
                                    on a.id=b.user_id
                                        group by a.id
                                                    order by orders desc;';
		$result = $mysql->getResult($sql);
		
		if ($result['COUNT'] > 0) {
			foreach ($result['RESULT'] as $row) {
				$i++;
		?>
        <tr>
        	<td><?php echo $i; ?></td>
            <td><?php echo $row['name'] ?></td>
            <td><?php echo $row['orders'] ?></td>
            <td><b><?php echo $row['Csum'] ?></b></td>
       	</tr>
        <?php
			}
		} else {
			echo '<tr><td class="text-center" colspan="3">No Record Found</td></tr>';
		}
		?>
    </table>
	</div>	
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" rel="stylesheet" type="text/css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<script type="text/javascript">
	var _sfunc = function(){
		Morris.Donut({
			element: 'hero-bar-userwise',
			data: [
				<?php
					$str = '';
					foreach ($result['RESULT'] as $row) {
						$str .= '{label: \'' . $row['name'] . '\', value: ' . $row['orders'] . '},';
					}
					echo trim($str, ',');
				?>
			]
			//xkey: 'Y',
			//ykeys: ['X'],
			//labels: ['Orders'],
			//barRatio: 0.6,
			//xLabelAngle: 80,
			//hideHover: 'auto',
			//stacked:'true',
			//grid:'false',
			//gridTextColor:['#710909'],
			//barColors: ['#710909']
        });
	}
	$(window).load(function(e){
		_sfunc();
	});
	if(_winLoad == true) _sfunc();
    // online status update code
</script>