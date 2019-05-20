<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('suppliers_edit_54964566hh2');

    

	$id = $request->GetInt('id');



	$sql ='select * from ' . SUPPLIER_MASTER . ' where id=' . $mysql->getInt($id);

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers.html?reply=" . urlencode('reply_invalid_login'));

		exit();

	}

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

	  $con = mysqli_connect("185.27.133.16","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");

$qry_check='select * from tbl_users where  domain="'.$_SERVER['HTTP_HOST'].'" and supplier_panel=0';



  $result = $con->query($qry_check);



if ($result->num_rows > 0) { 

                                     echo("<script>location.href = '../un_authrize.php';</script>");

                                        exit;

                                  }

?>





<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></a></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_suppliers')); ?></a></li>

        </ol>

    </div>

</div>

	<div class="col-lg-8">

		<!--tab nav start-->

        

		<div class="row">

			<div class="bs-nav-tabs nav-tabs-warning m-b-20">

				<ul class="nav nav-tabs nav-animated-border-from-left">

					<li class="nav-item"><a data-toggle="tab" href="#tabs-1" class="nav-link active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?></a></li>

					<li class="nav-item"><a data-toggle="tab" href="#tabs-2" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_profile')); ?></a></li>

					<li class="nav-item"><a data-toggle="tab" href="#tabs-3" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_services')); ?></a></li>

					<li class="nav-item"><a data-toggle="tab" href="#tabs-4" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_services')); ?></a></li>

					<li class="nav-item"><a data-toggle="tab" href="#tabs-5" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_service')); ?></a></li>

				</ul>

			</div>

			<div class="panel-body">

				<div class="tab-content">

				<div id="tabs-1" class="tab-pane active" role="tabpanel">

					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_edit_login_process.do" method="post">

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>

							<input name="username" type="text" readonly class="form-control" id="username" value="<?php echo $mysql->prints($row['username'])?>" />

							<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label><br>

                            <small class="text-muted"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password_for_the_above_login_email')); ?></small>

							<input name="password" type="text" class="form-control" id="password" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>

							<input name="email" type="text" readonly class="form-control" id="email" value="<?php echo $mysql->prints($row['email'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>

                           

                            

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> > <span class="c-indicator c-indicator-success"></span><i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> > <span class="c-indicator c-indicator-success"></span><i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="2" <?php echo (($row['status'] == '2') ? 'checked="checked"' : '');?> > <span class="c-indicator c-indicator-success"></span><i style="color:#888888"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_suspend')); ?></i></label>

						</div>

						<div class="form-group">

							<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php $lang->prints('com_cancel'); ?></a>

							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_login_details')); ?>" class="btn btn-success btn-sm"/>

						</div>

					</form>

				</div>

















				<div id="tabs-2" class="tab-pane" role="tabpanel">

					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_edit_profile_process.do" method="post">

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>

							<input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo $mysql->prints($row['first_name'])?>" />

							<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>

							<input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo $mysql->prints($row['last_name'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>

							<input name="company" type="text" class="form-control" id="company" value="<?php echo $mysql->prints($row['company'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>

							<textarea name="address" class="form-control" id="address" rows="4"><?php echo $mysql->prints($row['address'])?></textarea>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>

							<input name="city" type="text" class="form-control" id="city" value="<?php echo $mysql->prints($row['city'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>

							<select name="language" class="form-control" id="language">

								<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>

								<?php

									$sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';

									$query_language = $mysql->query($sql_language);

									$rows_language = $mysql->fetchArray($query_language);

									foreach($rows_language as $row_language)

									{

										echo '<option ' . (($rows_language['id'] == $row['language_id']) ? 'selected="selected"' : '') . ' value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';

									}

								?>

							</select>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>

							<select name="timezone" class="form-control" id="timezone">

								<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>

								<?php

									$sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';

									$query_timezone = $mysql->query($sql_timezone);

									$rows_timezone = $mysql->fetchArray($query_timezone);

									foreach($rows_timezone as $row_timezone)

									{

										echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';

									}

								?>

							</select>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>

							<select name="country" class="form-control" id="country">

								<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>

								<?php

									$sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';

									$query_country = $mysql->query($sql_country);

									$rows_country = $mysql->fetchArray($query_country);

									foreach($rows_country as $row_country)

									{

										echo '<option ' . (($row_country['id'] == $row['country_id']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $mysql->prints($row_country['countries_name']) . '</option>';

									}

								?>

							</select>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>

							<input name="phone" type="text" class="form-control" id="phone" value="<?php echo $mysql->prints($row['phone'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>

							<input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $mysql->prints($row['mobile'])?>" />

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_show_username')); ?><br></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="show_user" value="1" <?php echo (($row['show_user'] == '1') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="show_user" value="0" <?php echo (($row['show_user'] == '0') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></label>

						</div>

						<div class="form-group">

							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_show_credits')); ?><br></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="show_credits" value="1" <?php echo (($row['show_credits'] == '1') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></label>

							<label class="checkbox-inline c-input c-radio"><input type="radio" name="show_credits" value="0" <?php echo (($row['show_credits'] == '0') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></label>

						</div>

						<div class="form-group">

							<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_profile')); ?>" class="btn btn-success btn-sm"/>

						</div>

					</form>

				</div>























				<div id="tabs-3" class="tab-pane" role="tabpanel">

					<?php

						if($row['service_imei'] == '1')

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_imei_process.do?id=' . $id . '&enable=0" class="btn btn-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_disable')) . '</a>';

						}

						else

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_imei_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_enable')) . '</a>';

						}

					?>

					<div id="imei_spl_credits" <?php echo (($row['service_imei'] != '1') ? 'style="display:none"' : '');?>>

						<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_spl_credits_process.do" enctype="multipart/form-data" method="post">

							<input type="hidden" name="id" value="<?php echo $id;?>" >

							<table class="MT5 table table-striped table-hover panel">

							<tr>

								  <th width="16"></th>

								  <th colspan="2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>

								  <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase')); ?></th>

								  <th width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_commission')); ?></th>

								  <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>

							</tr>

							<?php

								$sql_spl_imei = 'select

												tm.*,

												itad.amount, itad.amount_purchase,

												sd.tool as splTool,

												sd.credits_purchase as credits_purchase_tool, 

												igm.group_name,

												cm.prefix, cm.suffix

											from ' . IMEI_TOOL_MASTER . ' tm

											left join ' . IMEI_SUPPLIER_DETAILS . ' sd on (tm.id = sd.tool and sd.supplier_id = ' . $mysql->getInt($id) . ')

											left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)

											left join ' . CURRENCY_MASTER . ' cm on(cm.is_default=1)

											left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = cm.id)

											where tm.visible=1 and tm.id not in ( select  b.tool from nxt_imei_supplier_details b  



                                                                                            where b.supplier_id!= ' . $mysql->getInt($id) . '

                                                                                            )

											order by igm.sort_order, tm.sort_order, tm.tool_name';

								

								$query_spl_imei = $mysql->query($sql_spl_imei);

								$strReturn = "";

								$i = 1;

								$groupName = "";

								if($mysql->rowCount($query_spl_imei) > 0)

								{

									$rows_spl_imei = $mysql->fetchArray($query_spl_imei);

									foreach($rows_spl_imei as $row_spl_imei)

									{

										$tempService = $mysql->prints($row_spl_imei['tool_name']);

										$tempCreditsPurchase = $mysql->prints($row_spl_imei['amount_purchase']);

										$tempCredits = $mysql->prints($row_spl_imei['amount']);

										if($groupName != $row_spl_imei['group_name'])

										{

											echo '<tr><td colspan="6">' . $mysql->prints($row_spl_imei['group_name']) . '</td></tr>';

											$groupName = $row_spl_imei['group_name'];

										}

										echo '<tr>';

										echo '<td>' . $i++ . '</td>';
										echo '<td width="16"><label class="c-input c-checkbox"><input type="checkbox" name="ids[]" value="' . $row_spl_imei['id'] . '"  ' . (($row_spl_imei['splTool'] != '') ? ' checked="checked" ' : '') . '/><span class="c-indicator c-indicator-success"></span></td>';

										echo '<td>' . (($row_spl_imei['splTool'] != '') ? '<b class="TC_R">' . $tempService . '</b>' : $tempService) . '</td>';

										echo '<td>' . $tempCreditsPurchase . '</td>';

										echo '<td><input type="text" class="form-control ' . (($row_spl_imei['credits_purchase_tool'] != '0' and $row_spl_imei['credits_purchase_tool'] != '') ? 'text-danger' : '') . '" name="spl_' . $row_spl_imei['id'] . '" value="' . $mysql->prints($row_spl_imei['credits_purchase_tool']) . '" /></td>';

										echo '<td>' . $tempCredits . '</td>';

										echo '</tr>';

									}

								}

								else

								{

									echo '<tr><td colspan="6" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

								}

							?>

							</table>

							<div class="form-group">

								<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

								<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_access')); ?>" class="btn btn-success btn-sm"/>

							</div>

						</form>

					</div>

				</div>









				<div id="tabs-4" class="tab-pane" role="tabpanel">

					<?php

						if($row['service_file'] == '1')

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_file_process.do?id=' . $id . '&enable=0" class="btn btn-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_disable')) . '</a>';

						}

						else

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_file_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_enable')) . '</a>';

						}

					?>



					<div id="file_spl_credits" <?php echo (($row['service_file'] != '1') ? 'style="display:none"' : '');?>>

						<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_spl_credits_file_process.do" enctype="multipart/form-data" method="post">

							<input type="hidden" name="id" value="<?php echo $id;?>" >

							<table class="MT5 table table-striped table-hover panel">

							<tr>

								  <th width="16"></th>

								  <th colspan="2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_commission')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>

							</tr>

							<?php

								$sql_spl_imei = 'select

															fsm.*, fsm.credits_purchase as credits_purchase_tool_org,

															fsm.credits as credits_sale_tool,

															fsd.credits_purchase as credits_purchase_tool,

															fsd.service_id as splID,fsd.service_id

													from ' . FILE_SERVICE_MASTER . ' fsm

													left join ' . FILE_SUPPLIER_DETAILS . ' fsd on (fsm.id = fsd.service_id and fsd.supplier_id = ' . $mysql->getInt($id) . ')

													order by fsm.service_name';





								$sql_spl_imei = 'select

														fsm.*,

														fsad.amount,

														fsd.credits_purchase as credits_purchase_tool,

														fsd.service_id as splID,fsd.service_id,

														cm.prefix, cm.suffix

													from ' . FILE_SERVICE_MASTER . ' fsm

													left join ' . FILE_SUPPLIER_DETAILS . ' fsd on (fsm.id = fsd.service_id and fsd.supplier_id = ' . $mysql->getInt($id) . ')

													left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)

													left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = cm.id)

													order by fsm.service_name';

								

								$query_spl_imei = $mysql->query($sql_spl_imei);

								$strReturn = "";

								$i = 1;

								if($mysql->rowCount($query_spl_imei) > 0)

								{

									$rows_spl_imei = $mysql->fetchArray($query_spl_imei);

									foreach($rows_spl_imei as $row_spl_imei)

									{

										$tempService = $mysql->prints($row_spl_imei['service_name']);

										$tempCreidts = $mysql->prints($row_spl_imei['amount']);

										echo '<tr>';

										echo '<td>' . $i++ . '</td>';

										echo '<td class="text_right">

												<input type="checkbox" name="ids[]" value="' . $row_spl_imei['id'] . '"  ' . (($row_spl_imei['splID'] != '') ? ' checked="checked" ' : '') . '/>

											  </td>';

										echo '<td>' . (($row_spl_imei['service_id'] != '') ? '<b class="TC_R">' . $tempService . '</b>' : $tempService) . '</td>';

										echo '<td>' . $tempCreditsPurchase . '</td>';

										echo '<td><input type="text" class="form-control ' . (($row_spl_imei['credits_purchase_tool'] != '0' and $row_spl_imei['credits_purchase_tool'] != '') ? 'text-danger' : '') . '" name="spl_' . $row_spl_imei['id'] . '" value="' . $mysql->prints($row_spl_imei['credits_purchase_tool']) . '" /></td>';

										echo '<td>' . $tempCredits . '</td>';

										echo '</tr>';

									}

								}

								else

								{

									echo '<tr><td colspan="6" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

								}

							?>

							</table>

							<div class="form-group">

								<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

								<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_access')); ?>" class="btn btn-success btn-sm"/>

							</div>

						</form>

					</div>

				</div>





				<div id="tabs-5" class="tab-pane">

					<?php

						if($row['service_logs'] == '1')

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_log_process.do?id=' . $id . '&enable=0" class="btn btn-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_disable')) . '</a>';

						}

						else

						{

							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit_log_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_enable')) . '</a>';

						}

					?>



					<div id="server_log_spl_credits" <?php echo (($row['service_logs'] != '1') ? 'style="display:none"' : '');?>>

						<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_spl_credits_server_log_process.do" enctype="multipart/form-data" method="post">

							<input type="hidden" name="id" value="<?php echo $id;?>" >

							<table class="MT5 table table-striped table-hover panel">

							<tr>

								  <th width="16"></th>

								  <th colspan="2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_commission')); ?></th>

								  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>

							</tr>

							<?php

								$sql_spl_imei = 'select

															slm.*, slm.credits_purchase as credits_purchase_org,

															slm.credits as credits_sale_tool,

															slsd.credits_purchase as credits_purchase_tool,

															slsd.log_id as splID,

															slgm.group_name

												from ' . SERVER_LOG_MASTER . ' slm

										left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)

										left join ' . SERVER_LOG_SUPPLIER_DETAILS . ' slsd on (slm.id = slsd.log_id and slsd.supplier_id = ' . $mysql->getInt($id) . ')

										order by slm.group_id, slm.server_log_name';





								$sql_spl_imei = 'select

															slm.*,

															slgm.group_name,

															slad.amount,

															slsd.credits_purchase as credits_purchase_tool,

															slsd.log_id as splID,

															cm.prefix, cm.suffix

														from ' . SERVER_LOG_MASTER . ' slm

														left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)

														left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')

														left join ' . SERVER_LOG_SUPPLIER_DETAILS . ' slsd on (slm.id = slsd.log_id and slsd.supplier_id = ' . $mysql->getInt($id) . ')

														left join ' . CURRENCY_MASTER . ' cm on(cm.is_default=1)

														order by slm.server_log_name';

								

								$query_spl_imei = $mysql->query($sql_spl_imei);

								$strReturn = "";

								$i = 1;

								$groupName = "";

								if($mysql->rowCount($query_spl_imei) > 0)

								{

									$rows_spl_imei = $mysql->fetchArray($query_spl_imei);

									foreach($rows_spl_imei as $row_spl_imei)

									{

										if($groupName != $row_spl_imei['group_name'])

										{

											echo '<tr><td colspan="5">' . $mysql->prints($row_spl_imei['group_name']) . '</td></tr>';

											$groupName = $row_spl_imei['group_name'];

										}

										$tempService = $mysql->prints($row_spl_imei['server_log_name']);

										$tempCredits = $mysql->prints($row_spl_imei['amount']);

										echo '<tr>';

										echo '<td>' . $i++ . '</td>';

										echo '<td class="text_right">

												<input type="checkbox" name="ids[]" value="' . $row_spl_imei['id'] . '"  ' . (($row_spl_imei['splID'] != '') ? ' checked="checked" ' : '') . '/>

											  </td>';

										echo '<td>' . (($row_spl_imei['splID'] != '') ? '<b>' . $tempService . '</b>' : $tempService) . '</td>';

										echo '<td>' . $tempCreditsPurchase . '</td>';

										echo '<td><input type="text" class="form-control ' . (($row_spl_imei['credits_purchase_tool'] != '0' and $row_spl_imei['credits_purchase_tool'] != '') ? 'text-danger' : '') . '" name="spl_' . $row_spl_imei['id'] . '" value="' . $mysql->prints($row_spl_imei['credits_purchase_tool']) . '" /></td>';

										echo '<td>' . $tempCredits . '</td>';

										echo '</tr>';

									}

								}

								else

								{

									echo '<tr><td colspan="6" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

								}

							?>

							</table>

							<div class="form-group">

								<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

								<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success btn-sm"/>

							</div>

						</form>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

                      <!--tab nav start-->

