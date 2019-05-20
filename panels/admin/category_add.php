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
    case 'reply_name_duplicate':
        $msg = 'Name Already Exist';
        break;
    case 'reply_invalid_lp':
        $msg = 'Invalid IP';
        break;
    case 'reply_admin_accept_wait':
        $msg = 'Contact To Admin For Your Account Activation!';
        break;
}
include("_message.php");
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>category_add_process.do" method="post">

    <div class="row">
        <div class="col-md-6 col-sm-offset-3">
            <div class="panel">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_categiry_details')); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_category_name')); ?></label>
                        <input name="name" type="text" class="form-control" id="name" value="" required />
                    </div>
                 
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_category_status')); ?></label>
                        <label class="checkbox-inline"><input type="radio" name="status" value="1" checked="checked"> <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>
                        <label class="checkbox-inline"><input type="radio" name="status" value="0"> <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>
                    </div>
                </div>
                <div class="panel-footer">
                      <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add')); ?>" class="btn btn-success"/>
                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>category.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>
                  
                </div> <!-- / panel-footer -->
            </div>
        </div>

    </div> <!-- / row -->

</form>
