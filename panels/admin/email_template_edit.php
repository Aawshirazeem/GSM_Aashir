<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('user_add_59905855d2');

	

	$id=$request->getInt('id');

	

	$sql='select * from ' . EMAIL_TEMPLATES . ' where id=' . $id;

	$query=$mysql->query($sql);

?>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_template_edit_process.do" method="post">

<div class="lock-to-top">

	

	<div class="btn-group btn-group-sm">

<!--		<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" />-->

	</div>

</div>



	<input type="hidden" name="id" value="<?php echo $id ;?>">

	  <?php

	  $email_config 	= $objEmail->getEmailSettings();

	  $admin_email    	= $email_config['admin_email'];

	  $system_from 		= $email_config['system_email'];

	  $support_email 	= $email_config['support_email'];

	  

	  if($mysql->rowCount($query)>0)

	  {

			$rows=$mysql->fetchArray($query);

			$row=$rows[0];

	  ?>

	  

		<div class="row">

			<div class="col-md-8">

				<div class="">

					<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_email_template')); ?></h4>

					<div class="panel-body">

						

						<div class="form-group">

							<div class="row">

								<div class="col-sm-6">

									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_subject')); ?></label>

									<input name="subject" type="text" class="form-control" value="<?php echo $row['subject']?>" />

								</div>

								<div class="col-sm-6">

									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_by')); ?></label>

									<select name="email_by_id" id="" class="form-control">

										<option <?php echo ($row['email_by_id'] == 1) ? 'selected' : ''; ?> value="1">Admin: <?php echo $admin_email; ?></option>

										<option <?php echo ($row['email_by_id'] == 2) ? 'selected' : ''; ?> value="2">System: <?php echo $system_from; ?></option>

										<option <?php echo ($row['email_by_id'] == 3) ? 'selected' : ''; ?> value="3">Support: <?php echo $support_email; ?></option>

									</select>

								</div>

							</div>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_body')); ?></label>

							<div class="clearfix"></div>

							<textarea class="ckeditor" name="mailbody" rows="15" cols="30"><?php echo $row['mailbody'];?></textarea>

							<div class="clearfix"></div>

						</div>

						<div class="form-group">

                       

							<label class="c-input c-checkbox"><input name="status" type="checkbox" <?php echo ($row['status']==1 ? 'checked="checked"' : '')?> /><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></span></label>							

						</div>

						

					</div> <!-- / panel-body -->

					<div class="panel-footer">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>email_template_list.html" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>

						<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" />

					</div> <!-- / panel-footer -->

				</div> <!-- / panel -->

			</div> <!-- / col-lg-6 -->

		</div> <!-- / row -->

	

	  <?php

	  }

	  else

	  {

		echo '<h2>'  . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record')) . '</h2>';

      }

	  ?>

  </form>

<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>





<script>

	CKEDITOR.config.toolbar = [

		['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace'],

		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

		['Source'],

	];

</script>

