<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->GetInt('id');
$e_id = $request->GetInt('e_id');

$sql = 'select * from nxt_elistdetail where id=' . $mysql->getInt($id);

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "elist.html?reply=" . urlencode('reply_invalid_id'));

    exit();
}

$rows = $mysql->fetchArray($query);

$row = $rows[0];

?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN.'elist_desc.html?id='.$e_id; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_Template(s)_List')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_Email_Template')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>elist_desc_edit_process.do" method="post">
    <div class="row">
        <div class="col-md-12">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New')); ?>
            </h4>
            <input type="hidden" name="emailid" value="<?php echo $id;?>"/>
              <input type="hidden" name="emailid2" value="<?php echo $e_id;?>"/>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Subject')); ?></label>
                <input  name="subject" type="text" class="form-control" required="" id="subSject" value="<?php echo $row['subject'];?>" />

            </div>
             <div class="form-group col-lg-12">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mail_Body')); ?></label>

                        <div class="clearfix"></div>

                        <textarea id="editor1" name="editor1" class="ckeditor" required="" ><?php echo $row['body'];?></textarea>

                        <div class="clearfix"></div>

                    </div>
                   <div class="form-group col-lg-12">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Status')); ?></label>

                

                <label class="c-input c-radio"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-success"></span> <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </span></label>

                

                <label class="c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-primary"></span> <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </span></label>

            </div>

                    <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN.'elist_desc.html?id='.$e_id;?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>
<hr>

<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
                        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
                        document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
</script>