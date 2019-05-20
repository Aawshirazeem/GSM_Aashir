<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('user_add_59905855d2');

	

	$package_id=$request->getInt('package_id');

	

	$sql='select * from ' . PACKAGE_MASTER . ' where id=' . $package_id;

	$query=$mysql->query($sql);

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_package')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_setting')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_edit_process.do" method="post">

	<input type="hidden" name="package_id" value="<?php echo $package_id ;?>">

    <?php

	if($mysql->rowCount($query)>0){

		$rows=$mysql->fetchArray($query);

		$row=$rows[0];

	?>

	<div class="row">

    	<div class="col-xs-8">

        	<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_package')); ?></h4>

            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_package_name')); ?></label>

                <input name="package_name" type="text" class="form-control" value="<?php echo $row['package_name']?>" />

            </div>

            <div class="form-group">

            	<label class="c-input c-checkbox">

                	<input type="checkbox" name="status" <?php echo ($row['status']==1 ? 'checked="checked"' : '')?>>

                    <span class="c-indicator c-indicator-default"></span>

                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </span>

                </label>

            </div>

            <div class="form-group">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>package.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>					

                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_package')); ?>" class="btn btn-success btn-sm" />

            </div>

        </div>

    </div>

    <?php

	}else{

		echo '<h2>'  . 'No Record' . '</h2>';

    }

	?>

</form>