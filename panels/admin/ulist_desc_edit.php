<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->GetInt('id');
//$e_id = $request->GetInt('e_id');

$sql = 'select * from nxt_ulistdetail2 where id=' . $mysql->getInt($id);

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "ulist_desc.html?reply=" . urlencode('repl_invalid_id'));

    exit();
}

$rows = $mysql->fetchArray($query);

$row = $rows[0];
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Users_List')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Edit_User_Email')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ulist_desc_edit_process.do" method="post">
    <div class="row">
        <div class="col-md-12">
            <h4 class="m-b-20">
<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Edit')); ?>
            </h4>
            <input type="hidden" name="emailid" value="<?php echo $id; ?>"/>
<!--          
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_name')); ?></label>
                <input  name="name" type="text" class="form-control" required="" id="name" value="<?php echo $row['name']; ?>" />

            </div>-->
             <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_email')); ?></label>
                <input  name="mail" type="email" class="form-control" required="" id="mail" value="<?php echo $row['email']; ?>" />

            </div>
            <div class="form-group col-lg-12">

                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Status')); ?></label>



                <label class="c-input c-radio"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-success"></span> <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_active')); ?> </span></label>



                <label class="c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-primary"></span> <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_inactive')); ?> </span></label>

            </div>

            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>