<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('user_add_59905855d2');

	

	$id=$request->getInt('id');
        $s_id=$request->getInt('s_id');

	

	$sql='select * from ' . FILE_EXTENSIONS . ' where id=' . $id;

	$query=$mysql->query($sql);

?>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_white_list_edit_process.do" method="post">

<div class="lock-to-top">

	<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_file_extension')); ?></h4>

	

</div>







  

	<input type="hidden" name="id" value="<?php echo $id ;?>">
        	<input type="hidden" name="ser_id" value="<?php echo $s_id ;?>">


	<fieldset>

	  <?php

	  if($mysql->rowCount($query)>0)

	  {

		$rows=$mysql->fetchArray($query);

		$row=$rows[0];

	  ?>

	  

	  

			<div class="row">

				<div class="col-md-6">

					<div class="">

						<!-- <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_file_extension')); ?></h4>-->

						<div class="panel-body">

						  <div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_extension')); ?></label>

							<div class="input-group">

								<span class="input-group-addon">Filename.</span>

								<input name="file_ext" type="text" class="form-control" value="<?php echo $row['file_ext']?>" />

							</div>

						  </div>

						  <div class="form-group">

                          <label class="checkbox-inline c-input c-checkbox"><input name="status" type="checkbox" <?php echo ($row['status']==1 ? 'checked="checked"' : '')?> /> <span class="c-indicator c-indicator-warning"></span><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></span> </label>

						  </div>

						</div> <!-- / panel-body -->

						<div class="panel-footer">

							<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_extension')); ?>" />

                                                       


						</div> <!-- / panel-footer -->

					</div> <!-- / panel -->

				</div> <!-- / col-lg-6 -->
					<div class="col-md-12">	
						<br>
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_file_white_list.html?s_id=<?php echo $s_id;?>"><< <i class="icon-minus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Back')); ?></a>
					</div>
			</div> <!-- / row -->

	

	

	



	  <?php

	  }

	  else

	  {

		echo '<h2>'  . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record')) . '</h2>';

      }

	  ?>

	</fieldset>

  </form>

