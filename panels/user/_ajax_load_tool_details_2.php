<?php
// Set flag that this is a parent file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();
$tool = $request->getInt('tool');

if ($tool != "") {
    $sql = 'select * from ' . IMEI_TOOL_MASTER . ' where id=' . $mysql->getInt($tool);
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) == 0) {
        ?>
        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>*</label>
            <input type="text" name="imei" id="imei" class="form-control" value="">
        <!--        <p class="help-block">The serial number of the mobile phone to unlock.</p>-->
        </div>

        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>(s)</label>
            <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
        <!--        <p class="help-block">The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).</p>-->
        </div>


        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Brand'); ?></label>
            <select name="brand" class="form-control" id="brand">
                <option value=""<?php echo $admin->wordTrans($admin->getUserLang(),'Select Brand'); ?></option>
            </select>
            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndBrand" />
        </div>
        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Model'); ?></label>
            <select name="model" class="form-control" id="model">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Model'); ?></option>
            </select>
        </div>
        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Country'); ?></label>
            <select name="country" class="form-control" id="country">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Country'); ?></option>
            </select>
            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndCountry" />
        </div>
        <div class="form-group hidden">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Network'); ?></label>
            <select name="network" class="form-control" id="network">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Network'); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'MEP'); ?> *</label>
            <select name="mep" class="form-control" id="mep">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select MEP'); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PIN '); ?>*</label>
            <input type="text" name="pin" class="form-control" id="pin" />
        </div>
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'KBH/KRH'); ?>*</label>
            <input type="text" name="pin" class="form-control" id="pin" maxlength="13" />
        </div>
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PRD'); ?>*</label>
            <input type="text" name="prd" class="form-control" id="prd" maxlength="13" value="PRD-XXXXX-XXX" />
        </div>
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Type'); ?>*</label>
            <input type="text" name="vtype" class="form-control" id="vtype" value="RM-" />
        </div>
        <div class="form-group hidden">
            <label></label>
            <input type="text" name="custom_value" class="form-control" id="custom_value" value="" />
        </div>
        <?php
    } else {
        $rows = $mysql->fetchArray($query);
        //             echo '<pre>';
        //           var_dump($rows);
        $row = $rows[0];


        $sql_cr = 'select
						tm.id as tid, tm.tool_name, tm.delivery_time,tm.custom_required,custom_range,
						itad.amount,
						isc.amount splCr,
                                                iscr.amount splCre,
						pim.amount as packageCr,
						igm.group_name,
						cm.prefix, cm.suffix
					from ' . IMEI_TOOL_MASTER . ' tm
					left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
					left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
					left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
					left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
					left join ' . IMEI_SPL_CREDITS_RESELLER . ' iscr on(iscr.user_id = ' . $member->getUserId() . ' and iscr.tool_id=tm.id)
                                        left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
					left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
					where tm.id=' . $tool;
         // echo $sql_cr;
         //exit;
        //		echo '<pre>';
        $resultCredits = $mysql->getResult($sql_cr);
        //              var_dump($resultCredits);
        $rowCr = $resultCredits['RESULT'][0];

        $prefix = $rowCr['prefix'];
        $suffix = $rowCr['suffix'];
        $amount = $mysql->getFloat($rowCr['amount']);
        $amountSpl = $rowCr['splCr'];
        $amountPackage = $mysql->getFloat($rowCr['packageCr']);
        $amountDisplay = $amountDisplayOld = $amount;

        $custom_required = $rowCr['custom_required'];
        $custom_required = ($custom_required == '' ? 0 : $custom_required);

        $custom_range = $rowCr['custom_range'];
        $minCustomRange = 0;
        $maxCustomRange = 0;

        $custom_range = preg_split('[-]', $custom_range);
        if (sizeof($custom_range) > 1) {
            //print_r($custom_range);
            $minCustomRange = $custom_range[0];
            $maxCustomRange = $custom_range[1];
        } else {
            $minCustomRange = 0;
            $maxCustomRange = $custom_range[0];
        }


        $isSpl = false;
        if ($rowCr["splCre"] == "") {
            if ($rowCr['packageCr']!='') {
                $isSpl = true;
                $amountDisplay = $amountPackage;
            }
            if ($amountSpl != '') {
                $isSpl = true;
                $amountSpl = $mysql->getFloat($amountSpl);
                $amountDisplay = $amountSpl;
            }
        } else {
            $isSpl = false;
            $amountDisplay = $mysql->getFloat($rowCr["splCre"]);
        }
        ?>
        <div>
            <?php
            $msgCr = 'Price: ' . (($isSpl == true) ? '<del>' . $objCredits->printCredits($amountDisplayOld, $prefix, $suffix) . '</del> <b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b>' : $objCredits->printCredits($amountDisplay, $prefix, $suffix));
            $msgCr .= (($rowCr['delivery_time'] != '') ? ' | <i class="icon-time"></i> ' . $rowCr['delivery_time'] : '');
            $graphics->messagebox($msgCr);
            ?>
        </div>
        <?php
        // cutsom field above html working start from here

        /*
         * 2 means custom above
         * 1 means  bulk
         * 0 means 1
         * 
         */
        $chkkk = $row['imei_type'];
        $temp_valid = $row['imei_field_length'];
        if ($row['imei_type'] == '2') {

            // custome field validations 
            //  $chkkk=$row['imei_type'];


            echo '
                                           
							<input type="text" name="imei" id="imei" class="hidden" value="" maxlength="' . $temp_valid . '">
						  <div class="form-group">
							<label>' . (($row['imei_field_name'] != '') ? $row['imei_field_name'] : 'IMEI(s)') . '</label>
							<textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
							<p class="help-block">' . (($row['imei_field_info'] != '') ? $row['imei_field_info'] : 'The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).') . '</p>
						  </div>';
        } else if ($row['imei_type'] == '3') {
            ?>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?></label>
                <div class="input-group m-bot15">
                    <input type="text" name="imei" id="imei" class="form-control" data-mask="999-999-999-999-999" value="" maxlength="14">
                    <input type="hidden" name="imeilastdigit" id="imeilastdigit" class="form-control imeicdtext">
                    <span id="lastdig" class="input-group-addon imeicd">-</span>
                    <?php
                    $langg = $member->getLang();

                    if ($langg == "sp") {
                        ?>
                        <input type="button" value="Añadir" id="addimi" class="form-control btn btn-info" onclick="addText(event);">

            <?php
            } else if ($langg == "fr") {
                ?> <input type="button" value="Ajouter" id="addimi" class="form-control btn btn-info" onclick="addText(event);">
                        <?php
                    } else if ($langg == "ro") {
                        ?> <input type="button" value="adăuga" id="addimi" class="form-control btn btn-info" onclick="addText(event);">
                        <?php
                    } else if ($langg == "cn") {
                        ?> <input type="button" value="加" id="addimi" class="form-control btn btn-info" onclick="addText(event);">
                        <?php
                    } else if ($langg == "se") {
                        ?> <input type="button" value="Lägg till" id="addimi" class="form-control btn btn-info" onclick="addText(event);">
                        <?php
                    } else {
                        ?>

                        <input type="button" value="add" id="addimi" class="form-control btn btn-info" onclick="addText(event);">
                    <?php } ?>
                </div>
            <!--            <p class="help-block">The serial number of the mobile phone to unlock.</p>-->
            </div>

            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>(s)</label>
                <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
            <!--            <p class="help-block">The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).</p>-->
            </div>
                    <?php
                } else {
                    ?>
            <div class="form-group <?php echo (($row['imei_type'] != '1') ? ' hidden' : ''); ?>">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?></label>
                <div class="input-group m-bot15">
                    <input type="text" name="imei" id="imei" class="form-control" data-mask="999-999-999-999-999" value="" maxlength="14">
                    <input type="hidden" name="imeilastdigit" id="imeilastdigit" class="form-control imeicdtext">
                    <span class="input-group-addon imeicd">-</span>
                </div>
            <!--            <p class="help-block">The serial number of the mobile phone to unlock.</p>-->
            </div>



            <div class="form-group <?php echo (($row['imei_type'] == '1') ? ' hidden' : ''); ?>">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>(s)</label>
                <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
            <!--            <p class="help-block">The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).</p>-->
            </div>
            <?php
            // end here then end here
        }
        ?>
        <div class="clear"></div>
        <div class="form-group <?php echo (($row['brand_id'] >= 0) ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Brand'); ?></label>
            <select name="brand" class="form-control" id="brand">
        <?php
        if ($row['brand_id'] == '0') {
            echo '<option value="0">Select Brand</option>';
        } else {
            $where_brand = ($row['brand_id'] > 0) ? ' where id=' . $mysql->getInt($row['brand_id']) : '';
            $sql_brand = 'select * from ' . IMEI_BRAND_MASTER . $where_brand . ' order by brand';
            $query_brand = $mysql->query($sql_brand);
            echo ($row['brand_id'] > 0) ? '' : '<option value="">Select Brand</option>';
            if ($mysql->rowCount($query_brand) > 0) {
                $rows_brand = $mysql->fetchArray($query_brand);
                foreach ($rows_brand as $row_brand) {
                    echo '<option value="' . $row_brand['id'] . '">' . $row_brand['brand'] . '</option>';
                }
            }
        }
        ?>
            </select>
            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndBrand" />
                <?php //echo "brand"?>
        </div>

        <div class="form-group <?php echo (($row['brand_id'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Model'); ?></label>
            <select name="model" class="form-control" id="model" required>
                <?php
                echo '<option value="">Select Model</option>';

                if ($row['brand_id'] > 0) {
                    // $sql_model = 'select * from ' . IMEI_MODEL_MASTER . ' where brand=' . $mysql->getInt($row['brand_id']) . ' order by model';
                    $sql_model = 'select ok.id,ok.model,c.model_id,d.brand  from(
select * from nxt_imei_model_master b
where b.brand=' . $mysql->getInt($row['brand_id']) . ') ok
 inner join nxt_imei_model_master_2 c on ok.id=c.model_id  
inner join nxt_imei_brand_master d
 on ok.brand=d.id 
order by ok.id';
                    $query_model = $mysql->query($sql_model);
                    if ($mysql->rowCount($query_model) > 0) {
                        $rows_model = $mysql->fetchArray($query_model);
                        foreach ($rows_model as $row_model) {
                            echo '<option value="' . $row_model['id'] . '">' . $row_model['brand'] . ' - ' . $row_model['model'] . '</option>';
                        }
                    }
                }
                ?>
            </select>
                <?php //echo "model" ?>
        </div>
        <div class="form-group <?php echo (($row['country_id'] >= 0) ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Country'); ?></label>
            <select name="country" class="form-control" id="country">
                <?php
                if ($row['country_id'] == '0') {
                    echo '<option value="0">Select Country</option>';
                } else {
                    $where_country = ($row['country_id'] > 0) ? ' where id=' . $mysql->getInt($row['country_id']) : '';
                    $sql_country = 'select * from ' . IMEI_COUNTRY_MASTER . $where_country . ' order by country';
                    $query_country = $mysql->query($sql_country);
                    echo ($row['country_id'] > 0) ? '' : '<option value="">Select Brand</option>';
                    if ($mysql->rowCount($query_country) > 0) {
                        $rows_country = $mysql->fetchArray($query_country);
                        foreach ($rows_country as $row_country) {
                            echo '<option value="' . $row_country['id'] . '">' . $row_country['country'] . '</option>';
                        }
                    }
                }
                ?>
            </select>
            <img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndCountry" />
                <?php //echo "country"?>
        </div>
        <div class="form-group <?php echo (($row['country_id'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Network'); ?></label>
            <select name="network" class="form-control" id="network">
                <?php
                echo '<option value="">Select Network</option>';
                $sql_network = 'select * from ' . IMEI_NETWORK_MASTER . ' where country=' . $mysql->getInt($row['country_id']) . ' order by network';
                $query_network = $mysql->query($sql_network);
                if ($row['country_id'] > 0) {
                    if ($mysql->rowCount($query_network) > 0) {
                        $rows_network = $mysql->fetchArray($query_network);
                        foreach ($rows_network as $row_network) {
                            echo '<option value="' . $row_network['id'] . '">' . $row_network['network'] . '</option>';
                        }
                    }
                }
                ?>
            </select>
                <?php //echo "Network" ?>
        </div>

        <div class="form-group <?php echo (($row['mep_group_id'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'MEP'); ?>*</label>
            <select name="mep" class="form-control" id="mep">
                <?php
                echo '<option value="">Select MEP</option>';
                $sql_mep = 'select * from ' . IMEI_MEP_MASTER . ' where mep_group_id=' . $mysql->getInt($row['mep_group_id']) . ' order by mep';
                $query_mep = $mysql->query($sql_mep);

                if ($mysql->rowCount($query_mep) > 0) {
                    $rows_mep = $mysql->fetchArray($query_mep);
                    foreach ($rows_mep as $row_mep) {
                        echo '<option value="' . $row_mep['id'] . '">' . $row_mep['mep'] . '</option>';
                    }
                }
                ?>

            </select>
        </div>
        <div class="form-group <?php echo (($row['field_pin'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PIN'); ?>*</label>
            <input type="text" name="pin" class="form-control" id="pin" />
        </div>
        <div class="form-group <?php echo (($row['field_kbh'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'KBH/KRH'); ?>*</label>
            <input type="text" name="pin" class="form-control" id="pin" maxlength="13" />
        </div>
        <div class="form-group <?php echo (($row['field_prd'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PRD'); ?>*</label>
            <input type="text" name="prd" class="form-control" id="prd" maxlength="13" value="PRD-XXXXX-XXX" />
        </div>
        <div class="form-group <?php echo (($row['field_type'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Type'); ?>*</label>
            <input type="text" name="vtype" class="form-control" id="vtype" value="RM-" />
        </div>
        <!-- custome field html!-->


        <?php if ($row['custom_field_name'] != '' && $custom_required != 0) { ?>



            <div class="form-group <?php echo (($row['custom_field_name'] == '') ? ' hidden' : ''); ?>">
                <label><?php echo $row['custom_field_name'] ?></label>
                <input type="text" name="custom_value" class="form-control" id="custom_value" value="<?php echo $row['custom_field_value']; ?>"
            <?= (($custom_required == 0 ? '' : 'required="required"')); ?>  pattern=".{<?= $minCustomRange; ?>,<?= $maxCustomRange; ?>}" required title=" <?= $minCustomRange; ?> to <?= $maxCustomRange; ?> characters"
                       />
                <p class="help-block"><?php echo $row['custom_field_message'] ?></p>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group <?php echo (($row['custom_field_name'] == '') ? ' hidden' : ''); ?>">
                <label><?php echo $row['custom_field_name'] ?></label>
                <input type="text" name="custom_value" class="form-control" id="custom_value" value="<?php echo $row['custom_field_value']; ?>"
                       pattern=".{0}|.{<?= $minCustomRange; ?>,<?= $maxCustomRange; ?>}" title=" <?= $minCustomRange; ?> to <?= $maxCustomRange; ?> characters"
                       />
                <p class="help-block"><?php echo $row['custom_field_message'] ?></p>
            </div>
                       <?php
                   }
               }
           }
           ?>
        <?php
         if ($row['is_custom'] == 1) {
            
            echo '<div id="c_fields">';
            // show some custom fields now
            $sql1 = 'select * from nxt_custom_fields a where a.s_type=1 and a.s_id='.$tool;
            $result = $mysql->getResult($sql1);
            $f_temp = 1;
            foreach ($result['RESULT'] as $row2) {

                if ($row2['f_type'] == 1) {
                    // text box
                    echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo ' <input type="text" name="custom_' . $f_temp . '"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="' . $row2['f_desc'] . '"/><br>';
                }
                if ($row2['f_type'] == 2) {

                    $ops = $row2['f_opt'];
                    $ops = explode(',', $ops);
                    // text box
                    echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<select ' . (($row2['f_req'] == 1) ? 'required' : '') . ' class="form-control"  name="custom_' . $f_temp . '">';
                    echo ' <option value="">' . $row2['f_desc'] . '</option>';
                    for ($a = 0; $a < count($ops); $a++) {
                        echo ' <option value="' . $ops[$a] . '">' . $ops[$a] . '</option>';
                    }

                    echo '</select><br>';
                    //echo  ' <input type="text" name="custom_1"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="'.$row2['f_desc'].'"/>';
                }
                
                if($row2['f_type'] == 3)
                {
                    echo '<br><label>' . $row2['f_name'] . '</label><br>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<label>' . $row2['f_desc'] . '</label>';
                    echo ' <input type="checkbox" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' name="custom_' . $f_temp . '"/><br>';
                }
                $f_temp++;
            }
            echo '</div>';
        }
        
        ?>
<script type="text/javascript">



//    
//    function sync()
//    {
//        var n1 = document.getElementById('temptb');
//        var n2 = document.getElementById('imeis');
//        if (n1.value.trim() != "" && n1.value.length ==<?php echo $temp_valid; ?>) {
//
//            n2.value += n1.value + "\n";
//            document.getElementById('temptb').value = "";
//        }
//        else
//        {
//            alert("Enter Value that must be equal to " + "<?php echo $temp_valid; ?>");
//        }
//
//
//    }
</script>