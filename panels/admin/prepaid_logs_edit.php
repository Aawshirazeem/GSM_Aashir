<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('con_pre_log_edit_14856467312');

$id = $request->GetInt('id');

$sql = 'select * from ' . PREPAID_LOG_MASTER . ' where id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_invalid_id'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
?>


<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log')); ?></a></li>
             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_setting')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_edit_process.do" method="post" class="formSkin noWidth">
    <div class="row">
        <div class="col-md-8">
            <section class="">
                <div class="bs-nav-tabs nav-tabs-warning m-b-20">
                    <ul class="nav nav-tabs nav-animated-border-from-left">
                        <li class="nav-item"><a href="#tabs-1" data-toggle="tab" class="nav-link active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-2" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></a></li>
                        <li class="nav-item"><a href="#tabs-3" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Special_Credits')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-4" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visibl_to_users')); ?> </a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="tabs-1" class="tab-pane active">
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log_name')); ?></label>
                                <input name="prepaid_log_name" type="text" class="form-control" id="prepaid_log_name" value="<?php echo $row['prepaid_log_name']; ?>" />
                                <input name="id" type="hidden" id="id" value="<?php echo $row['id']; ?>" />
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log_group')); ?></label>
                                <select name="group_id" id="group_id" class="form-control">
                                    <?php
                                    $sql_group = "select * from " . PREPAID_LOG_GROUP_MASTER;
                                    $query_group = $mysql->query($sql_group);
                                    $strReturn = "";
                                    if ($mysql->rowCount($query) > 0) {
                                        $rows_group = $mysql->fetchArray($query_group);
                                        foreach ($rows_group as $row_group) {
                                            echo '<option ' . (($row['group_id'] == $row_group['id']) ? 'selected="selected"' : '') . ' value="' . $row_group['id'] . '">' . $row_group['group_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div> <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?> </label>
                                <input name="delivery_time" type="text" class="form-control" id="delivery_time" value="<?php echo $mysql->prints($row['delivery_time']); ?>" />
<!--                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
                            </div>
                            <div class="form-group">
                               
                              
                                <div class="col-md-5"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?><div class="animated-switch pull-right"><input  type="checkbox" name="status" value="1"  id="switch-default-1" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>
                                  <label for="switch-default-1" class="label-success"></label> </div> </div><br>

                            </div>
                            <div class="form-group">                                  
                                   <div class="col-md-5"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_notification')); ?><div class="animated-switch pull-right"> <input  type="checkbox" name="e_notify" value="1"  id="switch-default-2" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>
                                 
                                   
                                   <label for="switch-default-2" class="label-success"></label> </div> </div><br>
                                  

<!--                                        <input type="checkbox" <?php echo ($row['auto_success'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" />-->
                                   
                                </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?></label>
                                <textarea class="form-control" name="info" rows="3"><?php echo $row['info']; ?></textarea>
                            </div>
                        </div>
                        <div id="tabs-2" class="tab-pane">
                            <!-- Credit -->
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>
                                <div class="row">
                                    <?php
                                    $sql_curr = 'select
															cm.id, cm.currency, cm.prefix, cm.is_default,
															plad.amount, plad.amount_purchase
														from ' . CURRENCY_MASTER . ' cm
														left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on (plad.currency_id = cm.id and plad.log_id = ' . $id . ')
														where cm.status=1
														order by is_default DESC';
                                    $currencies = $mysql->getResult($sql_curr);
                                    foreach ($currencies['RESULT'] as $currency) {
                                        ?>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
                                            <div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info') ?>">
                                                <label><?php echo $currency['currency']; ?> [<?php echo $currency['prefix']; ?>]</label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                        <input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_selling_price')); ?>" value="<?php echo (($currency['amount'] != '0') ? $currency['amount'] : '') ?>"/>
                                                    </div> 
                                                    <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Sale'); ?></div>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                    <input name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" onblur="calculaterate2(this);" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_purchase_price')); ?>" value="<?php echo $currency['amount_purchase']; ?>" />
                                                </div>
                                                <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Purchase'); ?></div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- //Credit -->
                        </div>
                         <div id="tabs-3" class="tab-pane">
                             <?php
	
	$log_id=$request->getInt('id');
	
	$sql = 'select
				um.id, um.username, um.currency_id,
				itad.amount,
				isc.amount spl_amount,
				cm.currency, cm.prefix, cm.suffix
			from ' . USER_MASTER . ' um 
			left join ' . PREPAID_LOG_SPL_CREDITS . ' isc on(um.id=isc.user_id and isc.log_id=' . $log_id. ')
			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)
			left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' itad on(itad.log_id=' . $log_id. ' and um.currency_id = itad.currency_id)
			where um.reseller_id = 0 
                        order by um.username ';	
	$result = $mysql->getResult($sql);
	$i = 1;
?>
                             <input type="hidden" name="id" value="<?php echo $log_id;?>" >
					<table class="table table-striped table-hover">
						<tr>
							  <th></th>
							  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="16"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remove')); ?></th>
						</tr>
					<?php
						if($result['COUNT'])
						{
							foreach($result['RESULT'] as $row)
							{
								echo '<tr class="' . (($row['spl_amount'] != '') ? 'success' : '') . '">';
								echo '<td>' . $i++ . '</td>';
								echo '<td><b>' . $row['username'] . '</b></td>';
								echo '<td>' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</td>';
								echo '<td>
										<div class="input-group">
											<span class="input-group-addon">' . $row['prefix'] . '</span>
											<input type="text" class="form-control ' . (($row['spl_amount'] != '') ? 'textbox_highlight'. (($row['spl_amount'] > $row['amount']) ? 'R noEffect' : '') : '') . '" style="width:80px" name="spl_' . $row['id'] . '" value="' . $row['spl_amount'] . '" />
										</div>
									  </td>';
								echo '<td class="text_right">
										<label class="c-input c-checkbox"><input type="checkbox"  name="check_user[]" ' . ' value="' . $row['id'] .	'"/><span class="c-indicator c-indicator-success"></span></label>
										<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />
										<input type="hidden" name="currency_id' . $row['id'] . '" value="' . $row['currency_id'] . '" />
									</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
						}
					?>
				</table>
                         </div>
                         <div id="tabs-4" class="tab-pane">
                             <?php

	$prepaid_log_id=$request->getInt('id');
	
	$sql = 'select um.id,plu.prepaid_log_id,
			um.username
			from ' . USER_MASTER . ' um 
			left join ' . PREPAID_LOG_USERS . ' plu on(um.id=plu.user_id and plu.prepaid_log_id=' . $prepaid_log_id. ')
			left join ' . PREPAID_LOG_MASTER . ' plm on(plu.prepaid_log_id=plm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>
                             <input type="hidden" name="prepaid_log_id" value="<?php echo $prepaid_log_id;?>" >
                             <table class="table table-hover table-striped">	
					<tr>
					  <th width="16"></th>
					  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>
					  <th width="16"></th>
				</tr>
			<?php
				if($mysql->rowCount($query) > 0)
				{
					$rows = $mysql->fetchArray($query);
					foreach($rows as $row)
					{
						echo '<tr class="' . (($row['prepaid_log_id'] != '') ? 'success' : '') . '">';
						echo '<td>' . $i++ . '</td>';
						echo '<td>' . $row['username'] . '</td>';
					
						echo '<td class="text_right">
								<label class="c-input c-checkbox"><input type="checkbox"  name="check_user1[]" ' . (($row['prepaid_log_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/><span class="c-indicator c-indicator-success"></span></label>
								<input type="hidden" name="user_ids1[]" value="' . $row['id'] . '" />
							</td>';
						echo '</tr>';
					}
				}
				else
				{
					echo '<tr><td colspan="6" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
				}
			?>
				</table>
                         </div>
                    </div>
                </div>
            </section>
            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />
            </div>
        </div>

</form>
<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/init_nxt_admin.js"></script>
<script type="text/javascript">
                                                        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

                                                        function calculaterate(e)
                                                        {
                                                            var id = e.id;
                                                            var cur_id = id.substring(7);
                                                            var value = $('#' + id).val();
                                                            //  alert($('#'+id).val());
                                                            // alert(cur_id); 
                                                            $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {
                                                                /* data will hold the php array as a javascript object */
                                                                $.each(data, function (key, val) {
                                                                    $('#amount_' + val.id).val(val.valuee);
                                                                    // $('#amount_' + val.id).attr('value', val.valuee);
                                                                    //$('#amount_' + val.id).html(val.valuee);
                                                                });
                                                            });
                                                        }

                                                        function calculaterate2(e)
                                                        {
                                                            var id = e.id;
                                                            var cur_id = id.substring(16);
                                                            var value = $('#' + id).val();
                                                            //  alert($('#'+id).val());
                                                            // alert(cur_id);

                                                            $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {
                                                                /* data will hold the php array as a javascript object */
                                                                $.each(data, function (key, val) {
                                                                    $('#amount_purchase_' + val.id).val(val.valuee);
                                                                    // $('#amount_' + val.id).attr('value', val.valuee);
                                                                    //$('#amount_' + val.id).html(val.valuee);

                                                                    //document.getElementById("amount_" + val.id).html =val.valuee;
                                                                    //$('#amount_'+key)
                                                                    //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');
                                                                });
                                                            });
                                                        }
</script>