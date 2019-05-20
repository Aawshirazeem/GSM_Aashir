<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ulist.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_list')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_user_list')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ulist_add_process.do" method="post">
    <div class="row">
        <div class="col-md-6">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New')); ?>
            </h4>
          
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Description')); ?></label>
                <input  name="desc" type="text" class="form-control" required="" id="api_server" value="" />

            </div>
                    <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ulist.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>