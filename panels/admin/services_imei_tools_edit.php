<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('services_imei_model_edit_5434553hh2');



$request = new request();

$mysql = new mysql();



$id = $request->GetInt('id');

// get gropss array

$sql_group = 'select a.grp from nxt_grp_det a where a.ser='.$id;

                                $query_group = $mysql->query($sql_group);
                                $i=0;
                                if ($mysql->rowCount($query) > 0) {

                                  //  $rows_group_selected = $mysql->fetchAssoc($query_group);
                                     while($row = mysqli_fetch_assoc($query_group)) {
        //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        $rows_group_selected[$i]=$row['grp'];
         $i++;
    }
                                }
                             //   var_dump($rows_group_selected);
//---------end

$sql = 'select * from ' . IMEI_TOOL_MASTER . ' where id=' . $mysql->getInt($id);

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_services_imei'));

    exit();

}

$rows = $mysql->fetchArray($query);

$row = $rows[0];

$is_custom = $row['is_custom'];

$custom_2=$row['imei_type'];
//echo $is_custome;



if ($is_custom == 1) {

    // get all custome filds

    $sql2 = 'select * from nxt_custom_fields a where a.s_type=1 and a.s_id=' . $mysql->getInt($id);



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

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_imei_unlocking_tool')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_edit_process.do" method="post" name="frm_inquiry" id="frm_inquiry" class="formSkin noWidth">

	<div class="row">

    	<div class="col-xs-12">

        	<div class="bs-nav-tabs nav-tabs-warning">

        	<ul class="nav nav-tabs nav-animated-border-from-left">

            	<li class="nav-item"> 

                	<a class="nav-link active" data-toggle="tab" data-target="#nav-tabs-0-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-3"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_input_options')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-8"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_Fields')); ?></a> 

                </li>
                  <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-9"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Settings')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-4"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_help/information')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-5"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_visible_to_users')); ?></a> 

                </li>

                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-6"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Special_Credits')); ?></a> 

                </li>

<!--                <li class="nav-item"> 

                	<a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-7"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Brands')); ?></a> 

                </li>-->

            </ul>

            <div class="tab-content">

            	<div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">

                	<div class="p-t-20">

                    	<div class="form-group">

                            <div class="row">

                                <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_name')); ?> </label>

                                    <input name="tool_name" type="text" class="form-control" id="tool_name" value="<?php echo $mysql->prints($row['tool_name']); ?>" />

                                    <input name="id" type="hidden" id="id" value="<?php echo $row['id']; ?>" />

                                </div>

    <!--                                    <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_alias')); ?></label>

                                    <input name="tool_alias" type="text" class="form-control" id="tool_alias" value="<?php echo $mysql->prints($row['tool_alias']); ?>" />

                                </div>-->

                            </div>

                        </div>

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group')); ?></label>

                            <select name="group_id[]" id="group_id" class="" multiple="multiple">

                                <?php

                                $sql_group = "select * from " . IMEI_GROUP_MASTER;

                                $query_group = $mysql->query($sql_group);

                                $strReturn = "";

                                if ($mysql->rowCount($query) > 0) {

                                    $rows_group = $mysql->fetchArray($query_group);

                                    foreach ($rows_group as $row_group) {

                                       // echo '<option ' . (($row['group_id'] == $row_group['id']) ? 'selected="selected"' : '') . ' value="' . $row_group['id'] . '">' . $mysql->prints($row_group['group_name']) . '</option>';
                                        echo '<option ' . (in_array($row_group['id'],$rows_group_selected) ? 'selected="selected"' : '') . ' value="' . $row_group['id'] . '">' . $mysql->prints($row_group['group_name']) . '</option>';

                                    }

                                }

                                ?>

                            </select>

                        </div>

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_delivery_time')); ?></label>

                            <input name="delivery_time" type="text" class="form-control" id="delivery_time" value="<?php echo $mysql->prints($row['delivery_time']); ?>" />

<!--                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->

                        </div>

                        <div class="alert alert-info">

                            <div class="form-group">



                            </div>
                            <div class="form-group text-center">
                                <div class="row">
                                     <div class="col-sm-6" data-toggle="tooltip" data-placement="bottom" title="if your supplier/provider of this service will increase your price and his new price is more then your adjusted limit so your api will be disconnected API." >

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_auto_stop_api_if_price_up')); ?></label>

                            	<div class="animated-switch">

<!--                                    <input type="checkbox" <?php echo ($row['is_check_rate'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" />-->

                                    <input id="switch-success-api_r_sync" name="api_r_sync" type="checkbox" onchange="change(this);" value="1" <?php echo ($row['is_check_rate'] == '1') ? 'checked="checked"' : ''; ?> />

                                    <label for="switch-success-api_r_sync" class="label-success adjchk"></label>

                                </div>     
                            <?php $p_limit=$row['is_check_rate'];
                            $show_providers=$row['api_rej'];
                            
                            ?>

                            </div> <div class="col-sm-6" id="p_limit">
                               
                                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_price_Limit')); ?></label>

                            <input name="api_c_limit" type="text" class="form-control" id="api_c_limit" value="<?php echo $mysql->prints($row['api_rate_sync']); ?>" />

                          
                             </div>
                                  <div class="col-sm-4" data-toggle="tooltip" data-placement="bottom" title="">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Manual Authorize API Order'); ?></label>

                                    <div class="animated-switch">


                                    	<input id="switch-success-api_auth" name="api_auth" type="checkbox" value="1" <?php echo ($row['api_auth'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-api_auth" class="label-success adjchk"></label>

                                    </div>

                                </div>
                                </div></div>
                            

                        </div>

                        <div class="form-group text-center">

                            <div class="row">

                                <div class="col-sm-2" data-toggle="tooltip" data-placement="bottom" title="if you turn it on so users can send orders related to this service for verification/review from user panel.">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),'Verification'); ?></label>

                                    <div class="animated-switch">

                                    	<!--input type="checkbox" <?php echo ($row['verification'] == '1') ? 'checked="checked"' : ''; ?> name="verification" value="1" /-->

                                    	<input id="switch-success" name="verification" type="checkbox" value="1" <?php echo ($row['verification'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                

                                <div class="col-sm-2" data-toggle="tooltip" data-placement="top" title="if you turn it on so users can cancel orders related to this service from user panel." >

                                	<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel_imei')); ?></label>

                                	<div class="animated-switch">

                                    

                                    	<!--input type="checkbox" <?php echo ($row['cancel'] == '1') ? 'checked="checked"' : ''; ?> name="cancel" value="1" /-->

                                    	<input id="switch-success-cancel" name="cancel" type="checkbox" value="1" <?php echo ($row['cancel'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-cancel" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                

                                <div class="col-sm-3" data-toggle="tooltip" data-placement="bottom" title="if any imei order is already success/completed in this service and any user submit the same imei again so system will detect it and system
will make auto complete order instantly with old code/reply."  >

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_duplicate_IMEI_Order_Success')); ?></label>

                                	<div class="animated-switch">

                                    	<!--input type="checkbox" <?php echo ($row['accept_duplicate'] == '1') ? 'checked="checked"' : ''; ?> name="accept_duplicate" value="1" /-->

                                    	<input id="switch-success-check_duplicate" name="check_duplicate" type="checkbox" value="1" <?php echo ($row['duplicate_yn'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-check_duplicate" class="label-success adjchk"></label>

                                    </div>

                                </div>
                                 <div class="col-sm-2" data-toggle="tooltip" data-placement="bottom" title="if this option is activated so user can order one imei/sn again and again as much time he want even if the same serial is already success so again he can submit.
normally this option need to active for info checker services." >

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_accept_duplicate')); ?></label>

                                	<div class="animated-switch">

                                    	<!--input type="checkbox" <?php echo ($row['accept_duplicate'] == '1') ? 'checked="checked"' : ''; ?> name="accept_duplicate" value="1" /-->

                                    	<input id="switch-success-accept_duplicate" name="accept_duplicate" type="checkbox" value="1" <?php echo ($row['accept_duplicate'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-accept_duplicate" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                

                                <div class="col-sm-3" data-toggle="tooltip" data-placement="bottom" title="This option will verify the imei number with the technical formula and system will not accept any incorrect imei number from user." >

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_verify_checksum')); ?></label>

                                    <div class="animated-switch">

                                    	<!--input type="checkbox" <?php echo ($row['verify_checksum'] == '1') ? 'checked="checked"' : ''; ?> name="verify_checksum" value="1" /-->

                                    	<input id="switch-success-verify_checksum" name="verify_checksum" type="checkbox" value="1" <?php echo ($row['verify_checksum'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-verify_checksum" class="label-success adjchk"></label>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="form-group text-center">

                            <div class="col-sm-3" data-toggle="tooltip" data-placement="bottom" title="if this option is turned off so this service can't see any user from user panel and no one can submit any order from user panel." >

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_visible')); ?></label>

                            	<div class="animated-switch">

                                    <!--input type="checkbox" <?php echo ($row['visible'] == '1') ? 'checked="checked"' : ''; ?> name="visible" value="1" /-->

                                    <input id="switch-success-visible" name="visible" type="checkbox" value="1" <?php echo ($row['visible'] == '1') ? 'checked="checked"' : ''; ?> />

                                    <label for="switch-success-visible" class="label-success adjchk"></label>

                                </div>

                            </div>

                            <div class="col-sm-3" data-toggle="tooltip" data-placement="bottom" title="if you turn it off so your customers can't submit any order in this service by api to your service. you must have to turn it ON!" >

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Accept_Order_by_API')); ?></label>

                            	<div class="animated-switch">

                                    <!--input type="checkbox" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" /-->

                                    <input id="switch-success-status" name="status" type="checkbox" value="1" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> />

                                    <label for="switch-success-status" class="label-success adjchk"></label>

                                </div>                                

                            </div>

                            

                             <div class="col-sm-2" data-toggle="tooltip" data-placement="bottom" title="if you turn it ON so when user will submit any order to this service so he will get response UNLOCKED Instantly without process and without doing anywork!
you can use this option for already unlocked devices services, Example: Swisscom Swizerland,Sunrise, Bouygues ETC." >

                             <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_auto_order')); ?></label>

                                <div class="animated-switch">

                                    <!--input type="checkbox" <?php echo ($row['auto_success'] == '1') ? 'checked="checked"' : ''; ?> name="auto_success" value="1" /-->

                                    <input id="switch-success-auto_success" name="auto_success" type="checkbox" value="1" <?php echo ($row['auto_success'] == '1') ? 'checked="checked"' : ''; ?> />

                                    <label for="switch-success-auto_success" class="label-success adjchk"></label>

                                </div>

                            </div>

                             <div class="col-sm-2" data-toggle="tooltip" data-placement="bottom" title="this email notification option is for admin Notification. if admin turn it off so admin will not recieve any email when any new order is recieved in this service." >

                             <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_notification')); ?></label>

                             	<div class="animated-switch">

                                    <!--input type="checkbox" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> name="e_notify" value="1" /-->

                                    <input id="switch-success-e_notify" name="e_notify" type="checkbox" value="1" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> />

                                    <label for="switch-success-e_notify" class="label-success adjchk"></label>

                                </div>

                            </div>
                           

                        </div>
                            <div class="form-group text-center">
                              
                            <div class="row">
                                <div class="col-sm-3">
                                    <br><br> <label>Auto Order Success Time(MINUTES)</label>
                                    <input type="number" class="form-control" name="succ_time" id="succ_time" value="<?php echo $row['succ_time'];?>"/>
                                </div>
                                 <div class="col-sm-3">
                                    <br><br> <label>Verification Expiry <br>In Days</label>
                                    <input type="number" class="form-control" name="veri_days" id="veri_days" value="<?php echo $row['veri_days'];?>"/>
                                </div>
                             
                             
                                  <div class="col-sm-3">
  <br>  <br><label>Click To Send Price <br>Notification</label>
                           <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>service_imei_price_update.html?id=<?php echo $id?>" class="btn btn-primary btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),'Send Price Notification To All Users');?></a>

                            </div>

                            </div>
                                
                            </div>
                            
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-9">
                                                    <div class="row alert alert-info">
                                                        <h3>Main Provider</h3><br>
                                    <div class="col-sm-6">

                                        <label>

                                            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_external_api')); ?>

                                            <input type="hidden" name="calltype" id="calltype" value="1">

                                            <div class="hidden pull-right ML10" id="ApiIdWait"><i class="icon-refresh icon-spin"></i></div>

                                        </label>

                                        <select id="parent" name="api_id" style="width:75%;overflow: auto">

                                            <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_api')); ?> </option>

                                            <?php

                                            $sql_api = 'select * from ' . API_MASTER . ' where `status`=1 order by api_server';

                                            $query_api = $mysql->query($sql_api);

                                            $rows_api = $mysql->fetchArray($query_api);

                                            foreach ($rows_api as $row_api) {

                                                echo '<option ' . (($row['api_id'] == $row_api['id']) ? 'selected="selected"' : '') . ' value="' . $row_api['id'] . '">' . $mysql->prints($row_api['api_server']) . '</option>';

                                            }

                                            ?>

                                        </select>

                                    </div>

                                    <div class="col-sm-6">

                                        <?php if ($row['api_id'] == 0) { ?>
                                            <select id="child" name="api_service_id" style="width:75%;overflow: scroll;">
                                                <option value="">Select One Of The Options</option>
                                            </select>
                                        <?php } else { ?>

                                            <div id="apiIdDetails">

                                                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_service')); ?></label>

                                                <?php
                                                $sql_api_service = 'select * from ' . API_DETAILS . ' where api_id=' . $mysql->getInt($row['api_id']) . ' order by service_name';
                                                //   echo $sql_api_service
                                                $query_api_service = $mysql->query($sql_api_service);

                                               echo '<select id="child" name="api_service_id" style="width:75%;overflow: scroll;">';

                                                echo '<option value="">--</option>';

                                                if ($mysql->rowCount($query_api_service) > 0) {

                                                    $rows_api_service = $mysql->fetchArray($query_api_service);

                                                    foreach ($rows_api_service as $row_api_service) {

                                                        echo '<option ' . (($row['api_service_id'] == $row_api_service['service_id']) ? 'selected="selected"' : '') . ' value="' . $row_api_service['service_id'] . '">' . $row_api_service['service_name'] . '</option>';
                                                    }
                                                }

                                                echo '</select>';
                                                ?>

                                            </div>


<?php } ?>
                                    </div>

                                </div>
                   
                       
                    
                        
                      <div class="col-sm-2" data-toggle="tooltip" data-placement="bottom" title="" >

                             <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Reject_Review')); ?></label>

                             	<div class="animated-switch">

                                    <!--input type="checkbox" <?php echo ($row['is_send_noti'] == '1') ? 'checked="checked"' : ''; ?> name="e_notify" value="1" /-->

                                &nbsp;&nbsp;&nbsp;    <input onchange="change2(this);" id="switch-success-api_rej" name="api_rej" type="checkbox" value="1" <?php echo ($row['api_rej'] == '1') ? 'checked="checked"' : ''; ?> />

                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <label for="switch-success-api_rej" class="label-success adjchk"></label>

                                </div>

                      </div><br><br><br><div class="row" id="s_pro">
                       <h3>Secondary Provider(S)</h3><br>
                    <hr>
                      <label>Manual OR Auto Reject Process</label>
                      <div class="checkboxes">

                            	<label class="c-input c-radio">

                                    <input <?php echo (($row['api_rej_man_auto'] == '0') ? 'checked="checked"' : ''); ?> type="radio" name="api_rej_custom_act" value="0">

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text">AUTO</span>

                                </label>
                               

                                <label class="c-input c-radio">

                                	<input <?php echo (($row['api_rej_man_auto'] == '1') ? 'checked="checked"' : ''); ?> type="radio" name="api_rej_custom_act" value="1">

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text"> MANUAL</span>

                                </label>

                            </div>
                      <div id="p_table" class="p-t-20">
                    <h3>Select Api </h3>
                    
                      <select id="parent2" name="api_id_2" style="width:75%;overflow: auto">

                                            <option selected="selected" value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_api')); ?> </option>

                                            <?php

                                            $sql_api = 'select * from ' . API_MASTER . ' where `status`=1 order by api_server';

                                            $query_api = $mysql->query($sql_api);

                                            $rows_api = $mysql->fetchArray($query_api);

                                            foreach ($rows_api as $row_api) {

                                                echo '<option  value="' . $row_api['id'] . '">' . $mysql->prints($row_api['api_server']) . '</option>';

                                            }

                                            ?>

                      </select><br>
                      <select id="child2" name="api_service_id_2" style="width:75%;overflow: scroll;">
<?php
                                                echo '<option value="">--</option>';

                                                if ($mysql->rowCount($query_api_service) > 0) {

                                                    $rows_api_service = $mysql->fetchArray($query_api_service);

                                                    foreach ($rows_api_service as $row_api_service) {

                                                        echo '<option ' . (($row['api_service_id'] == $row_api_service['service_id']) ? 'selected="selected"' : '') . ' value="' . $row_api_service['service_id'] . '">' . $row_api_service['service_name'] . '</option>';
                                                    }
                                                }?>

                      </select><br>
                      <label>ENTER PRICE ADJUSTMENT IN % (LIKE +50 or -20 or leave blank )</label>
                      <input type="text" name="p_adj" id="p_adj" class="" />
                      <input type="button" class="btn btn-danger" value="Add API TO List" onclick="apendtbl();">
                      <div id="apipridata" style="">
                          <table class="table table-bordered"  id="tblapip">
                           
                              <tr>
                                 
                                  <th>API NAME</th>
                                  <th>API SERVICE</th>
                                   <th>PRICE ADJ</th>
                                  <th>Action</th>
                              </tr>
                              <?php
                                $sql14 = 'select * from nxt_api_priority a where a.s_id=' . $mysql->getInt($id);



                              $result14 = $mysql->getResult($sql14);
                              if ($result14['COUNT']) {



                                  foreach ($result14['RESULT'] as $row14) {
                                     // echo '<tr><td><input type="text" value="'.$row14['api_id'].'" class="form-control" readonly name="one[]"></td><td><input type="text" value="'.$row14['api_name'].'" class="form-control" readonly name="two[]"></td><td><input type="hidden" value="'.$row14['api_service_id'].'" name="three[]"><input type="hidden" value="'.$row14['api_service_name'].'" name="four[]">'.$row14['api_service_name'].'</td><td><input type="button" value="-" class="btn btn-danger" onclick="deleteRow2(this)"></td></tr>';
                                      echo '<tr><td><input type="hidden" value="'.$row14['api_id'].'" name="one[]"><input type="hidden" value="'.$row14['api_name'].'" name="two[]">'.$row14['api_name'].'</td><td><input type="hidden" value="'.$row14['api_service_id'].'" name="three[]"><input type="hidden" value="'.$row14['api_service_name'].'" name="four[]">'.$row14['api_service_name'].'</td><td><input type="hidden" value="'.$row14['b_price_adj'].'" name="five[]">'.$row14['b_price_adj'].'</td><td><input type="button" value="-" class="btn btn-danger" onclick="deleteRow2(this)"></td></tr>';
                                      }
                              }
                                  ?>
                          </table>
                      </div></div></div>
                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-2">

                	<div class="p-t-20">

                    	<!-- Credit -->

                            <div class="form-group">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>

                                <div class="row">

                                    <?php

                                    $sql_curr = 'select

															cm.id, cm.currency, cm.prefix, cm.is_default,

															itad.amount, itad.amount_purchase

														from ' . CURRENCY_MASTER . ' cm

														left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on (itad.currency_id = cm.id and itad.tool_id = ' . $id . ')

                                                                                                                      

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

                                                        <input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_selling_price')); ?>" value="<?php echo (($currency['amount'] != '0') ? number_format($currency['amount'],2) : '') ?>"/>

                                                    </div> 

                                                    <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sale')); ?></div>

                                                </div>

                                                <div class="input-group">

                                                    <span class="input-group-addon"><?php echo $currency['prefix']; ?></span>

                                                    <input name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" onblur="calculaterate2(this);" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_purchase_price')); ?>" value="<?php echo number_format($currency['amount_purchase'],2); ?>" />

                                                </div>

                                                <div class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_purchase')); ?></div>

                                            </div>

                                        </div>

                                        <?php

                                    }

                                    ?>

                                </div>

                                <?php

                                $sql_disc = 'select itm.*,pkg.amount as discount,spl.amount as spl_dis from ' . IMEI_TOOL_MASTER . ' itm'

                                        . ' left join ' . PACKAGE_IMEI_DETAILS . ' pkg on (itm.id=pkg.tool_id) 

                                                                                          left join ' . IMEI_SPL_CREDITS . ' spl on (itm.id=spl.tool_id)'

                                        . ' where spl.tool_id=' . $id . ' or pkg.tool_id=' . $id;

                                //  echo $sql_disc;exit;

                                $data = $mysql->query($sql_disc);

                                //        var_dump($data);exit;

                                $data = $mysql->fetchArray($data);

                                //  var_dump($data);exit;

                                if ($data[0]["discount"] != '' || $data[0]["spl_dis"] != '') {







                                    echo '<a href="#" id="btn_dis' . $id . '" onclick="reset_discount(' . $id . ');" class="btn btn-success btn-sm"> '.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_reset_discount')).'</a>';

                                }

                                ?>      

                            </div>

                            <!-- //Credit -->

                    </div>
                        <div class="row">
                            <?php
                            $tool_id = $id;

$sql_tools = 'select
							tm.id, tm.tool_name, igm.group_name,
							itad.amount, cm.prefix
						from ' . IMEI_TOOL_MASTER . ' tm
						left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
						left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)
						left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(tm.id = itad.tool_id and cm.id = itad.currency_id)
                                                    where tm.id=' . $tool_id . '
						order by igm.sort_order, igm.group_name, tm.sort_order, tm.tool_name';
//$tools = $mysql->getResult($sql_tools);
$qrydata = $mysql->query($sql_tools);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $Tool_Amount = $rows[0]['amount'];
    $Tool_Prefix = $rows[0]['prefix'];
}


$sql_packg = 'select * from ' . PACKAGE_MASTER . ' where status=1 order by id';
$packgs = $mysql->getResult($sql_packg);


$sql_curr = 'select * from ' . CURRENCY_MASTER . ' where status=1 order by is_default DESC';
$currencies = $mysql->getResult($sql_curr);
                            
                            ?>
                            <hr>
        <div class="col-xs-10">
            <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_groups_price')); ?></h4>
            <table class="table table-hover table-striped">
                <tr>
                    <th width="16"></th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
                    <th width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org_cr')); ?></th>
                    <?php
                    foreach ($currencies['RESULT'] as $currency) {
                        echo '<th width="120">' . $currency['currency'] . '</th>';
                    }
                    ?>
                </tr>
                <?php
                $i = 1;
                foreach ($packgs['RESULT'] as $packg) {
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . $mysql->prints($packg['package_name']) . '</td>';
                    echo '<td class="text_right">' . $Tool_Prefix . $Tool_Amount . '</td>';
                    foreach ($currencies['RESULT'] as $currency) {


                        $sql_details = 'select currency_id, tool_id, amount from ' . PACKAGE_IMEI_DETAILS . ' where tool_id='.$id.' and package_id=' . $packg['id'] . ' order by tool_id, currency_id';
                        //     echo $sql_details;
                        $packge_details = $mysql->getResult($sql_details);
                        $deails = array();
                        foreach ($packge_details['RESULT'] as $package) {
                            $toolID = $package['tool_id'];
                            $currencyID = $package['currency_id'];
                            $amount = $package['amount'];
                            $details[$packg['id']][$currencyID] = $amount;
                        }




                        echo '<td class="text_right">
											<div class="input-group">
												<span class="input-group-addon">' . $currency['prefix'] . '</span>
												<input onblur="calculaterateok(this);" type="text" class="form-control" name="amountsg_' . $packg['id'] . '_' . $currency['id'] . '" id="amountsg_' . $packg['id'] . '_' . $currency['id'] . '" value="' . (isset($details[$packg['id']][$currency['id']]) ? number_format($details[$packg['id']][$currency['id']],2) : '') . '" />
											</div>
											
										  </td>';
                    }
                    echo '</tr>';
                    // }
                }
                ?>
            </table>
           
        </div>
    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-3">

                	<div class="p-t-20">

                    	<div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>



                            <select name="country_id" class="form-control">

                                <option <?php echo (($row['country_id'] == "0") ? 'selected="selected"' : ''); ?> value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_country')); ?></option>

                                <option <?php echo (($row['country_id'] == "-1") ? 'selected="selected"' : ''); ?> value="-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_*all_countries')); ?></option>

                                <?php

                                $sql_country = 'select * from ' . COUNTRY_MASTER . ' where status=1';

                                $query_country = $mysql->query($sql_country);

                                $rows_country = $mysql->fetchArray($query_country);

                                foreach ($rows_country as $row_country) {

                                    echo '<option ' . (($row['country_id'] == $row_country['id']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $mysql->prints($row_country['countries_name']) . '</option>';

                                }

                                ?>

                            </select>

                            <p class="help-block">

                                <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_country')); ?> </b> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to_hide_all')); ?> <br />

                                <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_*all_countries')); ?></b> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to_show_all_countries_with_operators')); ?> <br />

                                <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_others:')); ?></b> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_show_networks_of_seleted_country_only')); ?>

                            </p>

                        </div>

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mep_group')); ?></label>

                            <select name="mep_group_id" class="form-control">

                                <option <?php echo (($row['mep_group_id'] == "0") ? 'selected="selected"' : ''); ?> value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_mep')); ?></option>

                                <?php

                                $sql_mep = 'select * from ' . IMEI_MEP_GROUP_MASTER;

                                $query_mep = $mysql->query($sql_mep);

                                $rows_mep = $mysql->fetchArray($query_mep);

                                foreach ($rows_mep as $row_mep) {

                                    echo '<option ' . (($row['mep_group_id'] == $row_mep['id']) ? 'selected="selected"' : '') . ' value="' . $row_mep['id'] . '">' . $mysql->prints($row_mep['mep_group']) . '</option>';

                                }

                                ?>

                            </select>

                        </div>

                        <div class="form-group text-center">

                            <div class="row">

                                <div class="col-sm-3">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_required_pin')); ?></label>

                                	<div class="animated-switch">

                                    	<!--<input type="checkbox" <?php echo ($row['field_pin'] == '1') ? 'checked="checked"' : ''; ?> name="field_pin" value="1" />-->

                                    	<input id="switch-success-field_pin" name="field_pin" type="checkbox" value="1" <?php echo ($row['field_pin'] == '1') ? 'checked="checked"' : ''; ?>>

                                        <label for="switch-success-field_pin" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                <div class="col-sm-3">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_required_kbh')); ?></label>

                                    <div class="animated-switch">

                                    	<!--<input type="checkbox" <?php echo ($row['field_kbh'] == '1') ? 'checked="checked"' : ''; ?> name="field_kbh" value="1" />-->

                                    	<input id="switch-success-field_kbh" name="field_kbh" type="checkbox" value="1" <?php echo ($row['field_pin'] == '1') ? 'checked="checked"' : ''; ?>>

                                        <label for="switch-success-field_kbh" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                <div class="col-sm-3">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_required_prd')); ?></label>

                                	<div class="animated-switch">

                                    	<!--<input type="checkbox" <?php echo ($row['field_prd'] == '1') ? 'checked="checked"' : ''; ?> name="field_prd" value="1" />-->

                                    	<input id="switch-success-field_prd" name="field_prd" type="checkbox" value="1" <?php echo ($row['field_prd'] == '1') ? 'checked="checked"' : ''; ?>>

                                        <label for="switch-success-field_prd" class="label-success adjchk"></label>

                                    </div>

                                </div>

                                <div class="col-sm-3">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_required_type')); ?></label>

                                    <div class="animated-switch">

                                    	<!--<input type="checkbox" <?php echo ($row['field_type'] == '1') ? 'checked="checked"' : ''; ?> name="field_type" value="1" />-->

                                    	<input id="switch-success-field_type" name="field_type" type="checkbox" value="1" <?php echo ($row['field_type'] == '1') ? 'checked="checked"' : ''; ?>>

                                        <label for="switch-success-field_type" class="label-success adjchk"></label>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="well">

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_field_type')); ?> </label>

                            <div class="checkboxes">

                            	<label class="c-input c-radio">

                                    <input type="radio" onclick="showdiv(0);" name="imei_type" value="1" <?php echo (($row['imei_type'] == '1') ? 'checked="checked"' : ''); ?> />

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_single_imei')); ?> </span>

                                </label>

                                <label class="c-input c-radio">

                                	<input type="radio" name="imei_type" onclick="showdiv(0);" value="0" <?php echo (($row['imei_type'] == '0') ? 'checked="checked"' : ''); ?> />

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_bulk_imei')); ?> </span>

                                </label>

                                <label class="c-input c-radio">

                                	<input type="radio" name="imei_type" onclick="showdiv(0);" value="3" <?php echo (($row['imei_type'] == '3') ? 'checked="checked"' : ''); ?> />

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Both_Single_plus_Bulk')); ?> </span>

                                </label>

                                <label class="c-input c-radio">

                                	<input type="radio" name="imei_type" onclick="showdiv(1);" value="2" <?php echo (($row['imei_type'] == '2') ? 'checked="checked"' : ''); ?> />

                                    <span class="c-indicator c-indicator-default"></span>

                                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom')); ?> </span>

                                </label>

                            </div>

                        </div>
                        <div id="custom_fld">
                        <div class="form-group">

                            <div class="row">

                                <div class="col-sm-6">

                                

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_imei_field_name')); ?> </label>

                                    <input name="imei_field_name" type="text" class="form-control" id="imei_field_name" value="<?php echo $mysql->prints($row['imei_field_name']); ?>" />

                                </div>

                                <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_imei_info')); ?> </label>

                                    <input name="imei_field_info" type="text" class="form-control" id="imei_field_info" value="<?php echo $mysql->prints($row['imei_field_info']); ?>" />

                                </div>

                            </div>

                        </div>

                        <div class="form-group">

                            <div class="row">

                                <div class="col-sm-6">

                                

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_length')); ?> </label>

                                    <input name="imei_field_length" type="text" class="form-control" id="imei_field_length" value="<?php echo $mysql->prints($row['imei_field_length']); ?>" />

                                </div>

                                <div class="col-sm-6 text-center">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_accept_alphabet')); ?></label>

                                	<div class="animated-switch">

                                    	<!--<input type="checkbox" <?php echo ($row['imei_field_alpha'] == '1') ? 'checked="checked"' : ''; ?> name="imei_field_alpha" value="1" />-->

                                    	<input id="switch-success-imei_field_alpha" name="imei_field_alpha" type="checkbox" value="1" <?php echo ($row['imei_field_alpha'] == '1') ? 'checked="checked"' : ''; ?> />

                                        <label for="switch-success-imei_field_alpha" class="label-success adjchk"></label>

                                    </div>

                                </div>

                            </div>



                        </div>
</div>
                    </div>

                    <div class="form-group">

<!--                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field')); ?></label>

                                        <p>

                                        <div class="switch" data-on-label="ON" data-off-label="OFF">

                                          <input  type="checkbox" name="custom_required" value="1"  id="" <?php echo ($row['custom_required'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>



                                            <input type="checkbox" <?php echo ($row['custom_required'] == '1') ? 'checked="checked"' : ''; ?> name="custom_required" value="1" />

                                        </div>

                                        </p>

                                    </div>



                                    <div class="col-sm-4">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_range')); ?></label>

                                        <input type="range" id="custom_range" name="custom_range" min="1" value="<?//=$row['custom_range']!=''?$row['custom_range']:'10';?>" max="<?//=MAX_CUSTOM_FIELD_LENGTH_FOR_SERVICE;?>" step="1" oninput="outputUpdateRange(value)">

<output for="custom_range" id="r_id" style="font-weight:bold"><b><?//=$row['custom_range']!=''?$row['custom_range']:'10';?></b></output>

                                     <input  type="checkbox" name="custom_range" value="1"  id="" <?php echo ($row['custom_range'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" ata-size="small"/>



                                        <input type="text" name="custom_range" class="form-control" value="<?= $row['custom_range']; ?>" /> 

                                    </div>



                                </div>-->

<!--                                <div class="row">

                                    <div class="col-sm-4">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_name')); ?></label>

                                        <input name="custom_field_name" type="text" class="form-control" id="custom_field_name" value="<?php echo $mysql->prints($row['custom_field_name']); ?>" />

                                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_some_special_information_you_want_to_get_from_users')); ?></p>

                                    </div>

                                    <div class="col-sm-4">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_message')); ?></label>

                                        <input name="custom_field_message" type="text" class="form-control" id="custom_field_message" value="<?php echo $mysql->prints($row['custom_field_message']); ?>" />

                                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_addissional_information_you_want_to_show_like_this')); ?></p>

                                    </div>

                                    <div class="col-sm-4">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field_value')); ?></label>

                                        <input name="custom_field_value" type="text" class="form-control" id="custom_field_value" value="<?php echo $mysql->prints($row['custom_field_value']); ?>" />

                                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_what_to_display_in_the_textbox')); ?></p>

                                    </div>



                                </div>-->

                            </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-8">

                	<div class="p-t-20">

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

                                            <option value="1">Text</option>

                                            <option value="2">Drop Down</option>

                                            <option value="3">Checkbox</option>

                                        </select>

                                    </td>

                                    <td id="tblr1c2"><input type="text" name="f_name[]"  class="form-control"/></td>

                                    <td id="tblr1c3"><input type="text" name="f_desc[]"  class="form-control"/></td>

                                    <td id="tblr1c4"><input id="tblr1c4fop" type="text" name="f_opt[]" title="Add Options Separated with commas"  class="form-control" readonly/></td>

                                    <td id="tblr1c5"><input id="tblr1c5fvalid" type="text" name="f_valid[]"  class="form-control"/></td>

                                    <td id="tblr1c6">

                                        <label class="c-input c-checkbox">

                                        	<input type="checkbox"   name="f_req1[]"  class="" onclick="updatecheck(this);"/>

                                        	<span class="c-indicator c-indicator-default"></span>

                                            

                                        </label>

                                        <input id="chk_1" name="f_req2[]" type="hidden" value="0">

                                    </td>

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

                                        echo ' <td id="tblr' . $i . 'c4"><input id="tblr' . $i . 'c4fop" type="text" name="f_opt[]" title="Add Options Separated with commas"  value="'.$row2['f_opt'].'" class="form-control" ' . (($row2['f_type'] == 1 || $row2['f_type'] == 3 ) ? 'readonly=""' : '') . '/></td>';

                                        echo ' <td id="tblr' . $i . 'c5"><input id="tblr' . $i . 'c5fvalid" type="text" name="f_valid[]" value="'.$row2['f_valid'].'" class="form-control" ' . (($row2['f_type'] == 3 || $row2['f_type'] == 2) ? 'readonly=""' : '') . '/></td>';

                                        

                                        echo ' <td id="tblr' . $i . 'c6"><label class="c-input c-checkbox"><input type="checkbox"  name="f_req1[]"  class="" ' . (($row2['f_req'] == 1) ? 'checked="checked"' : '') . ' onclick="updatecheck(this);"/><input id="chk_' . $i . '" name="f_req2[]" type="hidden" value="' .$row2['f_req'].'"><span class="c-indicator c-indicator-default"></span></label></td>';

										

                                        

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

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),"Note: If you want to disable custom field option then delete all rows and remove the 'NAME' of first One"); ?></label>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-7">

                	<div class="p-t-20">

                    	<div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brands')); ?></label>

                            <select name="brand_id" class="form-control" onchange="getModels(this);">

                                <option <?php echo (($row['brand_id'] == "0") ? 'selected="selected"' : ''); ?> value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_no_brand')); ?> </option>

                                <!--option <?php echo (($row['brand_id'] == "-1") ? 'selected="selected"' : ''); ?> value="-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_*all_brands')); ?> </option-->

                                <?php

                                $sql_brand = 'select * from ' . IMEI_BRAND_MASTER . ' where status=1';

                                $query_brand = $mysql->query($sql_brand);

                                $rows_brand = $mysql->fetchArray($query_brand);

                                foreach ($rows_brand as $row_brand) {

                                    echo '<option ' . (($row['brand_id'] == $row_brand['id']) ? 'selected="selected"' : '') . ' value="' . $row_brand['id'] . '">' . $mysql->prints($row_brand['brand']) . '</option>';

                                }

                                ?>

                            </select>

                            <p class="help-block">

                                <!--b><?php $lang->prints('lbl_no_brand:'); ?> </b> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to_hide_all')); ?> <br />

                                <!--b><?php $lang->prints('lbl_*all_brands'); ?> </b> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to_show_all_brands_and_models')); ?> <br /-->

                                <!--b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_others:')); ?><--/b--><?php //$lang->prints('lbl__show_models_of_selected_brand_only');   ?>

                            </p>

                        </div>

                        <div class="form-group contact-search m-b-30">

                            <input type="text" id="search2" class="form-control" placeholder="Search Model......">

                        </div>

                        <div id="mdls">

							<?php

                            if ($row['brand_id'] != "0") {

                                $sql = 'select ok.id,ok.model,c.model_id from(

select * from ' . IMEI_MODEL_MASTER . ' b

where b.brand=' . $row['brand_id'] . ') ok

left join ' . IMEI_MODEL_MASTER_2 . ' c on ok.id=c.model_id  where ok.`status`=1 order by ok.id';

                                $result = $mysql->getResult($sql);

                                $msgid = '';

                                if ($result['COUNT']) {



                                    $chat_converstaion = '<div class="">

<table class="table table-striped table-hover panel" id="modelss">

<tr>

    <td><label class="c-input c-checkbox"><input type="checkbox" id="mine" value="1" onclick="ok()"><span class="c-indicator c-indicator-danger"></span></td>

    <td>' . $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_id')) .'</td>

    <td>'.$admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_models')) .'</td>

</tr>';

                                    foreach ($result['RESULT'] as $row2) {



                                        $chat_converstaion.=' 

    <tr>

    <td width="25" class="text-center" ><label class="c-input c-checkbox"><input type="checkbox" name="models[]" ' . (($row2['id'] == $row2['model_id']) ? 'checked="checked"' : '') . ' value="' . $row2['id'] . '" class="subSelect"><span class="c-indicator c-indicator-danger"></span></td>

    <td>' . $row2['id'] . '</td>

    <td>' . $row2['model'] . '</td>

</tr>';

                                    }

                                    $chat_converstaion.='</table></div>';

                                    echo $chat_converstaion;

                                    // exit;

                                }

                            }

                            ?>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-4">

                	<div class="p-t-20">

<!--                    	<div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download_link')); ?></label>

                            <input name="download_link" type="text" class="form-control" id="download_link" value="<?php echo $mysql->prints($row['download_link']); ?>" />

                        </div>-->

<!--                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_faq')); ?></label>

                            <select name="faq_id" class="form-control">

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

                        </div>-->

                        <div class="form-group">

                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service_information')); ?></label>

                            <textarea id="editor1"  class="ckeditor"  name="info"><?php echo $row['info']; ?></textarea>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-5">

                	<div class="p-t-20">

                    	<?php

                            //defined("_VALID_ACCESS") or die("Restricted Access");

                            //$validator->formSetAdmin('services_imei_tools_users_1812jdf18196');



                            $tool_id = $request->getInt('id');



                            $sql = 'select um.id,itu.tool_id, um.username

			from ' . USER_MASTER . ' um 

			left join ' . IMEI_TOOL_USERS . ' itu on(um.id=itu.user_id and itu.tool_id=' . $tool_id . ')

			left join ' . IMEI_TOOL_MASTER . ' tm on(itu.tool_id=tm.id)

			order by um.username';

                            $query = $mysql->query($sql);

                            $i = 1;

                            ?>

                            <input type="hidden" name="tool_id" value="<?php echo $tool_id; ?>" >

                            <table class="table table-striped table-hover">

                                <tr>

                                    <th width="16"></th>

                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>

                                   <th width="35px"><label class="c-input c-checkbox"><input type="checkbox" class="autoCheckAll" data-class="autoCheckMe" /><span class="c-indicator c-indicator-success"></span></label></th>


                                </tr>

                                <?php

                                if ($mysql->rowCount($query) > 0) {

                                    $rows = $mysql->fetchArray($query);

                                    foreach ($rows as $row) {

                                        echo '<tr class="' . (($row['tool_id'] != '') ? 'success' : '') . '">';

                                        echo '<td>' . $i++ . '</td>';

                                        echo '<td>' . $row['username'] . '</td>';



                                        echo '<td class="text_right">

											<label class="c-input c-checkbox">

												<input type="checkbox" class="autoCheckMe"  name="check_user[]" ' . (($row['tool_id'] != 0) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '"/>

												<span class="c-indicator c-indicator-default"></span>

                                    			<span class="c-input-text"></span>

											</label>

											<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />

										</td>';

                                        echo '</tr>';

                                    }

                                } else {

                                    echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

                                }

                                ?>

                            </table>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-6">

                	<div class="p-t-20">

                    	<?php

                            //defined("_VALID_ACCESS") or die("Restricted Access");

                            //$validator->formSetAdmin('services_imei_tools_users_1812jdf18196');



                            $tool_id = $request->getInt('id');



                            $sql = 'select

				um.id, um.username, um.currency_id,

				itad.amount,

				isc.amount spl_amount,

				cm.currency, cm.prefix, cm.suffix

			from ' . USER_MASTER . ' um 

			left join ' . IMEI_SPL_CREDITS . ' isc on(um.id=isc.user_id and isc.tool_id=' . $tool_id . ')

			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)

			left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=' . $tool_id . ' and um.currency_id = itad.currency_id)

                        where um.reseller_id = 0    

			order by um.username ';

                            $result = $mysql->getResult($sql);

                            $i = 1;

                            ?>

                            <table class="table table-striped table-hover">

                                <tr>

                                    <th></th>

                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>

                                    <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>

                                    <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>

                                    <th width="16"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remove')); ?></th>

                                </tr>

                                <?php

                                if ($result['COUNT']) {

                                    foreach ($result['RESULT'] as $row) {

                                        echo '<tr class="' . (($row['spl_amount'] != '') ? 'danger' : '') . '">';

                                        echo '<td>' . $i++ . '</td>';

                                        echo '<td><b>' . $row['username'] . '</b></td>';

                                        echo '<td>' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</td>';

                                        echo '<td>

										<div class="input-group">

											<span class="input-group-addon">' . $row['prefix'] . '</span>

											<input type="text" class="form-control ' . (($row['spl_amount'] != '') ? 'textbox_highlight' . (($row['spl_amount'] > $row['amount']) ? 'R noEffect' : '') : '') . '" style="width:80px" name="spl_' . $row['id'] . '" value="' . $row['spl_amount'] . '" />

										</div>

									  </td>';

                                        echo '<td class="text_right">

										<label class="c-input c-checkbox">

											<input type="checkbox"  name="check_user2[]" ' . ' value="' . $row['id'] . '"/>

											<span class="c-indicator c-indicator-default"></span>

                                    		<span class="c-input-text"></span>

										</label>

										<input type="hidden" name="user_ids2[]" value="' . $row['id'] . '" />

										<input type="hidden" name="currency_id' . $row['id'] . '" value="' . $row['currency_id'] . '" />

									</td>';

                                        echo '</tr>';

                                    }

                                } else {

                                    echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

                                }

                                ?>

                            </table>

                    </div>

                </div>

            </div>

        </div>

        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

        <input type="submit" onclick="return checkval();" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_unlocking_tool')); ?>" class="btn btn-success" />

        </div>

    </div>

</form>

<script type="text/javascript">

	setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN; ?>');

	function outputUpdateRange(vol) {

		document.querySelector('#r_id').value = vol;

	}
        function checkval()
{
    //alert($('select#my_multiselect').val)
    var countries = [];
        $.each($("#group_id option:selected"), function(){            
            countries.push($(this).val());
        });
       if(countries.length > 0)
           return true;
       else
       {
           alert('SELECT SERVICE GROUP FIRST');
           return false;
       }
}

</script>
<script>
$(document).ready(function() {
//$('#group_id').multiselect();
 $('#group_id').multiselect({
           
            maxHeight: 200,
              buttonWidth: '400px'
        });
});
</script>

<?php $url1 = CONFIG_PATH_SITE_ADMIN . 'service_imei_reset_discount_proccess.html'; ?>

<?php $url2 = CONFIG_PATH_SITE_ADMIN . 'service_imei_rate_calcuatios.html'; ?>

<script>

	function reset_discount(r){

		$("#btn_dis" + r).val('wait.....');

        //alert(r);

        $.ajax({

            url: '<?php echo $url1; ?>',

            data: {id: r},

            success: function (data) {



                // $("#key").val(data);

                $("#btn_dis" + r).hide();

                window.location.reload();

                // $("#btn_reg").val('Regenerate API Key');



                //change_log_id();

            }

        });

    }



    function ok(){

		if ($('#mine').is(":checked")){

            $('.subSelect').each(function () { //loop through each checkbo

                this.checked = true;  //select all checkboxes with class "checkbox1"               

            });

        }else{

            $('.subSelect').each(function () { //loop through each checkbox

                this.checked = false; //deselect all checkboxes with class "checkbox1"                       

            });

        }

    }

</script>



<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>

<script type="text/javascript">

    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

    document.getElementById('editor1').value = '<?php echo $mainbody; ?>';

function apendtbl()
{
    
    var api_id=$( "#parent2" ).val();
    var api_id_name=$( "#parent2 option:selected" ).text();
    var api_id_2=$( "#child2" ).val();
    var api_id_name_2=$( "#child2 option:selected" ).text();
    var p_adj=$( "#p_adj").val();
    //alert('a');
    if(api_id>0 && api_id_2>0)
    {
        
    $('#tblapip').append('<tr><input type="hidden" value="'+api_id+'" name="one[]"><td><input type="hidden" value="'+api_id_name+'" name="two[]">'+api_id_name+'<\/td><td><input type="hidden" value="'+api_id_2+'" name="three[]"><input type="hidden" value="'+api_id_name_2+'" name="four[]">'+api_id_name_2+'<\/td><td><input type="hidden" value="'+p_adj+'" name="five[]">'+p_adj+'<\/td><td><input type="button" value="-" class="btn btn-danger" onclick="deleteRow2(this)"><\/td></tr>');
    //api_pri_array
    }
}
    function calculaterate(e){

        var id = e.id;

        var cur_id = id.substring(7);

        var value = $('#' + id).val();

        //  alert($('#'+id).val());

        // alert(cur_id);



        $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {

            /* data will hold the php array as a javascript object */

            $.each(data, function (key, val) {

                $('#amount_' + val.id).val(val.valuee);

                $('#amount_' + val.id).attr('value', val.valuee);

                $('#amount_' + val.id).html(val.valuee);



                //document.getElementById("amount_" + val.id).html =val.valuee;

                //$('#amount_'+key)

                //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');

            });

        });

    }

    function calculaterate2(e){

        var id = e.id;

        var cur_id = id.substring(16);

        var value = $('#' + id).val();

        //  alert($('#'+id).val());

        // alert(cur_id);



        $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {

            /* data will hold the php array as a javascript object */

            $.each(data, function (key, val) {

                $('#amount_purchase_' + val.id).val(val.valuee);

                // $('#amount_' + val.id).attr('value', val.valuee);

                //$('#amount_' + val.id).html(val.valuee);



                //document.getElementById("amount_" + val.id).html =val.valuee;

                //$('#amount_'+key)

                //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');

            });

        });





//                                                                $.ajax({

//                                                                    type: "POST",

//                                                                    

//                                                                    // url: '<?php echo $url2; ?>',

//                                                                url: config_path_site_admin + "service_imei_rate_calcuatios.do",

//                                                                data: "&curId=" + cur_id + "&curval=" + value,

//                                                                success: function (msg) {

//                                                                  //  alert(msg);

//                                                                    //  $('#uid'+a).css('background', 'yellow');

//                                                                    $('#chat_panel_data').html(msg);

//                                                                }

//                                                            });

    }

    function getModels(e){

        var service =<?php echo $id; ?>;

        var brand = e.value;

        // alert(e.value);

        // exit;

        if (brand != 0){

            $.ajax({

                type: "POST",

                url: config_path_site_admin + "_ajax_get_models.do",

                //  data: 'page=' + url,

                data: "&srvc=" + service + "&brand=" + brand,

                dataType: "html",

                success: function (msg) {

                    //alert(msg);

                    if (parseInt(msg) != 0)

                    {

                        $('#mdls').html('');

                        $('#mdls').append(msg);

                        //$('#loading').css('visibility','hidden');

                    }

                }



            });

        }else{

            $('#mdls').html('');

        }

    }

</script>

<script type="text/javascript">

    $("#search2").on("keyup", function () {

        var value = $(this).val();



        $("table tr").each(function (index) {

            if (index !== 0) {



                $row = $(this);



                var id = $row.find("td:nth-child(3)").text();



                if (id.indexOf(value) !== 0) {

                    $row.hide();

                }

                else {

                    $row.show();

                }

            }

        });

    });

</script>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.js"></script>

<script type="text/javascript">var myJQ = $.noConflict(true);</script>

<script type="text/javascript">

var custom_1='<?php echo $custom_2;?>';
if(custom_1!=2)
    myJQ("#custom_fld").hide();

function showdiv(a)
{
    if(a==1)
        myJQ("#custom_fld").show();
    else
        myJQ("#custom_fld").hide();
}
    function makeclone(){

        var clonex = document.getElementById("mainaccount").cloneNode(true);

        myJQ("#kaka tr:last td:nth-child(3)").append(clonex);



    }
    
      var plimit =<?php echo $p_limit; ?>;
//alert(apiaccess);
    if (plimit == 0)
        $("#p_limit").hide();
    
     var plimit2 =<?php echo $show_providers; ?>;
//alert(apiaccess);
    if (plimit2 == 0)
        $("#s_pro").hide();

    function addnewrow(){



        //var mainncounter = 1;

        var rowCount = $('#kaka tr').length;

        var count = myJQ("#kaka tr:last td:nth-child(1)").text();

        //count=count+1;

        var mainncounter = parseInt(count);

        mainncounter = mainncounter + 1;

        if(rowCount <= 5){

            myJQ("#kaka").append(

                    "<tr>" +

                    "<td id=tblr" + mainncounter + "c0>" + rowCount + "</td>" +

                    " <td id=tblr" + mainncounter + "c1><select class='form-control'  name='f_type[]' style='width: 100px' onchange='setfop(this);'><option value='1'>Text</option> <option value='2'>Drop Down</option> <option value='3'>Checkbox</option></select></td>" +

                    "<td id=tblr" + mainncounter + "c2><input type='text' name='f_name[]'  class='form-control'/></td>" +

                    //  "  <td><select name='accountlist[]' class='form-control'><option value='Account One'>Account One</option><option value='Account Two'>Account Two</option></select></td> " +

                    //  "<td><select name='acc_id[]'><option value='1'>Excess UK</option><option value='2'>Excess EUR</option><option value='3'>test account new one</option></select></td>" +

                    " <td id=tblr" + mainncounter + "c3><input type='text' name='f_desc[]' class='form-control'/></td>" +

                    "  <td id=tblr" + mainncounter + "c4><input id='tblr" + mainncounter + "c4fop' type='text' name='f_opt[]' title='Add Options Separated with commas' readonly='readonly' class='form-control'/></td>" +

                    //  "<td>"+fname+"</td>" +

                    //   "<td> <input type='text' name='fc[]'class='form-control'/></td>" +

                    //    "<td> <input type='text' name='rate[]'class='form-control'/> </td>" +

                    "  <td id=tblr" + mainncounter + "c5><input type='text' id='tblr" + mainncounter + "c5fvalid' name='f_valid[]'  class='form-control'/></td>" +

                    //     "  <td id=tblr" + mainncounter + "c6><input type='text' name='rate[]' id=tblr" + mainncounter + "c6tb class='form-control' disabled='true' /><input type='hidden' name='min[]' id='tblr"+mainncounter+"c6tbhidmin'/><input type='hidden' name='max[]' id='tblr"+mainncounter+"c6tbhidmax'/></td>" +

                    // "  <td id=tblr" + mainncounter + "c6><input type='text' value='0' name='rate[]' onblur='sumuplc(this);' onkeypress='return isNumberKey(event)'  id=tblr" + mainncounter + "c6tb class='' readonly='true' /><span class='glyphicon glyphicon-arrow-up' style='color:green;font-size: 9px' id='tblr" + mainncounter + "c6tbhidmin'></span><span  id='tblr" + mainncounter + "c6tbhidmax' class='glyphicon glyphicon-arrow-down' style='color:red;font-size: 9px'></span> <input type='hidden' name='rule[]' id='tblr" + mainncounter + "c6tbhidrule'/></td>" +

                    "<td id=tblr" + mainncounter + "c7><label class='c-input c-checkbox'><input type='checkbox' onclick='updatecheck(this);' name='f_req1[]'/><input id='chk_" + mainncounter + "' name='f_req2[]' type='hidden' value='0'><span class='c-indicator c-indicator-default'></span></label></td>" +

                    "<td id=tblr" + mainncounter + "c8><input type='button' value='-' class='btn btn-danger' onclick='deleteRow(this)'></td>" +

                    "</tr>");

            //    oko();

            // alert(count);

            makeclone();

        }else{

            alert("Sorry you cant add more fields");

        }

    }

    function deleteRow(r) {

        //var i = r.parentNode.parentNode.rowIndex;

        //document.getElementById("kaka").deleteRow(i);

        myJQ("#kaka tr:last").remove();

    }
     function deleteRow2(r) {

        //var i = r.parentNode.parentNode.rowIndex;

        //document.getElementById("kaka").deleteRow(i);
        myJQ(r).closest('tr').remove();
      //  myJQ("#tblapip tr:last").remove();

    }

    function updatecheck(r) {

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
    
     function change(p) {

        if ($(p).prop("checked") == true) {

            $("#p_limit").show();
        } else {

            $("#p_limit").hide();
        }
    }

</script>

<script type="text/javascript">
   // setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

    function calculaterateok(e) {
        var id = e.id;
        var cur_id = id.split('_')[2];
        var value = $('#' + id).val();
        var seviceid = id.split('_')[1];
        //var seviceid = id.substring(6,2);
        //alert(seviceid);
         // alert(cur_id);

        if (value != '') {
            $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {
                /* data will hold the php array as a javascript object */
                $.each(data, function (key, val) {
                    $('#amountsg_' + seviceid + '_' + val.id).val(val.valuee);
                    //  $('#amount_' + val.id).attr('value', val.valuee);
                    //$('#amount_' + val.id).html(val.valuee);
                });
            });
        }
    }
</script>
<script>
	$(function () {
  $('[data-toggle="tooltip"]').tooltip()
                   })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
  $('#parent').select2();
  $('#parent2').select2();
   $('#child').select2();
   $('#child2').select2();
   
   
   function change2(p) {

        if ($(p).prop("checked") == true) {

            $("#s_pro").show();
        } else {

            $("#s_pro").hide();
        }
    }
   
</script>
<?php $url1 = CONFIG_PATH_ADMIN . '_ajax_load_api_details.php' ?>
<script type="text/javascript">

    /**
     * A Javascript module to loadeding/refreshing options of a select2 list box using ajax based on selection of another select2 list box.
     *
     * @url : https://gist.github.com/ajaxray/187e7c9a00666a7ffff52a8a69b8bf31
     * @auther : Anis Uddin Ahmad <anis.programmer@gmail.com>
     *
     * Live demo - https://codepen.io/ajaxray/full/oBPbQe/
     * w: http://ajaxray.com | t: @ajaxray
     */
    var Select2Cascade = (function (window, $) {

        function Select2Cascade(parent, child, url, options) {
            var afterActions = [];

            // Register functions to be called after cascading data loading done
            this.then = function (callback) {
                afterActions.push(callback);
                return this;
            };

            parent.select2(options).on("change", function (e) {

               // child.prop("disabled", true);
                 $("#child option").remove();
                var _this = this;

                $.getJSON(url.replace(':parentId:', $(this).val()), function (items) {
                    var newOptions = '<option value="">Select One</option>';
                  
                    for (var id in items) {
                         // console.log(items[id]['service_id']);
                        newOptions += '<option value="' + items[id]['service_id'] + '">' + items[id]['service_name']+ '</option>';
                    }

                    child.select2('destroy').html(newOptions).prop("disabled", false)
                            .select2(options);

                    afterActions.forEach(function (callback) {
                        callback(parent, child, items, options);
                    });
                });
            });
        }

        return Select2Cascade;

    })(window, $);
    var  url="<?php echo $url1; ?>";
  //  var cascadLoading = new Select2Cascade($('#parent'), $('#child'), +url+'?parent_id=:parentId:');
    var cascadLoading = new Select2Cascade($('#parent'), $('#child'), url+'?parent_id=:parentId:');
    
    
        var Select2Cascade = (function (window, $) {

        function Select2Cascade(parent2, child2, url, options) {
            var afterActions = [];

            // Register functions to be called after cascading data loading done
            this.then = function (callback) {
                afterActions.push(callback);
                return this;
            };

            parent2.select2(options).on("change", function (e) {

               // child2.prop("disabled", true);
               $("#child2 option").remove();
                var _this = this;

                $.getJSON(url.replace(':parentId:', $(this).val()), function (items) {
                    var newOptions = '<option value="">Select One</option>';
                  
                    for (var id in items) {
                         // console.log(items[id]['service_id']);
                        newOptions += '<option value="' + items[id]['service_id'] + '">' + items[id]['service_name']+ '</option>';
                    }

                    child2.select2('destroy').html(newOptions).prop("disabled", false)
                            .select2(options);

                    afterActions.forEach(function (callback) {
                        callback(parent2, child2, items, options);
                    });
                });
            });
        }

        return Select2Cascade;

    })(window, $);
    var  url="<?php echo $url1; ?>";
  //  var cascadLoading = new Select2Cascade($('#parent'), $('#child'), +url+'?parent_id=:parentId:');
    var cascadLoading = new Select2Cascade($('#parent2'), $('#child2'), url+'?parent_id=:parentId:');
</script>