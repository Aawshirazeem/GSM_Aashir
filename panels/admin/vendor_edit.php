<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('suppliers_edit_54964566hh2');

$id = $request->GetInt('id');
$sql = 'select * from ' . Vendor . ' a where a.id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "engineer.html?reply=" . urlencode('reply_invalid_login'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <section class="panel">
            <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_vendor_details')); ?></div>
            <div class="panel-body">
                <div class="tab-content">	
                    <div id="tabs-1" class="tab-pane active">
                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>vendor_edit_process.do" method="post">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_name')); ?></label>
                                            <input name="name" type="text"  class="form-control" id="name" value="<?php echo $mysql->prints($row['name']) ?>" />
                                            <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>
                                            <input name="email" type="text" class="form-control" id="email" value="<?php echo $mysql->prints($row['email']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_contact_person')); ?></label>
                                            <input name="c_person" type="text" class="form-control" id="c_person" value="<?php echo $mysql->prints($row['c_person']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone_number')); ?></label>
                                            <input name="c_number" type="text" class="form-control" id="c_number" value="<?php echo $mysql->prints($row['c_number']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>
                                            <input name="address" type="text" class="form-control" id="address" value="<?php echo $mysql->prints($row['address']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_notes')); ?></label>
                                            <input name="notes" type="text" class="form-control" id="notes" value="<?php echo $mysql->prints($row['notes']) ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> > <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> > <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>
                                        </div>


                                    </div> <!-- / panel-body -->
                                    <div class="panel-footer">
                                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success"/>
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>vendor.html" class="btn btn-danger">Cancel</a>

                                    </div> <!-- / panel-footer -->

                                </div> <!-- / col-lg-6 -->
                            </div> <!-- / row -->


                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>
