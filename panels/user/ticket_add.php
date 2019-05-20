<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_tick_add_98374428');

$trans_id = $request->GetInt('trans_id');
?>


<div class="col-lg-12">

        <div class="panel panel-color panel-inverse">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_ticket')); ?></h3>

            </div>
            <div class="panel-body">


<form action="<?php echo CONFIG_PATH_SITE_USER; ?>ticket_add_process.do" method="post">
    <input type="hidden" name="trans_id" value="<?php echo $trans_id; ?>">
          <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Subject')); ?></label>
                        <input type="text" name="subject" value="" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Details')); ?></label>
                        <textarea name="details" class="form-control" id="details" rows="8"></textarea>
                    </div>
                </div> <!-- / panel-body -->
                <div class="panel-footer">
                    <div class="btn-group">
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_ticket')); ?>" class="btn btn-success">
                    <a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html" style="float:left" class="btn btn-default" > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back_to_list')); ?></a>
</div>
                </div> <!-- / panel-footer --></div>
            </div> <!-- / panel -->
</form>

        