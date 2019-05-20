<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('con_services_file_edit_166h3412');

$request = new request();
$mysql = new mysql();

$id = $request->GetInt('id');


$sql = 'select * from ' . FILE_SERVICE_MASTER . ' where id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_services_file'));
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
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_manager')); ?></a></li>
              <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_file_service')); ?></li>
        </ol>
    </div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_edit_process.do" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="bs-nav-tabs nav-tabs-warning m-b-20">
                    <ul class="nav nav-tabs nav-animated-border-from-left">
                        <li class="nav-item"><a href="#tabs-1" data-toggle="tab" class="nav-link active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-2" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></a></li>
                        <li class="nav-item"><a href="#tabs-3" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_more_information')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-4" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_settings')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-5" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Special_Credits')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-6" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visible_to_users')); ?> </a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="tabs-1" class="tab-pane active">
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_name')); ?> </label>
                                <input name="service_name" type="text" class="form-control" id="service_name" value="<?php echo $mysql->prints($row['service_name']); ?>" />
                                <input name="id" type="hidden" id="id" value="<?php echo $row['id']; ?>" />
                            </div>
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?></label>
                                <input name="delivery_time" type="text" class="form-control" id="delivery_time" value="<?php echo $mysql->prints($row['delivery_time']); ?>" />
<!--                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
                            </div>
                            
                            <div class="form-group text-center">
                                <div class="row">
                              
                                </div>
                                <div class="row">
                                      <div class="col-sm-3">
                                      
                                      
                                          <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_type')); ?></label><br>
                                <label class="c-input c-radio"><input type="radio" name="reply_type" value="1" <?php echo ($row['reply_type'] == '1') ? 'checked="checked"' : ''; ?> /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file')); ?></label>
                               <label class="c-input c-radio"><input type="radio" name="reply_type" value="0" <?php echo ($row['reply_type'] == '0') ? 'checked="checked"' : ''; ?>/><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_data')); ?></label>
                           </div>
                                    <!--                                    <div class="col-sm-4">
                                                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_type')); ?></label>
                                                                            <p>
                                                                            <div class="switch" data-on-label="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file')); ?>" data-off-label="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_data')); ?>">
                                                                                <input type="checkbox" <?php echo ($row['verification'] == '1') ? 'checked="checked"' : ''; ?> name="verification" value="1" />
                                                                            </div>
                                                                            </p>
                                                                        </div>-->







                                    <div class="col-sm-3">
                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_verification')); ?></label>
                                      
                                                                                
                                        <div class="animated-switch">
                                        
                                            <input  type="checkbox" name="verification" value="1"  id="switch-success" <?php echo ($row['verification'] == '1') ? 'checked="checked"' : ''; ?>/>
                                            <label for="switch-success" class="label-success adjchk"></label>
                                            

                                        </div>
                                       
                                    </div>
                                    <div class="col-sm-3">
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_notification')); ?><br></label>
                                  
                                    <div class="animated-switch">
                                  <input  type="checkbox" name="e_notify" value="1"  id="switch-success-2" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>

<label for="switch-success-2" class="label-success adjchk"></label>
<!--                                        <input type="checkbox" <?php echo ($row['auto_success'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" />-->
                                    </div>
                                    
                                </div>
                                    <div class="col-sm-3">
                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> <br></label>
                                        
                                        <div class="animated-switch" data-on-label="ON" data-off-label="OFF">
                                            <input  type="checkbox" name="status" value="1"  id="switch-success-3" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>
<label for="switch-success-3" class="label-success adjchk"></label>
                                        </div>
                                        
                                    </div>
                                </div>
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
															fsad.amount, fsad.amount_purchase
														from ' . CURRENCY_MASTER . ' cm
														left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on (fsad.currency_id = cm.id and fsad.service_id = ' . $id . ')
														where cm.status=1
														order by is_default DESC';
                                    $currencies = $mysql->getResult($sql_curr);
                                    foreach ($currencies['RESULT'] as $currency) {
                                        ?>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
                                            <div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info') ?>">
                                                <label><?php echo $currency['currency']; ?> [<?php echo $currency['prefix']; ?>]</label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                        <input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_selling_price')); ?>" value="<?php echo (($currency['amount'] != '0') ? $currency['amount'] : '') ?>"/>
                                                    </div> 
                                                    <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sale')); ?></div>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                    <input name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" onblur="calculaterate2(this);" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_purchase_price')); ?>" value="<?php echo $currency['amount_purchase']; ?>" />
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


                        </div>
                        <div id="tabs-3" class="tab-pane">
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download_link')); ?> </label>
                                <input name="download_link" type="text" class="form-control" id="download_link" value="<?php echo $mysql->prints($row['download_link']); ?>" />
                            </div>
                            <div class="form-group col-md-6">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_faq')); ?> </label>
                                <select name="faq_id" class="form-control">
                                    <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_faq')); ?> </option>
                                    <?php
                                    $sql_faq = 'select * from ' . IMEI_FAQ_MASTER;
                                    $query_faq = $mysql->query($sql_faq);
                                    $rows_faq = $mysql->fetchArray($query_faq);
                                    foreach ($rows_faq as $row_faq) {
                                        echo '<option ' . (($row_faq['id'] == $row['faq_id']) ? 'selected="selected"' : '') . ' value="' . $row_faq['id'] . '">' . $mysql->prints($row_faq['question']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?> </label>
                                <textarea class="form-control" name="info" rows="3"><?php echo $mysql->prints($row['info']); ?></textarea>
                            </div>
                        </div>
                        <div id="tabs-4" class="tab-pane">
                            <div class="form-group col-md-12">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_external_api')); ?> </label>
                                <input type="hidden" name="calltype" id="calltype" value="2">
                                <select name="api_id" class="form-control" id="api_id">
                                    <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_api')); ?> </option>
                                    <?php
                                    $sql_api = 'select * from ' . API_MASTER . ' where is_visible=1 and file_service=1 order by api_server';
                                    $query_api = $mysql->query($sql_api);
                                    $rows_api = $mysql->fetchArray($query_api);
                                    foreach ($rows_api as $row_api) {
                                        echo '<option ' . (($row['api_id'] == $row_api['id']) ? 'selected="selected"' : '') . ' value="' . $row_api['id'] . '">' . $mysql->prints($row_api['api_server']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="ApiIdWait" />
                            </div>
                            <div class="form-group" id="apiIdDetails">
                                <?php
                                $sql_api_service = 'select * from ' . API_DETAILS . ' where type=2 and api_id=' . $row['api_id'];
                                $query_api_service = $mysql->query($sql_api_service);
                                echo '<label>Service</label>
									<select name="api_service_id" class="form-control">';
                                echo '<option value="">--</option>';
                                if ($mysql->rowCount($query_api_service) > 0) {
                                    $rows_api_service = $mysql->fetchArray($query_api_service);
                                    foreach ($rows_api_service as $row_api_service) {
                                        echo '<option ' . (($row['api_service_id'] == $row_api_service['service_id']) ? 'selected="selected"' : '') . ' value="' . base64_encode($row_api_service['service_id']) . '">' . $row_api_service['service_name'] . '</option>';
                                    }
                                }
                                echo '</select>';
                                ?>
                            </div>
                        </div>
                        <div id="tabs-5" class="tab-pane">
                            <?php
                            $service_id=$request->getInt('id');
	
	$sql = 'select
				um.id, um.username, um.currency_id,
				fsad.amount,
				fssc.amount spl_amount,
				cm.currency, cm.prefix, cm.suffix
			from ' . USER_MASTER . ' um 
			left join ' . FILE_SPL_CREDITS . ' fssc on(um.id=fssc.user_id and fssc.service_id=' . $service_id. ')
			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)
			left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=' . $service_id. ' and um.currency_id = fsad.currency_id)
			where um.reseller_id = 0 
                        order by um.username ';	
	$result = $mysql->getResult($sql);
	$i = 1;
                            ?>
                            <input type="hidden" name="service_id" value="<?php echo $service_id;?>" >
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
								echo '<tr class="' . (($row['spl_amount'] != '') ? 'danger' : '') . '">';
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
										<label class="c-input c-checkbox"><input type="checkbox"  name="check_user[]" ' . ' value="' . $row['id'] .	'"/><span class="c-indicator c-indicator-danger"></span></label>
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
                         <div id="tabs-6" class="tab-pane">
                           <?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_file_users_185158&9jnf8');
	
	$service_id=$request->getInt('id');
	
	$sql = 'select um.id,fsu.service_id,
			um.username
			from ' . USER_MASTER . ' um 
			left join ' . FILE_SERVICE_USERS . ' fsu on(um.id=fsu.user_id and fsu.service_id=' . $service_id. ')
			left join ' . FILE_SERVICE_MASTER . ' tm on(fsu.service_id=tm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?>
                             <input type="hidden" name="service_id" value="<?php echo $service_id;?>" >
					<table class="table table-striped table-hover">
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
									echo '<tr class="' . (($row['service_id'] != '') ? 'success' : '') . '">';
									echo '<td>' . $i++ . '</td>';
									echo '<td>' . $row['username'] . '</td>';
									
									echo '<td class="text_right">
											<label class="c-input c-checkbox"><input type="checkbox"  name="check_user1[]" ' . (($row['service_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/><span class="c-indicator c-indicator-danger"></span></label>
											<input type="hidden" name="user_ids1[]" value="' . $row['id'] . '" />
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
                    </div>
                </div>
            </div>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success" />

        </div>
    </div>


</form>

<script src="<?php echo CONFIG_PATH_PANEL; ?>js/bootstrap-switch.js"></script>
<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/init_nxt_admin.js"></script>
<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
                                                        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
                                                        //    document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
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