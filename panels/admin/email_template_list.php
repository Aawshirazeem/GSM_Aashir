<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('email_template_edit_784549971255d2');

	

	$sql = 'select et.*,etc.category, etc.id as eid,etc.type as tid

					from ' . EMAIL_TEMPLATES . ' et 

					left join '. EMAIL_TEMPLATES_CATEGORY . ' etc on(et.category_id=etc.id)

					where etc.type=1 order by category';

	$query = $mysql->query($sql);

	$i = 1;

	

	

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS'); ?></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Email_Template')); ?></li>

        </ol>

    </div>

</div>



<form method="post" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_template_list_process.do">

	<section class="m-t-10">

		<h4 class="m-b-20">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_email_template_list')); ?>

			<input type="submit" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')))?>" class="btn btn-danger btn-sm pull-right">

		</h4>

	<div class="table-responsive">

		<table class="table table-striped table-hover">

		<tr>

		  <th width="16"></th>

		  <th width="16"></th>

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_subject')); ?></th>

		  <?php if (defined("debug")){ ?><th></th><?php } ?>

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_category')); ?></th>

                  

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_by')); ?></th>

		  <th></th>

		</tr>

		<?php

			if($mysql->rowCount($query) > 0)

			{

				$rows = $mysql->fetchArray($query);

				

				foreach($rows as $row)

				{

					

					echo '<tr>';

					echo '<td>' . $i++ . '</td>';

		?>	

					<td>

                  

                    

					 <label class="c-input c-checkbox"><input type="checkbox" name="ids[]" value="<?php echo $row['id'];?>" <?php echo (($row['status']==1) ? 'checked="checked"' : '');?> ><span class="c-indicator c-indicator-success"></span></td>

		<?php			echo '<td>' . $row['subject'] . '</td>';

					echo (defined("debug")) ?  ('<td>' . $row['eid'] . ':' . $row['code'] . '</td>') : '';

					echo '<td>' . $row['category'] . '</td>';

                                       // echo '<td>' . $row['code'] . '</td>';

			?>

					<td>

						<?php 

						switch ($row['email_by_id']) {

						 	case 1:

						 			echo CONFIG_EMAIL_ADMIN;

						 		break;

						 	case 2:

									echo CONFIG_EMAIL;

								break;

							case 3:

									echo CONFIG_EMAIL_CONTACT;

						 		break;

						 } ?>

					</td>



			<?php

					echo '<td width="80">

							<a href="' . CONFIG_PATH_SITE_ADMIN . 'email_template_edit.html?id=' . $row['id'] . '&cid='.$row['eid'].'" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

						</td>';

			?>

			<?php

					echo '</tr>';

				}

			}

			else

			{

				echo $graphics->messagebox_warning($admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));

			}

		?>

		

	</table>
	</div>
	

</section>

<?php 

	$sql1 = 'select et.*,etc.category, etc.id as eid,etc.type as tid

					from ' . EMAIL_TEMPLATES . ' et 

					left join '. EMAIL_TEMPLATES_CATEGORY . ' etc on(et.category_id=etc.id)

					where etc.type=2 order by category';

        //echo $sql1;

	$query1 = $mysql->query($sql1);

	$i = 1;

?>

	<section class="panel">

		<h4 class="panel-heading m-b-20">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_email_template_list')); ?>

			<input type="submit" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update'))?>" class="btn btn-danger btn-xs  pull-right">

		</h4>
		
	<div class="table-responsive">	

	<table class="table table-striped table-hover">

		<tr>

		  <th width="16"></th>

		  <th width="16"></th>

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_subject')); ?></th>

		  <?php if (defined("debug")){ ?><th></th><?php } ?>

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_category')); ?></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_code')); ?></th>

		  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_by')); ?></th>

		  <th></th>

		</tr>

		<?php

		$email_config 		= $objEmail->getEmailSettings();

		$admin_email 		= $email_config['admin_email'];

		$from_admin 		= $email_config['system_email'];

		$support_email		= $email_config['support_email'];

	

			if($mysql->rowCount($query1) > 0)

			{



				$rows1 = $mysql->fetchArray($query1);

				foreach($rows1 as $row1)

				{

					echo '<tr>';

					echo '<td>' . $i++ . '</td>';

		?>

					<td>

					 <label class="c-input c-checkbox"><input type="checkbox" name="ids[]" value="<?php echo $row1['id'];?>" <?php echo (($row1['status']==1) ? 'checked="checked"' : '');?> ><span class="c-indicator c-indicator-success"></span> </td>

		<?php			echo '<td>' . $row1['subject'] . '</td>';

					echo (defined("debug")) ?  ('<td>' . $row1['eid'] . ':' . $row1['code'] . '</td>') : '';

					echo '<td>' . $row1['category'] . '</td>';

                                       // echo '<td>' . $row1['code'] . '</td>';

			?>

					<td>

						<?php 

						switch ($row1['email_by_id']) {

						 	case 1:

						 			echo $admin_email;

						 		break;

						 	case 2:

									echo $from_admin ;

								break;

							case 3:

									echo $support_email;

						 		break;

						 } ?>

					</td>



			<?php

					echo '<td width="80">

							<a href="' . CONFIG_PATH_SITE_ADMIN . 'email_template_edit.html?id=' . $row1['id'] . '&cid='.$row1['eid'].'" class="btn btn-primary btn-sm">' .$admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

						</td>';

					echo '</tr>';

				}

			}

			else

			{

				echo $graphics->messagebox_warning($admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')));

			}

		?>

	</table>
	
	</div>

</section>

	<input type="submit" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints("com_update"))?>" class="btn btn-success">

	</form>

