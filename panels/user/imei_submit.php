<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_imei_sub_292377338');

if ($service_imei == "0") {
    //echo '<h1>' . $data->language->get('msg_not_authorized') . '</h1>';
    echo "<h1>" . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_you_are_NOT_authorize_to_access_this_page_please_contact_with_admin')) . "</h1>";

    return;
}
// get user mater pin status
$masterpin = 0;
$sqll = 'select a.master_pin from nxt_user_master a

where a.id=' . $member->getUserId();
$query = $mysql->query($sqll);

if ($mysql->rowCount($query) != 0) {
    $row = $mysql->fetchArray($query);
    $masterpin = $row[0]['master_pin'];
}



$sql_2 = 'select a.value,a.field finfo from '.CONFIG_MASTER.' a
where a.field in ("USER_NOTES","ADMIN_NOTES")
order by a.id';
$query_2 = $mysql->query($sql_2);
$rows_2 = $mysql->fetchArray($query_2);

$a_notes=$rows_2[1]['value'];
$u_notes=$rows_2[0]['value'];

?>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>imei/jquery.imeichecksum.js"></script>  

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>
<style> 
#toolDetails  br { display: none; }
.dropdown-menu > li > a { line-height:0.5; padding: 6px 20px !important; }
.dropdown-menu li a.opt { padding: 6px 20px; }
.bs-searchbox .form-control { height: 25px; font-size: 12px; }
.dropdown-header { padding: 1px 20px; line-height: 1; color: #495c74; font-size: 16px; }
.bootstrap-select.btn-group .dropdown-menu.inner { max-height:180px !important; }
</style>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit_process.do" method="post" id="testAjax">
    <div class="row hidden" id="loadingPanel" >
        <div class="col-md-8 col-md-offset-2">

            <!-- Progress Bar -->
            <h3 class="text-center" id="h1Wait"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Please_wait_Your_Order_is_Processing')); ?></h3>

            <!-- / Progress Bar -->	


            <!-- Message after successfull submission -->
            <h1 class="text-center" id="h1Done"><i class="fa fa-check fa-large"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Done')); ?></h1>
            <div class="text-center" id="panelButtons">
                <a href="<?php echo CONFIG_PATH_SITE_USER ?>imei_submit.html" class="btn btn-primary"><i class="icon-ok-sign"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Submit_more')); ?></a>
                <a href="<?php echo CONFIG_PATH_SITE_USER ?>imei.html?type=pending" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Pending_IMEI_orders')); ?></a>
            </div>
            <!-- / Message after successfull submission -->


            <!-- Error Message -->
            <div class="alert alert-danger" id="h1Error"><i class="icon-remove icon-large"></i> <span id="h1ErrorText"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_There_is_some_unexpected_error!')); ?></span></div>
            <div class="text-center" id="panelButtonsCredits">
                <a href="<?php echo CONFIG_PATH_SITE_USER ?>imei_submit.html" class="btn btn-default"><i class="icon-refresh"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Try_again')); ?></a>
                <a href="<?php echo CONFIG_PATH_SITE_USER ?>credits_purchase.html" class="btn btn-primary"><i class="icon-back"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Request_Credits')); ?></a>
            </div>
            <!-- / Error Message -->



            <div class="text-muted" id="lblDone">
                <h2><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Done_IMEI(s)')); ?></h2>
            </div>
            <div class="text-warning" id="lblDuplicate">
                <h2><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Duplicate_IMEI(s)')); ?></h2>
            </div>
            <div class="text-danger" id="lblInvalid">
                <h2><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Invalid_IMEI(s)')); ?></h2>
            </div>
        </div>
    </div>

    <div class="row" id="real_form">
        <div class="col-md-6">


            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_imei(s)')); ?></div>
                <div class="panel-body">




                    <div class="form-group">
                        <label>
                            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?>
                            <span style="display:none" class="text-danger" id="loadIndTool"><i class="fa fa-spinner fa-pulse"></i></span>
                        </label>
<!--                        <select name="tool" class="form-control chosen-select" id="tool" placeholder="Select">-->
                        <select name="tool" id="tool"  class="selectpicker" data-live-search="true"  data-style="btn-white">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_tool')); ?></option>
                            <?php
                            $sql = 'select
													distinct(tm.tool_name),tm.id as tid, tm.delivery_time,
													itad.amount,
													isc.amount splCr,
                                                                                                        iscr.amount splCre,
													pim.amount as packageCr,
													igm.group_name,
													cm.prefix, cm.suffix
												from ' . IMEI_TOOL_MASTER . ' tm
                                                                                                inner join nxt_grp_det b on tm.id=b.ser
                                                                                                inner join ' . IMEI_GROUP_MASTER . ' igm on igm.id=b.grp
												
												left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
												left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
												left join ' . IMEI_SPL_CREDITS_RESELLER . ' iscr on(iscr.user_id = ' . $member->getUserId() . ' and iscr.tool_id=tm.id)
                                                                                                left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
												left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
												left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
												where tm.visible=1 or tm.id in (select a.tool_id from nxt_imei_tool_users a where a.user_id=' . $member->getUserId() . ')
												order by igm.sort_order, igm.group_name, tm.sort_order, tm.tool_name';


                            $mysql->query("SET SQL_BIG_SELECTS=1");
                            $query = $mysql->query($sql);

                            $rows = $mysql->fetchArray($query);
                            $tempGroupName = "";
                            $tempGroupID = 0;
                            foreach ($rows as $row) {
                                $prefix = $row['prefix'];
                                $suffix = $row['suffix'];

                                $amount = $mysql->getFloat($row['amount']);
                                $amountSpl = $row['splCr'];
                                $amountPackage = $row['packageCr'];
                                $amountDisplay = $amountDisplayOld = $amount;

                                $isSpl = false;
                                if ($row["splCre"] == "") {
                                    if ($amountPackage != '') {
                                        $isSpl = true;
                                        $amountDisplay = $mysql->getFloat($amountPackage);
                                    }
                                    if ($amountSpl != '') {
                                        $isSpl = true;
                                        $amountDisplay = $mysql->getFloat($amountSpl);
                                    }
                                } else {
                                    $isSpl = false;
                                    $amountDisplay = $mysql->getFloat($row["splCre"]);
                                }

                                if ($row['group_name'] != $tempGroupName) {
                                    echo ($tempGroupID == 0) ? '</optgroup>' : '';
                                    echo '<optgroup label="' . $row['group_name'] . '">';
                                    $tempGroupName = $row['group_name'];
                                    $tempGroupID++;
                                }

                                echo '<option ' . (($isSpl == true) ? 'style="color:red"' : '') . ' value="' . $row['tid'] . '">' . $row['tool_name'] . ' [' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '] : ' . $row['delivery_time'] . '</option>';
                            }
                            echo '</optgroup>';
                            ?>
                        </select>
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_choose_the_tool_that_best_suits_your_locked_moible_phone')); ?></p>
                    </div>




                    <div  id="comehere">



                    </div>
                    
                    <?php if ($u_notes==1)
                    {
                        ?>
                   
                    
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_Note')); ?></label>
                        <input name="remarks" id="remarks" type="text" class="form-control" value="" />
                    </div>  
                     <?php
                    }?>
                    
                      <?php if ($a_notes==1)
                    {
                        ?>
                   
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin_Note')); ?></label>
                        <input name="admin_note" id="admin_note" type="text" class="form-control" value="" />
                    </div>
                     <?php
                    }?>
                    
                    
                    <div id="game">
                        <?php
                        if ($masterpin == 1) {
                            ?>
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Enter Master Pin To Submit Order'); ?></label>
                            <input name="m_pin" id="m_pin" type="text" class="form-control" value="" required=""/><br>
                            <input type="button" class="btn btn-inverse" value="Authorize Pin" onclick="checkpin()">
                             <span style="display:none" class="text-danger" id="loadIndTool2"><i class="fa fa-spinner fa-pulse"></i></span>
                        <?php } else {
                            ?>

                            <button type="submit" class="btn btn-success"><i class="icon-ok-sign"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Submit_&_Continue')); ?></button>

                        <?php } ?>
                    </div>
                </div> <!-- / panel-body -->
            </div> <!-- / panel -->
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Description')); ?></div>
                <div class="panel-body">
                    <div id="toolDetails" class="row hidden">
                        <div class="form-group <?php echo (($row['imei_type'] == '0') ? ' hidden' : ''); ?>">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?>*</label>
                            <input type="text" name="imei" id="imei" class="form-control" value="" maxlength="15">
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_the_serial_number_of_the_mobile_phone_to_unlock')); ?>.</p>
                        </div>
                        <div class="form-group <?php echo (($row['imei_type'] == '1') ? ' hidden' : ''); ?>">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imeis')); ?></label>
                            <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_the_serial_number_of_the_mobile_phone_to_unlock2')); ?></p>
                        </div>

                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand')); ?></label>
                            <select name="brand" class="form-control" id="brand">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_brand')); ?></option>
                            </select>
                            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndBrand" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_model')); ?></label>
                            <select name="model" class="form-control" id="model">
                                <option value=""><?php $lang->prints('lbl_select_model'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
                            <select name="country" class="form-control" id="country">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_country')); ?></option>
                            </select>
                            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndCountry" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_network')); ?></label>
                            <select name="network" class="form-control" id="network">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_network')); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mep')); ?> *</label>
                            <select name="mep" class="form-control" id="mep">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_mep')); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pin')); ?> *</label>
                            <input type="text" name="pin" class="form-control" id="pin" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_kbh_krh')); ?> *</label>
                            <input type="text" name="pin" class="form-control" id="pin" maxlength="13" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prd')); ?> *</label>
                            <input type="text" name="prd" class="form-control" id="prd" maxlength="13" value="PRD-XXXXX-XXX" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_type')); ?> *</label>
                            <input type="text" name="vtype" class="form-control" id="vtype" value="RM-" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_value')); ?></label>
                            <input type="text" name="custom_value" class="form-control" id="custom_value" value="" />
                        </div>
                    </div>

                </div> <!-- / panel-body -->
            </div> <!-- / panel -->
        </div> <!-- / col-lg-6 -->
    </div> <!-- / row -->
    <!--    <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_other_Info')); ?></div>
                <div class="panel-body" id="comehere">
    
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>
                        <input name="email" id="email" type="text" class="form-control" value="<?php echo $member->getEmail(); ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
                        <input name="moible" id="moible" type="text" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remarks')); ?></label>
                        <input name="remarks" id="remarks" type="text" class="form-control" value="" />
                    </div>
    
                    <button type="submit" class="btn btn-success"><i class="icon-ok-sign"></i> Submit & Continue</button>
    
                </div>
            </div>
    
    
        </div>-->
</form>
<textarea name="imeisTemp" class="hidden" id="imeisTemp"></textarea>
<div id="temp" class="hidden"></div>
<script>
    function addText(event) {

        if (document.getElementById("imei").value != '') {
            document.getElementById("imeis").value += document.getElementById("imei").value + document.getElementById("imeilastdigit").value + '\n';
            document.getElementById("imei").value = '';
            document.getElementById("imeilastdigit").value = '';
            document.getElementById("lastdig").innerHTML = '-';
        }
        else
        {
            alert('Enter IMEI');
            document.getElementById("imei").focus();
        }
    }
</script>

<script>

    // var tTotalRequest = 0;
    // var tRequestPending = 0;


    // $('#loadingPanel').hide();
    //  $("#toolDetails").hide();
    $("#lblDone").hide();
    $("#lblDuplicate").hide();
    $("#lblInvalid").hide();
    //$("#h1Wait").hide();
    $("#panelButtons").hide();
    $("#panelButtonsCredits").hide();
    $("#h1Error").hide();
    $("#h1Done").hide();
    // $("#pBarSubmit").hide();

    setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
    // variable to hold request
    var request;
    var firstReqiest = true;
    var tTotalRequest = 0;

    // bind to the submit event of our form
    $("#testAjax").submit(function (event) {
        if ($("#tool").val() == '')
        {
            alert("Please select unlocking tool!");
            event.preventDefault();
            return;
        }

        // if both is visible then only chk imeis
        if ($("#model").is(':visible') && $('#model').val() == '')
        {
            alert("Please Select Model");
            event.preventDefault();
            return;
        }
        // alert($('#imei').val());
        if ($("#imei").is(':visible') && $("#imeis").is(':visible') && $('#imei').val() == '' && $('#imeis').val() == '')
        {
            alert("Please enter single imei number first!");
            event.preventDefault();
            return;
        }
//        else
//        {

        if ($("#imei").is(':visible') && $('#imei').val() == '' && $("#imeis").is(':hidden'))
        {
            alert("Please enter Singleee imei number!");
            event.preventDefault();
            return;
        }
        if ($("#imeis").is(':visible') && $('#imeis').val() == '' && $("#imei").is(':hidden'))
        {
            alert("Please enter Bulk imei numbers!");
            event.preventDefault();
            return;
        }
        // }
        if ($("#mep").is(':visible') && $('#mep').val() == '')
        {
            alert("Please select MEP!");
            event.preventDefault();
            return;
        }
        //Save Data to temp IMEI textarea
        if (firstReqiest == true)
        {
            $('#real_form').hide();
            // $('#loadingPanel').show();
            $("#loadingPanel").removeClass("hidden");
            //$('#imeis').val($('#imei').val() + $('#imei').val());
            $('#imeisTemp').val($('#imeis').val());
            tTotalRequest = $('#imeisTemp').val().split("\n");
            tTotalRequest = tTotalRequest.length;
            firstReqiest = false;
        }
        var arrRequest = $('#imeisTemp').val().split("\n");


        var i = 0;
        var requestIMEIS = pendingIEMIS = "";
        $.each(arrRequest, function (key, value) {
            if (value != '')
            {
                i++
                if (i <= 2000)
                {
                    requestIMEIS += value + "\n";
                }
                else
                {
                    pendingIEMIS += value + "\n";
                }
            }
        });

        $("#imeis").val(requestIMEIS);
        $('#imeisTemp').val(pendingIEMIS);
        var arrPending = $('#imeisTemp').val().split("\n");

        var tPending = arrPending.length;
        var tRequest = arrRequest.length;

        var per = ((tRequest - tPending) / tRequest) * 100;
        $("#pBarSubmit > .progress-bar").css("width", per + "%");
        $("#pBarSubmit").show();

        // abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);
        // let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");
        // serialize the data in the form
        var serializedData = $form.serialize();

        // let's disable the inputs for the duration of the ajax request
        //$inputs.prop("disabled", true);

        // fire off the request to /form.php
        request = $.ajax({
            url: "<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit_process.do",
            type: "post",
            data: serializedData
        });

        // callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR) {
            // log a message to the console
            console.log(response);
            $("#temp").append(response);
            try {
                var obj = jQuery.parseJSON(response);
                if (obj.result == 'reply_submit_success')
                {
                    if (obj.done != '')
                    {
                        $("#lblDone").show();
                        $("#lblDone").append(obj.done)
                    }
                    if (obj.duplicate != '')
                    {
                        $("#lblDuplicate").show();
                        $("#lblDuplicate").append(obj.duplicate)
                    }
                    if (obj.invalid != '')
                    {
                        $("#lblInvalid").show();
                        $("#lblInvalid").append(obj.invalid)
                    }
                }
                else
                {
                    switch (obj.result)
                    {
                        case 'reply_imei_miss':
                            $("#h1Error").show();
                            $("#h1ErrorText").text("Please enter some IMEIs!");
                            break;
                        case 'reply_insuff_credits':
                            $("#h1Error").show();
                            $("#panelButtonsCredits").show();
                            $("#h1ErrorText").text("You don't have sufficient credits to continue!");
                            break;
                    }
                    $("#h1Wait").hide();
                    $("#pBarSubmit").hide();
                    return false
                }
            } catch (err) {
                alert(err);
            }
            if ($('#imeisTemp').val() != '')
            {
                //$inputs.prop("disabled", false);
                $("#testAjax").submit();
            }
            else
            {
                $("#h1Done").show();
                $("#panelButtons").show();
                $("#h1Wait").hide();
                $("#pBarSubmit").hide();
            }
            return false
        });

        // callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown) {
            // log the error to the console
            alert("there is some error!");
            $("#temp").html("error" + errorThrown);
            return false;
        });

        // callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // reenable the inputs
            //$inputs.prop("disabled", false);
        });

        // prevent default posting of form
        event.preventDefault();
    });
    function checkpin()
    {
       
        var pin =document.getElementById("m_pin").value;
        // alert(pin);
        if(pin!="")
        {
        //alert(pin);
        $("#loadIndTool2").show();
        $.ajax({
            type: "GET",
            url: config_path_site_member + "_ajax_check_master_pin.do",
            data: "pin=" + pin,
                    error: function () {

                    },
            success: function (msg) {
                if(msg==1)
                {
                    $("#game").html('<button type="submit" class="btn btn-success"><i class="icon-ok-sign"></i>Submit_&_Continue</button>');
                }
                else
                {
                    alert('PIN IS INCORRECT');
                     $("#loadIndTool2").hide();
                    
                }

               

            }
        });

        }
        else
        {
            alert('enter master pin');
        }
    }
</script>
