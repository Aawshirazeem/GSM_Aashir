<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('con_ser_log_add_1488732');
	$group_id = $request->getInt('group_id');
?>

<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php $lang->prints('lbl_services'); ?></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>
             <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_server_log')); ?></li>
        </ol>
    </div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_add_process.do" method="post">
	<div class="row">
		<div class="col-md-8">
			<div class="">
				<div class="bs-nav-tabs nav-tabs-warning m-b-20">
					<ul class="nav nav-tabs nav-animated-border-from-left">
						<li class="nav-item"><a href="#tabs-1" data-toggle="tab" class="nav-link active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?> </a></li>
						<li class="nav-item"><a href="#tabs-2" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_input_options')); ?> </a></li>
						<li class="nav-item"><a href="#tabs-3" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_help/information')); ?> </a></li>
					</ul>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<div id="tabs-1" class="tab-pane active">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_name')); ?> </label>
								<input name="server_log_name" type="text" class="form-control" id="server_log_name" />
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_group')); ?> </label>
								<select name="group_id" id="group_id" class="form-control">
								<?php
									$sql= "select * from " . SERVER_LOG_GROUP_MASTER;
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
									<div class="col-sm-4">
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
							<!-- //Credit -->
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?></label>
								<input name="delivery_time" type="text" class="form-control" id="delivery_time" />
<!--								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_verification')); ?></label>
								<label class="checkbox-inline c-input c-radio"><input type="radio" name="verification" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></span></label>
								<label class="checkbox-inline c-input c-radio"><input type="radio" name="verification" value="0" /><span class="c-indicator c-indicator-success"></span><span class="c-input-text"><?php $lang->prints('com_no'); ?></span></label>
<!--								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_if_no_user_canot_verify_the_code')); ?></p>-->
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></label>
								<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></span></label>
								<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" /><span class="c-indicator c-indicator-success"></span><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></span></label>
							</div>
                                                        <div class="form-group list-group-item">
                               <?php echo $admin->wordTrans($admin->getUserLang(),'Is Chimera'); ?>
                                
                             <div class="animated-switch pull-right"> <input  type="checkbox" name="chimera" value="1"  id="chimera" onchange="change();"  data-plugin="switchery" onchange="change();" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/><label for="chimera" class="label-danger"></label> </div>
                            
                                

                            </div>
                            <div class="form-group" id="user_id">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Chimera User ID'); ?><br></label>
                                
                            
                                 <input  type="text" name="user_id"   id="user_id" value="<?php echo $row['chimera_user_id']; ?>"  class="form-control"/>

                            </div>
                            <div class="form-group" id="api_key">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Chimera API KEY'); ?><br></label>
                                
                            
                                <input  type="text" name="api_key"   id="api_key" value="<?php echo $row['chimera_api_key']; ?>" class="form-control" />

                            </div>
						</div>
						<div id="tabs-2" class="tab-pane">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_name')); ?></label>
								<input name="custom_field_name" type="text" class="form-control" id="custom_field_name" />
								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_some_special_information_you_want_to_get_from_users')); ?></p>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_message')); ?></label>
								<input name="custom_field_message" type="text" class="form-control" id="custom_field_message" />
								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_addissional_information_you_want_to_show_like_this')); ?></p>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_value')); ?></label>
								<input name="custom_field_value" type="text" class="form-control" id="custom_field_value" />
								<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_what_to_display_in_the_textbox')); ?></p>
							</div>
						</div>

						
						
						
						<div id="tabs-3" class="tab-pane">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download_link')); ?> </label>
								<input name="download_link" type="text" class="form-control" id="download_link" />
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_faq')); ?> </label>
								<select name="faq_id" class="form-control">
									<option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_faq')); ?> </option>
									<?php
										$sql_faq = 'select * from ' . IMEI_FAQ_MASTER;
										$query_faq = $mysql->query($sql_faq);
										$rows_faq = $mysql->fetchArray($query_faq);
										foreach($rows_faq as $row_faq)
										{
											echo '<option value="' . $row_faq['id'] . '">' . $row_faq['question'] . '</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?> </label>
								<textarea class="form-control" name="info" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group m-t-20">
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
				<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_server_log')); ?>" class="btn btn-success btn-sm" />
			</div>
		</div>
	</div>

</form>
<script type="text/javascript">
    $( document ).ready(function() {
    $("#user_id").hide();
    $("#api_key").hide();
      
});
                                                                                        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
                                                                                        // document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
                                                                                  function change()
                                                       {
                                                         
                                                          // var check=$('#chimera').is(":checked");
                                                            // alert(check);
                                                           //$('#user_id').hide();
                                                          if(document.getElementById('chimera').checked) {
    $("#user_id").show();
    $("#api_key").show();
} else {
    $("#user_id").hide();
    $("#api_key").hide();
}  
                                                       }         
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