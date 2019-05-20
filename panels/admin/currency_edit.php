<?php

defined("_VALID_ACCESS") or die("Restricted Access");

//$validator->formSetAdmin('');



$id = $request->GetInt('id');





$sql = 'select * from ' . CURRENCY_MASTER . ' where id=' . $mysql->getInt($id);

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "currency.html?reply=" . urlencode('reply_invalid_id'));

    exit();

}

$rows = $mysql->fetchArray($query);

$row = $rows[0];

?>

<div class="row m-b-20">

    <div class="col-lg-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Currency')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_currency')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency_edit_process.do" method="post">

	<div class="row">

    	<div class="col-md-12">

        	<h4 class="m-b-20">

            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_currency')); ?>

            </h4>
			
			<div class="col-md-3">

            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency')); ?></label>

                <input name="currency" type="text" class="form-control" id="currency" value="<?php echo $mysql->prints($row['currency']) ?>" />

            </div>
			</div>
			
			<div class="col-md-3">	
            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prefix')); ?></label>

                <input name="prefix" type="text" class="form-control" id="prefix" value="<?php echo $mysql->prints($row['prefix']) ?>" />

                <input name="id" type="hidden" class="form-control" id="id" value="<?php echo $row['id'] ?>" />

            </div>

			</div>
			
			<div class="col-md-3">
			
            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_suffix')); ?></label>

                <input name="suffix" type="text" class="form-control" id="suffix" value="<?php echo $mysql->prints($row['suffix']) ?>" />

            </div>

			</div>
			
			<div class="col-md-3">
			
            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_rate')); ?></label>

                <input name="rate" type="text" class="form-control" id="rate" value="<?php echo $mysql->prints($row['rate']) ?>" />

                <input  name="old_rate" type="hidden" class="form-control" id="old_rate" value="<?php echo $mysql->prints($row['rate']) ?>" />



            </div>
			
			
			</div>


            <div class="form-group col-md-3">

                <label class="c-input c-checkbox">

                    <input name="is_default" type="checkbox" <?php echo ($row['is_default'] == 1 ? 'checked="checked"' : '') ?> /><span class="c-indicator c-indicator-default"></span> <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_set_default_currency')); ?> </span>

                    <input  name="old_is_default" type="hidden" class="form-control" id="old_is_default" value="<?php echo $row['is_default']; ?>" />



                </label>

            </div>

            <div class="form-group col-md-9">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Currency_Status')); ?></label>

                

                <label class="c-input c-radio"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-success"></span> <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </span></label>

                

                <label class="c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> ><span class="c-indicator c-indicator-primary"></span> <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </span></label>

            </div>

            <div class="form-group">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

                <input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_currency')); ?>" />

            </div>

        </div>

    </div>

</form>