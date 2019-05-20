<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('suppliers_add_549883whh2');
?>
<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_name_missing':
        $msg = 'Name Is Missing';
        break;
}
include("_message.php");
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>vendor_add_process.do" method="post">

    <div class="row">
        <div class="col-md-6 col-sm-offset-3">
            <div class="panel">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Vendor_details')); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_name')); ?></label>
                        <input name="name" type="text" class="form-control" id="name" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>
                        <input name="email" type="text" class="form-control" id="email" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_contact_person')); ?></label>
                        <input name="c_person" type="text" class="form-control" id="c_person" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone_number')); ?></label>
                        <input name="c_number" type="text" class="form-control" id="c_number" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>
                        <input name="address" type="text" class="form-control" id="address" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_notes')); ?></label>
                        <input name="notes" type="text" class="form-control" id="notes" value="1" />
                    </div>


                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></label>
                        <label class="checkbox-inline"><input type="radio" name="status" value="1" checked="checked"> <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>
                        <label class="checkbox-inline"><input type="radio" name="status" value="0"> <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add')); ?>" class="btn btn-success"/>
                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>vendor.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>

                </div> <!-- / panel-footer -->
            </div>
        </div>

    </div> <!-- / row -->

</form>
