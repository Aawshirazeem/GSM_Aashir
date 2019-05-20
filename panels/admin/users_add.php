<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_add_59905855d2');
	
	$id = $request->GetInt("id");
	$firstC = $request->GetStr("firstC");
	$limit = $request->GetInt("limit");
	$offset = $request->GetInt("offset");
	
	$first_name = '';
	$last_name = '';
	$username = '';
	$email = '';
	$password = '';
	$country = '';
	$city = '';
	$phone = '';
    $is_access=1;
    ///
    
     $input = $_SERVER['HTTP_HOST'];
    $input = trim($input, '/');
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }
    $urlParts = parse_url($input);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);
	
	$con = mysqli_connect("185.27.133.16","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");
	$qry_check='select * from tbl_users where  domain LIKE "%'.$domain.'%"  and reseller_panel=0';
	
	$result = $con->query($qry_check);
	
	if($result->num_rows > 0){
		$is_access=0;
	}
	// echo  $qry; 
	//
	if($id != 0){
		$sql = 'select * from ' . USER_REGISTER_MASTER . ' where id=' . $mysql->getInt($id);
		$query = $mysql->query($sql);
		if($mysql->rowCount($query) > 0){
			$rows = $mysql->fetchArray($query);
			
			$first_name = $rows[0]['first_name'];
			$last_name = $rows[0]['last_name'];
			$username = $rows[0]['username'];
			$email = $rows[0]['email'];
			$password = $rows[0]['password'];
			
			$country = $rows[0]['country_id'];
            $time_zone = $rows[0]['timezone_id'];
			$city = $rows[0]['city'];
			$phone = $rows[0]['phone'];
			$lang_code=$rows[0]['lang'];
			$custom_1=$rows[0]['custom_1'];
			$custom_2=$rows[0]['custom_2'];
			$custom_3=$rows[0]['custom_3'];
			$custom_4=$rows[0]['custom_4'];
			$custom_5=$rows[0]['custom_5'];
			
			$sql = 'select id from ' . USER_MASTER . ' where username=' . $mysql->quote($username);
			$query = $mysql->query($sql);
			if($mysql->rowCount($query) > 0){
				$username = "";
			}
			
			$sql = 'select id from ' . USER_MASTER . ' where email=' . $mysql->quote($email);
			$query = $mysql->query($sql);
			if($mysql->rowCount($query) > 0){
				$email = "";
			}
		}
	}
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_user')); ?></li>
        </ol>
    </div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add_process.do" method="post" class="frmValidate">
	<input name="reg_id" type="hidden" id="reg_id" value="<?php echo $id;?>" />
	<input name="firstC" type="hidden" id="firstC" value="<?php echo $firstC;?>" />
	<input name="limit" type="hidden" id="limit" value="<?php echo $limit;?>" />
	<input name="offset" type="hidden" id="offset" value="<?php echo $offset;?>" />
    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-6">
            <h5 class="m-b-10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?></h5>
            <div class="form-group">
                <label for="username"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?>
                    <div class="hidden pull-right ML10" id="usernameWait"><i class="fa fa-spinner fa-pulse"></i></div>
                    <div class="hidden pull-right ML10" id="usernameAvail"><i class="fa fa-check text-success"></i></div>
                    <div class="hidden pull-right ML10" id="usernameNotAvail"><i class="fa fa-times text-danger"></i></div>
                </label>
                <input name="username" type="text" class="form-control checkUserName required" data-msg-required="Please enter username" id="username" value="<?php echo $mysql->prints($username);?>" required />
            </div>
            <?php if ($password!=""){?>
            <input name="password" type="hidden" id="password" value="<?php echo $password;?>"/>
            <?php }else{?>
            
                  <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>
                        <input name="password" type="password" class="form-control" id="password" value=""/>
                  </div>
            
            <?php }?>
            <div class="form-group">
                <label for="email"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?>
                    <div class="hidden pull-right ML10" id="emailWait"><i class="fa fa-spinner fa-pulse"></i></div>
                    <div class="hidden pull-right ML10" id="emailAvail"><i class="fa fa-check text-success"></i></div>
                    <div class="hidden pull-right ML10" id="emailNotAvail"><i class="fa fa-times text-danger"></i></div>
                </label>
                <input name="email" type="email" class="form-control checkEmail required" data-msg-required="Please enter email" data-msg-email="Please enter a valid email id" id="email" value="<?php echo $mysql->prints($email);?>" required />
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>
                        <input name="credits" type="text" class="form-control" id="credits" value=""/>
                    </div>
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency')); ?></label>
                        <select name="currency" class="form-control" id="currency">
                            <?php
                                                                    
                                                                    // get user curr first
                                                                             $sql_currency = 'select a.currency_id from '.USER_REGISTER_MASTER.' a where a.id='.$id;
                                $query_currency = $mysql->query($sql_currency);
                                $rows_currency = $mysql->fetchArray($query_currency);
                                foreach($rows_currency as $row_currency)
                                {
                                                                                $temp_cur=$row_currency['currency_id'];
                                    //echo '<option ' . (($row_currency['is_default'] == '1') ? 'selected="selected"' : '') . ' value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';
                                }
                                                                    
                                                                            
                                $sql_currency = 'select * from ' . CURRENCY_MASTER . ' order by currency';
                                $query_currency = $mysql->query($sql_currency);
                                $rows_currency = $mysql->fetchArray($query_currency);
                                foreach($rows_currency as $row_currency)
                                {
                                    echo '<option ' . (($row_currency['id'] == $temp_cur) ? 'selected="selected"' : '') . ' value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_type')); ?></label>
                <div class="checkboxes">
                
                
                     <label class="c-input c-radio"><input type="radio" name="user_type" value="0" checked /> <i class="fa fa-user"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?> [<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_can_order_any_service_as_per_user_rights')); ?>]<span class="c-indicator c-indicator-success"></span></label>
                    <?php if($is_access==1) { ?>
                                                    <br><label class="c-input c-radio"><input type="radio" name="user_type" value="1" /> <i class="fa fa-group"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> [<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_has_all_facilites_like_a_user,_but_can_create/manage_his_own_customers')); ?>]<span class="c-indicator c-indicator-success"></span></label>
                <?php
                                                    }
                                            ?>
                                            </div>
            </div>
            <div class="form-group hidden">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits_transaction_limit')); ?></label>
                <input name="credits_transaction_limit" type="text" class="form-control" id="credits_transaction_limit" value="" />
                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_transaction_limit')); ?></p>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_admin_note')); ?></label>
                        <input type="text" name="admin_note" class="form-control" value="" />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note_for_further_refrence')); ?></p>
                    </div>
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_note')); ?></label>
                        <input type="text" name="user_note" class="form-control" value="" />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_message_from_admin_to_user')); ?></p>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <div class="row">
                    <div class="col-sm-3">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_access')); ?></label>
                        <p>
                            <div class="animated-switch">
                                <input type="checkbox" checked name="service_imei" value="1" id="switch-primary"/><label for="switch-primary" class="label-success adjchk"></label>
                            </div>
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_system_access')); ?></label>
                        <p>
                            <div class="animated-switch">
                                <input type="checkbox" checked name="service_file" value="1" id="switch-primary-2"/><label for="switch-primary-2" class="label-success adjchk"></label>
                            </div>
                        </p>
                    </div>
                    
                    <div class="col-sm-3">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?></label>
                        <p>
                            <div class="animated-switch">
                                <input type="checkbox" checked name="service_logs" value="1" id="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?>" />
                                <label for="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?>" class="label-success adjchk"></label>
                            </div>
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs_access')); ?></label>
                        <p>
                            <div class="animated-switch">
                                <input type="checkbox" checked name="service_prepaid" value="1" id="switch-primary-3"/>
                                <label for="switch-primary-3" class="label-success adjchk"></label>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <div class="col-sm-6">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_access')); ?><br></label>
                    <p>
                        <div class="animated-switch">
                            <input type="checkbox" name="api_access" value="1" id="switch-primary-4"/>
                            <label for="switch-primary-4" class="label-success adjchk"></label>
                        </div>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                    <p>
                        <div class="animated-switch">
                            <input type="checkbox" checked name="status" value="1" id="switch-primary-5"/>
                            <label for="switch-primary-5" class="label-success adjchk"></label>
                        </div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-lg-6">
            <h5 class="m-b-10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_other_details_[optional]')); ?></h5>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>
                        <input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo $first_name;?>" />
                    </div>
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>
                        <input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo $last_name;?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>
                <input name="company" type="text" class="form-control" id="company" value="" />
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>
                <textarea name="address" class="form-control" id="address" rows="4"></textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')); ?></label>
                        <input name="city" type="text" class="form-control" id="city" value="<?PHP echo $city;?>" />
                    </div>
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
                        <?PHP /*
                        <select name="country" class="form-control" id="country">
                        <option value=""><?phpecho $admin->wordTrans($admin->getUserLang(), $lang->prints('com_select_country')); ?></option>
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
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>
                        <select name="timezone" class="form-control" id="timezone">
                        <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                            <?php
                                $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
                                $query_timezone = $mysql->query($sql_timezone);
                                $rows_timezone = $mysql->fetchArray($query_timezone);
                                foreach($rows_timezone as $row_timezone)
                                {
                                    //echo '<option value="' . $row_timezone['id'] . '">' . $row_timezone['timezone'] . '</option>';
                                                                                    echo '<option ' . (($row_timezone['id'] == $time_zone) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';

                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>
                        <input name="phone" type="text" class="form-control" id="phone" value="<?PHP echo $phone;?>" />
                    </div>
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
                        <input name="mobile" type="text" class="form-control" id="mobile" value="" />
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 m-t-20">
                	<h6><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Fields'); ?></h6>
                    <table class="table table-striped table-hover">
                    	<tr>
                          <th><?php echo $admin->wordTrans($admin->getUserLang(),'Name'); ?></th>
                          <th><?php echo $admin->wordTrans($admin->getUserLang(),'Value'); ?></th>
                      </tr>
					<?php
                    if($custom_1!=""){
                          echo '<tr><td width="40%">'.$mysql->prints(current(explode(":",$custom_1))).'</td><td>' . $mysql->prints(substr($custom_1, strpos($custom_1, ":") + 1)) . '</td></tr>';
                          echo '<input type="hidden" name="custom_1" value="' . $custom_1 . '" />';
                    }
                    if($custom_2!=""){
                          echo '<input type="hidden" name="custom_2" value="' . $custom_2 . '" />';
                          echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $custom_2))).'</td><td>' . $mysql->prints(substr($custom_2, strpos($custom_2, ":") + 1)) . '</td></tr>';
                    }
                    if($custom_3!=""){
                          echo '<input type="hidden" name="custom_3" value="' . $custom_3 . '" />';
                          echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $custom_3))).'</td><td>' . $mysql->prints(substr($custom_3, strpos($custom_3, ":") + 1)) . '</td></tr>';
                    }
                    if($custom_4!=""){
                          echo '<input type="hidden" name="custom_4" value="' . $custom_4 . '" />';
                          echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $custom_4))).'</td><td>' . $mysql->prints(substr($custom_4, strpos($custom_4, ":") + 1)) . '</td></tr>';
                    }
                    if($custom_5!=""){
                          echo '<input type="hidden" name="custom_5" value="' . $custom_5 . '" />';
                          echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $custom_5))).'</td><td>' . $mysql->prints(substr($custom_5, strpos($custom_5, ":") + 1)) . '</td></tr>';
                    }
                    ?>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
                <button type="submit" class="btn btn-success btn-sm formSubmit"><i class="fa fa-check"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')); ?></button>
            </div>
        </div>
    </div>
</form>
<br><br><br><br><br>