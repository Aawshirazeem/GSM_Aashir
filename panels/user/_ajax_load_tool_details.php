<?php
// Set flag that this is a parent file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();
$tool = $request->getInt('tool');

if($tool!="")
{
$sql = 'select * from ' . IMEI_TOOL_MASTER . ' where id=' . $mysql->getInt($tool);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) == 0) {
    ?>
    <div class="form-group hidden">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>*</label>
        <input type="text" name="imei" id="imei" class="form-control" value="">
        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_The_serial_number_of_the_mobile_phone_to_unlock')); ?></p>
    </div>

    <div class="form-group hidden">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI(s)'); ?></label>
        <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).'); ?></p>
    </div>


    <div class="form-group hidden">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Brand'); ?></label>
        <select name="brand" class="form-control" id="brand">
            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Brand'); ?></option>
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
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'MEP *'); ?></label>
        <select name="mep" class="form-control" id="mep">
            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select MEP'); ?></option>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'PIN *'); ?></label>
        <input type="text" name="pin" class="form-control" id="pin" />
    </div>
    <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'KBH/KRH *'); ?></label>
        <input type="text" name="pin" class="form-control" id="pin" maxlength="13" />
    </div>
    <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'PRD *'); ?></label>
        <input type="text" name="prd" class="form-control" id="prd" maxlength="13" value="PRD-XXXXX-XXX" />
    </div>
    <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Type *'); ?></label>
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
						tm.id as tid, tm.tool_name, tm.delivery_time,tm.custom_required,custom_range,tm.info,
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
    //  echo $sql_cr;
    // exit;
    //		echo '<pre>';
    $resultCredits = $mysql->getResult($sql_cr);
    //              var_dump($resultCredits);
    $rowCr = $resultCredits['RESULT'][0];
   // var_dump($rowCr);exit;
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
        if ($amountPackage > 0) {
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
  <div class="<?php echo (($rowCr['info'] == '') ? ' hidden' : ''); ?>">
        <div class="ui-widget">
            <br />
            <div class="ui-state-highlight ui-corner-all" style="padding:5px 5px 5px 5px;"> 
    <?php echo nl2br($row['info']); ?>
            </div>
        </div>
    </div>
<?php
}}
?>