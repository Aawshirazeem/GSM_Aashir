<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_file_submin_93939348');

if ($service_file == "0") {
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


/* Get package id for the user */
$package_id = 0;
$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $package_id = $rows[0]['package_id'];
}
$crM = $objCredits->getMemberCredits();
$prefix = $crM['prefix'];
$suffix = $crM['suffix'];
$rate = $crM['rate'];
?>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>file_submit_process.do" method="post" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_file_service')); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?>
                            <div class="hidden pull-right ML10" id="loadIndTool"><i class="fa fa-spinner fa-pulse"></i></div>
                        </label>
<!--                        <select name="file_service" class="form-control" id="file_service">-->
                             <select name="file_service" id="file_service"  class="selectpicker" data-live-search="true"  data-style="btn-white">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_file_service')); ?></option>
<?php
$sql = 'select
													fm.*,
													fsc.credits as splCr,
													pm.credits as packageCr
												from ' . FILE_SERVICE_MASTER . ' fm 
												left join ' . FILE_SPL_CREDITS . ' fsc on (fm.id = fsc.service_id and fsc.user_id = ' . $member->getUserId() . ')
												left join ' . PACKAGE_FILE_DETAILS . ' pm on(fm.id = pm.file_service_id and pm.package_id=' . $package_id . ')
											  where fm.status=1 and
												fm.id not in (
																select distinct(service_id) from ' . FILE_SERVICE_USERS . ' where service_id not in(
																		select distinct(service_id) from ' . FILE_SERVICE_USERS . ' where user_id = ' . $member->getUserId() . ')
															)';



$sql = 'select
													fsm.*,
													fsad.amount,
													fssc.amount splCr,
                                                                                                        fsscr.amount splres,
													pfm.amount as packageCr,
													cm.prefix, cm.suffix
												from ' . FILE_SERVICE_MASTER . ' fsm
												left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
												left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $member->getCurrencyID() . ')
												left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $member->getUserID() . ' and fssc.service_id=fsm.id)
												left join ' . FILE_SPL_CREDITS_RESELLER . ' fsscr on(fsscr.user_id = ' . $member->getUserID() . ' and fsscr.service_id=fsm.id)
												left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
												left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $member->getCurrencyID() . ' and pfm.service_id = fsm.id)
												order by fsm.service_name';
$query = $mysql->query($sql);
$rows = $mysql->fetchArray($query);
$tempGroupName = "";
$tempGroupID = 0;
foreach ($rows as $row) {
    $prefix = $row['prefix'];
    $suffix = $row['suffix'];
    $amount = $mysql->getFloat($row['amount']);
    $amountSpl = $mysql->getFloat($row['splCr']);
    $amountPackage = $mysql->getFloat($row['packageCr']);
    $amountDisplay = $amountDisplayOld = $amount;

    $isSpl = false;
    if ($row["splres"] == "") {
        if ($amountSpl > 0) {
            $isSpl = true;
            $amountDisplay = $amountSpl;
        }
        if ($amountPackage > 0) {
            $isSpl = true;
            $amountDisplay = $amountPackage;
        }
    } else {
        $isSpl = false;
        $amountDisplay = $mysql->getFloat($row["splres"]);
    }
    echo '<option ' . (($isSpl == true) ? 'style="color:#FF7000"' : '') . ' value="' . $row['id'] . '">' . $row['service_name'] . ' [' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '] : ' . $row['delivery_time'] . '</option>';
}
echo '</optgroup>';
?>
                        </select>
                    </div>
                    <div id="toolDetails" class="hidden">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_File')); ?>_1</label>
                                <input type="file" accept=".log,.bcl,.sha,.txt" name="file_service1" class="filestyle" data-buttonname="btn-white">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_File')); ?>_2</label>
                                <input type="file" accept=".log,.bcl,.sha,.txt" name="file_service2" class="filestyle" data-buttonname="btn-white">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_File')); ?>_3</label>
                                <input type="file" accept=".log,.bcl,.sha,.txt" name="file_service3" class="filestyle" data-buttonname="btn-white">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_File')); ?>_4</label>
                                <input type="file" accept=".log,.bcl,.sha,.txt" name="file_service4" class="filestyle" data-buttonname="btn-white">
                            </div>
                         
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
                                <input name="moible" id="moible" type="text" class="form-control" value="" />
                            </div>
                            
                             <?php if ($a_notes==1)
                    {
                        ?>
                            
                            <div class="col-sm-4">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_notes')); ?></label>
                                <input name="message" id="message" type="text" class="form-control" value="" />
                            </div>
                             <?php
                    }?>
                             <?php if ($u_notes==1)
                    {
                        ?>
                            <div class="col-sm-4">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_notes')); ?></label>
                                <input name="remarks" id="remarks" type="text" class="form-control" value="" />
                            </div>
                             <?php
                    }?>
                        </div>
                    </div>
                </div> <!-- / panel-body -->
                <div class="panel-footer">
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
                </div> <!-- / panel-footer -->
            </div> <!-- / panel -->
        </div> <!-- / col-lg-6 -->
    </div> <!-- / row -->

</form>
<?php
$url1=CONFIG_PATH_SITE_USER."_ajax_check_master_pin.do";
?>
<link rel="stylesheet" href="<?php echo CONFIG_PATH_ASSETS; ?>file-uploader/css/jquery.fileupload.css">
<script src="<?php echo CONFIG_PATH_ASSETS; ?>file-uploader/js/jquery.fileupload.js"></script>
<script type="text/javascript">
     // setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
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
            url:'<?php echo $url1;?>',
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