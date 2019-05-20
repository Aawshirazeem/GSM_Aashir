<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('config_reseller_add_148148548');

?>

<div class="row m-b-20">

    <div class="col-lg-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Currency')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency_add')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency_add_process.do" method="post">

	<div class="row">

    	<div class="col-md-12">

        	<h4 class="m-b-20 col-md-12">

            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency_add')); ?>

            </h4>

            <div class="form-group col-md-3">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency')); ?></label>

                <input name="currency" type="text" class="form-control" id="currency" value="" required />

            </div>

            <div class="form-group col-md-3">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prefix')); ?></label>

                <input name="prefix" type="text" class="form-control" id="prefix" value="" />

            </div>

            <div class="form-group col-md-3">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_suffix')); ?></label>

                <input name="suffix" type="text" class="form-control" id="suffix" value="" />

            </div>

            <div class="form-group col-md-3">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rate')); ?></label>

                <input name="rate" type="text" class="form-control" id="rate" value="" />

            </div>

            <div class="form-group col-md-12">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>currency.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

                <input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency_add')); ?>" />

            </div>

        </div>

    </div>

</form>