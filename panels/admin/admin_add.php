<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('suppliers_add_549883whh2');

?>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin_add_process.do" method="post">

	<div class="row">

    	<div class="col-md-10">

        	<h4 class="m-b-20 col-md-12">

            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?>

            </h4>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>

                <input name="username" type="text" class="form-control" id="username" value="" required />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>

                <input name="password" type="password" class="form-control" id="password" required/>

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Nick')); ?></label>

                <input name="nick" type="text" class="form-control" id="nick" value="" required />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_first_name')); ?></label>

                <input name="fname" type="text" class="form-control" id="fname" value=""  />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Last_Name')); ?></label>

                <input name="lname" type="text" class="form-control" id="lname" value="" />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone_number')); ?></label>

                <input name="pnum" type="text" class="form-control" id="pnum" value="" required />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>

                <input name="address" type="text" class="form-control" id="address" value="" />

            </div>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>

                <input name="email" type="text" class="form-control checkEmailSupplier" id="email" value="" required />

            </div>



            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>

                <?PHP /*

                  <select name="country" class="form-control" id="country">

                  <option value=""><?php $lang->prints('com_select_country'); ?></option>

                  <?php

                  $sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';

                  $query_country = $mysql->query($sql_country);

                  $rows_country = $mysql->fetchArray($query_country);

                  foreach($rows_country as $row_country)

                  {

                  echo '<option value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';

                  }

                  ?>

                  </select> */

                ?>

                <select name="country" class="form-control" id="country">

                    <?PHP

                    echo $objHelper->getCountries($country);

                    ?>

                </select>

            </div>



            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>

                <select name="timezone" class="form-control" id="timezone">

                    <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>

                    <?php

                    $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';

                    $query_timezone = $mysql->query($sql_timezone);

                    $rows_timezone = $mysql->fetchArray($query_timezone);

                    foreach ($rows_timezone as $row_timezone) {

                        echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';

                    }

                    ?>

                </select>

            </div>
			
            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label><br>

                <label class="c-input c-radio">

                	<input type="radio" name="status" value="1" checked="checked">

                    <span class="c-indicator c-indicator-success"></span>

                    <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </span>

                </label>

                

                <label class="c-input c-radio">

                	<input type="radio" name="status" value="0">

                    <span class="c-indicator c-indicator-primary"></span>

                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </span>

                </label>

            </div>

            <div class="form-group col-md-6">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>

                            <select name="language" class="form-control" id="language">

                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>

                                <?php

                                    $sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';

                                    $query_language = $mysql->query($sql_language);

                                    $rows_language = $mysql->fetchArray($query_language);

                                    foreach($rows_language as $row_language)

                                    {

                                        echo '<option ' . (($row_language['id'] == $lang_code) ? 'selected="selected"' : '') . ' value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';

                                    }

                                ?>

                            </select>

                        </div>

			<br style="clear:both">
            <div class="form-group col-md-6">

            	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin.html" class="btn btn-danger">
				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>

                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')); ?>" class="btn btn-success"/>

            </div>

        </div>

    </div>

</form>