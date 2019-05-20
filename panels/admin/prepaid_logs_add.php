<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('con_pre_log_148873737312');
	$group_id = $request->getInt('group_id');
?>
<style>
input::-moz-placeholder {
  font-size:10px;
  color:#ccc;
}


</style>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log')); ?></a></li>
             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_prepaid_log')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_add_process.do" method="post">
	<div class="row">
		<div class="col-md-12">
			<div class="">
				<h4 class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_prepaid_log')); ?></h4>
				<div class="panel-body">
					
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log_name')); ?> </label>
						<input name="prepaid_log_name" type="text" class="form-control" id="prepaid_log_name" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log_group')); ?> </label>
						<select name="group_id" id="group_id" class="form-control">
						<?php
							$sql= "select * from " . PREPAID_LOG_GROUP_MASTER;
							$query = $mysql->query($sql);
							$strReturn = "";
							if($mysql->rowCount($query) > 0)
							{
								$rows = $mysql->fetchArray($query);
								foreach($rows as $row)
								{
									echo '<option ' . (($group_id==$row['id']) ? 'selected="selected"' : '') . ' value="' . $row['id'] . '">' . $row['group_name'] . '</option>';
								}
							}
						?>
						</select>
					</div>
					<!-- Credit -->
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>
						<div class="row">
							<?php
								$sql_curr = 'select
													cm.id, cm.currency, cm.prefix, cm.is_default
												from ' . CURRENCY_MASTER . ' cm
												where cm.status=1
												order by is_default DESC';
								$currencies = $mysql->getResult($sql_curr);
								foreach($currencies['RESULT'] as $currency){
							?>
							<div class="col-sm-2">
								<input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
								<div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info')?>">
									<label><?php echo $currency['currency']; ?> [<?php echo $currency['prefix']; ?>]</label>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
											<input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_selling_price')); ?>" value="" required />
										</div>
										<div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sale')); ?></div>
									</div>
									<div class="input-group">
										<span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
										<input onblur="calculaterate2(this);" name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_purchase_price')); ?>" value="" />
									</div>
									<div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase')); ?></div>
								</div>
							</div>
							<?php
								}
							?>
						</div>
					</div>
                                        <div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?></label>
								<input name="delivery_time" type="text" class="form-control" id="delivery_time" />
<!--								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
							</div>
					<!-- //Credit -->
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> <br></label>
                        
						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></span></label>
						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" /><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></span></label>
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?></label>
						<textarea class="form-control" name="info" rows="3"></textarea>
					</div>
					<div class="from-group">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_now')); ?>" class="btn btn-success btn-sm" />
					</div> 
				</div> <!-- / panel-body -->
<!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
		
</form>
<script type="text/javascript">
                                                                                        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
                                                                                        // document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
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
                                                                                                    $('#amount_' + val.id).attr('value', val.valuee);
                                                                                                    $('#amount_' + val.id).html(val.valuee);

                                                                                                    //document.getElementById("amount_" + val.id).html =val.valuee;
                                                                                                    //$('#amount_'+key)
                                                                                                    //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');
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


    //                                                                $.ajax({
    //                                                                    type: "POST",
    //                                                                    
    //                                                                    // url: '<?php echo $url2; ?>',
//                                                                url: config_path_site_admin + "service_imei_rate_calcuatios.do",
//                                                                data: "&curId=" + cur_id + "&curval=" + value,
//                                                                success: function (msg) {
//                                                                  //  alert(msg);
//                                                                    //  $('#uid'+a).css('background', 'yellow');
//                                                                    $('#chat_panel_data').html(msg);
//                                                                }
//                                                            });
                                                                                    }


</script>