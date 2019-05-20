<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('services_gateway_edit_54434ghh2');

$id = $request->GetInt('id');

$sql = 'select * from ' . GATEWAY_MASTER . ' where id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_add.html?reply=" . urlencode('reply_success_setting_gateway'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
$fuc = $key = $user = '';
$id=$row['m_id'];
?>
<div class="row">
    <div class="col-lg-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_settings')); ?></li>
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>settings_gateway.html"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_manage_payment_gateways')); ?></a></li>
            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_settings')); ?></li>
        </ul>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>settings_gateway_edit_process.do" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_update_gateway_details')); ?></div>
                <hr>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_gateway')); ?></label>
                        <input name="gateway" type="text" readonly class="form-control" id="gateway" value='<?php echo strip_tags($row['gateway']); ?>' />
                        <input name="id" type="hidden" id="id" value="<?php echo $row['id'] ?>" />
                    </div>


                    <?php
                    if ($id == 6) {
                        //  echo sizeof(explode(':', $row['gateway_id']));

                        if (sizeof(explode(':', $row['gateway_id'])) == 5) {
                            list($fuc, $key, $user, $redsys_ter, $redsys_cur) = explode(':', $row['gateway_id']);
                        }
                        ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_user')); ?></label>
                            <input name="m_user" required="" type="text" class="form-control" id="" value="<?php echo $user ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_merchant_code')); ?></label>
                            <input name="m_code" required="" type="text" class="form-control" id="" value="<?php echo $fuc; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_merchant_password')); ?></label>
                            <input name="m_password" required="" type="text" class="form-control" id="" value="<?php echo $key; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_terminal')); ?></label>
                            <input name="m_terminal" required="" type="text" class="form-control" id="" value="<?php echo $redsys_ter; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_currency')); ?></label>
                            <select name="m_currency" class="form-control">
                                <option value="978" <?php echo ($redsys_cur == 978) ? 'selected ' : ""; ?>>Euro</option>
                                <option value="840" <?php echo ($redsys_cur == 840) ? 'selected ' : ""; ?> >U.S. Dollar</option>
                                <option value="826" <?php echo ($redsys_cur == 826) ? 'selected ' : ""; ?>>Pound</option>
                                <option value="392"<?php echo ($redsys_cur == 392) ? 'selected ' : ""; ?> >Yen</option>
                                <option value="032" <?php echo ($redsys_cur == "032") ? 'selected ' : ""; ?>>Southern Argentina</option>
                                <option value="124" <?php echo ($redsys_cur == 124) ? 'selected ' : ""; ?>>Canadian Dollar</option>
                                <option value="152" <?php echo ($redsys_cur == 152) ? 'selected ' : ""; ?>>Chilean Peso</option>
                                <option value="170" <?php echo ($redsys_cur == 170) ? 'selected ' : ""; ?>>Colombian Peso</option>
                                <option value="356" <?php echo ($redsys_cur == 356) ? 'selected ' : ""; ?>>India Rupee</option>
                                <option value="484" <?php echo ($redsys_cur == 484) ? 'selected ' : ""; ?>>New Mexican Peso</option>
                                <option value="604" <?php echo ($redsys_cur == 604) ? 'selected ' : ""; ?>>Soles</option>
                                <option value="756" <?php echo ($redsys_cur == 756) ? 'selected ' : ""; ?>>Swiss Franc</option>
                                <option value="986" <?php echo ($redsys_cur == 986) ? 'selected ' : ""; ?>>Brazilian Real</option>
                                <option value="937" <?php echo ($redsys_cur == 937) ? 'selected ' : ""; ?>>Bolivar</option>
                                <option value="949" <?php echo ($redsys_cur == 949) ? 'selected ' : ""; ?>>Turkish lira</option>
                            </select>
                        </div>
                        <?php
                    } else if ($id == 7) {
                        if (sizeof(explode(':', $row['gateway_id'])) == 2) {
                            list($api_key, $xpub) = explode(':', $row['gateway_id']);
                        }
                        ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_api_key')); ?></label>
                            <input name="api_key" required="" type="text" class="form-control" id="" value="<?php echo $api_key ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_x_pub_key')); ?></label>
                            <input name="x_key" required="" type="text" class="form-control" id="" value="<?php echo $xpub; ?>" />
                        </div>
    <?php
} 
else if ($id == 8) {
                        if (sizeof(explode(':', $row['gateway_id'])) == 3) {
                            list($user_name_paypal, $password_paypal,$sign_paypal) = explode(':', $row['gateway_id']);
                        }
                        ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_User')); ?></label>
                            <input name="user_name_paypal" required="" type="text" class="form-control" id="" value="<?php echo $user_name_paypal ?>" />
                        </div>
                      <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Password')); ?></label>
                            <input name="password_paypal" required="" type="password" class="form-control" id="" value="<?php echo $password_paypal ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Signature')); ?></label>
                            <input name="sign_paypal" required="" type="text" class="form-control" id="" value="<?php echo $sign_paypal; ?>" />
                        </div>
    <?php
}


else {
    ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_gateway_id')); ?></label>
                            <input name="gateway_id" type="text" class="form-control" id="gateway_id" value="<?php echo $row['gateway_id'] ?>" />
                        </div>


<?php } ?>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_charges')); ?></label>
                            <div class="input-group">
                                <input name="charges" type="text" class="form-control" id="charges" value="<?php echo $row['charges'] ?>" />
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_min')); ?></label>
                            <input name="min" type="text" class="form-control" id="min" value="<?php echo $row['min'] ?>" />
                        </div>
                        <div class="form-group col-sm-4">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_max')); ?></label>
                            <input name="max" type="text" class="form-control" id="max" value="<?php echo $row['max'] ?>" />
                        </div>

                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_details')); ?></label>
                        <textarea name="details" class="form-control"><?php echo $row['details']; ?></textarea>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="checkbox-inline"><input type="checkbox" name="status" value="0" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> /> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_active')); ?></label>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="checkbox-inline"><input type="checkbox" name="demo_mode" value="0" <?php echo (($row['demo_mode'] == '1') ? 'checked="checked"' : ''); ?> /> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_demo_mode')); ?></label>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>settings_gateway.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_cancel')); ?></a>
                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_update_gateway')); ?>" class="btn btn-success btn-sm"/>
                    </div> 

                </div> <!-- / panel-body -->
                <!-- / panel-footer -->
            </div> <!-- / panel -->
        </div> <!-- / col-lg-6 -->
    </div> <!-- / row -->


</form>
