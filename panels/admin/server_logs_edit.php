<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('con_pre_log_edit_14553737312');

$request = new request();
$mysql = new mysql();

$id = $request->GetInt('id');


$sql = 'select * from ' . SERVER_LOG_MASTER . ' where id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_server_logs_id_invalid'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
$is_custom = $row['is_custom'];
//echo $is_custome;

if ($is_custom == 1) {
    // get all custome filds
    $sql2 = 'select * from nxt_custom_fields a where a.s_type=2 and a.s_id=' . $mysql->getInt($id);

    $result = $mysql->getResult($sql2);
    $query = $mysql->query($sql2);
$rowCount2 = $mysql->rowCount($query);
    //$query2 = $mysql->query($sql2);
    //$c_rows = $mysql->fetchArray($query2);
}
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>
             <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_server_log')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_edit_process.do" method="post">
	<div class="row">
		<div class="col-md-12">
        	<div class="">
            	<div class="bs-nav-tabs nav-tabs-warning m-b-20">
					<ul class="nav nav-tabs nav-animated-border-from-left">
                        <li class="nav-item"><a href="#tabs-1" data-toggle="tab" class="nav-link active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-2" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></a></li>
                        <li class="nav-item"><a href="#tabs-3" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_input_options')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-6" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_Fields')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-4" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_settings')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-5" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_help/information')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-7" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Special_Credits')); ?> </a></li>
                        <li class="nav-item"><a href="#tabs-8" data-toggle="tab" class="nav-link"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visibl_to_users')); ?> </a></li>
					</ul>
				</div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="tabs-1" class="tab-pane active">
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_name')); ?> </label>
                                <input name="server_log_name" type="text" class="form-control" id="server_log_name" value="<?php echo $mysql->prints($row['server_log_name']); ?>" />
                                <input name="id" type="hidden" id="id" value="<?php echo $row['id']; ?>" />
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_group')); ?> </label>
                                <select name="group_id" id="group_id" class="form-control ">
                                    <?php
                                    $sql_group = "select * from " . SERVER_LOG_GROUP_MASTER;
                                    $query_group = $mysql->query($sql_group);
                                    $strReturn = "";
                                    if ($mysql->rowCount($query) > 0) {
                                        $rows_group = $mysql->fetchArray($query_group);
                                        foreach ($rows_group as $row_group) {
                                            echo '<option ' . (($row['group_id'] == $row_group['id']) ? 'selected="selected"' : '') . ' value="' . $row_group['id'] . '">' . $mysql->prints($row_group['group_name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?> </label>
                                <input name="delivery_time" type="text" class="form-control" id="delivery_time" value="<?php echo $mysql->prints($row['delivery_time']); ?>" />
<!--                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_verification')); ?> </label>
                                <input  type="checkbox" name="verification" value="1"  id="" <?php echo ($row['verification'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>

<!--                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_if_no_user_canot_verify_the_code')); ?></p>-->
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> <br></label>


                                <input  type="checkbox" name="status" value="1"  id="" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>

                            </div>
                            
                             <div class="form-group">
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_notification')); ?><br></label>
                                   
                                  <input  type="checkbox" name="e_notify" value="1"  id="" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>

<!--                                        <input type="checkbox" <?php echo ($row['auto_success'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" />-->
                                   
                                </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Is Chimera'); ?><br></label>


                                <input  type="checkbox" name="chimera" value="1"  id="chimera" <?php echo ($row['chimera'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" onchange="change(this);" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>

                            </div>

                            <div class="form-group" id="user_id" <?php echo ($row['chimera'] != '1') ? 'hidden' : ''; ?>>
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Chimera User ID'); ?><br></label>


                                <input  type="text" name="user_id"   id="user_id" onchange="change();" value="<?php echo $row['chimera_user_id']; ?>"  class="form-control"/>

                            </div>
                            <div class="form-group" id="api_key" <?php echo ($row['chimera'] != '1') ? 'hidden' : ''; ?>>
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Chimera API KEY'); ?><br></label>


                                <input  type="text" name="api_key"   id="api_key" value="<?php echo $row['chimera_api_key']; ?>" class="form-control" />

                            </div>

                        </div>
                        <div id="tabs-2" class="tab-pane">
                            <!-- Credit -->
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>
                                <div class="row">
                                    <?php
                                    $sql_curr = 'select
															cm.id, cm.currency, cm.prefix, cm.is_default,
															slad.amount, slad.amount_purchase
														from ' . CURRENCY_MASTER . ' cm
														left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on (slad.currency_id = cm.id and slad.log_id = ' . $id . ')
														where cm.status=1
														order by is_default DESC';
                                    $currencies = $mysql->getResult($sql_curr);
                                    foreach ($currencies['RESULT'] as $currency) {
                                        ?>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
                                            <div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info') ?>">
                                                <label><?php echo $currency['currency']; ?> [<?php echo $currency['prefix']; ?>]</label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                        <input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_selling_price')); ?>" value="<?php echo (($currency['amount'] != '0') ? $currency['amount'] : '') ?>"/>
                                                    </div> 
                                                    <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Sale'); ?></div>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>
                                                    <input name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" onblur="calculaterate2(this);" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_purchase_price')); ?>" value="<?php echo $currency['amount_purchase']; ?>" />
                                                </div>
                                                <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Purchase'); ?></div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- //Credit -->
                        </div>
                        <!-- //custome field -->
                        <div id="tabs-3" class="tab-pane">
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_name')); ?></label>
                                <input name="custom_field_name" type="text" class="form-control" id="custom_field_name" value="<?php echo $mysql->prints($row['custom_field_name']); ?>" />
                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_some_special_information_you_want_to_get_from_users')); ?></p>
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_message')); ?></label>
                                <input name="custom_field_message" type="text" class="form-control" id="custom_field_message" value="<?php echo $mysql->prints($row['custom_field_message']); ?>" />
                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_addissional_information_you_want_to_show_like_this')); ?></p>
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_value')); ?></label>
                                <input name="custom_field_value" type="text" class="form-control" id="custom_field_value" value="<?php echo $mysql->prints($row['custom_field_value']); ?>" />
                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_what_to_display_in_the_textbox')); ?></p>
                            </div>


<!--                            <input type="submit" value="Save" class="btn btn-large btn-success" id="btnsubmit" onclick="return validatelc();"/>-->

                        </div>
                        <div id="tabs-4" class="tab-pane">
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_external_api')); ?> </label>
                                <select name="api_id" class="form-control ">
                                    <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_api')); ?> </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service')); ?> </label>
                                <select name="api_service_id" class="form-control ">
                                    <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_service')); ?> </option>
                                </select>
                            </div>
                        </div>
                        <div id="tabs-6" class="tab-pane table-responsive">

                            <table class="table table-bordered" id="kaka">
                                <tr>
                                    <th>S.No</th>
                                    <th>Field Type</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Field Option</th>
                                    <th>Validation</th>
                                    <th>Required</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                                if ($rowCount2==0) {
                                    ?>
                                    <tr>
                                        <td style="width: 40px" id="tblr1c0">1</td>
                                        <td id="tblr1c1">
                                            <select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">
                                                <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Text'); ?></option>
                                                <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(),'Drop Down'); ?></option>
                                                <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(),'Checkbox'); ?></option>
                                            </select>
                                        </td>
                                        <td id="tblr1c2"><input type="text" name="f_name[]"  class="form-control"/></td>
                                        <td id="tblr1c3"><input type="text" name="f_desc[]"  class="form-control"/></td>
                                        <td id="tblr1c4"><input id="tblr1c4fop" type="text" name="f_opt[]"  class="form-control" title="Add Options Separated with commas" readonly/></td>
                                        <td id="tblr1c5"><input id="tblr1c5fvalid" type="text" name="f_valid[]"  class="form-control"/></td>
                                        <td id="tblr1c6"><input type="checkbox"   name="f_req1[]"  class="" onclick="updatecheck(this);"/><input id="chk_1" name="f_req2[]" type="hidden" value="0"></td>
                                        <td id="tblr1c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>
                                    </tr>
                                    <?php
                                } else {
                                    
                                    ?>
<!--                                     <tr>
                                        <td style="width: 40px" id="tblr1c0">1</td>
                                        <td id="tblr1c1">
                                            <select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">
                                                <option value="1">Text</option>
                                                <option value="2">Drop Down</option>
                                                <option value="3">Checkbox</option>
                                            </select>
                                        </td>
                                        <td id="tblr1c2"><input type="text" name="f_name[]"  class="form-control"/></td>
                                        <td id="tblr1c3"><input type="text" name="f_desc[]"  class="form-control"/></td>
                                        <td id="tblr1c4"><input id="tblr1c4fop" type="text" name="f_opt[]"  class="form-control" disabled="disabled"/></td>
                                        <td id="tblr1c5"><input id="tblr1c5fvalid" type="text" name="f_valid[]"  class="form-control"/></td>
                                        <td id="tblr1c6"><input type="checkbox"   name="f_req1[]"  class="" onclick="updatecheck(this);"/><input id="chk_1" name="f_req2[]" type="hidden" value="0"></td>
                                        <td id="tblr1c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>
                                    </tr>-->
                                    <?php
                                    if ($result['COUNT']) {
                                        $i = 1;
                                        foreach ($result['RESULT'] as $row2) {
                                            echo '<tr>';

                                            echo ' <td style="width: 40px" id="tblr' . $i . 'c0">' . $i . '</td>';
                                            // echo '<td>' . $mysql->prints($row2['name']) . '</td>';
                                            echo ' <td id="tblr' . $i . 'c1">
                                            <select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">
                                                <option ' . (($row2['f_type'] == 1) ? 'selected="selected"' : '') . ' value="1">Text</option>
                                                <option ' . (($row2['f_type'] == 2) ? 'selected="selected"' : '') . ' value="2">Drop Down</option>
                                                <option ' . (($row2['f_type'] == 3) ? 'selected="selected"' : '') . ' value="3">Checkbox</option>
                                            </select>
                                        </td>';
                                            echo ' <td id="tblr' . $i . 'c2"><input type="text" name="f_name[]" value="'.$row2['f_name'].'"  class="form-control"/></td>';
                                            echo ' <td id="tblr' . $i . 'c3"><input type="text" name="f_desc[]" value="'.$row2['f_desc'].'"  class="form-control"/></td>';
                                            echo ' <td id="tblr' . $i . 'c4"><input id="tblr' . $i . 'c4fop" type="text" name="f_opt[]" title="Add Options Separated with commas" value="'.$row2['f_opt'].'" class="form-control" ' . (($row2['f_type'] == 1 || $row2['f_type'] == 3 ) ? 'readonly=""' : '') . '/></td>';
                                            echo ' <td id="tblr' . $i . 'c5"><input id="tblr' . $i . 'c5fvalid" type="text" name="f_valid[]" value="'.$row2['f_valid'].'" class="form-control" ' . (($row2['f_type'] == 3 || $row2['f_type'] == 2) ? 'readonly=""' : '') . '/></td>';
                                            
                                            echo ' <td id="tblr' . $i . 'c6"><input type="checkbox"  name="f_req1[]"  class="" ' . (($row2['f_req'] == 1) ? 'checked="checked"' : '') . ' onclick="updatecheck(this);"/><input id="chk_' . $i . '" name="f_req2[]" type="hidden" value="' .$row2['f_req'].'"></td>';
                                            
                                            if($i==1)
                                            {
                                                echo '<td id="tblr' . $i . 'c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>';
                                            }
                                            else
                                            {
                                                echo '<td id=tblr' . $i . 'c8><input type="button" value="-" class="btn btn-danger" onclick="deleteRow(this)"></td>';
                                            }
                                            
                                            echo '</tr>';
                                            $i++;
                                        }
                                    }
                                }
                                ?>
                            </table>
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Note: If you want to disable custom field option then delete all rows and remove the NAME of first One' )?></label>
                        </div>
                        <div id="tabs-5" class="tab-pane">
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download_link')); ?> </label>
                                <input name="download_link" type="text" class="form-control" id="download_link" value="<?php echo $mysql->prints($row['download_link']); ?>" />
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_faq')); ?> </label>
                                <select name="faq_id" class="form-control ">
                                    <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_faq')); ?> </option>
                                    <?php
                                    $sql_faq = 'select * from ' . IMEI_FAQ_MASTER;
                                    $query_faq = $mysql->query($sql_faq);
                                    $rows_faq = $mysql->fetchArray($query_faq);
                                    foreach ($rows_faq as $row_faq) {
                                        echo '<option ' . (($row_faq['id'] == $row['faq_id']) ? 'selected="selected"' : '') . ' value="' . $row_faq['id'] . '">' . $mysql->prints($row_faq['question']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?> </label>
                                <textarea class="form-control" name="info" rows="3"><?php echo $mysql->prints($row['info']); ?></textarea>
                            </div>
                        </div>
                        <div id="tabs-7" class="tab-pane">
                            <?php

	
	$id=$request->getInt('id');
	
	$sql = 'select
				um.id, um.username, um.currency_id,
				slad.amount,
				slsc.amount spl_amount,
				cm.currency, cm.prefix, cm.suffix
			from ' . USER_MASTER . ' um 
			left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(um.id=slsc.user_id and slsc.log_id=' . $id. ')
			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)
			left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=' . $id. ' and um.currency_id = slad.currency_id)
			where um.reseller_id = 0 
                        order by um.username ';	
	$result = $mysql->getResult($sql);
	$result = $mysql->getResult($sql);
	$query = $mysql->query($sql);
	$i = 1;
?>
                            <input type="hidden" name="id" value="<?php echo $id;?>" >
		<div class="table-responsive">					
            <table class="table table-hover table-striped">
						<div class="panel-heading">
							<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_special_credit_user')); ?>
						</div>
						<tr>
							  <th width="16"></th>
							  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remove')); ?></th>
						</tr>
					<?php
						if($mysql->rowCount($query) > 0)
						{
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row)
							{
								echo '<tr class="' . (($row['spl_amount'] != '') ? 'danger' : '') . '">';
								echo '<td>' . $i++ . '</td>';
								echo '<td><b>' . $row['username'] . '</b></td>';
								echo '<td>' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</td>';
								echo '<td>
										<div class="input-group">
											<span class="input-group-addon">' . $row['prefix'] . '</span>
											<input type="text" class="form-control ' . (($row['spl_amount'] != '') ? 'textbox_highlight'. (($row['spl_amount'] > $row['amount']) ? 'R noEffect' : '') : '') . '" style="width:80px" name="spl_' . $row['id'] . '" value="' . $row['spl_amount'] . '" />
										</div>
									  </td>';
								echo '<td class="text_right">
										<input type="checkbox"  name="check_user[]" ' . ' value="' . $row['id'] .	'"/>
										<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />
										<input type="hidden" name="currency_id' . $row['id'] . '" value="' . $row['currency_id'] . '" />
									</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
						}
					?>
			</table>
		</div>	
                        </div>
                        <div id="tabs-8" class="tab-pane">
                           <?php

	
	$log_id=$request->getInt('id');
	
	$sql = 'select um.id,slu.log_id, um.username
			from ' . USER_MASTER . ' um 
			left join ' . SERVER_LOG_USERS . ' slu on(um.id=slu.user_id and slu.log_id=' . $log_id. ')
			left join ' . SERVER_LOG_MASTER . ' slm on(slu.log_id=slm.id)
			order by um.username';
	$query = $mysql->query($sql);
	$i = 1;
?> 
                            <input type="hidden" name="log_id" value="<?php echo $log_id;?>" >
						<table class="table table-striped table-hover">
						<?php
							if($mysql->rowCount($query) > 0)
							{
								$rows = $mysql->fetchArray($query);
								foreach($rows as $row)
								{
									echo '<tr class="' . (($row['log_id'] != '') ? 'success' : '') . '">';
									echo '<td>' . $i++ . '</td>';
									echo '<td>' . $row['username'] . '</td>';
									
									echo '<td class="text_right">
											<input type="checkbox"  name="check_user1[]" ' . (($row['log_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>
											<input type="hidden" name="user_ids1[]" value="' . $row['id'] . '" />
										</td>';
									echo '</tr>';
								}
							}
							else
							{
								echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
							}
						?>

					</table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_server_logs')); ?>" class="btn btn-success btn-sm" />
            </div>
        </div>
   	</div>
</form>

<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/init_nxt_admin.js"></script>

<script type="text/javascript">
	setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
	//document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
	function change(p){
		if($(p).prop("checked") == true){
			$("#user_id").show();
			$("#api_key").show();
		}else{
			$("#user_id").hide();
			$("#api_key").hide();
		}
		// var check=$('#chimera').is(":checked");
		// alert(check);
		//$('#user_id').hide();
		/*if(document.getElementById('chimera').checked){
			$("#user_id").show();
			$("#api_key").show();
		}else{
			$("#user_id").hide();
			$("#api_key").hide();
		}*/
	}
	
	function calculaterate(e){
		var id = e.id;
		var cur_id = id.substring(7);
		var value = $('#' + id).val();
		//  alert($('#'+id).val());
		// alert(cur_id);
		$.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data){
			/* data will hold the php array as a javascript object */
			$.each(data, function (key, val) {
				$('#amount_' + val.id).val(val.valuee);
				$('#amount_' + val.id).attr('value', val.valuee);
				$('#amount_' + val.id).html(val.valuee);
			});
		});
	}
	
	function calculaterate2(e){
		var id = e.id;
		var cur_id = id.substring(16);
		var value = $('#' + id).val();
		//  alert($('#'+id).val());
		// alert(cur_id);
		$.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data){
			/* data will hold the php array as a javascript object */
			$.each(data, function (key, val) {
				$('#amount_purchase_' + val.id).val(val.valuee);
				// $('#amount_' + val.id).attr('value', val.valuee);
				//$('#amount_' + val.id).html(val.valuee);
				//document.getElementById("amount_" + val.id).html =val.valuee;
				//$('#amount_'+key)
				//$('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');
			});
		});
	}
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.js"></script>
<script type="text/javascript">var myJQ = $.noConflict(true);</script>
<script type="text/javascript">
function makeclone()
    {
        var clonex = document.getElementById("mainaccount").cloneNode(true);
        myJQ("#kaka tr:last td:nth-child(3)").append(clonex);

    }
    function addnewrow()
    {

        //var mainncounter = 1;
        var rowCount = $('#kaka tr').length;
        var count = myJQ("#kaka tr:last td:nth-child(1)").text();
        //count=count+1;
        var mainncounter = parseInt(count);
        mainncounter = mainncounter + 1;
        if (rowCount <= 5)
        {
            myJQ("#kaka").append(
                    "<tr>" +
                    "<td id=tblr" + mainncounter + "c0>" + rowCount + "</td>" +
                    " <td id=tblr" + mainncounter + "c1><select class='form-control'  name='f_type[]' style='width: 100px' onchange='setfop(this);'><option value='1'>Text</option> <option value='2'>Drop Down</option> <option value='3'>Checkbox</option></select></td>" +
                    "<td id=tblr" + mainncounter + "c2><input type='text' name='f_name[]'  class='form-control'/></td>" +
                    //  "  <td><select name='accountlist[]' class='form-control'><option value='Account One'>Account One</option><option value='Account Two'>Account Two</option></select></td> " +
                    //  "<td><select name='acc_id[]'><option value='1'>Excess UK</option><option value='2'>Excess EUR</option><option value='3'>test account new one</option></select></td>" +
                    " <td id=tblr" + mainncounter + "c3><input type='text' name='f_desc[]' class='form-control'/></td>" +
                    "  <td id=tblr" + mainncounter + "c4><input id='tblr" + mainncounter + "c4fop' type='text' name='f_opt[]'  title='Add Options Separated with commas' readonly='readonly' class='form-control'/></td>" +
                    //  "<td>"+fname+"</td>" +
                    //   "<td> <input type='text' name='fc[]'class='form-control'/></td>" +
                    //    "<td> <input type='text' name='rate[]'class='form-control'/> </td>" +
                    "  <td id=tblr" + mainncounter + "c5><input type='text' id='tblr" + mainncounter + "c5fvalid' name='f_valid[]'  class='form-control'/></td>" +
                    //     "  <td id=tblr" + mainncounter + "c6><input type='text' name='rate[]' id=tblr" + mainncounter + "c6tb class='form-control' disabled='true' /><input type='hidden' name='min[]' id='tblr"+mainncounter+"c6tbhidmin'/><input type='hidden' name='max[]' id='tblr"+mainncounter+"c6tbhidmax'/></td>" +
                    // "  <td id=tblr" + mainncounter + "c6><input type='text' value='0' name='rate[]' onblur='sumuplc(this);' onkeypress='return isNumberKey(event)'  id=tblr" + mainncounter + "c6tb class='' readonly='true' /><span class='glyphicon glyphicon-arrow-up' style='color:green;font-size: 9px' id='tblr" + mainncounter + "c6tbhidmin'></span><span  id='tblr" + mainncounter + "c6tbhidmax' class='glyphicon glyphicon-arrow-down' style='color:red;font-size: 9px'></span> <input type='hidden' name='rule[]' id='tblr" + mainncounter + "c6tbhidrule'/></td>" +
                    "<td id=tblr" + mainncounter + "c7><input type='checkbox' onclick='updatecheck(this);' name='f_req1[]'/><input id='chk_" + mainncounter + "' name='f_req2[]' type='hidden' value='0'></td>" +
                    "<td id=tblr" + mainncounter + "c8><input type='button' value='-' class='btn btn-danger' onclick='deleteRow(this)'></td>" +
                    "</tr>");
            //    oko();
            // alert(count);
            makeclone();
        }
        else
        {
            alert("Sorry you cant add more fields");
        }
    }
    function deleteRow(r) {
        //var i = r.parentNode.parentNode.rowIndex;
        //document.getElementById("kaka").deleteRow(i);
        myJQ("#kaka tr:last").remove();
    }
    function updatecheck(r)
    {
        //$(r).attr('checked');
        //alert(r.is(':checked'));
        //alert(r.val);
        var $row = myJQ(r).closest("tr");      // Finds the closest row <tr> 
        var $tds = $row.find("td:nth-child(1)"); // Finds the 2nd <td> element
        //alert($tds.text());
        var rownumber = parseInt($tds.text());
        if (myJQ(r).is(':checked'))
        {
            //  alert("checked");
            myJQ("#chk_" + rownumber).val(1);
        }
        else
        {
            // alert("un-checked");
            myJQ("#chk_" + rownumber).val(0);
        }
        // alert(myJQ(r).val());


    }
     function setfop(r)
    {
        var temp = myJQ(r).val();
       // alert(temp);
        var $row = myJQ(r).closest("tr");      // Finds the closest row <tr> 
        var $tds = $row.find("td:nth-child(1)"); // Finds the 2nd <td> element
        //alert($tds.text());
        var rownumber = parseInt($tds.text());
        //alert(rownumber);
        if (temp == "2")
        {

            if (myJQ("#tblr" + rownumber + "c4fop").prop("readonly"))
            {
                //alert("disable");
                myJQ("#tblr" + rownumber + "c4fop").removeAttr('readonly');
                myJQ("#tblr" + rownumber + "c5fvalid").attr('readonly', 'readonly');
                //$("input").removeAttr('disabled');
            }

        }

        else if (temp == "3")
        {
            myJQ("#tblr" + rownumber + "c5fvalid").attr('readonly', 'readonly');
            myJQ("#tblr" + rownumber + "c4fop").attr('readonly', 'readonly');
        }
        else
        {
            //alert("enble");
            // $("input").attr('disabled','disabled');
            if (myJQ("#tblr" + rownumber + "c5fvalid").prop("readonly"))
            {
                myJQ("#tblr" + rownumber + "c4fop").attr('readonly', 'readonly');
                myJQ("#tblr" + rownumber + "c5fvalid").removeAttr('readonly');
            }
        }
        //alert('set op');
    }
</script>