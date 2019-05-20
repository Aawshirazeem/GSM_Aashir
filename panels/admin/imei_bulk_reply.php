<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");


$msg = '';
switch ($reply) {
    case 'reply_no_imei':
        $msg = 'Please Provide IMEI(s)';
        break;
    case 'reply_imei_issue':
        $msg = 'IMEI CAN ONLY BE NUMERIC';
        break;
    case 'reply_check_input_file':
        $msg = 'FILE FORMAT IS NOT CORRECT or  INPUT DATA IN NOT ON RIGHT FORMAT';
        break;
    case 'reply_success':
        $msg = 'ALL OK';
        break;
}

include '_message.php';
?>


<div class="row" id="real_form">
    <div class="col-md-12">
        <div class="">
            <h4 class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI BULK REPLY'); ?></b>            

            </h4>
            <div class="panel-body">
                <form enctype="multipart/form-data" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_bulk_reply_mid.html" method="post" class="form-horizontal tasi-form">

                    <div class="col-md-6">

                        <div class="form-group">
                            <label>
                                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_service')); ?>
                                <span style="display:none" class="text-danger" id="loadIndTool"><i class="fa fa-spinner fa-pulse"></i></span>
                            </label>
                        <!--                        <select name="tool" class="form-control chosen-select" id="tool" placeholder="Select">-->
                            <select name="tool" id="tool" class="form-control">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_tool')); ?></option>
                                <?php
                                $sql = 'select
													tm.id as tid, tm.tool_name, tm.delivery_time,
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
                            </select><br>
                            <label class="file"><input class="form-control" type="file" name="fileToUpload" id="fileToUpload"><span class="file-custom"></span> </label><br>
                            
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'OR'); ?></label>
                            <i class="fa fa-question-circle" onclick="popUp('help.html')" id="help"></i>
                          <br>
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_bulk_reply')); ?></label><br/>
                            <textarea name="imei_pool" class="form-control" id="imei_pool" rows="5" ><?php echo $textdata; ?></textarea>

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_separated_by')); ?></label>
                            <input name="rsby" type="text" class="form-control" id="rsby"/>
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rejected_words')); ?></label>
                            <input name="rjctwords" type="text" class="form-control" id="rjctwords"/>
                            <br>
                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_submit')); ?>" class="btn btn-success"/>

                        </div>

                    </div>
                </form>
                <div class="col-md-6" style="text-align: center">
                    <b ><?php echo $admin->wordTrans($admin->getUserLang(),'Note'); ?></b><br>

                   <?php echo $admin->wordTrans($admin->getUserLang(),' This page allows you to reply bulk imei in single file or in bulk number
                    Bulk number seperated by new line and reply code seperated by ',' , '-' etc. default seperator is space ex.(111111111111119 999999999999)
                    specify rejected word for reply reject order with imei number'); ?>
                    <br>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function popUp(url) {
            w = window.open(url, '_blank', 'width=800,height=800,menubar=no');
        }
    </script>