<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
?>

<div class="col-md-12">
    <div class="panel panel-color panel-success">
        <div class="panel-heading"><b># File format for bulk replay</b>            

        </div>
        <div class="panel-body">
            <div class="col-md-12">
              <?php echo $admin->wordTrans($admin->getUserLang(),'  # File format for bulk replay ,
                In the first column, enter your # IMEI and # Space and In the second column, enter the # Unlock Code Or Leave Black for reject order , '); ?>
            
            <img src="<?php echo CONFIG_PATH_SITE; ?>images/help_bulk_1.png" alt=""/>
            </div>

        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-color panel-success">
        <div class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(),'# Custom Separate'); ?></b>            

        </div>
        <div class="panel-body">
            <div class="col-md-12"><?php echo $admin->wordTrans($admin->getUserLang(),
                'In the first column, enter your # IMEI and # Custom Separate(, : ; \ / . etc...) and In the second column, enter the # Unlock Code Or Leave Black for reject order , '); ?>
            
            <img src="<?php echo CONFIG_PATH_SITE; ?>images/help_bulk_2.png" alt=""/>
            </div>

        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-color panel-success">
        <div class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(),'#Custom Define Rejected Word'); ?></b>            

        </div>
        <div class="panel-body">
            <div class="col-md-12">
               <?php echo $admin->wordTrans($admin->getUserLang(),' While line content found this word system will list as a rejected order
                In the first column, enter your # IMEI and # Space and In the second column, enter the # Custom Rejected word '); ?>
                <img src="<?php echo CONFIG_PATH_SITE; ?>images/help_bulk_3.png" alt=""/>
            </div>

        </div>
    </div>
</div>