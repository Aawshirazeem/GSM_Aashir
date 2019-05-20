<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('co_pass_change_9999923487');



    $id = $request->GetInt('id');

	$admin_id=$mysql->getInt($admin->getUserId());



	$sql ='select * from ' . ADMIN_MASTER . ' where id=' . $mysql->getInt($admin->getUserId());

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_option')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_password')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>password_change_process.do" method="post" class="frmValidate">

	<div class="row">

    	<div class="col-xs-8">

        	<h4 class="m-b-20">

            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_password')); ?>

            </h4>

            <input name="email" type="hidden" id="email" value="<?php echo $row['email'];?>" />

            <div class="form-group">

                <label for="username"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>

                <input name="username" type="text" class="form-control" id="username" value="<?php echo $row['username'];?>" readonly />

                <input name="admin_id" type="hidden" id="id" value="<?php echo $admin_id;?>" />

            </div>

            <div class="form-group">

                <label for="password_old"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_old_password')); ?></label>

                <input name="password_old" type="password" class="form-control" id="password_old" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_enter_you_existing_password')); ?>" />

            </div>

            <div class="form-group">

                <label for="password"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_new_passworder')); ?></label>

                <input name="password" type="password" class="form-control" id="password" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_enter_you_new_password')); ?>" />

            </div>

            <div class="form-group">

                <label for="password2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_re-enter_new_password')); ?></label>

                <input name="password2" type="password" class="form-control" id="password2" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reenter_and_confirm_you_new_password')); ?>" />

            </div>

            <div class="form-group">

            	<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_change_password')); ?>" />

            </div>

        </div>

    </div>

</form>