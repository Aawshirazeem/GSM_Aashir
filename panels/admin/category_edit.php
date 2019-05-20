<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('suppliers_edit_54964566hh2');

$id = $request->GetInt('id');
$sql = 'select * from ' . Category . ' a where a.id=' . $mysql->getInt($id);
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
            <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_category_details')); ?></div>
            <div class="panel-body">
                <div class="tab-content">	
                    <div id="tabs-1" class="tab-pane active">
                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>category_edit_process.do" method="post">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_name')); ?></label>
                                            <input name="username" type="text"  class="form-control" id="username" value="<?php echo $mysql->prints($row['name']) ?>" />
                                            <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> > <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> > <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>
                                        </div>


                                    </div> <!-- / panel-body -->
                                    <div class="panel-footer">
                                         <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success"/>
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>category.html" class="btn btn-danger">Cancel</a>
                                       
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
