<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('user_edit_789971255d2');

	

	$sql = 'select * from ' . PACKAGE_MASTER;

	$query = $mysql->query($sql);

	$i = 1;

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_package')); ?></li>

        </ol>

    </div>

</div>

<div class="row">

	<div class="col-xs-8">

    	<h4 class="m-b-20">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_packages')); ?>

        	<div class="btn-group btn-group-sm pull-right">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>package_add.html" class="btn btn-danger btn-xs"><i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_package')); ?></a>

            </div>

        </h4>

        <table class="table table-hover table-striped">

        	<tr>

            	<th width="16"></th>

                <th width="16"></th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_package_name')); ?></th>

                <th></th>

                <th></th>

           	</tr>

            <?php

			if($mysql->rowCount($query) > 0){

				$rows = $mysql->fetchArray($query);

				foreach($rows as $row){

			?>

            <tr>

            	<td><?php echo $i++; ?></td>

                <td><?php echo $graphics->status($row['status']); ?></td>

                <td><?php echo $row['package_name']; ?></td>

                <td>

                	<div class="btn-group btn-group-sm pull-right">

                    	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_edit.html?package_id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')); ?></a>

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_users.html?package_id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_users')); ?></a>

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_delete.html?package_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_delete')); ?></a>

                        

                        

                	<a class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_spl_credit')); ?> </a>

                    <div class="dropdown-menu dropdown-menu-scale from-right">

                    	<a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_imei_credit.html?package_id=<?php echo $row['id']; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_imei_credits')); ?></a>

                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_file_credit.html?package_id=<?php echo $row['id']; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_file_credits')); ?></a>

                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_server_logs_credit.html?package_id=<?php echo $row['id']; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_server_log_credits')); ?></a>

                        <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_prepaid_logs_credit.html?package_id=<?php echo $row['id']; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_prepaid_log_credits')); ?></a>

                    </div>

                

                    </div>

                 </td>

                <?php

                //echo '<td><a href="' . CONFIG_PATH_SITE_ADMIN . 'package_users.html?package_id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $lang->get('com_users') . '</a></td>';

				?>

            </tr>

			<?php

                }

			}else{

				echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

			}

			?>

        </table>

    </div>

</div>