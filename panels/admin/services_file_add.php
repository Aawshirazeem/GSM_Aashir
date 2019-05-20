<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('con_services_file_add_148353412');
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_manager')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_file_service')); ?></li>
        </ol>
    </div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_add_process.do" method="post">
    <div class="row">
        <div class="col-md-8">
            <div class="">
                <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_file_service')); ?></h4>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_name')); ?> </label>
                        <input name="service_name" type="text" class="form-control" id="service_name" />
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
                            foreach ($currencies['RESULT'] as $currency) {
                                ?>
                                <div class="col-sm-4">
                                    <input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
                                    <div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info') ?>">
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
<!--                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_type')); ?></label>
                           
                            <label class="checkbox-inline c-input c-radio"><input type="radio" name="reply_type" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file')); ?></label>
                            <label class="checkbox-inline  c-input c-radio"><input type="radio" name="reply_type" value="0" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_data')); ?></label>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_when_you_select_you_will_upload_a_file_else_user_will_send_you_the_text')); ?></p>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_verification')); ?></label>
                            <label class="checkbox-inline c-input c-radio"><input type="radio" name="verification" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></label>
                            <label class="checkbox-inline c-input c-radio"><input type="radio" name="verification" value="0" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></label>
<!--                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_if_no_user_canot_send_verify_the_code')); ?></p>-->
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> <br></label>
                            <label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" checked="checked" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>
                            <label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>
                        </div>
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
                                foreach ($rows_faq as $row_faq) {
                                    echo '<option value="' . $row_faq['id'] . '">' . $row_faq['question'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?> </label>
                            <textarea class="form-control" name="info" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_external_api')); ?> </label>
                            <select name="api_id" class="form-control" id="api_id">
                                <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_api')); ?> </option>
                                <?php
                                $sql_api = 'select * from ' . API_MASTER . ' where is_visible=1 order by api_server';
                                $query_api = $mysql->query($sql_api);
                                $rows_api = $mysql->fetchArray($query_api);
                                foreach ($rows_api as $row_api) {
                                    echo '<option ' . (($row['api_id'] == $row_api['id']) ? 'selected="selected"' : '') . ' value="' . $row_api['id'] . '">' . $row_api['api_server'] . '</option>';
                                }
                                ?>
                            </select>
                            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="ApiIdWait" />
                        </div>
                        <p class="field">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service')); ?> </label>
                            <select name="api_service_id" class="form-control">
                                <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_service')); ?> </option>
                            </select>
                        </p>
                        <input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_file_service')); ?>" />
                    </div>
                </div>
            </div>
        </div>


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